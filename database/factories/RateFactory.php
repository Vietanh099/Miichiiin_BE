<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rate>
 */
class RateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => fake()->numberBetween(1,5),
            'id_category' => fake()->numberBetween(1,5),
            'content' => fake()->text(50),
            'rating' => fake()->randomFloat(2,0,10),
            'status' => fake()->numberBetween(0,1)
        ];
    }
}
