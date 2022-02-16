<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectTeacherFactory extends Factory
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
            'subject_id' => Subject::factory()->create()->id,
            'teachers' => [
                $this->faker->uuid(),
                $this->faker->uuid(),
            ],
        ];
    }
}
