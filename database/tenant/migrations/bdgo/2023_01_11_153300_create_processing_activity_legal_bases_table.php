<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessingActivityLegalBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processing_activity_legal_bases', function (Blueprint $table) {
            $table->id();
            $table->string('legal_basis_information')->nullable();
            $table->foreignId('processing_activity_id')->constrained()->index('pa_legal_bases_pa_foreign');
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
        Schema::dropIfExists('processing_activity_legal_bases');
    }
}
