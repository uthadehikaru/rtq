<?php

namespace Database\Factories;

use App\Models\Course;
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
            'name' => $this->faker->sentence(2),
            'fee' => $this->faker->randomNumber(6, true),
            'type' => $this->faker->randomElement(Course::TYPES),
        ];
    }
}
