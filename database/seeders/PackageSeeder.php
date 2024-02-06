<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $count = (int) $this->command->ask('How many packages do you want to create?', 4);
        Package::factory($count)->create()->each(function ($package) {
            $channels = Channel::inRandomOrder()->limit(rand(10, 30))->get();
            $package->save();
        });
    }
}
