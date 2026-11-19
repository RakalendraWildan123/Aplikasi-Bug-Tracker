<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Module;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_projects(): void
    {
        $response = $this->get('/projects');
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_projects_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/projects');

        $response->assertOk();
        $response->assertSee('Master Proyek');
        $response->assertSee('Kelola data proyek sistem, kode unik, repositori kode sumber, status siklus, dan label warna identitas.');
    }

    public function test_projects_page_renders_sidebar_navigation_with_categories(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/projects');

        $response->assertOk();
        $response->assertSee('Fitur Operasional');
        $response->assertSee('Fitur Master Data');
        $response->assertSee('Pengaturan');
        $response->assertSee(route('kanban.index'));
        $response->assertSee(route('tasks.index'));
        $response->assertSee(route('bugs.index'));
        $response->assertSee(route('projects.index'));
        $response->assertSee(route('account.edit'));
    }

    public function test_projects_page_renders_summary_metric_cards(): void
    {
        $user = User::factory()->create();
        Project::factory()->create(['status' => 'active']);
        Project::factory()->create(['status' => 'maintenance']);
        Project::factory()->create(['status' => 'archived']);

        $response = $this->actingAs($user)->get('/projects');

        $response->assertOk();
        $response->assertSee('Total Proyek');
        $response->assertSee('Proyek Aktif');
        $response->assertSee('Masa Pemeliharaan');
        $response->assertSee('Diarsipkan');
        $response->assertSee('3'); // Total
    }

    public function test_projects_page_renders_filter_and_search_bar(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/projects');

        $response->assertOk();
        $response->assertSee('id="search-input"', false);
        $response->assertSee('id="status-filter"', false);
        $response->assertSee('Semua Status');
        $response->assertSee('Aktif (Active)');
    }

    public function test_projects_page_renders_table_with_projects_and_counts(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'name' => 'Sistem Payroll Terpadu',
            'code' => 'PAYROLL',
            'status' => 'active',
            'repo_url' => 'https://github.com/company/payroll',
            'color' => '#10B981',
        ]);

        $moduleA = Module::factory()->create(['project_id' => $project->id]);
        $moduleB = Module::factory()->create(['project_id' => $project->id]);
        Task::factory()->count(3)->create([
            'project_id' => $project->id,
            'module_id' => $moduleA->id,
        ]);
        Bug::factory()->count(1)->create([
            'project_id' => $project->id,
            'module_id' => $moduleA->id,
        ]);

        $response = $this->actingAs($user)->get('/projects');

        $response->assertOk();
        $response->assertSee('Sistem Payroll Terpadu');
        $response->assertSee('[PAYROLL]');
        $response->assertSee('Aktif');
        $response->assertSee('2 Modul');
        $response->assertSee('3 Tugas');
        $response->assertSee('1 Bug');
        $response->assertSee('btn-edit-project', false);
        $response->assertSee('btn-delete-project', false);
    }

    public function test_projects_filter_by_search_and_status(): void
    {
        $user = User::factory()->create();
        $projectA = Project::factory()->create([
            'name' => 'Aplikasi Logistik Express',
            'code' => 'LOGEX',
            'status' => 'active',
        ]);
        $projectB = Project::factory()->create([
            'name' => 'Portal Vendor Lama',
            'code' => 'PORTAL-OLD',
            'status' => 'archived',
        ]);

        // Search test
        $resSearch = $this->actingAs($user)->get('/projects?search=Logistik');
        $resSearch->assertSee('Aplikasi Logistik Express');
        $resSearch->assertDontSee('Portal Vendor Lama');

        // Status test
        $resStatus = $this->actingAs($user)->get('/projects?status=archived');
        $resStatus->assertSee('Portal Vendor Lama');
        $resStatus->assertDontSee('Aplikasi Logistik Express');
    }

    public function test_user_can_create_new_project_via_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/projects', [
            'name' => 'Inventory System 2026',
            'code' => 'inv-sys',
            'status' => 'active',
            'color' => '#0EA5E9',
            'repo_url' => 'https://github.com/company/inv-sys',
            'description' => 'Sistem pencatatan persediaan barang gudang.',
        ]);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'name' => 'Inventory System 2026',
            'code' => 'INV-SYS',
            'status' => 'active',
            'color' => '#0EA5E9',
        ]);
    }

    public function test_create_project_via_json_returns_201(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/projects', [
            'name' => 'Mobile App iOS',
            'code' => 'MOB-IOS',
            'status' => 'maintenance',
            'color' => '#8B5CF6',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('project.code', 'MOB-IOS');
        $response->assertJsonPath('project.status', 'maintenance');
    }

    public function test_create_project_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/projects', []);
        $response->assertSessionHasErrors(['name', 'code', 'status', 'color']);
    }

    public function test_create_project_validates_unique_code(): void
    {
        $user = User::factory()->create();
        Project::factory()->create(['code' => 'PAYROLL']);

        $response = $this->actingAs($user)->post('/projects', [
            'name' => 'Payroll Baru',
            'code' => 'PAYROLL',
            'status' => 'active',
            'color' => '#10B981',
        ]);

        $response->assertSessionHasErrors('code');
    }

    public function test_user_can_update_project_via_put(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'name' => 'Nama Lama',
            'code' => 'KODE-LAMA',
            'status' => 'active',
            'color' => '#4F46E5',
        ]);

        $response = $this->actingAs($user)->put("/projects/{$project->id}", [
            'name' => 'Nama Baru',
            'code' => 'kode-baru',
            'status' => 'maintenance',
            'color' => '#F59E0B',
            'description' => 'Deskripsi update.',
        ]);

        $response->assertRedirect(route('projects.index'));
        $fresh = $project->fresh();
        $this->assertSame('Nama Baru', $fresh->name);
        $this->assertSame('KODE-BARU', $fresh->code);
        $this->assertSame('maintenance', $fresh->status);
        $this->assertSame('#F59E0B', $fresh->color);
    }

    public function test_user_can_delete_project_via_delete(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->delete("/projects/{$project->id}");
        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_projects_page_renders_create_edit_and_delete_modals(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/projects');

        $response->assertOk();
        $response->assertSee('id="modal-create-project"', false);
        $response->assertSee('id="modal-edit-project"', false);
        $response->assertSee('id="modal-delete-project"', false);
        $response->assertSee('Tambah Proyek Baru');
        $response->assertSee('Edit Data Proyek');
        $response->assertSee('Hapus Proyek?');
    }

    public function test_projects_sidebar_does_not_contain_logout_button(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/projects');

        $response->assertOk();
        $content = $response->getContent();
        $sidebarPos = strpos($content, 'id="app-sidebar"');
        $sidebarEnd = strpos($content, '</aside>', $sidebarPos);
        $sidebarHtml = substr($content, $sidebarPos, $sidebarEnd - $sidebarPos);

        $this->assertStringNotContainsString(route('logout'), $sidebarHtml);
        $this->assertStringNotContainsString('action="' . route('logout') . '"', $sidebarHtml);
    }

    public function test_user_can_view_single_project_via_json(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Single Project']);

        $response = $this->actingAs($user)->getJson("/projects/{$project->id}");
        $response->assertOk();
        $response->assertJsonPath('project.name', 'Single Project');
    }
}
