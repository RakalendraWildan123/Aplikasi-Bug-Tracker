<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->toString();
        $projectId = $request->string('project_id')->toString();

        $query = Module::query()
            ->with('project')
            ->withCount(['tasks', 'bugs'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('project', function ($p) use ($search) {
                            $p->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        });
                });
            })
            ->when($projectId !== '', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            })
            ->orderBy('project_id')
            ->orderBy('name');

        $modules = $query->get();
        $projects = Project::query()->orderBy('name')->get();

        $metrics = [
            'total' => Module::query()->count(),
            'with_tasks' => Module::query()->has('tasks')->count(),
            'with_bugs' => Module::query()->has('bugs')->count(),
            'projects_count' => Project::query()->has('modules')->count(),
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'modules' => $modules,
                'metrics' => $metrics,
                'project_id_filter' => $projectId,
            ]);
        }

        return view('modules.index', [
            'user' => $request->user(),
            'modules' => $modules,
            'projects' => $projects,
            'metrics' => $metrics,
            'search' => $search,
            'projectIdFilter' => $projectId,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ], [
            'project_id.required' => 'Proyek wajib dipilih.',
            'project_id.exists' => 'Proyek yang dipilih tidak valid.',
            'name.required' => 'Nama modul wajib diisi.',
            'name.max' => 'Nama modul maksimal 150 karakter.',
        ]);

        $validated['name'] = trim($validated['name']);
        $module = Module::query()->create($validated);
        $module->load('project');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Modul berhasil dibuat.',
                'module' => $module,
            ], 201);
        }

        return redirect()
            ->route('modules.index')
            ->with('status', "Modul {$module->name} berhasil ditambahkan.");
    }

    public function show(Module $module): JsonResponse
    {
        $module->load('project');
        $module->loadCount(['tasks', 'bugs']);

        return response()->json([
            'module' => $module,
        ]);
    }

    public function update(Request $request, Module $module): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ], [
            'project_id.required' => 'Proyek wajib dipilih.',
            'project_id.exists' => 'Proyek yang dipilih tidak valid.',
            'name.required' => 'Nama modul wajib diisi.',
            'name.max' => 'Nama modul maksimal 150 karakter.',
        ]);

        $validated['name'] = trim($validated['name']);
        $module->update($validated);
        $module->load('project');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Modul berhasil diperbarui.',
                'module' => $module->fresh()->load('project'),
            ]);
        }

        return redirect()
            ->route('modules.index')
            ->with('status', "Modul {$module->name} berhasil diperbarui.");
    }

    public function destroy(Request $request, Module $module): JsonResponse|RedirectResponse
    {
        $name = $module->name;
        $module->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Modul {$name} berhasil dihapus.",
            ]);
        }

        return redirect()
            ->route('modules.index')
            ->with('status', "Modul {$name} berhasil dihapus.");
    }
}
