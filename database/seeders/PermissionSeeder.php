<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
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

            Log::info("{$permission} permissions created.");
            $this->command->info("{$permission} permissions created.");
        }

        Permission::create(['name' => "attach packages channels", 'guard_name' => 'web']);
        Permission::create(['name' => "detach packages channels", 'guard_name' => 'web']);
    }
}
