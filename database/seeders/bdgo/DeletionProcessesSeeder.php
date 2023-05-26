<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DeletionProcess;
use Illuminate\Support\Facades\DB;

class DeletionProcessesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DeletionProcess::truncate();

        $now = now();

        $data = [
            ['process' => 'Disposition', 'process_description' => '10 Jahre Aufbewahrung der steuerrelevanten Unterlagen nach § 147 AO', 'month' => '120', 'other' => '', 'template_id' => '1', 'department_category_id' => '10', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Ersthelfer Organisation', 'process_description' => 'bei jeder Veränderung der beteiligten Personen', 'month' => '0', 'other' => 'Bei Veränderung', 'template_id' => '2', 'department_category_id' => '10', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Barkasse / manuelle Kassenabrechnung', 'process_description' => 'nach handels  und steuerrechtichen Aufbewahrungspflichten', 'month' => '120', 'other' => '', 'template_id' => '3', 'department_category_id' => '13', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Debitorenbuchhaltung', 'process_description' => '10 Jahre zur Erfüllung gesetzlicher Aufbewahrungspflichten gemäß § 147 Abs. 3 AO', 'month' => '120', 'other' => '', 'template_id' => '4', 'department_category_id' => '13', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Elektronischer Zahlungsverkehr', 'process_description' => 'Speicherung gem. der gesetzlichen Aufbewahrungsfristen (GoBD)', 'month' => '120', 'other' => '', 'template_id' => '5', 'department_category_id' => '13', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'externe Zahlungsabwicklung', 'process_description' => 'gem. Vorgaben HGB', 'month' => '120', 'other' => '', 'template_id' => '6', 'department_category_id' => '13', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Mahn- und Vollstreckungswesen', 'process_description' => 'gesetzliche Aufbewahrungsfristen nach HGB', 'month' => '72', 'other' => '', 'template_id' => '7', 'department_category_id' => '13', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Auskunftsanfragen zum Datenschutz', 'process_description' => '1 Jahr nach Beauskunftung', 'month' => '12', 'other' => '', 'template_id' => '8', 'department_category_id' => '11', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Auskunftsverfahren an Betroffene', 'process_description' => '1 Jahr nach Beauskunftung', 'month' => '12', 'other' => '', 'template_id' => '9', 'department_category_id' => '11', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Datenschutzschulungen', 'process_description' => '1 Jahr nach Beendigung des Beschäftigungsverhältnisses', 'month' => '12', 'other' => '', 'template_id' => '10', 'department_category_id' => '11', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Datenschutzschulungen', 'process_description' => '1 Jahr nach Beendigung des Beschäftigungsverhältnisses', 'month' => '12', 'other' => '', 'template_id' => '11', 'department_category_id' => '11', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Datenschutzschulungen', 'process_description' => '1 Jahr nach Beendigung des Beschäftigungsverhältnisses', 'month' => '12', 'other' => '', 'template_id' => '12', 'department_category_id' => '11', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Arbeitsschutz Ersthelfer', 'process_description' => 'bei jeder Veränderung der beteiligten Personen', 'month' => '0', 'other' => 'Bei Veränderung', 'template_id' => '13', 'department_category_id' => '1', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Arbeitsschutz Sicherheitsbeauftragte', 'process_description' => 'bei jeder Veränderung der beteiligten Personen', 'month' => '0', 'other' => 'Bei Veränderung', 'template_id' => '14', 'department_category_id' => '1', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Barkasse / Manuelle Kassenabrechnung', 'process_description' => 'nach handels- und steuerrechtichen Aufbewahrungspflichten', 'month' => '120', 'other' => '', 'template_id' => '15', 'department_category_id' => '1', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'BewerberInnenauswahl, manuell geführt', 'process_description' => '6 Monate nach Nichteinstellung, sonst Übernahme in die Personalakte', 'month' => '6', 'other' => '', 'template_id' => '16', 'department_category_id' => '1', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Buchhaltung/Elektronischer Zahlungsverkehr', 'process_description' => '10 Jahre nach Jahresabschluss gemäß Abgabenordnung', 'month' => '120', 'other' => '', 'template_id' => '17', 'department_category_id' => '1', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Grabnutzungsverwaltung', 'process_description' => '10 Jahre gesetzliche Aufbewahrungsfrist', 'month' => '120', 'other' => '', 'template_id' => '18', 'department_category_id' => '1', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Personalakte', 'process_description' => '3 Jahre nach Beendigung des Arbeitsverhältnisses', 'month' => '36', 'other' => '', 'template_id' => '19', 'department_category_id' => '1', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Personendaten ', 'process_description' => '3 Jahre nach Beendigung des Beschäftigungsverhältnisses oder nach Ablauf handels- und steuerrechtlicher Aufbewahrungspflichten', 'month' => '36', 'other' => '', 'template_id' => '20', 'department_category_id' => '1', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Schlüsselverwaltung', 'process_description' => 'Nutzerdaten 3 Monate nach Rückgabe', 'month' => '3', 'other' => '', 'template_id' => '21', 'department_category_id' => '1', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Facility Management', 'process_description' => 'Daten über Wartungs  und Pflegearbeiten werden nach 4 Jahren gelöscht.', 'month' => '48', 'other' => '', 'template_id' => '22', 'department_category_id' => '26', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Datenträgerentsorgung', 'process_description' => '2 Jahre Aufbewahrung der Löschungsprotokolle und Entsorgungsbestätigungen', 'month' => '24', 'other' => '', 'template_id' => '23', 'department_category_id' => '73', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Dokumentenmanagement', 'process_description' => 'nach Ablauf von handels , steuer  sozialversicherungsrechtlichen Aufbewahrungsfristen', 'month' => '120', 'other' => '', 'template_id' => '24', 'department_category_id' => '73', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Barkasse / Manuelle Kassenabrechnung', 'process_description' => 'nach handels- und steuerrechtichen Aufbewahrungspflichten', 'month' => '120', 'other' => '', 'template_id' => '25', 'department_category_id' => '2', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Dienstplanung', 'process_description' => '1 Jahr nach Jahresabschluss', 'month' => '12', 'other' => '', 'template_id' => '26', 'department_category_id' => '2', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Telefonverzeichnis/liste', 'process_description' => '1 Monat nach Ausscheiden des/der Mitarbeitenden', 'month' => '1', 'other' => '', 'template_id' => '27', 'department_category_id' => '2', 'customer_type_id' => '2', 'created_at' => $now],
            ['process' => 'Newsletterversand', 'process_description' => 'sofort nach Widerruf durch den Besteller', 'month' => '0', 'other' => 'sofort nach Widerruf', 'template_id' => '28', 'department_category_id' => '33', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => '2G o. 3G am Arbeitsplatz', 'process_description' => 'Die auf Grundlage von § 28b Abs. 3 Satz 3 IfSG (rechtmäßig) erhobenen Daten sind spätestens am Ende des sechsten Monats nach ihrer Erhebung zu löschen.', 'month' => '6', 'other' => '§ 28 Abs. 3 Satz 9 Halbsatz 1 IfSG', 'template_id' => '29', 'department_category_id' => '37', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Dienstplanung', 'process_description' => '1 Jahr nach Jahresabschluss', 'month' => '12', 'other' => '', 'template_id' => '30', 'department_category_id' => '37', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Dienstreisenplanung', 'process_description' => 'Reisekosten gem. gesetzlicher Aufbewahrungspflichten', 'month' => '0', 'other' => '', 'template_id' => '31', 'department_category_id' => '37', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Elektronische Zeiterfassung', 'process_description' => 'gem. der gesetzlichen Aufbewahrungsfristen', 'month' => '0', 'other' => 'ArbZG', 'template_id' => '32', 'department_category_id' => '37', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Fahrtkostenabrechnung', 'process_description' => 'gesetzliche Aufbewahrungsfristen nach Steuerrecht', 'month' => '0', 'other' => '', 'template_id' => '33', 'department_category_id' => '37', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Datenbank Einwilligungen', 'process_description' => '3 Monate nach Beendigung des Beschäftigungsverhältnisses oder nach Wideruf der Einwilligung', 'month' => '3', 'other' => '', 'template_id' => '34', 'department_category_id' => '41', 'customer_type_id' => '1', 'created_at' => $now],
            ['process' => 'Fahrzeugkostenabrechnung', 'process_description' => 'nach Ablauf von handels  und steuerrechtlichen Aufbewahrungspflichten', 'month' => '0', 'other' => '', 'template_id' => '35', 'department_category_id' => '47', 'customer_type_id' => '1', 'created_at' => $now]
        ];

        DeletionProcess::insert($data);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
