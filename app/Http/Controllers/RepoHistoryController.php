<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\RepoHistory;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RepoHistoryController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->toString();
        $projectId = $request->string('project_id')->toString();
        $branch = $request->string('branch')->toString();

        $query = RepoHistory::query()
            ->with(['project.modules', 'task'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('release_tags', 'like', "%{$search}%")
                        ->orWhere('branch_name', 'like', "%{$search}%")
                        ->orWhere('commit_hash', 'like', "%{$search}%")
                        ->orWhere('summary_changes', 'like', "%{$search}%")
                        ->orWhereHas('task', function ($t) use ($search) {
                            $t->where('title', 'like', "%{$search}%");
                        })
                        ->orWhereHas('project', function ($p) use ($search) {
                            $p->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%")
                                ->orWhere('repo_url', 'like', "%{$search}%")
                                ->orWhereHas('modules', function ($m) use ($search) {
                                    $m->where('name', 'like', "%{$search}%");
                                });
                        });
                });
            })
            ->when($projectId !== '', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            })
            ->when($branch !== '', function ($q) use ($branch) {
                $q->where('branch_name', $branch);
            })
            ->orderByDesc('release_date')
            ->orderByDesc('id');

        $releases = $query->get();
        $projects = Project::query()->with('modules')->orderBy('name')->get();
        $branches = RepoHistory::query()->select('branch_name')->distinct()->orderBy('branch_name')->pluck('branch_name');
        $completedTasks = Task::query()
            ->with('project:id,code,name')
            ->where('status', 'done')
            ->orderByDesc('completed_at')
            ->orderByDesc('id')
            ->get(['id', 'project_id', 'title', 'task_type']);

        $metrics = [
            'total' => RepoHistory::query()->count(),
            'tagged' => RepoHistory::query()->whereNotNull('release_tags')->where('release_tags', '!=', '')->count(),
            'projects_released' => RepoHistory::query()->distinct('project_id')->count('project_id'),
            'branches_count' => $branches->count(),
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'releases' => $releases,
                'metrics' => $metrics,
                'project_id_filter' => $projectId,
                'branch_filter' => $branch,
            ]);
        }

        return view('releases.index', [
            'user' => $request->user(),
            'releases' => $releases,
            'projects' => $projects,
            'branches' => $branches,
            'completedTasks' => $completedTasks,
            'metrics' => $metrics,
            'search' => $search,
            'projectIdFilter' => $projectId,
            'branchFilter' => $branch,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'task_id' => [
                'nullable',
                'integer',
                Rule::exists('tasks', 'id')->where('status', 'done'),
            ],
            'release_date' => ['required', 'date'],
            'release_tags' => ['nullable', 'string', 'max:50'],
            'branch_name' => ['required', 'string', 'max:100'],
            'commit_hash' => [
                'required',
                'string',
                'min:7',
                'max:40',
                Rule::unique('repo_histories')->where('project_id', $request->input('project_id')),
            ],
            'summary_changes' => ['nullable', 'string'],
        ], [
            'project_id.required' => 'Proyek wajib dipilih.',
            'project_id.exists' => 'Proyek yang dipilih tidak valid.',
            'release_date.required' => 'Tanggal rilis wajib ditentukan.',
            'release_date.date' => 'Format tanggal rilis tidak valid.',
            'branch_name.required' => 'Nama branch wajib diisi.',
            'commit_hash.required' => 'Commit hash wajib diisi.',
            'commit_hash.min' => 'Commit hash minimal 7 karakter.',
            'commit_hash.unique' => 'Commit hash sudah pernah dicatat untuk proyek ini.',
        ]);

        $validated['branch_name'] = trim($validated['branch_name']);
        $validated['commit_hash'] = Str::lower(trim($validated['commit_hash']));
        $validated['release_tags'] = $validated['release_tags'] ? trim($validated['release_tags']) : null;

        $release = RepoHistory::query()->create($validated);
        $release->load(['project.modules', 'task']);

        $tagLabel = $release->release_tags ? "[{$release->release_tags}] " : '';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Riwayat rilis berhasil dicatat.',
                'release' => $release,
            ], 201);
        }

        return redirect()
            ->route('releases.index')
            ->with('status', "Riwayat rilis {$tagLabel}berhasil ditambahkan.");
    }

    public function show(RepoHistory $release): JsonResponse
    {
        $release->load('project.modules');

        return response()->json([
            'release' => $release,
        ]);
    }

    public function update(Request $request, RepoHistory $release): never
    {
        abort(403, 'Riwayat rilis bersifat permanen dan tidak dapat diubah.');
    }

    public function destroy(Request $request, RepoHistory $release): never
    {
        abort(403, 'Riwayat rilis bersifat permanen dan tidak dapat dihapus.');
    }
}
