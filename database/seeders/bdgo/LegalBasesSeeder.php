<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\LegalBases;
use Illuminate\Support\Facades\DB;

class LegalBasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        LegalBases::truncate();

        $now = now();

        $data = [
            ['name' => 'Art. 6 Abs. 1 lit a (DSGVO)', 'customer_type_id' => '1', 'created_at' => $now],
            ['name' => 'Art. 6 Abs. 1 lit b (DSGVO)', 'customer_type_id' => '1', 'created_at' => $now],
            ['name' => 'Art. 6 Abs. 1 lit c (DSGVO)', 'customer_type_id' => '1', 'created_at' => $now],
            ['name' => 'Art. 6 Abs. 1 lit f (DSGVO)', 'customer_type_id' => '1', 'created_at' => $now],
            ['name' => 'Art. 6 Abs. 1 lit c (DSGVO)', 'customer_type_id' => '1', 'created_at' => $now],
            ['name' => '§ 6 Nr. 1 DSG-EKD i.V.m.', 'customer_type_id' => '2', 'created_at' => $now],
            ['name' => '§ 6 Nr. 1 DSG-EKD i.V.m.', 'customer_type_id' => '2', 'created_at' => $now],
            ['name' => '§ 49 DSG-EKD', 'customer_type_id' => '2', 'created_at' => $now],
            ['name' => '§ 19 ff DSG-EKD', 'customer_type_id' => '2', 'created_at' => $now],
            ['name' => '§ 6 Nr. 1 DSG-EKD', 'customer_type_id' => '2', 'created_at' => $now],
            ['name' => '§ 6 Nr. 2 DSG-EKD', 'customer_type_id' => '2', 'created_at' => $now],
            ['name' => '§ 6 Nr. 3 DSG-EKD', 'customer_type_id' => '2', 'created_at' => $now],
            ['name' => '§ 6 Nr. 4 DSG-EKD', 'customer_type_id' => '2', 'created_at' => $now],
            ['name' => '§ 6 Nr. 5 DSG-EKD', 'customer_type_id' => '2', 'created_at' => $now],
            ['name' => '§ 6 Nr. 6 DSG-EKD', 'customer_type_id' => '2', 'created_at' => $now],
            ['name' => 'Art. 6 Abs.1 lit. c DSGVO i.V.m. § 28b IfSG, § 23a Satz 1 IfSG', 'customer_type_id' => '1', 'created_at' => $now]
        ];

        LegalBases::insert($data);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
