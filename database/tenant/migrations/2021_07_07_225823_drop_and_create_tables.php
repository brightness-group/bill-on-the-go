<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAndCreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shared_users', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('anydesk_access_token_test');
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('alias')->nullable();
            $table->string('description')->nullable();
            $table->string('bdgogid')->nullable();
            $table->foreign('bdgogid')->references('bdgogid')->on('customers');
            $table->string('online_state')->default('Online');
            $table->timestamps();
        });

        Schema::table('connection_reports', function (Blueprint $table) {
            $table->dropColumn(['deviceid']);
            $table->string('device_id')->nullable()->after('username');
            $table->foreign('device_id')->references('id')->on('devices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
