<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $firstName = $this->faker->firstName($gender);
        $lastName = $this->faker->lastName($gender);

        return [
            'full_name' => $firstName.' '.$lastName,
            'short_name' => $firstName,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->e164PhoneNumber(),
            'gender' => $gender,
            'address' => $this->faker->address(),
            'postcode' => $this->faker->postcode(),
            'user_id' => 0,
            'birth_date' => $this->faker->date(),
        ];
    }
}
