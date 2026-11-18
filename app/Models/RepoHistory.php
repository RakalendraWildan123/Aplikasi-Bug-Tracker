<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RepoHistory extends Model
{
    /** @use HasFactory<\Database\Factories\RepoHistoryFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'release_date',
        'release_tags',
        'branch_name',
        'commit_hash',
        'summary_changes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'release_date' => 'date',
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
     * Get the short 7-character Git commit hash.
     *
     * @return Attribute<string, never>
     */
    protected function shortHash(): Attribute
    {
        return Attribute::make(
            get: fn (): string => Str::substr((string) $this->commit_hash, 0, 7),
        );
    }

    /**
     * Scope a query to filter repo histories for a specific project.
     */
    public function scopeForProject(Builder $query, int $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope a query to order repo histories from latest release date.
     */
    public function scopeLatestRelease(Builder $query): Builder
    {
        return $query->orderByDesc('release_date')->orderByDesc('id');
    }

    /**
     * Scope a query to filter repo histories by branch name.
     */
    public function scopeBranch(Builder $query, string $branchName): Builder
    {
        return $query->where('branch_name', $branchName);
    }
}
