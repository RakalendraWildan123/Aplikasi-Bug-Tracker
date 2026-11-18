<?php

namespace Tests\Feature;

use App\Models\ActionItem;
use App\Models\AppEvaluation;
use App\Models\Developer;
use App\Models\Task;
use Carbon\CarbonInterface;
use Database\Seeders\ActionItemSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use Tests\TestCase;

class ActionItemTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_action_items_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('action_items'));
        $this->assertTrue(Schema::hasColumns('action_items', [
            'id',
            'evaluation_id',
            'action_plan',
            'assigned_to',
            'target_date',
            'status',
            'converted_task_id',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_defaults_and_nullable_columns_work_properly(): void
    {
        $evaluation = AppEvaluation::factory()->create();

        $item = ActionItem::create([
            'evaluation_id' => $evaluation->id,
            'action_plan' => 'Lakukan audit query lambat pada dashboard.',
        ]);

        $fresh = $item->fresh();

        $this->assertNotNull($fresh);
        $this->assertSame('planned', $fresh->status);
        $this->assertNull($fresh->assigned_to);
        $this->assertNull($fresh->target_date);
        $this->assertNull($fresh->converted_task_id);
    }

    public function test_foreign_keys_reject_non_existent_references(): void
    {
        $evaluation = AppEvaluation::factory()->create();

        // Invalid evaluation_id
        $this->assertThrows(function () {
            DB::table('action_items')->insert([
                'evaluation_id' => 999999,
                'action_plan' => 'Bad Evaluation Ref',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        // Invalid assigned_to
        $this->assertThrows(function () use ($evaluation) {
            DB::table('action_items')->insert([
                'evaluation_id' => $evaluation->id,
                'action_plan' => 'Bad Developer Ref',
                'assigned_to' => 999999,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        // Invalid converted_task_id
        $this->assertThrows(function () use ($evaluation) {
            DB::table('action_items')->insert([
                'evaluation_id' => $evaluation->id,
                'action_plan' => 'Bad Task Ref',
                'converted_task_id' => 999999,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);
    }

    public function test_foreign_key_delete_behaviors(): void
    {
        $evaluation = AppEvaluation::factory()->create();
        $assignee = Developer::factory()->create();
        $task = Task::factory()->create();

        $item = ActionItem::factory()->convertedToTask($task)->create([
            'evaluation_id' => $evaluation->id,
            'assigned_to' => $assignee->id,
        ]);

        // Deleting assignee sets assigned_to to null (keeps action item)
        $assignee->delete();
        $this->assertNull($item->fresh()->assigned_to);

        // Deleting converted task sets converted_task_id to null (keeps action item)
        $task->delete();
        $this->assertNull($item->fresh()->converted_task_id);

        // Deleting parent evaluation cascades to action items
        $evaluation->delete();
        $this->assertDatabaseMissing('action_items', ['id' => $item->id]);
    }

    public function test_invalid_status_enum_values_are_rejected_at_database_level(): void
    {
        $evaluation = AppEvaluation::factory()->create();

        $this->assertThrows(function () use ($evaluation) {
            DB::table('action_items')->insert([
                'evaluation_id' => $evaluation->id,
                'action_plan' => 'Invalid Status DB',
                'status' => 'converted_to_ask',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);
    }

    public function test_casting_relationships_and_scopes_work(): void
    {
        $evaluation = AppEvaluation::factory()->create();
        $assignee = Developer::factory()->create();
        $task = Task::factory()->create();

        $convertedItem = ActionItem::factory()->convertedToTask($task)->create([
            'evaluation_id' => $evaluation->id,
            'assigned_to' => $assignee->id,
            'target_date' => '2026-12-01',
        ]);

        $completedItem = ActionItem::factory()->completed()->unassigned()->create([
            'evaluation_id' => $evaluation->id,
        ]);

        $freshConverted = $convertedItem->fresh();

        $this->assertInstanceOf(CarbonInterface::class, $freshConverted->target_date);
        $this->assertSame('2026-12-01', $freshConverted->target_date->toDateString());
        $this->assertSame('converted_to_task', $freshConverted->status);
        $this->assertSame($evaluation->id, $freshConverted->evaluation->id);
        $this->assertSame($assignee->id, $freshConverted->assignee->id);
        $this->assertSame($task->id, $freshConverted->convertedTask->id);

        $this->assertCount(2, $evaluation->actionItems);
        $this->assertCount(1, $assignee->assignedActionItems);
        $this->assertSame($convertedItem->id, $task->sourceActionItem->id);

        $this->assertSame([$convertedItem->id], ActionItem::status('converted_to_task')->pluck('id')->all());
        $this->assertSame([$completedItem->id], ActionItem::status('completed')->pluck('id')->all());
        $this->assertSame([$convertedItem->id], ActionItem::forEvaluation($evaluation->id)->assignedTo($assignee->id)->pluck('id')->all());

        // Scope fails fast on typo
        $this->assertThrows(fn () => ActionItem::status('typo_status')->get(), InvalidArgumentException::class);
    }

    public function test_action_item_seeder_is_idempotent_and_covers_all_statuses(): void
    {
        $this->seed(ActionItemSeeder::class);
        $this->seed(ActionItemSeeder::class);

        $this->assertDatabaseCount('action_items', 4);

        $statuses = ActionItem::query()
            ->pluck('status')
            ->unique()
            ->sort()
            ->values()
            ->all();

        $this->assertSame(collect(ActionItem::STATUSES)->sort()->values()->all(), $statuses);
        $this->assertTrue(ActionItem::query()->whereNotNull('converted_task_id')->exists());
    }
}
