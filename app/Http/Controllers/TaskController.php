<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use App\Models\Module;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->query('search');
        $projectFilter = $request->query('project_id');
        $statusFilter = $request->query('status');
        $typeFilter = $request->query('task_type');
        $developerFilter = $request->query('assigned_to');
        $activeTab = $request->query('tab', 'all');
        $currentUser = $request->user();

        $baseQuery = Task::query();

        // 1. Metrik Statistik Tugas
        $metrics = [
            'total' => (clone $baseQuery)->count(),
            'todo' => (clone $baseQuery)->where('status', 'todo')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'review' => (clone $baseQuery)->where('status', 'review')->count(),
            'done' => (clone $baseQuery)->where('status', 'done')->count(),
        ];

        // 2. Hitung Jumlah untuk Tab Navigasi: Semua Task vs Task Saya
        $allTasksCount = (clone $baseQuery)->count();
        $myTasksCount = (clone $baseQuery)->where('assigned_to', $currentUser->id)->count();

        // 3. Query Koleksi Tasks (Pencatatan bug tidak otomatis menambahkan task baru)
        $tasksQuery = Task::query()
            ->with(['project', 'module', 'creator', 'assignee'])
            ->when($activeTab === 'my_tasks', function ($q) use ($currentUser) {
                $q->where('assigned_to', $currentUser->id);
            })
            ->when($search, function ($q, $term) {
                $q->where(function ($sub) use ($term) {
                    $sub->where('title', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('blocker_notes', 'like', "%{$term}%");
                });
            })
            ->when($projectFilter, fn ($q, $val) => $q->where('project_id', $val))
            ->when($statusFilter, fn ($q, $val) => $q->where('status', $val))
            ->when($typeFilter, fn ($q, $val) => $q->where('task_type', $val))
            ->when($developerFilter, fn ($q, $val) => $q->where('assigned_to', $val))
            ->latest('id');

        $tasks = $tasksQuery->paginate(15)->withQueryString();

        $projects = Project::query()->orderBy('name')->get(['id', 'name', 'code', 'color']);
        $modules = Module::query()->orderBy('name')->get(['id', 'name', 'project_id']);
        $developers = Developer::query()->orderBy('name')->get(['id', 'name', 'avatar', 'avatar_bg']);

        if ($request->wantsJson()) {
            return response()->json([
                'metrics' => $metrics,
                'tasks' => $tasks,
                'active_tab' => $activeTab,
                'all_tasks_count' => $allTasksCount,
                'my_tasks_count' => $myTasksCount,
            ]);
        }

        return view('tasks.index', [
            'user' => $currentUser,
            'tasks' => $tasks,
            'metrics' => $metrics,
            'projects' => $projects,
            'modules' => $modules,
            'developers' => $developers,
            'taskTypes' => Task::TYPES,
            'taskStatuses' => Task::STATUSES,
            'selectedSearch' => $search,
            'selectedProject' => $projectFilter,
            'selectedStatus' => $statusFilter,
            'selectedType' => $typeFilter,
            'selectedDeveloper' => $developerFilter,
            'activeTab' => $activeTab,
            'allTasksCount' => $allTasksCount,
            'myTasksCount' => $myTasksCount,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->input('task_type') === 'bug') {
            $request->merge(['task_type' => 'maintenance']);
        }

        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'module_id' => ['nullable', 'exists:modules,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'task_type' => ['required', 'string', Rule::in(Task::TYPES)],
            'status' => ['required', 'string', Rule::in(Task::STATUSES)],
            'start_date' => ['nullable', 'date'],
            'target_date' => ['nullable', 'date'],
            'blocker_notes' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul tugas wajib diisi.',
            'title.max' => 'Judul tugas tidak boleh melebihi 255 karakter.',
            'project_id.required' => 'Proyek wajib dipilih.',
            'project_id.exists' => 'Proyek yang dipilih tidak terdaftar.',
            'module_id.exists' => 'Modul yang dipilih tidak terdaftar.',
            'assigned_to.exists' => 'Pengembang yang dipilih tidak terdaftar.',
            'task_type.required' => 'Tipe tugas wajib dipilih.',
            'task_type.in' => 'Tipe tugas yang dipilih tidak valid.',
            'status.required' => 'Status tugas wajib dipilih.',
            'status.in' => 'Status tugas yang dipilih tidak valid.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'target_date.date' => 'Format target deadline tidak valid.',
        ]);

        $user = $request->user() ?? Auth::user();
        $validated['created_by'] = $user?->id ?? Auth::id();

        if ($user && $user->isAdmin()) {
            if (empty($validated['assigned_to'])) {
                $validated['assigned_to'] = null;
            }
            if (empty($validated['status'])) {
                $validated['status'] = 'todo';
            }
        } else {
            if (empty($validated['assigned_to'])) {
                $validated['assigned_to'] = $validated['created_by'];
            }
        }

        if ($validated['status'] === 'done') {
            $validated['completed_at'] = now();
        }

        $task = Task::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas baru berhasil ditambahkan.',
                'task' => $task->load(['project', 'module', 'assignee']),
            ], 201);
        }

        return redirect()->route('tasks.index')->with('success_message', 'Tugas baru berhasil ditambahkan.');
    }

    public function update(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        if ($request->input('task_type') === 'bug') {
            $request->merge(['task_type' => 'maintenance']);
        }

        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'module_id' => ['nullable', 'exists:modules,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'task_type' => ['required', 'string', Rule::in(Task::TYPES)],
            'status' => ['required', 'string', Rule::in(Task::STATUSES)],
            'start_date' => ['nullable', 'date'],
            'target_date' => ['nullable', 'date'],
            'blocker_notes' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul tugas wajib diisi.',
            'title.max' => 'Judul tugas tidak boleh melebihi 255 karakter.',
            'project_id.required' => 'Proyek wajib dipilih.',
            'project_id.exists' => 'Proyek yang dipilih tidak terdaftar.',
            'module_id.exists' => 'Modul yang dipilih tidak terdaftar.',
            'assigned_to.exists' => 'Pengembang yang dipilih tidak terdaftar.',
            'task_type.required' => 'Tipe tugas wajib dipilih.',
            'task_type.in' => 'Tipe tugas yang dipilih tidak valid.',
            'status.required' => 'Status tugas wajib dipilih.',
            'status.in' => 'Status tugas yang dipilih tidak valid.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'target_date.date' => 'Format target deadline tidak valid.',
        ]);

        if (! $request->has('assigned_to') || is_null($request->input('assigned_to'))) {
            unset($validated['assigned_to']);
        }

        if ($validated['status'] === 'done' && ! $task->completed_at) {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'done') {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data tugas berhasil diperbarui.',
                'task' => $task->fresh(['project', 'module', 'assignee']),
            ]);
        }

        return redirect()->route('tasks.index')->with('success_message', 'Data tugas berhasil diperbarui.');
    }

    public function destroy(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        // Validasi hak akses: hanya orang yang mengerjakan task tersebut (assigned_to) yang dapat menghapusnya
        if ((int) $task->assigned_to !== (int) $user->id) {
            $assigneeName = $task->assignee?->name ?? 'pengembang lain';
            $errorMessage = $task->assigned_to
                ? "Hanya orang yang mengerjakan task ini ({$assigneeName}) yang dapat menghapusnya."
                : 'Tugas ini belum ditugaskan. Ambil alih tugas terlebih dahulu untuk dapat mengelolanya.';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 403);
            }

            return redirect()->route('tasks.index')->with('error_message', $errorMessage);
        }

        $task->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dihapus.',
            ]);
        }

        return redirect()->route('tasks.index')->with('success_message', 'Tugas berhasil dihapus.');
    }

    public function takeover(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        $currentUser = $request->user();
        $previousAssignee = $task->assignee;
        $note = $request->input('note', 'Diambil alih oleh ' . $currentUser->name);

        $logs = $task->handover_logs ?? [];
        $logs[] = [
            'from_developer_id' => $previousAssignee?->id,
            'from_developer_name' => $previousAssignee?->name ?? 'Belum Ditugaskan',
            'to_developer_id' => $currentUser->id,
            'to_developer_name' => $currentUser->name,
            'project_name' => $task->project?->name,
            'project_code' => $task->project?->code,
            'note' => $note,
            'handed_over_at' => now()->toIso8601String(),
        ];

        $task->update([
            'assigned_to' => $currentUser->id,
            'handover_logs' => $logs,
        ]);

        $freshTask = $task->fresh(['project', 'module', 'assignee']);
        $message = "Tugas '{$freshTask->title}' berhasil Anda ambil alih dari " . ($previousAssignee?->name ?? 'developer sebelumnya') . '.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'task' => $freshTask,
                'user' => [
                    'id' => $currentUser->id,
                    'name' => $currentUser->name,
                    'avatar' => $currentUser->avatar ?? 'AK',
                    'avatar_bg' => $currentUser->avatar_bg ?? '#1E293B',
                    'foto_profil_url' => $currentUser->foto_profil_url,
                ],
            ]);
        }

        return redirect()->back()->with('success_message', $message);
    }
}
