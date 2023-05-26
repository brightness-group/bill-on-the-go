<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DeletionLog;
use Illuminate\Support\Facades\DB;

class DeletionLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DeletionLog::truncate();

        $now = now();

        $data = [
            ['log' => 'Keine Protokollierung', 'created_at' => $now],
            ['log' => 'Systemseitige Protokollierung', 'created_at' => $now],
            ['log' => 'Papierhaftes Protokoll', 'created_at' => $now]
        ];

        DeletionLog::insert($data);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
