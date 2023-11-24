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
        $role = Role::query()->select('*')->where('name','chain owner')->first();
        $permissions = Permission::query()
            ->select('*')
            ->whereNotIn('id', [54,14,33,34,18,26,22])->get();
        $role->syncPermissions($permissions);

        $role = Role::query()->select('*')->where('name','hotel owner')->first();
        $permissions = Permission::query()
            ->select('*')
            ->whereNotIn('id', [2,3,4,9,11,13,55])
            ->get();
        $role->syncPermissions($permissions);

        $staff_permission = Role::query()
            ->select('*')
            ->where('level','>', 1)
            ->get()
            ->pluck('id');
        $staff_role = Role::query()->select('*')->where('name','staff')->first();
        $permissions = Permission::query()
            ->select('*')
            ->whereNotIn('id', $staff_permission)
            ->get();
        $staff_role->syncPermissions($permissions);
    }
}
