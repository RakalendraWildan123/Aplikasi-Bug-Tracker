<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DeveloperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Atasan / Admin IT
        User::updateOrCreate(
            ['email' => 'admin@bugtracker.test'],
            [
                'name' => 'Kepala Divisi IT',
                'password' => 'password',
                'role' => 'admin',
                'role_title' => null,
                'avatar' => 'KD',
                'avatar_bg' => '#1E293B',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun Developer
        $developers = [
            [
                'name' => 'Raka Pratama',
                'email' => 'raka.pratama@bugtracker.test',
                'password' => 'password',
                'role' => 'developer',
                'role_title' => 'Fullstack Lead',
                'avatar' => 'RP',
                'avatar_bg' => '#4F46E5',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dina Kusuma',
                'email' => 'dina.kusuma@bugtracker.test',
                'password' => 'password',
                'role' => 'developer',
                'role_title' => 'Backend',
                'avatar' => 'DK',
                'avatar_bg' => '#10B981',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@bugtracker.test',
                'password' => 'password',
                'role' => 'developer',
                'role_title' => 'Frontend',
                'avatar' => 'BS',
                'avatar_bg' => '#F59E0B',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti.rahma@bugtracker.test',
                'password' => 'password',
                'role' => 'developer',
                'role_title' => 'Mobile',
                'avatar' => 'SR',
                'avatar_bg' => '#EF4444',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($developers as $developer) {
            User::updateOrCreate(
                ['email' => $developer['email']],
                $developer
            );
        }
    }
}
