<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $superAdmin = Role::create(['name' => 'super_admin']);
        $adminIcodsa = Role::create(['name' => 'admin_icodsa']);
        $adminIcicyta = Role::create(['name' => 'admin_icicyta']);

        $permissions = ['view_loa', 'view_invoice', 'view_receipt'];

        foreach ($permissions as $permissionName) {
            $permission = Permission::create(['name' => $permissionName]);
            $superAdmin->permissions()->attach($permission);
        }

        $adminIcodsa->permissions()->attach(Permission::whereIn('name', ['view_loa', 'view_invoice', 'view_receipt'])->get());
        $adminIcicyta->permissions()->attach(Permission::whereIn('name', ['view_loa', 'view_invoice', 'view_receipt'])->get());
    }
}

