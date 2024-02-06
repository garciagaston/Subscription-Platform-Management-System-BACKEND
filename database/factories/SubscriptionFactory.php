<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-2 month', '+1 week')->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($startDate)->addMonth()->toDateTimeString();
        return [
            'user_id' => optional(User::inRandomOrder()->first())->id ?? User::factory()->create()->id,
            'package_id' => optional(Package::inRandomOrder()->first())->id ?? Package::factory()->create()->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'created_at' =>  now()->toDateTimeString(),
            'updated_at' =>  now()->toDateTimeString(),
            'deleted_at' =>  null,
        ];
    }
}
