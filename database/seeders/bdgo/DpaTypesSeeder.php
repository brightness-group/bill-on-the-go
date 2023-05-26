<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DpaType;
use Illuminate\Support\Facades\DB;

class DpaTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DpaType::truncate();

        $now = now();

        $types = [
            [
                'type' => "-",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'type' => "Auftragsverarbeiter",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'type' => "gemeinsame Verantwortlichkeit",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'type' => "Datenverantwortlicher",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'type' => "Sonstige Dienstleister (keine Auftragsverarbeiter)",
                'customer_type_id' => "1",
                'created_at' => $now
            ]
        ];

        DpaType::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
