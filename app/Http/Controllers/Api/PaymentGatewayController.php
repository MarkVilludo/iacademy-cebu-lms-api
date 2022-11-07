<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{PaymentDetail, PaymentItem};
use App\Models\{PaymentMode, PaymentOrderItem};
use Illuminate\Http\Request;
use DB, App;

class PaymentGatewayController extends Controller
{
    public function __construct(
        PaymentDetail $paymentDetail,
        PaymentItem $item,
        PaymentMode $mode,
        PaymentOrderItem $paymentItem
    ) {
        $this->paymentDetail = $paymentDetail;
        $this->item = $item;
        $this->mode = $mode;
        $this->paymentItem = $paymentItem;
    }
    public function pay(Request $request)
    {
        DB::beginTransaction();
        try {
            $mailingFee = 0;
            $subtotal = $request->total_price_without_charge;
            $total = $request->total_price_with_charge;

            $modePayment = $this->mode->find($request->mode_of_payment_id);
            $chargeDefault = $request->charge;

            if ($modePayment->type == 'percentage') {
                //My number is subtotal
                //I want to get $modePayment->charge
                //Convert our percentage value into a decimal.
                $percentInDecimal = $modePayment->charge / 100;
                //Get the result.
                $charge = $percentInDecimal * $subtotal;

                if ($charge < 25) {
                    $charge = 25;
                }
            } else {
                $charge = $chargeDefault;
            }

            if ($chargeDefault != $charge || $mailingFee != request('mailing_fee')) {
                $data['message'] = 'Failed, wrong computations of charges.';
                $data['success'] = false;
                $data['charge'] = 'default' . $chargeDefault . 'backend' . $charge;
                $data['mailing_fee'] = 'frontend' . request('mailing_fee') . 'backend' . $mailingFee;
                $data['subtotal'] = $subtotal + $mailingFee;
                $data['mailing_cost'] = $mailingFee;
                $data['total'] = $total;
                $data['default_charge'] = $chargeDefault;
                DB::commit();
                return response()->json($data, 200);
            } else {
                $charge = $chargeDefault;
            }

            //payment mode + random string
            $requestId = $request->mode_payment_name . '' . substr(uniqid(), 0, 18);

            $newPaymentDetails = new $this->paymentDetail();
            $newPaymentDetails->request_id = $requestId;
            $newPaymentDetails->slug = \Str::uuid();
            $newPaymentDetails->description = $request->description;
            $newPaymentDetails->student_number = $request->student_number;
            $newPaymentDetails->first_name = $request->first_name;
            $newPaymentDetails->middle_name = $request->middle_name;
            $newPaymentDetails->last_name = $request->last_name;
            $newPaymentDetails->email_address = $request->email;
            $newPaymentDetails->remarks = $request->remarks;
            $newPaymentDetails->mode_of_payment_id = $request->mode_of_payment_id;
            $newPaymentDetails->remarks = $request->remarks;
            $newPaymentDetails->convenience_fee = @$modePayment->charge;
            $newPaymentDetails->subtotal_order = $subtotal + $mailingFee;
            $newPaymentDetails->total_amount_due = $total + $mailingFee;
            $newPaymentDetails->charges = $total - $subtotal;
            $newPaymentDetails->contact_number = $request->contact_number;
            $newPaymentDetails->name_of_school = @$request->name_of_school;
            $newPaymentDetails->course = @$request->course;

            $newPaymentDetails->ip_address = @$request->ip();
            $newPaymentDetails->save();

            if ($request->order_items) {
                $paynamicsOrders = [];
                foreach ($request->order_items as $orderItem) {
                    $newPaymentItem = new $this->paymentItem();
                    $newPaymentItem->price = $orderItem['price_default'];
                    $newPaymentItem->qty = $orderItem['qty'];
                    $newPaymentItem->item_id = $orderItem['id'];
                    $newPaymentItem->payment_detail_id = $newPaymentDetails->id;
                    $newPaymentItem->term = @$orderItem['term'];
                    $newPaymentItem->academic_year = @$orderItem['academic_year'];
                    $newPaymentItem->save();

                    $paynamicsOrders[] = [
                        "itemname" => $orderItem['title'],
                        "quantity" => $orderItem['qty'],
                        "unitprice" => $orderItem['price_default'],
                        "totalprice" => $orderItem['price_default'] * $orderItem['qty'],
                    ];
                }
            }

            if (App::environment(['local', 'staging'])) {
                $merchantid = "000000250621A30BDED2";
                $mkey = "EFBC7AB78E76574FF18A9C87C46ADD7A";
                $username = 'iacademy6DkF';
                $password = 'j3YdELFVt235';
                $url = 'https://payin.payserv.net/paygate/transactions/';
            } else {
                $merchantid = "000000220821A30BF10B";
                $mkey = "77466C4E0EC95380E8BBE4A5D9ACA6CD";
                $username = 'ictacademyRMB6';
                $password = 'Az5WXjNfbym7';
                $url = 'https://payin.paynamics.net/paygate/transactions/';
            }
            
            $requestId = $newPaymentDetails->request_id;
            $notURL = route('webhook');
            $responseURL = url('/');
            $cancelURL = $modePayment->payment_action == 'onlinebanktransfer' ? '' : route('cancel-payment');
            $pmethod = $modePayment->pmethod;
            $paymentAction = $modePayment->payment_action;
            $pchannel = $modePayment->pchannel;
            $collectionMethod = 'single_pay';
            $amount = $total + $mailingFee;  //charge for payment mode.
            $total = $amount;
            $currency = "PHP";
            $payNotStatus = "1";
            // $payNotChannel = $modePayment->payment_action == 'onlinebanktransfer' ? "1" : "";
            // $payNotChannel = $pmethod == 'onlinebanktransfer' ? '1' : '';
            $payNotChannel = $pchannel == 'ubp_online' ? '1' : '';

            $rawTrx = $merchantid .
                    $requestId .
                    $notURL .
                    $responseURL .
                    $cancelURL .
                    $pmethod .
                    $paymentAction .
                    $collectionMethod .
                    $amount .
                    $currency .
                    $payNotStatus .
                    $payNotChannel .
                    $mkey;

            $signatureTrx = hash("SHA512", $rawTrx);

            $fname = $request->first_name;
            $lname = $request->last_name;
            $mname = $request->middle_name ? $request->middle_name : '';
            $email = $request->email;
            $phone = $request->contact_number;
            $mobile = $request->contact_number;
            $dob = $request->dob;

            $raw = $fname .
                    $lname .
                    $mname .
                    $email .
                    $phone .
                    $mobile .
                    $dob .
                    $mkey;

            $signature = hash("sha512", $raw);


            // var raw = fname + lname + mname + email + phone + mobile + dob + mkey;
            // var signature = CryptoJS.enc.Hex.stringify(CryptoJS.SHA512(raw));

            // rawTrx = merchantid + request_id + notification_url + response_url + cancel_url + pmethod + payment_action + collectionMethod +
            // amount + currency + payment_notification_status + payment_notification_channel + mkey;
            // If any HTTP authentication is needed.
            $requestType = 'POST'; // This can be PUT or POST
            $arrPostData = '';
            $charge = $modePayment->charge;

            // // if($mailingFee){
            //     $subtotal += $mailingFee;
            // }

            if ($modePayment->type == 'percentage') {
                $charge = round(($modePayment->charge * 100) / $subtotal, 2);
            }

            if ($pmethod == 'onlinebanktransfer' || $pmethod == 'wallet') {
                $paynamicsOrders[] = [
                    "itemname" => "Fee",
                    "quantity" => 1,
                    "unitprice" => $total - $subtotal - $mailingFee,
                    "totalprice" => $total - $subtotal - $mailingFee
                ];
                
                if($mailingFee > 0){
                    $paynamicsOrders[] = [
                       "itemname" => "Mailing Fee",
                       "quantity" =>  1,
                       "unitprice" => $mailingFee,
                       "totalprice" => $mailingFee
                    ];
                }

                $arrPostData = [
                    "transaction" => [
                        "request_id" => $requestId,
                        "notification_url" => $notURL,
                        "response_url" => $responseURL,
                        "cancel_url" => $cancelURL,
                        "pmethod" => $pmethod,
                        "pchannel" => $pchannel,
                        "payment_action" => $paymentAction,
                        "schedule" => "",
                        "collection_method" => $collectionMethod,
                        "deferred_period" => "",
                        "deferred_time" => "",
                        "dp_balance_info" => "",
                        "amount" => $total,
                        "currency" => $currency,
                        "pay_reference" => "",
                        "payment_notification_status" => $payNotStatus,
                        "payment_notification_channel" => $payNotChannel,
                        "signature" => $signatureTrx,
                    ],
                    "customer_info" => [
                        "fname" => $fname,
                        "lname" => $lname,
                        "mname" => $mname,
                        "email" => $email,
                        "phone" => $phone,
                        "mobile" => $mobile,
                        "dob" => $dob,
                        "signature" => $signature,
                    ],
                    "order_details" => [
                        "orders" => $paynamicsOrders,
                        "subtotalprice" => $total,
                        "shippingprice" => "0.00",
                        "discountamount" => "0.00",
                        "totalorderamount" => $total
                    ]
                ];
                
                // return $arrPostData;
            } elseif ($pmethod == 'nonbank_otc') {
                $todayDateTime = date('m/d/Y h:i:s', strtotime(date('Y/m/d') . ' + 2 days'));
                $amount = $total;

                $rawTrx = $merchantid .
                $requestId .
                $notURL .
                $responseURL .
                $cancelURL .
                $pmethod .
                $paymentAction .
                $collectionMethod .
                $amount .
                $currency .
                $payNotStatus .
                $payNotChannel .
                $mkey;

                $signatureTrx = hash("sha512", $rawTrx);

                $paynamicsOrders[] = [
                   "itemname" => "Fee",
                   "quantity" =>  1,
                   "unitprice" => $charge,
                   "totalprice" => $charge
                ];

                if($mailingFee > 0){
                    $paynamicsOrders[] = [
                       "itemname" => "Mailing Fee",
                       "quantity" =>  1,
                       "unitprice" => $mailingFee,
                       "totalprice" => $mailingFee
                    ];
                }

                $arrPostData = [
                    "transaction" => [
                        "request_id" => $requestId,
                        "notification_url" => $notURL,
                        "response_url" => $responseURL,
                        "cancel_url" => $cancelURL,
                        "pmethod" => $pmethod,
                        "pchannel" => $pchannel,
                        "payment_action" => $paymentAction,
                        "schedule" => "",
                        "collection_method" => $collectionMethod,
                        "deferred_period" => "",
                        "deferred_time" => "",
                        "dp_balance_info" => "",
                        "amount" => $total,
                        "currency" => "PHP",
                        "descriptor_note" => "",
                        "payment_notification_status" => $payNotStatus,
                        "payment_notification_channel" => $payNotChannel,
                        "expiry_limit" => $todayDateTime,
                        "signature" => $signatureTrx,
                    ],
                    "customer_info" => [
                        "fname" => $fname,
                        "lname" => $lname,
                        "mname" => $mname,
                        "email" => $email,
                        "phone" => $phone,
                        "mobile" => $mobile,
                        "dob" => $dob,
                        "signature" => $signature,
                    ],
                    "billing_info" => [
                        "billing_address1" => "",
                        "billing_address2" => "",
                        "billing_city" => "",
                        "billing_state" => "",
                        "billing_country" => "PH",
                        "billing_zip" => ""
                    ],
                    "order_details" => [
                        "orders" => $paynamicsOrders,
                        "subtotalprice" => $total,
                        "shippingprice" => "0.00",
                        "discountamount" => "0.00",
                        "totalorderamount" => $total,
                    ]
                ];
            }
            
            // return $arrPostData;
            // You can set your post data
            // dd($arrPostData);
            $postData = http_build_query($arrPostData); // Raw PHP array
            $postData = json_encode($arrPostData); // Only USE this when request JSON data.
            
            return $mixResponse = $this->executeCurl(array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPGET => true,
                CURLOPT_VERBOSE => true,
                CURLOPT_AUTOREFERER => true,
                CURLOPT_CUSTOMREQUEST => $requestType,
                CURLOPT_POSTFIELDS  => $postData,
                CURLOPT_HTTPHEADER  => array(
                    "X-HTTP-Method-Override: " . $requestType,
                    'Content-Type: application/json', // Only USE this when requesting JSON data
                ),

                // If HTTP authentication is required, use the below lines.
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD  => $username . ':' . $password
            ));

            // Will dump a beauty json :3
            $responsePaynamics = json_decode($mixResponse, true);
            
            //now update the data based on paynamics response
            $paymentId = $newPaymentDetails->id;
            $paymentDetail = $this->paymentDetail->find($paymentId);

            $paymentDetail->response_message = @$responsePaynamics['response_message'];
            $paymentDetail->response_advise = @$responsePaynamics['response_advise'];
            $paymentDetail->payment_action_info = @$responsePaynamics['payment_action_info'];
            $paymentDetail->response_id = @$responsePaynamics['response_id'];

            // $data['response'] = $responsePaynamics;
            $data['data'] = @$responsePaynamics;

            if ($modePayment->payment_action == 'direct_otc') {
                $paymentDetail->pay_reference = $responsePaynamics['direct_otc_info'][0]['pay_reference'];
                $paymentDetail->pay_instructions = $responsePaynamics['direct_otc_info'][0]['pay_instructions'];
            }

            $paymentDetail->update();



            if ($pmethod == 'onlinebanktransfer' || $pmethod == 'wallet') {
                $data['response_paynamics'] = $responsePaynamics;
                $data['success'] = true;
                // $data['merchantid'] = '000000250621A30BDED2';
                // $data['mkey'] = 'EFBC7AB78E76574FF18A9C87C46ADD7A';
                // $data['username'] = 'iacademy6DkF';
                // $data['password'] = 'j3YdELFVt235';
                // $data['url'] = $url;
                $data['notification_url'] = $notURL;
                $data['response_url'] = $responseURL;
                $data['cancel_url'] = $cancelURL;
                $data['payment_link'] = $paymentDetail->payment_action_info;
            } else {
                $data['payment_link'] = @$responsePaynamics['direct_otc_info'][0]['pay_reference'];
                $data['message'] = 'Please check your email for the payment instructions.';
                $data['success'] = true;

                $this->paymentDetail->sendEmailStudent($paymentDetail);
                // $data['data'] = $newPaymentDetails;
            }

            if (@$responsePaynamics['response_advise']) {
                $data['message'] = "Your transaction has been recorded. Please complete the payment steps.";
                $data['success'] = true;
                DB::commit();
            } else {
                DB::rollback();
                $data['success'] = false;
                $data['message'] = "No response from payment gateway. Please contact the system administrator.";
            }
        } catch (Exception $e) {
            report($e);

            // Rollback and then redirect
            // back to form with errors
            DB::rollback();

            $data['message'] = strval($e);
            $data['success'] = false;
        }

        return response()->json($data, 200);
    }

     //cancel payment transactions
     public function cancelTransaction(Request $request)
     {
         // return $request->all();
         $paymentData = $this->paymentDetail->where('request_id', $request->request_id)->first();
 
         if ($paymentData) {
             if (App::environment(['local', 'staging'])) {
                 $merchantid = "000000250621A30BDED2";
                 $mkey = "EFBC7AB78E76574FF18A9C87C46ADD7A";
                 $username = 'iacademy6DkF';
                 $password = 'j3YdELFVt235';
                 $url = 'https://payin.payserv.net/paygate/transactions/';
             } else {
                 $merchantid = "000000220821A30BF10B";
                 $mkey = "77466C4E0EC95380E8BBE4A5D9ACA6CD";
                 $username = 'ictacademyRMB6';
                 $password = 'Az5WXjNfbym7';
                 $url = 'https://payin.paynamics.net/paygate/transactions/';
             }
 
             $requestId = $request->request_id;
             $origRequestId = $request->request_id;
             $notURL = route('webhook');
             $responseURL = url()->current();
 
             $rawTrx = $merchantid .
                 $requestId .
                 $origRequestId .
                 $paymentData->ip_address .
                 $notURL .
                 $responseURL .
                 $mkey;
 
             $signatureTrx = hash("sha512", $rawTrx);
 
             $arrPostData = [
                 "request_id" => $requestId,
                 "org_request_id" => $origRequestId,
                 "ip_address" => $paymentData->ip_address,
                 "notification_url" => $notURL,
                 "response_url" => $responseURL,
                 "signature" => $signatureTrx,
             ];
 
             // You can set your post data
             $postData = http_build_query($arrPostData); // Raw PHP array
             $postData = json_encode($arrPostData); // Only USE this when request JSON data.
             $mixResponse = $this->executeCurl(array(
                 CURLOPT_URL => $url,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_HTTPGET => true,
                 CURLOPT_VERBOSE => true,
                 CURLOPT_AUTOREFERER => true,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS  => $postData,
                 CURLOPT_HTTPHEADER  => array(
                     "X-HTTP-Method-Override: " . 'POST',
                     'Content-Type: application/json', // Only USE this when requesting JSON data
                 ),
 
                 // If HTTP authentication is required, use the below lines.
                 CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                 CURLOPT_USERPWD  => $username . ':' . $password
             ));
 
             var_export($mixResponse);
             exit;
             // Will dump a beauty json :3
             return $responsePaynamics = json_decode($mixResponse, true);
 
             $data['success'] = true;
             $data['message'] = $responsePaynamics;
         } else {
             $data['success'] = false;
             $data['message'] = 'No transaction found.';
         }
 
         return response()->json($data, 200);
     }

    function executeCurl($arrOptions)
    {

        $mixCH = curl_init();

        foreach ($arrOptions as $strCurlOpt => $mixCurlOptValue) {
            curl_setopt($mixCH, $strCurlOpt, $mixCurlOptValue);
        }

        $mixResponse = curl_exec($mixCH);
        curl_close($mixCH);
        return $mixResponse;
    }
}
