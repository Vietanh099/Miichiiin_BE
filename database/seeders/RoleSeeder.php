<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows_in_role = [
            [
                'name' => 'chain owner',
                'guard_name' => 'admins',
                'level' => 3,
                'updated_at' => now(),
                'created_at' => now()
            ],
            [
                'name' => 'hotel owner',
                'guard_name' => 'admins',
                'level' => 2,
                'updated_at' => now(),
                'created_at' => now()
            ],
            [
                'name' => 'staff',
                'guard_name' => 'admins',
                'level' => 1,
                'updated_at' => now(),
                'created_at' => now()
            ],
        ];
        Role::insert($rows_in_role);
    }
}
