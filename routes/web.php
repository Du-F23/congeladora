<?php

namespace App\Http\Controllers;

use App\Models\SoccerMatches;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (!Auth::check()){
    $soccer = SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->thisWeek()->get();

    return view('welcome', compact('soccer'));
    }
    return redirect()->route('dashboard');
})->name('welcome');

Route::get('/dashboard', function () {
    $soccer = SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->thisWeek()->get();
    return view('dashboard', compact('soccer'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/teams', [TeamsController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamsController::class, 'create'])->name('teams.create');
    Route::post('/teams/store', [TeamsController::class, 'store'])->name('teams.store');
    Route::get('/teams/{id}', [TeamsController::class, 'show'])->name('teams.show');
    Route::get('/teams/{id}/json', [TeamsController::class, 'showJson'])->name('teams.json');
    Route::get('/teams/{id}/edit', [TeamsController::class, 'edit'])->name('teams.edit');
    Route::patch('/teams/{id}/update', [TeamsController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{id}/delete', [TeamsController::class, 'destroy'])->name('teams.delete');
    Route::post('/teams/{id}/addPlayers', [TeamsController::class, 'addPlayerOfTeam'])->name('teams.add');

    Route::get('/soccer-matches', [SoccerMatchesController::class, 'index'])->name('matches.index');
    Route::get('/soccer-matches/create', [SoccerMatchesController::class, 'create'])->name('matches.create');
    Route::post('/soccer-matches/store', [SoccerMatchesController::class, 'store'])->name('matches.store');
    Route::get('/soccer-matches/{id}', [SoccerMatchesController::class, 'show'])->name('matches.show');
    Route::get('/soccer-matches/{id}/json', [SoccerMatchesController::class, 'showJson'])->name('matches.json');
    Route::get('/soccer-matches/{id}/edit', [SoccerMatchesController::class, 'edit'])->name('matches.edit');
    Route::delete('/soccer-matches/{id}/delete', [SoccerMatchesController::class, 'destroy'])->name('matches.delete');
    Route::patch('/soccer-matches/{id}/addFouls', [SoccerMatchesController::class, 'addGoalsFouls'])->name('matches.add_goals');
    Route::post('/soccer-matches/{id}/crete-goals', [SoccerMatchesController::class, 'addGoalsTeam'])->name('matches.team_goals');
    Route::get('/soccer-matches/{id}/goals', [SoccerMatchesController::class, 'goals'])->name('matches.goals');

});

require __DIR__ . '/auth.php';
