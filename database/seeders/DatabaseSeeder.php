<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Roles::create([
            'name' => 'Administrador'
        ]);

        Roles::create([
            'name' => 'Arbitro'
        ]);

        Roles::create([
            'name' => 'Capitan'
        ]);

        Roles::create([
            'name' => 'Jugador'
        ]);

        User::create([
            'name' => 'Administrador',
            'email' => 'admin@canchas.com',
            'password' => Hash::make('12345678'),
            'rol_id' => 1,
        ]);

        User::create([
            'name' => 'Arbitro',
            'email' => 'arbitro@canchas.com',
            'password' => Hash::make('12345678'),
            'rol_id' => 2,
        ]);
    }
}
