<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BatchFactory extends Factory
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
            'major_id' => $this->faker->uuid(),
            'entry_year' => $this->faker->randomElement(config('constant.common.entry_years')),
            'batch_name' => $this->faker->name(),
        ];
    }
}
