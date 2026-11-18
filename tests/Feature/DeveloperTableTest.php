<?php

namespace Tests\Feature;

use App\Models\Developer;
use Database\Seeders\DeveloperSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DeveloperTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_developers_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasTable('developers'));
        $this->assertTrue(Schema::hasColumns('developers', [
            'id',
            'user_id',
            'name',
            'email',
            'role_title',
            'avatar',
            'avatar_bg',
            'is_active',
            'created_at',
            'updated_at',
        ]));
    }

    public function test_is_active_defaults_to_true_when_omitted(): void
    {
        $developer = Developer::create([
            'name' => 'Andi Wijaya',
            'email' => 'andi.wijaya@bugtracker.test',
            'role_title' => 'Backend',
            'avatar' => 'AW',
            'avatar_bg' => '#4F46E5',
        ]);

        $fresh = $developer->fresh();

        $this->assertNotNull($fresh);
        $this->assertTrue($fresh->is_active);
        $this->assertIsBool($fresh->is_active);
    }

    public function test_email_column_enforces_unique_constraint(): void
    {
        Developer::factory()->create([
            'email' => 'unique.dev@bugtracker.test',
        ]);

        $this->expectException(QueryException::class);

        Developer::factory()->create([
            'email' => 'unique.dev@bugtracker.test',
        ]);
    }

    public function test_database_rejects_invalid_role_title_enum_value(): void
    {
        $this->expectException(QueryException::class);

        DB::table('developers')->insert([
            'name' => 'Invalid Role Dev',
            'email' => 'invalid.role@bugtracker.test',
            'role_title' => 'DevOps',
            'avatar' => 'IR',
            'avatar_bg' => '#4F46E5',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_model_supports_role_title_and_active_scope(): void
    {
        $activeDev = Developer::factory()->create([
            'role_title' => 'Fullstack Lead',
        ]);
        $inactiveDev = Developer::factory()->inactive()->create([
            'role_title' => 'Mobile',
        ]);

        $this->assertSame('Fullstack Lead', $activeDev->role_title);
        $this->assertTrue($activeDev->is_active);
        $this->assertFalse($inactiveDev->is_active);

        $activeIds = Developer::active()->pluck('id')->all();
        $this->assertContains($activeDev->id, $activeIds);
        $this->assertNotContains($inactiveDev->id, $activeIds);
    }

    public function test_developer_seeder_is_idempotent_and_seeds_all_roles(): void
    {
        $this->seed(DeveloperSeeder::class);
        $this->seed(DeveloperSeeder::class);

        $this->assertDatabaseCount('developers', 4);

        $roles = Developer::query()
            ->pluck('role_title')
            ->sort()
            ->values()
            ->all();

        $expected = collect(Developer::ROLES)->sort()->values()->all();
        $this->assertSame($expected, $roles);
    }
}
