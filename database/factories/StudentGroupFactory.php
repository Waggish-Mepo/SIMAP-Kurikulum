<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentGroupFactory extends Factory
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
            'batch_id' => $this->faker->uuid(),
            'school_year' => $this->faker->randomElement(config('constant.common.school_years')),
        ];
    }
}
