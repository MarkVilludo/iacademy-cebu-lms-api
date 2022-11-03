<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acceptance_letter_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('student_information_id');
            $table->string('filename')->nullable();
            $table->string('orig_filename')->nullable();
            $table->string('filetype')->nullable();
            $table->datetime('acceptance_letter_sent_date');
            $table->longText('acceptance_letter');
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
        Schema::dropIfExists('acceptance_letter_attachments');
    }
};
