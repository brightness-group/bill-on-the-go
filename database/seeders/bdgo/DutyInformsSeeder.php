<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DutyInform;
use Illuminate\Support\Facades\DB;

class DutyInformsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DutyInform::truncate();

        $now = now();

        $data = [
            ['inform' => 'Nicht erforderlich, da Information vorliegt', 'customer_type_id' => '1', 'created_at' => $now],
            ['inform' => 'Nicht erforderlich, da Einwilligung vorliegt', 'customer_type_id' => '1', 'created_at' => $now],
            ['inform' => 'Nicht erforderlich, da Einwilligung und Information vorliegt', 'customer_type_id' => '1', 'created_at' => $now],
            ['inform' => 'Sonstiges', 'customer_type_id' => '1', 'created_at' => $now]
        ];

        DutyInform::insert($data);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
