<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateDutyInformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duty_informs', function (Blueprint $table) {
            $table->id();
            $table->string('inform');
            $table->bigInteger('customer_type_id')->unsigned();
            $table->foreign('customer_type_id')->references('id')->on('customer_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\' . APP_EDITION . '\\DutyInformsSeeder',
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
        Schema::dropIfExists('duty_informs');
    }
}
