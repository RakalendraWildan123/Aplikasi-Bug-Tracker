<?php

namespace Database\Factories;

use App\Enums\DeveloperRole;
use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Developer>
 */
class DeveloperFactory extends Factory
{
    protected $model = Developer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = substr(fake()->name(), 0, 150);
        $initials = collect(preg_split('/\s+/', trim($name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
            ->implode('');

        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'role_title' => fake()->randomElement(DeveloperRole::cases()),
            'avatar' => $initials !== '' ? $initials : 'DV',
            'avatar_bg' => fake()->randomElement([
                'bg-indigo-600',
                'bg-emerald-600',
                'bg-amber-600',
                'bg-rose-600',
                'bg-sky-600',
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
