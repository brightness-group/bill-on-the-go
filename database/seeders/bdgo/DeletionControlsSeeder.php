<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DeletionControl;
use Illuminate\Support\Facades\DB;

class DeletionControlsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DeletionControl::truncate();

        $now = now();

        $data = [
            ['control' => 'Keine Kontrolle', 'created_at' => $now],
            ['control' => 'Regelmäßige stichprobenartige Kontrolle', 'created_at' => $now],
            ['control' => 'Regelmäßige vollständige Kontrolle', 'created_at' => $now],
            ['control' => 'Anlassbezogene stichprobenartige Kontrolle', 'created_at' => $now],
            ['control' => 'Anlassbezogene vollständige Kontrolle', 'created_at' => $now]
        ];

        DeletionControl::insert($data);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
