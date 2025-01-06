<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'photo' => 'uploads/user/avater/_MG_82041_1707802961_65cb0151779a8.jpg',
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone' => fake()->bothify('880-1####-#####'),
            'birthday' => fake()->date(),
            'gender' => 'male',
        ])->assignRole('super-admin');
    }
}
