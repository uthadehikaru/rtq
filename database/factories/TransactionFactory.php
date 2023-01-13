<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $isDebit = $this->faker->boolean();
        return [
            'transaction_date' => $this->faker->date(),
            'description' => $this->faker->sentence(),
            'debit' => $isDebit?$this->faker->numberBetween(99,999):0,
            'credit' => !$isDebit?$this->faker->numberBetween(99,999):0,
        ];
    }
}
