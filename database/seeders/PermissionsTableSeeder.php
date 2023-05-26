<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //permissions list
        Permission::create(['name' => 'login']);

        Permission::create(['name' => 'create_system_users']);
        Permission::create(['name' => 'edit_system_users']);
        Permission::create(['name' => 'destroy_system_users']);

        Permission::create(['name' => 'create_company']);
        Permission::create(['name' => 'edit_company']);
        Permission::create(['name' => 'destroy_company']);

        Permission::create(['name' => 'create_users_company']);
        Permission::create(['name' => 'edit_users_company']);
        Permission::create(['name' => 'destroy_users_company']);

        //roles list
        $admin = Role::create(['name' => 'Admin']);
        $user = Role::create(['name' => 'User']);

        $admin->givePermissionTo([

            'login',

            'create_system_users',
            'edit_system_users',
            'destroy_system_users',

            'create_company',
            'edit_company',
            'destroy_company',

            'create_users_company',
            'edit_users_company',
            'destroy_users_company',

        ]);

    }
}
