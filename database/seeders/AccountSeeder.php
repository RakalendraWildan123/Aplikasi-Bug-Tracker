<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Developer::query()->count() < 4) {
            $this->call(DeveloperSeeder::class);
        }

        // Akun Atasan Developer / IT Manager (Admin)
        User::updateOrCreate(
            ['email' => 'admin@bugtracker.test'],
            [
                'name' => 'Kepala Divisi IT',
                'password' => 'password',
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Hubungkan akun login untuk setiap developer yang sudah di-seed
        $developers = Developer::query()->orderBy('id')->get();

        foreach ($developers as $developer) {
            $user = User::updateOrCreate(
                ['email' => $developer->email],
                [
                    'name' => $developer->name,
                    'password' => 'password',
                    'role' => 'developer',
                    'is_active' => $developer->is_active,
                    'email_verified_at' => now(),
                ]
            );

            if ($developer->user_id !== $user->id) {
                $developer->update(['user_id' => $user->id]);
            }
        }
    }
}
