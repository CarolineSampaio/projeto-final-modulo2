<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'date_birth' => $this->faker->date,
            'cpf' => $this->faker->numerify('###########'),
            'contact' => $this->faker->phoneNumber,
            'cep' => $this->faker->numerify('########'),
            'street' => $this->faker->streetName,
            'state' => $this->faker->stateAbbr,
            'neighborhood' => $this->faker->city,
            'city' => $this->faker->city,
            'number' => $this->faker->randomNumber(3),
        ];
    }
}
