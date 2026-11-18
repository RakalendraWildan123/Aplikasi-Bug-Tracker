<?php

namespace Tests\Feature;

use App\Models\Project;
use Database\Seeders\ProjectSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use Tests\TestCase;

class ProjectTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('projects'));
        $this->assertTrue(Schema::hasColumns('projects', [
            'id',
            'name',
            'code',
            'description',
            'repo_url',
            'status',
            'color',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_status_defaults_to_active_and_optional_columns_allow_null(): void
    {
        $project = Project::create([
            'name' => 'Monitoring Infrastruktur',
            'code' => 'INFRA',
            'color' => '#0EA5E9',
        ]);

        $fresh = $project->fresh();

        $this->assertNotNull($fresh);
        $this->assertSame('active', $fresh->status);
        $this->assertNull($fresh->description);
        $this->assertNull($fresh->repo_url);
    }

    public function test_code_column_enforces_unique_constraint(): void
    {
        Project::factory()->create([
            'code' => 'INTRA',
        ]);

        $this->expectException(QueryException::class);

        Project::factory()->create([
            'code' => 'INTRA',
        ]);
    }

    public function test_database_rejects_invalid_status_enum_value(): void
    {
        $this->expectException(QueryException::class);

        DB::table('projects')->insert([
            'name' => 'Invalid Status Project',
            'code' => 'INV-01',
            'status' => 'invalid_status',
            'color' => '#000000',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_model_supports_status_and_scopes(): void
    {
        $activeProject = Project::factory()->create(['code' => 'ACT-01']);
        $maintenanceProject = Project::factory()->maintenance()->create(['code' => 'MNT-01']);
        $archivedProject = Project::factory()->archived()->create(['code' => 'ARC-01']);

        $this->assertSame('active', $activeProject->status);
        $this->assertSame('maintenance', $maintenanceProject->status);
        $this->assertSame('archived', $archivedProject->status);

        $activeIds = Project::active()->pluck('id')->all();
        $this->assertSame([$activeProject->id], $activeIds);

        $maintenanceIds = Project::status('maintenance')->pluck('id')->all();
        $this->assertSame([$maintenanceProject->id], $maintenanceIds);

        $archivedIds = Project::status('archived')->pluck('id')->all();
        $this->assertSame([$archivedProject->id], $archivedIds);

        $this->assertThrows(fn () => Project::status('unknown_status')->get(), InvalidArgumentException::class);
    }

    public function test_project_seeder_is_idempotent_and_seeds_all_statuses(): void
    {
        $this->seed(ProjectSeeder::class);
        $this->seed(ProjectSeeder::class);

        $this->assertDatabaseCount('projects', 3);

        $codes = Project::query()->orderBy('code')->pluck('code')->all();
        $this->assertSame(['HRIS', 'INTRA', 'KEU'], $codes);

        $statuses = Project::query()
            ->pluck('status')
            ->sort()
            ->values()
            ->all();

        $expected = collect(Project::STATUSES)->sort()->values()->all();
        $this->assertSame($expected, $statuses);
    }
}
