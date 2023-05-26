<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connection_reports', function (Blueprint $table) {
            $table->id();
            $table->string('connection_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('groupname');
            $table->string('userid')->nullable();
            $table->string('username')->nullable();
            $table->string('deviceid')->nullable();
            $table->string('devicename')->nullable();
            $table->integer('support_session_type')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->double('fee', 8, 2)->nullable();
            $table->string('currency')->default('Euro');
            $table->string('billing_state')->nullable();
            $table->longText('notes')->nullable();
            $table->string('contact_id')->nullable();

            $table->foreign('customer_id')->references('id')->on('customers')
                ->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('connection_reports');
    }
}
