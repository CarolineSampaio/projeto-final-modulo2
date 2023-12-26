<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workout>
 */
class WorkoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => random_int(1, 10),
            'exercise_id' => random_int(1, 10),
            'repetitions' => random_int(1, 10),
            'weight' => random_int(1, 10),
            'break_time' => random_int(1, 60),
            'day' => 'SEGUNDA',
            'observations' => 'Observações do treino',
            'time' => random_int(1, 120),
        ];
    }
}
