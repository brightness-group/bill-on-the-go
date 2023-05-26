<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActualOperationTimeColumnsOnCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('curr_month_actual_operate_time')->nullable();
            $table->string('last_month_actual_operate_time')->nullable();
            $table->string('last_quarter_actual_operate_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('curr_month_actual_operate_time');
            $table->dropColumn('last_month_actual_operate_time');
            $table->dropColumn('last_quarter_actual_operate_time');
        });
    }
}
