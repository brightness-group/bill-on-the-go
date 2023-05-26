<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *  'billing_country', 'billing_city',
    'billing_address', 'billing_street', 'billing_house_number', 'billing_zip_code'
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('post_code')->nullable();
            $table->string('comment')->nullable();
            $table->string('website')->nullable();

            $table->string('billing_addition')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_zip_code')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_iban')->unique()->nullable();
            $table->string('billing_email')->unique()->nullable();
            $table->string('billing_payment')->nullable();
            $table->boolean('billing_sepa')->nullable()->default(false);

            $table->softDeletes();
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
        Schema::dropIfExists('customers');
    }
}
