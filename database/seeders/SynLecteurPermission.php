<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SynLecteurPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::find(1);
        $permission_ids = [1, 2, 8, 9];
        $permissions = Permission::whereIn('id',$permission_ids);
        $role->givePermissionTo($permissions);
    }
}
