<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App, Mail;

class PaymentDetail extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeFilterByField($query, $field, $searchData)
    {
        if ($field && $searchData) {
            if ($field == 'id') {
                $searchData = str_replace('000000', '', $searchData);
                return $query->where($field, 'like', '%' . $searchData . '%');
            }
            return $query->where($field, 'like', '%' . $searchData . '%');
        }
    }

    public function scopeOrderByField($query, $field, $orderBy)
    {
        if ($field && $orderBy) {
            //if field is status
            return $query->orderBy($field, $orderBy);
        } else {
            return $query->orderBy('created_at', 'DESC');
        }
    }

    public function scopeStatus($query, $status)
    {
        if ($status !== "all") {
            //if field is status
            return $query->where('status', $status);
        }
    }

    public function scopeDepartment($query, $departmentId)
    {
        if ($departmentId) {
            return $query->whereHas('orders', function ($query) use ($departmentId) {
                        $query->whereHas('item', function ($query) use ($departmentId) {
                            $query->where('department_id', $departmentId);
                        });
            });
        }
    }

    public function studentInfo()
    {
        return $this->belongsTo(AdmissionStudentInformation::class, 'student_information_id', 'id');
    }
    public function mode()
    {
        return $this->hasOne(PaymentMode::class, 'id', 'mode_of_payment_id');
    }

    public function orders()
    {
        return $this->hasMany(PaymentOrderItem::class, 'payment_detail_id', 'id');
    }

    public function sendEmailAfterPayment($paymentDetails)
    {

        //template requestor, finance and dept head
        $toEmail = $paymentDetails->personal_email;
        $toName = @$paymentDetails->first_name . ' ' . @$paymentDetails->last_name;
        $subjectData = 'iACADEMY Finance: Online Payment Confirmation - ' . $toName;

        if (App::environment(['local', 'staging'])) {
            $logo = "http://103.225.39.201:8081/storage/isign/1597359296_Email_header.png";
            $toEmail = 'portal@iacademy.edu.ph';
        } else {
            $logo = "https://portalv2.iacademy.edu.ph/storage/isign/1597359296_Email_header.png";
            $toEmail = $paymentDetails->email_address;
        }

        $toEmail = $paymentDetails->email_address;

        $data = array("payment" => $paymentDetails, 'logo' => $logo);

        Mail::send('emails.admissions.notify_requestor_success_payment', $data, function ($message) use ($toName, $toEmail, $subjectData) {
            $message->to($toEmail, $toName)
                    ->subject($subjectData)
                    ->replyTo('finance@iacademy.edu.ph');
            $message->from('markanthony.villudo@gmail.com', 'iACADEMY Portal');
        });
    }

    public function sendEmailExpired($paymentDetails)
    {
        //template requestor, finance and dept head
        $toEmail = $paymentDetails->personal_email;
        $toName = @$paymentDetails->first_name . ' ' . @$paymentDetails->last_name;
        $subjectData = 'iACADEMY Finance: Online Payment Link Expiration - ' . $toName;

        if (App::environment(['local', 'staging'])) {
            $logo = "http://103.225.39.201:8081/storage/isign/1597359296_Email_header.png";
            $toEmail = 'portal@iacademy.edu.ph';
        } else {
            $logo = "https://portalv2.iacademy.edu.ph/storage/isign/1597359296_Email_header.png";
            $toEmail = $paymentDetails->email_address;
        }

        $toEmail = $paymentDetails->email_address;

        $data = array("payment" => $paymentDetails, 'logo' => $logo);

        Mail::send('emails.finance.notify_expired_transaction', $data, function ($message) use ($toName, $toEmail, $subjectData) {
            $message->to($toEmail, $toName)
                    ->subject($subjectData)
                    ->replyTo('finance@iacademy.edu.ph');
            $message->from('markanthony.villudo@gmail.com', 'iACADEMY Portal');
        });
    }

    public function sendEmailStudent($paymentDetails)
    {
        //template requestor, finance and dept head
        $toEmail = $paymentDetails->personal_email;
        $toName = @$paymentDetails->first_name . ' ' . @$requestedLaptop->last_name;
        $subjectData = 'iACADEMY Finance: Online Payment Instructions';

        if (App::environment(['local', 'staging'])) {
            $logo = "http://103.225.39.201:8081/storage/isign/1597359296_Email_header.png";
            $toEmail = 'portal@iacademy.edu.ph';
        } else {
            $logo = "https://portalv2.iacademy.edu.ph/storage/isign/1597359296_Email_header.png";
            $toEmail = $paymentDetails->email_address;
        }

        $toEmail = $paymentDetails->email_address;

        $data = array("payment" => $paymentDetails, 'logo' => $logo);

        Mail::send('emails.finance.notify_requestor_nonbank_reference', $data, function ($message) use ($toName, $toEmail, $subjectData) {
            $message->to($toEmail, $toName)
                    ->subject($subjectData)
                    ->replyTo('finance@iacademy.edu.ph');
            $message->from('markanthony.villudo@gmail.com', 'iACADEMY Portal');
        });
    }

}
