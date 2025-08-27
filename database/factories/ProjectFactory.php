<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['planned','active','done'];

        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement($statuses),
            'due_date' => $this->faker->optional()->dateTimeBetween('now','+3 months'),
        ];
    }
}
