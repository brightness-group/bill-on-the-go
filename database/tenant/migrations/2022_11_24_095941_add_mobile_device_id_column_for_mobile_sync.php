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
        // devices
        Schema::table('devices', function (Blueprint $table) {
            $table->string('mobile_id')->unique()->nullable();
        });

        // contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('mobile_id')->unique()->nullable();
        });

        // connection_reports
        Schema::table('connection_reports', function (Blueprint $table) {
            $table->string('mobile_id')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // devices
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('mobile_id');
        });

        // contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('mobile_id');
        });

        // connection_reports
        Schema::table('connection_reports', function (Blueprint $table) {
            $table->dropColumn('mobile_id');
        });
    }
};
