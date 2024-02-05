<?php

namespace Tests;

use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        // Config::set('database.default', 'sqlite');
        // Config::set('database.connections.mysql_vdg', [
        //     'driver' => 'sqlite',
        //     'url' => env('DATABASE_URL'),
        //     'database' => env('DB_DATABASE', database_path('database.sqlite')),
        //     'prefix' => '',
        //     'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        // ]);
    }
}
