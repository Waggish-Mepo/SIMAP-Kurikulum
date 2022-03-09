<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentAbsenceFactory extends Factory
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
            'student_id' => $this->faker->uuid(),
            'report_period_id' => $this->faker->uuid(),
            'alpa' => $this->faker->numerify('###'),
            'izin' => $this->faker->numerify('###'),
            'sakit' => $this->faker->numerify('###'),
        ];
    }
}
