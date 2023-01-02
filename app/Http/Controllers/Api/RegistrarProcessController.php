<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\{PaymentDetail, PaymentMode};
use App\Models\AdmissionStudentInformation;
use Illuminate\Support\Facades\Validator;
use App\Mail\SubmitInformationMail;
use App\Mail\Registrar\{RegistrationNotificationMail, RegistrationConfirmationMail};
use App\Http\Resources\PaymentDetailResource;
use App\Models\{StudentInfoStatusLog};


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


    public function sendNotifRegistered($slug){
                
        $studentInformation = $this->studentInformation::where('slug', $slug)->first();        
        $studentInformation->status = "Game Changer";                    
        $studentInformation->update();
        StudentInfoStatusLog::storeLogs($studentInformation->id, $studentInformation->status, '', '');

        $mailData = (object) array( 'student' => $studentInformation, 
                                    'message' =>  request('message'), 
                                    'payment_link' => request('payment_link'));                
        Mail::to($studentInformation->email)->send(
            new RegistrationNotificationMail($mailData)
        );

        $data['success'] = true;        
        $data['message'] = 'Registration Notification';
        return response()->json($data, 200);
    }
   
    public function confirmSelectedProgram($slug){
        $studentInformation = $this->studentInformation::where('slug', $slug)->first();        
        $studentInformation->status = "Confirmed";                    
        $studentInformation->update();
        StudentInfoStatusLog::storeLogs($studentInformation->id, $studentInformation->status, '', '');

        $mailData = (object) array( 'student' => $studentInformation);                
        
        Mail::to($studentInformation->email)->send(
            new RegistrationConfirmationMail($mailData)
        );

        $data['success'] = true;        
        $data['message'] = 'Program Confirmed';
        return response()->json($data, 200);

    }

}
