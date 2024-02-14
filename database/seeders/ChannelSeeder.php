<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ChannelSeeder extends Seeder
{
    // php artisan db:seed --class=ChannelSeeder
    public function run(): void
    {
        $count = (int) $this->command->ask('How many channels do you want to create?', 50);
        Channel::factory($count)->create()->each(function ($channel) {
            Log::info("Channel #{$channel->id} created.");
            $this->command->info("Channel #{$channel->id} created.");
        });
    }
}
