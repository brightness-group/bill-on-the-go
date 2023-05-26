<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subdomain');
            $table->string('logo')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('address')->nullable();
            $table->string('payment')->nullable();
            $table->string('iban')->unique()->nullable();
            $table->string('bic')->unique()->nullable();
            $table->text('notes')->nullable();
            $table->boolean('status')->default(false);
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
        $companies = Company::all();
        foreach ($companies as $company) {
            $company->delete();
        }
        Schema::dropIfExists('companies');
    }
}
