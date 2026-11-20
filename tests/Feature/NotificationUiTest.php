<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Project;
use App\Models\RepoHistory;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_notifications(): void
    {
        $response = $this->get('/notifications');
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_fetch_notifications_json(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $project = Project::factory()->create(['name' => 'Project Alpha', 'code' => 'ALPHA']);

        Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $user->id,
            'title' => 'Tugas Uji Notifikasi',
            'status' => 'in_progress',
        ]);

        Bug::factory()->create([
            'project_id' => $project->id,
            'title' => 'Bug Fatal Notifikasi',
            'severity' => 'critical',
        ]);

        RepoHistory::factory()->create([
            'project_id' => $project->id,
            'release_tags' => 'v1.5.0',
        ]);

        $response = $this->actingAs($user)->getJson('/notifications');

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'notifications' => [
                '*' => [
                    'id',
                    'type',
                    'title',
                    'message',
                    'project_code',
                    'time_ago',
                    'timestamp',
                    'is_read',
                    'link',
                ],
            ],
            'unread_count',
            'total_count',
        ]);

        $this->assertTrue($response->json('total_count') >= 3);
        $this->assertTrue($response->json('unread_count') >= 3);
    }

    public function test_kanban_board_renders_notification_trigger_and_modal(): void
    {
        $user = User::factory()->create(['role' => 'developer']);

        $response = $this->actingAs($user)->get('/kanban');

        $response->assertOk();

        // 1. Tombol Trigger Notifikasi (Desktop & Tablet)
        $response->assertSee('btn-trigger-notifications');
        $response->assertSee('notification-badge-count');
        $response->assertSee('Buka Notifikasi Pop-up');

        // 2. Modal Pop-up Notifikasi
        $response->assertSee('id="modal-notifications"', false);
        $response->assertSee('notification-modal-title');
        $response->assertSee('btn-notif-mark-all');
        $response->assertSee('btn-close-notif-x');
        $response->assertSee('btn-close-notif-footer');
        $response->assertSee('btn-notif-clear-all');

        // 3. Filter Sederhana Tanpa Tab Bersarang
        $response->assertSee('btn-notif-filter');
        $response->assertSee('data-filter="all"', false);
        $response->assertSee('data-filter="unread"', false);
        $response->assertDontSee('tab-content-nested');
        $response->assertDontSee('sub-tabs');

        // 4. Pastikan modal notifikasi tidak memiliki tombol logout
        $response->assertDontSee('id="logout-in-modal"');
    }

    public function test_tasks_page_renders_notification_trigger_and_modal(): void
    {
        $user = User::factory()->create(['role' => 'developer']);

        $response = $this->actingAs($user)->get('/tasks');

        $response->assertOk();

        // 1. Tombol Trigger Notifikasi (Desktop & Tablet)
        $response->assertSee('btn-trigger-notifications');
        $response->assertSee('notification-badge-count');
        $response->assertSee('Buka Notifikasi Pop-up');

        // 2. Modal Pop-up Notifikasi
        $response->assertSee('id="modal-notifications"', false);
        $response->assertSee('notification-modal-title');
    }

    public function test_user_can_mark_single_notification_as_read(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patchJson('/notifications/task-99/read');

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'id' => 'task-99',
        ]);

        // Cek bahwa session menyimpan read id
        $this->assertContains('task-99', session('read_notifications', []));
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/notifications/mark-all-read', [
                'ids' => ['task-1', 'bug-2', 'release-3'],
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Semua notifikasi berhasil ditandai sudah dibaca.',
        ]);

        $readIds = session('read_notifications', []);
        $this->assertContains('task-1', $readIds);
        $this->assertContains('bug-2', $readIds);
        $this->assertContains('release-3', $readIds);
    }

    public function test_user_can_delete_single_notification(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->deleteJson('/notifications/task-88');

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'id' => 'task-88',
        ]);

        $deletedIds = session('deleted_notifications', []);
        $this->assertContains('task-88', $deletedIds);
    }

    public function test_user_can_clear_all_notifications(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->deleteJson('/notifications', [
                'ids' => ['task-1', 'bug-2'],
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Semua notifikasi berhasil dibersihkan.',
        ]);

        $deletedIds = session('deleted_notifications', []);
        $this->assertContains('task-1', $deletedIds);
        $this->assertContains('bug-2', $deletedIds);
    }
}
