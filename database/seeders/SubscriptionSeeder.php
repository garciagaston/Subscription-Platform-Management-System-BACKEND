<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $user) {
            $subscription = Subscription::factory()->create(['user_id' => $user->id]);
            Log::info("Subscription #{$subscription->id} added to User #{$user->id}.");
            $this->command->info("Subscription #{$subscription->id} added to User #{$user->id}.");
        }
    }
}
