<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateDeletionProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deletion_processes', function (Blueprint $table) {
            $table->id();
            $table->string('process');
            $table->text('process_period')->nullable();
            $table->integer('month')->default(0);
            $table->string('other')->nullable();
            $table->bigInteger('template_id')->unsigned();
            $table->foreign('template_id')->references('id')->on('templates')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('department_category_id')->unsigned();
            $table->foreign('department_category_id')->references('id')->on('department_categories')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('customer_type_id')->unsigned();
            $table->foreign('customer_type_id')->references('id')->on('customer_types')
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
        Schema::dropIfExists('deletion_processes');
    }
}
