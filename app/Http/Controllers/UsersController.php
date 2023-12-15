<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Vinkla\Hashids\Facades\Hashids;

class UsersController extends Controller
{
    public function index(): View
    {
        $arbitros=User::where('rol_id', 2)->get();
//        $capitanes=User::where('rol_id', 3)->with('team')->get();
        $capitanes = User::where('rol_id', 3)
            ->leftJoin('teams', 'users.id', '=', 'teams.capitan_id')
            ->select('users.*', 'teams.name as team_name', 'teams.team as team')
            ->get();

        return view('users.index', compact('arbitros', 'capitanes'));
    }

    public function create(): View
    {
        $roles=Roles::all();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'number' => ['nullable', 'integer'],
            'rol_id' => ['required', 'exists:' . Roles::class . ',id'],
            'photo' => ['nullable', 'file']
        ]);

        if ($request->file('photo')) {
            $photo = 'profile/' . str_replace(" ", "_", $request->name) . '_' . date('Y-m-d') . '_' . $request->file('photo')->getClientOriginalName();
            $photo = $request->file('photo')->storeAs('public', $photo);
            $photo = str_replace("public/", "", $photo);
        }


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'number' => $request->number,
            'photo' => $photo,
            'rol_id' => $request->rol_id
        ]);

        return redirect()->route('users.index');
    }

    public function show($id): View
    {
        $id=Hashids::decode($id);
        $user=User::find($id);
        $user=$user[0];

        return view('users.show', compact('user'));
    }

    public function showJson($id)
    {
        $user=User::withTrashed()->find($id);

        return response()->json($user);
    }

    public function edit($id)
    {
        $id=Hashids::decode($id);
        $user=User::find($id);
        $user=$user[0];

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'number' => ['nullable', 'integer'],
            'rol_id' => ['required', 'exists:' . Roles::class . ',id'],
        ]);


        $id=Hashids::decode($id);
        $user=User::find($id);
        $user=$user[0];

        $user->update($request->all());
        if ($request->file('photo')) {
            $request->validate([
                'photo' => ['file']
            ]);
            $photo = 'profile/' . str_replace(" ", "_", $request->name) . '_' . date('Y-m-d') . '_' . $request->file('photo')->getClientOriginalName();
            $photo = $request->file('photo')->storeAs('public', $photo);
            $photo = str_replace("public/", "", $photo);

            $user->photo=$photo;
            $user->save();

            return redirect()->route('users.index');
        }

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        try {
            $user=User::find($id);
            $user->delete();

            return redirect()->route('users.index');
        }
        catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];

            if ($errorCode == 1451) {
                // Error de integridad referencial (clave foránea)
                return redirect()->route('users.index')->with('statusError', 'No se puede eliminar el Usuario. Primero elimina al jugador del equipo asociados');
            }
            // Otro tipo de error, puedes manejarlo según tus necesidades
            return redirect()->route('users.index')->with('statusError', 'Error al eliminar el Usuario: ' . $e->getMessage());
        }
    }
}
