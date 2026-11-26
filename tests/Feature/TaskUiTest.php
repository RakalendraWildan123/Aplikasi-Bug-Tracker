<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Developer;
use App\Models\Module;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_tasks_page(): void
    {
        $response = $this->get('/tasks');
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_tasks_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/tasks');

        $response->assertOk();
        $response->assertSee('Daftar Tugas');
        $response->assertSee('Tambah Tugas');
        $response->assertSee('Total Tugas');
        $response->assertSee('To Do');
        $response->assertSee('In Progress');
        $response->assertSee('Review');
        $response->assertSee('Done');
    }

    public function test_tasks_page_renders_sidebar_navigation_with_active_state(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/tasks');

        $response->assertOk();
        $response->assertSee(route('tasks.index'));
        $response->assertSee(route('kanban.index'));
        $response->assertSee(route('account.edit'));
        $response->assertDontSee('role="tablist"', false);
    }

    public function test_tasks_page_renders_empty_state_when_no_tasks(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/tasks');

        $response->assertOk();
        $response->assertSee('Belum ada tugas ditemukan');
    }

    public function test_tasks_page_renders_task_table_and_items(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Aplikasi Penggajian', 'code' => 'PAYROLL', 'color' => '#10B981']);
        $dev = Developer::factory()->create(['name' => 'Budi Santoso']);
        $module = Module::factory()->create(['project_id' => $project->id, 'name' => 'Modul Slip Gaji']);

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'module_id' => $module->id,
            'assigned_to' => $dev->id,
            'title' => 'Buat Template Export Slip Gaji',
            'task_type' => 'feature',
            'status' => 'in_progress',
            'target_date' => '2026-12-15',
            'blocker_notes' => 'Menunggu format template HRD',
        ]);

        $response = $this->actingAs($user)->get('/tasks');

        $response->assertOk();
        $response->assertSee('Buat Template Export Slip Gaji');
        $response->assertSee('Modul Slip Gaji');
        $response->assertSee('PAYROLL');
        $response->assertSee('Fitur');
        $response->assertSee('In Progress');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Menunggu format template HRD');
        $response->assertSee('btn-edit-task', false);
        $response->assertSee('btn-delete-task', false);
    }

    public function test_tasks_page_filters_by_search_and_project(): void
    {
        $user = User::factory()->create();
        $projA = Project::factory()->create(['name' => 'Project Alpha', 'code' => 'ALPHA']);
        $projB = Project::factory()->create(['name' => 'Project Beta', 'code' => 'BETA']);

        $taskA = Task::factory()->create([
            'project_id' => $projA->id,
            'title' => 'Tugas Spesifik Alpha',
            'status' => 'todo',
        ]);

        $taskB = Task::factory()->create([
            'project_id' => $projB->id,
            'title' => 'Tugas Spesifik Beta',
            'status' => 'todo',
        ]);

        // Search by keyword
        $searchResponse = $this->actingAs($user)->get('/tasks?search=Alpha');
        $searchResponse->assertOk();
        $searchResponse->assertSee('Tugas Spesifik Alpha');
        $searchResponse->assertDontSee('Tugas Spesifik Beta');

        // Filter by project ID
        $projectResponse = $this->actingAs($user)->get('/tasks?project_id=' . $projB->id);
        $projectResponse->assertOk();
        $projectResponse->assertSee('Tugas Spesifik Beta');
        $projectResponse->assertDontSee('Tugas Spesifik Alpha');
    }

    public function test_tasks_page_filters_by_status_and_type(): void
    {
        $user = User::factory()->create();

        $todoTask = Task::factory()->create([
            'title' => 'Tugas Masih Todo',
            'status' => 'todo',
            'task_type' => 'feature',
        ]);

        $doneTask = Task::factory()->create([
            'title' => 'Tugas Sudah Selesai',
            'status' => 'done',
            'task_type' => 'refactor',
        ]);

        $response = $this->actingAs($user)->get('/tasks?status=todo');
        $response->assertOk();
        $response->assertSee('Tugas Masih Todo');
        $response->assertDontSee('Tugas Sudah Selesai');
    }

    public function test_user_can_create_new_task_via_post(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $dev = Developer::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'project_id' => $project->id,
            'title' => 'Tugas Baru Dibuat Melalui Web',
            'description' => 'Rincian deskripsi tugas baru',
            'task_type' => 'feature',
            'status' => 'todo',
            'target_date' => '2026-12-20',
            'assigned_to' => $dev->id,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success_message', 'Tugas baru berhasil ditambahkan.');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Tugas Baru Dibuat Melalui Web',
            'project_id' => $project->id,
            'assigned_to' => $dev->id,
            'created_by' => $user->id,
        ]);
    }

    public function test_create_task_via_json_returns_201(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->postJson('/tasks', [
            'project_id' => $project->id,
            'title' => 'Tugas Baru via AJAX Modal',
            'task_type' => 'enhancement',
            'status' => 'todo',
        ]);

        $response->assertStatus(201);
        $this->assertSame($user->id, Task::where('title', 'Tugas Baru via AJAX Modal')->first()->assigned_to);
        $response->assertJson([
            'success' => true,
            'message' => 'Tugas baru berhasil ditambahkan.',
        ]);
    }

    public function test_create_task_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/tasks', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'project_id', 'task_type', 'status']);
    }

    public function test_create_task_with_bug_type_normalizes_to_maintenance(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->postJson('/tasks', [
            'project_id' => $project->id,
            'title' => 'Tugas Perbaikan Masalah Bug',
            'task_type' => 'bug',
            'status' => 'todo',
        ]);

        $response->assertStatus(201);
        $this->assertSame($user->id, Task::where('title', 'Tugas Perbaikan Masalah Bug')->first()->assigned_to);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Tugas Perbaikan Masalah Bug',
            'task_type' => 'maintenance',
        ]);
    }

    public function test_user_can_update_task_via_put(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'title' => 'Judul Sebelum Diedit',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->putJson("/tasks/{$task->id}", [
            'project_id' => $task->project_id,
            'title' => 'Judul Berhasil Diedit',
            'task_type' => 'maintenance',
            'status' => 'done',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Data tugas berhasil diperbarui.',
        ]);

        $fresh = $task->fresh();
        $this->assertSame('Judul Berhasil Diedit', $fresh->title);
        $this->assertSame('maintenance', $fresh->task_type);
        $this->assertSame('done', $fresh->status);
        $this->assertNotNull($fresh->completed_at);
    }

    public function test_user_can_delete_task_via_delete(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'title' => 'Tugas Akan Dihapus',
            'assigned_to' => $user->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/tasks/{$task->id}");

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Tugas berhasil dihapus.',
        ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_delete_task_assigned_to_another_developer(): void
    {
        $user = User::factory()->create();
        $otherDev = User::factory()->create(['name' => 'Budi Santoso']);
        $task = Task::factory()->create([
            'title' => 'Tugas Milik Developer Lain',
            'assigned_to' => $otherDev->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/tasks/{$task->id}");

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function test_tasks_page_renders_create_and_delete_modals(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/tasks');

        $response->assertOk();
        $response->assertSee('id="modal-task-form"', false);
        $response->assertSee('id="modal-delete-task"', false);
        $response->assertSee('Tambah Tugas Baru');
    }

    public function test_user_can_takeover_task_from_another_developer(): void
    {
        $devA = User::factory()->create(['name' => 'Developer Alpha']);
        $userB = User::factory()->create(['name' => 'Developer Beta']);

        $task = Task::factory()->create([
            'title' => 'Tugas Fitur API Alpha',
            'assigned_to' => $devA->id,
        ]);

        $response = $this->actingAs($userB)->postJson("/tasks/{$task->id}/takeover", [
            'note' => 'Diambil alih untuk percepatan release sprint',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'pending' => true,
        ]);

        // Sebelum disetujui devA, tugas belum berpindah & berstatus pending
        $pendingTask = $task->fresh(['assignee']);
        $this->assertSame($devA->id, $pendingTask->assigned_to);
        $this->assertSame($devA->id, $pendingTask->pending_handover['approver_id']);

        // Orang lain selain devA tidak bisa menyetujui
        $this->actingAs($userB)->postJson("/tasks/{$task->id}/handover/approve")->assertStatus(403);

        // Developer Alpha menyetujui permintaan ambil alih
        $approveResponse = $this->actingAs($devA)->postJson("/tasks/{$task->id}/handover/approve");
        $approveResponse->assertOk();

        $freshTask = $task->fresh(['assignee']);
        $this->assertSame($userB->id, $freshTask->assigned_to);
        $this->assertNull($freshTask->pending_handover);
        $this->assertNotEmpty($freshTask->handover_logs);

        $logs = $freshTask->handover_logs;
        $lastLog = end($logs);
        $this->assertSame($devA->id, $lastLog['from_developer_id']);
        $this->assertSame('Developer Alpha', $lastLog['from_developer_name']);
        $this->assertSame($userB->id, $lastLog['to_developer_id']);
        $this->assertSame('Developer Beta', $lastLog['to_developer_name']);
        $this->assertSame('Diambil alih untuk percepatan release sprint', $lastLog['note']);
        $this->assertNotNull($lastLog['handed_over_at']);
    }

    public function test_tasks_page_renders_takeover_button_and_modal(): void
    {
        $currentUser = User::factory()->create(['name' => 'Saya']);
        $otherDev = User::factory()->create(['name' => 'Dev Lain']);

        $taskOther = Task::factory()->create([
            'title' => 'Tugas Dev Lain',
            'assigned_to' => $otherDev->id,
        ]);

        $taskOwn = Task::factory()->create([
            'title' => 'Tugas Milik Saya',
            'assigned_to' => $currentUser->id,
        ]);

        $response = $this->actingAs($currentUser)->get('/tasks');

        $response->assertOk();
        $response->assertSee('id="modal-takeover-task"', false);
        $response->assertSee('Konfirmasi Ambil Alih Tugas');
        $response->assertSee('btn-takeover-task', false);
        $response->assertSee('Ambil Alih');
        $response->assertSee('Tugas Anda');
    }

    public function test_tasks_page_renders_my_tasks_tab_and_filters_user_tasks(): void
    {
        $currentUser = User::factory()->create(['name' => 'Saya']);
        $otherDev = User::factory()->create(['name' => 'Dev Lain']);
        $project = Project::factory()->create(['code' => 'DEMO']);

        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Tugas Milik Orang Lain',
            'assigned_to' => $otherDev->id,
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Tugas Prioritas Saya',
            'assigned_to' => $currentUser->id,
        ]);

        // Default: semua task ditampilkan
        $resAll = $this->actingAs($currentUser)->get('/tasks');
        $resAll->assertOk();
        $resAll->assertSee('Semua Task');
        $resAll->assertSee('Task Saya');
        $resAll->assertSee('Tugas Milik Orang Lain');
        $resAll->assertSee('Tugas Prioritas Saya');

        // Tab: Task Saya
        $resMy = $this->actingAs($currentUser)->get('/tasks?tab=my_tasks');
        $resMy->assertOk();
        $resMy->assertSee('Tugas Prioritas Saya');
        $resMy->assertDontSee('Tugas Milik Orang Lain');
    }

    public function test_bug_creation_in_bug_tracker_does_not_add_new_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['code' => 'APP']);
        $module = Module::factory()->create(['project_id' => $project->id, 'name' => 'Autentikasi']);

        $initialTaskCount = Task::count();

        // Laporkan bug melalui BugController
        $resBug = $this->actingAs($user)->post('/bugs', [
            'project_id' => $project->id,
            'module_id' => $module->id,
            'assigned_to' => $user->id,
            'title' => 'Crash saat upload avatar PNG 4K',
            'description' => 'Memory limit exceeded pada image processing',
            'severity' => 'high',
            'status' => 'open',
        ]);

        $resBug->assertRedirect(route('bugs.index'));
        $this->assertDatabaseHas('bugs', [
            'title' => 'Crash saat upload avatar PNG 4K',
            'status' => 'open',
        ]);

        // Memastikan pencatatan bug tidak otomatis menambahkan task baru
        $this->assertSame($initialTaskCount, Task::count());

        // Buka halaman task: tidak ada penambahan task baru dari pencatatan bug
        $resTasks = $this->actingAs($user)->get('/tasks');
        $resTasks->assertOk();
        $resTasks->assertDontSee('Crash saat upload avatar PNG 4K');
    }

    public function test_developer_b_creating_task_is_assigned_to_developer_b_even_when_based_on_developer_a_bug(): void
    {
        $devA = User::factory()->create(['name' => 'Developer A']);
        $devB = User::factory()->create(['name' => 'Developer B']);
        $project = Project::factory()->create();

        // 1. Developer A mencatat bug -> otomatis ditugaskan ke Developer A
        $resBug = $this->actingAs($devA)->post('/bugs', [
            'project_id' => $project->id,
            'title' => 'Bug Validasi Login Multi-Device',
            'severity' => 'high',
            'status' => 'open',
            'description' => 'Sesi tidak otomatis logout pada perangkat kedua',
        ]);
        $resBug->assertRedirect(route('bugs.index'));

        $bug = Bug::where('title', 'Bug Validasi Login Multi-Device')->firstOrFail();
        $this->assertSame($devA->id, $bug->created_by);
        $this->assertSame($devA->id, $bug->assigned_to);

        // 2. Developer B membuat tugas berdasarkan bug tersebut -> tugas otomatis milik Developer B
        $resTask = $this->actingAs($devB)->post('/tasks', [
            'project_id' => $project->id,
            'title' => 'Perbaiki Bug Validasi Login Multi-Device',
            'description' => 'Implementasi invalidate token sesi multi-tab & multi-device',
            'task_type' => 'maintenance',
            'status' => 'todo',
        ]);
        $resTask->assertRedirect(route('tasks.index'));

        $task = Task::where('title', 'Perbaiki Bug Validasi Login Multi-Device')->firstOrFail();
        $this->assertSame($devB->id, $task->created_by);
        $this->assertSame($devB->id, $task->assigned_to);

        // Kepemilikan bug tetap milik Developer A, sedangkan task milik Developer B
        $this->assertNotSame($bug->assigned_to, $task->assigned_to);
    }

    public function test_admin_creating_task_is_open_and_unassigned_and_any_developer_can_take_it(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Super Admin']);
        $dev = User::factory()->create(['name' => 'Developer C']);
        $project = Project::factory()->create();

        // 1. Admin membuat tugas -> tugas open dan belum ditugaskan ke siapapun (assigned_to null)
        $resTask = $this->actingAs($admin)->post('/tasks', [
            'project_id' => $project->id,
            'title' => 'Tugas Prioritas dari Admin',
            'description' => 'Tugas ini terbuka untuk diambil oleh developer mana saja',
            'task_type' => 'feature',
            'status' => 'todo',
        ]);
        $resTask->assertRedirect(route('tasks.index'));

        $task = Task::where('title', 'Tugas Prioritas dari Admin')->firstOrFail();
        $this->assertSame($admin->id, $task->created_by);
        $this->assertNull($task->assigned_to);
        $this->assertSame('todo', $task->status);

        // 2. Siapa saja developer bisa mengambil tugas tersebut (takeover)
        $resTakeover = $this->actingAs($dev)->postJson("/tasks/{$task->id}/takeover", [
            'note' => 'Saya ambil tugas ini untuk dikerjakan',
        ]);
        $resTakeover->assertOk();

        $freshTask = $task->fresh(['assignee']);
        $this->assertSame($dev->id, $freshTask->assigned_to);
        $this->assertSame('Developer C', $freshTask->assignee->name);
        $this->assertNotEmpty($freshTask->handover_logs);
    }

    public function test_user_can_transfer_own_task_to_another_teammate(): void
    {
        $devA = User::factory()->create(['name' => 'Developer A']);
        $devB = User::factory()->create(['name' => 'Developer B']);
        $project = Project::factory()->create();

        // 1. Developer A membuat tugas -> tugas milik Developer A
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $devA->id,
            'assigned_to' => $devA->id,
            'title' => 'Tugas Fitur Checkout Pembayaran',
            'status' => 'in_progress',
        ]);

        $this->assertSame($devA->id, $task->assigned_to);

        // 2. Developer A mengalihkan tugasnya kepada Developer B -> masuk status menunggu persetujuan Developer B
        $response = $this->actingAs($devA)->postJson("/tasks/{$task->id}/transfer", [
            'target_developer_id' => $devB->id,
            'note' => 'Mendelegasikan tugas ke rekan setim karena fokus pada modul inventory',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'pending' => true,
        ]);

        // Tugas masih dipegang devA sampai devB menyetujui
        $this->assertSame($devA->id, $task->fresh()->assigned_to);
        $this->assertSame($devB->id, $task->fresh()->pending_handover['approver_id']);

        // Developer B melihat notifikasi persetujuan dan menyetujui
        $this->actingAs($devB)->get('/tasks')->assertSee('Menunggu Persetujuan Anda');
        $this->actingAs($devB)->postJson("/tasks/{$task->id}/handover/approve")->assertOk();

        $freshTask = $task->fresh(['assignee']);
        $this->assertSame($devB->id, $freshTask->assigned_to);
        $this->assertSame('Developer B', $freshTask->assignee->name);
        $this->assertNull($freshTask->pending_handover);

        // Verifikasi audit trail handover_logs
        $logs = $freshTask->handover_logs;
        $this->assertNotEmpty($logs);
        $lastLog = end($logs);
        $this->assertSame($devA->id, $lastLog['from_developer_id']);
        $this->assertSame('Developer A', $lastLog['from_developer_name']);
        $this->assertSame($devB->id, $lastLog['to_developer_id']);
        $this->assertSame('Developer B', $lastLog['to_developer_name']);
        $this->assertSame('transfer', $lastLog['action']);
    }

    public function test_approver_can_reject_handover_request_and_keep_original_assignee(): void
    {
        $devA = User::factory()->create(['name' => 'Developer A']);
        $devB = User::factory()->create(['name' => 'Developer B']);
        $project = Project::factory()->create();

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $devA->id,
            'title' => 'Tugas yang Ditolak Pengalihannya',
        ]);

        $this->actingAs($devA)->postJson("/tasks/{$task->id}/transfer", [
            'target_developer_id' => $devB->id,
            'note' => 'Tolong bantu kerjakan ya',
        ])->assertOk();

        $this->actingAs($devB)->postJson("/tasks/{$task->id}/handover/reject", [
            'reason' => 'Sedang penuh sprint ini',
        ])->assertOk();

        $freshTask = $task->fresh();
        $this->assertSame($devA->id, $freshTask->assigned_to);
        $this->assertNull($freshTask->pending_handover);
    }

    public function test_transfer_task_validates_required_target_developer(): void
    {
        $devA = User::factory()->create(['name' => 'Developer A']);
        $project = Project::factory()->create();

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $devA->id,
            'assigned_to' => $devA->id,
        ]);

        $response = $this->actingAs($devA)->postJson("/tasks/{$task->id}/transfer", [
            'target_developer_id' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['target_developer_id']);
    }

    public function test_tasks_page_renders_source_bug_dropdown_and_links_converted_task_on_store(): void
    {
        $dev = User::factory()->create(['name' => 'Developer A']);
        $project = Project::factory()->create(['code' => 'BUGP']);

        $bug = Bug::factory()->create([
            'project_id' => $project->id,
            'title' => 'Bug Dropdown Konversi',
            'severity' => 'high',
        ]);

        // Dropdown tampil pada halaman /tasks
        $this->actingAs($dev)->get('/tasks')
            ->assertOk()
            ->assertSee('id="input-source-bug"', false)
            ->assertSee('Bug Dropdown Konversi');

        // Membuat task dengan memilih source_bug_id
        $response = $this->actingAs($dev)->postJson('/tasks', [
            'title' => '[Bug Fix] Bug Dropdown Konversi',
            'project_id' => $project->id,
            'task_type' => 'maintenance',
            'status' => 'todo',
            'source_bug_id' => $bug->id,
        ]);

        $response->assertCreated();
        $createdTaskId = $response->json('task.id');

        $this->assertSame($createdTaskId, $bug->fresh()->converted_task_id);
    }
}
