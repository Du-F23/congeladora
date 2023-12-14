<?php

namespace App\Http\Controllers;

use App\Models\MatchUser;
use App\Models\SoccerMatches;
use App\Models\Teams;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SoccerMatchesController extends Controller
{
    public function index()
    {
        $soccer = SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->thisWeek()->get();

//        dd($soccer);
        return view('matches.index', compact('soccer'));
    }

    public function create(): View
    {
        $referees = User::where('rol_id', 2)->get();
        $teams = Teams::all();

        return view('matches.create', compact('teams', 'referees'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $match = SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->find($id);

        return view('matches.show', compact('match'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function addGoalsTeam(Request $request, $id)
    {
            $request->validate([
                'player_id' => ['required', 'exists:' . User::class . ',id'],
                'goals' => ['required', 'integer']
            ]);
            $id = SoccerMatches::find($id);

            $match = MatchUser::create([
                'soccerMatch_id' => $id->id,
                'player_id' => $request->player_id,
                'goals' => $request->goals
            ]);

            return redirect()->route('matches.show', $match->id);
    }

    public function goals($id)
    {
        $id = SoccerMatches::find($id);
        $team_local = MatchUser::with('player')->where('soccerMatch_id', $id->id)->where('team_id', $id->team_local_id)->get();
        $team_visit = MatchUser::with('player')->where('soccerMatch_id', $id->id)->where('team_id', $id->team_visit_id)->get();

        return redirect()->route('matches.show', $id->id);
    }
}
