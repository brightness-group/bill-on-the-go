<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaBasicPersonalDataCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pa_basic_personal_data_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_data_category_id');
            $table->foreignId('processing_activity_basic_id')->constrained()->index('pa_basic_personal_data_categories_pa_basic_id_foreign')->cascadeOnDelete();
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
        Schema::dropIfExists('pa_basic_personal_data_categories');
    }
}
