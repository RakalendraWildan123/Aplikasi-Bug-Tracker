<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class ActionItem extends Model
{
    /** @use HasFactory<\Database\Factories\ActionItemFactory> */
    use HasFactory;

    public const STATUSES = [
        'planned',
        'converted_to_task',
        'completed',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'evaluation_id',
        'action_plan',
        'assigned_to',
        'target_date',
        'status',
        'converted_task_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'target_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<AppEvaluation, $this>
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(AppEvaluation::class, 'evaluation_id');
    }

    /**
     * @return BelongsTo<Developer, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Developer::class, 'assigned_to');
    }

    /**
     * @return BelongsTo<Task, $this>
     */
    public function convertedTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'converted_task_id');
    }

    /**
     * Scope a query to filter action items by status.
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        if (! in_array($status, self::STATUSES, true)) {
            throw new InvalidArgumentException("Invalid action item status: {$status}");
        }

        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter action items for a specific evaluation.
     */
    public function scopeForEvaluation(Builder $query, int $evaluationId): Builder
    {
        return $query->where('evaluation_id', $evaluationId);
    }

    /**
     * Scope a query to filter action items assigned to a specific developer.
     */
    public function scopeAssignedTo(Builder $query, int $developerId): Builder
    {
        return $query->where('assigned_to', $developerId);
    }
}
