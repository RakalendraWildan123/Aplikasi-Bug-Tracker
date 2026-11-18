<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = Str::substr(fake()->words(3, true), 0, 150);
        $slug = Str::substr(Str::slug($name), 0, 180);

        return [
            'name' => Str::title($name),
            'code' => Str::upper(fake()->unique()->lexify('PRJ-????')),
            'description' => fake()->paragraph(),
            'repo_url' => 'https://github.com/internal-it/'.($slug !== '' ? $slug : 'project'),
            'status' => 'active',
            'color' => fake()->randomElement([
                '#4F46E5',
                '#10B981',
                '#F59E0B',
                '#EF4444',
                '#0EA5E9',
            ]),
        ];
    }

    /**
     * Indicate that the project is in maintenance mode.
     */
    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
        ]);
    }

    /**
     * Indicate that the project is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }
}
