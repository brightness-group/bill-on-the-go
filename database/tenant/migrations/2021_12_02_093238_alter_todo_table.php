<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTodoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('todo', function (Blueprint $table) {
            $table->dropForeign(['bdgo_gid']);
            $table->dropColumn('bdgo_gid');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('todo', function (Blueprint $table) {
            $table->string('bdgo_gid');
            $table->foreign('bdgo_gid')->references('bdgogid')->on('customers');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::enableForeignKeyConstraints();
    }
}
