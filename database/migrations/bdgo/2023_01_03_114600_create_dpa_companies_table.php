<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateDpaCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dpa_companies', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('category')->nullable();
            $table->string('street')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('location')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('website')->nullable();
            $table->bigInteger('dpa_type_id')->unsigned();
            $table->foreign('dpa_type_id')->references('id')->on('dpa_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('dpa_category_id')->unsigned();
            $table->foreign('dpa_category_id')->references('id')->on('dpa_categories')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('customer_type_id')->unsigned();
            $table->foreign('customer_type_id')->references('id')->on('customer_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\' . APP_EDITION . '\\DpaCompaniesSeeder',
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
        Schema::dropIfExists('dpa_companies');
    }
}
