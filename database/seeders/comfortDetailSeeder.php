<?php

namespace Database\Seeders;

use App\Models\comfortDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class comfortDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        comfortDetail::factory(10)->create();
    }
}
