<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\categoryRoom>
 */
class categoryRoomFactory extends Factory
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
            'name'=>fake()->name(),
            'description'=>fake()->text(20),
            'image'=>fake()->imageUrl(),
            "price" => fake()->numberBetween(),
            "acreage" => fake()->numberBetween(),
            "floor" => fake()->numberBetween(),
            "likes" => fake()->numberBetween(),
            "status" => fake()->numberBetween(0,1),
            "views" => fake()->numberBetween(),
            "quantity_of_people" => fake()->numberBetween(),
        ];
    }
}
