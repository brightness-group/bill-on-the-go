<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\RequestType;
use Illuminate\Support\Facades\DB;

class RequestTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        RequestType::truncate();

        $now = now();

        $types = [
            [
                'name' => "Auskunftsanfrage (Art. 15 DSGVO)",
                'law_type_id' => "1",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Auskunftsanfrage (§ 19 DSG-EKD)",
                'law_type_id' => "2",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Auskunftsanfrage (Art. 15 DSGVO, SGB)",
                'law_type_id' => "3",
                'customer_type_id' => "7",
                'created_at' => $now
            ],
            [
                'name' => "Löschanfrage (Art. 17 DSGVO)",
                'law_type_id' => "4",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Löschanfrage (§ 21 DSG-EKD)",
                'law_type_id' => "5",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Berichtigungsersuchen (Art. 16 DSGVO)",
                'law_type_id' => "6",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Berichtigungsersuchen (§ 20 DSG-EKD)",
                'law_type_id' => "7",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Einschränkungsersuchen (Art. 18 DSGVO)",
                'law_type_id' => "8",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Einschränkungsersuchen (§ 22 DSG-EKD)",
                'law_type_id' => "9",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Widerspruch (Art. 21 DSGVO)",
                'law_type_id' => "10",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Widerspruch (§ 25 DSG-EKD)",
                'law_type_id' => "11",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Datenübertragbarkeit (Art. 20 DSGVO)",
                'law_type_id' => "12",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Datenübertragbarkeit (§ 24 DSG-EKD)",
                'law_type_id' => "13",
                'customer_type_id' => "2",
                'created_at' => $now
            ]
        ];

        RequestType::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
