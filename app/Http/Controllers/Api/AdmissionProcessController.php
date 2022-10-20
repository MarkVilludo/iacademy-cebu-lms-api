<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdmissionProcessController extends Controller
{
    
    //
    public function __construct(
        AdmissionStudentInformation $studentInformation,
        AdmissionStudentType $studentType,
        AdmissionDesiredProgram $desiredProgram,
        AdmissionFile $admissionFile,
    ) {
        $this->studentInformation = $studentInformation;
        $this->studentType = $studentType;
        $this->desiredProgram = $desiredProgram;
        $this->admissionFile = $admissionFile;
    }
    public function getStudentTypes()
    {
        return response()->json([
            'data' => $this->studentType->select('id', 'title', 'type')->get()
        ]);
    }

    public function getDesiredPrograms()
    {
        return response()->json([
            'data' => $this->desiredProgram->where('type', '!=', 'others')->select('id', 'title', 'type')->get()
        ]);
    }
}
