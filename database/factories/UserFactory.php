<?php

namespace Database\Factories;

use App\Helpers\TestsHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        $name = trim($this->faker->firstName() . ' ' . $this->faker->lastName());

        return [
            'name' => $name,
            'email' => trim(strtolower(str_replace(' ', '.', $name) . $this->faker->numerify('.###') . '@fake.com')),
            'email_verified_at' => now(),
            'password' => static::$password ?? Hash::make(TestsHelper::PASSWORD),
            'remember_token' => Str::random(10),
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
            'deleted_at' => null,
        ];
    }
}
