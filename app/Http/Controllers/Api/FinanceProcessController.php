<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\{PaymentDetail, PaymentMode};
use App\Models\AdmissionStudentInformation;
use Illuminate\Support\Facades\Validator;
use App\Mail\SubmitInformationMail;
use App\Http\Resources\PaymentDetailResource;
use App\Models\{StudentInfoStatusLog};
use App\Mail\Admissions\{AdmissionsNotificationEmail};


use DB, Mail;

class FinanceProcessController extends Controller
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

    public function transactions($slug, $sem){
                
        $student = $this->studentInformation::where('slug', $slug)->first();
        $data['data'] = @PaymentDetailResource::collection($this->paymentDetail
                                        ->where('student_information_id', $student->id)
                                        ->where('description','LIKE','Tuition%')
                                        ->where('sy_reference', $sem)                                        
                                        ->get());

        $data['other'] = @PaymentDetailResource::collection($this->paymentDetail
                                        ->where('student_information_id', $student->id)
                                        ->where('description','NOT LIKE','Tuition%')
                                        ->where('sy_reference', $sem)                                        
                                        ->get());
        $data['success'] = true;
        $data['message'] = 'transactions for current term';
        return response()->json($data, 200);
    }

    public function transactionsOther($slug, $sem){
                
        $student = $this->studentInformation::where('slug', $slug)->first();
        $data['data'] = @PaymentDetailResource::collection($this->paymentDetail
                                        ->where('student_information_id', $student->id)
                                        ->where('description','NOT LIKE','Tuition%')
                                        ->where('sy_reference', $sem)                                        
                                        ->get());
        $data['success'] = true;
        $data['message'] = 'transactions for current term';
        return response()->json($data, 200);
    }

    public function reservationPayment($slug){
                
        $student = $this->studentInformation::where('slug', $slug)->first();
        $data['data'] = new PaymentDetailResource($this->paymentDetail
                                        ->where('student_information_id', $student->id)
                                        ->where('description', 'Reservation Payment') 
                                        ->orderByRaw("FIELD(status , 'Paid', 'Pending') ASC")
                                        ->first());

        $data['application'] = new PaymentDetailResource($this->paymentDetail
                                        ->where('student_information_id', $student->id)
                                        ->where('description', 'Application Payment')                                        
                                        ->orderByRaw("FIELD(status , 'Paid', 'Pending') ASC")
                                        ->first());

        $data['student_sy'] = $student->syid;
        $data['success'] = true;
        $data['message'] = 'transactions for current term';
        return response()->json($data, 200);
    
    }

    public function manualPayment(Request $request){
        
        $referer = request()->headers->get('referer');
        if($referer == "http://cebu.iacademy.edu.ph"){

            $requestId =  'mp' . substr(uniqid(), 0, 18);
            $student = $this->studentInformation::where('slug', $request->slug)->first();
            $checkOR = $this->paymentDetail::where('or_number',$request->or_number)->first();
            if(!$checkOR || !$request->or_number){
                $newPaymentDetails = new $this->paymentDetail();
                $newPaymentDetails->request_id = $requestId;
                $newPaymentDetails->slug = \Str::uuid();
                $newPaymentDetails->description = $request->description;
                $newPaymentDetails->or_number = $request->or_number;
                $newPaymentDetails->status = $request->status;
                $newPaymentDetails->student_information_id = $student->id;
                $newPaymentDetails->student_number = '';
                $newPaymentDetails->first_name = $request->first_name;
                $newPaymentDetails->middle_name = $request->middle_name;
                $newPaymentDetails->last_name = $request->last_name;
                $newPaymentDetails->email_address = $request->email_address;
                $newPaymentDetails->remarks = $request->remarks;
                $newPaymentDetails->mode_of_payment_id = $request->mode_of_payment_id;            
                $newPaymentDetails->convenience_fee = 0;
                $newPaymentDetails->subtotal_order = $request->subtotal_order;
                $newPaymentDetails->total_amount_due = $request->total_amount_due;
                $newPaymentDetails->sy_reference = $request->sy_reference;
                $newPaymentDetails->charges = 0;
                $newPaymentDetails->contact_number = $request->contact_number;
                $newPaymentDetails->name_of_school = @$request->name_of_school;
                $newPaymentDetails->course = @$request->course;

                $newPaymentDetails->ip_address = @$request->ip();
                $newPaymentDetails->save();

                $this->paymentDetail->sendEmailAfterPayment($newPaymentDetails);

                if($request->description == "Reservation Payment" && $request->status == "Paid"){
                    $student->status = "Reserved";
                    //Notify Admissions
                    $mailData = (object) array( 'student' => $student, 
                                    'message' =>  "Applicant has paid reservation fee", 
                                    'subject' => "New Reserved Applicant");                
                    Mail::to("josephedmundcastillo@gmail.com")->send(
                        new AdmissionsNotificationEmail($mailData)
                    );

                    StudentInfoStatusLog::storeLogs($newPaymentDetails->studentInfo->id, $newPaymentDetails->studentInfo->status, '', '');
                    $student->update();
                }
                if($request->description == "Application Payment" && $request->status == "Paid"){
                    $student->status = "Waiting For Interview";
                    //Notify Admissions
                    $mailData = (object) array( 'student' => $student, 
                                    'message' =>  "New applicant is waiting for interview link", 
                                    'subject' => "Waiting for Interview");                
                    Mail::to("josephedmundcastillo@gmail.com")->send(
                        new AdmissionsNotificationEmail($mailData)
                    );

                    StudentInfoStatusLog::storeLogs($newPaymentDetails->studentInfo->id, $newPaymentDetails->studentInfo->status, '', '');
                    $student->update();
                }

                $data['success'] = true;
                $data['message'] = "Successfully Added Payment";
            }
            else{
                $data['success'] = false;
                $data['message'] = "OR Number Already Exists";    
            }

        }
        else{
            $data['success'] = false;
            $data['message'] = "request denied from ".$referer;
        }
        
        
        return response()->json($data, 200);
    }

    public function updateOrNumber(Request $request){
        
        $referer = request()->headers->get('referer');
        if($referer == "http://cebu.iacademy.edu.ph"){                        
            
            $PaymentDetails = $this->paymentDetail::find($request->id);        
            $checkOR = $this->paymentDetail::where('or_number',$request->or_number)->first();
            if(!$checkOR || !$request->or_number){
                $PaymentDetails->or_number = $request->or_number;
                $PaymentDetails->ip_address = @$request->ip();
                $PaymentDetails->save();
                $data['success'] = true;
                $data['message'] = "Successfully Updated OR";
            }
            else{
                $data['success'] = false;
                $data['message'] = "OR Number Already Exists";
            }
            

        }
        else{
            $data['success'] = false;
            $data['message'] = "request denied";
        }
        
        
        return response()->json($data, 200);
    }

    public function setPaid(Request $request){
        
        $referer = request()->headers->get('referer');
        if($referer == "http://cebu.iacademy.edu.ph"){                        
            
            $PaymentDetails = $this->paymentDetail::find($request->id);
            $PaymentMode = $this->paymentMode::find($PaymentDetails->mode_of_payment_id);
            if($PaymentMode->name == "MANUAL"){
                $PaymentDetails->status = "Paid";
                $PaymentDetails->ip_address = @$request->ip();
                $PaymentDetails->save();
                $data['success'] = true;
                $data['message'] = "Successfully Updated Payment";
            }
            else{
                $data['success'] = false;
                $data['message'] = "request denied";
            }
            

        }
        else{
            $data['success'] = false;
            $data['message'] = "request denied";
        }
        
        
        return response()->json($data, 200);
    }

    public function deletePayment(Request $request){
        
        $referer = request()->headers->get('referer');
        if($referer == "http://cebu.iacademy.edu.ph"){                        
            
            $PaymentDetails = $this->paymentDetail::find($request->id);
            $PaymentMode = $this->paymentMode::find($PaymentDetails->mode_of_payment_id);
            if($PaymentMode->name == "MANUAL"){
                $PaymentDetails->delete();
                $data['success'] = true;
                $data['message'] = "Successfully Deleted Payment";
            }
            else{
                $data['success'] = false;
                $data['message'] = "request denied";
            }
            

        }
        else{
            $data['success'] = false;
            $data['message'] = "request denied";
        }
        
        
        return response()->json($data, 200);
    }
}
