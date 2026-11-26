<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Module;
use App\Models\Project;
use App\Models\RepoHistory;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HundredTrialsFullSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: int}>
     */
    public static function trialProvider(): array
    {
        $trials = [];
        for ($i = 1; $i <= 100; $i++) {
            $trials[sprintf('trial_%03d', $i)] = [$i];
        }

        return $trials;
    }

    #[DataProvider('trialProvider')]
    public function test_full_system_lifecycle_100_trials(int $trialNumber): void
    {
        // 1. Inisialisasi Aktor (Dev A dengan password default 'password', Dev B dengan password custom)
        $admin = User::factory()->admin()->create([
            'name' => "Admin T{$trialNumber}",
            'password' => 'admin-aman-2026',
        ]);
        $devA = User::factory()->create([
            'name' => "Dev A T{$trialNumber}",
            'password' => 'password',
        ]);
        $devB = User::factory()->create([
            'name' => "Dev B T{$trialNumber}",
            'password' => 'custom-pass-2026',
        ]);

        // 2. Cek Notifikasi Password Default & Akun Saya
        $this->actingAs($devA)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('id="default-password-alert"', false);

        $this->actingAs($devB)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('id="default-password-alert"', false);

        // 3. Proyek & Modul
        $project = Project::factory()->create([
            'name' => "Proyek T{$trialNumber}",
            'code' => 'P' . str_pad((string) $trialNumber, 3, '0', STR_PAD_LEFT) . substr(uniqid(), -2),
        ]);
        $module = Module::factory()->create([
            'project_id' => $project->id,
            'name' => "Modul T{$trialNumber}",
        ]);

        // 4. Bug oleh Dev A (otomatis assigned_to Dev A) & Bug oleh Admin (open, unassigned)
        $this->actingAs($devA)->post(route('bugs.store'), [
            'project_id' => $project->id,
            'module_id' => $module->id,
            'title' => "Bug Dev T{$trialNumber}",
            'severity' => 'high',
            'status' => 'open',
            'description' => 'Deskripsi bug uji coba 100x',
        ])->assertRedirect(route('bugs.index'));

        $devBug = Bug::where('title', "Bug Dev T{$trialNumber}")->firstOrFail();
        $this->assertSame($devA->id, $devBug->assigned_to);

        $this->actingAs($admin)->post(route('bugs.store'), [
            'project_id' => $project->id,
            'module_id' => $module->id,
            'title' => "Bug Admin T{$trialNumber}",
            'severity' => 'critical',
            'status' => 'open',
            'description' => 'Deskripsi bug admin uji coba 100x',
        ])->assertRedirect(route('bugs.index'));

        $adminBug = Bug::where('title', "Bug Admin T{$trialNumber}")->firstOrFail();
        $this->assertNull($adminBug->assigned_to);

        // 5. Task oleh Admin (terbuka) -> diambil langsung oleh Dev A
        $this->actingAs($admin)->post(route('tasks.store'), [
            'project_id' => $project->id,
            'module_id' => $module->id,
            'title' => "Task Terbuka T{$trialNumber}",
            'task_type' => 'feature',
            'status' => 'todo',
            'target_date' => now('Asia/Jakarta')->addDays(5)->format('Y-m-d'),
        ])->assertRedirect(route('tasks.index'));

        $task = Task::where('title', "Task Terbuka T{$trialNumber}")->firstOrFail();
        $this->assertNull($task->assigned_to);

        $this->actingAs($devA)->postJson("/tasks/{$task->id}/takeover", [
            'note' => "Ambil tugas terbuka #{$trialNumber}",
        ])->assertOk()->assertJson(['success' => true, 'pending' => false]);

        $this->assertSame($devA->id, $task->fresh()->assigned_to);

        // 6. Alihkan Task dari Dev A ke Dev B -> Pending hingga Dev B menyetujui
        $this->actingAs($devA)->postJson("/tasks/{$task->id}/transfer", [
            'target_developer_id' => $devB->id,
            'note' => "Oper ke Dev B #{$trialNumber}",
        ])->assertOk()->assertJson(['success' => true, 'pending' => true]);

        $this->assertSame($devA->id, $task->fresh()->assigned_to);

        $this->actingAs($devB)->postJson("/tasks/{$task->id}/handover/approve")
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertSame($devB->id, $task->fresh()->assigned_to);
        $this->assertNull($task->fresh()->pending_handover);

        // 7. Dev A minta ambil alih dari Dev B -> Dev B menolak -> Tetap milik Dev B
        $this->actingAs($devA)->postJson("/tasks/{$task->id}/takeover", [
            'note' => "Minta ambil alih #{$trialNumber}",
        ])->assertOk()->assertJson(['success' => true, 'pending' => true]);

        $this->actingAs($devB)->postJson("/tasks/{$task->id}/handover/reject", [
            'reason' => 'Masih dikerjakan',
        ])->assertOk();

        $this->assertSame($devB->id, $task->fresh()->assigned_to);
        $this->assertNull($task->fresh()->pending_handover);

        // 8. Kanban Update Status oleh Dev B (pemilik sah)
        $this->actingAs($devB)->patchJson("/kanban/tasks/{$task->id}/status", [
            'status' => 'in_progress',
        ])->assertOk();

        $this->assertSame('in_progress', $task->fresh()->status);

        // 9. Laporan Periodik, Evaluasi, Riwayat Rilis & Proteksi RBAC Admin
        $this->actingAs($devB)->get(route('reports.index'))->assertOk();
        $this->actingAs($devB)->post(route('evaluations.auto-sync'))->assertRedirect(route('evaluations.index'));
        RepoHistory::factory()->create(['project_id' => $project->id, 'release_tags' => "v2.{$trialNumber}.0"]);

        $this->actingAs($devA)->get(route('admin.accounts.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.accounts.index'))->assertOk();
    }
}
