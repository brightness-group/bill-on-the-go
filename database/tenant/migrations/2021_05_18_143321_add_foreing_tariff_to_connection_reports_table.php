<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeingTariffToConnectionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('tariff_id')->nullable();
            $table->foreign('tariff_id')->references('id')->on('tariffs');
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
            //
        });
    }
}
