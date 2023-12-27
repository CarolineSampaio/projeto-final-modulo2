<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkoutFactory extends Factory
{
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
