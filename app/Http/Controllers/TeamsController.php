<?php

namespace App\Http\Controllers;

use App\Models\TableMatch;
use App\Models\Teams;
use App\Models\TeamsUser;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Vinkla\Hashids\Facades\Hashids;

class TeamsController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        if ($user->rol_id === 3) {
            $team = Teams::where('capitan_id', $user->id)->get();

            return view('teams.index', compact('team'));
        } elseif ($user->rol_id !== 3) {
            $teams = Teams::with('capitan', 'players')->get();

            return view('teams.index', compact('teams'));
        }
        $teams = Teams::with('capitan', 'players')->get();

        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        $capitans = User::leftJoin('teams', 'users.id', '=', 'teams.capitan_id')
            ->where('users.rol_id', 3)
            ->whereNull('teams.id')  // Filter users without a team
            ->select('users.*')
            ->get();

        return view('teams.create', compact('capitans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'unique:' . Teams::class,],
            'acronym' => ['required', 'string', 'max:4', 'unique:' . Teams::class,],
            'team' => ['required', 'file'],
            'capitan_id' => ['required', 'integer', 'exists:' . User::class . ',id'],
        ]);

        if ($request->file('team')) {
            $team = 'team/' . str_replace(" ", "_", $request->name) . '_' . date('Y-m-d') . '_' . $request->file('team')->getClientOriginalName();
            $team = $request->file('team')->storeAs('public', $team);
            $team = str_replace("public/", "", $team);
        }

        $team = Teams::create([
            'name' => $request->name,
            'acronym' => $request->acronym,
            'team' => $team,
            'capitan_id' => $request->capitan_id
        ]);

        TableMatch::create([
            'team_id' => $team->id,
            'matches' => 0,
            'wins' => 0,
            'loses' => 0,
            'draw' => 0,
            'points' => 0,
            'goals_team' => 0,
            'goals_conceded' => 0
        ]);

        return redirect()->route('teams.index')->with('status', __('Team Created Successfully!'));
    }

    public function show($id): View
    {
        $id = Hashids::decode($id);
        $team = Teams::with('capitan', 'players')->find($id);
        $team=$team[0];

        return view('teams.show', compact('team'));
    }

    public function showJson($id): JsonResponse
    {
        $team=Teams::withTrashed()->find($id);

        return response()->json($team);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $id = Hashids::decode($id);
        $capitans = User::leftJoin('teams', 'users.id', '=', 'teams.capitan_id')
            ->where('users.rol_id', 3)
            ->whereNull('teams.id')  // Filter users without a team
            ->select('users.*')
            ->get();

        $team = Teams::find($id);
        $team=$team[0];

        return view('teams.edit', compact('capitans', 'team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $id = Hashids::decode($id);
        $request->validate([
            'name' => ['string', 'min:3'],
            'acronym' => ['string', 'max:4'],
            'team' => ['file'],
            'capitan_id' => ['integer', 'exists:' . User::class . ',id'],
        ]);

        $teams = Teams::find($id);
        $teams=$teams[0];

        if ($request->file('team')) {
            $team = 'team/' . str_replace(" ", "_", $request->name) . '_' . date('Y-m-d') . '_' . $request->file('team')->getClientOriginalName();
            $team = $request->file('team')->storeAs('public', $team);
            $team = str_replace("public/", "", $team);
            $teams->team=$team;
            $teams->save();
        }

        $teams->update($request->all());

        return redirect()->route('teams.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        try {
        $team=Teams::find($id);
        $team->delete();

        return redirect()->route('teams.index');
        }
        catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];

            if ($errorCode == 1451) {
                // Error de integridad referencial (clave foránea)
                return redirect()->route('teams.index')->with('statusError', 'No se puede eliminar el Equipo. Primero elimina los jugadores asociados');
            }

            // Otro tipo de error, puedes manejarlo según tus necesidades
            return redirect()->route('teams.index')->with('statusError', 'Error al eliminar el Equipo: ' . $e->getMessage());
        }
    }

    public function addPlayerOfTeam(Request $request, $id)
    {
        $team = Teams::find($id);

//        $playerIds = explode(',', $request->player_id);

        $playerIds = $request->player_id;

        foreach ($playerIds as $playerId) {
            try {
                // Verifica si el jugador ya pertenece a otro equipo
                if (!$this->playerIsUnique($playerId)) {
                    continue; // Salta al siguiente jugador si no es único
                }

                $team->players()->attach($playerId);
            } catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];

                if ($errorCode == 1451) {
                    // Error de integridad referencial (clave foránea)
                    return redirect()->route('teams.index')->with('statusError', 'No se puede eliminar el Equipo. Primero elimina los jugadores asociados');
                }

                // Otro tipo de error, puedes manejarlo según tus necesidades
                return redirect()->route('teams.index')->with('statusError', 'Error al eliminar el Equipo: ' . $e->getMessage());
            }
        }

        $team = Teams::with('players')->find($id);

        return redirect()->route('teams.index')->with('messageDelete', 'Equipo Eliminado Correctamente');
    }

    private function playerIsUnique($playerId)
    {
        $playerIsUnique = true; // Asumir que el jugador es único

        // Realiza la consulta para verificar si el jugador ya pertenece a otro equipo
        $teamsUsers = TeamsUser::where('user_id', $playerId)->get();

        if ($teamsUsers->count() > 0) {
            // El jugador ya pertenece a otro equipo
            throw new Exception("El jugador ya pertenece a otro equipo");
        }

        return $playerIsUnique;
    }
}
