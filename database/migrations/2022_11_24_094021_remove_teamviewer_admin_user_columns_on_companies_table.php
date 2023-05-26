<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'anydesk_account_type')) {
                $table->dropColumn('anydesk_account_type');
            }
            if (Schema::hasColumn('companies', 'anydesk_admin_script_token')) {
                $table->dropColumn('anydesk_admin_script_token');
            }
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
            $table->string('anydesk_account_type')->nullable();
            $table->string('anydesk_admin_script_token')->nullable();
        });
    }
};
