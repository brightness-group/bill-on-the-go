<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\TemplateGroup;
use Illuminate\Support\Facades\DB;

class TemplateGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        TemplateGroup::truncate();

        $now = now();

        $types = [
            ['name' => "Allgemeine Verwaltung", 'created_at' => $now],
            ['name' => "Buchhaltung", 'created_at' => $now],
            ['name' => "Datenschutzbeauftragter", 'created_at' => $now],
            ['name' => "Friedhof", 'created_at' => $now],
            ['name' => "Gebäude-, Sicherheitsmanagement", 'created_at' => $now],
            ['name' => "IT-Management", 'created_at' => $now],
            ['name' => "Kirchenbüro", 'created_at' => $now],
            ['name' => "Marketing, Vertrieb", 'created_at' => $now],
            ['name' => "Personalwesen", 'created_at' => $now],
            ['name' => "Rechtsabteilung", 'created_at' => $now],
            ['name' => "Warenwirtschaft, Logistik, Fuhrpark", 'created_at' => $now]
        ];

        TemplateGroup::insert($types);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
