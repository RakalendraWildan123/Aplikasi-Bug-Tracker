<?php

namespace Tests\Feature;

use App\Models\ActionItem;
use App\Models\AppEvaluation;
use App\Models\Bug;
use App\Models\Project;
use App\Models\RepoHistory;
use App\Models\Task;
use App\Models\User;
use App\Services\AppEvaluationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppEvaluationAutomationTest extends TestCase
{
    use RefreshDatabase;

    public function test_system_automatically_evaluates_project_from_tasks_and_bugs(): void
    {
        $project = Project::factory()->create(['name' => 'Sistem Payroll Otomatis', 'code' => 'PAY']);
        $dev = User::factory()->create();

        // 1. Buat tugas dengan blocker
        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Integrasi API Bank',
            'status' => 'in_progress',
            'blocker_notes' => 'Menunggu kredensial sandbox dari pihak bank',
            'assigned_to' => $dev->id,
        ]);
        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Setup Database Payroll',
            'status' => 'done',
            'assigned_to' => $dev->id,
        ]);

        // 2. Buat bug kritis
        Bug::factory()->create([
            'project_id' => $project->id,
            'title' => 'Perhitungan PPh21 Selisih Pembulatan',
            'severity' => 'critical',
            'status' => 'open',
            'assigned_to' => $dev->id,
        ]);
        Bug::factory()->create([
            'project_id' => $project->id,
            'title' => 'Typo Label Cetak Slip',
            'severity' => 'low',
            'status' => 'resolved',
            'assigned_to' => $dev->id,
        ]);

        // 3. Buat rilis
        RepoHistory::factory()->create([
            'project_id' => $project->id,
            'release_tags' => 'v1.0.0',
            'release_date' => now()->toDateString(),
        ]);

        $service = new AppEvaluationService();
        $evaluation = $service->generateForProject($project, 'Q4 2026');

        $this->assertInstanceOf(AppEvaluation::class, $evaluation);
        $this->assertSame($project->id, $evaluation->project_id);
        $this->assertSame('Q4 2026', $evaluation->period_review);

        // Pastikan kendala operasional mendeteksi bug kritis dan tugas blocker
        $this->assertStringContainsString('Perhitungan PPh21 Selisih Pembulatan', $evaluation->operational_issues);
        $this->assertStringContainsString('Integrasi API Bank', $evaluation->operational_issues);
        $this->assertStringContainsString('Kritis', $evaluation->operational_issues);

        // Pastikan feedback dan performa mendeteksi tingkat penyelesaian tugas dan bug
        $this->assertStringContainsString('Penyelesaian tugas 50%', $evaluation->user_feedback);
        $this->assertStringContainsString('v1.0.0', $evaluation->user_feedback);
        $this->assertStringContainsString('Perlu Tindakan Cepat', $evaluation->user_feedback);

        // Pastikan action items otomatis terbuat untuk bug kritis dan blocker
        $this->assertDatabaseHas('action_items', [
            'evaluation_id' => $evaluation->id,
            'action_plan' => 'Perbaiki bug [critical]: Perhitungan PPh21 Selisih Pembulatan',
            'assigned_to' => $dev->id,
            'status' => 'planned',
        ]);
        $this->assertDatabaseHas('action_items', [
            'evaluation_id' => $evaluation->id,
            'action_plan' => 'Selesaikan kendala tugas: Integrasi API Bank',
            'assigned_to' => $dev->id,
            'status' => 'planned',
        ]);
    }

    public function test_user_can_trigger_auto_sync_via_post(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Sistem Gudang Terpadu', 'code' => 'SGT']);

        $response = $this->actingAs($user)->post('/evaluations/auto-sync');

        $response->assertRedirect('/evaluations');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('app_evaluations', [
            'project_id' => $project->id,
            'period_review' => AppEvaluationService::currentPeriod(),
        ]);
    }

    public function test_user_can_trigger_sync_for_single_evaluation(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Sistem Logistik', 'code' => 'LOG']);

        $evaluation = AppEvaluation::factory()->create([
            'project_id' => $project->id,
            'period_review' => 'Q4 2026',
            'operational_issues' => 'Isu lama',
        ]);

        Bug::factory()->create([
            'project_id' => $project->id,
            'title' => 'GPS tracker disconnect timeout',
            'severity' => 'critical',
            'status' => 'open',
        ]);

        $response = $this->actingAs($user)->post("/evaluations/{$evaluation->id}/sync");

        $response->assertRedirect('/evaluations');
        $response->assertSessionHas('status');

        $fresh = $evaluation->fresh();
        $this->assertStringContainsString('GPS tracker disconnect timeout', $fresh->operational_issues);
    }

    public function test_console_command_generates_evaluations(): void
    {
        $project = Project::factory()->create(['name' => 'Proyek Console Test', 'code' => 'PCT']);

        $this->artisan('evaluations:generate', ['--period' => 'Q4 2026'])
            ->expectsOutputToContain('Berhasil mengevaluasi dan menyinkronkan')
            ->assertSuccessful();

        $this->assertDatabaseHas('app_evaluations', [
            'project_id' => $project->id,
            'period_review' => 'Q4 2026',
        ]);
    }
}
