<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\RepoHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RepoHistory>
 */
class RepoHistoryFactory extends Factory
{
    protected $model = RepoHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'release_date' => now()->subDays(fake()->numberBetween(0, 30))->toDateString(),
            'release_tags' => sprintf(
                'v%d.%d.%d',
                fake()->numberBetween(1, 3),
                fake()->numberBetween(0, 9),
                fake()->numberBetween(0, 9)
            ),
            'branch_name' => fake()->randomElement([
                'main',
                'develop',
                'release/1.0',
                'hotfix/security',
            ]),
            'commit_hash' => fake()->unique()->sha1(),
            'summary_changes' => fake()->paragraph(),
        ];
    }

    /**
     * Indicate that the repo history entry has no release tag.
     */
    public function untagged(): static
    {
        return $this->state(fn (array $attributes) => [
            'release_tags' => null,
        ]);
    }
}
