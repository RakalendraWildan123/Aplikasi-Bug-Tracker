<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Developer;
use App\Models\Module;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BugUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_bugs_page(): void
    {
        $response = $this->get('/bugs');
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_bugs_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bugs');

        $response->assertOk();
        $response->assertSee('Pelacak Bug');
        $response->assertSee('Laporkan Bug');
        $response->assertSee('Total Bug');
        $response->assertSee('Open');
        $response->assertSee('In Progress');
        $response->assertSee('Resolved');
        $response->assertSee('Kritis');
    }

    public function test_bugs_page_renders_sidebar_navigation_with_active_state(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bugs');

        $response->assertOk();
        $response->assertSee(route('bugs.index'));
        $response->assertSee(route('tasks.index'));
        $response->assertSee(route('kanban.index'));
        $response->assertSee(route('account.edit'));
        $response->assertDontSee('role="tablist"', false);
        $response->assertDontSee('role="tab"', false);
    }

    public function test_bugs_page_renders_empty_state_when_no_bugs(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bugs');

        $response->assertOk();
        $response->assertSee('Belum Ada Laporan Bug');
    }

    public function test_bugs_page_renders_bug_table_and_items(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Aplikasi Penggajian', 'code' => 'PAYROLL', 'color' => '#10B981']);
        $dev = Developer::factory()->create(['name' => 'Budi Santoso']);
        $module = Module::factory()->create(['project_id' => $project->id, 'name' => 'Modul PPh21']);

        $bug = Bug::factory()->create([
            'project_id' => $project->id,
            'module_id' => $module->id,
            'assigned_to' => $dev->id,
            'title' => 'Perhitungan PPh21 PTKP Selisih Rp 5.000',
            'severity' => 'critical',
            'status' => 'in_progress',
            'reported_by' => 'QA - Sari',
            'reported_date' => '2026-11-18',
            'root_cause' => 'Faktor pembulatan desimal belum sesuai tarif TER',
        ]);

        $response = $this->actingAs($user)->get('/bugs');

        $response->assertOk();
        $response->assertSee('Perhitungan PPh21 PTKP Selisih Rp 5.000');
        $response->assertSee('Modul PPh21');
        $response->assertSee('PAYROLL');
        $response->assertSee('Kritis');
        $response->assertSee('In Progress');
        $response->assertSee('Budi Santoso');
        $response->assertSee('QA - Sari');
        $response->assertSee('Akar Masalah:');
        $response->assertSee('Faktor pembulatan desimal belum sesuai tarif TER');
        $response->assertSee('btn-detail-bug', false);
        $response->assertSee('btn-solution-bug', false);
        $response->assertSee('btn-delete-bug', false);
        $response->assertSee('sticky right-0', false);
        $response->assertSee('Solusi');
        $response->assertSee('Detail');
    }

    public function test_bugs_page_filters_by_search_and_project(): void
    {
        $user = User::factory()->create();
        $projA = Project::factory()->create(['name' => 'Project Alpha', 'code' => 'ALPHA']);
        $projB = Project::factory()->create(['name' => 'Project Beta', 'code' => 'BETA']);

        $bugA = Bug::factory()->create([
            'project_id' => $projA->id,
            'title' => 'Bug Unik Alpha',
            'status' => 'open',
        ]);

        $bugB = Bug::factory()->create([
            'project_id' => $projB->id,
            'title' => 'Bug Unik Beta',
            'status' => 'open',
        ]);

        // Search by keyword
        $searchResponse = $this->actingAs($user)->get('/bugs?search=Alpha');
        $searchResponse->assertOk();
        $searchResponse->assertSee('Bug Unik Alpha');
        $searchResponse->assertDontSee('Bug Unik Beta');

        // Filter by project ID
        $projectResponse = $this->actingAs($user)->get('/bugs?project_id=' . $projB->id);
        $projectResponse->assertOk();
        $projectResponse->assertSee('Bug Unik Beta');
        $projectResponse->assertDontSee('Bug Unik Alpha');
    }

    public function test_bugs_page_filters_by_severity_and_status(): void
    {
        $user = User::factory()->create();

        $openCriticalBug = Bug::factory()->create([
            'title' => 'Bug Kritis Open',
            'severity' => 'critical',
            'status' => 'open',
        ]);

        $resolvedLowBug = Bug::factory()->create([
            'title' => 'Bug Rendah Selesai',
            'severity' => 'low',
            'status' => 'resolved',
        ]);

        // Filter by severity critical
        $severityResponse = $this->actingAs($user)->get('/bugs?severity=critical');
        $severityResponse->assertOk();
        $severityResponse->assertSee('Bug Kritis Open');
        $severityResponse->assertDontSee('Bug Rendah Selesai');

        // Filter by status resolved
        $statusResponse = $this->actingAs($user)->get('/bugs?status=resolved');
        $statusResponse->assertOk();
        $statusResponse->assertSee('Bug Rendah Selesai');
        $statusResponse->assertDontSee('Bug Kritis Open');
    }

    public function test_user_can_create_new_bug_via_post(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->post('/bugs', [
            'project_id' => $project->id,
            'title' => 'Bug Baru Dilaporkan Form',
            'severity' => 'high',
            'status' => 'open',
            'reported_by' => 'User Feedback',
            'reported_date' => '2026-11-19',
            'description' => 'Terjadi error ketika submit form data.',
        ]);

        $response->assertRedirect(route('bugs.index'));
        $response->assertSessionHas('success_message', 'Laporan bug baru berhasil ditambahkan.');

        $this->assertDatabaseHas('bugs', [
            'project_id' => $project->id,
            'title' => 'Bug Baru Dilaporkan Form',
            'severity' => 'high',
            'status' => 'open',
            'created_by' => $user->id,
            'assigned_to' => $user->id,
        ]);
    }

    public function test_create_bug_via_json_returns_201(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->postJson('/bugs', [
            'project_id' => $project->id,
            'title' => 'Bug Baru via AJAX Modal',
            'severity' => 'medium',
            'status' => 'open',
        ]);

        $response->assertStatus(201);
        $this->assertSame($user->id, Bug::where('title', 'Bug Baru via AJAX Modal')->first()->assigned_to);
        $response->assertJson([
            'success' => true,
            'message' => 'Laporan bug baru berhasil ditambahkan.',
        ]);
    }

    public function test_create_bug_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/bugs', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'project_id', 'severity', 'status']);
    }

    public function test_user_can_update_bug_via_put(): void
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create([
            'title' => 'Bug Sebelum Diedit',
            'status' => 'open',
        ]);

        $response = $this->actingAs($user)->putJson("/bugs/{$bug->id}", [
            'project_id' => $bug->project_id,
            'title' => 'Bug Berhasil Diperbaiki',
            'severity' => 'low',
            'status' => 'resolved',
            'solution_notes' => 'Telah dilakukan perbaikan penanganan payload null.',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Data bug berhasil diperbarui.',
        ]);

        $fresh = $bug->fresh();
        $this->assertSame('Bug Berhasil Diperbaiki', $fresh->title);
        $this->assertSame('low', $fresh->severity);
        $this->assertSame('resolved', $fresh->status);
        $this->assertNotNull($fresh->resolved_at);
        $this->assertSame('Telah dilakukan perbaikan penanganan payload null.', $fresh->solution_notes);
    }

    public function test_user_can_delete_bug_via_delete(): void
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create(['title' => 'Bug Akan Dihapus']);

        $response = $this->actingAs($user)->deleteJson("/bugs/{$bug->id}");

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Laporan bug berhasil dihapus.',
        ]);

        $this->assertDatabaseMissing('bugs', ['id' => $bug->id]);
    }

    public function test_bugs_page_renders_create_and_delete_modals(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bugs');

        $response->assertOk();
        $response->assertSee('id="modal-create-bug"', false);
        $response->assertSee('id="modal-detail-bug"', false);
        $response->assertSee('id="modal-solution-bug"', false);
        $response->assertSee('id="modal-delete-bug"', false);
        $response->assertSee('Laporkan Bug Baru');
    }

    public function test_user_can_takeover_bug_from_another_developer(): void
    {
        $devA = User::factory()->create(['name' => 'Developer Alpha']);
        $userB = User::factory()->create(['name' => 'Developer Beta']);

        $bug = Bug::factory()->create([
            'title' => 'Bug Kritis Database Lock',
            'assigned_to' => $devA->id,
        ]);

        $response = $this->actingAs($userB)->postJson("/bugs/{$bug->id}/takeover", [
            'note' => 'Diambil alih untuk investigasi segera',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $freshBug = $bug->fresh(['assignee']);
        $this->assertSame($userB->id, $freshBug->assigned_to);
        $this->assertNotEmpty($freshBug->handover_logs);

        $logs = $freshBug->handover_logs;
        $lastLog = end($logs);
        $this->assertSame($devA->id, $lastLog['from_developer_id']);
        $this->assertSame('Developer Alpha', $lastLog['from_developer_name']);
        $this->assertSame($userB->id, $lastLog['to_developer_id']);
        $this->assertSame('Developer Beta', $lastLog['to_developer_name']);
        $this->assertSame('Diambil alih untuk investigasi segera', $lastLog['note']);
        $this->assertNotNull($lastLog['handed_over_at']);
    }

    public function test_bugs_page_does_not_render_takeover_button_nor_modal(): void
    {
        $currentUser = User::factory()->create(['name' => 'Saya']);
        $otherDev = User::factory()->create(['name' => 'Dev Lain']);

        $bugOther = Bug::factory()->create([
            'title' => 'Bug Dev Lain',
            'assigned_to' => $otherDev->id,
        ]);

        $bugOwn = Bug::factory()->create([
            'title' => 'Bug Milik Saya',
            'assigned_to' => $currentUser->id,
        ]);

        $response = $this->actingAs($currentUser)->get('/bugs');

        $response->assertOk();
        $response->assertDontSee('id="modal-takeover-bug"', false);
        $response->assertDontSee('Konfirmasi Ambil Alih Bug');
        $response->assertDontSee('btn-takeover-bug', false);
    }

    public function test_bugs_sidebar_does_not_contain_logout_button(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bugs');

        $response->assertOk();
        $content = $response->getContent();
        $sidebarPos = strpos($content, 'id="app-sidebar"');
        $sidebarEnd = strpos($content, '</aside>', $sidebarPos);
        $sidebarHtml = substr($content, $sidebarPos, $sidebarEnd - $sidebarPos);

        $this->assertStringNotContainsString('route(\'logout\')', $sidebarHtml);
        $this->assertStringNotContainsString('action="' . route('logout') . '"', $sidebarHtml);
        $this->assertStringNotContainsString('Logout', $sidebarHtml);
    }

    public function test_bugs_page_renders_detail_and_history_modal(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bugs');

        $response->assertOk();
        $response->assertSee('id="modal-detail-bug"', false);
        $response->assertSee('id="modal-solution-bug"', false);
        $response->assertSee('id="form-detail-bug"', false);
        $response->assertSee('id="form-solution-bug"', false);
        $response->assertSee('Simpan Perubahan Detail');
        $response->assertSee('Simpan Solusi');
        $response->assertSee('Riwayat Aktivitas &amp; Pengalihan (Handover)', false);
    }

    public function test_bugs_page_displays_solution_notes_badge_in_table(): void
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->resolved()->create([
            'title' => 'Bug Ekspor PDF Lambat',
            'solution_notes' => 'Menambahkan index komposit dan cache Redis.',
        ]);

        $response = $this->actingAs($user)->get('/bugs');

        $response->assertOk();
        $response->assertSee('Solusi:');
        $response->assertSee('Menambahkan index komposit dan cache Redis.');
        $response->assertSee('btn-detail-bug', false);
        $response->assertSee('btn-solution-bug', false);
        $response->assertSee('Detail');
        $response->assertSee('Solusi');
    }

    public function test_user_can_view_single_bug_detail_via_json(): void
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->resolved()->create([
            'title' => 'Bug Detail Test',
            'solution_notes' => 'Solusi perbaikan tuntas.',
            'handover_logs' => [
                [
                    'from_developer_id' => 1,
                    'from_developer_name' => 'Dev A',
                    'to_developer_id' => 2,
                    'to_developer_name' => 'Dev B',
                    'note' => 'Alih tugas darurat',
                    'handed_over_at' => now()->toIso8601String(),
                ],
            ],
        ]);

        $response = $this->actingAs($user)->getJson("/bugs/{$bug->id}");

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'bug' => [
                'id' => $bug->id,
                'title' => 'Bug Detail Test',
                'solution_notes' => 'Solusi perbaikan tuntas.',
                'status' => 'resolved',
            ],
        ]);
    }

    public function test_developer_who_records_bug_is_automatically_assigned_to_it(): void
    {
        $devA = User::factory()->create(['name' => 'Developer A']);
        $project = Project::factory()->create();

        $response = $this->actingAs($devA)->post('/bugs', [
            'project_id' => $project->id,
            'title' => 'Bug Dicatat Developer A',
            'severity' => 'medium',
            'status' => 'open',
            'description' => 'Bug ini dicatat Developer A tanpa memilih assigned_to secara manual',
        ]);

        $response->assertRedirect(route('bugs.index'));
        $createdBug = Bug::where('title', 'Bug Dicatat Developer A')->firstOrFail();
        $this->assertSame($devA->id, $createdBug->created_by);
        $this->assertSame($devA->id, $createdBug->assigned_to);
    }

    public function test_admin_who_records_bug_creates_open_and_unassigned_bug(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Super Admin']);
        $project = Project::factory()->create();

        $response = $this->actingAs($admin)->post('/bugs', [
            'project_id' => $project->id,
            'title' => 'Bug Temuan Admin',
            'severity' => 'critical',
            'status' => 'open',
            'description' => 'Bug ini dicatat Admin dan terbuka untuk developer',
        ]);

        $response->assertRedirect(route('bugs.index'));
        $createdBug = Bug::where('title', 'Bug Temuan Admin')->firstOrFail();
        $this->assertSame($admin->id, $createdBug->created_by);
        $this->assertNull($createdBug->assigned_to);
        $this->assertSame('open', $createdBug->status);
    }
}
