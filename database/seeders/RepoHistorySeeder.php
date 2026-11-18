<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\RepoHistory;
use Illuminate\Database\Seeder;

class RepoHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $historiesByProjectCode = [
            'INTRA' => [
                [
                    'release_date' => '2026-11-05',
                    'release_tags' => 'v1.0.0',
                    'branch_name' => 'main',
                    'commit_hash' => 'a1b2c3d4e5f60718293a4b5c6d7e8f9012345601',
                    'summary_changes' => 'Rilis awal Portal Intranet dengan autentikasi dasar dan feed berita.',
                ],
                [
                    'release_date' => '2026-11-15',
                    'release_tags' => 'v1.1.0',
                    'branch_name' => 'release/1.1',
                    'commit_hash' => 'a1b2c3d4e5f60718293a4b5c6d7e8f9012345602',
                    'summary_changes' => 'Penambahan SSO OAuth2 dan perbaikan invalidasi sesi multi-tab.',
                ],
            ],
            'KEU' => [
                [
                    'release_date' => '2026-11-02',
                    'release_tags' => 'v2.3.0',
                    'branch_name' => 'main',
                    'commit_hash' => 'b2c3d4e5f60718293a4b5c6d7e8f901234560201',
                    'summary_changes' => 'Pembaruan modul pengajuan anggaran per divisi dan ekspor PDF Snappy.',
                ],
                [
                    'release_date' => '2026-11-14',
                    'release_tags' => 'v2.3.1',
                    'branch_name' => 'hotfix/report-cache',
                    'commit_hash' => 'b2c3d4e5f60718293a4b5c6d7e8f901234560202',
                    'summary_changes' => 'Optimasi query laporan tahunan dan penambahan caching Redis.',
                ],
            ],
            'HRIS' => [
                [
                    'release_date' => '2026-10-20',
                    'release_tags' => 'v3.0.4',
                    'branch_name' => 'main',
                    'commit_hash' => 'c3d4e5f60718293a4b5c6d7e8f90123456030301',
                    'summary_changes' => 'Stabilisasi modul penggajian dan pencatatan lembur bulanan.',
                ],
                [
                    'release_date' => '2026-11-12',
                    'release_tags' => null,
                    'branch_name' => 'develop',
                    'commit_hash' => 'c3d4e5f60718293a4b5c6d7e8f90123456030302',
                    'summary_changes' => 'Persiapan perbaikan koordinat GPS absensi untuk perangkat Android terbaru.',
                ],
            ],
        ];

        $requiredCodes = array_keys($historiesByProjectCode);
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

        foreach ($historiesByProjectCode as $code => $entries) {
            $project = $projects->get($code);

            if (! $project) {
                continue;
            }

            foreach ($entries as $entry) {
                RepoHistory::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'commit_hash' => $entry['commit_hash'],
                    ],
                    $entry
                );
            }
        }
    }
}
