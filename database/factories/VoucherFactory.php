<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(50),
            'slug' => fake()->text(10),
            'image' => fake()->imageUrl,
            'quantity' => fake()->numberBetween(0,10),
            'discount' => fake()->numerify(),
            'start_at' => fake()->dateTime(),
            'expire_at' => fake()->dateTime(),
            'status' => fake()->numberBetween(1,3),
            'meta' => fake()->text(50),
            'description' => fake()->text(50)
        ];
    }
}
