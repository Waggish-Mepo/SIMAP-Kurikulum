<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
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
            'group' => $this->faker->text(10),
            'order' => $this->faker->numberBetween(1, 500)
        ];
    }
}
