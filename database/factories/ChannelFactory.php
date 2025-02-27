<?php

namespace Database\Factories;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    public function definition(): array
    {
        $name = $this->faker->lexify('???????????');

        return [
            'name' => $name,
            'description' => $this->faker->text(100),
            'image_url' => $this->faker->imageUrl(),
            'lineup_id' => $this->faker->numberBetween(1, 100),
            'call_sign' => strtoupper($name),
            'active' => $this->faker->boolean(),
            'display_order' => $this->faker->numberBetween(1, 100),
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
            'deleted_at' => null,
        ];
    }
}
