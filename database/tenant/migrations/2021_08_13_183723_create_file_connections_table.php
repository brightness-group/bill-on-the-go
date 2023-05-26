<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files')->onDelete('cascade');
            $table->string('connection_id');
            $table->string('bdgogid')->nullable();
            $table->string('groupname')->nullable();
            $table->string('userid')->nullable();
            $table->string('username')->nullable();
            $table->string('device_id')->nullable();
            $table->string('devicename')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('billing_state')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('activity_report')->nullable();
            $table->boolean('processed')->default(false);
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
        Schema::dropIfExists('file_connections');
    }
}
