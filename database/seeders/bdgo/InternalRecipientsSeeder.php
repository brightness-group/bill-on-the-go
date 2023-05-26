<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\InternalRecipient;
use Illuminate\Support\Facades\DB;

class InternalRecipientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        InternalRecipient::truncate();

        $now = now();

        $data = [
            ['recipient' => '-', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Abonnentenverwaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Abteilungsleitung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'alle Abteilungen', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'alle Mitarbeitenden', 'customer_type_id' => '2', 'created_at' => $now],
            ['recipient' => 'alle Mitarbeitenden', 'customer_type_id' => '5', 'created_at' => $now],
            ['recipient' => 'Arbeitnehmervertretung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Auftragsbearbeitung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Beschäftigte', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Betreuer', 'customer_type_id' => '7', 'created_at' => $now],
            ['recipient' => 'betriebliche Organe', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Betriebsrat', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'betroffene Mitarbeiter', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Betroffene Personen', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Blog-Mitgliederverwaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Buchhaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Controlling', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Datenschutzbeauftragte', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Datenschutzbeauftragter', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Datenschutzverantwortlicher', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Disposition', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Einkauf', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Empfang', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Fachabteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Finanzbuchhaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Freelancer', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Friedhof', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Fuhrparkleitung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Geschäftsleitung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'ggf. alle Mitarbeitenden', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Gremien', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Gremienmitglieder', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Gruppen', 'customer_type_id' => '2', 'created_at' => $now],
            ['recipient' => 'Gruppenbetreuer', 'customer_type_id' => '2', 'created_at' => $now],
            ['recipient' => 'Gruppen', 'customer_type_id' => '7', 'created_at' => $now],
            ['recipient' => 'Gruppenbetreuer', 'customer_type_id' => '7', 'created_at' => $now],
            ['recipient' => 'Hausverwaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'intern', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'IT-Abeilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'IT-Administration', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Jugendbetreuer', 'customer_type_id' => '2', 'created_at' => $now],
            ['recipient' => 'Jugendbetreuer', 'customer_type_id' => '7', 'created_at' => $now],
            ['recipient' => 'Kantor', 'customer_type_id' => '2', 'created_at' => $now],
            ['recipient' => 'Kasse', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Kirchenbüro', 'customer_type_id' => '2', 'created_at' => $now],
            ['recipient' => 'Kirchenkreisverwaltung', 'customer_type_id' => '2', 'created_at' => $now],
            ['recipient' => 'Kita-Leitung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Kundenbetreuung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Kundendienst', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Kundenservice', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Lager', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Leitung', 'customer_type_id' => '2', 'created_at' => $now],
            ['recipient' => 'Leitung', 'customer_type_id' => '7', 'created_at' => $now],
            ['recipient' => 'Marketing', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Mitarbeiter der Finanzbuchhaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Mitarbeitervertretung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Outlooknutzer', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Parkplatzverwaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Personalabteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Personalrat', 'customer_type_id' => '5', 'created_at' => $now],
            ['recipient' => 'Rechtsabteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Reparaturabteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Restaurantverwaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Rezeption', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Schwerbehindertenvertretung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Serviceabteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Sicherheitsabteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Spartenleitung', 'customer_type_id' => '6', 'created_at' => $now],
            ['recipient' => 'Teilnehmer', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Teilnehmer am geschäftlichen E-Mail-Verfahren', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Trainer', 'customer_type_id' => '6', 'created_at' => $now],
            ['recipient' => 'Veranstaltungsorganisation', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'verantwortliche Abteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Vereinsführung', 'customer_type_id' => '6', 'created_at' => $now],
            ['recipient' => 'Versandabteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Vertriebsabteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Verwaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Vollmachtsberechtigte für Barkasse', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Vorgesetzte', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Vorstand', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Warenwirtschaft', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Zollabteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Zugriffsberechtigte', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Zuständige Abteilung', 'customer_type_id' => '1', 'created_at' => $now],
            ['recipient' => 'Zuständige Sachbearbeitung', 'customer_type_id' => '1', 'created_at' => $now]
        ];

        InternalRecipient::insert($data);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
