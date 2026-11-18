<?php

namespace App\Models;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bug extends Model
{
    /** @use HasFactory<\Database\Factories\BugFactory> */
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
        'reported_by',
        'reported_date',
        'severity',
        'status',
        'root_cause',
        'solution_notes',
        'resolved_at',
        'handover_logs',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'severity' => BugSeverity::class,
            'status' => BugStatus::class,
            'reported_date' => 'date',
            'resolved_at' => 'datetime',
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
     * Scope a query to filter bugs by status.
     */
    public function scopeStatus(Builder $query, BugStatus|string $status): Builder
    {
        $enum = $status instanceof BugStatus ? $status : BugStatus::from($status);

        return $query->where('status', $enum->value);
    }

    /**
     * Scope a query to filter bugs by severity.
     */
    public function scopeSeverity(Builder $query, BugSeverity|string $severity): Builder
    {
        $enum = $severity instanceof BugSeverity ? $severity : BugSeverity::from($severity);

        return $query->where('severity', $enum->value);
    }

    /**
     * Scope a query to only include open bugs.
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', BugStatus::Open->value);
    }

    /**
     * Scope a query to filter bugs for a specific project.
     */
    public function scopeForProject(Builder $query, int $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope a query to filter bugs assigned to a specific developer.
     */
    public function scopeAssignedTo(Builder $query, int $developerId): Builder
    {
        return $query->where('assigned_to', $developerId);
    }
}
