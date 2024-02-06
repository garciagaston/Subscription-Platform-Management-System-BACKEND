<?php

namespace Tests\Unit\API;

use App\Helpers\TestsHelper;
use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class ChannelsTest extends TestCase
{
    private $admin;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['name' => 'admin']);
        $this->admin->assignRole('admin');

        $this->user = User::factory()->create(['name' => 'user']);
        $this->user->assignRole('user');
    }
    public function testIndexSuccess(): void
    {
        $count = 20;
        $perPage = 10;
        Channel::factory($count)->create();
        $channelsPaginated = Channel::take($perPage)->get();
        $response = $this->actingAs($this->admin)->get("/api/v1/channels?per_page={$perPage}");
        TestsHelper::dumpApiResponsesWithErrors($response);
        $response->assertStatus(Response::HTTP_OK);
        TestsHelper::checkPagination($response, $this, $count, $perPage);
        $jsonResponse = TestsHelper::getJsonResponse($response);
        $this->assertEquals($jsonResponse['data'], ChannelResource::collection($channelsPaginated)->toArray(new Request()));
    }

    public function testIndexFailed(): void
    {
        $count = 20;
        $perPage = 10;
        Channel::factory($count)->create();
        $response = $this->actingAs($this->user)->get("/api/v1/users?per_page={$perPage}");
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_FORBIDDEN);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testShowSuccessWithAdmin(): void
    {
        $count = 10;
        $channels = Channel::factory($count)->create();
        $channel = $channels->random();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->admin)->get("/api/v1/channels/{$channel->id}");
        TestsHelper::dumpApiResponsesWithErrors($response);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => (new ChannelResource($channel))->toArray(new Request())
        ]);
    }

    public function testStoreSuccess(): void
    {
        $data = [
            'name' => $this->faker->name(),
            'image_url' => $this->faker->imageUrl(),
            'lineup_id' => $this->faker->numberBetween(1, 100),
            'call_sign' => $this->faker->lexify('????'),
            'active' => $this->faker->boolean(),
            'display_order' => $this->faker->numberBetween(1, 100)
        ];
        $this->assertEquals(Channel::count(), 0);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->admin)->post("/api/v1/channels", $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_CREATED);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'status',
            'data'
        ]);
        $this->assertEquals(Channel::count(), 1, 'channel created');
        $channel = Channel::firstOrFail();
        $response->assertJson([
            'status' => 'success',
            'data' => (new ChannelResource($channel))->toArray(new Request())
        ]);
        foreach ($data as $key => $value) {
            $this->assertEquals($channel->$key, $value, "key {$key}");
        }
    }

    public function testUpdateSuccess(): void
    {
        $channel = Channel::factory()->create();
        $data = [
            'name' => $this->faker->name(),
            'image_url' => $this->faker->imageUrl(),
            'lineup_id' => $this->faker->numberBetween(1, 100),
            'call_sign' => $this->faker->lexify('????'),
            'active' => $this->faker->boolean(),
            'display_order' => $this->faker->numberBetween(1, 100)
        ];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->admin)->put("/api/v1/channels/{$channel->id}", $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_OK);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'data'
        ]);
        $channel->refresh();
        $response->assertJson([
            'status' => 'success',
            'data' => (new ChannelResource($channel))->toArray(new Request())
        ]);
        foreach ($data as $key => $value) {
            $this->assertEquals($channel->$key, $value, "key {$key}");
        }
    }

    public function testDeleteSuccess(): void
    {
        $channel = Channel::factory()->create();
        $this->assertEquals(Channel::count(), 1, '1 Channel');
        $response = $this->actingAs($this->admin)->delete("/api/v1/channels/{$channel->id}");
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_OK);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['status' => 'success', 'message' => 'Channel deleted successfully.']);
        $deletedChannel = Channel::where('id', $channel->id)->onlyTrashed()->firstOrFail();
        $this->assertNotNull($deletedChannel);
        $this->assertEquals($deletedChannel::count(), 0, 'deleted channel');
        $this->assertEquals(Channel::onlyTrashed()->count(), 1, 'deleted channels ' . Channel::onlyTrashed()->count());
    }
}
