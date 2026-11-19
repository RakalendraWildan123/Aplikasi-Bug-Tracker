<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KanbanController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $projectFilter = $request->query('project_id');
        $typeFilter = $request->query('task_type');

        $query = Task::query()
            ->with(['project', 'module', 'assignee'])
            ->when($projectFilter, function ($q, $projectId) {
                $q->where('project_id', $projectId);
            })
            ->when($typeFilter, function ($q, $taskType) {
                $q->where('task_type', $taskType);
            })
            ->latest('id');

        $tasks = $query->get();

        // Group tasks by the 4 standard Kanban statuses
        $columns = [
            'todo' => [
                'key' => 'todo',
                'title' => 'To Do',
                'description' => 'Tugas belum dikerjakan',
                'badge_class' => 'bg-slate-100 text-slate-700 border-slate-300',
                'dot_class' => 'bg-slate-500',
                'header_border' => 'border-slate-300',
                'tasks' => $tasks->where('status', 'todo')->values(),
            ],
            'in_progress' => [
                'key' => 'in_progress',
                'title' => 'In Progress',
                'description' => 'Sedang dikerjakan',
                'badge_class' => 'bg-sky-50 text-sky-700 border-sky-200',
                'dot_class' => 'bg-sky-500',
                'header_border' => 'border-sky-400',
                'tasks' => $tasks->where('status', 'in_progress')->values(),
            ],
            'review' => [
                'key' => 'review',
                'title' => 'Review',
                'description' => 'Dalam peninjauan',
                'badge_class' => 'bg-amber-50 text-amber-700 border-amber-200',
                'dot_class' => 'bg-amber-500',
                'header_border' => 'border-amber-400',
                'tasks' => $tasks->where('status', 'review')->values(),
            ],
            'done' => [
                'key' => 'done',
                'title' => 'Done',
                'description' => 'Tugas telah selesai',
                'badge_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'dot_class' => 'bg-emerald-500',
                'header_border' => 'border-emerald-500',
                'tasks' => $tasks->where('status', 'done')->values(),
            ],
        ];

        $projects = Project::query()->orderBy('name')->get(['id', 'name', 'code', 'color']);

        if ($request->wantsJson()) {
            return response()->json([
                'columns' => $columns,
                'total_tasks' => $tasks->count(),
            ]);
        }

        return view('kanban.index', [
            'user' => $request->user(),
            'columns' => $columns,
            'projects' => $projects,
            'taskTypes' => Task::TYPES,
            'selectedProject' => $projectFilter,
            'selectedType' => $typeFilter,
        ]);
    }

    public function updateStatus(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(Task::STATUSES)],
        ]);

        $newStatus = $validated['status'];

        $updateData = ['status' => $newStatus];
        if ($newStatus === 'done' && ! $task->completed_at) {
            $updateData['completed_at'] = now();
        } elseif ($newStatus !== 'done') {
            $updateData['completed_at'] = null;
        }

        $task->update($updateData);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status tugas berhasil diperbarui.',
                'task' => $task->fresh(['project', 'module', 'assignee']),
            ]);
        }

        return redirect()->route('kanban.index')->with('status_message', 'Status tugas berhasil diperbarui.');
    }
}
