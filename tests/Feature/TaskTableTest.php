<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Developer;
use App\Models\Module;
use App\Models\Project;
use App\Models\Task;
use Carbon\CarbonInterface;
use Database\Seeders\TaskSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use ValueError;

class TaskTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('tasks'));
        $this->assertTrue(Schema::hasColumns('tasks', [
            'id',
            'project_id',
            'module_id',
            'created_by',
            'assigned_to',
            'title',
            'description',
            'task_type',
            'status',
            'start_date',
            'target_date',
            'completed_at',
            'blocker_notes',
            'handover_logs',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_defaults_and_nullable_columns_work_properly(): void
    {
        $project = Project::factory()->create();
        $creator = Developer::factory()->create();

        $task = Task::create([
            'project_id' => $project->id,
            'created_by' => $creator->id,
            'title' => 'Inisialisasi Task Minimal',
        ]);

        $fresh = $task->fresh();

        $this->assertNotNull($fresh);
        $this->assertSame(TaskType::Feature, $fresh->task_type);
        $this->assertSame(TaskStatus::Todo, $fresh->status);
        $this->assertNull($fresh->module_id);
        $this->assertNull($fresh->assigned_to);
        $this->assertNull($fresh->description);
        $this->assertNull($fresh->start_date);
        $this->assertNull($fresh->target_date);
        $this->assertNull($fresh->completed_at);
        $this->assertNull($fresh->blocker_notes);
        $this->assertNull($fresh->handover_logs);
    }

    public function test_foreign_keys_reject_non_existent_references(): void
    {
        $project = Project::factory()->create();
        $creator = Developer::factory()->create();

        // Invalid project_id
        $this->assertThrows(function () use ($creator) {
            DB::table('tasks')->insert([
                'project_id' => 999999,
                'created_by' => $creator->id,
                'title' => 'Bad Project',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        // Invalid module_id
        $this->assertThrows(function () use ($project, $creator) {
            DB::table('tasks')->insert([
                'project_id' => $project->id,
                'module_id' => 999999,
                'created_by' => $creator->id,
                'title' => 'Bad Module',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        // Invalid created_by
        $this->assertThrows(function () use ($project) {
            DB::table('tasks')->insert([
                'project_id' => $project->id,
                'created_by' => 999999,
                'title' => 'Bad Creator',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        // Invalid assigned_to
        $this->assertThrows(function () use ($project, $creator) {
            DB::table('tasks')->insert([
                'project_id' => $project->id,
                'created_by' => $creator->id,
                'assigned_to' => 999999,
                'title' => 'Bad Assignee',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);
    }

    public function test_foreign_key_delete_behaviors(): void
    {
        $project = Project::factory()->create();
        $module = Module::factory()->for($project)->create();
        $creator = Developer::factory()->create();
        $assignee = Developer::factory()->create();

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'module_id' => $module->id,
            'created_by' => $creator->id,
            'assigned_to' => $assignee->id,
        ]);

        // Deleting module sets module_id to null (keeps task)
        $module->delete();
        $this->assertNull($task->fresh()->module_id);

        // Deleting assignee developer sets assigned_to to null (keeps task)
        $assignee->delete();
        $this->assertNull($task->fresh()->assigned_to);

        // Deleting creator developer is restricted
        $this->assertThrows(fn () => $creator->delete(), QueryException::class);

        // Deleting project cascades to tasks
        $project->delete();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_invalid_enum_values_are_rejected_at_database_and_model_levels(): void
    {
        $project = Project::factory()->create();
        $creator = Developer::factory()->create();

        $this->assertThrows(function () use ($project, $creator) {
            DB::table('tasks')->insert([
                'project_id' => $project->id,
                'created_by' => $creator->id,
                'title' => 'Invalid Task Type DB',
                'task_type' => 'bug_invalid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        $this->assertThrows(function () use ($project, $creator) {
            DB::table('tasks')->insert([
                'project_id' => $project->id,
                'created_by' => $creator->id,
                'title' => 'Invalid Status DB',
                'status' => 'cancelled_invalid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        $this->assertThrows(function () use ($project, $creator) {
            Task::create([
                'project_id' => $project->id,
                'created_by' => $creator->id,
                'title' => 'Invalid Cast',
                'task_type' => 'non_existent_type',
            ]);
        }, ValueError::class);

        $this->assertThrows(function () use ($project, $creator) {
            Task::create([
                'project_id' => $project->id,
                'created_by' => $creator->id,
                'title' => 'Invalid Status Cast',
                'status' => 'non_existent_status',
            ]);
        }, ValueError::class);
    }

    public function test_casts_json_handover_logs_and_dates_properly(): void
    {
        $logs = [
            [
                'from_developer_id' => 1,
                'to_developer_id' => 2,
                'note' => 'Handover ke backend',
                'handed_over_at' => '2026-11-18T10:00:00Z',
            ],
        ];

        $task = Task::factory()->done()->create([
            'start_date' => '2026-11-10',
            'target_date' => '2026-11-20',
            'handover_logs' => $logs,
        ]);

        $fresh = $task->fresh();

        $this->assertInstanceOf(CarbonInterface::class, $fresh->start_date);
        $this->assertInstanceOf(CarbonInterface::class, $fresh->target_date);
        $this->assertInstanceOf(CarbonInterface::class, $fresh->completed_at);
        $this->assertSame('2026-11-10', $fresh->start_date->toDateString());
        $this->assertSame('2026-11-20', $fresh->target_date->toDateString());
        $this->assertSame($logs, $fresh->handover_logs);
    }

    public function test_relationships_and_query_scopes_work(): void
    {
        $project = Project::factory()->create();
        $module = Module::factory()->for($project)->create();
        $creator = Developer::factory()->create();
        $assignee = Developer::factory()->create();

        $featureTask = Task::factory()->inProgress()->create([
            'project_id' => $project->id,
            'module_id' => $module->id,
            'created_by' => $creator->id,
            'assigned_to' => $assignee->id,
            'task_type' => TaskType::Feature,
        ]);

        $refactorTask = Task::factory()->inReview()->unassigned()->create([
            'project_id' => $project->id,
            'module_id' => $module->id,
            'created_by' => $creator->id,
            'task_type' => TaskType::Refactor,
        ]);

        $this->assertSame($project->id, $featureTask->project->id);
        $this->assertSame($module->id, $featureTask->module->id);
        $this->assertSame($creator->id, $featureTask->creator->id);
        $this->assertSame($assignee->id, $featureTask->assignee->id);

        $this->assertCount(2, $project->tasks);
        $this->assertCount(2, $module->tasks);
        $this->assertCount(2, $creator->createdTasks);
        $this->assertCount(1, $assignee->assignedTasks);

        $this->assertSame([$featureTask->id], Task::status(TaskStatus::InProgress)->pluck('id')->all());
        $this->assertSame([$refactorTask->id], Task::status('review')->pluck('id')->all());
        $this->assertSame([$refactorTask->id], Task::type(TaskType::Refactor)->pluck('id')->all());
        $this->assertSame([$featureTask->id], Task::forProject($project->id)->assignedTo($assignee->id)->pluck('id')->all());

        // Scopes fail fast on typo
        $this->assertThrows(fn () => Task::status('typo_status')->get(), ValueError::class);
        $this->assertThrows(fn () => Task::type('typo_type')->get(), ValueError::class);
    }

    public function test_task_seeder_is_idempotent_and_covers_all_enums(): void
    {
        $this->seed(TaskSeeder::class);
        $this->seed(TaskSeeder::class);

        $this->assertDatabaseCount('tasks', 4);

        $types = Task::query()
            ->pluck('task_type')
            ->map(fn (TaskType $type) => $type->value)
            ->sort()
            ->values()
            ->all();

        $statuses = Task::query()
            ->pluck('status')
            ->map(fn (TaskStatus $status) => $status->value)
            ->sort()
            ->values()
            ->all();

        $this->assertSame(collect(TaskType::values())->sort()->values()->all(), $types);
        $this->assertSame(collect(TaskStatus::values())->sort()->values()->all(), $statuses);
        $this->assertTrue(Task::query()->whereNotNull('blocker_notes')->exists());
        $this->assertTrue(Task::query()->get()->contains(fn (Task $t) => ! empty($t->handover_logs)));
    }
}
