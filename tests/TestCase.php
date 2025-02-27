<?php

namespace Tests;

use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->createPassportClient();
    }

    private function createPassportClient(): void
    {
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null,
            'Test Personal Access Client',
            'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $clientSecret = 'unhashed-client-secret-value';
        $client->setSecretAttribute($clientSecret);
        $client->save();
    }
}
