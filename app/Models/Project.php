<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    public const STATUSES = [
        'active',
        'maintenance',
        'archived',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'repo_url',
        'status',
        'color',
    ];

    /**
     * Scope a query to only include active projects.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter projects by status.
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        if (! in_array($status, self::STATUSES, true)) {
            throw new InvalidArgumentException("Invalid project status: {$status}");
        }

        return $query->where('status', $status);
    }

    /**
     * Get the modules for the project.
     *
     * @return HasMany<Module, $this>
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    /**
     * Get the tasks for the project.
     *
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the bugs for the project.
     *
     * @return HasMany<Bug, $this>
     */
    public function bugs(): HasMany
    {
        return $this->hasMany(Bug::class);
    }

    /**
     * Get the repository release histories for the project.
     *
     * @return HasMany<RepoHistory, $this>
     */
    public function repoHistories(): HasMany
    {
        return $this->hasMany(RepoHistory::class);
    }

    /**
     * Get the periodic evaluations for the project.
     *
     * @return HasMany<AppEvaluation, $this>
     */
    public function appEvaluations(): HasMany
    {
        return $this->hasMany(AppEvaluation::class);
    }
}
