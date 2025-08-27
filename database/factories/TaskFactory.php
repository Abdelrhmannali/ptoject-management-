<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        $priorities = ['low','medium','high'];

        return [
            'title' => $this->faker->sentence(4),
            'details' => $this->faker->optional()->paragraph(),
            'priority' => $this->faker->randomElement($priorities),
            'is_completed' => $this->faker->boolean(20),
        ];
    }
}
