<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\AffectedCategoryType;
use Illuminate\Support\Facades\DB;

class AffectedCategoryTypesTableSeeder extends Seeder
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

        AffectedCategoryType::truncate();

        $types = [
            ['name' => "Beschäftigte", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Bewerber", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Dienstleister", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Ehrenamtliche", 'customer_type_id' => "2", 'created_at' => $now],
            ['name' => "Erziehungsberechtigte", 'customer_type_id' => "2", 'created_at' => $now],
            ['name' => "Freiwillige", 'customer_type_id' => "2", 'created_at' => $now],
            ['name' => "Gremien", 'customer_type_id' => "2", 'created_at' => $now],
            ['name' => "Interessenten", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "KGR Mitglieder", 'customer_type_id' => "2", 'created_at' => $now],
            ['name' => "Kunden", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Lieferanten", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Mitglieder", 'customer_type_id' => "2", 'created_at' => $now],
            ['name' => "Nutzer", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Partnerunternehmen und deren Mitarbeiter", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Patientendaten", 'customer_type_id' => "7", 'created_at' => $now],
            ['name' => "Patientendaten", 'customer_type_id' => "8", 'created_at' => $now],
            ['name' => "Webseitenbesucher", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Beschäftigte von Kunden oder Lieferanten", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Alle Personen", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Mitarbeitende", 'customer_type_id' => "2", 'created_at' => $now],
            ['name' => "Gebührenzahler", 'customer_type_id' => "2", 'created_at' => $now],
            ['name' => "Beschäftigte von Lieferanten oder Dienstleistern", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Angehörige von Mitgliedern, Gebührenzahler", 'customer_type_id' => "2", 'created_at' => $now],
            ['name' => "Praktikanten, Sozialstundenleistende", 'customer_type_id' => "2", 'created_at' => $now],
            ['name' => "Dritte", 'customer_type_id' => "1", 'created_at' => $now],
            ['name' => "Interessenten", 'customer_type_id' => "1", 'created_at' => $now]
        ];

        AffectedCategoryType::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
