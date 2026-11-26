<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use InvalidArgumentException;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    public const TYPES = [
        'feature',
        'enhancement',
        'refactor',
        'maintenance',
    ];

    public const ALL_TYPES = [
        'feature',
        'enhancement',
        'refactor',
        'maintenance',
        'bug',
    ];

    public const STATUSES = [
        'todo',
        'in_progress',
        'review',
        'done',
    ];

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
        'pending_handover',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'target_date' => 'date',
            'completed_at' => 'datetime',
            'handover_logs' => 'array',
            'pending_handover' => 'array',
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
    public function scopeStatus(Builder $query, string $status): Builder
    {
        if (! in_array($status, self::STATUSES, true)) {
            throw new InvalidArgumentException("Invalid task status: {$status}");
        }

        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter tasks by type.
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        if (! in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException("Invalid task type: {$type}");
        }

        return $query->where('task_type', $type);
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

    /**
     * Get the releases referencing this completed task.
     *
     * @return HasMany<RepoHistory, $this>
     */
    public function repoHistories(): HasMany
    {
        return $this->hasMany(RepoHistory::class, 'task_id');
    }


}
