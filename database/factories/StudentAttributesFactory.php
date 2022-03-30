<?php

namespace Database\Factories;

use App\Models\AttitudePredicate;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentAttributesFactory extends Factory
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
            'description' => $this->faker->text(),
            'attitude_predicate_id' => AttitudePredicate::factory()->create()->id,
            'student_id' => Student::factory()->create()->id,
        ];
    }
}
