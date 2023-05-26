<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFileConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_connections', function (Blueprint $table) {
            $table->unsignedBigInteger('cont_id')->nullable();
            $table->foreign('cont_id')->references('id')->on('contacts')->nullOnDelete();
            $table->enum('contact_type', [1, 2, 3, 4, 5])->nullable();
            $table->unsignedBigInteger('tariff_id')->nullable();
            $table->foreign('tariff_id')->references('id')->on('tariffs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('file_connections', function (Blueprint $table) {
            $table->dropForeign(['tariff_id']);
            $table->dropForeign(['cont_id']);
            $table->dropColumn(['tariff_id', 'contact_type', 'cont_id']);
        });
        Schema::enableForeignKeyConstraints();
    }
}
