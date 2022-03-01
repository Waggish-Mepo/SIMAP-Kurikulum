<?php

namespace Database\Factories;

use App\Models\Gradebook;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScorecardFactory extends Factory
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
            'student_id' => Student::factory()->create()->id,
            'gradebook_id' => Gradebook::factory()->create()->id,
            'knowledge_score' => $this->faker->numberBetween(0, 100),
            'skill_score' => $this->faker->numberBetween(0, 100),
            'general_score' => $this->faker->numberBetween(0, 100),
            'final_score' => $this->faker->numberBetween(0, 100),
            'predicate' => $this->faker->randomElement(['A','B','C','D','E']),
        ];
    }
}
