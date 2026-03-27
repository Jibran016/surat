<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Notification;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');
        $now = now();

        $divisions = [
            ['name' => 'Technical & Operation', 'unit_code' => '1000'],
            ['name' => 'Technical & Service', 'unit_code' => '1100'],
            ['name' => 'Production', 'unit_code' => '1200'],
            ['name' => 'HSE & QM', 'unit_code' => '1300'],
            ['name' => 'Legal', 'unit_code' => '1400'],
            ['name' => 'HR Development', 'unit_code' => '1700'],
            ['name' => 'Finance Operation', 'unit_code' => '1500'],
            ['name' => 'Procurement', 'unit_code' => '1600'],
        ];

        Division::query()
            ->whereNotIn('name', collect($divisions)->pluck('name')->all())
            ->delete();

        Division::upsert(
            collect($divisions)->map(fn (array $division) => [
                'name' => $division['name'],
                'unit_code' => $division['unit_code'],
                'created_at' => $now,
                'updated_at' => $now,
            ])->all(),
            ['name'],
            ['unit_code', 'updated_at']
        );

        $users = [
            ['username' => 'admin', 'division' => null, 'role' => 'Admin', 'email' => 'admin@suratin.local'],
            ['username' => 'Technical & Operation', 'division' => 'Technical & Operation', 'role' => 'User', 'email' => 'tech.operation@suratin.local'],
            ['username' => 'Technical & Service', 'division' => 'Technical & Service', 'role' => 'User', 'email' => 'tech.service@suratin.local'],
            ['username' => 'Production', 'division' => 'Production', 'role' => 'User', 'email' => 'production@suratin.local'],
            ['username' => 'HSE & QM', 'division' => 'HSE & QM', 'role' => 'User', 'email' => 'hseqm@suratin.local'],
            ['username' => 'Legal', 'division' => 'Legal', 'role' => 'User', 'email' => 'legal@suratin.local'],
            ['username' => 'HR Development', 'division' => 'HR Development', 'role' => 'User', 'email' => 'hrdev@suratin.local'],
            ['username' => 'Finance Operation', 'division' => 'Finance Operation', 'role' => 'User', 'email' => 'finance.operation@suratin.local'],
            ['username' => 'Procurement', 'division' => 'Procurement', 'role' => 'User', 'email' => 'procurement@suratin.local'],
        ];

        User::query()->update(['password' => $password]);

        foreach ($users as $item) {
            $existing = User::query()
                ->where('email', $item['email'])
                ->first();

            if (!$existing) {
                $existing = User::query()
                    ->where('username', $item['username'])
                    ->first();
            }

            if ($existing) {
                User::query()
                    ->where('id', '!=', $existing->id)
                    ->where('email', $item['email'])
                    ->update(['email' => null]);

                $usernameConflicts = User::query()
                    ->where('id', '!=', $existing->id)
                    ->where('username', $item['username'])
                    ->get();

                foreach ($usernameConflicts as $conflict) {
                    $fallback = substr($conflict->username, 0, 38) . '-old-' . $conflict->id;
                    $conflict->update(['username' => $fallback]);
                }

                $existing->update([
                    'email' => $item['email'],
                    'username' => $item['username'],
                    'division' => $item['division'],
                    'role' => $item['role'],
                    'password' => $password,
                ]);
            } else {
                User::create([
                    'email' => $item['email'],
                    'username' => $item['username'],
                    'division' => $item['division'],
                    'role' => $item['role'],
                    'password' => $password,
                ]);
            }
        }

        // Bersihkan data surat/notifikasi; surat dummy tidak lagi dibuat saat seeding.
        Notification::query()->delete();
        Surat::query()->delete();
    }
}
