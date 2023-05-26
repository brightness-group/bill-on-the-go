<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedUserLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_user_links', function (Blueprint $table) {
            $table->id();
            $table->string('shared_user_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('isTxtFile');
            $table->timestamps();
            $table->foreign('shared_user_id')->references('id')->on('shared_users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shared_user_links');
    }
}
