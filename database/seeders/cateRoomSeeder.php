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
        categoryRoom::factory()->count(10)->create();

    }
}
