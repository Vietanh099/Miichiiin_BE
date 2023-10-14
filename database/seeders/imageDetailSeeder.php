<?php

namespace Database\Seeders;

use App\Models\imageDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class imageDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        imageDetail::factory()->count(10)->create();

    }
}
