<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;

class PaynamicsWebhookController extends Controller
{
    //
    public function __construct(PaymentDetail $paymentDetail)
    {
        $this->paymentDetail = $paymentDetail;
    }

    public function webhook(Request $request)
    {
        //
        $response =  $request->all();
        //send email to requestor, finance and department involve
        $paymentDetails = $this->paymentDetail->where('request_id', $response['request_id'])
                                                     ->first();
        
        if ($paymentDetails) {

            if ($response['response_message'] == 'Transaction Successful') {
                //transaction is approved.
                $this->paymentDetail->sendEmailAfterPayment($paymentDetails);

                //send email then update status
                $paymentDetails->status = 'Paid';
                $paymentDetails->date_paid = date('F d, Y', strtotime(date('Y-m-d')));
                $paymentDetails->is_sent_email = 1;

                //update student info
                if ($paymentDetails->studentInfo->status == 'For Reservation') {
                    $paymentDetails->studentInfo->status = 'Reserved';
                } else {
                    $paymentDetails->studentInfo->status = 'Waiting For Interview';
                }
                $paymentDetails->studentInfo->update();

            } elseif ($response['response_message'] == 'Transaction Expired') {
                //transaction is expired.
                $paymentDetails->status = 'expired';
                $paymentDetails->date_expired = date('F d, Y', strtotime(date('Y-m-d')));
                //send email to student
                // This request has expired. Pleare re-process your order should you wish to pursue with your request
                $this->paymentDetail->sendEmailExpired($paymentDetails);

                //update student info
                $paymentDetails->studentInfo->status = 'Expired Payment';
                $paymentDetails->studentInfo->update();
            }  else {
                //transaction is expired.
                $paymentDetails->status = $response['response_message'];
            }
            $data['success'] = true;
            $data['message'] = 'Sucessfully updated the status';

            $paymentDetails->response_message = $response['response_message'];
            $paymentDetails->response_advise = $response['response_advise'];
            $paymentDetails->update();
        } else {
            $data['success'] = false;
            $data['message'] = 'Request ID not found.';
        }

        return response()->json($data, 200);
    }
}
