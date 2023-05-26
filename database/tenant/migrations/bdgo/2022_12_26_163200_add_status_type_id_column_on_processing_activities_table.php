<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processing_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('status_type_id')->nullable()->after('template_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('processing_activities', function (Blueprint $table) {
            $table->dropColumn('status_type_id');
        });
    }
};
