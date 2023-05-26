<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessingActivityInternalRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processing_activity_internal_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('other_recipients_internally')->nullable();
            $table->string('contact_person')->nullable();
            $table->foreignId('processing_activity_id')->constrained()->index('pa_internal_recipient_pa_foreign');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processing_activity_internal_recipients');
    }
}
