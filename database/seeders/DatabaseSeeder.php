<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'username' => 'admin',
            'division' => null,
            'role' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'username' => 'user-hr',
            'division' => 'HR',
            'role' => 'User',
            'email' => 'hr@example.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'username' => 'user-keu',
            'division' => 'Keuangan',
            'role' => 'User',
            'email' => 'keu@example.com',
            'password' => 'password',
        ]);
    }
}
