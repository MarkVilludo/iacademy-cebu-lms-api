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
        Schema::create('admission_files', function (Blueprint $table) {
            $table->id();
            $table->text('filename');
            $table->string('type', 32)->nullable();
            $table->string('slug', 64)->nullable();
            $table->text('path')->nullable();
            $table->string('orig_filename', 128);
            $table->string('filetype', 32);
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
        Schema::dropIfExists('admission_files');
    }
};
