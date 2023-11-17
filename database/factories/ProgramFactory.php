<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->sentence(2);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'amount' => $this->faker->numberBetween(1111111, 9999999),
            'qty' => $this->faker->numberBetween(111, 999),
            'published_at' => null,
        ];
    }
}
