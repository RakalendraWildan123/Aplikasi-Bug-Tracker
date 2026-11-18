<?php

namespace Database\Seeders;

use App\Models\Bug;
use App\Models\Developer;
use App\Models\Module;
use App\Models\Project;
use Illuminate\Database\Seeder;

class BugSeeder extends Seeder
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

        $bugs = [
            [
                'project_id' => $keu->id,
                'module_id' => $keu->modules->firstWhere('name', 'Laporan Keuangan')?->id,
                'created_by' => $lead->id,
                'assigned_to' => $backend->id,
                'title' => 'Timeout 504 saat Ekspor Laporan Tahunan PDF',
                'description' => 'Ekspor laporan keuangan periode 12 bulan mengalami timeout ketika data melebihi 10.000 baris.',
                'reported_by' => 'Divisi Finance - Hendra',
                'reported_date' => '2026-11-12',
                'severity' => 'critical',
                'status' => 'in_progress',
                'root_cause' => 'Query agregasi berjalan tanpa index komposit dan belum memanfaatkan cache ekspor Redis.',
                'solution_notes' => null,
                'resolved_at' => null,
                'handover_logs' => [
                    [
                        'from_developer_id' => $lead->id,
                        'to_developer_id' => $backend->id,
                        'note' => 'Profiling selesai, bottleneck ditemukan di query rekapitulasi bulanan.',
                        'handed_over_at' => '2026-11-13T08:45:00Z',
                    ],
                ],
            ],
            [
                'project_id' => $intra->id,
                'module_id' => $intra->modules->firstWhere('name', 'Autentikasi & SSO')?->id,
                'created_by' => $lead->id,
                'assigned_to' => $backend->id,
                'title' => 'Token Sesi Tidak Ter-invalidate Saat Logout Multi-Tab',
                'description' => 'Pengguna yang logout di satu tab masih dapat mengakses API dari tab lain hingga token expired.',
                'reported_by' => 'QA - Sari',
                'reported_date' => '2026-11-08',
                'severity' => 'high',
                'status' => 'resolved',
                'root_cause' => 'Endpoint logout hanya menghapus cookie lokal tanpa merevoke session token di server.',
                'solution_notes' => 'Menambahkan invalidasi sesi server-side dan regenerasi CSRF token saat logout.',
                'resolved_at' => '2026-11-10 15:20:00',
                'handover_logs' => [],
            ],
            [
                'project_id' => $hris->id,
                'module_id' => $hris->modules->firstWhere('name', 'Manajemen Absensi')?->id,
                'created_by' => $lead->id,
                'assigned_to' => $mobile->id,
                'title' => 'Koordinat GPS Terkirim 0,0 Saat Izin Lokasi Baru Diaktifkan',
                'description' => 'Pada beberapa perangkat Android, check-in pertama setelah memberi izin lokasi mengirim koordinat kosong.',
                'reported_by' => 'HR Operations - Nia',
                'reported_date' => '2026-11-15',
                'severity' => 'medium',
                'status' => 'open',
                'root_cause' => null,
                'solution_notes' => null,
                'resolved_at' => null,
                'handover_logs' => [],
            ],
            [
                'project_id' => $intra->id,
                'module_id' => $intra->modules->firstWhere('name', 'Portal Berita & Pengumuman')?->id,
                'created_by' => $lead->id,
                'assigned_to' => $frontend->id,
                'title' => 'Badge Kategori Pengumuman Terpotong di Layar 360px',
                'description' => 'Teks badge kategori panjang keluar dari kontainer kartu pada viewport smartphone kecil.',
                'reported_by' => 'QA - Dimas',
                'reported_date' => '2026-11-16',
                'severity' => 'low',
                'status' => 'open',
                'root_cause' => null,
                'solution_notes' => null,
                'resolved_at' => null,
                'handover_logs' => [],
            ],
        ];

        foreach ($bugs as $bug) {
            Bug::updateOrCreate(
                [
                    'project_id' => $bug['project_id'],
                    'title' => $bug['title'],
                ],
                $bug
            );
        }
    }
}
