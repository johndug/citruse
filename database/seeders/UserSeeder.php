<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
        ]);

        // Create manager users
        for ($i = 0; $i < 3; $i++) {
            User::create([
                'name' => "Purchasing Manager 0{$i}",
                'email' => "purchasing0{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => UserRole::MANAGER,
            ]);
        }
    }
}
