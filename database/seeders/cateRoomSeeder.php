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
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098357/274803_Dobbelt-vaerelse-hotel-oasia-aarhus_iistvj.jpg",
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
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098357/56009974_a7f12f.jpg",
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
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098356/360_F_65821315_WGpXLhFtlEHfGQ8sqJ5RUNFNmnYDGgOd_l17pcj.jpg",
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
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098356/360_F_29133877_bfA2n7cWV53fto2BomyZ6pyRujJTBwjd_dy9oxw.jpg",
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
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098357/56009974_a7f12f.jpg",
                "quantity_of_people" => fake()->numberBetween(4,12),
                "price" => fake()->numberBetween(500000,5000000),
                "acreage" => fake()->numberBetween(1,100),
                "floor" => fake()->numberBetween(1,10),
                "status" => 2,
                "likes" => fake()->numberBetween(1,10000),
                "views" => fake()->numberBetween(1,10000),
            ]
        ];
        categoryRoom::query()->insert($categories);
    }
}
