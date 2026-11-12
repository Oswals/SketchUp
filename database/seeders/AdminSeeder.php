<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'Admin Principal',
            'email' => 'admin@example.com', // ✅ Changez ceci
            'password' => Hash::make('Admin@2025'), // ✅ Changez le mot de passe
            'role' => 'admin',
            'status' => 1,
            'clean_password' => false,
            'coin_balance' => 999999, // Admin a coins illimités
        ]);
        
        \Log::info('✅ Admin créé avec succès');
    }
}