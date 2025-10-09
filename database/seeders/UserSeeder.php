<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Owner Utama',
            'email' => 'owner@example.com',
            'password' => ('password'),
            'role' => 'owner'
        ]);

        User::create([
            'name' => 'Admin Satu',
            'email' => 'admin@example.com',
            'password' => ('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Kasir Satu',
            'email' => 'kasir@example.com',
            'password' => ('password'),
            'role' => 'kasir'
        ]);
    }
}
