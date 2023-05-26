<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DpaCompany;
use Illuminate\Support\Facades\DB;

class DpaCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DpaCompany::truncate();

        $now = now();

        $types = [
            [
                'company'           => "Hetzner Online GmbH",
                'category'          => "Webhosting",
                'street'            => "Industriestr. 25",
                'postcode'          => "91710",
                'location'          => "Gunzenhausen",
                'email'             => "info@hetzner.com",
                'telephone'         => "+49 (0)9831 505-0",
                'website'           => "https://www.hetzner.com",
                'dpa_type_id'       => "2",
                'dpa_category_id'   => "21",
                'customer_type_id'  => "1",
                'created_at'        => $now
            ],
            [
                'company'           => "IONOS SE",
                'category'          => "Webhosting",
                'street'            => "Elgendorfer Str. 57",
                'postcode'          => "56410",
                'location'          => "Montabaur",
                'email'             => "info@ionos.de",
                'telephone'         => "49 (0) 721 170 555",
                'website'           => "https://www.ionos.de/",
                'dpa_type_id'       => "2",
                'dpa_category_id'   => "21",
                'customer_type_id'  => "1",
                'created_at'        => $now
            ],
            [
                'company'           => "webgo GmbH",
                'category'          => "Webhosting",
                'street'            => "Heidenkampsweg 81",
                'postcode'          => "20097",
                'location'          => "Hamburg",
                'email'             => "info@webgo.de",
                'telephone'         => "040 -605 900 399",
                'website'           => "www.webgo.de",
                'dpa_type_id'       => "2",
                'dpa_category_id'   => "21",
                'customer_type_id'  => "1",
                'created_at'        => $now
            ],
            [
                'company'           => "Microsoft Deutschland GmbH",
                'category'          => "Cloud-Anbieter",
                'street'            => "Walter-Gropius-Straße 5",
                'postcode'          => "80807",
                'location'          => "München",
                'email'             => "-",
                'telephone'         => "+49 89 31 76 0",
                'website'           => "https://www.microsoft.com/de-de/contact.aspx",
                'dpa_type_id'       => "2",
                'dpa_category_id'   => "5",
                'customer_type_id'  => "1",
                'created_at'        => $now
            ],
            [
                'company'           => "Haufe-Lexware GmbH & Co. KG",
                'category'          => "Externe Buchhaltung",
                'street'            => "Munzinger Straße 9",
                'postcode'          => "79111",
                'location'          => "Freiburg",
                'email'             => "info@lexoffice.de",
                'telephone'         => "0800 3000 777",
                'website'           => "https://www.lexoffice.de/",
                'dpa_type_id'       => "2",
                'dpa_category_id'   => "7",
                'customer_type_id'  => "1",
                'created_at'        => $now
            ],
            [
                'company'           => "DATEV eG, Nürnberg",
                'category'          => "Externe Buchhaltung",
                'street'            => "Paumgartnerstr. 6 - 14",
                'postcode'          => "90429",
                'location'          => "Nürnberg",
                'email'             => "info@datev.de",
                'telephone'         => "+49 911 319-0",
                'website'           => "https://www.datev.de/",
                'dpa_type_id'       => "2",
                'dpa_category_id'   => "7",
                'customer_type_id'  => "1",
                'created_at'        => $now
            ],
            [
                'company'           => "Zoho Corporation GmbH",
                'category'          => "Externes CRM",
                'street'            => "Trinkausstr. 7",
                'postcode'          => "40213",
                'location'          => "Düsseldorf",
                'email'             => "Zoho.Deutschland@eu.zohocorp.com",
                'telephone'         => "+49 8000229966",
                'website'           => "https://www.zoho.com/de",
                'dpa_type_id'       => "2",
                'dpa_category_id'   => "8",
                'customer_type_id'  => "1",
                'created_at'        => $now
            ],
            [
                'company'           => "LinkedIn Germany GmbH",
                'category'          => "Social Media Anbieter",
                'street'            => "Sendlinger Str. 12",
                'postcode'          => "80331",
                'location'          => "München",
                'email'             => NULL,
                'telephone'         => NULL,
                'website'           => NULL,
                'dpa_type_id'       => "2",
                'dpa_category_id'   => "17",
                'customer_type_id'  => "1",
                'created_at'        => $now
            ]
        ];

        DpaCompany::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
