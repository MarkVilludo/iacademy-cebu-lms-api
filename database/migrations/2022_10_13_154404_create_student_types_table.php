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
        Schema::create('admission_student_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->timestamps();
        });

        \App\Models\AdmissionStudentType::insert([
            [
                'title' => 'Sofware Engineering',
                'type' => 'college',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Game Development',
                'type' => 'college',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Animation',
                'type' => 'college',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Multimedia Arts and Design',
                'type' => 'college',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_types');
    }
};
