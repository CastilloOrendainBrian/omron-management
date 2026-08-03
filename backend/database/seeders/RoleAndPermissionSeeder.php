<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'view-measurements',
            'create-measurements',
            'update-measurements',
            'delete-measurements',
            'view-all-measurements',
            'manage-users',
            'view-reports',
            'create-reports',
            'manage-goals',
            'view-goals',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $superAdmin->syncPermissions(Permission::all());

        $admin->syncPermissions([
            'view-all-measurements',
            'manage-users',
            'view-reports',
            'create-reports',
        ]);

        $user->syncPermissions([
            'view-measurements',
            'create-measurements',
            'update-measurements',
            'delete-measurements',
            'manage-goals',
            'view-goals',
        ]);
    }
}
