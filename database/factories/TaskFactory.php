<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Developer;
use App\Models\Module;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = now()->subDays(fake()->numberBetween(0, 7))->toDateString();
        $targetDate = now()->addDays(fake()->numberBetween(3, 21))->toDateString();

        return [
            'project_id' => Project::factory(),
            'module_id' => fn (array $attributes) => Module::factory()->create([
                'project_id' => $attributes['project_id'],
            ])->id,
            'created_by' => Developer::factory(),
            'assigned_to' => Developer::factory(),
            'title' => Str::substr(fake()->sentence(5), 0, 255),
            'description' => fake()->paragraph(),
            'task_type' => fake()->randomElement(TaskType::cases()),
            'status' => TaskStatus::Todo,
            'start_date' => $startDate,
            'target_date' => $targetDate,
            'completed_at' => null,
            'blocker_notes' => null,
            'handover_logs' => [],
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::InProgress,
            'completed_at' => null,
        ]);
    }

    public function inReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::Review,
            'completed_at' => null,
        ]);
    }

    public function done(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::Done,
            'completed_at' => now(),
        ]);
    }

    public function unassigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'assigned_to' => null,
        ]);
    }

    public function blocked(string $note = 'Menunggu dependensi dari tim terkait.'): static
    {
        return $this->state(fn (array $attributes) => [
            'blocker_notes' => $note,
        ]);
    }
}
