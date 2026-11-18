<?php

namespace Database\Seeders;

use App\Models\AppEvaluation;
use App\Models\Project;
use Illuminate\Database\Seeder;

class AppEvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $evaluationsByProjectCode = [
            'INTRA' => [
                [
                    'evaluation_date' => '2026-09-30',
                    'period_review' => 'Q3 2026',
                    'operational_issues' => 'Lonjakan beban login pada jam 08.00 WIB menyebabkan respons halaman melambat sementara.',
                    'user_feedback' => 'Karyawan mengapresiasi fitur pencarian dokumen baru dan meminta dukungan SSO.',
                ],
                [
                    'evaluation_date' => '2026-11-16',
                    'period_review' => 'Q4 2026',
                    'operational_issues' => null,
                    'user_feedback' => 'Login SSO berjalan stabil dan tampilan berita di desktop dinilai lebih rapi.',
                ],
            ],
            'KEU' => [
                [
                    'evaluation_date' => '2026-09-28',
                    'period_review' => 'Q3 2026',
                    'operational_issues' => 'Ekspor laporan PDF tahunan sempat mengalami timeout pada data transaksi besar.',
                    'user_feedback' => 'Tim finance membutuhkan waktu generasi laporan di bawah 5 detik.',
                ],
                [
                    'evaluation_date' => '2026-11-15',
                    'period_review' => 'Q4 2026',
                    'operational_issues' => 'Perlu pemantauan memori Redis saat puncak tutup buku akhir bulan.',
                    'user_feedback' => 'Waktu ekspor PDF jauh lebih cepat setelah caching Redis diaktifkan.',
                ],
            ],
            'HRIS' => [
                [
                    'evaluation_date' => '2026-09-25',
                    'period_review' => 'Q3 2026',
                    'operational_issues' => 'Beberapa perangkat Android baru gagal mengambil koordinat GPS saat check-in pertama.',
                    'user_feedback' => 'HR meminta notifikasi pengingat approval cuti yang lebih jelas.',
                ],
                [
                    'evaluation_date' => '2026-11-14',
                    'period_review' => 'Q4 2026',
                    'operational_issues' => 'Aplikasi masuk fase arsip; perlu rencana migrasi data ke sistem HR baru.',
                    'user_feedback' => 'Data historis absensi dan slip gaji tetap dibutuhkan dalam mode read-only.',
                ],
            ],
        ];

        $requiredCodes = array_keys($evaluationsByProjectCode);
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

        foreach ($evaluationsByProjectCode as $code => $entries) {
            $project = $projects->get($code);

            if (! $project) {
                continue;
            }

            foreach ($entries as $entry) {
                AppEvaluation::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'period_review' => $entry['period_review'],
                    ],
                    $entry
                );
            }
        }
    }
}
