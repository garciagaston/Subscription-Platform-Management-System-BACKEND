<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        $count = (int) $this->command->ask('How many channels do you want to create?', 50);
        Channel::factory($count)->create();
    }
}
