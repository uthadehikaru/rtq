<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Violation>
 */
class ViolationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'violated_date' => $this->faker->date(),
            'user_id' => null,
            'description' => $this->faker->text(),
            'amount' => $this->faker->numberBetween(100, 1000),
            'paid_at' => null,
            'type' => 'member',
        ];
    }
}
