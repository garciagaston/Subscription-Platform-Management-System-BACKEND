<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN ROLE
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::where('guard_name', 'web')->get());

        // USER ROLE
        Role::create(['name' => 'user', 'guard_name' => 'web']);
    }
}
