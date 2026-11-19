<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->toString();
        $statusFilter = $request->string('status')->toString();

        $query = Project::query()
            ->withCount(['modules', 'tasks', 'bugs'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('repo_url', 'like', "%{$search}%");
                });
            })
            ->when(in_array($statusFilter, Project::STATUSES, true), fn ($q) => $q->where('status', $statusFilter))
            ->orderByRaw("CASE WHEN status = 'active' THEN 0 WHEN status = 'maintenance' THEN 1 ELSE 2 END")
            ->orderBy('name');

        $projects = $query->get();

        $metrics = [
            'total' => Project::query()->count(),
            'active' => Project::query()->where('status', 'active')->count(),
            'maintenance' => Project::query()->where('status', 'maintenance')->count(),
            'archived' => Project::query()->where('status', 'archived')->count(),
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'projects' => $projects,
                'metrics' => $metrics,
                'status_filter' => $statusFilter,
            ]);
        }

        return view('projects.index', [
            'user' => $request->user(),
            'projects' => $projects,
            'metrics' => $metrics,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'statuses' => Project::STATUSES,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:150', 'unique:projects,code'],
            'description' => ['nullable', 'string'],
            'repo_url' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(Project::STATUSES)],
            'color' => ['required', 'string', 'max:50'],
        ], [
            'name.required' => 'Nama proyek wajib diisi.',
            'code.required' => 'Kode proyek wajib diisi.',
            'code.unique' => 'Kode proyek sudah digunakan.',
            'status.required' => 'Status proyek wajib dipilih.',
            'color.required' => 'Warna proyek wajib ditentukan.',
        ]);

        $validated['code'] = Str::upper(trim($validated['code']));
        $project = Project::query()->create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Proyek berhasil dibuat.',
                'project' => $project,
            ], 201);
        }

        return redirect()
            ->route('projects.index')
            ->with('status', "Proyek {$project->name} berhasil ditambahkan.");
    }

    public function show(Project $project): JsonResponse
    {
        $project->loadCount(['modules', 'tasks', 'bugs']);

        return response()->json([
            'project' => $project,
        ]);
    }

    public function update(Request $request, Project $project): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:150', Rule::unique('projects', 'code')->ignore($project->id)],
            'description' => ['nullable', 'string'],
            'repo_url' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(Project::STATUSES)],
            'color' => ['required', 'string', 'max:50'],
        ], [
            'name.required' => 'Nama proyek wajib diisi.',
            'code.required' => 'Kode proyek wajib diisi.',
            'code.unique' => 'Kode proyek sudah digunakan.',
            'status.required' => 'Status proyek wajib dipilih.',
            'color.required' => 'Warna proyek wajib ditentukan.',
        ]);

        $validated['code'] = Str::upper(trim($validated['code']));
        $project->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Proyek berhasil diperbarui.',
                'project' => $project->fresh(),
            ]);
        }

        return redirect()
            ->route('projects.index')
            ->with('status', "Proyek {$project->name} berhasil diperbarui.");
    }

    public function destroy(Request $request, Project $project): JsonResponse|RedirectResponse
    {
        $name = $project->name;
        $project->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Proyek {$name} berhasil dihapus.",
            ]);
        }

        return redirect()
            ->route('projects.index')
            ->with('status', "Proyek {$name} berhasil dihapus.");
    }
}
