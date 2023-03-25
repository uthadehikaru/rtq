<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nik'=>$this->faker->numerify('################'),
            'full_name'=>$this->faker->name(),
            'short_name'=>$this->faker->name(),
            'gender'=>$this->faker->randomElement(['male','female']),
            'birth_place'=>$this->faker->word(),
            'birth_date'=>$this->faker->date(),
            'address'=>$this->faker->address(),
            'phone'=>$this->faker->e164PhoneNumber(),
            'email'=>$this->faker->safeEmail(),
            'address'=>$this->faker->address(),
            'reference'=>$this->faker->name(),
            'term_condition'=>1,
        ];
    }
}
