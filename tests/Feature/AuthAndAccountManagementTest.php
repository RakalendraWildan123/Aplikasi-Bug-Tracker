<?php

namespace Tests\Feature;

use App\Models\Developer;
use App\Models\User;
use Database\Seeders\DeveloperSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthAndAccountManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_table_has_unified_developer_and_auth_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'id',
            'name',
            'email',
            'password',
            'role',
            'role_title',
            'avatar',
            'avatar_bg',
            'foto_profil',
            'is_active',
        ]));

        $user = User::factory()->create(['role' => 'developer', 'role_title' => 'Backend']);

        $this->assertTrue($user->isDeveloper());
        $this->assertSame('Backend', $user->role_title);
        $this->assertSame($user->id, $user->developer->id);
    }

    public function test_admin_and_developer_can_login_and_logout(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'boss@bugtracker.test',
            'password' => 'secret123',
        ]);

        $this->get('/login')->assertOk();

        $this->post('/login', [
            'email' => 'boss@bugtracker.test',
            'password' => 'secret123',
        ])->assertRedirect(route('account.edit'));

        $this->assertAuthenticatedAs($admin);

        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();

        $dev = User::factory()->create([
            'email' => 'dev@bugtracker.test',
            'password' => 'secret123',
            'role' => 'developer',
        ]);

        $this->post('/login', [
            'email' => 'dev@bugtracker.test',
            'password' => 'secret123',
        ])->assertRedirect(route('account.edit'));

        $this->assertAuthenticatedAs($dev);
    }

    public function test_authenticated_routes_have_prevent_back_history_cache_headers_and_redirect_after_logout(): void
    {
        $user = User::factory()->create([
            'role' => 'developer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('account.edit'));
        $response->assertOk();
        $response->assertHeader('Pragma', 'no-cache');
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();

        // Ketika user menekan tombol back setelah logout, browser dialihkan ke login
        $this->get(route('account.edit'))->assertRedirect(route('login'));
    }

    public function test_login_rejects_wrong_password_and_inactive_accounts(): void
    {
        User::factory()->create([
            'email' => 'active@bugtracker.test',
            'password' => 'correct-pass',
        ]);

        $this->post('/login', [
            'email' => 'active@bugtracker.test',
            'password' => 'wrong-pass',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();

        User::factory()->inactive()->create([
            'email' => 'disabled@bugtracker.test',
            'password' => 'correct-pass',
        ]);

        $this->post('/login', [
            'email' => 'disabled@bugtracker.test',
            'password' => 'correct-pass',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_rbac_middleware_restricts_account_management_to_active_admins(): void
    {
        // Guests are redirected to login from all protected application routes
        $this->get('/')->assertRedirect(route('login'));
        $this->get('/exports/sample-pdf')->assertRedirect(route('login'));
        $this->get('/admin/accounts')->assertRedirect(route('login'));

        // Inactive developer is forced logout & redirected to login on homepage
        $inactiveDev = User::factory()->inactive()->create(['role' => 'developer']);
        $this->actingAs($inactiveDev)
            ->get('/')
            ->assertRedirect(route('login'));
        $this->assertGuest();

        // Developer gets 403 Forbidden on admin routes
        $developerUser = User::factory()->create(['role' => 'developer']);
        $this->actingAs($developerUser)
            ->get('/admin/accounts')
            ->assertForbidden();

        // Inactive admin forced logout & redirected to login
        $inactiveAdmin = User::factory()->admin()->inactive()->create();
        $this->actingAs($inactiveAdmin)
            ->get('/admin/accounts')
            ->assertRedirect(route('login'));
        $this->assertGuest();

        // Active admin succeeds
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)
            ->get('/admin/accounts')
            ->assertOk();
    }

    public function test_admin_can_create_developer_and_admin_accounts_atomically(): void
    {
        $admin = User::factory()->admin()->create();

        // Create developer account -> creates both User and linked Developer
        $this->actingAs($admin)->post('/admin/accounts', [
            'name' => 'Farhan Nugroho',
            'email' => 'farhan@bugtracker.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'developer',
            'role_title' => 'Backend',
            'avatar' => '',
            'avatar_bg' => '#10B981',
        ])->assertRedirect(route('admin.accounts.index'));

        $devUser = User::query()->where('email', 'farhan@bugtracker.test')->firstOrFail();
        $this->assertSame('developer', $devUser->role);
        $this->assertTrue($devUser->is_active);
        $this->assertNotNull($devUser->developer);
        $this->assertSame('FN', $devUser->developer->avatar);
        $this->assertSame('Backend', $devUser->developer->role_title);

        // Create admin account -> creates User only (no Developer profile)
        $this->actingAs($admin)->post('/admin/accounts', [
            'name' => 'Manager IT',
            'email' => 'manager@bugtracker.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ])->assertRedirect(route('admin.accounts.index'));

        $manager = User::query()->where('email', 'manager@bugtracker.test')->firstOrFail();
        $this->assertSame('admin', $manager->role);
        $this->assertNull($manager->developer);
    }

    public function test_admin_can_update_toggle_active_and_reset_password_with_developer_sync(): void
    {
        $admin = User::factory()->admin()->create();
        $devUser = User::factory()->create([
            'name' => 'Dev Lama',
            'email' => 'old.dev@bugtracker.test',
            'role' => 'developer',
            'role_title' => 'Frontend',
            'is_active' => true,
        ]);

        // Update syncs user/developer fields
        $this->actingAs($admin)->put("/admin/accounts/{$devUser->id}", [
            'name' => 'Dev Baru',
            'email' => 'new.dev@bugtracker.test',
            'role' => 'developer',
            'role_title' => 'Fullstack Lead',
            'avatar' => 'DB',
            'avatar_bg' => '#0EA5E9',
        ])->assertRedirect(route('admin.accounts.index'));

        $fresh = $devUser->fresh();
        $this->assertSame('Dev Baru', $fresh->name);
        $this->assertSame('new.dev@bugtracker.test', $fresh->email);
        $this->assertSame('Fullstack Lead', $fresh->role_title);

        // Toggle active
        $this->actingAs($admin)
            ->patch("/admin/accounts/{$devUser->id}/toggle-active")
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertFalse($devUser->fresh()->is_active);

        // Admin cannot deactivate self
        $this->actingAs($admin)
            ->patch("/admin/accounts/{$admin->id}/toggle-active")
            ->assertSessionHasErrors('account');
        $this->assertTrue($admin->fresh()->is_active);

        // Reset password updates hash
        $this->actingAs($admin)
            ->patch("/admin/accounts/{$devUser->id}/reset-password", [
                'password' => 'newsecret999',
                'password_confirmation' => 'newsecret999',
            ])
            ->assertRedirect(route('admin.accounts.edit', $devUser));

        $this->assertTrue(Hash::check('newsecret999', $devUser->fresh()->password));
    }

    public function test_developer_seeder_is_idempotent_and_creates_admin_and_developers(): void
    {
        $this->seed(DeveloperSeeder::class);
        $this->seed(DeveloperSeeder::class);

        $this->assertDatabaseCount('users', 5);
        $this->assertSame(4, User::query()->where('role', 'developer')->count());

        $admin = User::query()->where('email', 'admin@bugtracker.test')->firstOrFail();
        $this->assertSame('admin', $admin->role);
        $this->assertNull($admin->developer);
    }

    public function test_developer_cannot_perform_any_account_management_mutations(): void
    {
        $admin = User::factory()->admin()->create();
        $developerUser = User::factory()->create(['role' => 'developer']);

        $this->actingAs($developerUser)->get('/admin/accounts/create')->assertForbidden();

        $this->actingAs($developerUser)->post('/admin/accounts', [
            'name' => 'Akun Ilegal',
            'email' => 'ilegal@bugtracker.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'developer',
            'role_title' => 'Backend',
        ])->assertForbidden();

        $this->actingAs($developerUser)->put("/admin/accounts/{$admin->id}", [
            'name' => 'Hacked Admin',
            'email' => $admin->email,
            'role' => 'developer',
        ])->assertForbidden();

        $this->actingAs($developerUser)
            ->patch("/admin/accounts/{$admin->id}/toggle-active")
            ->assertForbidden();

        $this->actingAs($developerUser)
            ->patch("/admin/accounts/{$admin->id}/reset-password", [
                'password' => 'hacked123',
                'password_confirmation' => 'hacked123',
            ])
            ->assertForbidden();

        $this->actingAs($developerUser)
            ->delete("/admin/accounts/{$admin->id}")
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'ilegal@bugtracker.test']);
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
        $this->assertTrue($admin->fresh()->is_active);
        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_admin_can_delete_other_accounts_safely_and_cannot_delete_self(): void
    {
        $admin = User::factory()->admin()->create();

        // Admin cannot delete own account
        $this->actingAs($admin)
            ->delete("/admin/accounts/{$admin->id}")
            ->assertSessionHasErrors('account');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        // Deleting developer without history removes User
        $unusedUser = User::factory()->create(['role' => 'developer']);

        $this->actingAs($admin)
            ->delete("/admin/accounts/{$unusedUser->id}")
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertDatabaseMissing('users', ['id' => $unusedUser->id]);

        // Deleting developer with created Task preserves Task with created_by = null
        $historyUser = User::factory()->create(['role' => 'developer']);
        $task = \App\Models\Task::factory()->create(['created_by' => $historyUser->id]);

        $this->actingAs($admin)
            ->delete("/admin/accounts/{$historyUser->id}")
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertDatabaseMissing('users', ['id' => $historyUser->id]);
        $this->assertNull($task->fresh()->created_by);
    }

    public function test_account_management_returns_json_accounts_list(): void
    {
        $admin = User::factory()->admin()->create();
        $other = User::factory()->create(['name' => 'Target PopUp']);

        $this->actingAs($admin)
            ->getJson('/admin/accounts')
            ->assertOk()
            ->assertJsonStructure(['accounts', 'role_labels', 'role_filter', 'status_filter']);
    }

    public function test_admin_can_upload_sync_and_cleanup_profile_photo_via_account_crud(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();

        $photo = UploadedFile::fake()->image('dev-photo.png', 200, 200);

        $this->actingAs($admin)->post('/admin/accounts', [
            'name' => 'Dev Dengan Foto',
            'email' => 'dev.foto@bugtracker.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'developer',
            'role_title' => 'Frontend',
            'foto_profil' => $photo,
        ])->assertRedirect(route('admin.accounts.index'));

        $created = User::query()->where('email', 'dev.foto@bugtracker.test')->firstOrFail();
        $photoPath = $created->foto_profil;
        $this->assertNotNull($photoPath);
        $this->assertSame($photoPath, $created->developer->foto_profil);
        Storage::disk('public')->assertExists($photoPath);

        // Deleting account cleans up profile photo file
        $this->actingAs($admin)
            ->delete("/admin/accounts/{$created->id}")
            ->assertRedirect(route('admin.accounts.index'));

        Storage::disk('public')->assertMissing($photoPath);
    }

    public function test_full_end_to_end_lifecycle_admin_creates_developer_who_logs_in_then_gets_deactivated(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'atasan@bugtracker.test',
            'password' => 'adminpass123',
        ]);

        // 1. Admin logs in and creates a new developer account
        $this->post('/login', [
            'email' => 'atasan@bugtracker.test',
            'password' => 'adminpass123',
        ])->assertRedirect(route('account.edit'));

        $this->post('/admin/accounts', [
            'name' => 'Nadia Putri',
            'email' => 'nadia@bugtracker.test',
            'password' => 'devpass123',
            'password_confirmation' => 'devpass123',
            'role' => 'developer',
            'role_title' => 'Mobile',
            'avatar_bg' => '#0EA5E9',
        ])->assertRedirect(route('admin.accounts.index'));

        $this->post('/logout')->assertRedirect(route('login'));

        // 2. Newly created developer logs in
        $this->post('/login', [
            'email' => 'nadia@bugtracker.test',
            'password' => 'devpass123',
        ])->assertRedirect(route('account.edit'));

        $this->getJson('/account')
            ->assertOk()
            ->assertJsonPath('user.name', 'Nadia Putri');

        // 3. Developer cannot access account creation page
        $this->get('/admin/accounts/create')->assertForbidden();
        $this->post('/logout')->assertRedirect(route('login'));

        // 4. Admin logs back in and deactivates Nadia
        $nadia = User::query()->where('email', 'nadia@bugtracker.test')->firstOrFail();
        $this->actingAs($admin)
            ->patch("/admin/accounts/{$nadia->id}/toggle-active")
            ->assertRedirect(route('admin.accounts.index'));
        $this->post('/logout');

        // 5. Deactivated developer is blocked from logging in
        $this->post('/login', [
            'email' => 'nadia@bugtracker.test',
            'password' => 'devpass123',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_full_database_seeder_runs_idempotently_across_all_tables(): void
    {
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('users', 6); // Test User + Admin + 4 Developers
        $this->assertDatabaseCount('projects', 3);
        $this->assertDatabaseCount('modules', 6);
        $this->assertDatabaseCount('tasks', 4);
        $this->assertDatabaseCount('bugs', 4);
        $this->assertDatabaseCount('repo_histories', 6);
        $this->assertDatabaseCount('app_evaluations', 6);
        $this->assertDatabaseCount('action_items', 4);
    }

    public function test_user_with_default_password_sees_notification_and_disappears_after_password_change(): void
    {
        $dev = User::factory()->create([
            'role' => 'developer',
            'password' => 'password',
        ]);

        // Masih menggunakan password default -> muncul notifikasi di dashboard & account
        $this->actingAs($dev)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('id="default-password-alert"', false)
            ->assertSee('Segera Ganti Password Default Anda');

        $this->actingAs($dev)
            ->get('/account')
            ->assertOk()
            ->assertSee('id="default-password-alert"', false);

        // Setelah mengganti password -> notifikasi otomatis hilang
        $this->actingAs($dev)
            ->put('/account/password', [
                'current_password' => 'password',
                'password' => 'password-baru-aman-123',
                'password_confirmation' => 'password-baru-aman-123',
            ])
            ->assertRedirect(route('account.edit'));

        $this->actingAs($dev->fresh())
            ->get('/dashboard')
            ->assertOk()
            ->assertDontSee('id="default-password-alert"', false);
    }
}
