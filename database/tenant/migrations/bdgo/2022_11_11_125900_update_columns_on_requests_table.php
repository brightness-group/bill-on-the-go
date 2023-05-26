<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function __construct()
    {
        if (!Type::hasType('enum')) {
            Type::addType('enum', StringType::class);
        }

        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->longText('request_text')->nullable()->change();
            $table->longText('description')->nullable()->change();
            $table->longText('identification_details')->nullable()->change();
            $table->enum('positively_identified', [0, 1, 2])->default(0)->comment('0: None, 1: No, 2: Yes')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            //
        });
    }
};
