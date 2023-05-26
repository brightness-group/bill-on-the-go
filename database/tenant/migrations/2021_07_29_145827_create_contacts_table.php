<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('bdgo_gid')->nullable();
            $table->foreign('bdgo_gid')->references('bdgogid')->on('customers');
            $table->string('device_id')->nullable();
            $table->foreign('device_id')->references('id')->on('devices');
            $table->boolean('salutation')->default(true);
            $table->string('firstname');
            $table->string('lastname');
            $table->string('s_email')->nullable();
            $table->string('p_email')->nullable();
            $table->string('b_number')->nullable();
            $table->string('m_number')->nullable();
            $table->string('h_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
