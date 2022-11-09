<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInformationRequirement extends Model
{
    use HasFactory;

    public function file()
    {
        return $this->belongsTo(AdmissionFile::class, 'admission_file_id', 'id');
    }
}
