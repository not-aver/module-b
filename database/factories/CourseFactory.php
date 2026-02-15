<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(30),
            'description' => $this->faker->text(100),
            'duration' => $this->faker->numberBetween(1,10),
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(30),
            'cover' => 'mpic_default.jpg'
        ];
    }
}
