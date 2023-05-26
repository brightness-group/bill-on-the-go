<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\AffectedInformation;
use Illuminate\Support\Facades\DB;

class AffectedInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        AffectedInformation::truncate();

        $now = now();

        $types = [
            ['name' => "Individuell (z.B. per E-Mail)", 'customer_type_id' => '1', 'created_at' => $now],
            ['name' => "Datenschutzerklärung", 'customer_type_id' => '1', 'created_at' => $now],
            ['name' => "Datenschutzhinweis bei Vertragsabschluss", 'customer_type_id' => '1', 'created_at' => $now],
            ['name' => "Datenschutzhinweis bei Vertragsunterzeichnung", 'customer_type_id' => '1', 'created_at' => $now],
            ['name' => "Datenschutzhinweis über Beschäftigungsdaten", 'customer_type_id' => '1', 'created_at' => $now]
        ];

        AffectedInformation::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
