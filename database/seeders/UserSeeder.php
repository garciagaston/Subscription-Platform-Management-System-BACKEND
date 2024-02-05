<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // CREATE ADMIN USER
        $user = User::factory()->create(['name' => 'admin', 'email' => 'admin@fake.com']);
        $user->assignRole('admin');
        Log::info("Admin User created: {$user->name} (#{$user->id})");

        // CREATE USERS
        $users = User::factory(10)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
            Log::info("User created: {$user->name} (#{$user->id})");
        }
    }
}
