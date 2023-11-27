<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IconsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $Icons = [
            [
                "icon" => "<i class='fa-sharp fa-regular fa-plus'></i>",
            ],
            [
                "icon" => "<i class='fa-sharp fa-light fa-pen-to-square'></i>",
            ],
            [
                "icon" => "<i class='fa-sharp fa-regular fa-delete-right'></i>",
            ]
        ];
        Icon::query()->insert($Icons);

    }
}
