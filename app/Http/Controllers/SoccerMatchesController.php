<?php

namespace App\Http\Controllers;

use App\Models\MatchUser;
use App\Models\SoccerMatches;
use App\Models\Teams;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Vinkla\Hashids\Facades\Hashids;

class SoccerMatchesController extends Controller
{
    public function index(): View
    {
        $soccer = SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->thisWeek()->get();

//        dd($soccer);
        return view('matches.index', compact('soccer'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'dayOfMatch' => ['required', 'date'],
            'team_local_id' => ['required', 'exists:' . Teams::class . ',id'],
            'team_visit_id' => ['required', 'exists:' . Teams::class . ',id'],
            'referee_id' => ['required', 'exists:' . User::class . ',id'],
        ]);


        $dayOfMatchExists = SoccerMatches::where('dayOfMatch', $request->dayOfMatch)->exists();

        if ($dayOfMatchExists) {
            return redirect()->back()->withErrors(['error' => __('The game schedule already has a match scheduled for that day.')]);
        }

        $teamLocalMatchesExist = SoccerMatches::where('team_local_id', $request->team_local_id)
            ->where('dayOfMatch', $request->dayOfMatch)
            ->exists();

        if ($teamLocalMatchesExist) {
            return redirect()->back()->withErrors(['error' => __('The local team already has a match scheduled for that day.')]);
        }

        $teamVisitMatchesExist = SoccerMatches::where('team_visit_id', $request->team_visit_id)
            ->where('dayOfMatch', $request->dayOfMatch)
            ->exists();

        if ($teamVisitMatchesExist) {
            return redirect()->back()->withErrors(['error' => 'The visiting team already has a match scheduled for that day']);
        }


        SoccerMatches::create([
            'dayOfMatch' => $request->dayOfMatch,
            'team_local_id' => $request->team_local_id,
            'team_visit_id' => $request->team_visit_id,
            'referee_id' => $request->referee_id,
            'started' => false,
        ]);

        return redirect()->route('matches.index');
    }

    public function create(): View
    {
        $referees = User::where('rol_id', 2)->get();
        $teams = Teams::all();

        return view('matches.create', compact('teams', 'referees'));
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

    public function show($id): View
    {
        $id = Hashids::decode($id);
        $match = SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->find($id);
        $match = $match[0];
        $team_local_users = $match->team_local->players;
        $team_visit_users = $match->team_visit->players;

        return view('matches.show', compact('match', 'team_local_users', 'team_visit_users'));
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

//            dd($request->all());
        $id = SoccerMatches::find($id);

        if ($request->player_id_local || $request->goals_local) {
            $id->addGoals()->attach($request->player_id_local);
            $id->team_local_goals = $request->goals_local;
            $id->save();
        }

        $id->addGoals()->attach($request->player_id_visit);
        $id->team_visit_goals = $request->goals_visit;
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
}
