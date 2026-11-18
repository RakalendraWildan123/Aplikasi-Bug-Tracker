<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'name',
        'description',
    ];

    /**
     * Get the project that owns the module.
     *
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the tasks for the module.
     *
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the bugs for the module.
     *
     * @return HasMany<Bug, $this>
     */
    public function bugs(): HasMany
    {
        return $this->hasMany(Bug::class);
    }
}
