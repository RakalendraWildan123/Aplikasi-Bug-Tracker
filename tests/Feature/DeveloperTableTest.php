<?php

namespace Tests\Feature;

use App\Enums\DeveloperRole;
use App\Models\Developer;
use Database\Seeders\DeveloperSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'role_title' => DeveloperRole::Backend,
            'avatar' => 'AW',
            'avatar_bg' => 'bg-indigo-600',
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

    public function test_model_casts_role_title_and_supports_active_scope(): void
    {
        $activeDev = Developer::factory()->create([
            'role_title' => DeveloperRole::FullstackLead,
        ]);
        $inactiveDev = Developer::factory()->inactive()->create([
            'role_title' => DeveloperRole::Mobile,
        ]);

        $this->assertInstanceOf(DeveloperRole::class, $activeDev->role_title);
        $this->assertSame('Fullstack Lead', $activeDev->role_title->value);
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
            ->map(fn (DeveloperRole $role) => $role->value)
            ->sort()
            ->values()
            ->all();

        $expected = collect(DeveloperRole::values())->sort()->values()->all();
        $this->assertSame($expected, $roles);
    }
}
