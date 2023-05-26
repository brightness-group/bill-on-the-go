<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\StatusType;
use Illuminate\Support\Facades\DB;

class StatusTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        StatusType::truncate();

        $now = now();

        $types = [
            ['status' => "New", 'created_at' => $now],
            ['status' => "In Progress", 'created_at' => $now],
            ['status' => "Done", 'created_at' => $now]
        ];

        StatusType::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
