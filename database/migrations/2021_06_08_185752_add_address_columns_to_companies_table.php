<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressColumnsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('address')->after('logo')->change();
            $table->string('zip')->nullable()->after('address');
            $table->string('city')->nullable()->after('zip');
            $table->string('country')->nullable()->after('city');

            $table->string('email')->after('country')->change();
            $table->string('payment')->after('email')->change();
            $table->string('iban')->after('payment')->change();
            $table->string('bic')->after('iban')->change();
            $table->text('notes')->after('bic')->change();

            $table->string('contact')->nullable()->after('notes');
            $table->string('contact_email')->nullable()->after('contact');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
}
