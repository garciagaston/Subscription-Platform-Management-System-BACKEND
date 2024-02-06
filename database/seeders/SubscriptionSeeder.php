<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $user) {
            Subscription::factory()->create(['user_id' => $user->id]);
        }
    }
}
