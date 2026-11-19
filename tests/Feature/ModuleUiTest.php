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

    public function test_authenticated_user_can_view_modules_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/modules');

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
        $user = User::factory()->create();
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

        $response = $this->actingAs($user)->get('/modules');

        $response->assertOk();
        $response->assertSee('Total Modul');
        $response->assertSee('Dengan Tugas');
        $response->assertSee('Dengan Bug');
        $response->assertSee('Proyek Terkait');
    }

    public function test_modules_page_renders_filter_and_search_bar(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'name' => 'Portal Vendor Online',
            'code' => 'PVND',
        ]);

        $response = $this->actingAs($user)->get('/modules');

        $response->assertOk();
        $response->assertSee('placeholder="Cari modul, deskripsi, atau proyek..."', false);
        $response->assertSee('Semua Proyek (Semua)');
        $response->assertSee('[PVND] Portal Vendor Online');
    }

    public function test_modules_page_renders_table_with_modules_and_counts(): void
    {
        $user = User::factory()->create();
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

        $response = $this->actingAs($user)->get('/modules');

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
        $user = User::factory()->create();
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
        $searchResponse = $this->actingAs($user)->get('/modules?search=Gateway');
        $searchResponse->assertOk();
        $searchResponse->assertSee('Integrasi Payment Gateway');
        $searchResponse->assertDontSee('Modul Penagihan Invoice');

        // Filter proyek
        $projectFilterResponse = $this->actingAs($user)->get("/modules?project_id={$projectB->id}");
        $projectFilterResponse->assertOk();
        $projectFilterResponse->assertSee('Modul Penagihan Invoice');
        $projectFilterResponse->assertDontSee('Integrasi Payment Gateway');
    }

    public function test_user_can_create_new_module_via_post(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->post('/modules', [
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
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->postJson('/modules', [
            'project_id' => $project->id,
            'name' => 'Modul Otentikasi Biometrik',
            'description' => 'Login via sidik jari.',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('module.name', 'Modul Otentikasi Biometrik');
    }

    public function test_create_module_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/modules', [
            'project_id' => '',
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['project_id', 'name']);
    }

    public function test_create_module_validates_invalid_project_id(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/modules', [
            'project_id' => 999999,
            'name' => 'Modul Dummy',
        ]);

        $response->assertSessionHasErrors(['project_id']);
    }

    public function test_user_can_update_module_via_put(): void
    {
        $user = User::factory()->create();
        $projectOld = Project::factory()->create();
        $projectNew = Project::factory()->create();

        $module = Module::factory()->create([
            'project_id' => $projectOld->id,
            'name' => 'Modul Pelaporan Lama',
        ]);

        $response = $this->actingAs($user)->put("/modules/{$module->id}", [
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
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $module = Module::factory()->create([
            'project_id' => $project->id,
            'name' => 'Modul Siap Hapus',
        ]);

        $response = $this->actingAs($user)->delete("/modules/{$module->id}");

        $response->assertRedirect('/modules');
        $this->assertDatabaseMissing('modules', [
            'id' => $module->id,
        ]);
    }

    public function test_modules_page_renders_create_edit_and_delete_modals(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/modules');

        $response->assertOk();
        $response->assertSee('id="modal-create-module"', false);
        $response->assertSee('id="modal-edit-module"', false);
        $response->assertSee('id="modal-delete-module"', false);
    }

    public function test_modules_sidebar_does_not_contain_logout_button(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/modules');

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
        $user = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Proyek Tunggal']);
        $module = Module::factory()->create([
            'project_id' => $project->id,
            'name' => 'Modul Tunggal',
        ]);

        $response = $this->actingAs($user)->getJson("/modules/{$module->id}");

        $response->assertOk();
        $response->assertJsonPath('module.name', 'Modul Tunggal');
        $response->assertJsonPath('module.project.name', 'Proyek Tunggal');
    }
}
