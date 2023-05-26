<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTariffConfirmColumnOnConnectionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_reports', function (Blueprint $table) {
            $table->boolean('is_tariff_overlap_confirmed')->default(false)
            ->comment("true when tariff overlap confirmed, else false");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connection_reports', function (Blueprint $table) {
            $table->dropColumn('is_tariff_overlap_confirmed');
        });
    }
}
