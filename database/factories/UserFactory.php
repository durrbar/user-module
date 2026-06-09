<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\User\Models\User;

#[UseModel(User::class)]
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'avatar' => 'avatar_'.$this->faker->numberBetween(1, 12).'.jpg',
            'phone' => $this->faker->phoneNumber,
            'birthday' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'locale' => $this->faker->locale,
            'username' => $this->faker->unique()->userName,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
