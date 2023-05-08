<?php

namespace Database\Factories;

use App\Models\Degree;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'degree_id' => Degree::factory(),
            'name' => $this->faker->name()
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Classroom $classroom) {

            Student::factory()
                ->count(10)
                ->state(new Sequence(
                    [
                        'gender' => 'male',
                        'classroom_id' => $classroom->id
                    ],
                ))
                ->create();

            Student::factory()
                ->count(5)
                ->state(new Sequence(
                    [
                        'gender' => 'female',
                        'classroom_id' => $classroom->id
                    ],
                ))
                ->create();
        });
    }
}
