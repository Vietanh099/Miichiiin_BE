<?php

namespace Database\Seeders;

use App\Models\Comfort;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComfortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comfort::factory(10)->create();
    }
}
