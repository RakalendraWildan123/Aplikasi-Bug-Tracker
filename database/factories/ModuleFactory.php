<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleFactory extends Factory
{
    protected $model = Module::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = Str::substr(fake()->words(3, true), 0, 150);

        return [
            'project_id' => Project::factory(),
            'name' => Str::title($name),
            'description' => fake()->paragraph(),
        ];
    }
}
