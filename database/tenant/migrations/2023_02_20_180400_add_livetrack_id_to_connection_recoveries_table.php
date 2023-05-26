<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLivetrackIdToConnectionRecoveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('connection_recoveries', function (Blueprint $table) {
            $table->unsignedBigInteger('livetrack_id')->nullable();
            $table->foreign('livetrack_id')->references('id')->on('livetracks')->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('connection_recoveries', function (Blueprint $table) {
            $table->dropForeign(['livetrack_id']);
            $table->dropColumn('livetrack_id');
        });

        Schema::enableForeignKeyConstraints();
    }
}
