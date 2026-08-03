<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $user = User::firstOrCreate(
            ['email' => 'root@mail.com'],
            [
                'name' => 'root',
                'password' => '1234567a',
            ],
        );

        if (! $user->hasRole('super-admin')) {
            $user->assignRole('super-admin');
        }
    }
}
