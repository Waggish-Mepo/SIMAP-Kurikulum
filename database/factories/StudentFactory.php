<?php

namespace Database\Factories;

use App\Models\StudentGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
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
            'nis' => $this->faker->numerify('#######'),
            'jk' => $this->faker->randomElement(config('constant.teacher.gender')),
            'student_group_id' => StudentGroup::factory()->create()->id,
        ];
    }
}
