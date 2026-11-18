<?php

namespace App\Models;

use App\Enums\ActionItemStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionItem extends Model
{
    /** @use HasFactory<\Database\Factories\ActionItemFactory> */
    use HasFactory;

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
            'status' => ActionItemStatus::class,
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
    public function scopeStatus(Builder $query, ActionItemStatus|string $status): Builder
    {
        $enum = $status instanceof ActionItemStatus ? $status : ActionItemStatus::from($status);

        return $query->where('status', $enum->value);
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
