<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DepartmentCategory;
use Illuminate\Support\Facades\DB;

class DepartmentCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DepartmentCategory::truncate();

        $now = now();

        $types = [
            ['department' => 'Friedhof', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Kirchenbüro', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Gruppenbetreuer', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Pastor:in', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Kantorei', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Kita-Leitung', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'KGR', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'IT-Betreuung', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Personalwesen', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Allgemeine Verwaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Datenschutzbeauftragter', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Geschäftsführung', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Buchhaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Finanzbuchhaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Assistenz der Geschäftsführung', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Betriebsrat', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Personalrat', 'customer_type_id' => '5', 'created_at' => $now],
            ['department' => 'Personalrat', 'customer_type_id' => '4', 'created_at' => $now],
            ['department' => 'Mitarbeitervertretung', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Empfang', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Finanzen / FiBu', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'FM-B', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'FM-LS', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Meldewesen', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Archiv', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Gebäude-, Sicherheitsmanagement', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Gesundheitswesen', 'customer_type_id' => '7', 'created_at' => $now],
            ['department' => 'Grüner Hahn', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'IT', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Kirchenkreisverwaltung', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Kundendienst', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Kundenservice', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Marketing', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Vertrieb', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Öffentlichkeitsarbeit', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Personal', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Personalwesen', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Schwerbehindertenvertretung', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Produktion', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Recht / Verwaltung', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Rechtsabteilung', 'customer_type_id' => '0', 'created_at' => $now],
            ['department' => 'Sekretariat Pröpste', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Sonstige Dienstleistung', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Vereinswesen', 'customer_type_id' => '6', 'created_at' => $now],
            ['department' => 'Warenwirtschaft', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Logistik', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Fuhrpark', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Erziehungsleitung', 'customer_type_id' => '7', 'created_at' => $now],
            ['department' => 'Heimverwaltung', 'customer_type_id' => '7', 'created_at' => $now],
            ['department' => 'Sekretariat', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Sekretariat', 'customer_type_id' => '4', 'created_at' => $now],
            ['department' => 'Erzieher', 'customer_type_id' => '7', 'created_at' => $now],
            ['department' => 'Lerngruppenleitung', 'customer_type_id' => '7', 'created_at' => $now],
            ['department' => 'Psychologen', 'customer_type_id' => '7', 'created_at' => $now],
            ['department' => 'Mitarbeiter - IT', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Einrichtungsabhängig', 'customer_type_id' => '7', 'created_at' => $now],
            ['department' => 'Ausbildung', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Büchereiabteilung', 'customer_type_id' => '5', 'created_at' => $now],
            ['department' => 'EDV-Abteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Lektorat', 'customer_type_id' => '5', 'created_at' => $now],
            ['department' => 'Ausgewählte Mitarbeiter der jeweiligen Abteilung', 'customer_type_id' => '5', 'created_at' => $now],
            ['department' => 'Fahrdienst', 'customer_type_id' => '5', 'created_at' => $now],
            ['department' => 'Hausverwaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Katalogabteilung', 'customer_type_id' => '5', 'created_at' => $now],
            ['department' => 'Leihverkehrs- und Ergänzungbibliothek ', 'customer_type_id' => '5', 'created_at' => $now],
            ['department' => 'Pastor:innen', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Sachgebiet Meldewesen', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Sachgebiet Friedhof', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Verwaltungsleitung', 'customer_type_id' => '2', 'created_at' => $now],
            ['department' => 'Facility-Management ', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Wahlvorstand', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'Controlling', 'customer_type_id' => '1', 'created_at' => $now],
            ['department' => 'IT-Management', 'customer_type_id' => '1', 'created_at' => $now]
        ];

        DepartmentCategory::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
