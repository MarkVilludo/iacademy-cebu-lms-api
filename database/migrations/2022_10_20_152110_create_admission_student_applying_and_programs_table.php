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
        Schema::create('admission_student_applying_and_programs', function (Blueprint $table) {
            $table->id();
            $table->integer('student_information_id');
            $table->integer('ref_id')->comment = 'student_type_id or desired_program_id';
            $table->string('ref_type')->comment = 'student_type or desired_program';
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
        Schema::dropIfExists('admission_student_applying_and_programs');
    }
};
