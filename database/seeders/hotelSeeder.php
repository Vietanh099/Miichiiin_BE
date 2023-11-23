<?php

namespace Database\Seeders;

use App\Models\hotel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class hotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = [
            [
                "name" => "Miichi Hà Nội",
                "description" => fake()->text(20),
                "star" => fake()->numberBetween(0,5),
                "status" => 2,
                "email" => fake()->unique()->email(),
                "address" => fake()->address(),
                "phone" => fake()->phoneNumber(),
                "id_city" => 1,
                "quantity_floor" => fake()->numberBetween(0,10),
                "quantity_of_room" => fake()->numberBetween(0,30),
            ],
            [
                "name" => "Miichi Đà Nẵng",
                "description" => fake()->text(20),
                "star" => fake()->numberBetween(0,5),
                "status" => 2,
                "email" => fake()->unique()->email(),
                "address" => fake()->address(),
                "phone" => fake()->phoneNumber(),
                "id_city" => 4,
                "quantity_floor" => fake()->numberBetween(0,10),
                "quantity_of_room" => fake()->numberBetween(0,30),
            ],
            [
                "name" => "Michi Hồ Chí Minh",
                "description" => fake()->text(20),
                "star" => fake()->numberBetween(0,5),
                "status" => 2,
                "email" => fake()->unique()->email(),
                "address" => fake()->address(),
                "phone" => fake()->phoneNumber(),
                "id_city" => 2,
                "quantity_floor" => fake()->numberBetween(0,10),
                "quantity_of_room" => fake()->numberBetween(0,30),
            ],
            [
                "name" => "Michi Đà Lạt",
                "description" => fake()->text(20),
                "star" => fake()->numberBetween(0,5),
                "status" => 2,
                "email" => fake()->unique()->email(),
                "address" => fake()->address(),
                "phone" => fake()->phoneNumber(),
                "id_city" => 41,
                "quantity_floor" => fake()->numberBetween(0,10),
                "quantity_of_room" => fake()->numberBetween(0,30),
            ],
            [
                "name" => "Michi Hạ Long",
                "description" => fake()->text(20),
                "star" => fake()->numberBetween(0,5),
                "status" => 2,
                "email" => fake()->unique()->email(),
                "address" => fake()->address(),
                "phone" => fake()->phoneNumber(),
                "id_city" => 17,
                "quantity_floor" => fake()->numberBetween(0,10),
                "quantity_of_room" => fake()->numberBetween(0,30),
            ]
        ];
        hotel::query()->insert($hotels);
    }
}
