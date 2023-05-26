<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsForTenantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appEdition = APP_EDITION;

        // Permissions list
        Permission::findOrCreate('login', 'tenant');
        Permission::findOrCreate('create_system_users', 'tenant');
        Permission::findOrCreate('edit_system_users', 'tenant');
        Permission::findOrCreate('destroy_system_users', 'tenant');
        Permission::findOrCreate('create_company', 'tenant');
        Permission::findOrCreate('edit_company', 'tenant');
        Permission::findOrCreate('destroy_company', 'tenant');
        Permission::findOrCreate('create_users_company', 'tenant');
        Permission::findOrCreate('edit_users_company', 'tenant');
        Permission::findOrCreate('destroy_users_company', 'tenant');
        Permission::findOrCreate('customer_general', 'tenant');
        Permission::findOrCreate('customer_billing', 'tenant');
        Permission::findOrCreate('customer_tariff', 'tenant');
        Permission::findOrCreate('customer_contact', 'tenant');
        Permission::findOrCreate('customer_device', 'tenant');
        Permission::findOrCreate('customer_statistic', 'tenant');
        Permission::findOrCreate('customer_documents', 'tenant');

        // Roles list
        $admin = Role::findOrCreate('Admin', 'tenant');
        $user = Role::findOrCreate('User', 'tenant');

        // Give permissions.
        $adminPermissions = [
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
        ];
        $admin->revokePermissionTo($adminPermissions);
        $admin->givePermissionTo($adminPermissions);

        $extraPermissions = [
            'customer_general',
            'customer_billing',
            'customer_contact',
            'customer_statistic',
            'customer_documents'
        ];

        if ($appEdition == 'billonthego') {
            $extraPermissions = array_merge($extraPermissions,
                ['customer_tariff', 'customer_device']
            );
        }

        $admin->revokePermissionTo($extraPermissions);
        $admin->givePermissionTo($extraPermissions);

        $user->revokePermissionTo($extraPermissions);
        $user->givePermissionTo($extraPermissions);
    }
}
