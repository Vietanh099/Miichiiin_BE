<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\room>
 */
class roomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            "name" => fake()->name(),
            "id_hotel" => fake()->numberBetween(1,10),
            "id_cate" => fake()->numberBetween(1,10),
            "status" => fake()->numberBetween(0,1),

        ];
    }
}
