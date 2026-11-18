<?php

namespace Database\Factories;

use App\Models\AppEvaluation;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppEvaluation>
 */
class AppEvaluationFactory extends Factory
{
    protected $model = AppEvaluation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'evaluation_date' => now()->subDays(fake()->numberBetween(0, 60))->toDateString(),
            'period_review' => sprintf(
                'Q%d %d #%s',
                fake()->numberBetween(1, 4),
                fake()->numberBetween(2024, 2026),
                fake()->unique()->numerify('####')
            ),
            'operational_issues' => fake()->paragraph(),
            'user_feedback' => fake()->paragraph(),
        ];
    }

    /**
     * Indicate that the evaluation recorded no operational issues.
     */
    public function withoutIssues(): static
    {
        return $this->state(fn (array $attributes) => [
            'operational_issues' => null,
        ]);
    }
}
