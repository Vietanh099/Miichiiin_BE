<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\comfortDetail>
 */
class comfortDetailFactory extends Factory
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
            "id_cate_room" => fake()->numberBetween(1,10),
            "id_comfort" => fake()->numberBetween(1,10),
        ];
    }
}
