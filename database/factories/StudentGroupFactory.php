<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Major;
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
            'batch_id' => Batch::factory()->create()->id,
            'school_year' => $this->faker->randomElement(config('constant.common.school_years')),
            'major_id' => Major::factory()->create()->id,
        ];
    }
}
