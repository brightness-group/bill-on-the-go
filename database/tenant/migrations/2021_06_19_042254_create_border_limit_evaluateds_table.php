<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorderLimitEvaluatedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('border_limit_evaluateds', function (Blueprint $table) {
            $table->id();
            $table->string('connection_report_id');
            $table->foreign('connection_report_id')->references('id')->on('connection_reports');
            $table->integer('tariff_related');
            $table->integer('tariff_overlaped');
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
        Schema::dropIfExists('border_limit_evaluateds');
    }
}
