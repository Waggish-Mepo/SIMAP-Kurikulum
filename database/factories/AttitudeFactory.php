<?php

namespace Database\Factories;

use App\Models\ReportPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttitudeFactory extends Factory
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
            'name' => $this->faker->name(),
            'type' => $this->faker->randomElement(['A','B','C','D','E']),
            'order' => $this->faker->numberBetween(1, 500),
            'report_period_id' => ReportPeriod::factory()->create()->id,
        ];
    }
}
