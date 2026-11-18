<?php

namespace Database\Factories;

use App\Models\Bug;
use App\Models\Developer;
use App\Models\Module;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bug>
 */
class BugFactory extends Factory
{
    protected $model = Bug::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'module_id' => fn (array $attributes) => Module::factory()->create([
                'project_id' => $attributes['project_id'],
            ])->id,
            'created_by' => Developer::factory(),
            'assigned_to' => Developer::factory(),
            'title' => Str::substr(fake()->sentence(5), 0, 255),
            'description' => fake()->paragraph(),
            'reported_by' => Str::substr('QA - '.fake()->name(), 0, 150),
            'reported_date' => now()->subDays(fake()->numberBetween(0, 10))->toDateString(),
            'severity' => fake()->randomElement(Bug::SEVERITIES),
            'status' => 'open',
            'root_cause' => null,
            'solution_notes' => null,
            'resolved_at' => null,
            'handover_logs' => [],
        ];
    }

    public function critical(): static
    {
        return $this->state(fn (array $attributes) => [
            'severity' => 'critical',
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'resolved_at' => null,
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'root_cause' => 'Validasi input belum menangani nilai null pada payload.',
            'solution_notes' => 'Menambahkan guard clause dan regression test.',
            'resolved_at' => now(),
        ]);
    }

    public function unassigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'assigned_to' => null,
        ]);
    }
}
