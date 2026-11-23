<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Services\AppEvaluationService;
use Illuminate\Console\Command;

class GenerateAppEvaluationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'evaluations:generate
                            {--project= : ID atau Kode Proyek tertentu untuk dievaluasi}
                            {--period= : Periode evaluasi (default: periode kuartal saat ini)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate dan sinkronkan evaluasi aplikasi otomatis berdasarkan data real-time bug, tugas, dan riwayat rilis.';

    /**
     * Execute the console command.
     */
    public function handle(AppEvaluationService $evaluationService): int
    {
        $period = $this->option('period') ?: AppEvaluationService::currentPeriod();
        $projectOption = $this->option('project');

        $this->info("Menjalankan Evaluasi Aplikasi Otomatis untuk Periode: [{$period}]...");

        if ($projectOption) {
            $project = Project::query()
                ->where('id', $projectOption)
                ->orWhere('code', $projectOption)
                ->first();

            if (! $project) {
                $this->error("Proyek dengan ID/Kode [{$projectOption}] tidak ditemukan.");
                return Command::FAILURE;
            }

            $evaluation = $evaluationService->generateForProject($project, $period);
            $this->info("✓ Berhasil mengevaluasi proyek: [{$project->code}] {$project->name}");
            $this->line("  - Kendala: {$evaluation->operational_issues}");
            $this->line("  - Feedback/Kinerja: {$evaluation->user_feedback}");
            return Command::SUCCESS;
        }

        $count = $evaluationService->generateAll($period);
        $this->info("✓ Berhasil mengevaluasi dan menyinkronkan {$count} proyek untuk periode [{$period}].");

        return Command::SUCCESS;
    }
}
