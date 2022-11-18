<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->regexify('[A-Z]{2}[0-4]{7}'),
            'upi' => $this->faker->regexify('[a-z0-9]{15}'),
            'mis_id' => $this->faker->regexify('[0-9]{2}'),
            'title' => $this->faker->title(),
            'initials' => $this->faker->regexify('[A-Z]{2}'),
            'surname' => $this->faker->lastName(),
            'forename' => $this->faker->firstName(),
            'middle_names' => $this->faker->firstName(),
            'legal_surname' => $this->faker->lastName(),
            'legal_forename' => $this->faker->firstName(),
            'gender' => $this->faker->randomElement(['M', 'F']),
            'date_of_birth' => $this->faker->date(),
        ];
    }
}
