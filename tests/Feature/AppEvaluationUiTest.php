<?php

namespace Tests\Feature;

use App\Models\ActionItem;
use App\Models\AppEvaluation;
use App\Models\Developer;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppEvaluationUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_evaluations(): void
    {
        $response = $this->get('/evaluations');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_evaluations_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/evaluations');

        $response->assertOk();
        $response->assertSee('Evaluasi Aplikasi');
        $response->assertSee('Fitur Operasional');
        $response->assertDontSee('btn-open-create-evaluation', false);
    }

    public function test_evaluations_page_renders_sidebar_navigation_with_categories(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/evaluations');

        $response->assertOk();
        $response->assertSee('Fitur Operasional');
        $response->assertSee('Papan Kanban');
        $response->assertSee('Daftar Tugas');
        $response->assertSee('Pelacak Bug');
        $response->assertSee('Riwayat Rilis');
        $response->assertSee('Evaluasi Aplikasi');
        $response->assertSee('Fitur Master Data');
        $response->assertSee('Master Proyek');
        $response->assertSee('Master Modul');
        $response->assertSee('Master Akun');
        $response->assertSee('Pengaturan');
        $response->assertSee('Akun Saya');
    }

    public function test_evaluations_page_renders_summary_metric_cards(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $evaluation = AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-19',
            'period_review' => 'Q4 2026',
            'operational_issues' => 'Kendala memori server.',
            'user_feedback' => 'Sistem cepat.',
        ]);

        ActionItem::create([
            'evaluation_id' => $evaluation->id,
            'action_plan' => 'Tingkatkan spesifikasi server.',
            'status' => 'planned',
        ]);

        $response = $this->actingAs($user)->get('/evaluations');

        $response->assertOk();
        $response->assertSee('Total Evaluasi');
        $response->assertSee('Proyek Dievaluasi');
        $response->assertSee('Tindak Lanjut');
        $response->assertSee('Isu Operasional');
    }

    public function test_evaluations_page_renders_filter_and_search_bar(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'name' => 'Sistem Logistik Sentral',
            'code' => 'SLS',
        ]);

        $response = $this->actingAs($user)->get('/evaluations');

        $response->assertOk();
        $response->assertSee('placeholder="Cari periode, isu operasional, umpan balik, atau proyek..."', false);
        $response->assertSee('Semua Proyek');
        $response->assertSee('[SLS] Sistem Logistik Sentral');
        $response->assertSee('Semua Periode');
    }

    public function test_evaluations_page_renders_table_with_data_and_action_items_count(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'name' => 'Sistem Gudang Pintar',
            'code' => 'SGP',
        ]);

        $eval = AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-19',
            'period_review' => 'Sprint 20',
            'operational_issues' => 'Koneksi barcode scanner sempat terputus.',
            'user_feedback' => 'Pengguna meminta feedback suara scan.',
        ]);

        ActionItem::create([
            'evaluation_id' => $eval->id,
            'action_plan' => 'Update firmware barcode receiver.',
            'status' => 'planned',
        ]);

        $response = $this->actingAs($user)->get('/evaluations');

        $response->assertOk();
        $response->assertSee('Sprint 20');
        $response->assertSee('[SGP]');
        $response->assertSee('Sistem Gudang Pintar');
        $response->assertSee('Koneksi barcode scanner sempat terputus.');
        $response->assertSee('Pengguna meminta feedback suara scan.');
        $response->assertSee('1 Tindak Lanjut');
        $response->assertSee('btn-detail-evaluation', false);
        $response->assertSee('btn-edit-evaluation', false);
        $response->assertSee('btn-delete-evaluation', false);
    }

    public function test_evaluations_filter_by_search_project_and_period(): void
    {
        $user = User::factory()->create();
        $projectA = Project::factory()->create(['name' => 'Sistem Alpha', 'code' => 'SALPHA']);
        $projectB = Project::factory()->create(['name' => 'Sistem Beta', 'code' => 'SBETA']);

        AppEvaluation::create([
            'project_id' => $projectA->id,
            'evaluation_date' => '2026-09-10',
            'period_review' => 'Q3 2026 Alpha',
            'operational_issues' => 'Kendala unik Alpha.',
            'user_feedback' => 'Feedback unik Alpha.',
        ]);

        AppEvaluation::create([
            'project_id' => $projectB->id,
            'evaluation_date' => '2026-11-15',
            'period_review' => 'Q4 2026 Beta',
            'operational_issues' => 'Kendala unik Beta.',
            'user_feedback' => 'Feedback unik Beta.',
        ]);

        // Search test
        $searchRes = $this->actingAs($user)->get('/evaluations?search=Kendala+unik+Alpha');
        $searchRes->assertOk();
        $searchRes->assertSee('Kendala unik Alpha');
        $searchRes->assertDontSee('Kendala unik Beta');

        // Project filter test
        $projRes = $this->actingAs($user)->get("/evaluations?project_id={$projectB->id}");
        $projRes->assertOk();
        $projRes->assertSee('Kendala unik Beta');
        $projRes->assertDontSee('Kendala unik Alpha');

        // Period filter test
        $periodRes = $this->actingAs($user)->get('/evaluations?period=Q3+2026+Alpha');
        $periodRes->assertOk();
        $periodRes->assertSee('Kendala unik Alpha');
        $periodRes->assertDontSee('Kendala unik Beta');
    }

    public function test_user_can_create_new_evaluation_via_post(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->post('/evaluations', [
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-19',
            'period_review' => 'Q4 2026',
            'operational_issues' => 'Beban kueri laporan tinggi pada jam kantor.',
            'user_feedback' => 'Klien meminta ekspor excel dipercepat.',
        ]);

        $response->assertRedirect('/evaluations');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('app_evaluations', [
            'project_id' => $project->id,
            'period_review' => 'Q4 2026',
            'operational_issues' => 'Beban kueri laporan tinggi pada jam kantor.',
            'user_feedback' => 'Klien meminta ekspor excel dipercepat.',
        ]);
    }

    public function test_user_can_create_evaluation_with_initial_action_item(): void
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->post('/evaluations', [
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-19',
            'period_review' => 'Sprint 30',
            'operational_issues' => 'Server redis kehabisan memori.',
            'initial_action_plan' => 'Terapkan auto-purge key redis kadaluarsa.',
            'initial_assigned_to' => $developer->id,
            'initial_target_date' => '2026-11-25',
        ]);

        $response->assertRedirect('/evaluations');

        $eval = AppEvaluation::where('period_review', 'Sprint 30')->firstOrFail();
        $this->assertDatabaseHas('action_items', [
            'evaluation_id' => $eval->id,
            'action_plan' => 'Terapkan auto-purge key redis kadaluarsa.',
            'assigned_to' => $developer->id,
        ]);
        $item = ActionItem::where('evaluation_id', $eval->id)->firstOrFail();
        $this->assertSame('2026-11-25', $item->target_date?->toDateString());
    }

    public function test_create_evaluation_via_json_returns_201(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->postJson('/evaluations', [
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-19',
            'period_review' => 'Q1 2027 Preview',
            'operational_issues' => null,
            'user_feedback' => 'Semua fitur berjalan stabil.',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('evaluation.period_review', 'Q1 2027 Preview');
    }

    public function test_create_evaluation_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/evaluations', [
            'project_id' => '',
            'evaluation_date' => '',
            'period_review' => '',
        ]);

        $response->assertSessionHasErrors(['project_id', 'evaluation_date', 'period_review']);
    }

    public function test_create_evaluation_validates_unique_period_per_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-10',
            'period_review' => 'Q4 2026',
        ]);

        $response = $this->actingAs($user)->post('/evaluations', [
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-19',
            'period_review' => 'Q4 2026',
        ]);

        $response->assertSessionHasErrors(['period_review']);
    }

    public function test_user_can_update_evaluation_via_put(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $evaluation = AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-10',
            'period_review' => 'Q3 2026',
            'operational_issues' => 'Isu lama.',
            'user_feedback' => 'Feedback lama.',
        ]);

        $response = $this->actingAs($user)->put("/evaluations/{$evaluation->id}", [
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-12',
            'period_review' => 'Q3 2026 Updated',
            'operational_issues' => 'Isu baru telah diupdate.',
            'user_feedback' => 'Feedback baru telah diupdate.',
        ]);

        $response->assertRedirect('/evaluations');

        $this->assertDatabaseHas('app_evaluations', [
            'id' => $evaluation->id,
            'period_review' => 'Q3 2026 Updated',
            'operational_issues' => 'Isu baru telah diupdate.',
            'user_feedback' => 'Feedback baru telah diupdate.',
        ]);
    }

    public function test_user_can_delete_evaluation_via_delete(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $evaluation = AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-10',
            'period_review' => 'Periode Hapus',
        ]);

        ActionItem::create([
            'evaluation_id' => $evaluation->id,
            'action_plan' => 'Tindak lanjut yang ikut terhapus.',
        ]);

        $response = $this->actingAs($user)->delete("/evaluations/{$evaluation->id}");

        $response->assertRedirect('/evaluations');
        $this->assertDatabaseMissing('app_evaluations', ['id' => $evaluation->id]);
        $this->assertDatabaseMissing('action_items', ['action_plan' => 'Tindak lanjut yang ikut terhapus.']);
    }

    public function test_evaluations_page_renders_create_edit_delete_and_detail_modals(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/evaluations');

        $response->assertOk();
        $response->assertSee('id="modal-create-evaluation"', false);
        $response->assertSee('id="modal-edit-evaluation"', false);
        $response->assertSee('id="modal-delete-evaluation"', false);
        $response->assertSee('id="modal-detail-evaluation"', false);
    }

    public function test_evaluations_sidebar_does_not_contain_logout_button(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/evaluations');

        $response->assertOk();

        $content = $response->getContent();
        $sidebarPos = strpos($content, 'id="app-sidebar"');
        $sidebarEnd = strpos($content, '</aside>', $sidebarPos);
        $sidebarHtml = substr($content, $sidebarPos, $sidebarEnd - $sidebarPos);

        $this->assertStringNotContainsString(route('logout'), $sidebarHtml);
        $this->assertStringNotContainsString('>Logout<', $sidebarHtml);
    }

    public function test_user_can_store_action_item_under_evaluation(): void
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create();
        $project = Project::factory()->create();

        $evaluation = AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-19',
            'period_review' => 'Q4 2026 Action Test',
        ]);

        $response = $this->actingAs($user)->post("/evaluations/{$evaluation->id}/action-items", [
            'action_plan' => 'Refactor modul ekspor CSV.',
            'assigned_to' => $developer->id,
            'target_date' => '2026-11-30',
            'status' => 'planned',
        ]);

        $response->assertRedirect('/evaluations');

        $this->assertDatabaseHas('action_items', [
            'evaluation_id' => $evaluation->id,
            'action_plan' => 'Refactor modul ekspor CSV.',
            'assigned_to' => $developer->id,
            'status' => 'planned',
        ]);
    }

    public function test_user_can_update_action_item_status(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $evaluation = AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-19',
            'period_review' => 'Q4 2026 Status Update',
        ]);

        $item = ActionItem::create([
            'evaluation_id' => $evaluation->id,
            'action_plan' => 'Implementasi rate limiter API.',
            'status' => 'planned',
        ]);

        $response = $this->actingAs($user)->patch("/evaluations/action-items/{$item->id}/status", [
            'status' => 'completed',
        ]);

        $response->assertRedirect('/evaluations');
        $this->assertDatabaseHas('action_items', [
            'id' => $item->id,
            'status' => 'completed',
        ]);
    }

    public function test_user_can_delete_action_item(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $evaluation = AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-19',
            'period_review' => 'Q4 2026 Action Delete',
        ]);

        $item = ActionItem::create([
            'evaluation_id' => $evaluation->id,
            'action_plan' => 'Item yang akan dihapus mandiri.',
        ]);

        $response = $this->actingAs($user)->delete("/evaluations/action-items/{$item->id}");

        $response->assertRedirect('/evaluations');
        $this->assertDatabaseMissing('action_items', ['id' => $item->id]);
    }

    public function test_user_can_view_single_evaluation_via_json(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Proyek Single Eval']);

        $evaluation = AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-19',
            'period_review' => 'Single Eval Q4',
            'operational_issues' => 'Isu tunggal.',
        ]);

        $response = $this->actingAs($user)->getJson("/evaluations/{$evaluation->id}");

        $response->assertOk();
        $response->assertJsonPath('evaluation.period_review', 'Single Eval Q4');
        $response->assertJsonPath('evaluation.project.name', 'Proyek Single Eval');
    }

    public function test_user_can_convert_action_item_to_task_via_post(): void
    {
        $user = User::factory()->create();
        $developer = User::factory()->create(['name' => 'Dev Tindak Lanjut']);
        $project = Project::factory()->create(['name' => 'Proyek Konversi Task']);

        $evaluation = AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-20',
            'period_review' => 'Periode 2026-11',
            'operational_issues' => 'Performa query lambat saat ekspor laporan.',
        ]);

        $item = ActionItem::create([
            'evaluation_id' => $evaluation->id,
            'action_plan' => 'Optimalisasi indeks database pada tabel transaksi',
            'assigned_to' => $developer->id,
            'target_date' => '2026-11-28',
            'status' => 'planned',
        ]);

        $response = $this->actingAs($user)->postJson("/evaluations/action-items/{$item->id}/convert-to-task");

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);

        // Verifikasi task baru terbuat di database
        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'assigned_to' => $developer->id,
            'title' => 'Optimalisasi indeks database pada tabel transaksi',
            'status' => 'todo',
        ]);

        // Verifikasi action item berstatus converted_to_task dan memiliki converted_task_id
        $freshItem = $item->fresh();
        $this->assertSame('converted_to_task', $freshItem->status);
        $this->assertNotNull($freshItem->converted_task_id);
    }

    public function test_cannot_convert_already_converted_action_item(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Proyek Double Convert']);

        $evaluation = AppEvaluation::create([
            'project_id' => $project->id,
            'evaluation_date' => '2026-11-20',
            'period_review' => 'Periode 2026-11',
        ]);

        $existingTask = Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Tugas Hasil Konversi Lama',
        ]);

        $item = ActionItem::create([
            'evaluation_id' => $evaluation->id,
            'action_plan' => 'Rencana yang sudah pernah dikonversi',
            'status' => 'converted_to_task',
            'converted_task_id' => $existingTask->id,
        ]);

        $response = $this->actingAs($user)->postJson("/evaluations/action-items/{$item->id}/convert-to-task");

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Action item ini sudah dikonversi menjadi tugas sebelumnya.',
        ]);
    }
}
