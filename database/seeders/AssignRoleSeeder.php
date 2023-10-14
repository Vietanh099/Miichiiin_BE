<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

class AssignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::select('*')->where('name','chain owner')->first();
        $admin = Admin::query()->first();
        $admin->assignRole($role);
    }
}
