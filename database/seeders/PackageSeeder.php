<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PackageSeeder extends Seeder
{
    // php artisan db:seed --class=PackageSeeder
    public function run(): void
    {
        $count = (int) $this->command->ask('How many packages do you want to create?', 4);
        Package::factory($count)->create()->each(function ($package) {
            $channels = Channel::inRandomOrder()->limit(rand(10, 30))->get();
            $package->channels()->attach($channels);
            foreach ($channels as $channel) {
                Log::info("Channel #{$channel->id} added to Package #{$package->id}.");
                $this->command->info("Channel #{$channel->id} added to Package #{$package->id}.");
            }
        });
    }
}
