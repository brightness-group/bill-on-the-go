<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTopFiveCustomersColumnToDashboardWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashboard_widgets', function (Blueprint $table) {
            $table->json('top_five_customers')->nullable()->after('turnover_widget');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dashboard_widgets', function (Blueprint $table) {
            $table->dropColumn('top_five_customers');
        });
    }
}
