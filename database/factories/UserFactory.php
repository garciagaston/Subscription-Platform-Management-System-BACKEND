<?php

namespace Database\Factories;

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
        $name = $this->faker->firstName() . ' ' . $this->faker->lastName();
        return [
            'name' => $name,
            'email' => trim(strtolower(str_replace(' ', '.', $name) . $this->faker->numerify('.###') . '@fake.com')),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
