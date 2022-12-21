<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\{PaymentDetail, PaymentMode};
use App\Models\AdmissionStudentInformation;
use Illuminate\Support\Facades\Validator;
use App\Mail\SubmitInformationMail;
use App\Mail\Registrar\RegistrationNotificationMail;
use App\Http\Resources\PaymentDetailResource;


use DB, Mail;

class RegistrarProcessController extends Controller
{
    
    //
    public function __construct(
        PaymentDetail $paymentDetail,
        PaymentMode $paymentMode,
        AdmissionStudentInformation $studentInformation) {

        $this->paymentDetail = $paymentDetail;
        $this->paymentMode = $paymentMode;
        $this->studentInformation = $studentInformation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
    }


    public function sendNotifRegistered(Request $request, $slug){
                
        $studentInformation = $this->studentInformation::where('slug', $slug)->first();
        
        Mail::to($studentInformation->email)->send(
            new RegistrationNotificationMail($studentInformation)
        );

        $data['success'] = true;        
        $data['message'] = 'Registration Notification';
        return response()->json($data, 200);
    }
   

}
