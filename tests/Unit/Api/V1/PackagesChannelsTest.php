<?php

namespace Tests\Unit\Api\V1;

use App\Helpers\TestsHelper;
use App\Http\Resources\PackageResource;
use App\Models\Channel;
use App\Models\Package;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class PackagesChannelsTest extends TestCase
{
    private $admin;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = TestsHelper::createAdmin();
        $this->user = TestsHelper::createUser();
    }

    public function testAttachSuccess(): void
    {
        $count = 5;
        $packages = Package::factory($count)->create();
        $channels = Channel::factory($count)->create();
        $package = $packages->random();
        $channel = $channels->random();
        $this->assertFalse($package->channels->contains($channel));
        $response = $this->actingAs($this->admin)->post("/api/v1/packages/{$package->id}/channels/{$channel->id}");
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_CREATED);
        $response->assertStatus(Response::HTTP_CREATED);
        $package->refresh();
        $this->assertTrue($package->channels->contains($channel));
        $response->assertJson([
            'status' => 'success',
            'data' => (new PackageResource($package))->toArray(new Request),
        ]);
    }

    public function testDetachSuccess(): void
    {
        $count = 5;
        $packages = Package::factory($count)->create();
        $channels = Channel::factory($count)->create();
        $package = $packages->random();
        $channel = $channels->random();
        $package->channels()->syncWithoutDetaching($channel);
        $this->assertTrue($package->channels->contains($channel));
        $response = $this->actingAs($this->admin)->delete("/api/v1/packages/{$package->id}/channels/{$channel->id}");
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_OK);
        $response->assertStatus(Response::HTTP_OK);
        $package->refresh();
        $this->assertFalse($package->channels->contains($channel));
        $response->assertJson([
            'status' => 'success',
            'data' => (new PackageResource($package))->toArray(new Request),
        ]);
    }
}
