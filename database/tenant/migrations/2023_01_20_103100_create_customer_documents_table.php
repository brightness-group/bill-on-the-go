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
        
        Schema::create('customer_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('file');
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
