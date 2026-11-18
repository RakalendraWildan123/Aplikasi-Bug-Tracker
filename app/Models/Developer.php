<?php

namespace App\Models;

use App\Enums\DeveloperRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Developer extends Model
{
    /** @use HasFactory<\Database\Factories\DeveloperFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role_title',
        'avatar',
        'avatar_bg',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role_title' => DeveloperRole::class,
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include active developers.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Get tasks created by this developer.
     *
     * @return HasMany<Task, $this>
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Get tasks assigned to this developer.
     *
     * @return HasMany<Task, $this>
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Get bugs created by this developer.
     *
     * @return HasMany<Bug, $this>
     */
    public function createdBugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'created_by');
    }

    /**
     * Get bugs assigned to this developer.
     *
     * @return HasMany<Bug, $this>
     */
    public function assignedBugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'assigned_to');
    }
}
