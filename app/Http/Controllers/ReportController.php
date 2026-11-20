<?php

namespace App\Http\Controllers;

use App\Models\AppEvaluation;
use App\Models\Bug;
use App\Models\Developer;
use App\Models\Project;
use App\Models\RepoHistory;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $preset = $request->string('preset', 'all')->toString();
        $projectId = $request->string('project_id')->toString();
        $developerId = $request->string('developer_id')->toString();
        $customStart = $request->input('start_date');
        $customEnd = $request->input('end_date');

        [$startDate, $endDate] = $this->resolveDateRange($preset, $customStart, $customEnd);

        $projects = Project::query()->orderBy('name')->get();
        $developers = Developer::query()->orderBy('name')->get(['id', 'name', 'avatar', 'avatar_bg']);

        // Base queries
        $tasksQuery = Task::query()->with(['project', 'assignee']);
        $bugsQuery = Bug::query()->with(['project', 'assignee', 'module']);
        $releasesQuery = RepoHistory::query()->with(['project']);
        $evaluationsQuery = AppEvaluation::query()->with(['project', 'actionItems']);

        // Apply Date Range (unless preset is 'all')
        if ($preset !== 'all' && $startDate && $endDate) {
            $startDay = $startDate->copy()->startOfDay();
            $endDay = $endDate->copy()->endOfDay();
            $startDateStr = $startDate->format('Y-m-d');
            $endDateStr = $endDate->format('Y-m-d');

            $tasksQuery->where(function ($q) use ($startDay, $endDay, $startDateStr, $endDateStr) {
                $q->whereBetween('created_at', [$startDay, $endDay])
                    ->orWhereBetween('start_date', [$startDateStr, $endDateStr]);
            });

            $bugsQuery->where(function ($q) use ($startDay, $endDay, $startDateStr, $endDateStr) {
                $q->whereBetween('created_at', [$startDay, $endDay])
                    ->orWhereBetween('reported_date', [$startDateStr, $endDateStr]);
            });

            $releasesQuery->whereBetween('release_date', [$startDateStr, $endDateStr]);
            $evaluationsQuery->whereBetween('evaluation_date', [$startDateStr, $endDateStr]);
        }

        // Apply Project Filter
        if ($projectId !== '') {
            $tasksQuery->where('project_id', $projectId);
            $bugsQuery->where('project_id', $projectId);
            $releasesQuery->where('project_id', $projectId);
            $evaluationsQuery->where('project_id', $projectId);
        }

        // Apply Developer Filter
        if ($developerId !== '') {
            $tasksQuery->where('assigned_to', $developerId);
            $bugsQuery->where('assigned_to', $developerId);
        }

        $tasks = $tasksQuery->orderByDesc('id')->get();
        $bugs = $bugsQuery->orderByDesc('id')->get();
        $releases = $releasesQuery->orderByDesc('release_date')->get();
        $evaluations = $evaluationsQuery->orderByDesc('evaluation_date')->get();

        // Calculate Metrics
        $totalTasks = $tasks->count();
        $doneTasks = $tasks->where('status', 'done')->count();
        $inProgressTasks = $tasks->where('status', 'in_progress')->count();
        $reviewTasks = $tasks->where('status', 'review')->count();
        $todoTasks = $tasks->where('status', 'todo')->count();
        $taskRate = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

        $totalBugs = $bugs->count();
        $resolvedBugs = $bugs->whereIn('status', ['resolved', 'closed'])->count();
        $openBugs = $bugs->whereIn('status', ['open', 'reopened', 'in_progress'])->count();
        $bugRate = $totalBugs > 0 ? round(($resolvedBugs / $totalBugs) * 100) : 0;

        $totalReleases = $releases->count();
        $totalEvaluations = $evaluations->count();
        $actionItems = $evaluations->flatMap->actionItems;
        $totalActionItems = $actionItems->count();
        $completedActionItems = $actionItems->where('status', 'completed')->count();

        $metrics = [
            'total_tasks' => $totalTasks,
            'done_tasks' => $doneTasks,
            'in_progress_tasks' => $inProgressTasks,
            'review_tasks' => $reviewTasks,
            'todo_tasks' => $todoTasks,
            'task_rate' => $taskRate,
            'total_bugs' => $totalBugs,
            'resolved_bugs' => $resolvedBugs,
            'open_bugs' => $openBugs,
            'bug_rate' => $bugRate,
            'total_releases' => $totalReleases,
            'total_evaluations' => $totalEvaluations,
            'total_action_items' => $totalActionItems,
            'completed_action_items' => $completedActionItems,
        ];

        // Project Breakdown
        $projectBreakdown = $projects->map(function ($project) use ($tasks, $bugs, $releases) {
            $pTasks = $tasks->where('project_id', $project->id);
            $pDoneTasks = $pTasks->where('status', 'done')->count();
            $pTotalTasks = $pTasks->count();
            $pTaskRate = $pTotalTasks > 0 ? round(($pDoneTasks / $pTotalTasks) * 100) : 0;

            $pBugs = $bugs->where('project_id', $project->id);
            $pResolvedBugs = $pBugs->whereIn('status', ['resolved', 'closed'])->count();
            $pTotalBugs = $pBugs->count();
            $pBugRate = $pTotalBugs > 0 ? round(($pResolvedBugs / $pTotalBugs) * 100) : 0;

            $pReleases = $releases->where('project_id', $project->id)->count();

            return [
                'id' => $project->id,
                'name' => $project->name,
                'code' => $project->code,
                'color' => $project->color ?? '#4F46E5',
                'status' => $project->status,
                'tasks_total' => $pTotalTasks,
                'tasks_done' => $pDoneTasks,
                'tasks_rate' => $pTaskRate,
                'bugs_total' => $pTotalBugs,
                'bugs_resolved' => $pResolvedBugs,
                'bugs_rate' => $pBugRate,
                'releases_total' => $pReleases,
            ];
        });

        if ($request->wantsJson()) {
            return response()->json([
                'metrics' => $metrics,
                'project_breakdown' => $projectBreakdown,
                'preset' => $preset,
                'start_date' => $startDate ? $startDate->format('Y-m-d') : null,
                'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
            ]);
        }

        return view('reports.index', [
            'user' => $request->user(),
            'preset' => $preset,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'projectIdFilter' => $projectId,
            'developerIdFilter' => $developerId,
            'projects' => $projects,
            'developers' => $developers,
            'metrics' => $metrics,
            'projectBreakdown' => $projectBreakdown,
            'tasks' => $tasks,
            'bugs' => $bugs,
            'releases' => $releases,
            'evaluations' => $evaluations,
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $preset = $request->string('preset', 'all')->toString();
        $projectId = $request->string('project_id')->toString();
        $developerId = $request->string('developer_id')->toString();

        [$startDate, $endDate] = $this->resolveDateRange(
            $preset,
            $request->input('start_date'),
            $request->input('end_date')
        );

        $projects = Project::query()->orderBy('name')->get();

        $tasksQuery = Task::query()->with(['project', 'assignee']);
        $bugsQuery = Bug::query()->with(['project', 'assignee', 'module']);

        if ($preset !== 'all' && $startDate && $endDate) {
            $startDay = $startDate->copy()->startOfDay();
            $endDay = $endDate->copy()->endOfDay();
            $startDateStr = $startDate->format('Y-m-d');
            $endDateStr = $endDate->format('Y-m-d');

            $tasksQuery->where(function ($q) use ($startDay, $endDay, $startDateStr, $endDateStr) {
                $q->whereBetween('created_at', [$startDay, $endDay])
                    ->orWhereBetween('start_date', [$startDateStr, $endDateStr]);
            });

            $bugsQuery->where(function ($q) use ($startDay, $endDay, $startDateStr, $endDateStr) {
                $q->whereBetween('created_at', [$startDay, $endDay])
                    ->orWhereBetween('reported_date', [$startDateStr, $endDateStr]);
            });
        }

        if ($projectId !== '') {
            $tasksQuery->where('project_id', $projectId);
            $bugsQuery->where('project_id', $projectId);
        }

        if ($developerId !== '') {
            $tasksQuery->where('assigned_to', $developerId);
            $bugsQuery->where('assigned_to', $developerId);
        }

        $tasks = $tasksQuery->orderByDesc('id')->get();
        $bugs = $bugsQuery->orderByDesc('id')->get();

        $dateSuffix = $startDate && $endDate
            ? $startDate->format('Ymd') . '-' . $endDate->format('Ymd')
            : 'semua-periode';

        $filename = 'laporan-periodik-' . $dateSuffix . '.csv';

        return response()->streamDownload(function () use ($startDate, $endDate, $projects, $tasks, $bugs) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($handle, ['LAPORAN PERIODIK MONITORING IT DEVELOPER']);
            fputcsv($handle, ['Tanggal Cetak', now()->isoFormat('D MMMM Y, HH:mm')]);
            if ($startDate && $endDate) {
                fputcsv($handle, ['Rentang Periode', $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y')]);
            } else {
                fputcsv($handle, ['Rentang Periode', 'Semua Data Terdaftar']);
            }
            fputcsv($handle, []);

            // Rekapitulasi Proyek
            fputcsv($handle, ['REKAPITULASI KINERJA PROYEK']);
            fputcsv($handle, ['Kode Proyek', 'Nama Proyek', 'Total Tugas', 'Tugas Selesai', '% Penyelesaian', 'Total Bug', 'Bug Tertangani', '% Resolusi']);
            foreach ($projects as $proj) {
                $pTasks = $tasks->where('project_id', $proj->id);
                $pDone = $pTasks->where('status', 'done')->count();
                $pBugs = $bugs->where('project_id', $proj->id);
                $pResolved = $pBugs->whereIn('status', ['resolved', 'closed'])->count();

                fputcsv($handle, [
                    $proj->code,
                    $proj->name,
                    $pTasks->count(),
                    $pDone,
                    $pTasks->count() > 0 ? round(($pDone / $pTasks->count()) * 100) . '%' : '0%',
                    $pBugs->count(),
                    $pResolved,
                    $pBugs->count() > 0 ? round(($pResolved / $pBugs->count()) * 100) . '%' : '0%',
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['RINCIAN TUGAS PENGEMBANGAN']);
            fputcsv($handle, ['ID', 'Proyek', 'Judul Tugas', 'Tipe', 'PIC', 'Status', 'Target Selesai']);
            foreach ($tasks as $t) {
                fputcsv($handle, [
                    $t->id,
                    $t->project ? '[' . $t->project->code . '] ' . $t->project->name : '-',
                    $t->title,
                    strtoupper($t->task_type),
                    $t->assignee ? $t->assignee->name : 'Belum Ditugaskan',
                    $t->status,
                    $t->target_date ? $t->target_date->format('Y-m-d') : '-',
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['RINCIAN TEMUAN BUG']);
            fputcsv($handle, ['ID', 'Proyek', 'Judul Bug', 'Keparahan', 'PIC', 'Status', 'Solusi Perbaikan']);
            foreach ($bugs as $b) {
                fputcsv($handle, [
                    $b->id,
                    $b->project ? '[' . $b->project->code . '] ' . $b->project->name : '-',
                    $b->title,
                    strtoupper($b->severity),
                    $b->assignee ? $b->assignee->name : 'Belum Ditugaskan',
                    $b->status,
                    $b->solution_notes ? 'Sudah Ada Solusi' : 'Belum Ada Solusi',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function resolveDateRange(string $preset, ?string $start, ?string $end): array
    {
        if ($preset === 'custom' && $start && $end) {
            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);
            if ($startDate->gt($endDate)) {
                [$startDate, $endDate] = [$endDate, $startDate];
            }
            return [$startDate, $endDate];
        }

        return match ($preset) {
            '7d' => [now()->subDays(7), now()],
            '30d' => [now()->subDays(30), now()],
            'this_month' => [now()->startOfMonth(), now()],
            'this_quarter' => [now()->startOfQuarter(), now()],
            'this_year' => [now()->startOfYear(), now()],
            'all' => [null, null],
            default => [null, null],
        };
    }
}
