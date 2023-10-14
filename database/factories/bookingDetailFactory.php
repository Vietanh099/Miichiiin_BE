<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\bookingDetail>
 */
class bookingDetailFactory extends Factory
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
            "id_promotions" => fake()->numberBetween(1,10),
            "id_cate" => fake()->numberBetween(1,10),
            "id_room" => fake()->numberBetween(1,10),
            "id_services" => fake()->numberBetween(1,10),
            "id_booking" => fake()->numberBetween(1,10),
        ];
    }
}
