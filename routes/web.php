<?php

namespace App\Http\Controllers;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
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

});

require __DIR__.'/auth.php';
