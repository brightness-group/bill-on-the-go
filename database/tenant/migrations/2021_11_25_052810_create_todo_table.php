<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo', function (Blueprint $table) {
            $table->id();
            $table->string('bdgo_gid');
            $table->foreign('bdgo_gid')->references('bdgogid')->on('customers');
            $table->string('todo')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_important')->default(false);
            $table->boolean('sort_order')->default(false);
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
        Schema::dropIfExists('todo');
    }
}
