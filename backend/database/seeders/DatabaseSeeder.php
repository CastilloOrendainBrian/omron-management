<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            SkinfoldProtocolSeeder::class,
            SkinfoldSiteSeeder::class,
        ]);
    }
}
