<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\imageDetail>
 */
class imageDetailFactory extends Factory
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
            "id_hotel" => fake()->numberBetween(1,10),
            "id_rooms" => fake()->numberBetween(1,10),
            "id_cate" => fake()->numberBetween(1,10),
            "id_image" => fake()->numberBetween(1,10),
        ];
    }
}
