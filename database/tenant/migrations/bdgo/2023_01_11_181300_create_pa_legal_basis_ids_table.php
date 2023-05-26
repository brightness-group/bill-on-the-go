<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaLegalBasisIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pa_legal_basis_ids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legal_basis_id');
            $table->foreignId('processing_activity_legal_basis_id')->constrained()->index('pa_lb_deletion_process_pa_legal_basis_id_foreign')->cascadeOnDelete();
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
        Schema::dropIfExists('pa_legal_basis_templates');
    }
}
