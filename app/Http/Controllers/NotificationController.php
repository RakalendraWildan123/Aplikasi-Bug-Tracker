<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\RepoHistory;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Dapatkan daftar notifikasi pengguna saat ini berdasarkan aktivitas riil database.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $readIds = $request->session()->get('read_notifications', []);
        $deletedIds = $request->session()->get('deleted_notifications', []);

        $notifications = [];

        // 1. Notifikasi Tugas Aktif / Terbaru
        $tasks = Task::with('project')
            ->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                    ->orWhereNull('assigned_to')
                    ->orWhereNotNull('blocker_notes');
            })
            ->latest('updated_at')
            ->limit(5)
            ->get();

        foreach ($tasks as $task) {
            $id = 'task-' . $task->id;
            if (in_array($id, $deletedIds, true)) {
                continue;
            }

            $isAssignedToMe = (int)$task->assigned_to === (int)$user->id;
            $hasBlocker = ! empty($task->blocker_notes);

            if ($hasBlocker) {
                $title = 'Kendala Tugas Dilaporkan';
                $message = 'Tugas "' . $task->title . '" mengalami kendala: ' . $task->blocker_notes;
                $type = 'blocker';
            } elseif ($isAssignedToMe) {
                $title = 'Penugasan Tugas: ' . ucfirst($task->status);
                $message = 'Anda ditugaskan pada tugas "' . $task->title . '" [' . ($task->project?->code ?? 'PROYEK') . '].';
                $type = 'task';
            } else {
                $title = 'Tugas Tersedia untuk Dikerjakan';
                $message = 'Tugas baru "' . $task->title . '" belum ditugaskan kepada siapapun.';
                $type = 'task';
            }

            $notifications[] = [
                'id' => $id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'project_code' => $task->project?->code ?? 'PROYEK',
                'time_ago' => $task->updated_at ? $task->updated_at->diffForHumans() : 'Baru saja',
                'timestamp' => $task->updated_at ? $task->updated_at->toIso8601String() : now()->toIso8601String(),
                'is_read' => in_array($id, $readIds, true),
                'link' => route('kanban.index'),
            ];
        }

        // 2. Notifikasi Temuan Bug Terbaru
        $bugs = Bug::with(['project', 'module'])
            ->latest('created_at')
            ->limit(4)
            ->get();

        foreach ($bugs as $bug) {
            $id = 'bug-' . $bug->id;
            if (in_array($id, $deletedIds, true)) {
                continue;
            }

            $severityLabels = ['critical' => 'Kritis', 'high' => 'Tinggi', 'medium' => 'Sedang', 'low' => 'Rendah'];
            $sev = $severityLabels[$bug->severity] ?? ucfirst($bug->severity);

            $notifications[] = [
                'id' => $id,
                'type' => 'bug',
                'title' => 'Temuan Bug (' . $sev . ')',
                'message' => 'Bug dilaporkan: "' . $bug->title . '" pada modul ' . ($bug->module?->name ?? 'Sistem') . '.',
                'project_code' => $bug->project?->code ?? 'BUG',
                'time_ago' => $bug->created_at ? $bug->created_at->diffForHumans() : 'Baru saja',
                'timestamp' => $bug->created_at ? $bug->created_at->toIso8601String() : now()->toIso8601String(),
                'is_read' => in_array($id, $readIds, true),
                'link' => route('bugs.index'),
            ];
        }

        // 3. Notifikasi Riwayat Rilis Modul Terbaru
        $releases = RepoHistory::with('project')
            ->latest('release_date')
            ->limit(3)
            ->get();

        foreach ($releases as $rel) {
            $id = 'release-' . $rel->id;
            if (in_array($id, $deletedIds, true)) {
                continue;
            }

            $notifications[] = [
                'id' => $id,
                'type' => 'release',
                'title' => 'Rilis Versi: ' . ($rel->release_tags ?? 'v1.0'),
                'message' => 'Rilis proyek ' . ($rel->project?->name ?? 'Sistem') . ' [' . ($rel->project?->code ?? 'REPO') . '] telah dipublikasikan.',
                'project_code' => $rel->project?->code ?? 'RILIS',
                'time_ago' => $rel->release_date ? $rel->release_date->diffForHumans() : 'Baru saja',
                'timestamp' => $rel->release_date ? $rel->release_date->toIso8601String() : now()->toIso8601String(),
                'is_read' => in_array($id, $readIds, true),
                'link' => route('releases.index'),
            ];
        }

        // Urutkan berdasarkan waktu terbaru
        usort($notifications, function ($a, $b) {
            return strcmp($b['timestamp'], $a['timestamp']);
        });

        // Batasi maksimal 8 notifikasi aktif
        $notifications = array_slice($notifications, 0, 8);

        $unreadCount = count(array_filter($notifications, fn ($n) => ! $n['is_read']));

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'total_count' => count($notifications),
        ]);
    }

    /**
     * Tandai sebuah notifikasi spesifik sudah dibaca.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $readIds = $request->session()->get('read_notifications', []);
        if (! in_array($id, $readIds, true)) {
            $readIds[] = $id;
            $request->session()->put('read_notifications', $readIds);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca.',
            'id' => $id,
        ]);
    }

    /**
     * Tandai semua notifikasi saat ini sudah dibaca.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $notificationIds = $request->input('ids', []);
        $readIds = $request->session()->get('read_notifications', []);

        if (empty($notificationIds)) {
            // Ambil semua id default jika tidak dikirim dari klien
            $taskIds = Task::latest('updated_at')->limit(10)->pluck('id')->map(fn ($id) => 'task-' . $id)->all();
            $bugIds = Bug::latest('created_at')->limit(10)->pluck('id')->map(fn ($id) => 'bug-' . $id)->all();
            $relIds = RepoHistory::latest('release_date')->limit(10)->pluck('id')->map(fn ($id) => 'release-' . $id)->all();
            $notificationIds = array_merge($taskIds, $bugIds, $relIds);
        }

        $readIds = array_unique(array_merge($readIds, $notificationIds));
        $request->session()->put('read_notifications', $readIds);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil ditandai sudah dibaca.',
        ]);
    }

    /**
     * Hapus sebuah notifikasi dari daftar tampilan.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $deletedIds = $request->session()->get('deleted_notifications', []);
        if (! in_array($id, $deletedIds, true)) {
            $deletedIds[] = $id;
            $request->session()->put('deleted_notifications', $deletedIds);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus.',
            'id' => $id,
        ]);
    }

    /**
     * Bersihkan semua notifikasi.
     */
    public function clearAll(Request $request): JsonResponse
    {
        $allIds = $request->input('ids', []);
        $deletedIds = $request->session()->get('deleted_notifications', []);

        $deletedIds = array_unique(array_merge($deletedIds, $allIds));
        $request->session()->put('deleted_notifications', $deletedIds);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil dibersihkan.',
        ]);
    }
}
