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
        Schema::create('admission_desired_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->timestamps();
        });


        \App\Models\AdmissionDesiredProgram::insert([
            [
                'title' => 'SHS- Accountancy, Business & Management',
                'type' => 'shs',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'SHS - Humanities and Social Sciences',
                'type' => 'shs',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'SHS - Graphic Illustration',
                'type' => 'shs',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'SHS - Media & Visual Arts',
                'type' => 'shs',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'SHS - Software Development',
                'type' => 'shs',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'SHS - Animation',
                'type' => 'shs',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'SHS - Audio Production',
                'type' => 'shs',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'SHS - Robotics',
                'type' => 'shs',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'SHS - Fashion Design',
                'type' => 'shs',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Animation',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Multimedia Arts',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Fashion Design',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Film & Visual Effects',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Accountancy',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Marketing Management',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Psychology',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Software Engineering',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Game Development',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Web Development',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Cloud Computing',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Data Science',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'College - Music Production and Sound Design',
                'type' => 'college',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => '2nd degree - Real Estate Management',
                'type' => 'second_degree',                
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Others',
                'type' => 'others',                
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
        Schema::dropIfExists('admission_desired_programs');
    }
};
