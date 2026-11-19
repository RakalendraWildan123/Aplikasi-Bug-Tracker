<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\Developer;
use App\Models\Module;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BugController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->query('search');
        $projectFilter = $request->query('project_id');
        $severityFilter = $request->query('severity');
        $statusFilter = $request->query('status');
        $developerFilter = $request->query('assigned_to');

        $baseQuery = Bug::query();

        // Metrik statistik bug (bersih, tegas & ringkas)
        $metrics = [
            'total' => (clone $baseQuery)->count(),
            'open' => (clone $baseQuery)->where('status', 'open')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'resolved' => (clone $baseQuery)->where('status', 'resolved')->count(),
            'critical' => (clone $baseQuery)->where('severity', 'critical')->count(),
        ];

        $bugsQuery = Bug::query()
            ->with(['project', 'module', 'creator', 'assignee'])
            ->when($search, function ($q, $term) {
                $q->where(function ($sub) use ($term) {
                    $sub->where('title', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('reported_by', 'like', "%{$term}%")
                        ->orWhere('root_cause', 'like', "%{$term}%")
                        ->orWhere('solution_notes', 'like', "%{$term}%");
                });
            })
            ->when($projectFilter, fn ($q, $val) => $q->where('project_id', $val))
            ->when($severityFilter, fn ($q, $val) => $q->where('severity', $val))
            ->when($statusFilter, fn ($q, $val) => $q->where('status', $val))
            ->when($developerFilter, fn ($q, $val) => $q->where('assigned_to', $val))
            ->latest('id');

        $bugs = $bugsQuery->paginate(15)->withQueryString();

        $projects = Project::query()->orderBy('name')->get(['id', 'name', 'code', 'color']);
        $modules = Module::query()->orderBy('name')->get(['id', 'name', 'project_id']);
        $developers = Developer::query()->orderBy('name')->get(['id', 'name', 'avatar', 'avatar_bg']);

        if ($request->wantsJson()) {
            return response()->json([
                'metrics' => $metrics,
                'bugs' => $bugs,
            ]);
        }

        return view('bugs.index', [
            'user' => $request->user(),
            'bugs' => $bugs,
            'metrics' => $metrics,
            'projects' => $projects,
            'modules' => $modules,
            'developers' => $developers,
            'bugSeverities' => Bug::SEVERITIES,
            'bugStatuses' => Bug::STATUSES,
            'selectedSearch' => $search,
            'selectedProject' => $projectFilter,
            'selectedSeverity' => $severityFilter,
            'selectedStatus' => $statusFilter,
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
            'reported_by' => ['nullable', 'string', 'max:150'],
            'reported_date' => ['nullable', 'date'],
            'severity' => ['required', 'string', Rule::in(Bug::SEVERITIES)],
            'status' => ['required', 'string', Rule::in(Bug::STATUSES)],
            'root_cause' => ['nullable', 'string'],
            'solution_notes' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul bug wajib diisi.',
            'project_id.required' => 'Proyek wajib dipilih.',
            'severity.required' => 'Tingkat keparahan (severity) wajib dipilih.',
            'status.required' => 'Status bug wajib dipilih.',
        ]);

        $validated['created_by'] = $request->user()->id;

        if (empty($validated['reported_date'])) {
            $validated['reported_date'] = now()->toDateString();
        }

        if ($validated['status'] === 'resolved') {
            $validated['resolved_at'] = now();
        }

        $bug = Bug::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Laporan bug baru berhasil ditambahkan.',
                'bug' => $bug->load(['project', 'module', 'assignee']),
            ], 201);
        }

        return redirect()->route('bugs.index')->with('success_message', 'Laporan bug baru berhasil ditambahkan.');
    }

    public function show(Request $request, Bug $bug): JsonResponse
    {
        $bug->load(['project', 'module', 'creator', 'assignee']);

        return response()->json([
            'success' => true,
            'bug' => $bug,
        ]);
    }

    public function update(Request $request, Bug $bug): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['sometimes', 'required', 'exists:projects,id'],
            'module_id' => ['nullable', 'exists:modules,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'reported_by' => ['nullable', 'string', 'max:150'],
            'reported_date' => ['nullable', 'date'],
            'severity' => ['sometimes', 'required', 'string', Rule::in(Bug::SEVERITIES)],
            'status' => ['sometimes', 'required', 'string', Rule::in(Bug::STATUSES)],
            'root_cause' => ['nullable', 'string'],
            'solution_notes' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul bug wajib diisi.',
            'project_id.required' => 'Proyek wajib dipilih.',
            'severity.required' => 'Tingkat keparahan (severity) wajib dipilih.',
            'status.required' => 'Status bug wajib dipilih.',
        ]);

        if (isset($validated['status'])) {
            if ($validated['status'] === 'resolved' && ! $bug->resolved_at) {
                $validated['resolved_at'] = now();
            } elseif ($validated['status'] !== 'resolved') {
                $validated['resolved_at'] = null;
            }
        }

        $bug->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data bug berhasil diperbarui.',
                'bug' => $bug->fresh(['project', 'module', 'assignee']),
            ]);
        }

        return redirect()->route('bugs.index')->with('success_message', 'Data bug berhasil diperbarui.');
    }

    public function destroy(Request $request, Bug $bug): JsonResponse|RedirectResponse
    {
        $bug->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Laporan bug berhasil dihapus.',
            ]);
        }

        return redirect()->route('bugs.index')->with('success_message', 'Laporan bug berhasil dihapus.');
    }

    public function takeover(Request $request, Bug $bug): JsonResponse|RedirectResponse
    {
        $currentUser = $request->user();
        $previousAssignee = $bug->assignee;
        $note = $request->input('note', 'Diambil alih oleh ' . $currentUser->name);

        $logs = $bug->handover_logs ?? [];
        $logs[] = [
            'from_developer_id' => $previousAssignee?->id,
            'from_developer_name' => $previousAssignee?->name ?? 'Belum Ditugaskan',
            'to_developer_id' => $currentUser->id,
            'to_developer_name' => $currentUser->name,
            'project_name' => $bug->project?->name,
            'project_code' => $bug->project?->code,
            'note' => $note,
            'handed_over_at' => now()->toIso8601String(),
        ];

        $bug->update([
            'assigned_to' => $currentUser->id,
            'handover_logs' => $logs,
        ]);

        $freshBug = $bug->fresh(['project', 'module', 'assignee']);

        $message = "Bug '{$freshBug->title}' berhasil Anda ambil alih dari " . ($previousAssignee?->name ?? 'developer sebelumnya') . '.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'bug' => $freshBug,
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
