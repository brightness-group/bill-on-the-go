<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodAndPermanentFieldsToTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->boolean('permanent')->nullable()->default(false)->after('tariff_state');
            $table->dateTime('start_period')->nullable()->after('permanent');
            $table->dateTime('end_period')->nullable()->after('start_period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tariffs', function (Blueprint $table) {
            //
        });
    }
}
