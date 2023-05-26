<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateDeletionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deletion_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\' . APP_EDITION . '\\DeletionLogsSeeder',
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
        Schema::dropIfExists('deletion_logs');
    }
}
