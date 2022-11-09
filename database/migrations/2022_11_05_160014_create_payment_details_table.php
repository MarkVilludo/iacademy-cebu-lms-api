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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->string('student_number', 32)->nullable();
            $table->integer('student_information_id')->nullable();
            $table->string('first_name', 64);
            $table->string('middle_name', 64)->nullable();
            $table->string('last_name', 64);
            $table->string('contact_number', 64);
            $table->string('email_address', 64);
            $table->text('remarks')->nullable();
            $table->integer('mode_of_payment_id');
            $table->text('description')->nullable();
            $table->double('subtotal_order', 10,2);
            $table->double('convenience_fee', 10,2);
            $table->double('total_amount_due', 10,2);
            $table->double('charges', 10,2)->default(0);

            //payment response - Response will be our basis in changing status in portal.
            $table->string('request_id', 128);
            $table->string('slug', 64);
            $table->string('merchant_id', 128)->nullable();
            $table->string('response_id', 128)->nullable();
            $table->text('timestamp')->nullable();
            $table->text('pay_reference')->nullable();
            $table->string('response_code', 32)->nullable();
            $table->text('signature')->nullable();
            $table->text('response_advise')->nullable();
            $table->text('ip_address')->nullable();
            $table->string('currency', 32)->nullable();
            $table->string('pchannel', 64)->nullable();
            $table->string('pmethod', 64)->nullable();
            $table->string('response_message', 64)->nullable();
            $table->text('payment_action_info')->nullable();
            $table->boolean('is_sent_email')->default(0);

            //additional
            $table->text('name_of_school')->nullable();
            $table->integer('course')->nullable();
            $table->integer('mode_release')->nullable();

            $table->string('date_paid', 32)->nullable();
            $table->string('date_expired', 32)->nullable();

            //NON BANKS
            $table->text('pay_instructions')->nullable();

            $table->string('status', 32)->default('Pending');

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
        Schema::dropIfExists('payment_details');
    }
};
