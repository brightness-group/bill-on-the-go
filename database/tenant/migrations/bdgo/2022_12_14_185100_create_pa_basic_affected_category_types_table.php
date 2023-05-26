<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaBasicAffectedCategoryTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pa_basic_affected_category_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affected_category_type_id');
            $table->foreignId('processing_activity_basic_id')->constrained()->index('pa_basic_affected_category_types_pa_basic_id_foreign')->cascadeOnDelete();
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
        Schema::dropIfExists('pa_basic_affected_category_types');
    }
}
