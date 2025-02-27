<?php

namespace Tests\Unit\Api\V1;

use App\Helpers\TestsHelper;
use App\Http\Resources\SubscriptionResource;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class SubscriptionsTest extends TestCase
{
    private $admin;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = TestsHelper::createAdmin();
        $this->user = TestsHelper::createUser();
    }

    public function test_index_success(): void
    {
        $count = 20;
        $perPage = 10;
        Subscription::factory($count)->create();
        $subscriptionsPaginated = Subscription::take($perPage)->get();
        $response = $this->actingAs($this->admin)->get("/api/v1/subscriptions?per_page={$perPage}");
        TestsHelper::dumpApiResponsesWithErrors($response);
        $response->assertStatus(Response::HTTP_OK);
        TestsHelper::checkPagination($response, $this, $count, $perPage);
        $jsonResponse = TestsHelper::getJsonResponse($response);
        $this->assertEquals($jsonResponse['data'], SubscriptionResource::collection($subscriptionsPaginated)->toArray(new Request));
    }

    public function test_index_failed(): void
    {
        $count = 20;
        $perPage = 10;
        Subscription::factory($count)->create();
        $response = $this->actingAs($this->user)->get("/api/v1/users?per_page={$perPage}");
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_FORBIDDEN);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_show_success_with_admin(): void
    {
        $count = 10;
        $subscriptions = Subscription::factory($count)->create();
        $subscription = $subscriptions->random();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->admin)->get("/api/v1/subscriptions/{$subscription->id}");
        TestsHelper::dumpApiResponsesWithErrors($response);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => (new SubscriptionResource($subscription))->toArray(new Request),
        ]);
    }

    public function test_store_success_with_admin(): void
    {
        $startDate = $this->faker->dateTimeBetween('-2 month', '+1 week')->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($startDate)->addMonth()->toDateTimeString();
        $data = [
            'user_id' => optional(User::inRandomOrder()->first())->id ?? User::factory()->create()->id,
            'package_id' => optional(Package::inRandomOrder()->first())->id ?? Package::factory()->create()->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        $this->assertEquals(Subscription::count(), 0);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->admin)->post('/api/v1/subscriptions', $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_CREATED);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'status',
            'data',
        ]);
        $this->assertEquals(Subscription::count(), 1, 'subscription created');
        $subscription = Subscription::firstOrFail();
        $response->assertJson([
            'status' => 'success',
            'data' => (new SubscriptionResource($subscription))->toArray(new Request),
        ]);
        foreach ($data as $key => $value) {
            $this->assertEquals($subscription->$key, $value, "key {$key}");
        }
    }

    public function test_store_success_with_user(): void
    {
        $startDate = $this->faker->dateTimeBetween('-2 month', '+1 week')->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($startDate)->addMonth()->toDateTimeString();
        $data = [
            'user_id' => $this->user->id,
            'package_id' => optional(Package::inRandomOrder()->first())->id ?? Package::factory()->create()->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        $this->assertEquals(Subscription::count(), 0);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->user)->post('/api/v1/subscriptions', $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_CREATED);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'status',
            'data',
        ]);
        $this->assertEquals(Subscription::count(), 1, 'subscription created');
        $subscription = Subscription::firstOrFail();
        $response->assertJson([
            'status' => 'success',
            'data' => (new SubscriptionResource($subscription))->toArray(new Request),
        ]);
        foreach ($data as $key => $value) {
            $this->assertEquals($subscription->$key, $value, "key {$key}");
        }
    }

    public function test_store_fail_with_user(): void
    {
        $startDate = $this->faker->dateTimeBetween('-2 month', '+1 week')->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($startDate)->addMonth()->toDateTimeString();
        $data = [
            'user_id' => User::factory()->create()->id,
            'package_id' => optional(Package::inRandomOrder()->first())->id ?? Package::factory()->create()->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        $this->assertEquals(Subscription::count(), 0);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->user)->post('/api/v1/subscriptions', $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_FORBIDDEN);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertEquals(Subscription::count(), 0);
        $response->assertJson([
            'message' => 'This action is unauthorized.',
        ]);
    }

    public function test_update_success(): void
    {
        $subscription = Subscription::factory()->create();
        $startDate = $this->faker->dateTimeBetween('-2 month', '+1 week')->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($startDate)->addMonth()->toDateTimeString();
        $data = [
            'user_id' => optional(User::inRandomOrder()->first())->id ?? User::factory()->create()->id,
            'package_id' => optional(Package::inRandomOrder()->first())->id ?? Package::factory()->create()->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->admin)->put("/api/v1/subscriptions/{$subscription->id}", $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_OK);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'data',
        ]);
        $subscription->refresh();
        $response->assertJson([
            'status' => 'success',
            'data' => (new SubscriptionResource($subscription))->toArray(new Request),
        ]);
        foreach ($data as $key => $value) {
            $this->assertEquals($subscription->$key, $value, "key {$key}");
        }
    }

    public function test_delete_success(): void
    {
        $subscription = Subscription::factory()->create();
        $this->assertEquals(Subscription::count(), 1, '1 Subscription');
        $response = $this->actingAs($this->admin)->delete("/api/v1/subscriptions/{$subscription->id}");
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_OK);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['status' => 'success', 'message' => 'Subscription deleted successfully.']);
        $deletedSubscription = Subscription::where('id', $subscription->id)->onlyTrashed()->firstOrFail();
        $this->assertNotNull($deletedSubscription);
        $this->assertEquals($deletedSubscription::count(), 0, 'deleted subscription');
        $this->assertEquals(Subscription::onlyTrashed()->count(), 1, 'deleted subscriptions '.Subscription::onlyTrashed()->count());
    }
}
