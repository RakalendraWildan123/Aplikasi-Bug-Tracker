<?php

namespace Database\Factories;

use App\Enums\ActionItemStatus;
use App\Models\ActionItem;
use App\Models\AppEvaluation;
use App\Models\Developer;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActionItem>
 */
class ActionItemFactory extends Factory
{
    protected $model = ActionItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'evaluation_id' => AppEvaluation::factory(),
            'action_plan' => fake()->sentence(10),
            'assigned_to' => Developer::factory(),
            'target_date' => now()->addDays(fake()->numberBetween(3, 30))->toDateString(),
            'status' => ActionItemStatus::Planned,
            'converted_task_id' => null,
        ];
    }

    /**
     * Indicate that the action item has been converted to a task.
     */
    public function convertedToTask(?Task $task = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ActionItemStatus::ConvertedToTask,
            'converted_task_id' => $task?->id ?? Task::factory(),
        ]);
    }

    /**
     * Indicate that the action item is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ActionItemStatus::Completed,
        ]);
    }

    /**
     * Indicate that the action item has no assigned developer.
     */
    public function unassigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'assigned_to' => null,
        ]);
    }
}
