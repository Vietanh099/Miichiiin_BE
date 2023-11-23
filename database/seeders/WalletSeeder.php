<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_wallet = [];
        for ($i = 1; $i <= 20; $i++) {
            $list_wallet[] = [
                "id_user" => $i,
                "slug" => "MiiChi-Wallet-" . strtolower(Str::random(2)) . rand(100, 999),
                "created_at" => now(),
            ];
        }
        Wallet::query()->insert($list_wallet);
    }
}
