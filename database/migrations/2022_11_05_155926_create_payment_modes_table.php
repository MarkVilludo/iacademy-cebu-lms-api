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
        Schema::create('payment_modes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->text('image_url', 64);
            $table->string('type', 12);
            $table->integer('charge');
            $table->boolean('is_active')->default(1);
            $table->string('pchannel', 32);
            $table->string('pmethod', 32);
            $table->boolean('is_nonbank')->default(0);
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
        Schema::dropIfExists('payment_modes');
    }
};
