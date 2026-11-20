<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Project;
use App\Models\RepoHistory;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_to_login_from_root(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create(['name' => 'Raka Pratama', 'role' => 'developer']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertSee('Raka Pratama');
        $response->assertSee('Developer');
    }

    public function test_dashboard_renders_key_metrics_and_quick_links(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $project = Project::factory()->create(['name' => 'Sistem Presensi', 'code' => 'PRESENSI', 'status' => 'active']);

        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Integrasi API Geolocation',
            'status' => 'in_progress',
        ]);

        Bug::factory()->create([
            'project_id' => $project->id,
            'title' => 'GPS Mocking Bypass',
            'severity' => 'critical',
            'status' => 'open',
        ]);

        RepoHistory::factory()->create([
            'project_id' => $project->id,
            'release_tags' => 'v2.1.0',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();

        // 1. Stat cards
        $response->assertSee('Proyek Aktif');
        $response->assertSee('Tugas Berjalan');
        $response->assertSee('Bug Perlu Ditangani');
        $response->assertSee('Rilis Terkini');
        $response->assertSee('v2.1.0');

        // 2. Konten tugas & bug
        $response->assertSee('Integrasi API Geolocation');
        $response->assertSee('GPS Mocking Bypass');
        $response->assertSee('PRESENSI');

        // 3. Pintasan Navigasi
        $response->assertSee('Papan Kanban');
        $response->assertSee('Daftar Tugas');
        $response->assertSee('Pelacak Bug');
        $response->assertSee('Laporan');
    }

    public function test_dashboard_renders_blocker_alert_when_task_has_blocker(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['code' => 'PAYMENT']);

        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Sistem Verifikasi Pembayaran',
            'status' => 'in_progress',
            'blocker_notes' => 'API Gateway timeout saat uji sandbox',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Perhatian: Terdapat 1 Tugas Mengalami Kendala (Blocker)');
        $response->assertSee('API Gateway timeout saat uji sandbox');
        $response->assertSee('Tinjau di Kanban');
    }

    public function test_dashboard_sidebar_does_not_contain_logout_button(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        // Tombol logout tidak boleh ada di sidebar
        $response->assertDontSee('id="btn-sidebar-logout"');
        $response->assertDontSee('Logout dari Sidebar');
    }

    public function test_dashboard_does_not_contain_nested_tabs(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertDontSee('tab-content-nested');
        $response->assertDontSee('sub-tab-panel');
        $response->assertDontSee('tablist-nested');
    }

    public function test_dashboard_renders_notification_triggers_and_modal(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('btn-trigger-notifications');
        $response->assertSee('notification-badge-count');
        $response->assertSee('id="modal-notifications"', false);
    }

    public function test_dashboard_returns_json_when_requested_via_json(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/dashboard');

        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'metrics' => [
                'active_projects',
                'total_projects',
                'tasks_in_progress',
                'total_tasks',
                'open_bugs',
                'total_bugs',
                'total_releases',
            ],
            'recent_tasks',
            'urgent_bugs',
        ]);
        $this->assertSame('online', $response->json('status'));
    }

    public function test_admin_sees_master_akun_in_sidebar(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Master Akun');
    }

    public function test_developer_does_not_see_master_akun_in_sidebar(): void
    {
        $dev = User::factory()->create(['role' => 'developer']);

        $response = $this->actingAs($dev)->get('/dashboard');

        $response->assertOk();
        $response->assertDontSee('Master Akun');
    }
}
