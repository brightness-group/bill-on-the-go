<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaLegalBasisDeletionProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pa_legal_basis_deletion_processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deletion_process_id');
            $table->unsignedBigInteger('template_id');
            $table->text('process_description')->nullable();
            $table->string('retention_period')->nullable();
            $table->string('other_information')->nullable();
            $table->foreignId('processing_activity_legal_basis_id')->constrained()->index('pa_lb_deletion_process_pa_legal_basis_foreign')->cascadeOnDelete();
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
        Schema::dropIfExists('pa_legal_basis_deletion_processes');
    }
}
