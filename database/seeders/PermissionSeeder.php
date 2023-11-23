<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 3 - Chủ chuỗi
        // 2 - Chủ khách sạn
        // 1 - Nhân viên
        $rows = [];
        $permissions = [
            // BANNER
            [1, 'get banner'],
            [3, 'add banner'],
            [3, 'update banner'],
            [3, 'delete banner'],
            // VOUCHER
            [1, 'get voucher'],
            [3, 'add voucher'],
            [3, 'update voucher'],
            [3,'delete voucher'],
            // HOTEL
            [3, 'get hotel'],
            [1, 'get detail hotel'],
            [3, 'add hotel'],
            [2, 'update hotel'],
            [3, 'delete hotel'],
            // ROOM
            [1, 'get room'],
            [2, 'add room'],
            [2, 'update room'],
            [2, 'delete room'],
            // CATEGORY
            [1, 'get category'],
            [2, 'add category'],
            [2, 'update category'],
            [2, 'delete category'],
            // SERVICE
            [1, 'get service'],
            [2, 'add service'],
            [2, 'update service'],
            [2, 'delete service'],
            // COMFORT
            [1, 'get comfort'],
            [2, 'add comfort'],
            [2, 'update comfort'],
            [2, 'delete comfort'],
            // RATE
            [1, 'get rate'],
            [1, 'update rate'],
            [1, 'delete rate'],
            // ADMIN
            [2, 'get admin'],
            [1, 'get detail admin'],
            [2, 'add admin'],
            [1, 'update admin'],
            [2, 'delete admin'],
            // USER
            [1, 'get user'],
            [1, 'add user'],
            [1, 'update user'],
            [1, 'delete user'],
            // ROLE
            [2, 'get role'],
            [2, 'add role'],
            [2, 'update role'],
            [2, 'delete role'],
            // PERMISSION
            [2, 'get permission'],
            [2, 'add permission'],
            [2, 'update permission'],
            [2, 'delete permission'],
            // BOOKING
            [1, 'get booking'],
            [1, 'add booking'],
            [1, 'update booking'],
            [1, 'delete booking'],
            // STATICTICAL
            [2, 'get statictical hotel'],
            [3, 'add statictical chain'],
        ];

        // create row to insert to permission table
        foreach ($permissions as $permission) {
            $rows[] = [
                'name' => $permission[1],
                'guard_name' => 'admins',
                'level' => $permission[0],
                'updated_at' => now(),
                'created_at' => now()
            ];
        }

        Permission::insert($rows);
    }
}
