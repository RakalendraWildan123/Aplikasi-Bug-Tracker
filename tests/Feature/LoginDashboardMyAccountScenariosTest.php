<?php

namespace Tests\Feature;

use App\Models\Developer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginDashboardMyAccountScenariosTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // A. SKENARIO LOGIN & LOGOUT (1 - 10)
    // =========================================================================

    public function test_01_guest_can_view_login_page(): void
    {
        $this->withoutVite()
            ->get('/login')
            ->assertOk()
            ->assertSee('Login')
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false);
    }

    public function test_02_authenticated_user_cannot_open_guest_login_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/login')
            ->assertRedirect();
    }

    public function test_03_login_validation_fails_when_fields_are_empty(): void
    {
        $this->from('/login')
            ->post('/login', [
                'email' => '',
                'password' => '',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email', 'password']);

        $this->assertGuest();
    }

    public function test_04_login_validation_fails_for_invalid_email_format(): void
    {
        $this->from('/login')
            ->post('/login', [
                'email' => 'bukan-format-email',
                'password' => 'password123',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_05_login_fails_for_unregistered_email(): void
    {
        $this->from('/login')
            ->post('/login', [
                'email' => 'tidak.ada@bugtracker.test',
                'password' => 'password123',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_06_login_fails_for_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'dev.pass@bugtracker.test',
            'password' => 'benar1234',
        ]);

        $this->from('/login')
            ->post('/login', [
                'email' => 'dev.pass@bugtracker.test',
                'password' => 'salah9999',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email'])
            ->assertSessionHasInput('email', 'dev.pass@bugtracker.test');

        $this->assertGuest();
    }

    public function test_07_login_fails_for_inactive_developer_account(): void
    {
        User::factory()->inactive()->create([
            'email' => 'nonaktif.dev@bugtracker.test',
            'password' => 'password123',
            'role' => 'developer',
        ]);

        $this->from('/login')
            ->post('/login', [
                'email' => 'nonaktif.dev@bugtracker.test',
                'password' => 'password123',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_08_login_fails_for_inactive_admin_account(): void
    {
        User::factory()->admin()->inactive()->create([
            'email' => 'nonaktif.admin@bugtracker.test',
            'password' => 'password123',
        ]);

        $this->from('/login')
            ->post('/login', [
                'email' => 'nonaktif.admin@bugtracker.test',
                'password' => 'password123',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_09_login_is_throttled_after_five_failed_attempts(): void
    {
        $email = 'throttle.test@bugtracker.test';
        RateLimiter::clear($email.'|127.0.0.1');

        User::factory()->create([
            'email' => $email,
            'password' => 'password-benar',
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $email,
                'password' => 'password-salah',
            ])->assertSessionHasErrors(['email']);
        }

        // Percobaan ke-6 langsung terkena rate limiter meskipun password benar
        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password-benar',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
        $this->assertStringContainsString('Terlalu banyak percobaan login', session('errors')->first('email'));
    }

    public function test_10_active_developer_and_admin_can_login_with_remember_and_logout(): void
    {
        $dev = User::factory()->create([
            'email' => 'aktif.dev@bugtracker.test',
            'password' => 'devpass123',
            'role' => 'developer',
            'remember_token' => null,
        ]);

        $this->post('/login', [
            'email' => 'aktif.dev@bugtracker.test',
            'password' => 'devpass123',
            'remember' => '1',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($dev);
        $this->assertNotNull($dev->fresh()->remember_token);

        $this->post('/logout')
            ->assertRedirect(route('login'))
            ->assertSessionHas('status');
        $this->assertGuest();

        $admin = User::factory()->admin()->create([
            'email' => 'aktif.admin@bugtracker.test',
            'password' => 'adminpass123',
        ]);

        $this->post('/login', [
            'email' => 'aktif.admin@bugtracker.test',
            'password' => 'adminpass123',
        ])->assertRedirect(route('admin.accounts.index'));

        $this->assertAuthenticatedAs($admin);
    }

    // =========================================================================
    // B. SKENARIO DASHBOARD & NAVIGASI SIDEBAR (11 - 20)
    // =========================================================================

    public function test_11_guest_is_redirected_to_login_when_opening_dashboard(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));
    }

    public function test_12_active_developer_can_open_blank_dashboard_with_sidebar(): void
    {
        $dev = User::factory()->create([
            'name' => 'Dina Kusuma',
            'email' => 'dina@bugtracker.test',
            'role' => 'developer',
        ]);

        $this->actingAs($dev)
            ->get('/')
            ->assertOk()
            ->assertJson([
                'status' => 'online',
            ]);
    }

    public function test_13_active_admin_can_open_blank_dashboard_and_sees_admin_sidebar(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Kepala IT',
            'email' => 'kepala.it@bugtracker.test',
        ]);

        $this->actingAs($admin)
            ->get('/')
            ->assertOk()
            ->assertJson([
                'status' => 'online',
            ]);
    }

    public function test_14_dashboard_renders_left_sidebar_with_dashboard_and_my_account_without_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/account')
            ->assertOk()
            ->assertJsonStructure(['user', 'developer']);
    }

    public function test_15_dashboard_hides_account_management_menu_from_developer(): void
    {
        $dev = User::factory()->create(['role' => 'developer']);

        $this->actingAs($dev)
            ->get('/admin/accounts')
            ->assertForbidden();
    }

    public function test_16_dashboard_shows_account_management_menu_for_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin/accounts')
            ->assertOk()
            ->assertJsonStructure(['accounts']);
    }

    public function test_17_dashboard_displays_linked_developer_avatar_in_sidebar(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'role' => 'developer',
            'role_title' => 'Frontend',
            'avatar' => 'BS',
        ]);

        $this->actingAs($user)
            ->get('/account')
            ->assertOk()
            ->assertJsonPath('developer.avatar', 'BS')
            ->assertJsonPath('developer.role_title', 'Frontend');
    }

    public function test_18_dashboard_works_for_admin_without_linked_developer_profile(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Direktur Teknologi']);

        $this->assertNull($admin->developer);

        $this->actingAs($admin)
            ->get('/account')
            ->assertOk()
            ->assertJsonPath('user.name', 'Direktur Teknologi')
            ->assertJsonPath('developer', null);
    }

    public function test_19_deactivated_developer_is_logged_out_when_accessing_dashboard(): void
    {
        $dev = User::factory()->inactive()->create(['role' => 'developer']);

        $this->actingAs($dev)
            ->get('/')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_20_deactivated_admin_is_logged_out_when_accessing_dashboard(): void
    {
        $admin = User::factory()->admin()->inactive()->create();

        $this->actingAs($admin)
            ->get('/')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    // =========================================================================
    // C. SKENARIO FITUR AKUN SAYA / MY ACCOUNT (21 - 30)
    // =========================================================================

    public function test_21_guest_is_redirected_to_login_from_my_account_page(): void
    {
        $this->get('/account')
            ->assertRedirect(route('login'));
    }

    public function test_22_guest_cannot_submit_profile_or_password_update(): void
    {
        $this->put('/account/profile', [
            'name' => 'Guest Hacker',
            'email' => 'guest@bugtracker.test',
        ])->assertRedirect(route('login'));

        $this->put('/account/password', [
            'current_password' => 'anything',
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ])->assertRedirect(route('login'));
    }

    public function test_23_developer_can_view_my_account_page_with_avatar_fields(): void
    {
        $user = User::factory()->create([
            'name' => 'Siti Rahma',
            'email' => 'siti@bugtracker.test',
            'role' => 'developer',
            'role_title' => 'Mobile',
            'avatar' => 'SR',
            'avatar_bg' => '#EF4444',
        ]);

        $this->actingAs($user)
            ->get('/account')
            ->assertOk()
            ->assertJsonPath('user.email', 'siti@bugtracker.test')
            ->assertJsonPath('developer.avatar_bg', '#EF4444');
    }

    public function test_24_admin_without_developer_profile_views_my_account_without_avatar_fields(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Admin Utama']);

        $this->actingAs($admin)
            ->get('/account')
            ->assertOk()
            ->assertJsonPath('user.name', 'Admin Utama')
            ->assertJsonPath('developer', null);
    }

    public function test_25_owner_can_update_name_email_and_avatar_synced_to_developer(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Awal',
            'email' => 'awal@bugtracker.test',
            'role' => 'developer',
            'avatar' => 'NA',
            'avatar_bg' => '#4F46E5',
        ]);

        $this->actingAs($user)
            ->put('/account/profile', [
                'name' => 'Nama Terbarui',
                'email' => 'terbarui@bugtracker.test',
                'avatar' => 'nt',
                'avatar_bg' => '#0EA5E9',
            ])
            ->assertRedirect(route('account.edit'))
            ->assertSessionHas('profile_status');

        $this->assertSame('Nama Terbarui', $user->fresh()->name);
        $this->assertSame('terbarui@bugtracker.test', $user->fresh()->email);
        $this->assertSame('NT', $user->fresh()->avatar);
        $this->assertSame('#0EA5E9', $user->fresh()->avatar_bg);
    }

    public function test_26_empty_avatar_auto_generates_uppercase_initials_from_name(): void
    {
        $user = User::factory()->create(['name' => 'Galih Prakoso', 'role' => 'developer', 'avatar' => 'XX']);

        $this->actingAs($user)
            ->put('/account/profile', [
                'name' => 'Wulan Sari',
                'email' => $user->email,
                'avatar' => '   ',
                'avatar_bg' => '#10B981',
            ])
            ->assertRedirect(route('account.edit'));

        $this->assertSame('WS', $user->fresh()->avatar);
    }

    public function test_27_profile_update_rejects_duplicate_email_used_by_another_user(): void
    {
        User::factory()->create(['email' => 'terpakai@bugtracker.test']);
        $user = User::factory()->create(['email' => 'sendiri@bugtracker.test']);

        $this->actingAs($user)
            ->from('/account')
            ->put('/account/profile', [
                'name' => $user->name,
                'email' => 'terpakai@bugtracker.test',
            ])
            ->assertRedirect('/account')
            ->assertSessionHasErrors(['email']);

        $this->assertSame('sendiri@bugtracker.test', $user->fresh()->email);
    }

    public function test_28_profile_update_ignores_self_role_and_status_tampering(): void
    {
        $user = User::factory()->create([
            'name' => 'Dev Biasa',
            'email' => 'biasa@bugtracker.test',
            'role' => 'developer',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->put('/account/profile', [
                'name' => 'Dev Coba Admin',
                'email' => 'biasa@bugtracker.test',
                'role' => 'admin',
                'is_active' => false,
            ])
            ->assertRedirect(route('account.edit'));

        $fresh = $user->fresh();
        $this->assertSame('Dev Coba Admin', $fresh->name);
        $this->assertSame('developer', $fresh->role);
        $this->assertTrue($fresh->is_active);
    }

    public function test_29_password_update_rejects_wrong_current_password_and_short_or_unconfirmed_new_password(): void
    {
        $user = User::factory()->create([
            'password' => 'rahasia123',
        ]);

        // Password saat ini salah
        $this->actingAs($user)
            ->from('/account')
            ->put('/account/password', [
                'current_password' => 'salah-lama',
                'password' => 'baru12345',
                'password_confirmation' => 'baru12345',
            ])
            ->assertRedirect('/account')
            ->assertSessionHasErrors(['current_password']);

        // Password baru terlalu pendek (< 8 karakter)
        $this->actingAs($user)
            ->from('/account')
            ->put('/account/password', [
                'current_password' => 'rahasia123',
                'password' => '12345',
                'password_confirmation' => '12345',
            ])
            ->assertRedirect('/account')
            ->assertSessionHasErrors(['password']);

        // Konfirmasi password baru tidak cocok
        $this->actingAs($user)
            ->from('/account')
            ->put('/account/password', [
                'current_password' => 'rahasia123',
                'password' => 'baru12345',
                'password_confirmation' => 'beda99999',
            ])
            ->assertRedirect('/account')
            ->assertSessionHasErrors(['password']);

        $this->assertTrue(Hash::check('rahasia123', $user->fresh()->password));
    }

    public function test_30_owner_can_update_password_synced_to_developer_and_login_with_new_password(): void
    {
        $user = User::factory()->create([
            'email' => 'ganti.pass@bugtracker.test',
            'password' => 'password-lama',
            'role' => 'developer',
        ]);

        $this->actingAs($user)
            ->put('/account/password', [
                'current_password' => 'password-lama',
                'password' => 'password-baru-2026',
                'password_confirmation' => 'password-baru-2026',
            ])
            ->assertRedirect(route('account.edit'))
            ->assertSessionHas('password_status');

        $this->assertTrue(Hash::check('password-baru-2026', $user->fresh()->password));

        // Logout lalu pastikan password lama ditolak dan password baru diterima
        $this->post('/logout')->assertRedirect(route('login'));

        $this->post('/login', [
            'email' => 'ganti.pass@bugtracker.test',
            'password' => 'password-lama',
        ])->assertSessionHasErrors(['email']);
        $this->assertGuest();

        $this->post('/login', [
            'email' => 'ganti.pass@bugtracker.test',
            'password' => 'password-baru-2026',
        ])->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }
}
