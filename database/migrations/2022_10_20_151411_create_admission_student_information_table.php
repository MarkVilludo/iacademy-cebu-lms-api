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
        Schema::create('admission_student_information', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('email');
            $table->longText('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('school')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('tel_number')->nullable();
            $table->integer('type_id')->nullable();
            $table->integer('program_id')->nullable();
            $table->longtext('interview_remarks')->nullable();
            $table->string('status')->default('New Applicant')->comment = 'New Applicant, For Interview, For Reservation, For Enrollment, Enrolled, Failed, For Guidance Counselling';
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
        Schema::dropIfExists('admission_student_information');
    }
};
