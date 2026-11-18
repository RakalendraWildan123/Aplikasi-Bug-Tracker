<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            DeveloperSeeder::class,
            ProjectSeeder::class,
            ModuleSeeder::class,
            TaskSeeder::class,
            BugSeeder::class,
            RepoHistorySeeder::class,
            AppEvaluationSeeder::class,
        ]);
    }
}
