<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'Portal Intranet Perusahaan',
                'code' => 'INTRA',
                'description' => 'Portal internal untuk kolaborasi karyawan, pengumuman, dan dokumen operasional.',
                'repo_url' => 'https://github.com/internal-it/portal-intranet',
                'status' => 'active',
                'color' => '#4F46E5',
            ],
            [
                'name' => 'Sistem Keuangan & Anggaran',
                'code' => 'KEU',
                'description' => 'Aplikasi pengelolaan anggaran, pencatatan transaksi, dan pelaporan keuangan.',
                'repo_url' => 'https://github.com/internal-it/sistem-keuangan',
                'status' => 'maintenance',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Human Resource Information System',
                'code' => 'HRIS',
                'description' => 'Sistem manajemen data karyawan, absensi, cuti, dan penggajian.',
                'repo_url' => 'https://github.com/internal-it/hris-legacy',
                'status' => 'archived',
                'color' => '#64748B',
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['code' => $project['code']],
                $project
            );
        }
    }
}
