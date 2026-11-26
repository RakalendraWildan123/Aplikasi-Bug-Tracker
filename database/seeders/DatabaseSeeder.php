<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DeveloperSeeder::class,
            ProjectSeeder::class,
            ModuleSeeder::class,
            TaskSeeder::class,
            BugSeeder::class,
            RepoHistorySeeder::class,
            AppEvaluationSeeder::class,
            ActionItemSeeder::class,
        ]);
    }
}
