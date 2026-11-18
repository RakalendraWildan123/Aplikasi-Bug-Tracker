<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Models\Bug;
use App\Models\Developer;
use App\Models\Module;
use App\Models\Project;
use Carbon\CarbonInterface;
use Database\Seeders\BugSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use ValueError;

class BugTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_bugs_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('bugs'));
        $this->assertTrue(Schema::hasColumns('bugs', [
            'id',
            'project_id',
            'module_id',
            'created_by',
            'assigned_to',
            'title',
            'description',
            'reported_by',
            'reported_date',
            'severity',
            'status',
            'root_cause',
            'solution_notes',
            'resolved_at',
            'handover_logs',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_defaults_and_nullable_columns_work_properly(): void
    {
        $project = Project::factory()->create();
        $creator = Developer::factory()->create();

        $bug = Bug::create([
            'project_id' => $project->id,
            'created_by' => $creator->id,
            'title' => 'Laporan Bug Minimal',
        ]);

        $fresh = $bug->fresh();

        $this->assertNotNull($fresh);
        $this->assertSame(BugSeverity::Medium, $fresh->severity);
        $this->assertSame(BugStatus::Open, $fresh->status);
        $this->assertNull($fresh->module_id);
        $this->assertNull($fresh->assigned_to);
        $this->assertNull($fresh->description);
        $this->assertNull($fresh->reported_by);
        $this->assertNull($fresh->reported_date);
        $this->assertNull($fresh->root_cause);
        $this->assertNull($fresh->solution_notes);
        $this->assertNull($fresh->resolved_at);
        $this->assertNull($fresh->handover_logs);
    }

    public function test_foreign_keys_reject_non_existent_references(): void
    {
        $project = Project::factory()->create();
        $creator = Developer::factory()->create();

        // Invalid project_id
        $this->assertThrows(function () use ($creator) {
            DB::table('bugs')->insert([
                'project_id' => 999999,
                'created_by' => $creator->id,
                'title' => 'Bad Project Bug',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        // Invalid module_id
        $this->assertThrows(function () use ($project, $creator) {
            DB::table('bugs')->insert([
                'project_id' => $project->id,
                'module_id' => 999999,
                'created_by' => $creator->id,
                'title' => 'Bad Module Bug',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        // Invalid created_by
        $this->assertThrows(function () use ($project) {
            DB::table('bugs')->insert([
                'project_id' => $project->id,
                'created_by' => 999999,
                'title' => 'Bad Creator Bug',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        // Invalid assigned_to
        $this->assertThrows(function () use ($project, $creator) {
            DB::table('bugs')->insert([
                'project_id' => $project->id,
                'created_by' => $creator->id,
                'assigned_to' => 999999,
                'title' => 'Bad Assignee Bug',
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

        $bug = Bug::factory()->create([
            'project_id' => $project->id,
            'module_id' => $module->id,
            'created_by' => $creator->id,
            'assigned_to' => $assignee->id,
        ]);

        // Deleting module sets module_id to null (keeps bug)
        $module->delete();
        $this->assertNull($bug->fresh()->module_id);

        // Deleting assignee developer sets assigned_to to null (keeps bug)
        $assignee->delete();
        $this->assertNull($bug->fresh()->assigned_to);

        // Deleting creator developer is restricted
        $this->assertThrows(fn () => $creator->delete(), QueryException::class);

        // Deleting project cascades to bugs
        $project->delete();
        $this->assertDatabaseMissing('bugs', ['id' => $bug->id]);
    }

    public function test_invalid_enum_values_are_rejected_at_database_and_model_levels(): void
    {
        $project = Project::factory()->create();
        $creator = Developer::factory()->create();

        $this->assertThrows(function () use ($project, $creator) {
            DB::table('bugs')->insert([
                'project_id' => $project->id,
                'created_by' => $creator->id,
                'title' => 'Invalid Severity DB',
                'severity' => 'blocker_invalid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        $this->assertThrows(function () use ($project, $creator) {
            DB::table('bugs')->insert([
                'project_id' => $project->id,
                'created_by' => $creator->id,
                'title' => 'Invalid Status DB',
                'status' => 'closed_invalid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, QueryException::class);

        $this->assertThrows(function () use ($project, $creator) {
            Bug::create([
                'project_id' => $project->id,
                'created_by' => $creator->id,
                'title' => 'Invalid Severity Cast',
                'severity' => 'extreme',
            ]);
        }, ValueError::class);

        $this->assertThrows(function () use ($project, $creator) {
            Bug::create([
                'project_id' => $project->id,
                'created_by' => $creator->id,
                'title' => 'Invalid Status Cast',
                'status' => 'wontfix',
            ]);
        }, ValueError::class);
    }

    public function test_casts_json_handover_logs_and_dates_properly(): void
    {
        $logs = [
            [
                'from_developer_id' => 1,
                'to_developer_id' => 2,
                'note' => 'Investigasi awal selesai, lanjut hotfix.',
                'handed_over_at' => '2026-11-18T11:15:00Z',
            ],
        ];

        $bug = Bug::factory()->resolved()->create([
            'reported_date' => '2026-11-15',
            'handover_logs' => $logs,
        ]);

        $fresh = $bug->fresh();

        $this->assertInstanceOf(CarbonInterface::class, $fresh->reported_date);
        $this->assertInstanceOf(CarbonInterface::class, $fresh->resolved_at);
        $this->assertSame('2026-11-15', $fresh->reported_date->toDateString());
        $this->assertSame($logs, $fresh->handover_logs);
    }

    public function test_relationships_and_query_scopes_work(): void
    {
        $project = Project::factory()->create();
        $module = Module::factory()->for($project)->create();
        $creator = Developer::factory()->create();
        $assignee = Developer::factory()->create();

        $criticalOpenBug = Bug::factory()->critical()->create([
            'project_id' => $project->id,
            'module_id' => $module->id,
            'created_by' => $creator->id,
            'assigned_to' => $assignee->id,
        ]);

        $resolvedBug = Bug::factory()->resolved()->unassigned()->create([
            'project_id' => $project->id,
            'module_id' => $module->id,
            'created_by' => $creator->id,
            'severity' => BugSeverity::Low,
        ]);

        $this->assertSame($project->id, $criticalOpenBug->project->id);
        $this->assertSame($module->id, $criticalOpenBug->module->id);
        $this->assertSame($creator->id, $criticalOpenBug->creator->id);
        $this->assertSame($assignee->id, $criticalOpenBug->assignee->id);

        $this->assertCount(2, $project->bugs);
        $this->assertCount(2, $module->bugs);
        $this->assertCount(2, $creator->createdBugs);
        $this->assertCount(1, $assignee->assignedBugs);

        $this->assertSame([$criticalOpenBug->id], Bug::open()->pluck('id')->all());
        $this->assertSame([$resolvedBug->id], Bug::status(BugStatus::Resolved)->pluck('id')->all());
        $this->assertSame([$resolvedBug->id], Bug::status('resolved')->pluck('id')->all());
        $this->assertSame([$criticalOpenBug->id], Bug::severity(BugSeverity::Critical)->pluck('id')->all());
        $this->assertSame([$criticalOpenBug->id], Bug::forProject($project->id)->assignedTo($assignee->id)->pluck('id')->all());

        // Scopes fail fast on typo
        $this->assertThrows(fn () => Bug::status('typo_status')->get(), ValueError::class);
        $this->assertThrows(fn () => Bug::severity('typo_severity')->get(), ValueError::class);
    }

    public function test_bug_seeder_is_idempotent_and_covers_all_enums(): void
    {
        $this->seed(BugSeeder::class);
        $this->seed(BugSeeder::class);

        $this->assertDatabaseCount('bugs', 4);

        $severities = Bug::query()
            ->pluck('severity')
            ->map(fn (BugSeverity $severity) => $severity->value)
            ->sort()
            ->values()
            ->all();

        $statuses = Bug::query()
            ->pluck('status')
            ->map(fn (BugStatus $status) => $status->value)
            ->unique()
            ->sort()
            ->values()
            ->all();

        $this->assertSame(collect(BugSeverity::values())->sort()->values()->all(), $severities);
        $this->assertSame(collect(BugStatus::values())->sort()->values()->all(), $statuses);
        $this->assertTrue(Bug::query()->whereNotNull('root_cause')->whereNotNull('solution_notes')->whereNotNull('resolved_at')->exists());
        $this->assertTrue(Bug::query()->get()->contains(fn (Bug $b) => ! empty($b->handover_logs)));
    }
}
