<?php

namespace Tests\Unit\Api\V1;

use App\Helpers\TestsHelper;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class PackagesTest extends TestCase
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

    public function test_index_success(): void
    {
        $count = 20;
        $perPage = 10;
        Package::factory($count)->create();
        $packagesPaginated = Package::take($perPage)->get();
        $response = $this->actingAs($this->admin)->get("/api/v1/packages?per_page={$perPage}");
        TestsHelper::dumpApiResponsesWithErrors($response);
        $response->assertStatus(Response::HTTP_OK);
        TestsHelper::checkPagination($response, $this, $count, $perPage);
        $jsonResponse = TestsHelper::getJsonResponse($response);
        $this->assertEquals($jsonResponse['data'], PackageResource::collection($packagesPaginated)->toArray(new Request));
    }

    public function test_index_failed(): void
    {
        $count = 20;
        $perPage = 10;
        Package::factory($count)->create();
        $response = $this->actingAs($this->user)->get("/api/v1/users?per_page={$perPage}");
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_FORBIDDEN);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_show_success_with_admin(): void
    {
        $count = 10;
        $packages = Package::factory($count)->create();
        $package = $packages->random();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->admin)->get("/api/v1/packages/{$package->id}");
        TestsHelper::dumpApiResponsesWithErrors($response);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => (new PackageResource($package))->toArray(new Request),
        ]);
    }

    public function test_store_success(): void
    {
        $data = [
            'name' => $this->faker->lexify('????????'),
            'description' => $this->faker->text(100),
            'sku' => $this->faker->lexify('????????'),
            'image_url' => $this->faker->imageUrl(),
            'active' => $this->faker->boolean(),
        ];
        $this->assertEquals(Package::count(), 0);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->admin)->post('/api/v1/packages', $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_CREATED);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'status',
            'data',
        ]);
        $this->assertEquals(Package::count(), 1, 'package created');
        $package = Package::firstOrFail();
        $response->assertJson([
            'status' => 'success',
            'data' => (new PackageResource($package))->toArray(new Request),
        ]);
        foreach ($data as $key => $value) {
            $this->assertEquals($package->$key, $value, "key {$key}");
        }
    }

    public function test_update_success(): void
    {
        $package = Package::factory()->create();
        $data = [
            'name' => $this->faker->lexify('????????'),
            'description' => $this->faker->text(100),
            'sku' => $this->faker->lexify('????????'),
            'image_url' => $this->faker->imageUrl(),
            'active' => $this->faker->boolean(),
        ];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->actingAs($this->admin)->put("/api/v1/packages/{$package->id}", $data);
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_OK);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'data',
        ]);
        $package->refresh();
        $response->assertJson([
            'status' => 'success',
            'data' => (new PackageResource($package))->toArray(new Request),
        ]);
        foreach ($data as $key => $value) {
            $this->assertEquals($package->$key, $value, "key {$key}");
        }
    }

    public function test_delete_success(): void
    {
        $package = Package::factory()->create();
        $this->assertEquals(Package::count(), 1, '1 Package');
        $response = $this->actingAs($this->admin)->delete("/api/v1/packages/{$package->id}");
        TestsHelper::dumpApiResponsesWithErrors($response, Response::HTTP_OK);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['status' => 'success', 'message' => 'Package deleted successfully.']);
        $deletedPackage = Package::where('id', $package->id)->onlyTrashed()->firstOrFail();
        $this->assertNotNull($deletedPackage);
        $this->assertEquals($deletedPackage::count(), 0, 'deleted package');
        $this->assertEquals(Package::onlyTrashed()->count(), 1, 'deleted packages '.Package::onlyTrashed()->count());
    }
}
