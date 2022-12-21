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
        //
        // $searchField = $request->search_field;
        // $searchData = $request->search_data;
        // $orderBy = $request->order_by;
        // $sortField = $request->sort_field;
        // $filter = $request->filter;

        // $paginateCount = 10;
        // if ($request->count_content) {
        //     $paginateCount = $request->count_content;
        // }
        // if($filter != "none")
        //     $applications = $this->studentInformation->filterByField($searchField, $searchData)
        //                             ->where('status',$filter)
        //                             ->orderByField($sortField, $orderBy)
        //                             ->paginate($paginateCount);
        // else
        //     $applications = $this->studentInformation->filterByField($searchField, $searchData)
        //                             ->orderByField($sortField, $orderBy)
        //                             ->paginate($paginateCount);

        // if ($applications) {
        //     return StudentInformationResource::collection($applications);
        //     $data['message'] = 'Shows student application available.';
        // } else {
        //     $data['message'] = 'No student application available';
        // }

        // return response()->json($data, 200);
    }


    public function sendNotifRegistered($slug){
                
        $studentInformation = $this->studentInformation::where('slug', $slug)->first();
        
        Mail::to($studentInformation->email)->send(
            new RegistrationNotificationMail($studentInformation, $request->message)
        );

        $data['success'] = true;        
        $data['message'] = 'Registration Notification';
        return response()->json($data, 200);
    }
   

}
