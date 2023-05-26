<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaLegalBasisDeletionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pa_legal_basis_deletion_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deletion_type_id');
            $table->foreignId('processing_activity_legal_basis_id')->constrained()->index('pa_legal_basis_type_foreign')->cascadeOnDelete();
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
        Schema::dropIfExists('pa_legal_basis_deletion_types');
    }
}
