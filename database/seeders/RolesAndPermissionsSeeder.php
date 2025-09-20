<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/** Defines roles & permissions for the HR system. */
class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $perms = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage reporting lines',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        $map = [
            'user'        => ['view users'],
            'leader'      => ['view users'],
            'manager'     => ['view users','edit users','manage reporting lines'],
            'admin'       => ['view users','create users','edit users','delete users','manage reporting lines'],
            'super-admin' => $perms,
        ];

        foreach ($map as $role => $grants) {
            $r = Role::firstOrCreate(['name' => $role]);
            $r->syncPermissions($grants);
        }
    }
}