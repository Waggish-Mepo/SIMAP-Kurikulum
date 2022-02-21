<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
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
            'curriculum' => $this->faker->randomElement(config('constant.common.curriculums')),
            'caption' => $this->faker->text(10),
            'entry_year' => $this->faker->randomElement(config('constant.common.school_years')),
            'majors' => [],
            'subject_id' => Subject::factory()->create()->id
        ];
    }
}
