<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppEvaluation extends Model
{
    /** @use HasFactory<\Database\Factories\AppEvaluationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'evaluation_date',
        'period_review',
        'operational_issues',
        'user_feedback',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'evaluation_date' => 'date',
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
     * Scope a query to filter evaluations for a specific project.
     */
    public function scopeForProject(Builder $query, int $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope a query to order evaluations from latest evaluation date.
     */
    public function scopeLatestEvaluation(Builder $query): Builder
    {
        return $query->orderByDesc('evaluation_date')->orderByDesc('id');
    }

    /**
     * Scope a query to filter evaluations by period review label.
     */
    public function scopePeriod(Builder $query, string $periodReview): Builder
    {
        return $query->where('period_review', $periodReview);
    }

    /**
     * Get the action items resulting from this evaluation.
     *
     * @return HasMany<ActionItem, $this>
     */
    public function actionItems(): HasMany
    {
        return $this->hasMany(ActionItem::class, 'evaluation_id');
    }
}
