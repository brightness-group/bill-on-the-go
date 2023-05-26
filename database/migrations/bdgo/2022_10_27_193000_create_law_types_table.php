<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_types', function (Blueprint $table) {
            $table->id();
            $table->string('law_type');
            $table->char('language_code', 4)->nullable();
            $table->bigInteger('request_type_id')->unsigned();
            $table->bigInteger('customer_type_id')->unsigned();
            $table->foreign('customer_type_id')->references('id')->on('customer_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
        
        // Call seeder
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\' . APP_EDITION . '\\LawTypesTableSeeder',
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
        Schema::dropIfExists('law_types');
    }
}
