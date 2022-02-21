<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MajorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->firstName(),
            'abbreviation' => $this->faker->company(),
        ];
    }
}
