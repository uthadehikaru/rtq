<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->word(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'course_id' => 1,
            'start_time' => $this->faker->time(),
            'place' => $this->faker->word(),
        ];
    }
}
