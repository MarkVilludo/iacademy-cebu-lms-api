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
                'title' => 'UG- Freshman',
                'type' => 'college',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'UG- Transferee',
                'type' => 'college',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'SHS- Freshman',
                'type' => 'shs',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'SHS- Transferee',
                'type' => 'shs',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'SHS- DRIVE',
                'type' => 'shs-drive',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => '2ND- DEGREE',
                'type' => 'second_degree',
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
