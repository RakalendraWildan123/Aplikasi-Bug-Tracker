<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modulesByProjectCode = [
            'INTRA' => [
                [
                    'name' => 'Autentikasi & SSO',
                    'description' => 'Pengelolaan login terpusat, sesi pengguna, dan hak akses internal.',
                ],
                [
                    'name' => 'Portal Berita & Pengumuman',
                    'description' => 'Publikasi informasi perusahaan, pengumuman HR, dan arsip dokumen.',
                ],
            ],
            'KEU' => [
                [
                    'name' => 'Pengelolaan Anggaran',
                    'description' => 'Perencanaan, pengajuan, dan persetujuan anggaran per divisi.',
                ],
                [
                    'name' => 'Laporan Keuangan',
                    'description' => 'Rekapitulasi transaksi bulanan serta ekspor laporan keuangan.',
                ],
            ],
            'HRIS' => [
                [
                    'name' => 'Manajemen Absensi',
                    'description' => 'Pencatatan kehadiran harian, lembur, dan pengajuan cuti karyawan.',
                ],
                [
                    'name' => 'Penggajian & Slip Gaji',
                    'description' => 'Perhitungan gaji bulanan, potongan, serta penerbitan slip gaji.',
                ],
            ],
        ];

        $requiredCodes = array_keys($modulesByProjectCode);
        $projects = Project::query()
            ->whereIn('code', $requiredCodes)
            ->get()
            ->keyBy('code');

        if ($projects->count() < count($requiredCodes)) {
            $this->call(ProjectSeeder::class);
            $projects = Project::query()
                ->whereIn('code', $requiredCodes)
                ->get()
                ->keyBy('code');
        }

        foreach ($modulesByProjectCode as $code => $modules) {
            $project = $projects->get($code);

            if (! $project) {
                continue;
            }

            foreach ($modules as $module) {
                Module::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'name' => $module['name'],
                    ],
                    [
                        'description' => $module['description'],
                    ]
                );
            }
        }
    }
}
