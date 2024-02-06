<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->lexify('????????'),
            'sku' => $this->faker->lexify('????????'),
            'image_url' => $this->faker->imageUrl(),
            'active' => $this->faker->boolean(),
            'created_at' =>  now()->toDateTimeString(),
            'updated_at' =>  now()->toDateTimeString(),
        ];
    }
}
