<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un Administrador
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'rol' => User::ROL_ADMIN,
        ]);

        // Crear un Profesor
        User::create([
            'name' => 'Profesor User',
            'email' => 'profesor@example.com',
            'password' => Hash::make('password'),
            'rol' => User::ROL_PROFESOR,
        ]);

        // Crear un Estudiante
        User::create([
            'name' => 'Estudiante User',
            'email' => 'estudiante@example.com',
            'password' => Hash::make('password'),
            'rol' => User::ROL_ESTUDIANTE,
        ]);
    }
}