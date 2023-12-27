<?php

namespace App\Http\Controllers;

use App\Mail\SoccerMatchMail;
use App\Models\MatchUser;
use App\Models\SoccerMatches;
use App\Models\TableMatch;
use App\Models\Teams;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Vinkla\Hashids\Facades\Hashids;

class SoccerMatchesController extends Controller
{
    public function index(): View
    {
//        $soccer = SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->where('started', 0)->get();
        $soccer = Cache::remember('upcoming_soccer_matches', 60, function () {
            return SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->where('started', 0)->get();
        });

        $matches = Cache::remember('soccer_matches_played', 60, function () {
            return SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->where('started', 1)->get();
        });

//        dd($soccer);
        return view('matches.index', compact('soccer', 'matches'));
    }

    public function create(): View
    {
        $referees = User::where('rol_id', 2)->get();
        $teams = Teams::all();

        return view('matches.create', compact('teams', 'referees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'dayOfMatch' => ['required', 'date'],
            'team_local_id' => ['required', 'exists:' . Teams::class . ',id'],
            'team_visit_id' => ['required', 'exists:' . Teams::class . ',id', 'distinct:team_local_id'],
            'referee_id' => ['required', 'exists:' . User::class . ',id'],
        ]);

//        $dayOfMatchExists = SoccerMatches::where('dayOfMatch', $request->dayOfMatch)->exists();
        $dayOfMatchExists = SoccerMatches::where('dayOfMatch', $request->dayOfMatch)
            ->where(function ($query) use ($request) {
                $query->where('team_local_id', $request->team_local_id)
                    ->orWhere('team_visit_id', $request->team_local_id);
            })
            ->where('started', false)
            ->exists();

        if ($dayOfMatchExists) {
            return redirect()->back()->withErrors(['error' => __('The game schedule already has a match scheduled for that day.')]);
        }

        $teamLocalMatchesExist = SoccerMatches::where('dayOfMatch', $request->dayOfMatch)
            ->orWhere('team_visit_id', $request->team_local_id)
            ->where('team_local_id', $request->team_local_id)
            ->exists();

        if ($teamLocalMatchesExist) {
            return redirect()->back()->withErrors(['error' => __('The local team already has a match scheduled for that day.')]);
        }

        $teamVisitMatchesExist = SoccerMatches::where('dayOfMatch', $request->dayOfMatch)
            ->orWhere('team_local_id', $request->team_visit_id)
            ->where('team_visit_id', $request->team_visit_id)
            ->exists();

        if ($teamVisitMatchesExist) {
            return redirect()->back()->withErrors(['error' => __('The visiting team already has a match scheduled for that day')]);
        }

        if ($request->team_local_id === $request->team_visit_id) {
            return redirect()->back()->withErrors(['error' => __('The visiting team cannot be the same as the local team')]);
        }


        $match = SoccerMatches::create([
            'dayOfMatch' => $request->dayOfMatch,
            'team_local_id' => $request->team_local_id,
            'team_visit_id' => $request->team_visit_id,
            'referee_id' => $request->referee_id,
            'started' => false,
        ]);

        $team_local = Teams::with('capitan')->find($request->team_local_id);
        $user=$team_local->capitan;
        Mail::to($user->email)->send(new SoccerMatchMail($match, $user));

        $team_visit = Teams::with('capitan')->find($request->team_visit_id);
        $capitan=$team_visit->capitan;
        Mail::to($capitan->email)->send(new SoccerMatchMail($match, $capitan));

        return redirect()->route('matches.index');
    }

    public function show($id): View
    {
        $id = Hashids::decode($id);
        $match = SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->find($id);
        $match = $match[0];
        $team_local_users = $match->team_local->players;
        $team_visit_users = $match->team_visit->players;

        return view('matches.show', compact('match', 'team_local_users', 'team_visit_users'));
    }


    public function addGoalsFouls(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'team_local_goals' => ['required', 'integer'],
            'team_visit_goals' => ['required', 'integer'],
            'team_local_fouls' => ['required', 'integer'],
            'team_visit_fouls' => ['required', 'integer'],
        ]);

        $match = SoccerMatches::find($id);

        $match->update([
            'team_local_goals' => $request->team_local_goals,
            'team_visit_goals' => $request->team_visit_goals,
            'team_local_fouls' => $request->team_local_fouls,
            'team_visit_fouls' => $request->team_visit_fouls,
            'started' => true,
        ]);


        return redirect()->route('matches.show', $match->id);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function destroy($id)
    {
        $match = SoccerMatches::find($id);
        $match->delete();

        return redirect()->route('matches.index');
    }

    public function addGoalsTeam(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'player_id_local' => ['exists:' . User::class . ',id'],
            'player_id_visit' => ['exists:' . User::class . ',id'],
            'goals_local' => ['integer', 'nullable'],
            'goals_visit' => ['integer', 'nullable'],
        ]);

        $id = SoccerMatches::find($id);

        $winnerId = $id->ganador();

        // Si hay un ganador, sumarle 3 puntos
        if ($winnerId === $id->team_local_id) {
            $equipoGanador = TableMatch::where('team_id', $winnerId)->first();
            $equipoGanador->matches += 1;
            $equipoGanador->points += 3;
            $equipoGanador->wins += 1;
            $equipoGanador->goals_team = $equipoGanador->goals_team += ($winnerId === $id->team_local_id) ? $request->goals_local : $request->goals_visit;
            $equipoGanador->goals_conceded = $equipoGanador->goals_conceded += ($winnerId === $id->team_local_id) ? $request->goals_visit : $request->goals_local;
            $equipoGanador->save();

            $equipoPerdedor = TableMatch::where('team_id', ($winnerId === $id->team_visit_id) ? $id->team_local_id : $id->team_visit_id)->first();
            $equipoPerdedor->loses += 1;
            $equipoPerdedor->matches += 1;
            $equipoPerdedor->points += 0;
            $equipoPerdedor->goals_conceded = $equipoPerdedor->goals_conceded += ($winnerId === $id->team_local_id) ? $request->goals_local : $request->goals_visit;
            $equipoPerdedor->goals_team = $equipoPerdedor->goals_team += ($winnerId === $id->team_visit_id) ? $request->goals_local : $request->goals_visit;
            $equipoPerdedor->save();
        }
        elseif ($winnerId === $id->team_visit_id) {
            $equipoGanador = TableMatch::where('team_id', $winnerId)->first();
            $equipoGanador->goals_team = $equipoGanador->goals_team += ($winnerId === $id->team_visit_id) ? $request->goals_visit : $request->goals_local;
            $equipoGanador->matches += 1;
            $equipoGanador->points += 3;
            $equipoGanador->wins += 1;
            $equipoGanador->goals_conceded = $equipoGanador->goals_conceded += ($winnerId === $id->team_local_id) ? $request->goals_visit : $request->goals_local;
            $equipoGanador->save();

            $equipoPerdedor = TableMatch::where('team_id', ($winnerId === $id->team_local_id) ? $id->team_visit_id : $id->team_local_id)->first();
            $equipoPerdedor->goals_team = $equipoPerdedor->goals_team += ($winnerId === $id->team_local_id) ? $request->goals_visit : $request->goals_local;
            $equipoPerdedor->loses += 1;
            $equipoPerdedor->matches += 1;
            $equipoPerdedor->points += 0;
            $equipoPerdedor->goals_conceded = $equipoPerdedor->goals_conceded += ($winnerId === $id->team_visit_id) ? $request->goals_visit : $request->goals_local;
            $equipoPerdedor->save();
        }
        else {
            // Si hay un empate, sumarle 1 punto a cada equipo
            $local = TableMatch::where('team_id', $id->team_local_id)->first();
            $local->points += 1;
            $local->draw += 1;
            $local->goals_team = $local->goals_team += $request->goals_local;
            $local->goals_conceded = $local->goals_conceded += $request->goals_visit;
            $local->save();

            $visitante = TableMatch::where('team_id', $id->team_visit_id)->first();
            $visitante->points += 1;
            $visitante->draw += 1;
            $visitante->goals_team = $visitante->goals_team += $request->goals_visit;
            $visitante->goals_conceded = $visitante->goals_conceded += $request->goals_local;
            $visitante->save();
        }

        if ($request->player_id_local || $request->goals_local) {
            $id->addGoals()->attach($request->player_id_local);
            $id->team_local_goals = $request->goals_local;
            $id->save();
        }

        $id->addGoals()->attach($request->player_id_visit);
        $id->team_visit_goals = $request->goals_visit;
        $id->started = true;
        $id->save();

        $id=Hashids::encode($id->id);

        return redirect()->route('matches.show', $id);
    }

    public function goals($id): RedirectResponse
    {
        $id = SoccerMatches::find($id);
        $team_local = MatchUser::with('player')->where('soccerMatch_id', $id->id)->where('team_id', $id->team_local_id)->get();
        $team_visit = MatchUser::with('player')->where('soccerMatch_id', $id->id)->where('team_id', $id->team_visit_id)->get();

        return redirect()->route('matches.show', $id->id);
    }

    public function showJson($id): JsonResponse
    {
        $match = SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->find($id);

        return response()->json($match);
    }


    public function sendEmail(): RedirectResponse
    {
        $match = SoccerMatches::find(8);

        $team_local = Teams::with('capitan')->find($match->team_local_id);
        $user=$team_local->capitan;

        Mail::to($user->email)->send(new SoccerMatchMail($match, $user));

        return redirect()->route('matches.index');
    }
}
