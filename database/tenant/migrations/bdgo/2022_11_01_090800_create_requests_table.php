<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_of_receipt')->useCurrent();
            $table->string('name');
            $table->string('email');
            $table->longText('request_text');
            $table->longText('description');
            $table->enum('positively_identified', [0, 1])->default(0)->comment('0: No, 1: Yes');
            $table->longText('identification_details');
            $table->bigInteger('affected_category_type_id')->unsigned();
            $table->bigInteger('request_type_id')->unsigned();
            $table->bigInteger('status_type_id')->unsigned();
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
        Schema::dropIfExists('requests');
    }
}
