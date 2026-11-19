<?php

namespace Tests\Feature;

use App\Models\Developer;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Barryvdh\Snappy\PdfWrapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class CompleteApiScenariosTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // GROUP 1: PUBLIC & SYSTEM HEALTH ENDPOINTS (001 - 005)
    // =========================================================================

    public function test_001_guest_is_redirected_to_login_when_accessing_root(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_002_authenticated_developer_receives_online_status_from_root(): void
    {
        $user = User::factory()->create(['role' => 'developer']);

        $this->actingAs($user)
            ->get('/')
            ->assertOk()
            ->assertJson([
                'status' => 'online',
                'name' => config('app.name', 'Aplikasi Bug Tracker'),
            ]);
    }

    public function test_003_authenticated_admin_receives_online_status_from_root(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/')
            ->assertOk()
            ->assertJson([
                'status' => 'online',
            ]);
    }

    public function test_004_health_check_up_endpoint_returns_ok(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_005_root_api_returns_expected_json_structure(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/')
            ->assertOk()
            ->assertJsonStructure(['name', 'description', 'status']);
    }

    // =========================================================================
    // GROUP 2: AUTHENTICATION - GET/POST LOGIN & LOGOUT (006 - 025)
    // =========================================================================

    public function test_006_guest_can_access_login_info_endpoint(): void
    {
        $this->getJson('/login')
            ->assertOk()
            ->assertJsonStructure(['message']);
    }

    public function test_007_authenticated_user_cannot_access_login_endpoint(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/login')
            ->assertRedirect(route('home'));
    }

    public function test_008_login_rejects_missing_email_and_password(): void
    {
        $this->post('/login', [])
            ->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_009_login_rejects_missing_password(): void
    {
        $this->post('/login', ['email' => 'user@test.test'])
            ->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_010_login_rejects_missing_email(): void
    {
        $this->post('/login', ['password' => 'secret123'])
            ->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_011_login_rejects_invalid_email_format(): void
    {
        $this->post('/login', ['email' => 'bukan-email', 'password' => 'secret123'])
            ->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_012_login_rejects_non_existent_email(): void
    {
        $this->post('/login', ['email' => 'tidak.ada@test.test', 'password' => 'secret123'])
            ->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_013_login_rejects_incorrect_password(): void
    {
        User::factory()->create(['email' => 'dev@test.test', 'password' => 'benar#123']);

        $this->post('/login', ['email' => 'dev@test.test', 'password' => 'salah#123'])
            ->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_014_login_rejects_inactive_developer_account(): void
    {
        User::factory()->inactive()->create([
            'email' => 'inactive.dev@test.test',
            'password' => 'secret123',
            'role' => 'developer',
        ]);

        $this->post('/login', ['email' => 'inactive.dev@test.test', 'password' => 'secret123'])
            ->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_015_login_rejects_inactive_admin_account(): void
    {
        User::factory()->admin()->inactive()->create([
            'email' => 'inactive.admin@test.test',
            'password' => 'secret123',
        ]);

        $this->post('/login', ['email' => 'inactive.admin@test.test', 'password' => 'secret123'])
            ->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_016_developer_can_login_with_valid_credentials(): void
    {
        $dev = User::factory()->create([
            'email' => 'active.dev@test.test',
            'password' => 'secret123',
            'role' => 'developer',
        ]);

        $this->post('/login', ['email' => 'active.dev@test.test', 'password' => 'secret123'])
            ->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($dev);
    }

    public function test_017_admin_can_login_with_valid_credentials(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'active.admin@test.test',
            'password' => 'secret123',
        ]);

        $this->post('/login', ['email' => 'active.admin@test.test', 'password' => 'secret123'])
            ->assertRedirect(route('admin.accounts.index'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_018_login_with_json_accept_header_returns_json_response(): void
    {
        $user = User::factory()->create([
            'email' => 'json.user@test.test',
            'password' => 'secret123',
        ]);

        $this->postJson('/login', ['email' => 'json.user@test.test', 'password' => 'secret123'])
            ->assertOk()
            ->assertJsonPath('message', 'Login berhasil.')
            ->assertJsonPath('user.email', 'json.user@test.test');
        $this->assertAuthenticatedAs($user);
    }

    public function test_019_login_sets_remember_token_when_remember_is_true(): void
    {
        $user = User::factory()->create([
            'email' => 'remember.user@test.test',
            'password' => 'secret123',
        ]);

        $this->post('/login', [
            'email' => 'remember.user@test.test',
            'password' => 'secret123',
            'remember' => '1',
        ])->assertRedirect();

        $this->assertNotNull($user->fresh()->remember_token);
    }

    public function test_020_login_rate_limiter_throttles_after_five_failed_attempts(): void
    {
        User::factory()->create(['email' => 'throttle@test.test', 'password' => 'correct']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => 'throttle@test.test', 'password' => 'wrong'])
                ->assertSessionHasErrors(['email']);
        }

        $this->post('/login', ['email' => 'throttle@test.test', 'password' => 'wrong'])
            ->assertSessionHasErrors(['email']);

        $this->assertTrue(RateLimiter::tooManyAttempts('throttle@test.test|127.0.0.1', 5));
    }

    public function test_021_rate_limiter_clears_upon_successful_login(): void
    {
        $user = User::factory()->create(['email' => 'clear.throttle@test.test', 'password' => 'valid']);

        $this->post('/login', ['email' => 'clear.throttle@test.test', 'password' => 'wrong']);
        $this->assertSame(1, RateLimiter::attempts('clear.throttle@test.test|127.0.0.1'));

        $this->post('/login', ['email' => 'clear.throttle@test.test', 'password' => 'valid'])
            ->assertRedirect();

        $this->assertSame(0, RateLimiter::attempts('clear.throttle@test.test|127.0.0.1'));
    }

    public function test_022_guest_cannot_logout(): void
    {
        $this->post('/logout')->assertRedirect(route('login'));
    }

    public function test_023_authenticated_developer_can_logout_via_post(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('login'))
            ->assertSessionHas('status');

        $this->assertGuest();
    }

    public function test_024_authenticated_admin_can_logout_via_post(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_025_logout_with_json_accept_header_returns_json_success(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/logout')
            ->assertOk()
            ->assertJsonPath('message', 'Anda berhasil keluar dari aplikasi.');

        $this->assertGuest();
    }

    // =========================================================================
    // GROUP 3: MY ACCOUNT - GET /account (026 - 035)
    // =========================================================================

    public function test_026_guest_cannot_access_account_endpoint(): void
    {
        $this->get('/account')->assertRedirect(route('login'));
    }

    public function test_027_developer_can_fetch_account_details_with_linked_developer_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Fulan Dev',
            'role' => 'developer',
            'role_title' => 'Backend',
            'avatar' => 'FD',
        ]);

        $this->actingAs($user)
            ->get('/account')
            ->assertOk()
            ->assertJsonPath('user.name', 'Fulan Dev')
            ->assertJsonPath('developer.role_title', 'Backend')
            ->assertJsonPath('developer.avatar', 'FD');
    }

    public function test_028_admin_can_fetch_account_details_without_linked_developer_profile(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Head Admin']);

        $this->actingAs($admin)
            ->get('/account')
            ->assertOk()
            ->assertJsonPath('user.name', 'Head Admin')
            ->assertJsonPath('developer', null);
    }

    public function test_029_admin_with_developer_profile_fetches_both_user_and_developer(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Admin Coder']);

        $this->actingAs($admin)
            ->get('/account')
            ->assertOk()
            ->assertJsonPath('user.name', 'Admin Coder')
            ->assertJsonPath('developer', null);
    }

    public function test_030_account_endpoint_returns_relative_photo_url_when_photo_exists(): void
    {
        $user = User::factory()->create(['foto_profil' => 'profile-photos/sample.png']);

        $this->actingAs($user)
            ->get('/account')
            ->assertOk()
            ->assertJsonPath('user.foto_profil', 'profile-photos/sample.png');
    }

    public function test_031_account_endpoint_returns_null_photo_when_no_photo(): void
    {
        $user = User::factory()->create(['foto_profil' => null]);

        $this->actingAs($user)
            ->get('/account')
            ->assertOk()
            ->assertJsonPath('user.foto_profil', null);
    }

    public function test_032_inactive_developer_session_is_terminated_on_account_endpoint(): void
    {
        $user = User::factory()->inactive()->create(['role' => 'developer']);

        $this->actingAs($user)
            ->get('/account')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_033_account_endpoint_returns_json_structure(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/account')
            ->assertOk()
            ->assertJsonStructure(['user' => ['id', 'name', 'email', 'role', 'is_active'], 'developer']);
    }

    public function test_034_account_endpoint_reflects_immediate_db_changes(): void
    {
        $user = User::factory()->create(['name' => 'Nama Sebelum']);

        $this->actingAs($user)->get('/account')->assertJsonPath('user.name', 'Nama Sebelum');

        $user->update(['name' => 'Nama Sesudah']);

        $this->actingAs($user->fresh())->get('/account')->assertJsonPath('user.name', 'Nama Sesudah');
    }

    public function test_035_account_endpoint_does_not_expose_password_hash(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();

        $this->assertArrayNotHasKey('password', $response->json('user'));
    }

    // =========================================================================
    // GROUP 4: MY ACCOUNT PROFILE UPDATE - PUT /account/profile (036 - 050)
    // =========================================================================

    public function test_036_guest_cannot_update_account_profile(): void
    {
        $this->put('/account/profile', ['name' => 'Hacker'])->assertRedirect(route('login'));
    }

    public function test_037_profile_update_validates_required_name(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put('/account/profile', ['email' => $user->email])
            ->assertSessionHasErrors(['name']);
    }

    public function test_038_profile_update_validates_required_email(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put('/account/profile', ['name' => 'Valid Name'])
            ->assertSessionHasErrors(['email']);
    }

    public function test_039_profile_update_validates_valid_email_format(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put('/account/profile', ['name' => 'Valid Name', 'email' => 'format-salah'])
            ->assertSessionHasErrors(['email']);
    }

    public function test_040_profile_update_rejects_email_already_taken_by_another_user(): void
    {
        User::factory()->create(['email' => 'lain@test.test']);
        $user = User::factory()->create(['email' => 'saya@test.test']);

        $this->actingAs($user)
            ->put('/account/profile', ['name' => 'Saya Baru', 'email' => 'lain@test.test'])
            ->assertSessionHasErrors(['email']);
    }

    public function test_041_profile_update_allows_owner_to_keep_own_email(): void
    {
        $user = User::factory()->create(['name' => 'Awal', 'email' => 'tetap@test.test']);

        $this->actingAs($user)
            ->put('/account/profile', ['name' => 'Ganti Nama Saja', 'email' => 'tetap@test.test'])
            ->assertRedirect(route('account.edit'));

        $this->assertSame('Ganti Nama Saja', $user->fresh()->name);
    }

    public function test_042_developer_can_update_name(): void
    {
        $user = User::factory()->create(['name' => 'Lama', 'role' => 'developer']);

        $this->actingAs($user)
            ->put('/account/profile', ['name' => 'Nama Anyar', 'email' => $user->email])
            ->assertRedirect(route('account.edit'));

        $this->assertSame('Nama Anyar', $user->fresh()->name);
    }

    public function test_043_developer_can_update_email(): void
    {
        $user = User::factory()->create(['name' => 'Dev', 'email' => 'old.email@test.test', 'role' => 'developer']);

        $this->actingAs($user)
            ->put('/account/profile', ['name' => 'Dev', 'email' => 'new.email@test.test'])
            ->assertRedirect(route('account.edit'));

        $this->assertSame('new.email@test.test', $user->fresh()->email);
    }

    public function test_044_developer_can_update_custom_avatar_and_avatar_bg(): void
    {
        $user = User::factory()->create(['role' => 'developer', 'avatar' => 'AA', 'avatar_bg' => '#111111']);

        $this->actingAs($user)
            ->put('/account/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => 'ZZ',
                'avatar_bg' => '#22C55E',
            ])
            ->assertRedirect(route('account.edit'));

        $this->assertSame('ZZ', $user->fresh()->avatar);
        $this->assertSame('#22C55E', $user->fresh()->avatar_bg);
    }

    public function test_045_developer_clearing_avatar_regenerates_initials_from_name(): void
    {
        $user = User::factory()->create(['name' => 'Bambang Sudirman', 'role' => 'developer', 'avatar' => 'XX']);

        $this->actingAs($user)
            ->put('/account/profile', [
                'name' => 'Bambang Sudirman',
                'email' => $user->email,
                'avatar' => '',
            ]);

        $this->assertSame('BS', $user->fresh()->avatar);
    }

    public function test_046_developer_can_upload_profile_photo(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'developer']);

        $photo = UploadedFile::fake()->image('me.jpg', 200, 200);

        $this->actingAs($user)
            ->put('/account/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => $photo,
            ])
            ->assertRedirect(route('account.edit'));

        $savedPath = $user->fresh()->foto_profil;
        $this->assertNotNull($savedPath);
        Storage::disk('public')->assertExists($savedPath);
    }

    public function test_047_uploading_new_photo_deletes_previous_photo_from_storage(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'developer']);

        $firstPhoto = UploadedFile::fake()->image('first.jpg', 200, 200);
        $this->actingAs($user)->put('/account/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'foto_profil' => $firstPhoto,
        ]);

        $firstPath = $user->fresh()->foto_profil;
        Storage::disk('public')->assertExists($firstPath);

        $secondPhoto = UploadedFile::fake()->image('second.png', 200, 200);
        $this->actingAs($user->fresh())->put('/account/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'foto_profil' => $secondPhoto,
        ]);

        $secondPath = $user->fresh()->foto_profil;
        $this->assertNotSame($firstPath, $secondPath);
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($secondPath);
    }

    public function test_048_developer_can_remove_photo_via_hapus_foto_profil_flag(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'developer']);

        $photo = UploadedFile::fake()->image('avatar.jpg', 200, 200);
        $this->actingAs($user)->put('/account/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'foto_profil' => $photo,
        ]);

        $path = $user->fresh()->foto_profil;
        Storage::disk('public')->assertExists($path);

        $this->actingAs($user->fresh())->put('/account/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'hapus_foto_profil' => '1',
        ]);

        $this->assertNull($user->fresh()->foto_profil);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_049_profile_update_rejects_non_image_file_upload(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $pdf = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $this->actingAs($user)
            ->put('/account/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => $pdf,
            ])
            ->assertSessionHasErrors(['foto_profil']);
    }

    public function test_050_profile_update_returns_json_when_requested(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->putJson('/account/profile', [
                'name' => 'Json Name',
                'email' => $user->email,
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Informasi profil Anda berhasil diperbarui.')
            ->assertJsonPath('user.name', 'Json Name');
    }

    // =========================================================================
    // GROUP 5: MY ACCOUNT PASSWORD UPDATE - PUT /account/password (051 - 060)
    // =========================================================================

    public function test_051_guest_cannot_update_password(): void
    {
        $this->put('/account/password', ['password' => 'secret123'])->assertRedirect(route('login'));
    }

    public function test_052_password_update_validates_required_current_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put('/account/password', [
                'password' => 'BaruPass#123',
                'password_confirmation' => 'BaruPass#123',
            ])
            ->assertSessionHasErrors(['current_password']);
    }

    public function test_053_password_update_validates_required_new_password(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->actingAs($user)
            ->put('/account/password', [
                'current_password' => 'secret123',
            ])
            ->assertSessionHasErrors(['password']);
    }

    public function test_054_password_update_rejects_incorrect_current_password(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->actingAs($user)
            ->put('/account/password', [
                'current_password' => 'salah-pass',
                'password' => 'BaruPass#123',
                'password_confirmation' => 'BaruPass#123',
            ])
            ->assertSessionHasErrors(['current_password']);
    }

    public function test_055_password_update_rejects_new_password_shorter_than_eight_chars(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->actingAs($user)
            ->put('/account/password', [
                'current_password' => 'secret123',
                'password' => 'pendek',
                'password_confirmation' => 'pendek',
            ])
            ->assertSessionHasErrors(['password']);
    }

    public function test_056_password_update_rejects_unconfirmed_new_password(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->actingAs($user)
            ->put('/account/password', [
                'current_password' => 'secret123',
                'password' => 'BaruPass#123',
                'password_confirmation' => 'BedaPass#123',
            ])
            ->assertSessionHasErrors(['password']);
    }

    public function test_057_developer_can_update_password(): void
    {
        $user = User::factory()->create(['password' => 'oldSecret#1', 'role' => 'developer']);

        $this->actingAs($user)
            ->put('/account/password', [
                'current_password' => 'oldSecret#1',
                'password' => 'newSecret#2026',
                'password_confirmation' => 'newSecret#2026',
            ])
            ->assertRedirect(route('account.edit'));

        $this->assertTrue(Hash::check('newSecret#2026', $user->fresh()->password));
    }

    public function test_058_admin_can_update_password_successfully(): void
    {
        $admin = User::factory()->admin()->create(['password' => 'adminOldPass1']);

        $this->actingAs($admin)
            ->put('/account/password', [
                'current_password' => 'adminOldPass1',
                'password' => 'adminNewPass2026',
                'password_confirmation' => 'adminNewPass2026',
            ])
            ->assertRedirect(route('account.edit'));

        $this->assertTrue(Hash::check('adminNewPass2026', $admin->fresh()->password));
    }

    public function test_059_user_can_login_with_new_password_immediately_after_update(): void
    {
        $user = User::factory()->create(['email' => 'relogin@test.test', 'password' => 'passLama#1']);

        $this->actingAs($user)->put('/account/password', [
            'current_password' => 'passLama#1',
            'password' => 'passBaru#2026',
            'password_confirmation' => 'passBaru#2026',
        ]);

        $this->post('/logout');

        $this->post('/login', ['email' => 'relogin@test.test', 'password' => 'passLama#1'])
            ->assertSessionHasErrors(['email']);

        $this->post('/login', ['email' => 'relogin@test.test', 'password' => 'passBaru#2026'])
            ->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    public function test_060_password_update_returns_json_when_accept_json(): void
    {
        $user = User::factory()->create(['password' => 'oldPass123']);

        $this->actingAs($user)
            ->putJson('/account/password', [
                'current_password' => 'oldPass123',
                'password' => 'newPass12345',
                'password_confirmation' => 'newPass12345',
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Password Anda berhasil diperbarui.');
    }

    // =========================================================================
    // GROUP 6: ADMIN ACCOUNTS LIST & CREATE METADATA (061 - 070)
    // =========================================================================

    public function test_061_guest_cannot_access_admin_accounts_list(): void
    {
        $this->get('/admin/accounts')->assertRedirect(route('login'));
    }

    public function test_062_developer_is_forbidden_from_admin_accounts_list(): void
    {
        $dev = User::factory()->create(['role' => 'developer']);

        $this->actingAs($dev)->get('/admin/accounts')->assertForbidden();
    }

    public function test_063_admin_can_fetch_all_accounts_list(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(3)->create(['role' => 'developer']);

        $this->actingAs($admin)
            ->get('/admin/accounts')
            ->assertOk()
            ->assertJsonStructure(['accounts', 'role_labels', 'role_filter', 'status_filter'])
            ->assertJsonCount(4, 'accounts');
    }

    public function test_064_admin_can_filter_accounts_by_role_admin(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(2)->create(['role' => 'developer']);

        $this->actingAs($admin)
            ->get('/admin/accounts?role=admin')
            ->assertOk()
            ->assertJsonCount(1, 'accounts')
            ->assertJsonPath('accounts.0.role', 'admin');
    }

    public function test_065_admin_can_filter_accounts_by_role_developer(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(3)->create(['role' => 'developer']);

        $this->actingAs($admin)
            ->get('/admin/accounts?role=developer')
            ->assertOk()
            ->assertJsonCount(3, 'accounts');
    }

    public function test_066_admin_can_filter_accounts_by_status_active(): void
    {
        $admin = User::factory()->admin()->create(['is_active' => true]);
        User::factory()->create(['is_active' => true]);
        User::factory()->inactive()->create();

        $this->actingAs($admin)
            ->get('/admin/accounts?status=active')
            ->assertOk()
            ->assertJsonCount(2, 'accounts');
    }

    public function test_067_admin_can_filter_accounts_by_status_inactive(): void
    {
        $admin = User::factory()->admin()->create(['is_active' => true]);
        User::factory()->inactive()->count(2)->create();

        $this->actingAs($admin)
            ->get('/admin/accounts?status=inactive')
            ->assertOk()
            ->assertJsonCount(2, 'accounts');
    }

    public function test_068_admin_can_filter_accounts_by_both_role_and_status(): void
    {
        $admin = User::factory()->admin()->create(['is_active' => true]);
        User::factory()->create(['role' => 'developer', 'is_active' => true]);
        User::factory()->inactive()->create(['role' => 'developer']);

        $this->actingAs($admin)
            ->get('/admin/accounts?role=developer&status=active')
            ->assertOk()
            ->assertJsonCount(1, 'accounts')
            ->assertJsonPath('accounts.0.is_active', true);
    }

    public function test_069_guest_and_developer_cannot_access_admin_accounts_create_metadata(): void
    {
        $this->get('/admin/accounts/create')->assertRedirect(route('login'));

        $dev = User::factory()->create(['role' => 'developer']);
        $this->actingAs($dev)->get('/admin/accounts/create')->assertForbidden();
    }

    public function test_070_admin_can_access_admin_accounts_create_metadata(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin/accounts/create')
            ->assertOk()
            ->assertJsonStructure(['role_labels', 'developer_roles']);
    }

    // =========================================================================
    // GROUP 7: ADMIN ACCOUNT CREATION - POST /admin/accounts (071 - 080)
    // =========================================================================

    public function test_071_guest_cannot_create_account(): void
    {
        $this->post('/admin/accounts', ['name' => 'Fulan'])->assertRedirect(route('login'));
    }

    public function test_072_developer_is_forbidden_from_creating_account(): void
    {
        $dev = User::factory()->create(['role' => 'developer']);

        $this->actingAs($dev)->post('/admin/accounts', ['name' => 'Fulan'])->assertForbidden();
    }

    public function test_073_create_account_validates_required_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/accounts', [])
            ->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_074_create_account_rejects_duplicate_email(): void
    {
        $admin = User::factory()->admin()->create(['email' => 'ada@test.test']);

        $this->actingAs($admin)
            ->post('/admin/accounts', [
                'name' => 'Duplikat',
                'email' => 'ada@test.test',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'role' => 'developer',
                'role_title' => 'Backend',
            ])
            ->assertSessionHasErrors(['email']);
    }

    public function test_075_create_account_rejects_invalid_role(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/accounts', [
                'name' => 'Invalid Role',
                'email' => 'inv@test.test',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'role' => 'superadmin',
            ])
            ->assertSessionHasErrors(['role']);
    }

    public function test_076_create_account_requires_role_title_when_role_is_developer(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/accounts', [
                'name' => 'Dev Tanpa Role Title',
                'email' => 'norole@test.test',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'role' => 'developer',
            ])
            ->assertSessionHasErrors(['role_title']);
    }

    public function test_077_create_account_rejects_invalid_developer_role_title(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/accounts', [
                'name' => 'Dev Salah Title',
                'email' => 'wrongtitle@test.test',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'role' => 'developer',
                'role_title' => 'Astronaut',
            ])
            ->assertSessionHasErrors(['role_title']);
    }

    public function test_078_admin_can_create_developer_account(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/accounts', [
                'name' => 'Ali Backend',
                'email' => 'ali@test.test',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'role' => 'developer',
                'role_title' => 'Backend',
                'avatar_bg' => '#3B82F6',
            ])
            ->assertRedirect(route('admin.accounts.index'));

        $user = User::query()->where('email', 'ali@test.test')->firstOrFail();
        $this->assertSame('Ali Backend', $user->name);
        $this->assertSame('developer', $user->role);
        $this->assertTrue($user->is_active);
        $this->assertSame('Backend', $user->role_title);
        $this->assertSame('AB', $user->avatar);
    }

    public function test_079_admin_can_create_admin_account_without_developer_entry(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/accounts', [
                'name' => 'Partner Admin',
                'email' => 'partner.admin@test.test',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'role' => 'admin',
            ])
            ->assertRedirect(route('admin.accounts.index'));

        $newAdmin = User::query()->where('email', 'partner.admin@test.test')->firstOrFail();
        $this->assertSame('admin', $newAdmin->role);
        $this->assertNull($newAdmin->developer);
    }

    public function test_080_admin_can_create_developer_account_with_photo_upload(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $photo = UploadedFile::fake()->image('dev.png', 200, 200);

        $this->actingAs($admin)
            ->post('/admin/accounts', [
                'name' => 'Dev Foto',
                'email' => 'dev.foto@test.test',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'role' => 'developer',
                'role_title' => 'Frontend',
                'foto_profil' => $photo,
            ])
            ->assertRedirect(route('admin.accounts.index'));

        $user = User::query()->where('email', 'dev.foto@test.test')->firstOrFail();
        $this->assertNotNull($user->foto_profil);
        Storage::disk('public')->assertExists($user->foto_profil);
    }

    // =========================================================================
    // GROUP 8: ADMIN VIEW & EDIT METADATA (081 - 085)
    // =========================================================================

    public function test_081_guest_cannot_view_single_account(): void
    {
        $user = User::factory()->create();
        $this->get("/admin/accounts/{$user->id}")->assertRedirect(route('login'));
    }

    public function test_082_developer_is_forbidden_from_viewing_single_account(): void
    {
        $dev = User::factory()->create(['role' => 'developer']);
        $target = User::factory()->create();

        $this->actingAs($dev)->get("/admin/accounts/{$target->id}")->assertForbidden();
    }

    public function test_083_admin_can_view_single_account_details(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create([
            'name' => 'Target User',
            'role' => 'developer',
            'role_title' => 'Backend',
        ]);

        $this->actingAs($admin)
            ->get("/admin/accounts/{$target->id}")
            ->assertOk()
            ->assertJsonPath('account.name', 'Target User')
            ->assertJsonPath('account.role_title', 'Backend');
    }

    public function test_084_single_account_returns_404_for_non_existent_user_id(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/accounts/999999')->assertNotFound();
    }

    public function test_085_admin_can_fetch_edit_metadata_for_account(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->get("/admin/accounts/{$target->id}/edit")
            ->assertOk()
            ->assertJsonStructure(['account', 'role_labels', 'developer_roles']);
    }

    // =========================================================================
    // GROUP 9: ADMIN ACCOUNT UPDATE - PUT /admin/accounts/{user} (086 - 092)
    // =========================================================================

    public function test_086_guest_cannot_update_account(): void
    {
        $user = User::factory()->create();
        $this->put("/admin/accounts/{$user->id}", ['name' => 'Test'])->assertRedirect(route('login'));
    }

    public function test_087_developer_is_forbidden_from_updating_account(): void
    {
        $dev = User::factory()->create(['role' => 'developer']);
        $target = User::factory()->create();

        $this->actingAs($dev)->put("/admin/accounts/{$target->id}", ['name' => 'Test'])->assertForbidden();
    }

    public function test_088_update_account_validates_required_fields(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->put("/admin/accounts/{$target->id}", [])
            ->assertSessionHasErrors(['name', 'email', 'role']);
    }

    public function test_089_update_account_allows_keeping_same_email(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create(['name' => 'Old Name', 'email' => 'same@test.test', 'role' => 'developer']);

        $this->actingAs($admin)
            ->put("/admin/accounts/{$target->id}", [
                'name' => 'New Name',
                'email' => 'same@test.test',
                'role' => 'developer',
                'role_title' => 'Backend',
            ])
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertSame('New Name', $target->fresh()->name);
    }

    public function test_090_update_account_rejects_email_taken_by_another_user(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['email' => 'taken@test.test']);
        $target = User::factory()->create(['email' => 'my@test.test']);

        $this->actingAs($admin)
            ->put("/admin/accounts/{$target->id}", [
                'name' => 'Name',
                'email' => 'taken@test.test',
                'role' => 'developer',
            ])
            ->assertSessionHasErrors(['email']);
    }

    public function test_091_admin_can_update_developer_details(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create([
            'name' => 'Dev Lama',
            'role' => 'developer',
            'role_title' => 'Frontend',
        ]);

        $this->actingAs($admin)
            ->put("/admin/accounts/{$target->id}", [
                'name' => 'Dev Baru',
                'email' => 'dev.baru@test.test',
                'role' => 'developer',
                'role_title' => 'Fullstack Lead',
                'avatar' => 'DB',
            ])
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertSame('Dev Baru', $target->fresh()->name);
        $this->assertSame('Fullstack Lead', $target->fresh()->role_title);
    }

    public function test_092_admin_can_promote_developer_to_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create(['role' => 'developer']);

        $this->actingAs($admin)
            ->put("/admin/accounts/{$target->id}", [
                'name' => $target->name,
                'email' => $target->email,
                'role' => 'admin',
            ])
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertSame('admin', $target->fresh()->role);
        $this->assertNull($target->fresh()->role_title);
    }

    // =========================================================================
    // GROUP 10: TOGGLE ACTIVE, RESET PASSWORD, DELETE & EXPORT (093 - 100)
    // =========================================================================

    public function test_093_toggle_active_fails_when_admin_attempts_to_toggle_self(): void
    {
        $admin = User::factory()->admin()->create(['is_active' => true]);

        $this->actingAs($admin)
            ->patch("/admin/accounts/{$admin->id}/toggle-active")
            ->assertSessionHasErrors(['account']);

        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_094_admin_can_toggle_active_status_for_developer_account(): void
    {
        $admin = User::factory()->admin()->create();
        $dev = User::factory()->create(['role' => 'developer', 'is_active' => true]);

        $this->actingAs($admin)
            ->patch("/admin/accounts/{$dev->id}/toggle-active")
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertFalse($dev->fresh()->is_active);

        // Toggle back to active
        $this->actingAs($admin)
            ->patch("/admin/accounts/{$dev->id}/toggle-active")
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertTrue($dev->fresh()->is_active);
    }

    public function test_095_reset_password_requires_min_eight_chars_and_confirmation(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->patch("/admin/accounts/{$target->id}/reset-password", [
                'password' => 'short',
                'password_confirmation' => 'short',
            ])
            ->assertSessionHasErrors(['password']);
    }

    public function test_096_admin_can_reset_password_for_another_account(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['role' => 'developer', 'password' => 'oldSecret#1']);

        $this->actingAs($admin)
            ->patch("/admin/accounts/{$user->id}/reset-password", [
                'password' => 'ResetPass#2026',
                'password_confirmation' => 'ResetPass#2026',
            ])
            ->assertRedirect(route('admin.accounts.edit', $user));

        $this->assertTrue(Hash::check('ResetPass#2026', $user->fresh()->password));
    }

    public function test_097_delete_account_fails_when_admin_attempts_to_delete_self(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete("/admin/accounts/{$admin->id}")
            ->assertSessionHasErrors(['account']);

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_098_admin_can_delete_unused_developer_account_completely(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['role' => 'developer']);

        $this->actingAs($admin)
            ->delete("/admin/accounts/{$user->id}")
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_099_admin_deleting_developer_with_task_history_preserves_task_with_null_created_by(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['role' => 'developer']);

        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $user->id,
        ]);

        $this->actingAs($admin)
            ->delete("/admin/accounts/{$user->id}")
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertNull($task->fresh()->created_by);
    }

    public function test_100_export_sample_pdf_endpoint_returns_pdf_with_redis_cache_headers(): void
    {
        config(['cache.stores.export.driver' => 'array']);
        Cache::store('export')->flush();

        $admin = User::factory()->admin()->create();

        $pdfMock = Mockery::mock(PdfWrapper::class);
        $pdfMock->shouldReceive('setOption')->twice()->andReturnSelf();
        $pdfMock->shouldReceive('output')->once()->andReturn('%PDF-1.4 sample');

        SnappyPdf::shouldReceive('loadView')
            ->once()
            ->with('exports.sample', Mockery::type('array'))
            ->andReturn($pdfMock);

        $response = $this->actingAs($admin)->get('/exports/sample-pdf');
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('X-Export-Cache', 'MISS');
    }
}
