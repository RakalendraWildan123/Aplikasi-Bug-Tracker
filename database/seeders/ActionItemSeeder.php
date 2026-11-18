<?php

namespace Database\Seeders;

use App\Enums\ActionItemStatus;
use App\Models\ActionItem;
use App\Models\AppEvaluation;
use App\Models\Developer;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class ActionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Developer::query()->count() < 4) {
            $this->call(DeveloperSeeder::class);
        }

        if (AppEvaluation::query()->count() < 6) {
            $this->call(AppEvaluationSeeder::class);
        }

        if (Task::query()->count() < 4) {
            $this->call(TaskSeeder::class);
        }

        $developers = Developer::query()->get()->keyBy('email');
        $projects = Project::query()->with('appEvaluations')->get()->keyBy('code');

        $lead = $developers->get('raka.pratama@bugtracker.test') ?? Developer::query()->firstOrFail();
        $backend = $developers->get('dina.kusuma@bugtracker.test') ?? $lead;
        $frontend = $developers->get('budi.santoso@bugtracker.test') ?? $lead;
        $mobile = $developers->get('siti.rahma@bugtracker.test') ?? $lead;

        $intraQ3 = $projects->get('INTRA')?->appEvaluations->firstWhere('period_review', 'Q3 2026');
        $keuQ3 = $projects->get('KEU')?->appEvaluations->firstWhere('period_review', 'Q3 2026');
        $keuQ4 = $projects->get('KEU')?->appEvaluations->firstWhere('period_review', 'Q4 2026');
        $hrisQ3 = $projects->get('HRIS')?->appEvaluations->firstWhere('period_review', 'Q3 2026');

        $ssoTask = Task::query()
            ->where('title', 'Implementasi OAuth2 SSO untuk Portal Intranet')
            ->first();

        $refactorTask = Task::query()
            ->where('title', 'Refactor Query Rekapitulasi Transaksi Bulanan')
            ->first();

        if (! $intraQ3 || ! $keuQ3 || ! $keuQ4 || ! $hrisQ3) {
            return;
        }

        $items = [
            [
                'evaluation_id' => $intraQ3->id,
                'action_plan' => 'Implementasi autentikasi terpusat OAuth2 SSO untuk mempercepat login jam sibuk.',
                'assigned_to' => $backend->id,
                'target_date' => '2026-11-24',
                'status' => ActionItemStatus::ConvertedToTask,
                'converted_task_id' => $ssoTask?->id,
            ],
            [
                'evaluation_id' => $keuQ3->id,
                'action_plan' => 'Optimasi query agregasi dan tambahkan Redis caching untuk ekspor PDF laporan keuangan.',
                'assigned_to' => $backend->id,
                'target_date' => '2026-11-14',
                'status' => ActionItemStatus::Completed,
                'converted_task_id' => $refactorTask?->id,
            ],
            [
                'evaluation_id' => $keuQ4->id,
                'action_plan' => 'Siapkan alert monitoring penggunaan memori Redis menjelang periode tutup buku bulanan.',
                'assigned_to' => $lead->id,
                'target_date' => '2026-12-05',
                'status' => ActionItemStatus::Planned,
                'converted_task_id' => null,
            ],
            [
                'evaluation_id' => $hrisQ3->id,
                'action_plan' => 'Perbarui mekanisme izin GPS dan tambah indikator notifikasi approval cuti di aplikasi.',
                'assigned_to' => $mobile->id,
                'target_date' => '2026-11-30',
                'status' => ActionItemStatus::Planned,
                'converted_task_id' => null,
            ],
        ];

        foreach ($items as $item) {
            ActionItem::updateOrCreate(
                [
                    'evaluation_id' => $item['evaluation_id'],
                    'action_plan' => $item['action_plan'],
                ],
                $item
            );
        }
    }
}
