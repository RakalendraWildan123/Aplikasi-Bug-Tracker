<?php

namespace Database\Seeders;

use App\Models\Developer;
use Illuminate\Database\Seeder;

class DeveloperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developers = [
            [
                'name' => 'Raka Pratama',
                'email' => 'raka.pratama@bugtracker.test',
                'password' => 'password',
                'role_title' => 'Fullstack Lead',
                'avatar' => 'RP',
                'avatar_bg' => '#4F46E5',
                'is_active' => true,
            ],
            [
                'name' => 'Dina Kusuma',
                'email' => 'dina.kusuma@bugtracker.test',
                'password' => 'password',
                'role_title' => 'Backend',
                'avatar' => 'DK',
                'avatar_bg' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@bugtracker.test',
                'password' => 'password',
                'role_title' => 'Frontend',
                'avatar' => 'BS',
                'avatar_bg' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti.rahma@bugtracker.test',
                'password' => 'password',
                'role_title' => 'Mobile',
                'avatar' => 'SR',
                'avatar_bg' => '#EF4444',
                'is_active' => true,
            ],
        ];

        foreach ($developers as $developer) {
            Developer::updateOrCreate(
                ['email' => $developer['email']],
                $developer
            );
        }
    }
}
