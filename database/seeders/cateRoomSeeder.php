<?php

namespace Database\Seeders;

use App\Models\cateogry_room;
use App\Models\categoryRoom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class cateRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
//        categoryRoom::factory()->count(10)->create();
        $categories = [
            [
                "name" => "Phòng tiêu chuẩn",
                "description" =>fake()->text(20),
                "image" => fake()->imageUrl,
                "quantity_of_people" => fake()->numberBetween(4,12),
                "price" => fake()->numberBetween(500000,5000000),
                "acreage" => fake()->numberBetween(1,10),
                "floor" => fake()->numberBetween(1,10),
                "status" => 2,
                "likes" => fake()->numberBetween(1,100),
                "views" => fake()->numberBetween(1,100),
            ],
            [

                "name" => "Phòng gia đình",
                "description" =>fake()->text(20),
                "image" => fake()->imageUrl,
                "quantity_of_people" => fake()->numberBetween(4,12),
                "price" => fake()->numberBetween(500000,5000000),
                "acreage" => fake()->numberBetween(1,10),
                "floor" => fake()->numberBetween(1,10),
                "status" => 2,
                "likes" => fake()->numberBetween(1,100),
                "views" => fake()->numberBetween(1,100),
            ],
            [
                "name" => "Phòng view biển",
                "description" =>fake()->text(20),
                "image" => fake()->imageUrl,
                "quantity_of_people" => fake()->numberBetween(4,12),
                "price" => fake()->numberBetween(500000,5000000),
                "acreage" => fake()->numberBetween(1,10),
                "floor" => fake()->numberBetween(1,10),
                "status" => 2,
                "likes" => fake()->numberBetween(1,100),
                "views" => fake()->numberBetween(1,100),
            ],
            [

                "name" => "Phòng hạng sang",
                "description" =>fake()->text(20),
                "image" => fake()->imageUrl,
                "quantity_of_people" => fake()->numberBetween(4,12),
                "price" => fake()->numberBetween(500000,5000000),
                "acreage" => fake()->numberBetween(1,10),
                "floor" => fake()->numberBetween(1,10),
                "status" => 2,
                "likes" => fake()->numberBetween(1,100),
                "views" => fake()->numberBetween(1,100),
            ],
            [
                "name" => "Phòng suite",
                "description" =>fake()->text(20),
                "image" => fake()->imageUrl,
                "quantity_of_people" => fake()->numberBetween(4,12),
                "price" => fake()->numberBetween(500000,5000000),
                "acreage" => fake()->numberBetween(1,10),
                "floor" => fake()->numberBetween(1,10),
                "status" => 2,
                "likes" => fake()->numberBetween(1,100),
                "views" => fake()->numberBetween(1,100),
            ]
        ];
        categoryRoom::query()->insert($categories);
    }
}
