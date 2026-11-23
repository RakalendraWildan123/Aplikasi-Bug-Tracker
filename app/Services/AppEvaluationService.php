<?php

namespace App\Services;

use App\Models\ActionItem;
use App\Models\AppEvaluation;
use App\Models\Bug;
use App\Models\Project;
use App\Models\RepoHistory;
use App\Models\Task;

class AppEvaluationService
{
    /**
     * Dapatkan label periode evaluasi saat ini (misal: "Q4 2026").
     */
    public static function currentPeriod(): string
    {
        $quarter = (int) ceil(now()->month / 3);
        $year = now()->year;

        return "Q{$quarter} {$year}";
    }

    /**
     * Generate atau sinkronkan evaluasi otomatis untuk seluruh proyek yang terdaftar.
     */
    public function generateAll(?string $period = null): int
    {
        $period = $period ?: self::currentPeriod();
        $projects = Project::query()->orderBy('name')->get();
        $count = 0;

        foreach ($projects as $project) {
            $this->generateForProject($project, $period);
            $count++;
        }

        return $count;
    }

    /**
     * Sinkronkan evaluasi untuk proyek yang belum memiliki evaluasi pada periode tertentu.
     */
    public function syncMissingEvaluations(?string $period = null): int
    {
        $period = $period ?: self::currentPeriod();
        $projectsWithoutEvaluation = Project::query()
            ->whereDoesntHave('appEvaluations', function ($q) use ($period) {
                $q->where('period_review', $period);
            })
            ->get();

        $count = 0;
        foreach ($projectsWithoutEvaluation as $project) {
            $this->generateForProject($project, $period);
            $count++;
        }

        return $count;
    }

    /**
     * Generate atau perbarui evaluasi otomatis untuk satu proyek berdasarkan data real-time bug, tugas, dan rilis.
     */
    public function generateForProject(Project $project, ?string $period = null): AppEvaluation
    {
        $period = $period ?: self::currentPeriod();

        // 1. Ambil data Tugas (Tasks)
        $tasks = Task::query()->where('project_id', $project->id)->get();
        $totalTasks = $tasks->count();
        $doneTasks = $tasks->where('status', 'done')->count();
        $blockedTasks = $tasks->filter(function ($task) {
            return ! empty($task->blocker_notes);
        });
        $overdueTasks = $tasks->filter(function ($task) {
            return $task->status !== 'done'
                && ! empty($task->target_date)
                && $task->target_date->isPast();
        });

        // 2. Ambil data Bug Tracker
        $bugs = Bug::query()->where('project_id', $project->id)->get();
        $totalBugs = $bugs->count();
        $openBugs = $bugs->whereIn('status', ['open', 'in_progress']);
        $criticalBugs = $openBugs->where('severity', 'critical');
        $highBugs = $openBugs->where('severity', 'high');
        $resolvedBugs = $bugs->whereIn('status', ['resolved', 'closed'])->count();

        // 3. Ambil data Rilis Kode Terkini
        $latestRelease = RepoHistory::query()
            ->where('project_id', $project->id)
            ->latest('release_date')
            ->first();

        // 4. Kompilasi Kendala Operasional (Operational Issues) Otomatis
        $issues = [];

        $severeBugs = $criticalBugs->concat($highBugs);
        if ($severeBugs->isNotEmpty()) {
            $critCount = $criticalBugs->count();
            $highCount = $highBugs->count();
            $bugDetails = $severeBugs->take(2)->pluck('title')->implode(', ');

            $bugSummary = "Terdeteksi {$severeBugs->count()} bug prioritas tinggi/kritis belum terselesaikan";
            if ($critCount > 0 && $highCount > 0) {
                $bugSummary .= " ({$critCount} Kritis, {$highCount} Tinggi)";
            } elseif ($critCount > 0) {
                $bugSummary .= " ({$critCount} Kritis)";
            } else {
                $bugSummary .= " ({$highCount} Tinggi)";
            }
            $bugSummary .= ": {$bugDetails}.";
            $issues[] = $bugSummary;
        }

        if ($blockedTasks->isNotEmpty()) {
            $blockerList = $blockedTasks->take(2)->map(function ($t) {
                return "'{$t->title}' ({$t->blocker_notes})";
            })->implode(', ');
            $issues[] = "Terdapat {$blockedTasks->count()} tugas terhambat kendala blocker: {$blockerList}.";
        }

        if ($overdueTasks->isNotEmpty()) {
            $issues[] = "Terdapat {$overdueTasks->count()} tugas melewati target tanggal penyelesaian.";
        }

        if (empty($issues) && $openBugs->isNotEmpty()) {
            $minorDetails = $openBugs->take(2)->pluck('title')->implode(', ');
            $issues[] = "Terdapat {$openBugs->count()} bug aktif dalam penanganan: {$minorDetails}.";
        }

        if (empty($issues)) {
            $operationalIssues = 'Tidak ada kendala operasional aktif. Seluruh modul dan alur kerja proyek berjalan stabil sesuai jadwal.';
        } else {
            $operationalIssues = implode(' ', $issues);
        }

        // 5. Kompilasi Evaluasi Kinerja & Umpan Balik (User Feedback / Performance) Otomatis
        $taskRate = $totalTasks > 0 ? (int) round(($doneTasks / $totalTasks) * 100) : 0;
        $bugRate = $totalBugs > 0 ? (int) round(($resolvedBugs / $totalBugs) * 100) : 100;

        $feedbackParts = [];
        $feedbackParts[] = "Kinerja Sistem: Penyelesaian tugas {$taskRate}% ({$doneTasks}/{$totalTasks} selesai) | Rasio penanganan bug {$bugRate}% ({$resolvedBugs}/{$totalBugs} tuntas).";

        if ($latestRelease) {
            $releaseDate = $latestRelease->release_date ? $latestRelease->release_date->format('d M Y') : '-';
            $feedbackParts[] = "Rilis stabil terakhir: {$latestRelease->release_tags} ({$releaseDate}).";
        }

        if ($severeBugs->isNotEmpty() || $blockedTasks->isNotEmpty()) {
            $feedbackParts[] = 'Status: Perlu Tindakan Cepat — Prioritaskan penyelesaian bug kritis dan buka hambatan tugas blocker.';
        } elseif ($taskRate >= 70 && $bugRate >= 80) {
            $feedbackParts[] = 'Status: Sangat Baik & Stabil — Operasional sistem berjalan optimal tanpa kendala berarti.';
        } else {
            $feedbackParts[] = 'Status: Dalam Pengembangan Aktif — Lanjutkan pengerjaan backlog dan pemeliharaan performa.';
        }

        $userFeedback = implode(' ', $feedbackParts);

        // 6. Simpan atau Perbarui Data Evaluasi Aplikasi
        $evaluation = AppEvaluation::query()->updateOrCreate(
            [
                'project_id' => $project->id,
                'period_review' => $period,
            ],
            [
                'evaluation_date' => now()->toDateString(),
                'operational_issues' => $operationalIssues,
                'user_feedback' => $userFeedback,
            ]
        );

        // 7. Otomatisasi Rencana Tindak Lanjut (Action Items)
        $this->syncActionItems($evaluation, $severeBugs, $blockedTasks, $project);

        // 8. Satukan evaluasi lama pada proyek induk ini agar tidak ada duplikasi proyek
        $this->consolidateProjectEvaluations($project->id, $evaluation->id);

        return $evaluation;
    }

    /**
     * Satukan seluruh evaluasi lama pada proyek induk ini ke evaluasi aktif terbaru.
     */
    public function consolidateProjectEvaluations(int $projectId, int $activeEvaluationId): void
    {
        $oldEvaluationIds = AppEvaluation::query()
            ->where('project_id', $projectId)
            ->where('id', '!=', $activeEvaluationId)
            ->pluck('id');

        if ($oldEvaluationIds->isNotEmpty()) {
            // Pindahkan action items dari evaluasi lama ke evaluasi aktif
            ActionItem::query()
                ->whereIn('evaluation_id', $oldEvaluationIds)
                ->update(['evaluation_id' => $activeEvaluationId]);

            // Hapus rekaman evaluasi lama agar proyek induk tidak terduplikasi
            AppEvaluation::query()
                ->whereIn('id', $oldEvaluationIds)
                ->delete();
        }
    }

    /**
     * Sinkronkan Action Items otomatis dari bug prioritas tinggi dan tugas blocker.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\Bug>  $severeBugs
     * @param  \Illuminate\Support\Collection<int, \App\Models\Task>  $blockedTasks
     */
    protected function syncActionItems(AppEvaluation $evaluation, $severeBugs, $blockedTasks, Project $project): void
    {
        $existingPlans = ActionItem::query()
            ->where('evaluation_id', $evaluation->id)
            ->pluck('action_plan')
            ->all();

        // Action items untuk bug prioritas tinggi / kritis
        foreach ($severeBugs as $bug) {
            $plan = "Perbaiki bug [{$bug->severity}]: {$bug->title}";
            if (! in_array($plan, $existingPlans, true)) {
                ActionItem::query()->create([
                    'evaluation_id' => $evaluation->id,
                    'action_plan' => $plan,
                    'assigned_to' => $bug->assigned_to,
                    'target_date' => now()->addDays(3)->toDateString(),
                    'status' => $bug->status === 'resolved' ? 'completed' : 'planned',
                ]);
                $existingPlans[] = $plan;
            }
        }

        // Action items untuk blocker tugas
        foreach ($blockedTasks as $task) {
            $plan = "Selesaikan kendala tugas: {$task->title}";
            if (! in_array($plan, $existingPlans, true)) {
                ActionItem::query()->create([
                    'evaluation_id' => $evaluation->id,
                    'action_plan' => $plan,
                    'assigned_to' => $task->assigned_to,
                    'target_date' => now()->addDays(2)->toDateString(),
                    'status' => $task->status === 'done' ? 'completed' : 'planned',
                ]);
                $existingPlans[] = $plan;
            }
        }

        // Jika tidak ada kendala dan belum ada action item sama sekali
        if (empty($existingPlans)) {
            $defaultAssignee = $project->tasks()->whereNotNull('assigned_to')->first()?->assigned_to;
            ActionItem::query()->create([
                'evaluation_id' => $evaluation->id,
                'action_plan' => "Pemantauan berkala stabilitas modul dan performa sistem {$project->code}",
                'assigned_to' => $defaultAssignee,
                'target_date' => now()->addDays(7)->toDateString(),
                'status' => 'planned',
            ]);
        }
    }
}
