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

        $baseQuery = Task::query();

        // Metrik statistik tugas (bersih & ringkas)
        $metrics = [
            'total' => (clone $baseQuery)->count(),
            'todo' => (clone $baseQuery)->where('status', 'todo')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'review' => (clone $baseQuery)->where('status', 'review')->count(),
            'done' => (clone $baseQuery)->where('status', 'done')->count(),
        ];

        $tasksQuery = Task::query()
            ->with(['project', 'module', 'creator', 'assignee'])
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
            ]);
        }

        return view('tasks.index', [
            'user' => $request->user(),
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
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
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
            'project_id.required' => 'Proyek wajib dipilih.',
            'task_type.required' => 'Tipe tugas wajib dipilih.',
            'status.required' => 'Status tugas wajib dipilih.',
        ]);

        $validated['created_by'] = $request->user()->id;

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
            'project_id.required' => 'Proyek wajib dipilih.',
            'task_type.required' => 'Tipe tugas wajib dipilih.',
            'status.required' => 'Status tugas wajib dipilih.',
        ]);

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
        $task->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dihapus.',
            ]);
        }

        return redirect()->route('tasks.index')->with('success_message', 'Tugas berhasil dihapus.');
    }
}
