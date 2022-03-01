<?php

namespace Database\Factories;

use App\Models\GradebookComponent;
use App\Models\Scorecard;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScorecardComponentFactory extends Factory
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
            'scorecard_id' => Scorecard::factory()->create()->id,
            'gradebook_component_id' => GradebookComponent::factory()->create()->id,
            'knowledge_score' => $this->faker->numberBetween(0, 100),
            'skill_score' => $this->faker->numberBetween(0, 100),
            'general_score' => $this->faker->numberBetween(0, 100),
        ];
    }
}
