<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestAffectedCategoryTypeIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_affected_category_type_ids', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('affected_category_type_id')->unsigned();
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('requests');
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
        Schema::dropIfExists('request_affected_category_type_ids');
    }
}
