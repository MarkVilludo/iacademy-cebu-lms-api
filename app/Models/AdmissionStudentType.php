<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionStudentType extends Model
{
    use HasFactory;

    public function uploadTypes()
    {
        return $this->belongsToMany(
            'App\Models\AdmissionUploadType',
            'admission_student_upload_types',
            'admission_student_type_id',
            'admission_upload_type_id'
        );
    }
}
