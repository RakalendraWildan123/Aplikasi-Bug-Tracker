<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Project;
use App\Models\RepoHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReleaseHistoryUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_releases(): void
    {
        $response = $this->get('/releases');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_releases_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/releases');

        $response->assertOk();
        $response->assertSee('Riwayat Rilis');
        $response->assertSee('Repo Modul');
        $response->assertSee('btn-open-create-release', false);
    }

    public function test_releases_page_renders_sidebar_navigation_with_categories(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/releases');

        $response->assertOk();
        $response->assertSee('Fitur Operasional');
        $response->assertSee('Papan Kanban');
        $response->assertSee('Daftar Tugas');
        $response->assertSee('Pelacak Bug');
        $response->assertSee('Riwayat Rilis');
        $response->assertSee('Fitur Master Data');
        $response->assertSee('Master Proyek');
        $response->assertSee('Master Modul');
        $response->assertSee('Master Akun');
        $response->assertSee('Pengaturan');
        $response->assertSee('Akun Saya');
    }

    public function test_releases_page_renders_summary_metric_cards(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        RepoHistory::create([
            'project_id' => $project->id,
            'release_date' => '2026-11-18',
            'release_tags' => 'v1.0.0',
            'branch_name' => 'main',
            'commit_hash' => '1111111111222222222233333333334444444444',
            'summary_changes' => 'Rilis awal fitur.',
        ]);

        $response = $this->actingAs($user)->get('/releases');

        $response->assertOk();
        $response->assertSee('Total Rilis');
        $response->assertSee('Rilis Ber-Tag');
        $response->assertSee('Proyek Dirilis');
        $response->assertSee('Branch Terpantau');
    }

    public function test_releases_page_renders_filter_and_search_bar(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'name' => 'Portal Layanan Publik',
            'code' => 'PLP',
        ]);

        $response = $this->actingAs($user)->get('/releases');

        $response->assertOk();
        $response->assertSee('placeholder="Cari tag versi, branch, commit, proyek, atau modul..."', false);
        $response->assertSee('Semua Proyek');
        $response->assertSee('[PLP] Portal Layanan Publik');
        $response->assertSee('Semua Branch');
    }

    public function test_releases_page_renders_table_with_releases_and_project_modules(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'name' => 'Sistem Presensi GPS',
            'code' => 'SGPS',
            'repo_url' => 'https://github.com/company/sgps-repo',
            'color' => '#10B981',
        ]);

        Module::factory()->create([
            'project_id' => $project->id,
            'name' => 'Modul Geofencing',
        ]);

        $release = RepoHistory::create([
            'project_id' => $project->id,
            'release_date' => '2026-11-19',
            'release_tags' => 'v2.1.0',
            'branch_name' => 'release/2.1',
            'commit_hash' => 'abcdef1234567890abcdef1234567890abcdef12',
            'summary_changes' => 'Optimasi akurasi koordinat lokasi mobile.',
        ]);

        $response = $this->actingAs($user)->get('/releases');

        $response->assertOk();
        $response->assertSee('v2.1.0');
        $response->assertSee('[SGPS]');
        $response->assertSee('Sistem Presensi GPS');
        $response->assertSee('release/2.1');
        $response->assertSee('abcdef1');
        $response->assertSee('Modul Geofencing');
        $response->assertSee('Optimasi akurasi koordinat lokasi mobile.');
        $response->assertDontSee('btn-edit-release', false);
        $response->assertDontSee('btn-delete-release', false);
    }

    public function test_releases_filter_by_search_project_and_branch(): void
    {
        $user = User::factory()->create();
        $projectA = Project::factory()->create(['name' => 'Sistem Alpha', 'code' => 'SALPHA']);
        $projectB = Project::factory()->create(['name' => 'Sistem Beta', 'code' => 'SBETA']);

        RepoHistory::create([
            'project_id' => $projectA->id,
            'release_date' => '2026-11-10',
            'release_tags' => 'v1.0.0-alpha',
            'branch_name' => 'main',
            'commit_hash' => 'aaaaaaaaaa1111111111aaaaaaaaaa1111111111',
            'summary_changes' => 'Changelog Alpha unik.',
        ]);

        RepoHistory::create([
            'project_id' => $projectB->id,
            'release_date' => '2026-11-15',
            'release_tags' => 'v1.0.0-beta',
            'branch_name' => 'develop',
            'commit_hash' => 'bbbbbbbbbb2222222222bbbbbbbbbb2222222222',
            'summary_changes' => 'Changelog Beta unik.',
        ]);

        // Search test
        $searchRes = $this->actingAs($user)->get('/releases?search=Changelog+Alpha');
        $searchRes->assertOk();
        $searchRes->assertSee('Changelog Alpha unik.');
        $searchRes->assertDontSee('Changelog Beta unik.');

        // Project filter test
        $projRes = $this->actingAs($user)->get("/releases?project_id={$projectB->id}");
        $projRes->assertOk();
        $projRes->assertSee('v1.0.0-beta');
        $projRes->assertDontSee('v1.0.0-alpha');

        // Branch filter test
        $branchRes = $this->actingAs($user)->get('/releases?branch=develop');
        $branchRes->assertOk();
        $branchRes->assertSee('v1.0.0-beta');
        $branchRes->assertDontSee('v1.0.0-alpha');
    }

    public function test_user_can_create_new_release_via_post(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->post('/releases', [
            'project_id' => $project->id,
            'release_date' => '2026-11-19',
            'release_tags' => 'v1.5.0',
            'branch_name' => 'main',
            'commit_hash' => '1234567890abcdef1234567890abcdef12345678',
            'summary_changes' => 'Penambahan fitur ekspor laporan otomatis.',
        ]);

        $response->assertRedirect('/releases');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('repo_histories', [
            'project_id' => $project->id,
            'release_tags' => 'v1.5.0',
            'branch_name' => 'main',
            'commit_hash' => '1234567890abcdef1234567890abcdef12345678',
        ]);
    }

    public function test_create_release_via_json_returns_201(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->postJson('/releases', [
            'project_id' => $project->id,
            'release_date' => '2026-11-19',
            'release_tags' => 'v1.0.1',
            'branch_name' => 'hotfix/sso',
            'commit_hash' => '4567890123abcdef4567890123abcdef45678901',
            'summary_changes' => 'Hotfix sesi OAuth.',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('release.release_tags', 'v1.0.1');
    }

    public function test_create_release_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/releases', [
            'project_id' => '',
            'release_date' => '',
            'branch_name' => '',
            'commit_hash' => '',
        ]);

        $response->assertSessionHasErrors(['project_id', 'release_date', 'branch_name', 'commit_hash']);
    }

    public function test_create_release_validates_unique_commit_hash_per_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        RepoHistory::create([
            'project_id' => $project->id,
            'release_date' => '2026-11-18',
            'branch_name' => 'main',
            'commit_hash' => 'commitunik1234567890commitunik1234567890c',
        ]);

        $response = $this->actingAs($user)->post('/releases', [
            'project_id' => $project->id,
            'release_date' => '2026-11-19',
            'branch_name' => 'main',
            'commit_hash' => 'commitunik1234567890commitunik1234567890c',
        ]);

        $response->assertSessionHasErrors(['commit_hash']);
    }

    public function test_release_history_cannot_be_updated_because_it_is_immutable(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $release = RepoHistory::create([
            'project_id' => $project->id,
            'release_date' => '2026-11-10',
            'release_tags' => 'v1.0.0',
            'branch_name' => 'master',
            'commit_hash' => 'oldcommit1234567890oldcommit1234567890',
            'summary_changes' => 'Catatan lama.',
        ]);

        $response = $this->actingAs($user)->put("/releases/{$release->id}", [
            'project_id' => $project->id,
            'release_date' => '2026-11-11',
            'release_tags' => 'v1.0.1',
            'branch_name' => 'main',
            'commit_hash' => 'newcommit1234567890newcommit1234567890',
            'summary_changes' => 'Catatan baru telah diperbarui.',
        ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('repo_histories', [
            'id' => $release->id,
            'release_tags' => 'v1.0.0',
            'commit_hash' => 'oldcommit1234567890oldcommit1234567890',
            'summary_changes' => 'Catatan lama.',
        ]);
    }

    public function test_release_history_cannot_be_deleted_because_it_is_immutable(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $release = RepoHistory::create([
            'project_id' => $project->id,
            'release_date' => '2026-11-10',
            'branch_name' => 'temp-branch',
            'commit_hash' => 'delcommit1234567890delcommit1234567890del',
        ]);

        $response = $this->actingAs($user)->delete("/releases/{$release->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('repo_histories', ['id' => $release->id]);
    }

    public function test_releases_page_only_renders_create_modal_and_no_edit_or_delete_modals(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/releases');

        $response->assertOk();
        $response->assertSee('id="modal-create-release"', false);
        $response->assertDontSee('id="modal-edit-release"', false);
        $response->assertDontSee('id="modal-delete-release"', false);
    }

    public function test_releases_sidebar_does_not_contain_logout_button(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/releases');

        $response->assertOk();

        $content = $response->getContent();
        $sidebarPos = strpos($content, 'id="app-sidebar"');
        $sidebarEnd = strpos($content, '</aside>', $sidebarPos);
        $sidebarHtml = substr($content, $sidebarPos, $sidebarEnd - $sidebarPos);

        $this->assertStringNotContainsString(route('logout'), $sidebarHtml);
        $this->assertStringNotContainsString('>Logout<', $sidebarHtml);
    }

    public function test_user_can_view_single_release_via_json(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Proyek Tunggal Rilis']);

        $release = RepoHistory::create([
            'project_id' => $project->id,
            'release_date' => '2026-11-18',
            'release_tags' => 'v3.0.0',
            'branch_name' => 'main',
            'commit_hash' => 'singlecommit12345678singlecommit12345678s',
        ]);

        $response = $this->actingAs($user)->getJson("/releases/{$release->id}");

        $response->assertOk();
        $response->assertJsonPath('release.release_tags', 'v3.0.0');
        $response->assertJsonPath('release.project.name', 'Proyek Tunggal Rilis');
    }

    public function test_releases_page_lists_only_completed_tasks_and_can_link_release_to_done_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['code' => 'REL']);

        $doneTask = \App\Models\Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Task Selesai Siap Rilis',
            'status' => 'done',
        ]);

        $todoTask = \App\Models\Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Task Belum Selesai',
            'status' => 'todo',
        ]);

        $page = $this->actingAs($user)->get('/releases');
        $page->assertOk();
        $page->assertSee('id="create-task-id"', false);
        $page->assertSee('Task Selesai Siap Rilis');
        $page->assertDontSee('Task Belum Selesai');

        $storeRes = $this->actingAs($user)->postJson('/releases', [
            'project_id' => $project->id,
            'task_id' => $doneTask->id,
            'release_date' => '2026-11-26',
            'release_tags' => 'v1.5.0',
            'branch_name' => 'main',
            'commit_hash' => 'abc1234def5678',
            'summary_changes' => 'Rilis fitur siap pakai',
        ]);

        $storeRes->assertCreated();
        $storeRes->assertJsonPath('release.task_id', $doneTask->id);
        $storeRes->assertJsonPath('release.task.title', 'Task Selesai Siap Rilis');

        $this->actingAs($user)->get('/releases')
            ->assertOk()
            ->assertSee('Task: Task Selesai Siap Rilis');
    }

    public function test_release_cannot_reference_uncompleted_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $inProgressTask = \App\Models\Task::factory()->create([
            'project_id' => $project->id,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($user)->postJson('/releases', [
            'project_id' => $project->id,
            'task_id' => $inProgressTask->id,
            'release_date' => '2026-11-26',
            'branch_name' => 'main',
            'commit_hash' => 'def5678abc1234',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['task_id']);
    }
}
