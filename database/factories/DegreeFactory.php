<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Degree;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Degree>
 */
class DegreeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'max_year' => 4
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Degree $degree) {
            for ($i=0; $i < 7; $i++) {
                Classroom::factory()
                ->count(1)
                ->state(new Sequence(
                    [
                        'name' => $degree->name[0].''.($i+1),
                        'degree_id' => $degree->id,
                    ],
                ))
                ->create();
            }
        });
    }
}
