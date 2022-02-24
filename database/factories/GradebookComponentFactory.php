<?php

namespace Database\Factories;

use App\Models\Gradebook;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradebookComponentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'gradebook_id' => Gradebook::factory()->create()->id,
            'title' => $this->faker->title,
            'abbreviation' => $this->faker->lexify('???'),
            'knowledge_weight' => $this->faker->numberBetween(0, 9),
            'skill_weight' => $this->faker->numberBetween(0, 9),
            'general_weight' => $this->faker->numberBetween(0, 9),
        ];
    }
}
