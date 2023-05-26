<?php

namespace Database\Seeders\Bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\LawType;
use Illuminate\Support\Facades\DB;

class LawTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        LawType::truncate();

        $types = [
            [
                'law_type' => "Art. 15 DSGVO",
                'request_type_id' => "1",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'law_type' => "§ 19 DSG-EKD",
                'request_type_id' => "1",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'law_type' => "Art. 15 DSGVO,  SGB",
                'request_type_id' => "1",
                'customer_type_id' => "7",
                'created_at' => $now
            ],
            [
                'law_type' => "Art. 17 DSGVO",
                'request_type_id' => "3",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'law_type' => "§ 21 DSG-EKD",
                'request_type_id' => "3",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'law_type' => "Art. 16 DSGVO",
                'request_type_id' => "2",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'law_type' => "§ 20 DSG-EKD",
                'request_type_id' => "2",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'law_type' => "Art. 18 DSGVO",
                'request_type_id' => "4",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'law_type' => "§ 22 DSG-EKD",
                'request_type_id' => "4",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'law_type' => "Art. 21 DSGVO",
                'request_type_id' => "5",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'law_type' => "§ 25 DSG-EKD",
                'request_type_id' => "5",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'law_type' => "Art. 20 DSGVO",
                'request_type_id' => "6",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'law_type' => "§ 24 DSG-EKD",
                'request_type_id' => "6",
                'customer_type_id' => "2",
                'created_at' => $now
            ]
        ];

        LawType::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
