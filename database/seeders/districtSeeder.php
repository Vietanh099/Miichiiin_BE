<?php

namespace Database\Seeders;

use App\Models\district;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class districtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        district::factory()->count(10)->create();

    }
}
