<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\RepoHistory;
use Carbon\CarbonInterface;
use Database\Seeders\RepoHistorySeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RepoHistoryTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_repo_histories_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('repo_histories'));
        $this->assertTrue(Schema::hasColumns('repo_histories', [
            'id',
            'project_id',
            'release_date',
            'release_tags',
            'branch_name',
            'commit_hash',
            'summary_changes',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_optional_columns_allow_null(): void
    {
        $project = Project::factory()->create();

        $history = RepoHistory::create([
            'project_id' => $project->id,
            'release_date' => '2026-11-18',
            'branch_name' => 'main',
            'commit_hash' => '1234567890abcdef1234567890abcdef12345678',
        ]);

        $fresh = $history->fresh();

        $this->assertNotNull($fresh);
        $this->assertNull($fresh->release_tags);
        $this->assertNull($fresh->summary_changes);
        $this->assertSame('1234567', $fresh->short_hash);
    }

    public function test_project_id_enforces_foreign_key_constraint(): void
    {
        $this->expectException(QueryException::class);

        DB::table('repo_histories')->insert([
            'project_id' => 999999,
            'release_date' => '2026-11-18',
            'branch_name' => 'main',
            'commit_hash' => 'abcdef1234567890abcdef1234567890abcdef12',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_deleting_project_cascades_to_repo_histories(): void
    {
        $project = Project::factory()->create();
        $histories = RepoHistory::factory()->count(2)->for($project)->create();

        $this->assertDatabaseCount('repo_histories', 2);

        $project->delete();

        foreach ($histories as $history) {
            $this->assertDatabaseMissing('repo_histories', ['id' => $history->id]);
        }
        $this->assertDatabaseCount('repo_histories', 0);
    }

    public function test_composite_unique_constraint_on_project_and_commit_hash(): void
    {
        $projectA = Project::factory()->create(['code' => 'PRJ-A']);
        $projectB = Project::factory()->create(['code' => 'PRJ-B']);
        $sharedHash = 'deadbeef1234567890deadbeef1234567890abcd';

        RepoHistory::factory()->for($projectA)->create([
            'commit_hash' => $sharedHash,
        ]);

        // Same commit_hash on a different project is allowed
        $onProjectB = RepoHistory::factory()->for($projectB)->create([
            'commit_hash' => $sharedHash,
        ]);
        $this->assertDatabaseHas('repo_histories', ['id' => $onProjectB->id]);

        // Duplicate commit_hash on the same project is rejected
        $this->expectException(QueryException::class);

        RepoHistory::factory()->for($projectA)->create([
            'commit_hash' => $sharedHash,
        ]);
    }

    public function test_casting_relationships_and_scopes_work(): void
    {
        $project = Project::factory()->create(['code' => 'REL-HIST']);

        $older = RepoHistory::factory()->for($project)->create([
            'release_date' => '2026-11-01',
            'branch_name' => 'main',
            'release_tags' => 'v1.0.0',
        ]);

        $newer = RepoHistory::factory()->for($project)->untagged()->create([
            'release_date' => '2026-11-18',
            'branch_name' => 'develop',
        ]);

        $freshNewer = $newer->fresh();

        $this->assertInstanceOf(CarbonInterface::class, $freshNewer->release_date);
        $this->assertSame('2026-11-18', $freshNewer->release_date->toDateString());
        $this->assertNull($freshNewer->release_tags);
        $this->assertSame($project->id, $freshNewer->project->id);
        $this->assertCount(2, $project->repoHistories);

        $orderedIds = RepoHistory::forProject($project->id)
            ->latestRelease()
            ->pluck('id')
            ->all();
        $this->assertSame([$newer->id, $older->id], $orderedIds);

        $mainIds = RepoHistory::forProject($project->id)
            ->branch('main')
            ->pluck('id')
            ->all();
        $this->assertSame([$older->id], $mainIds);
    }

    public function test_repo_history_seeder_is_idempotent_and_seeds_all_projects(): void
    {
        $this->seed(RepoHistorySeeder::class);
        $this->seed(RepoHistorySeeder::class);

        $this->assertDatabaseCount('projects', 3);
        $this->assertDatabaseCount('repo_histories', 6);

        $intra = Project::query()->where('code', 'INTRA')->firstOrFail();
        $keu = Project::query()->where('code', 'KEU')->firstOrFail();
        $hris = Project::query()->where('code', 'HRIS')->firstOrFail();

        $this->assertCount(2, $intra->repoHistories);
        $this->assertCount(2, $keu->repoHistories);
        $this->assertCount(2, $hris->repoHistories);
        $this->assertTrue(RepoHistory::query()->whereNull('release_tags')->exists());
    }
}
