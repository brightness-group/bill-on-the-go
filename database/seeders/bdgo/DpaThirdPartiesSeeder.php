<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DpaThirdParty;
use Illuminate\Support\Facades\DB;

class DpaThirdPartiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DpaThirdParty::truncate();

        $now = now();

        $data = [
            ['third_party' => 'Anbieter des Social Media Plugins', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Anbieter von Newsletterdiensten', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Anbieter von Trackingdiensten', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Anspruchsteller', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Aufsichtsbehörde', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Auftraggeber', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Aus  und Weiterbildungsunternehmen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Auslieferungsunternehmen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Autovermietungen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Bank  und Kreditinstitutionen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Berufsgenossenschaften', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Berufsgenossenschaften als Träger der gesetzlichen Unfallversicherung', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Betriebssportverband', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Buchungsplattformen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Dienstleister', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Dienstleister', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'diverse Cloud Dienstleister', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Bahn', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Empfänger', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Empfänger von E Mails', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'evtl. externer Dienstleister des Bewertungstools', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'evtl. Gesundheitsamt, Ordnungsamt', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Fernwartungsdienstleister', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Finanzämt', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Fluglinien', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Fotostudio', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Geldinstitute', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Gerichte', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'ggf. sonstige Dritte', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Hausverwalter', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Hoster', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Hotels', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Immigrationsbehörden', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Inkassounternehmen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Institute', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Interessenten', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Kommunikationsteilnehmer', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Krankenkassen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Krankenversicherung', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Kunden', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'KVB', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Leasinggeber', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Leasingunternehmen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Leiharbeitsfirmen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Lieferanten', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Makler', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Messenger Dienstleister', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Notare', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Pensionskassen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Personalvermittlungsagentur als Dienstleister', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'PVS', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Reisebuchungsunternehmen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Reisebüros', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Sonstige', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Sozialversicherungsträger', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Steuerbehörden', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Steuerberater', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Strafverfolgungsbehörden', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Support Dienstleister', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Teilnehmer', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Transportunternehmen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Transportunternehmen für mögliche Heimanlieferung', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Versicherer', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Versicherungen', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Versicherungsgesellschaften', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Vetriebspartner', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Werkstätten', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Wettkampf  und Turnierveranstalter', 'customer_type_id' => '6', 'created_at' => $now],
            ['third_party' => 'Wirtschaftsprüfer', 'customer_type_id' => '1', 'created_at' => $now],
            ['third_party' => 'Zustellungsunternehmen', 'customer_type_id' => '1', 'created_at' => $now]
        ];

        DpaThirdParty::insert($data);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
