<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaExternalRecipientDpaThirdPartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pa_external_recipient_dpa_third_parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dpa_third_party_id');
            $table->foreignId('processing_activity_external_recipient_id')->constrained()->index('pa_ir_third_party_pa_external_recipient_foreign')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pa_external_recipient_dpa_third_parties');
    }
}
