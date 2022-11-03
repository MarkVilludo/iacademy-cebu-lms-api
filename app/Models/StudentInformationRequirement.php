<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInformationRequirement extends Model
{
    use HasFactory;
    protected $fillable = [
        'admission_student_type_id',
        'admission_upload_type_id',
    ];

    public function uploadType()
    {
        return $this->belongsTo(
            'App\Models\AdmissionUploadType',
            'admission_upload_type_id',
        );
    }

    public function file()
    {
        return $this->belongsTo(
            'App\Models\AdmissionFile',
            'admission_file_id',
        );
    }
}
