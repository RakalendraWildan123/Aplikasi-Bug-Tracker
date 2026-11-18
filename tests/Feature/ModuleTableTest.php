<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Project;
use Database\Seeders\ModuleSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ModuleTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_modules_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('modules'));
        $this->assertTrue(Schema::hasColumns('modules', [
            'id',
            'project_id',
            'name',
            'description',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_description_column_allows_null(): void
    {
        $project = Project::factory()->create();

        $module = Module::create([
            'project_id' => $project->id,
            'name' => 'Notifikasi Sistem',
        ]);

        $fresh = $module->fresh();

        $this->assertNotNull($fresh);
        $this->assertSame($project->id, $fresh->project_id);
        $this->assertSame('Notifikasi Sistem', $fresh->name);
        $this->assertNull($fresh->description);
    }

    public function test_project_id_enforces_foreign_key_constraint(): void
    {
        $this->expectException(QueryException::class);

        DB::table('modules')->insert([
            'project_id' => 999999,
            'name' => 'Orphan Module',
            'description' => 'Should fail foreign key check',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_deleting_project_cascades_to_modules(): void
    {
        $project = Project::factory()->create();
        $modules = Module::factory()->count(2)->for($project)->create();

        $this->assertDatabaseCount('modules', 2);

        $project->delete();

        foreach ($modules as $module) {
            $this->assertDatabaseMissing('modules', ['id' => $module->id]);
        }
        $this->assertDatabaseCount('modules', 0);
    }

    public function test_bidirectional_eloquent_relationships_work(): void
    {
        $project = Project::factory()->create(['code' => 'REL-01']);
        $moduleA = Module::factory()->for($project)->create(['name' => 'Module A']);
        $moduleB = Module::factory()->for($project)->create(['name' => 'Module B']);

        $this->assertInstanceOf(Project::class, $moduleA->project);
        $this->assertSame($project->id, $moduleA->project->id);

        $moduleNames = $project->modules()->orderBy('name')->pluck('name')->all();
        $this->assertSame(['Module A', 'Module B'], $moduleNames);
        $this->assertSame([$moduleA->id, $moduleB->id], $project->modules()->orderBy('id')->pluck('id')->all());
    }

    public function test_module_seeder_is_idempotent_and_seeds_all_project_modules(): void
    {
        $this->seed(ModuleSeeder::class);
        $this->seed(ModuleSeeder::class);

        $this->assertDatabaseCount('projects', 3);
        $this->assertDatabaseCount('modules', 6);

        $intra = Project::query()->where('code', 'INTRA')->firstOrFail();
        $keu = Project::query()->where('code', 'KEU')->firstOrFail();
        $hris = Project::query()->where('code', 'HRIS')->firstOrFail();

        $this->assertCount(2, $intra->modules);
        $this->assertCount(2, $keu->modules);
        $this->assertCount(2, $hris->modules);
    }
}
