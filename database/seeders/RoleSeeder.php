<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN ROLE
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::where('guard_name', 'web')->get());
        Log::info('Admin Role created.');
        $this->command->info('Admin Role created.');

        // USER ROLE
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo(Permission::whereIn('name', [
            'view any channels',
            'view channels',
            'view any packages',
            'view packages',
        ])->get());
        Log::info('User Role created.');
        $this->command->info('User Role created.');

    }
}
