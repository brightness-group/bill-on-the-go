<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DeletionType;
use Illuminate\Support\Facades\DB;

class DeletionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DeletionType::truncate();

        $now = now();

        $data = [
            ['type' => 'manuell', 'created_at' => $now],
            ['type' => 'automatisiert', 'created_at' => $now]
        ];

        DeletionType::insert($data);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
