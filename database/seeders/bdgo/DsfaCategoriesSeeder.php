<?php

namespace Database\Seeders\bdgo;

use Illuminate\Database\Seeder;
use App\Models\Bdgo\DsfaCategory;
use Illuminate\Support\Facades\DB;

class DsfaCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DsfaCategory::truncate();

        $now = now();

        $data = [
            ['category' => '-', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Nein, nicht erforderlich', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Ja durchgeführt', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Information an die Aufsichtsbehörde - nein, nicht erforderlich', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Information an die Aufsichtsbehörde - ja, nicht erforderlich', 'customer_type_id' => '1', 'created_at' => $now],
            ['category' => 'Sonstiges', 'customer_type_id' => '1', 'created_at' => $now]
        ];

        DsfaCategory::insert($data);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
