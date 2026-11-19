<?php

namespace Tests\Feature;

use App\Models\Developer;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Pengujian Komprehensif Seluruh Fitur UI Aplikasi Bug Tracker (100+ Skenario)
 * Setiap skenario menjalankan RefreshDatabase agar data terhapus dan konsisten.
 */
class UiComprehensiveScenariosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Menjamin kebersihan data untuk setiap skenario pengujian
        Storage::fake('public');
    }

    // =========================================================================
    // MODUL 1: AUTENTIKASI & HALAMAN LOGIN UI (Skenario 001 - 020)
    // =========================================================================

    public function test_001_guest_can_access_login_page_ui(): void
    {
        $response = $this->get('/login');
        $response->assertOk();
        $response->assertSee('Login');
    }

    public function test_002_login_ui_contains_email_input_field(): void
    {
        $response = $this->get('/login');
        $response->assertOk();
        $response->assertSee('name="email"', false);
    }

    public function test_003_login_ui_contains_password_input_field(): void
    {
        $response = $this->get('/login');
        $response->assertOk();
        $response->assertSee('name="password"', false);
        $response->assertSee('type="password"', false);
    }

    public function test_004_login_ui_contains_remember_me_checkbox(): void
    {
        $response = $this->get('/login');
        $response->assertOk();
        $response->assertSee('name="remember"', false);
    }

    public function test_005_login_ui_contains_submit_login_button(): void
    {
        $response = $this->get('/login');
        $response->assertOk();
        $response->assertSee('type="submit"', false);
    }

    public function test_006_login_ui_displays_brand_title(): void
    {
        $response = $this->get('/login');
        $response->assertOk();
        $response->assertSee(config('app.name', 'Aplikasi Bug Tracker'));
    }

    public function test_007_authenticated_user_visiting_login_is_redirected(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect(route('home'));
    }

    public function test_008_login_submission_fails_with_empty_inputs(): void
    {
        $response = $this->post('/login', []);
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_009_login_submission_fails_with_missing_email(): void
    {
        $response = $this->post('/login', ['password' => 'secret123']);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_010_login_submission_fails_with_missing_password(): void
    {
        $response = $this->post('/login', ['email' => 'admin@bugtracker.test']);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_011_login_submission_fails_with_invalid_email_format(): void
    {
        $response = $this->post('/login', [
            'email' => 'bukan-email-valid',
            'password' => 'secret123',
        ]);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_012_login_submission_fails_with_unregistered_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'tidak.terdaftar@bugtracker.test',
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_013_login_submission_fails_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'user.uji@bugtracker.test',
            'password' => Hash::make('password-asli'),
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'user.uji@bugtracker.test',
            'password' => 'password-keliru',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_014_login_submission_fails_for_inactive_account(): void
    {
        $inactive = User::factory()->create([
            'email' => 'nonaktif@bugtracker.test',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'nonaktif@bugtracker.test',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_015_admin_user_can_login_successfully(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin.login@bugtracker.test',
            'password' => Hash::make('password-admin'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin.login@bugtracker.test',
            'password' => 'password-admin',
        ]);

        $response->assertRedirect(route('account.edit'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_016_developer_user_can_login_successfully(): void
    {
        $developer = User::factory()->create([
            'email' => 'dev.login@bugtracker.test',
            'password' => Hash::make('password-dev'),
            'role' => 'developer',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'dev.login@bugtracker.test',
            'password' => 'password-dev',
        ]);

        $response->assertRedirect(route('account.edit'));
        $this->assertAuthenticatedAs($developer);
    }

    public function test_017_login_with_remember_me_stores_cookie(): void
    {
        $user = User::factory()->create([
            'email' => 'remember@bugtracker.test',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'remember@bugtracker.test',
            'password' => 'password123',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('account.edit'));
        $response->assertCookieNotExpired(auth()->guard()->getRecallerName());
        $this->assertAuthenticatedAs($user);
    }

    public function test_018_authenticated_user_can_logout_via_post(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_019_logout_invalidates_session_and_clears_auth(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->actingAs($user);
        $this->assertAuthenticated();

        $this->post('/logout');
        $this->assertGuest();
    }

    public function test_020_guest_cannot_logout(): void
    {
        $response = $this->post('/logout');
        $response->assertRedirect(route('login'));
    }

    // =========================================================================
    // MODUL 2: NAVIGASI SIDEBAR & TATA LETAK GLOBAL UI (Skenario 021 - 035)
    // =========================================================================

    public function test_021_guest_accessing_home_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_022_authenticated_user_accessing_home_redirects_to_account(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertRedirect(route('account.edit'));
    }

    public function test_023_sidebar_renders_application_title(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee(config('app.name', 'Aplikasi Bug Tracker'));
    }

    public function test_024_sidebar_renders_workspace_badge(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('IT Dev Workspace');
    }

    public function test_025_sidebar_renders_menu_utama_section(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('Menu Utama');
    }

    public function test_026_sidebar_renders_link_to_papan_kanban(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee(route('kanban.index'));
        $response->assertSee('Papan Kanban');
    }

    public function test_027_sidebar_renders_link_to_akun_saya(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee(route('account.edit'));
        $response->assertSee('Akun Saya');
    }

    public function test_028_sidebar_highlights_active_state_on_kanban_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('bg-slate-800 text-white', false);
    }

    public function test_029_sidebar_highlights_active_state_on_account_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('bg-slate-800 text-white', false);
    }

    public function test_030_sidebar_renders_responsive_close_button(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('id="btn-sidebar-close"', false);
    }

    public function test_031_layout_renders_backdrop_for_mobile_sidebar(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('id="sidebar-backdrop"', false);
    }

    public function test_032_mobile_header_renders_open_sidebar_button(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('id="btn-sidebar-open"', false);
    }

    public function test_033_mobile_header_renders_logged_in_user_avatar(): void
    {
        $user = User::factory()->create(['name' => 'Budi Santoso', 'avatar' => 'BS']);
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('BS');
    }

    public function test_034_sidebar_does_not_contain_logout_button(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();

        // Memastikan sidebar id="app-sidebar" tidak memiliki tombol logout terduplikasi
        $content = $response->getContent();
        $sidebarPos = strpos($content, 'id="app-sidebar"');
        $sidebarEnd = strpos($content, '</aside>', $sidebarPos);
        $sidebarHtml = substr($content, $sidebarPos, $sidebarEnd - $sidebarPos);

        $this->assertStringNotContainsString('route(\'logout\')', $sidebarHtml);
        $this->assertStringNotContainsString('action="' . route('logout') . '"', $sidebarHtml);
    }

    public function test_035_layout_is_clean_and_free_of_nested_tabs(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertDontSee('role="tablist"', false);
        $response->assertDontSee('role="tab"', false);
    }

    // =========================================================================
    // MODUL 3: AKUN SAYA UI - TAMPILAN PROFIL READ-ONLY (Skenario 036 - 050)
    // =========================================================================

    public function test_036_guest_cannot_access_account_profile_page(): void
    {
        $response = $this->get('/account');
        $response->assertRedirect(route('login'));
    }

    public function test_037_account_page_renders_main_heading(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('Akun Saya');
    }

    public function test_038_account_page_renders_subheading_description(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('Rincian informasi akun dan profil Anda.');
    }

    public function test_039_account_page_renders_user_name_in_summary_card(): void
    {
        $user = User::factory()->create(['name' => 'Ahmad Fadillah']);
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('Ahmad Fadillah');
    }

    public function test_040_account_page_renders_admin_role_badge(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/account');
        $response->assertOk();
        $response->assertSee('Atasan / Admin');
    }

    public function test_041_account_page_renders_developer_role_badge(): void
    {
        $dev = User::factory()->create(['role' => 'developer']);
        $response = $this->actingAs($dev)->get('/account');
        $response->assertOk();
        $response->assertSee('Developer');
    }

    public function test_042_account_page_renders_user_email_address(): void
    {
        $user = User::factory()->create(['email' => 'ahmad@bugtracker.test']);
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('ahmad@bugtracker.test');
    }

    public function test_043_account_page_renders_active_status_indicator(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('Aktif');
    }

    public function test_044_account_page_renders_developer_specialization(): void
    {
        $dev = User::factory()->create([
            'role' => 'developer',
            'role_title' => 'Fullstack Lead',
        ]);
        $response = $this->actingAs($dev)->get('/account');
        $response->assertOk();
        $response->assertSee('Spesialisasi');
        $response->assertSee('Fullstack Lead');
    }

    public function test_045_account_page_does_not_render_specialization_for_admin(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'role_title' => null,
        ]);
        $response = $this->actingAs($admin)->get('/account');
        $response->assertOk();
        $response->assertDontSee('Spesialisasi');
    }

    public function test_046_account_page_renders_avatar_initials_when_no_photo(): void
    {
        $user = User::factory()->create([
            'name' => 'Citra Dewi',
            'avatar' => 'CD',
            'foto_profil' => null,
        ]);
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('CD');
    }

    public function test_047_account_page_renders_custom_photo_url_when_uploaded(): void
    {
        $user = User::factory()->create([
            'foto_profil' => 'profile-photos/test-avatar.jpg',
        ]);
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('profile-photos/test-avatar.jpg');
    }

    public function test_048_account_page_renders_masked_password_placeholder(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('••••••••••••');
        $response->assertSee('Tersimpan aman');
    }

    public function test_049_account_page_renders_security_explanation_hint(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('Untuk mengubah nama, foto profil, atau kata sandi');
    }

    public function test_050_account_page_renders_logout_button_inside_profile_card(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('Logout');
    }

    // =========================================================================
    // MODUL 4: AKUN SAYA UI - TOMBOL LOGOUT & STYLING ASLI (Skenario 051 - 055)
    // =========================================================================

    public function test_051_profile_card_logout_button_has_dedicated_post_form(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('action="' . route('logout') . '"', false);
    }

    public function test_052_profile_card_logout_button_has_rose_styling_classes(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('bg-rose-50', false);
        $response->assertSee('border-rose-200', false);
        $response->assertSee('text-rose-700', false);
    }

    public function test_053_profile_card_logout_button_text_is_strictly_logout(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('Logout');
        $response->assertDontSee('Keluar Akun');
    }

    public function test_054_submitting_logout_from_profile_card_terminates_session(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_055_profile_card_logout_form_includes_csrf_token(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('name="_token"', false);
    }

    // =========================================================================
    // MODUL 5: AKUN SAYA UI - VERIFIKASI PASSWORD POP-UP MODAL (Skenario 056 - 065)
    // =========================================================================

    public function test_056_account_page_renders_edit_akun_button(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('id="btn-open-modal"', false);
        $response->assertSee('Edit Akun');
    }

    public function test_057_account_page_renders_password_verification_modal(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('id="modal-edit"', false);
        $response->assertSee('Verifikasi Kata Sandi');
    }

    public function test_058_account_page_renders_modal_password_input(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('id="input-verify-password"', false);
    }

    public function test_059_account_page_renders_modal_verify_button(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('id="btn-submit-verify"', false);
    }

    public function test_060_account_page_renders_modal_cancel_button(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('btn-cancel-modal', false);
    }

    public function test_061_guest_cannot_access_verify_password_endpoint(): void
    {
        $response = $this->postJson('/account/verify-password', ['password' => 'secret']);
        $response->assertUnauthorized();
    }

    public function test_062_verify_password_fails_with_empty_input(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->actingAs($user)->postJson('/account/verify-password', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_063_verify_password_fails_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password-benar')]);

        $response = $this->actingAs($user)->postJson('/account/verify-password', [
            'password' => 'password-salah',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Password saat ini yang Anda masukkan salah.',
        ]);
    }

    public function test_064_verify_password_succeeds_with_correct_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password-rahasia')]);

        $response = $this->actingAs($user)->postJson('/account/verify-password', [
            'password' => 'password-rahasia',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Verifikasi password berhasil.',
        ]);
    }

    public function test_065_verify_password_does_not_alter_user_state(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Tetap',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user)->postJson('/account/verify-password', ['password' => 'password123']);

        $this->assertSame('Nama Tetap', $user->fresh()->name);
    }

    // =========================================================================
    // MODUL 6: AKUN SAYA UI - FORM POP-UP EDIT PROFIL (Skenario 066 - 075)
    // =========================================================================

    public function test_066_account_page_renders_edit_profile_form_in_modal(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee(route('account.profile.update'));
        $response->assertSee('id="edit-name"', false);
    }

    public function test_067_update_profile_fails_without_current_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Awal',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->put('/account/profile', [
            'name' => 'Nama Baru',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertSame('Nama Awal', $user->fresh()->name);
    }

    public function test_068_update_profile_fails_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Awal',
            'password' => Hash::make('password-benar'),
        ]);

        $response = $this->actingAs($user)->put('/account/profile', [
            'current_password' => 'password-salah',
            'name' => 'Nama Baru',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertSame('Nama Awal', $user->fresh()->name);
    }

    public function test_069_update_profile_fails_with_empty_name(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->actingAs($user)->put('/account/profile', [
            'current_password' => 'password123',
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_070_update_profile_fails_with_name_longer_than_150_chars(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->actingAs($user)->put('/account/profile', [
            'current_password' => 'password123',
            'name' => str_repeat('a', 151),
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_071_update_profile_succeeds_with_valid_name(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->put('/account/profile', [
            'current_password' => 'password123',
            'name' => 'Nama Sukses Diperbarui',
        ]);

        $response->assertRedirect(route('account.edit'));
        $this->assertSame('Nama Sukses Diperbarui', $user->fresh()->name);
    }

    public function test_072_update_profile_syncs_name_to_developer_table(): void
    {
        $dev = Developer::factory()->create([
            'name' => 'Dev Lama',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($dev)->put('/account/profile', [
            'current_password' => 'password123',
            'name' => 'Dev Baru Sinkron',
        ]);

        $response->assertRedirect(route('account.edit'));
        $this->assertSame('Dev Baru Sinkron', $dev->fresh()->name);
    }

    public function test_073_update_profile_with_image_uploads_file(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);
        $file = UploadedFile::fake()->image('profil.jpg', 200, 200);

        $response = $this->actingAs($user)->put('/account/profile', [
            'current_password' => 'password123',
            'name' => $user->name,
            'foto_profil' => $file,
        ]);

        $response->assertRedirect(route('account.edit'));
        $this->assertNotNull($user->fresh()->foto_profil);
        Storage::disk('public')->assertExists($user->fresh()->foto_profil);
    }

    public function test_074_update_profile_rejects_non_image_file(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($user)->put('/account/profile', [
            'current_password' => 'password123',
            'name' => $user->name,
            'foto_profil' => $file,
        ]);

        $response->assertSessionHasErrors(['foto_profil']);
    }

    public function test_075_update_profile_rejects_image_larger_than_2mb(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);
        $file = UploadedFile::fake()->image('huge.jpg')->size(2500); // 2.5 MB

        $response = $this->actingAs($user)->put('/account/profile', [
            'current_password' => 'password123',
            'name' => $user->name,
            'foto_profil' => $file,
        ]);

        $response->assertSessionHasErrors(['foto_profil']);
    }

    // =========================================================================
    // MODUL 7: AKUN SAYA UI - FORM POP-UP EDIT PASSWORD (Skenario 076 - 085)
    // =========================================================================

    public function test_076_account_page_renders_edit_password_form_in_modal(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/account');
        $response->assertOk();
        $response->assertSee('id="edit-new-password"', false);
    }

    public function test_077_update_password_fails_with_empty_current_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->actingAs($user)->put('/account/password', [
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_078_update_password_fails_with_wrong_current_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password-lama')]);

        $response = $this->actingAs($user)->put('/account/password', [
            'current_password' => 'salah-password',
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_079_update_password_fails_with_new_password_less_than_8_chars(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->actingAs($user)->put('/account/password', [
            'current_password' => 'password123',
            'password' => 'pendek',
            'password_confirmation' => 'pendek',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_080_update_password_fails_when_confirmation_does_not_match(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->actingAs($user)->put('/account/password', [
            'current_password' => 'password123',
            'password' => 'password-baru-123',
            'password_confirmation' => 'beda-password-konfirmasi',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_081_update_password_succeeds_with_valid_data(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password-lama-123')]);

        $response = $this->actingAs($user)->put('/account/password', [
            'current_password' => 'password-lama-123',
            'password' => 'password-baru-aman-2026',
            'password_confirmation' => 'password-baru-aman-2026',
        ]);

        $response->assertRedirect(route('account.edit'));
        $this->assertTrue(Hash::check('password-baru-aman-2026', $user->fresh()->password));
    }

    public function test_082_update_password_allows_login_with_new_password(): void
    {
        $user = User::factory()->create([
            'email' => 'login.ganti@bugtracker.test',
            'password' => Hash::make('password-lama-123'),
            'is_active' => true,
        ]);

        $this->actingAs($user)->put('/account/password', [
            'current_password' => 'password-lama-123',
            'password' => 'password-baru-super-2026',
            'password_confirmation' => 'password-baru-super-2026',
        ]);

        auth()->logout();

        $loginResponse = $this->post('/login', [
            'email' => 'login.ganti@bugtracker.test',
            'password' => 'password-baru-super-2026',
        ]);

        $loginResponse->assertRedirect(route('account.edit'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_083_update_password_rejects_login_with_old_password(): void
    {
        $user = User::factory()->create([
            'email' => 'login.lama@bugtracker.test',
            'password' => Hash::make('password-lama-123'),
            'is_active' => true,
        ]);

        $this->actingAs($user)->put('/account/password', [
            'current_password' => 'password-lama-123',
            'password' => 'password-baru-super-2026',
            'password_confirmation' => 'password-baru-super-2026',
        ]);

        auth()->logout();

        $loginResponse = $this->post('/login', [
            'email' => 'login.lama@bugtracker.test',
            'password' => 'password-lama-123',
        ]);

        $loginResponse->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_084_update_password_redirects_with_success_flash_message(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->actingAs($user)->put('/account/password', [
            'current_password' => 'password123',
            'password' => 'password-baru-aman',
            'password_confirmation' => 'password-baru-aman',
        ]);

        $response->assertSessionHas('password_status', 'Password Anda berhasil diperbarui.');
    }

    public function test_085_update_profile_redirects_with_success_flash_message(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->actingAs($user)->put('/account/profile', [
            'current_password' => 'password123',
            'name' => 'Nama Terbarukan',
        ]);

        $response->assertSessionHas('profile_status', 'Data akun Anda berhasil diperbarui.');
    }

    // =========================================================================
    // MODUL 8: PAPAN KANBAN UI - STRUKTUR 4 KOLOM & KARTU (Skenario 086 - 095)
    // =========================================================================

    public function test_086_guest_cannot_access_kanban_board(): void
    {
        $response = $this->get('/kanban');
        $response->assertRedirect(route('login'));
    }

    public function test_087_kanban_board_renders_main_heading(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('Papan Kanban');
    }

    public function test_088_kanban_board_renders_subheading_description(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('Visualisasi alur pengerjaan tugas tim pengembang');
    }

    public function test_089_kanban_board_renders_column_todo(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('id="column-todo"', false);
        $response->assertSee('To Do');
    }

    public function test_090_kanban_board_renders_column_in_progress(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('id="column-in_progress"', false);
        $response->assertSee('In Progress');
    }

    public function test_091_kanban_board_renders_column_review(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('id="column-review"', false);
        $response->assertSee('Review');
    }

    public function test_092_kanban_board_renders_column_done(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('id="column-done"', false);
        $response->assertSee('Done');
    }

    public function test_093_kanban_dropzone_renders_empty_placeholder_when_no_tasks(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('empty-placeholder', false);
        $response->assertSee('Belum ada tugas');
    }

    public function test_094_kanban_card_renders_task_title_and_description(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'title' => 'Implementasi Fitur Export Excel',
            'description' => 'Mengekspor laporan aktivitas ke file .xlsx',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('Implementasi Fitur Export Excel');
        $response->assertSee('Mengekspor laporan aktivitas ke file .xlsx');
    }

    public function test_095_kanban_card_renders_project_badge_with_code(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'CRM Apps', 'code' => 'CRM']);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Tugas Modul CRM',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('CRM');
    }

    // =========================================================================
    // MODUL 9: PAPAN KANBAN UI - FILTER INSTAN, NAVIGASI & EDIT (Skenario 096 - 110)
    // =========================================================================

    public function test_096_kanban_card_renders_task_type_badge(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'task_type' => 'enhancement',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('Peningkatan');
    }

    public function test_097_kanban_card_renders_assignee_name(): void
    {
        $user = User::factory()->create();
        $dev = Developer::factory()->create(['name' => 'Doni Wahyudi']);
        $task = Task::factory()->create([
            'assigned_to' => $dev->id,
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('Doni Wahyudi');
    }

    public function test_098_kanban_card_renders_blocker_notes_alert_when_present(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'blocker_notes' => 'Menunggu persetujuan API Gateway',
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('Kendala:');
        $response->assertSee('Menunggu persetujuan API Gateway');
    }

    public function test_099_kanban_card_renders_deadline_target_date(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'target_date' => '2026-12-25',
            'status' => 'todo',
        ]);

        $formatted = \Carbon\Carbon::parse('2026-12-25')->translatedFormat('d M Y');

        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee($formatted);
    }

    public function test_100_kanban_board_renders_quick_navigation_buttons(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('Navigasi Cepat:');
        $response->assertSee('Semua Kolom');
        $response->assertSee('btn-quick-nav', false);
    }

    public function test_101_kanban_board_renders_instant_project_dropdown_filter(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'HRIS System', 'code' => 'HRIS']);

        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('id="filter-project"', false);
        $response->assertSee('[HRIS] HRIS System');
    }

    public function test_102_kanban_board_renders_instant_developer_dropdown_filter(): void
    {
        $user = User::factory()->create();
        $dev = Developer::factory()->create(['name' => 'Gilang Ramadhan']);

        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('id="filter-developer"', false);
        $response->assertSee('Gilang Ramadhan');
    }

    public function test_103_kanban_board_renders_instant_reset_filters_button(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('id="btn-reset-filters"', false);
        $response->assertSee('Reset Filter');
    }

    public function test_104_kanban_card_renders_data_project_and_developer_attributes(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $dev = Developer::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $dev->id,
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('data-project-id="' . $project->id . '"', false);
        $response->assertSee('data-assigned-to="' . $dev->id . '"', false);
    }

    public function test_105_kanban_card_renders_edit_button_and_edit_modal(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['title' => 'Tugas Siap Diedit', 'status' => 'todo']);

        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('btn-edit-task', false);
        $response->assertSee('id="modal-edit-task"', false);
        $response->assertSee('Edit Tugas');
    }

    public function test_106_kanban_edit_modal_updates_task_details_via_put_json(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'title' => 'Judul Sebelum Edit',
            'task_type' => 'feature',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->putJson("/kanban/tasks/{$task->id}", [
            'title' => 'Judul Setelah Diedit Sempurna',
            'task_type' => 'enhancement',
            'status' => 'in_progress',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Data tugas berhasil diperbarui.',
        ]);

        $this->assertSame('Judul Setelah Diedit Sempurna', $task->fresh()->title);
        $this->assertSame('enhancement', $task->fresh()->task_type);
        $this->assertSame('in_progress', $task->fresh()->status);
    }

    public function test_107_kanban_instant_status_change_via_patch_updates_status(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['status' => 'todo']);

        $response = $this->actingAs($user)->patchJson("/kanban/tasks/{$task->id}/status", [
            'status' => 'in_progress',
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertSame('in_progress', $task->fresh()->status);
    }

    public function test_108_kanban_instant_status_change_to_done_sets_completed_at(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['status' => 'in_progress', 'completed_at' => null]);

        $response = $this->actingAs($user)->patchJson("/kanban/tasks/{$task->id}/status", [
            'status' => 'done',
        ]);

        $response->assertOk();
        $this->assertSame('done', $task->fresh()->status);
        $this->assertNotNull($task->fresh()->completed_at);
    }

    public function test_109_kanban_instant_status_change_from_done_clears_completed_at(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['status' => 'done', 'completed_at' => now()]);

        $response = $this->actingAs($user)->patchJson("/kanban/tasks/{$task->id}/status", [
            'status' => 'todo',
        ]);

        $response->assertOk();
        $this->assertSame('todo', $task->fresh()->status);
        $this->assertNull($task->fresh()->completed_at);
    }

    public function test_110_kanban_instant_status_change_validates_invalid_status(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['status' => 'todo']);

        $response = $this->actingAs($user)->patchJson("/kanban/tasks/{$task->id}/status", [
            'status' => 'invalid_status_value',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['status']);
        $this->assertSame('todo', $task->fresh()->status);
    }
}
