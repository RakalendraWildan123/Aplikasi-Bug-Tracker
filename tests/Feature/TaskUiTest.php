<?php

namespace Tests\Feature;

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
        $task = Task::factory()->create(['title' => 'Tugas Akan Dihapus']);

        $response = $this->actingAs($user)->deleteJson("/tasks/{$task->id}");

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Tugas berhasil dihapus.',
        ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
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
}
