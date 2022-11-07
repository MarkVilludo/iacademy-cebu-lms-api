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
        Schema::create('payment_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_detail_id');
            $table->integer('item_id');
            $table->double('price', 10,2);
            $table->double('discount', 10,2)->nullable();
            $table->integer('qty')->default(0);
            $table->string('term', 32)->nullable();
            $table->string('academic_year', 32)->nullable();
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
        Schema::dropIfExists('payment_order_items');
    }
};
