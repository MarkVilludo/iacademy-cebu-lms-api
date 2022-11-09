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
        Schema::create('admission_upload_types', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('key');
            $table->timestamps();
            $table->softDeletes();
        });

        \App\Models\AdmissionUploadType::insert([
            [
                'label' => 'Current School ID',
                'key' => 'school_id',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'label' => 'Photocopy of PSA Birth Certificate',
                'key' => 'psa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'label' => 'Digital Copy Photo (2x2)',
                'key' => '2x2',
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
        Schema::dropIfExists('admission_upload_types');
    }
};
