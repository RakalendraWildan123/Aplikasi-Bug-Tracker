<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\Module;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Developer::query()->count() < 4) {
            $this->call(DeveloperSeeder::class);
        }

        if (Project::query()->count() < 3) {
            $this->call(ProjectSeeder::class);
        }

        if (Module::query()->count() < 6) {
            $this->call(ModuleSeeder::class);
        }

        $developers = Developer::query()->get()->keyBy('email');
        $projects = Project::query()->with('modules')->get()->keyBy('code');

        $lead = $developers->get('raka.pratama@bugtracker.test') ?? Developer::query()->firstOrFail();
        $backend = $developers->get('dina.kusuma@bugtracker.test') ?? $lead;
        $frontend = $developers->get('budi.santoso@bugtracker.test') ?? $lead;
        $mobile = $developers->get('siti.rahma@bugtracker.test') ?? $lead;

        $intra = $projects->get('INTRA');
        $keu = $projects->get('KEU');
        $hris = $projects->get('HRIS');

        if (! $intra || ! $keu || ! $hris) {
            return;
        }

        $tasks = [
            [
                'project_id' => $intra->id,
                'module_id' => $intra->modules->firstWhere('name', 'Autentikasi & SSO')?->id,
                'created_by' => $lead->id,
                'assigned_to' => $backend->id,
                'title' => 'Implementasi OAuth2 SSO untuk Portal Intranet',
                'description' => 'Menambahkan login SSO terpusat menggunakan OAuth2 dan token refresh.',
                'task_type' => 'feature',
                'status' => 'in_progress',
                'start_date' => '2026-11-10',
                'target_date' => '2026-11-24',
                'completed_at' => null,
                'blocker_notes' => 'Menunggu kredensial client_secret dari tim infrastruktur.',
                'handover_logs' => [
                    [
                        'from_developer_id' => $lead->id,
                        'to_developer_id' => $backend->id,
                        'note' => 'Dokumen arsitektur SSO sudah disetujui, lanjut implementasi endpoint callback.',
                        'handed_over_at' => '2026-11-11T09:30:00Z',
                    ],
                ],
            ],
            [
                'project_id' => $intra->id,
                'module_id' => $intra->modules->firstWhere('name', 'Portal Berita & Pengumuman')?->id,
                'created_by' => $lead->id,
                'assigned_to' => $frontend->id,
                'title' => 'Optimasi Tampilan Feed Pengumuman di Layar Mobile',
                'description' => 'Meningkatkan responsivitas kartu berita dan lazy-loading gambar.',
                'task_type' => 'enhancement',
                'status' => 'review',
                'start_date' => '2026-11-12',
                'target_date' => '2026-11-20',
                'completed_at' => null,
                'blocker_notes' => null,
                'handover_logs' => [],
            ],
            [
                'project_id' => $keu->id,
                'module_id' => $keu->modules->firstWhere('name', 'Laporan Keuangan')?->id,
                'created_by' => $lead->id,
                'assigned_to' => $backend->id,
                'title' => 'Refactor Query Rekapitulasi Transaksi Bulanan',
                'description' => 'Mengurangi N+1 query dan menambahkan caching Redis untuk ekspor PDF laporan.',
                'task_type' => 'refactor',
                'status' => 'done',
                'start_date' => '2026-11-01',
                'target_date' => '2026-11-14',
                'completed_at' => '2026-11-13 16:45:00',
                'blocker_notes' => null,
                'handover_logs' => [],
            ],
            [
                'project_id' => $hris->id,
                'module_id' => $hris->modules->firstWhere('name', 'Manajemen Absensi')?->id,
                'created_by' => $lead->id,
                'assigned_to' => $mobile->id,
                'title' => 'Maintenance Sinkronisasi Lokasi Absensi Android',
                'description' => 'Pembaruan library GPS untuk menjaga akurasi absensi di perangkat Android terbaru.',
                'task_type' => 'maintenance',
                'status' => 'todo',
                'start_date' => '2026-11-19',
                'target_date' => '2026-11-28',
                'completed_at' => null,
                'blocker_notes' => null,
                'handover_logs' => [],
            ],
        ];

        foreach ($tasks as $task) {
            Task::updateOrCreate(
                [
                    'project_id' => $task['project_id'],
                    'title' => $task['title'],
                ],
                $task
            );
        }
    }
}
