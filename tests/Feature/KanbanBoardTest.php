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

    public function test_update_task_status_validates_invalid_status(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $task = Task::factory()->create(['status' => 'todo']);

        $response = $this->actingAs($user)
            ->patchJson("/kanban/tasks/{$task->id}/status", [
                'status' => 'invalid_status_xyz',
            ]);

        $response->assertStatus(422);
        $this->assertSame('todo', $task->fresh()->status);
    }
}
