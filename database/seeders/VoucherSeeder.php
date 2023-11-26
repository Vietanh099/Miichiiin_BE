<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Voucher::factory(20)->create();
        for ($i = 0; $i < 5; $i++) {
            $list_voucher = [
                [
                    'name' => fake()->text(10),
                    'slug' => "MiiChi-Voucher-" . strtolower(Str::random(2)) . rand(100, 999),
                    'image' => fake()->imageUrl,
                    'type' => 1,
                    'quantity' => 100,
                    'discount' => fake()->numberBetween(5,20),
                    'start_at' => now(),
                    'expire_at' => Carbon::now()->addDays(7),
                    'status' => 2,
                    'meta' => fake()->text(20),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => fake()->text(10),
                    'slug' => "MiiChi-Voucher-" . strtolower(Str::random(2)) . rand(100, 999),
                    'image' => fake()->imageUrl,
                    'type' => 2,
                    'quantity' => 100,
                    'discount' => fake()->numberBetween(1,10),
                    'start_at' => now(),
                    'expire_at' => Carbon::now()->addDays(7),
                    'status' => 2,
                    'meta' => fake()->text(20),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];
            Voucher::query()->insert($list_voucher);
        }
    }
}
