<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DeveloperSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAccountUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin_accounts(): void
    {
        $response = $this->get('/admin/accounts');
        $response->assertRedirect(route('login'));
    }

    public function test_developer_is_forbidden_from_admin_accounts(): void
    {
        $dev = User::factory()->create(['role' => 'developer']);
        $response = $this->actingAs($dev)->get('/admin/accounts');
        $response->assertForbidden();
    }

    public function test_inactive_admin_is_logged_out_and_redirected_to_login(): void
    {
        $inactiveAdmin = User::factory()->admin()->inactive()->create();
        $response = $this->actingAs($inactiveAdmin)->get('/admin/accounts');
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_active_admin_can_view_master_akun_page(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Atasan Sistem']);
        $response = $this->actingAs($admin)->get('/admin/accounts');

        $response->assertOk();
        $response->assertSee('Master Akun Pengguna');
        $response->assertSee('Kelola akun atasan dan developer, hak akses, peranan teknis, dan status aktif login.');
        $response->assertSee('Atasan Sistem');
    }

    public function test_master_akun_page_renders_summary_metric_cards(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(3)->create(['role' => 'developer', 'is_active' => true]);
        User::factory()->create(['role' => 'developer', 'is_active' => false]);

        $response = $this->actingAs($admin)->get('/admin/accounts');

        $response->assertOk();
        $response->assertSee('Total Akun');
        $response->assertSee('Atasan / Admin');
        $response->assertSee('Developer');
        $response->assertSee('Akun Aktif');
        $response->assertSee('5'); // Total
        $response->assertSee('4'); // Aktif
    }

    public function test_master_akun_page_renders_filter_and_search_bar(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get('/admin/accounts');

        $response->assertOk();
        $response->assertSee('id="search-input"', false);
        $response->assertSee('id="role-filter"', false);
        $response->assertSee('id="status-filter"', false);
        $response->assertSee('Semua Peran');
        $response->assertSee('Semua Status');
    }

    public function test_master_akun_page_renders_table_with_accounts(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Admin Utama', 'email' => 'admin@test.com']);
        $dev = User::factory()->create([
            'name' => 'Budi Backend',
            'email' => 'budi@test.com',
            'role' => 'developer',
            'role_title' => 'Backend',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/accounts');

        $response->assertOk();
        $response->assertSee('Admin Utama');
        $response->assertSee('admin@test.com');
        $response->assertSee('Budi Backend');
        $response->assertSee('budi@test.com');
        $response->assertSee('Backend');
        $response->assertSee('Aktif');
        $response->assertSee('btn-edit-account', false);
        $response->assertSee('btn-delete-account', false);
    }

    public function test_master_akun_filters_by_search_role_and_status(): void
    {
        $admin = User::factory()->admin()->create();
        $devActive = User::factory()->create([
            'name' => 'Farhan Nugroho',
            'role' => 'developer',
            'is_active' => true,
        ]);
        $devInactive = User::factory()->create([
            'name' => 'Citra Mobile',
            'role' => 'developer',
            'is_active' => false,
        ]);

        // Search test
        $resSearch = $this->actingAs($admin)->get('/admin/accounts?search=Farhan');
        $resSearch->assertSee('Farhan Nugroho');
        $resSearch->assertDontSee('Citra Mobile');

        // Role filter
        $resRole = $this->actingAs($admin)->get('/admin/accounts?role=admin');
        $resRole->assertSee($admin->name);
        $resRole->assertDontSee('Farhan Nugroho');

        // Status filter
        $resStatus = $this->actingAs($admin)->get('/admin/accounts?status=inactive');
        $resStatus->assertSee('Citra Mobile');
        $resStatus->assertDontSee('Farhan Nugroho');
    }

    public function test_master_akun_renders_create_edit_and_delete_modals(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get('/admin/accounts');

        $response->assertOk();
        $response->assertSee('id="modal-create-account"', false);
        $response->assertSee('id="modal-edit-account"', false);
        $response->assertSee('id="modal-toggle-account"', false);
        $response->assertSee('id="modal-delete-account"', false);
        $response->assertSee('Tambah Akun Baru');
        $response->assertSee('Edit Data Akun');
        $response->assertSee('Nonaktifkan Akses Akun?');
        $response->assertSee('Hapus Akun Pengguna?');
    }

    public function test_sidebar_on_admin_accounts_does_not_contain_logout_button(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get('/admin/accounts');

        $response->assertOk();
        $content = $response->getContent();
        $sidebarPos = strpos($content, 'id="app-sidebar"');
        $sidebarEnd = strpos($content, '</aside>', $sidebarPos);
        $sidebarHtml = substr($content, $sidebarPos, $sidebarEnd - $sidebarPos);

        $this->assertStringNotContainsString(route('logout'), $sidebarHtml);
        $this->assertStringNotContainsString('action="' . route('logout') . '"', $sidebarHtml);
    }

    public function test_admin_can_create_developer_account(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/accounts', [
            'name' => 'Rahmat Hidayat',
            'email' => 'rahmat@bugtracker.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'developer',
            'role_title' => 'Frontend',
            'avatar' => 'RH',
            'avatar_bg' => '#10B981',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.accounts.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'rahmat@bugtracker.test',
            'role' => 'developer',
            'role_title' => 'Frontend',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_admin_account(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/accounts', [
            'name' => 'Wakil Direktur IT',
            'email' => 'wadir@bugtracker.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('admin.accounts.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'wadir@bugtracker.test',
            'role' => 'admin',
            'role_title' => null,
        ]);
    }

    public function test_create_account_validates_required_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/accounts', []);
        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_admin_can_update_account_details(): void
    {
        $admin = User::factory()->admin()->create();
        $targetUser = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@test.com',
            'role' => 'developer',
            'role_title' => 'Backend',
        ]);

        $response = $this->actingAs($admin)->put("/admin/accounts/{$targetUser->id}", [
            'name' => 'Nama Baru',
            'email' => 'baru@test.com',
            'role' => 'developer',
            'role_title' => 'Fullstack Lead',
            'avatar' => 'NB',
            'avatar_bg' => '#0EA5E9',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.accounts.index'));
        $fresh = $targetUser->fresh();
        $this->assertSame('Nama Baru', $fresh->name);
        $this->assertSame('baru@test.com', $fresh->email);
        $this->assertSame('Fullstack Lead', $fresh->role_title);
    }

    public function test_admin_can_toggle_account_active_status(): void
    {
        $admin = User::factory()->admin()->create();
        $dev = User::factory()->create(['is_active' => true]);

        // Toggle to inactive
        $response = $this->actingAs($admin)->patch("/admin/accounts/{$dev->id}/toggle-active");
        $response->assertRedirect(route('admin.accounts.index'));
        $this->assertFalse($dev->fresh()->is_active);

        // Toggle back to active
        $response2 = $this->actingAs($admin)->patch("/admin/accounts/{$dev->id}/toggle-active");
        $response2->assertRedirect(route('admin.accounts.index'));
        $this->assertTrue($dev->fresh()->is_active);
    }

    public function test_admin_cannot_deactivate_own_account(): void
    {
        $admin = User::factory()->admin()->create(['is_active' => true]);

        $response = $this->actingAs($admin)->patch("/admin/accounts/{$admin->id}/toggle-active");
        $response->assertSessionHasErrors('account');
        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_admin_can_delete_another_account(): void
    {
        $admin = User::factory()->admin()->create();
        $dev = User::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/accounts/{$dev->id}");
        $response->assertRedirect(route('admin.accounts.index'));
        $this->assertDatabaseMissing('users', ['id' => $dev->id]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete("/admin/accounts/{$admin->id}");
        $response->assertSessionHasErrors('account');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_accounts_endpoint_returns_json_when_requested(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['name' => 'Dev Json']);

        $response = $this->actingAs($admin)->getJson('/admin/accounts');
        $response->assertOk();
        $response->assertJsonStructure(['accounts', 'role_labels', 'role_filter', 'status_filter', 'metrics']);
    }
}
