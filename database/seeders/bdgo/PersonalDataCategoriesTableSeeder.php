<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\PersonalDataCategory;
use Illuminate\Support\Facades\DB;

class PersonalDataCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        PersonalDataCategory::truncate();

        $now = now();

        $data = [
            [
                'name' => 'Abteilung',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'An- und Abwesenheitszeiten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Angehörigenname und -adresse, Gebührenzahlername und -adresse ',
                'customer_type_id' => '2',
                'created_at' => $now
            ],
            [
                'name' => 'Bankdaten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Bankverbindungs- und Zahlungsinformationen',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Beschäftigtendaten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Beschäftigtendaten von Kunden oder Lieferanten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Beschäftigtendaten, Ort, Datum, Inhalt, ggf. Arbeitsblätter, Teilnehmerlisten ggf. mit Unterschrift',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Beschäftigtendaten, Planungsdaten des zeitlichen Einsatzes',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Bewerberdaten, Bewerbungsunterlagen, Anschrift , Lebenslauf, Zeugnisse, Angaben zum beruflichen Werdegang, Führungszeugnis, Gesundheitszeugnis',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Datum',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Datum, Betrag, Grund, Name des Betroffenen, Unterschrift',
                'customer_type_id' => '2',
                'created_at' => $now
            ],
            [
                'name' => 'Dienstleistern',
                'customer_type_id' => '2',
                'created_at' => $now
            ],
            [
                'name' => 'Einwilligung des Bestellers (Opt-Out / Opt-In)',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'evtl. weitere Kontaktdaten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Fotos',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Kontaktdaten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Kundendaten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Lieferantendaten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Mitarbeitendendaten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Mitarbeitendendaten, Ort, Datum, Inhalt, ggf. Arbeitsblätter, Teilnehmerlisten ggf. mit Unterschrift',
                'customer_type_id' => '2',
                'created_at' => $now
            ],
            [
                'name' => 'Mitarbeitendendaten, Veränderungen persönlicher Verhältnisse',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Name',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Name, Adresse des Gebührenzahlers, Grund',
                'customer_type_id' => '2',
                'created_at' => $now
            ],
            [
                'name' => 'Name, Anschrift, Datum, Auskunftsersuchen, Beantwortung',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Planungsdaten des zeitlichen Einsatzes',
                'customer_type_id' => '2',
                'created_at' => $now
            ],
            [
                'name' => 'Quelle, Datum und Anlass der Bestellung',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Rechnungsdaten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Reisedaten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Schlüsselnutzer,  Aus- und Rückgabedaten, Schlüsselart',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'schriftliche Form der Einwilligungen',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Schriftverkehr',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Sonstige: Datum, Betrag, Grund, Name des Betroffenen, Unterschrift',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Stammdaten der Kunden',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Telefonnummer',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Telefonnummern von Mitarbeitenden',
                'customer_type_id' => '2',
                'created_at' => $now
            ],
            [
                'name' => 'Termindaten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Vertragsdaten',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Visabeantragung',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Vorname, Nachname, Impf-/Genesenen-/ und Teststatus, Datum des Ablaufs der Gültigkeit des Nachweises',
                'customer_type_id' => '1',
                'created_at' => $now
            ],
            [
                'name' => 'Zeitpunkt des Datenzugriffs',
                'customer_type_id' => '1',
                'created_at' => $now
            ]
        ];

        PersonalDataCategory::insert($data);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
