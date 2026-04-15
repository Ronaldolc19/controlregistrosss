<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin_serviciosocial@admin.com', // Cambia esto por tu correo
            'password' => Hash::make('Serviciosocial.admin#'), // Usa una contraseña segura
            'email_verified_at' => now(),
        ]);
    }
}
