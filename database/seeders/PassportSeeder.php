<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PassportSeeder extends Seeder
{
    // php artisan db:seed --class=PassportSeeder
    public function run(): void
    {
        // Create a new OAuth client without user interaction
        Artisan::call('passport:client', [
            '--client' => true,
            '--name' => 'OAuth Client',
            //'--redirect_uri' => 'http://localhost'
        ]);

        // Create a new personal access client without user interaction
        Artisan::call('passport:client', [
            '--personal' => true,
            '--name' => 'Personal Access Client'
        ]);
    }
}
