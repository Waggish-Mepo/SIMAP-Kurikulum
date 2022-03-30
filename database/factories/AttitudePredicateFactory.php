<?php

namespace Database\Factories;

use App\Models\Attitude;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttitudePredicateFactory extends Factory
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
            'description' => $this->faker->text(),
            'predicate' => $this->faker->randomElement(['A','B','C','D','E']),
            'attitude_id' => Attitude::factory()->create()->id,
        ];
    }
}
