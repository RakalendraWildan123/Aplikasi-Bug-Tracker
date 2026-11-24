<?php

namespace App\Http\Controllers;

use App\Models\ActionItem;
use App\Models\AppEvaluation;
use App\Models\Developer;
use App\Models\Project;
use App\Models\Task;
use App\Services\AppEvaluationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppEvaluationController extends Controller
{
    public function index(Request $request, AppEvaluationService $evaluationService): View|JsonResponse
    {
        $currentPeriod = AppEvaluationService::currentPeriod();

        // Sinkronkan evaluasi proyek otomatis (dibatasi interval 15 detik agar performa GET request instan < 0.1s)
        if (! app()->runningUnitTests()) {
            $cacheKey = "auto_eval_sync_{$currentPeriod}";
            if (! cache()->has($cacheKey) || $request->boolean('auto_sync')) {
                $evaluationService->generateAll($currentPeriod);
                cache()->put($cacheKey, true, now()->addSeconds(15));
            }
        } elseif ($request->boolean('auto_sync')) {
            $evaluationService->generateAll($currentPeriod);
        }

        $search = $request->string('search')->toString();
        $projectId = $request->string('project_id')->toString();
        $period = $request->string('period')->toString();

        $query = AppEvaluation::query()
            ->with(['project', 'actionItems.assignee', 'actionItems.convertedTask'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('period_review', 'like', "%{$search}%")
                        ->orWhere('operational_issues', 'like', "%{$search}%")
                        ->orWhere('user_feedback', 'like', "%{$search}%")
                        ->orWhereHas('project', function ($p) use ($search) {
                            $p->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('actionItems', function ($a) use ($search) {
                            $a->where('action_plan', 'like', "%{$search}%");
                        });
                });
            })
            ->when($projectId !== '', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            })
            ->when($period !== '', function ($q) use ($period) {
                $q->where('period_review', $period);
            }, function ($q) {
                // Satukan evaluasi per proyek induk jika tidak memfilter periode spesifik
                $latestIds = AppEvaluation::query()
                    ->selectRaw('MAX(id) as id')
                    ->groupBy('project_id')
                    ->pluck('id');

                $q->whereIn('id', $latestIds);
            })
            ->orderByDesc('evaluation_date')
            ->orderByDesc('id');

        $evaluations = $query->get();
        $projects = Project::query()->orderBy('name')->get();
        $developers = Developer::query()->orderBy('name')->get(['id', 'name', 'avatar', 'avatar_bg']);
        $periods = AppEvaluation::query()->select('period_review')->distinct()->orderBy('period_review')->pluck('period_review');

        $metrics = [
            'total' => AppEvaluation::query()->distinct('project_id')->count('project_id'),
            'projects_evaluated' => AppEvaluation::query()->distinct('project_id')->count('project_id'),
            'total_action_items' => ActionItem::query()->count(),
            'issues_recorded' => AppEvaluation::query()
                ->whereIn('id', AppEvaluation::query()->selectRaw('MAX(id)')->groupBy('project_id'))
                ->whereNotNull('operational_issues')
                ->where('operational_issues', '!=', '')
                ->count(),
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'evaluations' => $evaluations,
                'metrics' => $metrics,
                'project_id_filter' => $projectId,
                'period_filter' => $period,
            ]);
        }

        return view('evaluations.index', [
            'user' => $request->user(),
            'evaluations' => $evaluations,
            'projects' => $projects,
            'developers' => $developers,
            'periods' => $periods,
            'metrics' => $metrics,
            'search' => $search,
            'projectIdFilter' => $projectId,
            'periodFilter' => $period,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'evaluation_date' => ['required', 'date'],
            'period_review' => [
                'required',
                'string',
                'max:100',
                Rule::unique('app_evaluations')->where('project_id', $request->input('project_id')),
            ],
            'operational_issues' => ['nullable', 'string'],
            'user_feedback' => ['nullable', 'string'],
            'initial_action_plan' => ['nullable', 'string'],
            'initial_assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'initial_target_date' => ['nullable', 'date'],
        ], [
            'project_id.required' => 'Proyek wajib dipilih.',
            'project_id.exists' => 'Proyek yang dipilih tidak valid.',
            'evaluation_date.required' => 'Tanggal evaluasi wajib diisi.',
            'evaluation_date.date' => 'Format tanggal evaluasi tidak valid.',
            'period_review.required' => 'Periode evaluasi wajib diisi.',
            'period_review.max' => 'Periode evaluasi maksimal 100 karakter.',
            'period_review.unique' => 'Evaluasi untuk periode ini sudah pernah dicatat pada proyek terpilih.',
        ]);

        $evaluation = AppEvaluation::query()->create([
            'project_id' => $validated['project_id'],
            'evaluation_date' => $validated['evaluation_date'],
            'period_review' => trim($validated['period_review']),
            'operational_issues' => ! empty($validated['operational_issues']) ? trim($validated['operational_issues']) : null,
            'user_feedback' => ! empty($validated['user_feedback']) ? trim($validated['user_feedback']) : null,
        ]);

        if (! empty($validated['initial_action_plan'])) {
            ActionItem::query()->create([
                'evaluation_id' => $evaluation->id,
                'action_plan' => trim($validated['initial_action_plan']),
                'assigned_to' => $validated['initial_assigned_to'] ?? null,
                'target_date' => $validated['initial_target_date'] ?? null,
                'status' => 'planned',
            ]);
        }

        $evaluation->load(['project', 'actionItems.assignee']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Evaluasi aplikasi berhasil ditambahkan.',
                'evaluation' => $evaluation,
            ], 201);
        }

        return redirect()
            ->route('evaluations.index')
            ->with('status', "Evaluasi aplikasi periode {$evaluation->period_review} berhasil ditambahkan.");
    }

    public function show(AppEvaluation $evaluation): JsonResponse
    {
        $evaluation->load(['project', 'actionItems.assignee', 'actionItems.convertedTask']);

        return response()->json([
            'evaluation' => $evaluation,
        ]);
    }

    public function update(Request $request, AppEvaluation $evaluation): JsonResponse|RedirectResponse
    {
        $projectId = $request->input('project_id') ?? $evaluation->project_id;

        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'evaluation_date' => ['required', 'date'],
            'period_review' => [
                'required',
                'string',
                'max:100',
                Rule::unique('app_evaluations')
                    ->where('project_id', $projectId)
                    ->ignore($evaluation->id),
            ],
            'operational_issues' => ['nullable', 'string'],
            'user_feedback' => ['nullable', 'string'],
        ], [
            'project_id.required' => 'Proyek wajib dipilih.',
            'project_id.exists' => 'Proyek yang dipilih tidak valid.',
            'evaluation_date.required' => 'Tanggal evaluasi wajib diisi.',
            'period_review.required' => 'Periode evaluasi wajib diisi.',
            'period_review.unique' => 'Evaluasi untuk periode ini sudah pernah dicatat pada proyek terpilih.',
        ]);

        $evaluation->update([
            'project_id' => $validated['project_id'],
            'evaluation_date' => $validated['evaluation_date'],
            'period_review' => trim($validated['period_review']),
            'operational_issues' => ! empty($validated['operational_issues']) ? trim($validated['operational_issues']) : null,
            'user_feedback' => ! empty($validated['user_feedback']) ? trim($validated['user_feedback']) : null,
        ]);

        $evaluation->load(['project', 'actionItems.assignee']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Evaluasi aplikasi berhasil diperbarui.',
                'evaluation' => $evaluation,
            ]);
        }

        return redirect()
            ->route('evaluations.index')
            ->with('status', "Evaluasi aplikasi periode {$evaluation->period_review} berhasil diperbarui.");
    }

    public function destroy(Request $request, AppEvaluation $evaluation): JsonResponse|RedirectResponse
    {
        $period = $evaluation->period_review;
        $evaluation->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Evaluasi aplikasi periode {$period} berhasil dihapus.",
            ]);
        }

        return redirect()
            ->route('evaluations.index')
            ->with('status', "Evaluasi aplikasi periode {$period} berhasil dihapus.");
    }

    public function storeActionItem(Request $request, AppEvaluation $evaluation): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'action_plan' => ['required', 'string'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'target_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', Rule::in(ActionItem::STATUSES)],
        ], [
            'action_plan.required' => 'Rencana tindakan (action plan) wajib diisi.',
            'assigned_to.exists' => 'Pengembang yang dipilih tidak valid.',
            'target_date.date' => 'Format target tanggal tidak valid.',
        ]);

        $actionItem = ActionItem::query()->create([
            'evaluation_id' => $evaluation->id,
            'action_plan' => trim($validated['action_plan']),
            'assigned_to' => $validated['assigned_to'] ?? null,
            'target_date' => $validated['target_date'] ?? null,
            'status' => $validated['status'] ?? 'planned',
        ]);

        $actionItem->load('assignee');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Rencana tindak lanjut berhasil ditambahkan.',
                'action_item' => $actionItem,
            ], 201);
        }

        return redirect()
            ->route('evaluations.index')
            ->with('status', 'Rencana tindak lanjut baru berhasil ditambahkan.');
    }

    public function updateActionItemStatus(Request $request, ActionItem $actionItem): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(ActionItem::STATUSES)],
        ], [
            'status.required' => 'Status tindak lanjut wajib dipilih.',
            'status.in' => 'Status tindak lanjut tidak valid.',
        ]);

        $actionItem->update([
            'status' => $validated['status'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Status tindak lanjut berhasil diperbarui.',
                'action_item' => $actionItem,
            ]);
        }

        return redirect()
            ->route('evaluations.index')
            ->with('status', 'Status rencana tindak lanjut berhasil diperbarui.');
    }

    public function destroyActionItem(Request $request, ActionItem $actionItem): JsonResponse|RedirectResponse
    {
        $actionItem->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Rencana tindak lanjut berhasil dihapus.',
            ]);
        }

        return redirect()
            ->route('evaluations.index')
            ->with('status', 'Rencana tindak lanjut berhasil dihapus.');
    }

    /**
     * Konversi 1-Klik Action Item evaluasi menjadi Task baru.
     */
    public function convertToTask(Request $request, ActionItem $actionItem): JsonResponse|RedirectResponse
    {
        if ($actionItem->status === 'converted_to_task' && $actionItem->converted_task_id) {
            $message = 'Action item ini sudah dikonversi menjadi tugas sebelumnya.';
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }
            return redirect()->back()->with('error_message', $message);
        }

        $evaluation = $actionItem->evaluation()->with('project')->first();
        if (! $evaluation || ! $evaluation->project) {
            $message = 'Data proyek evaluasi tidak ditemukan.';
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 404);
            }
            return redirect()->back()->with('error_message', $message);
        }

        $user = $request->user();
        $title = $actionItem->action_plan;
        if (mb_strlen($title) > 250) {
            $title = mb_substr($title, 0, 247) . '...';
        }

        // Buat Task baru di database
        $task = Task::query()->create([
            'project_id' => $evaluation->project_id,
            'module_id' => null,
            'created_by' => $user?->id,
            'assigned_to' => $actionItem->assigned_to,
            'title' => $title,
            'description' => "Tindak lanjut evaluasi aplikasi periode {$evaluation->period_review} untuk proyek {$evaluation->project->name}.\n\nRencana tindakan: {$actionItem->action_plan}",
            'task_type' => 'enhancement',
            'status' => 'todo',
            'start_date' => now()->toDateString(),
            'target_date' => $actionItem->target_date ? $actionItem->target_date->toDateString() : now()->addDays(7)->toDateString(),
        ]);

        // Perbarui status ActionItem dan simpan foreign key converted_task_id
        $actionItem->update([
            'status' => 'converted_to_task',
            'converted_task_id' => $task->id,
        ]);

        $message = "Action item berhasil dikonversi menjadi tugas '{$task->title}'.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'task' => $task->fresh(['project', 'assignee']),
                'action_item' => $actionItem->fresh(['assignee', 'convertedTask']),
            ], 201);
        }

        return redirect()->route('evaluations.index')->with('status', $message);
    }

    /**
     * Sinkronkan evaluasi otomatis untuk semua proyek.
     */
    public function autoSync(Request $request, AppEvaluationService $evaluationService): JsonResponse|RedirectResponse
    {
        $period = $request->input('period') ?: AppEvaluationService::currentPeriod();
        $count = $evaluationService->generateAll($period);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Evaluasi aplikasi berhasil disinkronkan secara otomatis untuk seluruh proyek ({$count} proyek).",
                'synced_count' => $count,
                'period' => $period,
            ]);
        }

        return redirect()
            ->route('evaluations.index')
            ->with('status', "Evaluasi aplikasi berhasil disinkronkan secara otomatis dari data proyek, tugas, dan bug terkini ({$count} proyek).");
    }

    /**
     * Sinkronkan ulang evaluasi untuk proyek tertentu dari data terkini.
     */
    public function syncSingle(Request $request, AppEvaluation $evaluation, AppEvaluationService $evaluationService): JsonResponse|RedirectResponse
    {
        $project = $evaluation->project;
        if (! $project) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Proyek tidak ditemukan.'], 404);
            }

            return redirect()->route('evaluations.index')->withErrors(['Proyek evaluasi tidak ditemukan.']);
        }

        $updated = $evaluationService->generateForProject($project, $evaluation->period_review);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Evaluasi proyek [{$project->code}] {$project->name} berhasil diperbarui dari data real-time.",
                'evaluation' => $updated->load(['project', 'actionItems.assignee']),
            ]);
        }

        return redirect()
            ->route('evaluations.index')
            ->with('status', "Evaluasi proyek [{$project->code}] {$project->name} berhasil disinkronkan dari data real-time.");
    }
}
