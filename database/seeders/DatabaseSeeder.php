<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@mass.com',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Usuario',
            'email' => 'usuario@mass.com',
            'password' => Hash::make('usuario12345'),
            'role' => 'usuario',
        ]);
    }
}