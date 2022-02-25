<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PredicateLetterFactory extends Factory
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
            'min_score' => $this->faker->numerify('##'),
            'max_score' => $this->faker->numerify('##'),
            'letter' => $this->faker->text(),
            'gradebook_id' => GradeBook::factory()->create()->id,
        ];
    }
}
