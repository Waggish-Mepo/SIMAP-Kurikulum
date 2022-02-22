<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Gradebook;
use App\Models\ReportPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradebookFactory extends Factory
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
            'report_period_id' => ReportPeriod::factory()->create()->id,
            'title' => $this->faker->title,
            'components' => [
                Gradebook::KNOWLEDGE, Gradebook::SKILL
            ],
            'weights' => [
                'knowledge' => 0.5,
                'skill' => 0.5
            ],
            'scorebar' => $this->faker->numberBetween(60, 75),
            'course_id' => Course::factory()->create()->id,
        ];
    }
}
