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
        Schema::table('pa_legal_basis_deletion_processes', function (Blueprint $table) {
            $table->dropColumn('deletion_process_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pa_legal_basis_deletion_processes', function (Blueprint $table) {
            $table->unsignedBigInteger('deletion_process_id');
        });
    }
};
