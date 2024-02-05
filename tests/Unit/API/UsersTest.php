<?php

namespace Tests\Unit\Api\Auth\V1;

use App\Helpers\TestsHelper;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class UsersTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['name' => 'admin']);
        $this->admin->assignRole('admin');
    }
    public function testIndexSuccess(): void
    {
        $count = 20;
        $perPage = 10;
        $users = User::factory($count)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
        $usersPaginated = User::take($perPage)->get();
        $response = $this->actingAs($this->admin)->get("/api/v1/users?per_page={$perPage}");
        TestsHelper::dumpApiResponsesWithErrors($response);
        $response->assertStatus(Response::HTTP_OK);
        TestsHelper::checkPagination($response, $this, $count + 1, $perPage);
        $jsonResponse = TestsHelper::getJsonResponse($response);
        $this->assertEquals($jsonResponse['data'], UserResource::collection($usersPaginated)->toArray(new Request()));
    }

    public function testIndexFailed(): void
    {
        $count = 20;
        $perPage = 10;
        $users = User::factory($count)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
        $randomUser = $users->random();
        $response = $this->actingAs($randomUser)->get("/api/v1/users?per_page={$perPage}");
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_FORBIDDEN);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testShowSuccessWithAdmin(): void
    {
        $count = 10;
        $users = User::factory($count)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
        $user = $users->random();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->admin)->get("/api/v1/users/{$user->id}");
        TestsHelper::dumpApiResponsesWithErrors($response);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => (new UserResource($user))->toArray(new Request())
        ]);
    }

    public function testShowSuccessWithUser(): void
    {
        $count = 10;
        $users = User::factory($count)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
        $user = $users->random();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($user)->get("/api/v1/users/{$user->id}");
        TestsHelper::dumpApiResponsesWithErrors($response);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => (new UserResource($user))->toArray(new Request())
        ]);
    }

    public function testShowFailWithUser(): void
    {
        $count = 10;
        $users = User::factory($count)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
        $usersRandom = $users->random(2);
        $user1 = $usersRandom[0];
        $user2 = $usersRandom[1];
        $this->assertFalse($user1->isAdmin());
        $this->assertFalse($user2->isAdmin());
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($user1)->get("/api/v1/users/{$user2->id}");
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_FORBIDDEN);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'This action is unauthorized.'
        ]);
    }

    public function testStoreSuccess(): void
    {
        $password = $this->faker->password(8);
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password
        ];
        $this->assertEquals(User::count(), 1, 'only admin user');
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->admin)->post("/api/v1/users", $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_CREATED);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'status',
            'data'
        ]);
        $this->assertEquals(User::count(), 2, 'created new user');
        $user = User::whereNot('email', $this->admin->email)->firstOrFail();
        $this->assertTrue($this->admin->isAdmin());
        $this->assertFalse($user->isAdmin());
        $response->assertJson([
            'status' => 'success',
            'data' => (new UserResource($user))->toArray(new Request())
        ]);
        $jsonResponse = json_decode($response->getContent(), true);
        unset($data['password_confirmation']);
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $this->assertTrue(Hash::check($value, $user->$key));
            } else {
                $this->assertEquals($user->$key, $value, "key {$key}");
            }
        }
    }
}
