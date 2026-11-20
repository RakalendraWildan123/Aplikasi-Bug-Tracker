<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KanbanBoardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_kanban_board(): void
    {
        $this->get('/kanban')->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_kanban_board(): void
    {
        $user = User::factory()->create([
            'name' => 'Kanban Tester',
            'role' => 'developer',
        ]);

        $project = Project::factory()->create([
            'name' => 'Sistem Informasi',
            'code' => 'SISFO',
            'color' => '#4F46E5',
        ]);

        $todoTask = Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Tugas Awal Desain',
            'status' => 'todo',
            'task_type' => 'feature',
        ]);

        $inProgressTask = Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Pengembangan Backend API',
            'status' => 'in_progress',
            'task_type' => 'enhancement',
        ]);

        $response = $this->actingAs($user)->get('/kanban');

        $response->assertOk();
        $response->assertSee('Papan Kanban');
        $response->assertSee('To Do');
        $response->assertSee('In Progress');
        $response->assertSee('Review');
        $response->assertSee('Done');
        $response->assertSee('Tugas Awal Desain');
        $response->assertSee('Pengembangan Backend API');
        $response->assertSee('SISFO');
    }

    public function test_kanban_board_filters_by_project_and_task_type(): void
    {
        $user = User::factory()->create(['role' => 'developer']);

        $projectA = Project::factory()->create(['name' => 'Project Alpha', 'code' => 'ALPHA']);
        $projectB = Project::factory()->create(['name' => 'Project Beta', 'code' => 'BETA']);

        $taskA = Task::factory()->create([
            'project_id' => $projectA->id,
            'title' => 'Tugas Khusus Alpha',
            'task_type' => 'feature',
            'status' => 'todo',
        ]);

        $taskB = Task::factory()->create([
            'project_id' => $projectB->id,
            'title' => 'Tugas Khusus Beta',
            'task_type' => 'refactor',
            'status' => 'todo',
        ]);

        // Filter by Project A
        $response = $this->actingAs($user)->get('/kanban?project_id=' . $projectA->id);
        $response->assertOk();
        $response->assertSee('Tugas Khusus Alpha');
        $response->assertDontSee('Tugas Khusus Beta');

        // Filter by task_type refactor
        $responseType = $this->actingAs($user)->get('/kanban?task_type=refactor');
        $responseType->assertOk();
        $responseType->assertSee('Tugas Khusus Beta');
        $responseType->assertDontSee('Tugas Khusus Alpha');
    }

    public function test_authenticated_user_can_update_task_status_via_ajax(): void
    {
        $user = User::factory()->create(['role' => 'developer']);

        $task = Task::factory()->create([
            'assigned_to' => $user->id,
            'title' => 'Tugas Uji Coba Status',
            'status' => 'todo',
            'completed_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/kanban/tasks/{$task->id}/status", [
                'status' => 'in_progress',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Status tugas berhasil diperbarui.',
        ]);

        $this->assertSame('in_progress', $task->fresh()->status);
        $this->assertNull($task->fresh()->completed_at);

        // Move to done sets completed_at
        $responseDone = $this->actingAs($user)
            ->patchJson("/kanban/tasks/{$task->id}/status", [
                'status' => 'done',
            ]);

        $responseDone->assertOk();
        $this->assertSame('done', $task->fresh()->status);
        $this->assertNotNull($task->fresh()->completed_at);
    }

    public function test_user_cannot_move_task_assigned_to_another_developer(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $anotherDeveloper = User::factory()->create(['name' => 'Budi Santoso', 'role' => 'developer']);

        $task = Task::factory()->create([
            'assigned_to' => $anotherDeveloper->id,
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/kanban/tasks/{$task->id}/status", [
                'status' => 'in_progress',
            ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Hanya orang yang mengerjakan task ini (Budi Santoso) yang dapat memindahkan kartu tugas.',
        ]);

        $this->assertSame('todo', $task->fresh()->status);
    }

    public function test_user_cannot_move_unassigned_task_without_taking_over(): void
    {
        $user = User::factory()->create(['role' => 'developer']);

        $task = Task::factory()->create([
            'assigned_to' => null,
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/kanban/tasks/{$task->id}/status", [
                'status' => 'in_progress',
            ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Tugas ini belum ditugaskan. Ambil alih tugas terlebih dahulu untuk dapat memindahkannya.',
        ]);

        $this->assertSame('todo', $task->fresh()->status);
    }

    public function test_update_task_status_validates_invalid_status(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $task = Task::factory()->create([
            'assigned_to' => $user->id,
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/kanban/tasks/{$task->id}/status", [
                'status' => 'invalid_status_xyz',
            ]);

        $response->assertStatus(422);
        $this->assertSame('todo', $task->fresh()->status);
    }

    public function test_kanban_board_renders_quick_navigation_and_edit_buttons(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $task = Task::factory()->create([
            'title' => 'Tugas dengan Tombol Edit',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->get('/kanban');

        $response->assertOk();
        $response->assertSee('Navigasi Cepat:');
        $response->assertSee('Semua Kolom');
        $response->assertSee('btn-quick-nav');
        $response->assertSee('btn-edit-task');
        $response->assertSee('modal-edit-task');
    }

    public function test_authenticated_user_can_update_task_details_via_put(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $dev = User::factory()->create(['role' => 'developer']);

        $task = Task::factory()->create([
            'title' => 'Judul Lama',
            'description' => 'Deskripsi lama',
            'task_type' => 'feature',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)
            ->putJson("/kanban/tasks/{$task->id}", [
                'title' => 'Judul Baru Diperbarui',
                'description' => 'Deskripsi baru lengkap',
                'task_type' => 'enhancement',
                'status' => 'in_progress',
                'target_date' => '2026-12-01',
                'blocker_notes' => 'Menunggu review API',
                'assigned_to' => $dev->id,
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Data tugas berhasil diperbarui.',
        ]);

        $fresh = $task->fresh();
        $this->assertSame('Judul Baru Diperbarui', $fresh->title);
        $this->assertSame('Deskripsi baru lengkap', $fresh->description);
        $this->assertSame('enhancement', $fresh->task_type);
        $this->assertSame('in_progress', $fresh->status);
        $this->assertSame('2026-12-01', $fresh->target_date->format('Y-m-d'));
        $this->assertSame('Menunggu review API', $fresh->blocker_notes);
        $this->assertSame($dev->id, $fresh->assigned_to);
    }

    public function test_update_task_validates_required_fields(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $task = Task::factory()->create(['title' => 'Judul Tetap']);

        $response = $this->actingAs($user)
            ->putJson("/kanban/tasks/{$task->id}", [
                'title' => '',
                'task_type' => 'invalid_type',
                'status' => 'invalid_status',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'task_type', 'status']);
        $this->assertSame('Judul Tetap', $task->fresh()->title);
    }

    public function test_kanban_board_renders_instant_dropdown_filters_and_card_data_attributes(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $project = Project::factory()->create(['name' => 'Portal Layanan', 'code' => 'PORTAL']);
        $dev = \App\Models\Developer::factory()->create(['name' => 'Fulan Pengembang']);

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $dev->id,
            'title' => 'Tugas Uji Filter Instan',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->get('/kanban');

        $response->assertOk();
        $response->assertSee('id="filter-project"', false);
        $response->assertSee('id="filter-developer"', false);
        $response->assertSee('id="btn-reset-filters"', false);
        $response->assertSee('data-project-id="' . $project->id . '"', false);
        $response->assertSee('data-assigned-to="' . $dev->id . '"', false);
    }

    public function test_kanban_board_filters_by_developer_query_parameter(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $devA = \App\Models\Developer::factory()->create(['name' => 'Developer A']);
        $devB = \App\Models\Developer::factory()->create(['name' => 'Developer B']);

        $taskA = Task::factory()->create([
            'assigned_to' => $devA->id,
            'title' => 'Tugas Milik Dev A',
            'status' => 'todo',
        ]);

        $taskB = Task::factory()->create([
            'assigned_to' => $devB->id,
            'title' => 'Tugas Milik Dev B',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->get('/kanban?developer_id=' . $devA->id);
        $response->assertOk();
        $response->assertSee('Tugas Milik Dev A');
        $response->assertDontSee('Tugas Milik Dev B');
    }

    public function test_kanban_board_renders_takeover_button_and_modal(): void
    {
        $user = User::factory()->create(['name' => 'Saya']);
        $otherDev = \App\Models\Developer::factory()->create(['name' => 'Developer Lain']);

        $taskOther = Task::factory()->create([
            'assigned_to' => $otherDev->id,
            'title' => 'Tugas Ambil Alih Kanban',
            'status' => 'in_progress',
        ]);

        $taskOwn = Task::factory()->create([
            'assigned_to' => $user->id,
            'title' => 'Tugas Milik Saya Sendiri',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->get('/kanban');
        $response->assertOk();
        $response->assertSee('id="modal-takeover-task"', false);
        $response->assertSee('Konfirmasi Ambil Alih Tugas');
        $response->assertSee('btn-takeover-task', false);
        $response->assertSee('Ambil Alih');
        $response->assertSee('Tugas Anda');

        // Test POST /kanban/tasks/{task}/takeover
        $takeoverResponse = $this->actingAs($user)->postJson("/kanban/tasks/{$taskOther->id}/takeover", [
            'note' => 'Takeover via Kanban',
        ]);

        $takeoverResponse->assertOk();
        $takeoverResponse->assertJson(['success' => true]);

        $this->assertSame($user->id, $taskOther->fresh()->assigned_to);
    }

    public function test_kanban_board_renders_status_colored_borders_and_arrow_navigation_buttons(): void
    {
        $user = User::factory()->create(['role' => 'developer']);

        $taskTodo = Task::factory()->create(['title' => 'Tugas Status Todo', 'status' => 'todo', 'assigned_to' => $user->id]);
        $taskInProgress = Task::factory()->create(['title' => 'Tugas Status In Progress', 'status' => 'in_progress', 'assigned_to' => $user->id]);
        $taskReview = Task::factory()->create(['title' => 'Tugas Status Review', 'status' => 'review', 'assigned_to' => $user->id]);
        $taskDone = Task::factory()->create(['title' => 'Tugas Status Done', 'status' => 'done', 'assigned_to' => $user->id]);

        $response = $this->actingAs($user)->get('/kanban');

        $response->assertOk();

        // 1. Verifikasi Garis Warna Status pada Kartu (border-l-4)
        $response->assertSee('border-l-4 border-l-slate-400');
        $response->assertSee('border-l-4 border-l-sky-500');
        $response->assertSee('border-l-4 border-l-amber-500');
        $response->assertSee('border-l-4 border-l-emerald-500');

        // 2. Verifikasi Tombol Panah Navigasi untuk Klik Perpindahan
        $response->assertSee('btn-move-prev');
        $response->assertSee('btn-move-next');

        // 3. Verifikasi target status tombol panah
        $response->assertSee('data-target-status="in_progress"', false);
        $response->assertSee('data-target-status="todo"', false);
        $response->assertSee('data-target-status="review"', false);
        $response->assertSee('data-target-status="done"', false);

        // 4. Verifikasi teks tooltip / label bantuan
        $response->assertSee('Pindahkan ke In Progress');
        $response->assertSee('Pindahkan ke To Do');
        $response->assertSee('Pindahkan ke Review');
        $response->assertSee('Pindahkan ke Done');
    }
}
