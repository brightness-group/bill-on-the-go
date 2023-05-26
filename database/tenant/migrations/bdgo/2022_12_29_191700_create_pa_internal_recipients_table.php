<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaInternalRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pa_internal_recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('internal_recipient_id');
            $table->foreignId('processing_activity_internal_recipient_id')->constrained()->index('pa_ir_pa_internal_recipient_foreign')->cascadeOnDelete();
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
        Schema::dropIfExists('pa_internal_recipients');
    }
}
