<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\CustomerType;
use Illuminate\Support\Facades\DB;

class CustomerTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        CustomerType::truncate();

        $now = now();

        $types = [
            ['type' => "0", 'created_at' => $now],
            ['type' => "1", 'created_at' => $now],
            ['type' => "2", 'created_at' => $now],
            ['type' => "3", 'created_at' => $now],
            ['type' => "4", 'created_at' => $now],
            ['type' => "5", 'created_at' => $now],
            ['type' => "6", 'created_at' => $now],
            ['type' => "7", 'created_at' => $now]
        ];

        CustomerType::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
