<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\Template;
use Illuminate\Support\Facades\DB;

class TemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Template::truncate();

        $now = now();

        $types = [
            [
                'name' => "Disposition",
                'description' => "Einkauf von für die Produktion benötigten Materialien",
                'template_bdgo_gid' => "1",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Ersthelfer Organisation",
                'description' => "Organisation, Ausbildung und Dokumentation der Ersthelfer, öffentlicher Aushang",
                'template_bdgo_gid' => "1",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Barkasse / Manuelle Kassenabrechnung",
                'description' => "Durchführung von Bar-, Ein- und -Auszahlungen",
                'template_bdgo_gid' => "2",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Debitorenbuchhaltung",
                'description' => "Buchführung der Kontokorrentbeziehungen zwischen dem eigenen Unternehmen und Gläubigern von Forderungen aus Lieferungen und Leistungen des Unternehmens",
                'template_bdgo_gid' => "2",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Elektronischer Zahlungsverkehr",
                'description' => "Durchführung des elektronischen Zahlungsverkehrs, Ausgleich von Zahlungsverpflichtungen",
                'template_bdgo_gid' => "2",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "externe Zahlungsabwicklung",
                'description' => "Zahlungsabwicklung",
                'template_bdgo_gid' => "2",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Mahn- und Vollstreckungswesen",
                'description' => "Sicherstellung der Zahlungsleistung von Schuldern",
                'template_bdgo_gid' => "2",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Auskunftsanfragen zum Datenschutz",
                'description' => "Dokumentation über Anfragen zum Datenschutz von Betroffenen",
                'template_bdgo_gid' => "3",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Auskunftsverfahren an Betroffene",
                'description' => "Dokumentation über Anfragen zum Datenschutz von Betroffenen",
                'template_bdgo_gid' => "3",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Datenschutzschulungen",
                'description' => "Nachweis der durchgeführten Informationsveranstaltungen für den Datenschutz gem. BDSG und DS-GVO",
                'template_bdgo_gid' => "3",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Datenschutzschulungen",
                'description' => "Nachweis der durchgeführten Informationsveranstaltungen für den Datenschutz",
                'template_bdgo_gid' => "3",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Datenschutzschulungen",
                'description' => "Nachweis der durchgeführten Informationsveranstaltungen für den Datenschutz gem. BDSG und DS-GVO",
                'template_bdgo_gid' => "3",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Arbeitsschutz Ersthelfer",
                'description' => "Organisation, Ausbildung und Dokumentation",
                'template_bdgo_gid' => "4",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Arbeitsschutz Sicherheitsbeauftragte",
                'description' => "Organisation, Ausbildung und Dokumentation",
                'template_bdgo_gid' => "4",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Barkasse / Manuelle Kassenabrechnung",
                'description' => "Durchführung von Bar-, Ein-und -Auszahlungen, Kassenbuch, Quittungsbuch",
                'template_bdgo_gid' => "4",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "BewerberInnenauswahl, manuell geführt",
                'description' => "Auswahl geeigneter Bewerber zur Einstellung",
                'template_bdgo_gid' => "4",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Buchhaltung/Elektronischer Zahlungsverkehr",
                'description' => "Ausstellung Gebührenbescheid für die Grabnutzung",
                'template_bdgo_gid' => "4",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Grabnutzungsverwaltung",
                'description' => "Verwaltung der Gräber: Grabnutzungsverträge, Ausstellen des Gebührenbescheids",
                'template_bdgo_gid' => "4",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Personalakte",
                'description' => "manuell geführte Personalakte",
                'template_bdgo_gid' => "4",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Personendaten",
                'description' => "Kontaktaufnahme zu Praktikanten, Sozialstundenleistenden",
                'template_bdgo_gid' => "4",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Schlüsselverwaltung",
                'description' => "Dokumentation der Schlüsselverwaltung",
                'template_bdgo_gid' => "4",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Facility Management",
                'description' => "Verwaltung und Bewirtschaftung von Gebäuden sowie deren technische Anlagen und Einrichtungen",
                'template_bdgo_gid' => "5",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Datenträgerentsorgung",
                'description' => "Nutzung eines Dienstleisters zur Datenträgerentsorgung",
                'template_bdgo_gid' => "6",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Dokumentenmanagement",
                'description' => "Verwaltung und Archivierung aller geschäftsrelevanten, Dokumente, zentrale Verwaltung des Schriftverkehrs",
                'template_bdgo_gid' => "6",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Barkasse / Manuelle Kassenabrechnung",
                'description' => "Durchführung von Bar-, Ein-und -Auszahlungen, Kollektenbon, Kassenbuch, Quittungsbuch",
                'template_bdgo_gid' => "7",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Dienstplanung",
                'description' => "Übersicht der Mitarbeiter-Einsatzzeiten",
                'template_bdgo_gid' => "7",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Telefonverzeichnis/liste",
                'description' => "interne und externe telefonische Kommunikation",
                'template_bdgo_gid' => "7",
                'customer_type_id' => "2",
                'created_at' => $now
            ],
            [
                'name' => "Newsletterversand",
                'description' => "Versand von Informationen mittels Newsletter",
                'template_bdgo_gid' => "8",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "2G o. 3G am Arbeitsplatz",
                'description' => "Abfrage von Impf-, Genesenen- (Sero-) oder Teststatus bei Beschäftigten und Arbeitgeber",
                'template_bdgo_gid' => "9",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Dienstplanung",
                'description' => "Planung für Schichtbetieb, Mitarbeitereinsatz",
                'template_bdgo_gid' => "9",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Dienstreisenplanung",
                'description' => "Durchführung von dienstlich angeordneten Reisen",
                'template_bdgo_gid' => "9",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Elektronische Zeiterfassung",
                'description' => "Dokumentation der betrieblichen An- und Abwesenheitszeiten, Arbeitszeitkontrolle, Nachweis der persönlichen Arbeitszeiten der Beschäftigten",
                'template_bdgo_gid' => "9",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Fahrtkostenabrechnung",
                'description' => "monatliche Fahrtkostenzahlungen für Verträge im öffentlichen Nahverkehr",
                'template_bdgo_gid' => "9",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Datenbank Einwilligungen",
                'description' => "Dokumentation und Archivierung von Einwilligungen",
                'template_bdgo_gid' => "10",
                'customer_type_id' => "1",
                'created_at' => $now
            ],
            [
                'name' => "Fahrzeugkostenabrechnung",
                'description' => "Abrechnung der Fahrzeugkosten bei privater Nutzung",
                'template_bdgo_gid' => "11",
                'customer_type_id' => "1",
                'created_at' => $now
            ]
        ];

        Template::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
