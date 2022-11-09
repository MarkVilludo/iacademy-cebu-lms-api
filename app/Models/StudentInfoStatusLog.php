<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInfoStatusLog extends Model
{
    use HasFactory;

    public static function storeLogs($id, $status, $ao, $remarks) {
        $newLogs = new self;
        $newLogs->date_change = date('Y-m-d h:i:s');
        $newLogs->student_information_id = $id;
        $newLogs->status = $status;
        $newLogs->admissions_officer = $ao;
        $newLogs->remarks = $remarks;
        $newLogs->save();
    }
}
