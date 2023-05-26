<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreatePersonalDataCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_data_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('customer_type_id')->unsigned();
            $table->foreign('customer_type_id')->references('id')->on('customer_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
        
        // Call seeder
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\' . APP_EDITION . '\\PersonalDataCategoriesTableSeeder',
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
        Schema::dropIfExists('personal_data_categories');
    }
}
