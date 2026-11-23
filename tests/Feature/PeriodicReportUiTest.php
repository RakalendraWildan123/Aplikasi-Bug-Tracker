<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Developer;
use App\Models\Project;
use App\Models\RepoHistory;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeriodicReportUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_reports(): void
    {
        $response = $this->get('/reports');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_reports_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/reports');

        $response->assertOk();
        $response->assertSee('Laporan Periodik');
        $response->assertSee('Fitur Operasional');
        $response->assertDontSee('btn-export-csv', false);
        $response->assertDontSee('Ekspor CSV');
        $response->assertSee('btn-print-report', false);
    }

    public function test_reports_page_renders_sidebar_navigation_with_categories(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/reports');

        $response->assertOk();
        $response->assertSee('Fitur Operasional');
        $response->assertSee('Papan Kanban');
        $response->assertSee('Daftar Tugas');
        $response->assertSee('Pelacak Bug');
        $response->assertSee('Riwayat Rilis');
        $response->assertSee('Evaluasi Aplikasi');
        $response->assertSee('Laporan Periodik');
        $response->assertSee('Fitur Master Data');
        $response->assertSee('Master Proyek');
        $response->assertSee('Master Modul');
        $response->assertSee('Master Akun');
        $response->assertSee('Pengaturan');
        $response->assertSee('Akun Saya');
    }

    public function test_reports_page_renders_summary_metric_cards(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Task::factory()->create([
            'project_id' => $project->id,
            'status' => 'done',
        ]);

        Bug::factory()->create([
            'project_id' => $project->id,
            'status' => 'resolved',
        ]);

        RepoHistory::create([
            'project_id' => $project->id,
            'release_date' => '2026-11-18',
            'branch_name' => 'main',
            'commit_hash' => '1111111111222222222233333333334444444444',
            'release_tags' => 'v1.0.0',
        ]);

        $response = $this->actingAs($user)->get('/reports');

        $response->assertOk();
        $response->assertSee('Tugas Selesai');
        $response->assertSee('Bug Tertangani');
        $response->assertSee('Rilis Repositori');
        $response->assertSee('Tindak Lanjut Evaluasi');
    }

    public function test_reports_page_renders_filter_bar_and_preset_buttons(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'name' => 'Sistem Rekam Medis',
            'code' => 'SRM',
        ]);

        $response = $this->actingAs($user)->get('/reports');

        $response->assertOk();
        $response->assertSee('Semua Data');
        $response->assertSee('7 Hari Terakhir');
        $response->assertSee('30 Hari Terakhir');
        $response->assertSee('Bulan Ini');
        $response->assertSee('Kuartal Ini');
        $response->assertSee('Tahun Ini');
        $response->assertSee('Semua Proyek');
        $response->assertSee('[SRM] Sistem Rekam Medis');
        $response->assertSee('Semua Developer');
    }

    public function test_reports_page_renders_project_breakdown_table(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'name' => 'Portal Kepegawaian ASN',
            'code' => 'PKASN',
            'color' => '#0EA5E9',
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Tugas ASN Unik',
            'status' => 'done',
        ]);

        Bug::factory()->create([
            'project_id' => $project->id,
            'title' => 'Bug ASN Unik',
            'status' => 'resolved',
        ]);

        $response = $this->actingAs($user)->get('/reports');

        $response->assertOk();
        $response->assertSee('[PKASN]');
        $response->assertSee('Portal Kepegawaian ASN');
        $response->assertSee('Rekapitulasi Kinerja Berdasarkan Proyek');
    }

    public function test_reports_page_renders_task_and_bug_detail_tables(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Integrasi Gateway Pembayaran QRIS',
            'task_type' => 'feature',
            'status' => 'in_progress',
        ]);

        Bug::factory()->create([
            'project_id' => $project->id,
            'title' => 'Memory Leak pada Worker Notifikasi',
            'severity' => 'critical',
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($user)->get('/reports');

        $response->assertOk();
        $response->assertSee('Integrasi Gateway Pembayaran QRIS');
        $response->assertSee('Memory Leak pada Worker Notifikasi');
    }

    public function test_reports_filter_by_preset_and_project(): void
    {
        $user = User::factory()->create();
        $projectA = Project::factory()->create(['name' => 'Proyek Alpha', 'code' => 'P-ALPHA']);
        $projectB = Project::factory()->create(['name' => 'Proyek Beta', 'code' => 'P-BETA']);

        Task::factory()->create([
            'project_id' => $projectA->id,
            'title' => 'Tugas Khusus Alpha',
        ]);

        Task::factory()->create([
            'project_id' => $projectB->id,
            'title' => 'Tugas Khusus Beta',
        ]);

        $response = $this->actingAs($user)->get("/reports?project_id={$projectA->id}");

        $response->assertOk();
        $response->assertSee('Tugas Khusus Alpha');
        $response->assertDontSee('Tugas Khusus Beta');
    }

    public function test_reports_filter_by_developer(): void
    {
        $user = User::factory()->create();
        $devA = Developer::factory()->create(['name' => 'Developer Alpha']);
        $devB = Developer::factory()->create(['name' => 'Developer Beta']);
        $project = Project::factory()->create();

        Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $devA->id,
            'title' => 'Tugas Milik Dev Alpha',
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $devB->id,
            'title' => 'Tugas Milik Dev Beta',
        ]);

        $response = $this->actingAs($user)->get("/reports?developer_id={$devA->id}");

        $response->assertOk();
        $response->assertSee('Tugas Milik Dev Alpha');
        $response->assertDontSee('Tugas Milik Dev Beta');
    }

    public function test_reports_json_endpoint_returns_metrics_and_breakdown(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Proyek JSON Test', 'code' => 'PJSON']);

        Task::factory()->create([
            'project_id' => $project->id,
            'status' => 'done',
        ]);

        $response = $this->actingAs($user)->getJson('/reports');

        $response->assertOk();
        $response->assertJsonStructure([
            'metrics' => [
                'total_tasks',
                'done_tasks',
                'task_rate',
                'total_bugs',
                'resolved_bugs',
                'bug_rate',
                'total_releases',
                'total_evaluations',
            ],
            'project_breakdown',
            'preset',
        ]);
    }

    public function test_reports_export_csv_downloads_valid_csv_stream(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'name' => 'Proyek CSV Ekspor',
            'code' => 'CSVEXP',
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Judul Tugas CSV Unik',
        ]);

        Bug::factory()->create([
            'project_id' => $project->id,
            'title' => 'Judul Bug CSV Unik',
        ]);

        $response = $this->actingAs($user)->get('/reports/export/csv');

        $response->assertOk();
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'laporan-periodik'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('LAPORAN PERIODIK MONITORING IT DEVELOPER', $content);
        $this->assertStringContainsString('Proyek CSV Ekspor', $content);
        $this->assertStringContainsString('Judul Tugas CSV Unik', $content);
        $this->assertStringContainsString('Judul Bug CSV Unik', $content);
    }

    public function test_reports_sidebar_does_not_contain_logout_button(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/reports');

        $response->assertOk();

        $content = $response->getContent();
        $sidebarPos = strpos($content, 'id="app-sidebar"');
        $sidebarEnd = strpos($content, '</aside>', $sidebarPos);
        $sidebarHtml = substr($content, $sidebarPos, $sidebarEnd - $sidebarPos);

        $this->assertStringNotContainsString(route('logout'), $sidebarHtml);
        $this->assertStringNotContainsString('>Logout<', $sidebarHtml);
    }
}
