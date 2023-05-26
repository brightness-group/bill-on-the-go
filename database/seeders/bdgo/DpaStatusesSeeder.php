<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DpaStatus;
use Illuminate\Support\Facades\DB;

class DpaStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DpaStatus::truncate();

        $now = now();

        $types = [
            [
                'status' => "-",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'status' => "Aktiv",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'status' => "Inaktiv",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'status' => "In Planung",
                'customer_type_id' => "1",
                'created_at' => $now
            ]
        ];

        DpaStatus::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
