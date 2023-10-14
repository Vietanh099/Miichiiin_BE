<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\hotel>
 */
class hotelFactory extends Factory
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
            "description" => fake()->text(20),
            "star" => fake()->numberBetween(0,5),
            "status" => fake()->numberBetween(0,1),
            "email" => fake()->unique()->email(),
            "address" => fake()->address(),
            "phone" => fake()->phoneNumber(),
            "id_city" => fake()->numberBetween(1,10),
            "quantity_floor" => fake()->numberBetween(0,10),
            "quantity_of_room" => fake()->numberBetween(0,100),
        ];
    }
}
