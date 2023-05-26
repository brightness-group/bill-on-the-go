<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DpaCategory;
use Illuminate\Support\Facades\DB;

class DpaCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DpaCategory::truncate();

        $now = now();

        $categories = [
            ['category' => 'Administrationsservice Serversysteme', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Akten- und Datenträgervernichtung', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Applikationsservice', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Arbeitskleidung', 'customer_type_id' => '3', 'created_at' => $now],
            ['category' => 'Cloud-Anbieter', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'E-Mail Anbieter', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Externe Buchhaltung', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Externes CRM', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Externe Lohnabrechnung', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Handelspartner', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Internetanbieter', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'IT-Service (EDV)', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Leasingfirma Digitalkopierer', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Letter-Service', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Newsletterversand', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Reinigungsfirma', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Social Media Anbieter', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Telekommunikationsanbieter', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Versandservice', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Webdesign', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Webhosting', 'customer_type_id' => '1', 'created_at' => $now]
        ];

        DpaCategory::insert($categories);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
