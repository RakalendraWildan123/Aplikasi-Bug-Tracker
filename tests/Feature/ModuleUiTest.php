<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Module;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_modules(): void
    {
        $response = $this->get('/modules');

        $response->assertRedirect('/login');
    }

    public function test_developer_can_access_modules_page(): void
    {
        $developer = User::factory()->create(['role' => 'developer']);
        $response = $this->actingAs($developer)->get('/modules');
        $response->assertOk();
        $response->assertSee('Master Modul');
    }

    public function test_developer_can_create_update_or_delete_module(): void
    {
        $developer = User::factory()->create(['role' => 'developer']);
        $project = Project::factory()->create();
        $module = Module::factory()->create(['project_id' => $project->id, 'name' => 'Modul Awal']);

        // Attempt POST
        $this->actingAs($developer)->post('/modules', [
            'project_id' => $project->id,
            'name' => 'Modul Baru Dev',
        ])->assertRedirect(route('modules.index'));

        $this->assertDatabaseHas('modules', ['name' => 'Modul Baru Dev']);

        // Attempt PUT
        $this->actingAs($developer)->put("/modules/{$module->id}", [
            'project_id' => $project->id,
            'name' => 'Modul Diubah Dev',
        ])->assertRedirect(route('modules.index'));

        $this->assertDatabaseHas('modules', ['id' => $module->id, 'name' => 'Modul Diubah Dev']);

        // Attempt DELETE
        $this->actingAs($developer)->delete("/modules/{$module->id}")->assertRedirect(route('modules.index'));

        $this->assertDatabaseMissing('modules', ['id' => $module->id]);
    }

    public function test_admin_user_can_view_modules_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/modules');

        $response->assertOk();
        $response->assertSee('Master Modul');
        $response->assertSee('Kelola modul fitur untuk setiap proyek');
        $response->assertSee('btn-open-create-module', false);
    }

    public function test_modules_page_renders_sidebar_navigation_with_categories(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/modules');

        $response->assertOk();
        $response->assertSee('Fitur Operasional');
        $response->assertSee('Papan Kanban');
        $response->assertSee('Daftar Tugas');
        $response->assertSee('Pelacak Bug');
        $response->assertSee('Fitur Master Data');
        $response->assertSee('Master Proyek');
        $response->assertSee('Master Modul');
        $response->assertSee('Master Akun');
        $response->assertSee('Pengaturan');
        $response->assertSee('Akun Saya');
    }

    public function test_modules_page_renders_summary_metric_cards(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create();

        $moduleA = Module::factory()->create([
            'project_id' => $project->id,
            'name' => 'Modul Alpha',
        ]);
        $moduleB = Module::factory()->create([
            'project_id' => $project->id,
            'name' => 'Modul Beta',
        ]);

        Task::factory()->count(2)->create([
            'project_id' => $project->id,
            'module_id' => $moduleA->id,
        ]);
        Bug::factory()->count(1)->create([
            'project_id' => $project->id,
            'module_id' => $moduleB->id,
        ]);

        $response = $this->actingAs($admin)->get('/modules');

        $response->assertOk();
        $response->assertSee('Total Modul');
        $response->assertSee('Dengan Tugas');
        $response->assertSee('Dengan Bug');
        $response->assertSee('Proyek Terkait');
    }

    public function test_modules_page_renders_filter_and_search_bar(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create([
            'name' => 'Portal Vendor Online',
            'code' => 'PVND',
        ]);

        $response = $this->actingAs($admin)->get('/modules');

        $response->assertOk();
        $response->assertSee('placeholder="Cari modul, deskripsi, atau proyek..."', false);
        $response->assertSee('Semua Proyek (Semua)');
        $response->assertSee('[PVND] Portal Vendor Online');
    }

    public function test_modules_page_renders_table_with_modules_and_counts(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create([
            'name' => 'Aplikasi SDM Internal',
            'code' => 'HRIS',
            'color' => '#10B981',
        ]);

        $module = Module::factory()->create([
            'project_id' => $project->id,
            'name' => 'Modul Absensi Karyawan',
            'description' => 'Pencatatan jam masuk dan jam pulang.',
        ]);

        Task::factory()->count(2)->create([
            'project_id' => $project->id,
            'module_id' => $module->id,
        ]);
        Bug::factory()->count(1)->create([
            'project_id' => $project->id,
            'module_id' => $module->id,
        ]);

        $response = $this->actingAs($admin)->get('/modules');

        $response->assertOk();
        $response->assertSee('Modul Absensi Karyawan');
        $response->assertSee('Pencatatan jam masuk dan jam pulang.');
        $response->assertSee('[HRIS]');
        $response->assertSee('Aplikasi SDM Internal');
        $response->assertSee('2 Tugas');
        $response->assertSee('1 Bug');
        $response->assertSee('btn-edit-module', false);
        $response->assertSee('btn-delete-module', false);
    }

    public function test_modules_filter_by_search_and_project(): void
    {
        $admin = User::factory()->admin()->create();
        $projectA = Project::factory()->create(['name' => 'Proyek Alpha', 'code' => 'ALPHA']);
        $projectB = Project::factory()->create(['name' => 'Proyek Beta', 'code' => 'BETA']);

        $moduleA = Module::factory()->create([
            'project_id' => $projectA->id,
            'name' => 'Integrasi Payment Gateway',
        ]);
        $moduleB = Module::factory()->create([
            'project_id' => $projectB->id,
            'name' => 'Modul Penagihan Invoice',
        ]);

        // Filter pencarian
        $searchResponse = $this->actingAs($admin)->get('/modules?search=Gateway');
        $searchResponse->assertOk();
        $searchResponse->assertSee('Integrasi Payment Gateway');
        $searchResponse->assertDontSee('Modul Penagihan Invoice');

        // Filter proyek
        $projectFilterResponse = $this->actingAs($admin)->get("/modules?project_id={$projectB->id}");
        $projectFilterResponse->assertOk();
        $projectFilterResponse->assertSee('Modul Penagihan Invoice');
        $projectFilterResponse->assertDontSee('Integrasi Payment Gateway');
    }

    public function test_user_can_create_new_module_via_post(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($admin)->post('/modules', [
            'project_id' => $project->id,
            'name' => 'Modul Notifikasi Push',
            'description' => 'Layanan pengiriman pesan real-time.',
        ]);

        $response->assertRedirect('/modules');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('modules', [
            'project_id' => $project->id,
            'name' => 'Modul Notifikasi Push',
            'description' => 'Layanan pengiriman pesan real-time.',
        ]);
    }

    public function test_create_module_via_json_returns_201(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($admin)->postJson('/modules', [
            'project_id' => $project->id,
            'name' => 'Modul Otentikasi Biometrik',
            'description' => 'Login via sidik jari.',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('module.name', 'Modul Otentikasi Biometrik');
    }

    public function test_create_module_validates_required_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/modules', [
            'project_id' => '',
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['project_id', 'name']);
    }

    public function test_create_module_validates_invalid_project_id(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/modules', [
            'project_id' => 999999,
            'name' => 'Modul Dummy',
        ]);

        $response->assertSessionHasErrors(['project_id']);
    }

    public function test_user_can_update_module_via_put(): void
    {
        $admin = User::factory()->admin()->create();
        $projectOld = Project::factory()->create();
        $projectNew = Project::factory()->create();

        $module = Module::factory()->create([
            'project_id' => $projectOld->id,
            'name' => 'Modul Pelaporan Lama',
        ]);

        $response = $this->actingAs($admin)->put("/modules/{$module->id}", [
            'project_id' => $projectNew->id,
            'name' => 'Modul Pelaporan Baru',
            'description' => 'Format ekspor Excel dan PDF otomatis.',
        ]);

        $response->assertRedirect('/modules');

        $this->assertDatabaseHas('modules', [
            'id' => $module->id,
            'project_id' => $projectNew->id,
            'name' => 'Modul Pelaporan Baru',
            'description' => 'Format ekspor Excel dan PDF otomatis.',
        ]);
    }

    public function test_user_can_delete_module_via_delete(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create();
        $module = Module::factory()->create([
            'project_id' => $project->id,
            'name' => 'Modul Siap Hapus',
        ]);

        $response = $this->actingAs($admin)->delete("/modules/{$module->id}");

        $response->assertRedirect('/modules');
        $this->assertDatabaseMissing('modules', [
            'id' => $module->id,
        ]);
    }

    public function test_modules_page_renders_create_edit_and_delete_modals(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/modules');

        $response->assertOk();
        $response->assertSee('id="modal-create-module"', false);
        $response->assertSee('id="modal-edit-module"', false);
        $response->assertSee('id="modal-delete-module"', false);
    }

    public function test_modules_sidebar_does_not_contain_logout_button(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/modules');

        $response->assertOk();

        $content = $response->getContent();
        $sidebarPos = strpos($content, 'id="app-sidebar"');
        $sidebarEnd = strpos($content, '</aside>', $sidebarPos);
        $sidebarHtml = substr($content, $sidebarPos, $sidebarEnd - $sidebarPos);

        $this->assertStringNotContainsString(route('logout'), $sidebarHtml);
        $this->assertStringNotContainsString('>Logout<', $sidebarHtml);
    }

    public function test_user_can_view_single_module_via_json(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create(['name' => 'Proyek Tunggal']);
        $module = Module::factory()->create([
            'project_id' => $project->id,
            'name' => 'Modul Tunggal',
        ]);

        $response = $this->actingAs($admin)->getJson("/modules/{$module->id}");

        $response->assertOk();
        $response->assertJsonPath('module.name', 'Modul Tunggal');
        $response->assertJsonPath('module.project.name', 'Proyek Tunggal');
    }
}
