<?php

namespace Database\Factories;

use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Developer>
 */
class DeveloperFactory extends Factory
{
    protected $model = Developer::class;

    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = Str::substr(fake()->name(), 0, 150);
        $initials = collect(preg_split('/\s+/', trim($name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => Str::upper(Str::substr($part, 0, 1)))
            ->implode('');

        return [
            'user_id' => null,
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role_title' => fake()->randomElement(Developer::ROLES),
            'avatar' => $initials !== '' ? $initials : 'DV',
            'avatar_bg' => fake()->randomElement([
                '#4F46E5',
                '#10B981',
                '#F59E0B',
                '#EF4444',
                '#0EA5E9',
            ]),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the developer is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
