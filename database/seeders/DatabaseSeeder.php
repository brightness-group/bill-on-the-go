<?php

namespace Database\Seeders;

use App\Models\Tenant\Product;
use Database\Seeders\CreateSuperAdminUser;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Database\Seeders\CompanySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            CreateSuperAdminUser::class,
            CustomerTypesTableSeeder::class,
        ]);
    }
}
