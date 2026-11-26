<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    public const ROLES = ['admin', 'developer'];

    public const ROLE_LABELS = [
        'admin' => 'Atasan / Admin',
        'developer' => 'Developer',
    ];

    public const DEVELOPER_ROLES = [
        'Fullstack Lead',
        'Backend',
        'Frontend',
        'Mobile',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_title',
        'avatar',
        'avatar_bg',
        'foto_profil',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Compatibility helper: returns self if developer, null if admin.
     */
    public function getDeveloperAttribute(): ?self
    {
        return $this->role === 'developer' ? $this : null;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function roleLabel(): string
    {
        return self::ROLE_LABELS[$this->role] ?? (string) $this->role;
    }

    /**
     * Get the public URL for the user's profile photo, if set.
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
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include developers.
     */
    public function scopeDevelopers(Builder $query): Builder
    {
        return $query->where('role', 'developer');
    }

    /**
     * Scope a query to only include admins.
     */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', 'admin');
    }

    /**
     * Get tasks created by this user/developer.
     *
     * @return HasMany<Task, $this>
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Get tasks assigned to this user/developer.
     *
     * @return HasMany<Task, $this>
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Get bugs created by this user/developer.
     *
     * @return HasMany<Bug, $this>
     */
    public function createdBugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'created_by');
    }

    /**
     * Get bugs assigned to this user/developer.
     *
     * @return HasMany<Bug, $this>
     */
    public function assignedBugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'assigned_to');
    }

    /**
     * Get action items assigned to this user/developer.
     *
     * @return HasMany<ActionItem, $this>
     */
    public function assignedActionItems(): HasMany
    {
        return $this->hasMany(ActionItem::class, 'assigned_to');
    }

    /**
     * Periksa apakah pengguna masih menggunakan password default ("password").
     */
    public function usesDefaultPassword(): bool
    {
        return ! empty($this->password) && \Illuminate\Support\Facades\Hash::check('password', $this->password);
    }
}
