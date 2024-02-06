<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['channels', 'users', 'packages', 'subscriptions'];
        foreach ($permissions as $permission) {
            Permission::create(['name' => "view any $permission", 'guard_name' => 'web']);
            Permission::create(['name' => "view $permission", 'guard_name' => 'web']);
            Permission::create(['name' => "create $permission", 'guard_name' => 'web']);
            Permission::create(['name' => "edit $permission", 'guard_name' => 'web']);
            Permission::create(['name' => "delete $permission", 'guard_name' => 'web']);
        }
    }
}
