<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('tariff_name')->unique();
            $table->string('selected_days');
            $table->dateTime('archieved_date_time')->nullable();
            $table->string('initial_time');
            $table->string('end_time');
            $table->double('price', 8, 2);
            $table->string('interval');
            $table->boolean('attempt')->default(0);
            $table->boolean('global')->default(0);
            $table->string('tariff_state')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariffs');
    }
}
