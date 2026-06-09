<?php

declare(strict_types=1);

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
        $admin = User::firstOrCreate(
            ['email' => 'kidmax285@gmail.com'],
            [
                'name' => 'Kid Max',
                'password' => bcrypt('demo1234'),
            ]
        );

        if ($admin instanceof User) {
            $admin->assignRole('Super Admin');
        }
    }
}
