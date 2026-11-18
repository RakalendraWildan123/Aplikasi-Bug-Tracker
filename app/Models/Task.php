<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'module_id',
        'created_by',
        'assigned_to',
        'title',
        'description',
        'task_type',
        'status',
        'start_date',
        'target_date',
        'completed_at',
        'blocker_notes',
        'handover_logs',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'task_type' => TaskType::class,
            'status' => TaskStatus::class,
            'start_date' => 'date',
            'target_date' => 'date',
            'completed_at' => 'datetime',
            'handover_logs' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo<Module, $this>
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * @return BelongsTo<Developer, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Developer::class, 'created_by');
    }

    /**
     * @return BelongsTo<Developer, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Developer::class, 'assigned_to');
    }

    /**
     * Scope a query to filter tasks by status.
     */
    public function scopeStatus(Builder $query, TaskStatus|string $status): Builder
    {
        $enum = $status instanceof TaskStatus ? $status : TaskStatus::from($status);

        return $query->where('status', $enum->value);
    }

    /**
     * Scope a query to filter tasks by type.
     */
    public function scopeType(Builder $query, TaskType|string $type): Builder
    {
        $enum = $type instanceof TaskType ? $type : TaskType::from($type);

        return $query->where('task_type', $enum->value);
    }

    /**
     * Scope a query to filter tasks for a specific project.
     */
    public function scopeForProject(Builder $query, int $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope a query to filter tasks assigned to a specific developer.
     */
    public function scopeAssignedTo(Builder $query, int $developerId): Builder
    {
        return $query->where('assigned_to', $developerId);
    }

    /**
     * Get the source action item converted into this task, if any.
     *
     * @return HasOne<ActionItem, $this>
     */
    public function sourceActionItem(): HasOne
    {
        return $this->hasOne(ActionItem::class, 'converted_task_id');
    }
}
