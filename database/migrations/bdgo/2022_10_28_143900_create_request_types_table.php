<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('law_type_id')->unsigned();
            $table->foreign('law_type_id')->references('id')->on('law_types')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('customer_type_id')->unsigned();
            $table->foreign('customer_type_id')->references('id')->on('customer_types')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
        
        // Call seeder
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\' . APP_EDITION . '\\RequestTypesTableSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_types');
    }
}
