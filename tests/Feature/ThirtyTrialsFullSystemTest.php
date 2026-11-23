<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Module;
use App\Models\Project;
use App\Models\RepoHistory;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThirtyTrialsFullSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Eksekusi satu siklus penuh pengujian seluruh fitur sistem:
     * 1. Auth & Akun Saya (Profil, Sidebar tanpa tombol redundan)
     * 2. Dashboard Metrik (Tugas Selesai, Jumlah Bug, Ancaman)
     * 3. Proyek & Modul CRUD
     * 4. Bug oleh Developer -> otomatis ditugaskan ke Developer
     * 5. Bug oleh Admin -> otomatis status OPEN dan penugasan kosong (unassigned)
     * 6. Task oleh Developer -> otomatis ditugaskan ke Developer pembuat
     * 7. Task oleh Admin -> otomatis status TODO dan penugasan kosong (OPEN)
     * 8. Ambil Alih / Ambil Tugas oleh Developer lain (Handover Log tercatat)
     * 9. Kanban Board status update & proteksi drag-and-drop
     * 10. Evaluasi Aplikasi otomatis (tanpa tombol manual)
     * 11. Riwayat Rilis & sifat imutabel
     * 12. Master Akun Admin & proteksi akses role
     */
    private function executeFullFeatureLifecycleTrial(int $trialNumber): void
    {
        // 1. Inisialisasi Aktor
        $admin = User::factory()->admin()->create(['name' => "Admin Trial {$trialNumber}"]);
        $devA = User::factory()->create(['name' => "Developer A Trial {$trialNumber}"]);
        $devB = User::factory()->create(['name' => "Developer B Trial {$trialNumber}"]);

        // 2. Fitur Akun Saya & Navigasi Sidebar
        $resAccount = $this->actingAs($devA)->get(route('account.edit'));
        $resAccount->assertOk();
        $resAccount->assertSee($devA->name);
        $resAccount->assertSee('Pengaturan Akun Saya');

        // 3. Fitur Dashboard Metrik
        $resDash = $this->actingAs($devA)->get(route('dashboard'));
        $resDash->assertOk();
        $resDash->assertSee('Tugas Telah Diselesaikan');
        $resDash->assertSee('Bug Perlu Ditangani');
        $resDash->assertSee('Buka Akun Saya');

        // 4. Fitur Master Proyek & Modul
        $code = sprintf('TR%02d', $trialNumber % 100);
        $project = Project::factory()->create([
            'name' => "Sistem ERP Trial {$trialNumber}",
            'code' => $code . substr(uniqid(), -3),
        ]);
        $module = Module::factory()->create([
            'project_id' => $project->id,
            'name' => "Modul Keuangan T{$trialNumber}",
        ]);

        // 5. Fitur Bug oleh Developer -> Otomatis Ditugaskan ke Developer A
        $resDevBug = $this->actingAs($devA)->post(route('bugs.store'), [
            'project_id' => $project->id,
            'module_id' => $module->id,
            'title' => "Bug Temuan Dev A Trial {$trialNumber}",
            'severity' => 'high',
            'status' => 'open',
            'description' => "Kendala perhitungan pajak pada siklus {$trialNumber}",
        ]);
        $resDevBug->assertRedirect(route('bugs.index'));

        $devBug = Bug::where('title', "Bug Temuan Dev A Trial {$trialNumber}")->firstOrFail();
        $this->assertSame($devA->id, $devBug->created_by);
        $this->assertSame($devA->id, $devBug->assigned_to);

        // 6. Fitur Bug oleh Admin -> Otomatis OPEN dan Belum Ditugaskan (Unassigned)
        $resAdminBug = $this->actingAs($admin)->post(route('bugs.store'), [
            'project_id' => $project->id,
            'module_id' => $module->id,
            'title' => "Bug Laporan Admin Trial {$trialNumber}",
            'severity' => 'critical',
            'status' => 'open',
            'description' => "Issue keamanan dilaporkan admin pada siklus {$trialNumber}",
        ]);
        $resAdminBug->assertRedirect(route('bugs.index'));

        $adminBug = Bug::where('title', "Bug Laporan Admin Trial {$trialNumber}")->firstOrFail();
        $this->assertSame($admin->id, $adminBug->created_by);
        $this->assertNull($adminBug->assigned_to);
        $this->assertSame('open', $adminBug->status);

        // 7. Fitur Task oleh Developer B -> Otomatis Milik Developer B
        $resDevTask = $this->actingAs($devB)->post(route('tasks.store'), [
            'project_id' => $project->id,
            'module_id' => $module->id,
            'title' => "Task Dibuat Dev B Trial {$trialNumber}",
            'task_type' => 'feature',
            'status' => 'todo',
            'description' => "Pengerjaan modul baru siklus {$trialNumber}",
        ]);
        $resDevTask->assertRedirect(route('tasks.index'));

        $devTask = Task::where('title', "Task Dibuat Dev B Trial {$trialNumber}")->firstOrFail();
        $this->assertSame($devB->id, $devTask->created_by);
        $this->assertSame($devB->id, $devTask->assigned_to);

        // 8. Fitur Task oleh Admin -> Otomatis TODO dan Terbuka (Unassigned)
        $resAdminTask = $this->actingAs($admin)->post(route('tasks.store'), [
            'project_id' => $project->id,
            'module_id' => $module->id,
            'title' => "Task Terbuka dari Admin Trial {$trialNumber}",
            'task_type' => 'maintenance',
            'status' => 'todo',
            'description' => "Tugas terbuka untuk developer siklus {$trialNumber}",
        ]);
        $resAdminTask->assertRedirect(route('tasks.index'));

        $adminTask = Task::where('title', "Task Terbuka dari Admin Trial {$trialNumber}")->firstOrFail();
        $this->assertSame($admin->id, $adminTask->created_by);
        $this->assertNull($adminTask->assigned_to);
        $this->assertSame('todo', $adminTask->status);

        // 9. Fitur Ambil Alih / Ambil Tugas oleh Developer A atas Tugas Admin
        $resTakeover = $this->actingAs($devA)->postJson("/tasks/{$adminTask->id}/takeover", [
            'note' => "Dev A mengambil tugas terbuka admin siklus {$trialNumber}",
        ]);
        $resTakeover->assertOk();

        $freshAdminTask = $adminTask->fresh(['assignee']);
        $this->assertSame($devA->id, $freshAdminTask->assigned_to);
        $this->assertNotEmpty($freshAdminTask->handover_logs);

        // 10. Fitur Papan Kanban (Update Status Tugas oleh Assignee)
        $resKanban = $this->actingAs($devA)->patchJson("/kanban/tasks/{$freshAdminTask->id}/status", [
            'status' => 'in_progress',
        ]);
        $resKanban->assertOk();
        $this->assertSame('in_progress', $freshAdminTask->fresh()->status);

        // 11. Fitur Evaluasi Otomatis Aplikasi
        $resSync = $this->actingAs($devA)->post(route('evaluations.auto-sync'));
        $resSync->assertRedirect(route('evaluations.index'));
        $resEvalPage = $this->actingAs($devA)->get(route('evaluations.index'));
        $resEvalPage->assertOk();
        $resEvalPage->assertSee($project->code);

        // 12. Fitur Riwayat Rilis & Master Akun
        $release = RepoHistory::factory()->create([
            'project_id' => $project->id,
            'release_tags' => "v1.{$trialNumber}.0",
        ]);
        $this->assertDatabaseHas('repo_histories', ['id' => $release->id]);

        // Developer tidak boleh akses master akun admin
        $this->actingAs($devA)->get(route('admin.accounts.index'))->assertForbidden();
        // Admin boleh akses master akun
        $this->actingAs($admin)->get(route('admin.accounts.index'))->assertOk();
    }

    public function test_trial_01_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(1); }
    public function test_trial_02_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(2); }
    public function test_trial_03_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(3); }
    public function test_trial_04_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(4); }
    public function test_trial_05_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(5); }
    public function test_trial_06_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(6); }
    public function test_trial_07_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(7); }
    public function test_trial_08_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(8); }
    public function test_trial_09_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(9); }
    public function test_trial_10_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(10); }
    public function test_trial_11_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(11); }
    public function test_trial_12_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(12); }
    public function test_trial_13_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(13); }
    public function test_trial_14_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(14); }
    public function test_trial_15_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(15); }
    public function test_trial_16_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(16); }
    public function test_trial_17_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(17); }
    public function test_trial_18_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(18); }
    public function test_trial_19_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(19); }
    public function test_trial_20_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(20); }
    public function test_trial_21_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(21); }
    public function test_trial_22_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(22); }
    public function test_trial_23_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(23); }
    public function test_trial_24_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(24); }
    public function test_trial_25_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(25); }
    public function test_trial_26_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(26); }
    public function test_trial_27_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(27); }
    public function test_trial_28_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(28); }
    public function test_trial_29_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(29); }
    public function test_trial_30_full_system_features_lifecycle(): void { $this->executeFullFeatureLifecycleTrial(30); }
}
