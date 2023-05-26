<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessingActivityBasicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processing_activity_basics', function (Blueprint $table) {
            $table->id();
            $table->string('name_of_processing')->nullable();
            $table->unsignedBigInteger('status_type_id')->nullable();
            $table->text('purpose_of_processing')->nullable();
            $table->enum('processing_analog', ['0', '1'])->default('0')->comment('0: No, 1: Yes');
            $table->enum('processing_digital', ['0', '1'])->default('0')->comment('0: No, 1: Yes');
            $table->enum('data_is_processed', ['0', '1'])->default('0')->comment('0: No, 1: Yes');
            $table->unsignedBigInteger('processing_activity_id');
            $table->foreign('processing_activity_id')->references('id')->on('processing_activities');
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
        Schema::dropIfExists('processing_activity_basics');
    }
}
