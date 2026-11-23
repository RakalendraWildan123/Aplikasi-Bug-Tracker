<?php

namespace App\Http\Controllers;

use App\Models\ActionItem;
use App\Models\Bug;
use App\Models\Project;
use App\Models\RepoHistory;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan ringkasan metrik dan aktivitas terpadu pada Dashboard UI.
     */
    public function index(Request $request): View|JsonResponse
    {
        $user = $request->user();

        // 1. Ringkasan Metrik Cepat (4 Kartu Utama)
        $metrics = [
            'active_projects' => Project::where('status', 'active')->count(),
            'total_projects' => Project::count(),
            'tasks_in_progress' => Task::where('status', 'in_progress')->count(),
            'tasks_todo' => Task::where('status', 'todo')->count(),
            'tasks_review' => Task::where('status', 'review')->count(),
            'tasks_done' => Task::where('status', 'done')->count(),
            'total_tasks' => Task::count(),
            'open_bugs' => Bug::whereIn('status', ['open', 'in_progress'])->count(),
            'critical_bugs' => Bug::whereIn('status', ['open', 'in_progress'])->where('severity', 'critical')->count(),
            'high_bugs' => Bug::whereIn('status', ['open', 'in_progress'])->where('severity', 'high')->count(),
            'medium_bugs' => Bug::whereIn('status', ['open', 'in_progress'])->where('severity', 'medium')->count(),
            'low_bugs' => Bug::whereIn('status', ['open', 'in_progress'])->where('severity', 'low')->count(),
            'total_bugs' => Bug::count(),
            'bugs_critical_total' => Bug::where('severity', 'critical')->count(),
            'bugs_high_total' => Bug::where('severity', 'high')->count(),
            'bugs_medium_total' => Bug::where('severity', 'medium')->count(),
            'bugs_low_total' => Bug::where('severity', 'low')->count(),
            'total_releases' => RepoHistory::count(),
            'blocker_tasks' => Task::whereNotNull('blocker_notes')->where('blocker_notes', '!=', '')->count(),
        ];

        // 2. Rilis Kode Terbaru
        $latestRelease = RepoHistory::with('project')->latest('release_date')->first();

        // 3. Tugas Aktif Terkini (Maksimal 5)
        $recentTasks = Task::with(['project', 'assignee'])
            ->whereIn('status', ['in_progress', 'todo', 'review'])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        // 4. Tugas Tertahan Kendala (Blockers)
        $blockerTasks = Task::with(['project', 'assignee'])
            ->whereNotNull('blocker_notes')
            ->where('blocker_notes', '!=', '')
            ->latest('updated_at')
            ->limit(3)
            ->get();

        // 5. Bug Kritis & Mendesak (Maksimal 4)
        $urgentBugs = Bug::with(['project', 'module', 'assignee'])
            ->whereIn('status', ['open', 'in_progress'])
            ->orderByRaw("CASE WHEN severity = 'critical' THEN 1 WHEN severity = 'high' THEN 2 WHEN severity = 'medium' THEN 3 ELSE 4 END")
            ->latest('created_at')
            ->limit(4)
            ->get();

        // 6. Ringkasan Riwayat Rilis Terkini (Maksimal 3)
        $recentReleases = RepoHistory::with('project')
            ->latest('release_date')
            ->limit(3)
            ->get();

        // 7. Rencana Tindak Lanjut Evaluasi Aktif (Maksimal 3)
        $activeActionItems = ActionItem::with(['evaluation.project', 'assignee'])
            ->whereIn('status', ['planned', 'converted_to_task'])
            ->latest('id')
            ->limit(3)
            ->get();

        // 8. Tim Pengembang Aktif (Maksimal 5)
        $activeDevelopers = User::where('role', 'developer')
            ->where('is_active', true)
            ->limit(5)
            ->get();

        // Respon JSON jika diminta secara eksplisit melalui API/AJAX
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'online',
                'name' => config('app.name', 'Aplikasi Bug Tracker'),
                'metrics' => $metrics,
                'latest_release' => $latestRelease,
                'recent_tasks' => $recentTasks,
                'urgent_bugs' => $urgentBugs,
            ]);
        }

        return view('dashboard.index', compact(
            'user',
            'metrics',
            'latestRelease',
            'recentTasks',
            'blockerTasks',
            'urgentBugs',
            'recentReleases',
            'activeActionItems',
            'activeDevelopers'
        ));
    }
}
