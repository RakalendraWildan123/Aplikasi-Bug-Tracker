<?php

namespace App\Models;

use Database\Factories\DeveloperFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Developer extends User
{
    /** @use HasFactory<DeveloperFactory> */
    use HasFactory;

    protected $table = 'users';

    public const ROLES = [
        'Fullstack Lead',
        'Backend',
        'Frontend',
        'Mobile',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('developer_role', function (Builder $builder) {
            $builder->where('role', 'developer');
        });

        static::creating(function (Developer $developer) {
            $developer->role = 'developer';
        });
    }

    /**
     * Compatibility helper: user returns $this.
     */
    public function getUserAttribute(): self
    {
        return $this;
    }

    /**
     * Compatibility helper: user_id returns $this->id.
     */
    public function getUserIdAttribute(): int
    {
        return (int) $this->id;
    }

    /**
     * Scope a query to only include active developers.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
