<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::select('*')->where('name','chain owner')->first();
        $permissions = Permission::select('*')->whereNotIn('id', [
            12, 14, 18, 23, 24, 25, 26, 31, 32
        ])->get();
        $role->syncPermissions($permissions);
    }
}
