<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{AdmissionStudentInformation, AdmissionInterviewSchedule};
use App\Http\Resources\Admissions\InterviewScheduleResource;
use Illuminate\Http\Request;

use App\Mail\SubmitInformationMail;
use App\Mail\Admissions\InterviewNotificationMail;

use DB, Mail;

class InterviewScheduleController extends Controller
{
    //
    public function __construct(AdmissionStudentInformation $studentInformation,
        AdmissionInterviewSchedule $interviewSchedule)
    {
        $this->studentInformation = $studentInformation;
        $this->interviewSchedule = $interviewSchedule;
    }

    public function index($date) {
        $reservedSchedules = $this->interviewSchedule->where('date', $date)
                                                      ->get();

        $data['data'] = $reservedSchedules ? InterviewScheduleResource::collection($reservedSchedules) : [];
        $data['messsage'] = 'Show scheduled dates.';

        return response()->json($data, 200);
    }

    public function store()
    {
        //check student details
        $checkStudentInfo = $this->studentInformation->where('slug', request('slug'))->first();
        if ($checkStudentInfo) {
            $checkExist = $this->interviewSchedule->where('student_information_id', $checkStudentInfo->id)
                                                    ->where('date', request('date'))
                                                    ->where('time_from', request('time_from'))
                                                    ->where('time_to', request('time_to'))
                                                    ->first();

            if (!$checkExist) {
                $newInterviewSchedule = new $this->interviewSchedule;
                $newInterviewSchedule->student_information_id = $checkStudentInfo->id;
                $newInterviewSchedule->date  = request('date');
                $newInterviewSchedule->time_from  = request('time_from');
                $newInterviewSchedule->time_to  = request('time_to');
                $newInterviewSchedule->save();

                //Email to AO
                //Registrar Email here
                Mail::to('josephedmundcastillo@gmail.com')->send(
                    new InterviewNotificationMail($checkStudentInfo)
                );

                $data['message'] = 'Succcessfully saved interview schedule.';
                $data['success'] = true;
            } else {
                $data['message'] = 'Failed, already exist in database.';
                $data['success'] = false;
            }
        } else {
            $data['message'] = 'Student not found.';
            $data['success'] = false;
        }

        return response()->json($data, 200);
    }
}
