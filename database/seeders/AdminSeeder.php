<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\User;

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
        User::first()->assignRole('Super Admin');
    }
}
