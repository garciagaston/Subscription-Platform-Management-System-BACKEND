<?php

namespace Tests\Unit\Api\V1;

use App\Helpers\TestsHelper;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class AuthTest extends TestCase
{
    public function testUserRegisterSuccess(): void
    {
        $password = $this->faker->password(8);
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
        ];
        $response = $this->post('/api/v1/auth/register', $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_CREATED);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'token_type',
                'token',
                'user',
            ],
        ]);
        $user = User::firstOrFail();
        $this->assertTrue($user->hasRole('user'));
        $response->assertJson([
            'status' => 'success',
            'message' => 'User is created successfully.',
            'data' => [
                'user' => (new UserResource($user))->toArray(new Request),
            ],
        ]);
        $jsonResponse = json_decode($response->getContent(), true);
        $this->assertEquals($jsonResponse['data']['token_type'], 'bearer');
        $this->assertNotNull($jsonResponse['data']['token']);
        unset($data['password_confirmation']);
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $this->assertTrue(Hash::check($value, $user->$key));
            } else {
                $this->assertEquals($user->$key, $value, "key {$key}");
            }
        }
    }

    public function testUserRegisterFail(): void
    {
        $password = $this->faker->password(8);
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => "FAKE{$password}",
        ];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post('/api/v1/auth/register', $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'password',
            ],
        ]);
        $response->assertJson([
            'message' => 'The password field confirmation does not match.',
            'errors' => [
                'password' => [
                    'The password field confirmation does not match.',
                ],
            ],
        ]);
        $this->assertEquals(User::count(), 0, 'no user registered');
    }

    public function testUserLoginSuccess(): void
    {
        $user = User::factory()->create(['password' => TestsHelper::PASSWORD]);
        $data = [
            'email' => $user->email,
            'password' => TestsHelper::PASSWORD,
        ];
        $response = $this->post('/api/v1/auth/login', $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_OK);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'token_type',
                'token',
                'user',
            ],
        ]);
        $response->assertJson([
            'status' => 'success',
            'message' => 'User is logged in successfully.',
            'data' => [
                'user' => (new UserResource($user))->toArray(new Request),
            ],
        ]);
        $jsonResponse = json_decode($response->getContent(), true);
        $this->assertEquals($jsonResponse['data']['token_type'], 'bearer');
        $this->assertNotNull($jsonResponse['data']['token']);
        $this->assertEquals($user->id, $jsonResponse['data']['user']['id']);
    }

    public function testUserLoginFail(): void
    {
        $data = ['email' => 'fake@email.com', 'password' => 'fakeEmail'];
        $response = $this->post('/api/v1/auth/login', $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_UNAUTHORIZED);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Invalid credentials',
        ]);
    }

    public function testLogoutSuccess(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $response = $this->actingAs($user)->post('/api/v1/auth/logout');
        TestsHelper::dumpApiResponsesWithErrors($response);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'User is logged out successfully',
        ]);
    }
}
