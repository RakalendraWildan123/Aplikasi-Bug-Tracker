<?php

namespace Tests\Feature;

use App\Models\AppEvaluation;
use App\Models\Project;
use Carbon\CarbonInterface;
use Database\Seeders\AppEvaluationSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AppEvaluationTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_app_evaluations_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('app_evaluations'));
        $this->assertTrue(Schema::hasColumns('app_evaluations', [
            'id',
            'project_id',
            'evaluation_date',
            'period_review',
            'operational_issues',
            'user_feedback',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_optional_columns_allow_null(): void
    {
        $project = Project::factory()->create();

        $evaluation = AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-18',
            'period_review' => 'Q4 2026',
        ]);

        $fresh = $evaluation->fresh();

        $this->assertNotNull($fresh);
        $this->assertNull($fresh->operational_issues);
        $this->assertNull($fresh->user_feedback);
    }

    public function test_project_id_enforces_foreign_key_constraint(): void
    {
        $this->expectException(QueryException::class);

        DB::table('app_evaluations')->insert([
            'project_id' => 999999,
            'evaluation_date' => '2026-11-18',
            'period_review' => 'Q4 2026',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_deleting_project_cascades_to_app_evaluations(): void
    {
        $project = Project::factory()->create();
        $evaluations = AppEvaluation::factory()->count(2)->for($project)->create();

        $this->assertDatabaseCount('app_evaluations', 2);

        $project->delete();

        foreach ($evaluations as $evaluation) {
            $this->assertDatabaseMissing('app_evaluations', ['id' => $evaluation->id]);
        }
        $this->assertDatabaseCount('app_evaluations', 0);
    }

    public function test_composite_unique_constraint_on_project_and_period_review(): void
    {
        $projectA = Project::factory()->create(['code' => 'EVAL-A']);
        $projectB = Project::factory()->create(['code' => 'EVAL-B']);

        AppEvaluation::factory()->for($projectA)->create([
            'period_review' => 'Q4 2026',
        ]);

        // Same period_review on a different project is allowed
        $onProjectB = AppEvaluation::factory()->for($projectB)->create([
            'period_review' => 'Q4 2026',
        ]);
        $this->assertDatabaseHas('app_evaluations', ['id' => $onProjectB->id]);

        // Duplicate period_review on the same project is rejected
        $this->expectException(QueryException::class);

        AppEvaluation::factory()->for($projectA)->create([
            'period_review' => 'Q4 2026',
        ]);
    }

    public function test_casting_relationships_and_scopes_work(): void
    {
        $project = Project::factory()->create(['code' => 'REL-EVAL']);

        $older = AppEvaluation::factory()->for($project)->create([
            'evaluation_date' => '2026-09-30',
            'period_review' => 'Q3 2026',
        ]);

        $newer = AppEvaluation::factory()->for($project)->withoutIssues()->create([
            'evaluation_date' => '2026-11-18',
            'period_review' => 'Q4 2026',
        ]);

        $freshNewer = $newer->fresh();

        $this->assertInstanceOf(CarbonInterface::class, $freshNewer->evaluation_date);
        $this->assertSame('2026-11-18', $freshNewer->evaluation_date->toDateString());
        $this->assertNull($freshNewer->operational_issues);
        $this->assertSame($project->id, $freshNewer->project->id);
        $this->assertCount(2, $project->appEvaluations);

        $orderedIds = AppEvaluation::forProject($project->id)
            ->latestEvaluation()
            ->pluck('id')
            ->all();
        $this->assertSame([$newer->id, $older->id], $orderedIds);

        $q3Ids = AppEvaluation::forProject($project->id)
            ->period('Q3 2026')
            ->pluck('id')
            ->all();
        $this->assertSame([$older->id], $q3Ids);
    }

    public function test_app_evaluation_seeder_is_idempotent_and_seeds_all_projects(): void
    {
        $this->seed(AppEvaluationSeeder::class);
        $this->seed(AppEvaluationSeeder::class);

        $this->assertDatabaseCount('projects', 3);
        $this->assertDatabaseCount('app_evaluations', 6);

        $intra = Project::query()->where('code', 'INTRA')->firstOrFail();
        $keu = Project::query()->where('code', 'KEU')->firstOrFail();
        $hris = Project::query()->where('code', 'HRIS')->firstOrFail();

        $this->assertCount(2, $intra->appEvaluations);
        $this->assertCount(2, $keu->appEvaluations);
        $this->assertCount(2, $hris->appEvaluations);
        $this->assertTrue(AppEvaluation::query()->whereNull('operational_issues')->exists());
    }
}
