<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CreateSuperAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appEdition = APP_EDITION;

        $email = $appEdition == 'bdgo' ? 'admin@bdgo.app' : 'admin@billonthego.com';
        $superAdminUser = User::create([
            'name' => 'SuperAdmin',
            'email' => $email,
            'email_verified_at' => now(),
            'password' => '123456789',
            'remember_token' => Str::random(10),
        ]);
        $superAdminUser->assignRole(Role::findByName('Admin'));
    }
}
