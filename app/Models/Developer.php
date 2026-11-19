<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Developer extends Model
{
    /** @use HasFactory<\Database\Factories\DeveloperFactory> */
    use HasFactory;

    public const ROLES = [
        'Fullstack Lead',
        'Backend',
        'Frontend',
        'Mobile',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'role_title',
        'avatar',
        'avatar_bg',
        'foto_profil',
        'is_active',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user account associated with this developer.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the public URL for the developer's profile photo, if set.
     *
     * @return Attribute<?string, never>
     */
    protected function fotoProfilUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->foto_profil ? '/storage/'.ltrim($this->foto_profil, '/') : null,
        );
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

    /**
     * Get action items assigned to this developer.
     *
     * @return HasMany<ActionItem, $this>
     */
    public function assignedActionItems(): HasMany
    {
        return $this->hasMany(ActionItem::class, 'assigned_to');
    }
}
