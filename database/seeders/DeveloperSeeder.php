<?php

namespace Database\Seeders;

use App\Enums\DeveloperRole;
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
                'role_title' => DeveloperRole::FullstackLead,
                'avatar' => 'RP',
                'avatar_bg' => 'bg-indigo-600',
                'is_active' => true,
            ],
            [
                'name' => 'Dina Kusuma',
                'email' => 'dina.kusuma@bugtracker.test',
                'role_title' => DeveloperRole::Backend,
                'avatar' => 'DK',
                'avatar_bg' => 'bg-emerald-600',
                'is_active' => true,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@bugtracker.test',
                'role_title' => DeveloperRole::Frontend,
                'avatar' => 'BS',
                'avatar_bg' => 'bg-amber-600',
                'is_active' => true,
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti.rahma@bugtracker.test',
                'role_title' => DeveloperRole::Mobile,
                'avatar' => 'SR',
                'avatar_bg' => 'bg-rose-600',
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
