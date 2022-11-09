<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdmissionStudentInformation;
use App\Models\AdmissionStudentType;
use App\Models\{AdmissionDesiredProgram, StudentInformationRequirement};
use App\Models\{AdmissionFile, StudentInfoStatusLog};
use Illuminate\Support\Facades\Validator;
use App\Mail\SubmitInformationMail;
use App\Http\Resources\Admissions\{StudentInformationResource, AdmissionFileResource};
use App\Mail\Admissions\{SendAcceptanceLetterMail, SubmitRequirementsMail};
use App\Mail\Admissions\{ForInterviewMail};

use DB, Mail;

class AdmissionProcessController extends Controller
{
    
    //
    public function __construct(
        AdmissionStudentInformation $studentInformation,
        AdmissionStudentType $studentType,
        AdmissionDesiredProgram $desiredProgram,
        AdmissionFile $admissionFile,
        StudentInformationRequirement $studentInformationRequirement,
    ) {
        $this->studentInformation = $studentInformation;
        $this->studentType = $studentType;
        $this->desiredProgram = $desiredProgram;
        $this->admissionFile = $admissionFile;
        $this->studentInformationRequirement = $studentInformationRequirement;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $searchField = $request->search_field;
        $searchData = $request->search_data;
        $orderBy = $request->order_by;

        $paginateCount = 10;
        if ($request->count_content) {
            $paginateCount = $request->count_content;
        }

        $applications = $this->studentInformation->filterByField($searchField, $searchData)
                                ->orderByField($searchField, $orderBy)
                                ->paginate($paginateCount);

        if ($applications) {
            return StudentInformationResource::collection($applications);
            $data['message'] = 'Shows student application available.';
        } else {
            $data['message'] = 'No student application available';
        }

        return response()->json($data, 200);
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

    public function storeInformation()
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make(request()->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|confirmed|email'
            ]); //|unique:admission_student_information,email

            if ($validator->fails()) {
                if (isset($validator->messages()->toArray()['first_name'])) {
                    $message[] = $validator->messages()->toArray()['first_name'][0];
                }

                if (isset($validator->messages()->toArray()['last_name'])) {
                    $message[] = $validator->messages()->toArray()['last_name'][0];
                }

                if (isset($validator->messages()->toArray()['email'])) {
                    $message[] = $validator->messages()->toArray()['email'][0];
                }

                $data['success'] = false;
                $data['response'] = $validator->messages();
                $data['message'] = str_replace('.', '', implode(', ', $message));
                return response()->json($data);
            }

            $studentInformation = new $this->studentInformation();
            $studentInformation->first_name = request('first_name');
            $studentInformation->last_name = request('last_name');
            $studentInformation->middle_name = request('middle_name');
            $studentInformation->email = request('email');
            $studentInformation->school = request('school');
            $studentInformation->mobile_number = request('mobile_number');
            $studentInformation->tel_number = request('tel_number');
            $studentInformation->type_id = request('type_id');
            $studentInformation->program_id = request('program_id');
            $studentInformation->address = request('address');
            $studentInformation->status = 'New';
            $studentInformation->date_of_birth = request('date_of_birth');
            $studentInformation->slug = \Str::uuid();
            $studentInformation->save();

            // Email registrant
            Mail::to($studentInformation)->send(
                new SubmitInformationMail($studentInformation)
            );

            $data['message'] = 'Success! Please check your email for the next step.';
            $data['success'] = true;
            $data['data'] = new StudentInformationResource($studentInformation);
            DB::commit();
            return response()->json($data);
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['response'] = $e->getMessage();
            $data['message'] = 'Server Error.';
            DB::rollback();
            return response()->json($data);
        }
    }

    public function viewInformation($slug)
    {
        $studentInformation = $this->studentInformation::with('acceptanceAttachments')
                                                       ->where('id', $slug)
                                                       ->where('email', request('email'))
                                                       ->first();
        if (!$studentInformation) {
            $studentInformation = $this->studentInformation::with('acceptanceAttachments')
                                                       ->where('slug', $slug)
                                                       ->first();
        }

        if (!$studentInformation) {
            $data['success'] = false;
            $data['data'] = [];
            $data['message'] = 'Not found';
            return response()->json($data);
        }

        $data['success'] = true;
        $data['data'] = new StudentInformationResource($studentInformation);
        return response()->json($data);
    }

    public function viewInformationForAdmission($slug)
    {
        $studentInformation = $this->studentInformation::with('acceptanceAttachments')
                                                       ->where('id', $slug)
                                                       ->where('email', request('email'))
                                                       ->first();
        if (!$studentInformation) {
            $studentInformation = $this->studentInformation::with('acceptanceAttachments')
                                                       ->where('slug', $slug)
                                                       ->first();
        }

        if (!$studentInformation) {
            $data['success'] = false;
            $data['data'] = [];
            $data['message'] = 'Not found';
            return response()->json($data);
        }

        $data['success'] = true;
        $data['data'] = new StudentInformationResource($studentInformation);
        return response()->json($data);
    }

    public function uploadRequirements()
    {
        $validator = Validator::make(request()->all(), [
            'file' => 'required|max:30000'
        ]);

        if ($validator->fails()) {
            if (isset($validator->messages()->toArray()['file'])) {
                $message[] = str_replace('.', '', implode(', ', $validator->messages()->toArray()['file']));
            }

            $data['success'] = false;
            $data['response'] = $validator->messages();
            $data['message'] = str_replace('.', '', implode(', ', $message));
            return response()->json($data);
        }


        //check exist in requirements

        $checkExist = $this->admissionFile->where('type', request('type'))
                                          ->where('slug', request('slug')) 
                                          ->first();

        $filenameWithExt = request()->file('file')->getClientOriginalName();
        $filename = now()->format('dmYHis');
        $extension = request()->file('file')->getClientOriginalExtension();
        $fileNameToStore = $filename . '.' . $extension;
        $path = request()->file('file')->storeAs('public/admission_files', $fileNameToStore);

        if (!$checkExist) {
            $file = new $this->admissionFile();
            $file->filename = $filename;
            $file->type = request('type');
            $file->slug = request('slug');
            $file->orig_filename =  $filenameWithExt;
            $file->filetype = $extension;
            $file->path = url('storage/admission_files/'.$filename.'.'.$extension);
            $file->save();

            $data['data'] = new AdmissionFileResource($file);
        } else {
            $checkExist->orig_filename =  $filenameWithExt;
            $checkExist->filename = $filename;
            $checkExist->filetype = $extension;
            $checkExist->path = url('storage/admission_files/'.$filename.'.'.$extension);
            $checkExist->update();

            $data['data'] = new AdmissionFileResource($checkExist);
        }

        $data['message'] = 'Successfully uploaded';
        $data['success'] = true;
        return response()->json($data);
    }

    public function deleteFile($id)
    {
        $file = $this->admissionFile->find($id);

        if (!$file) {
            $data['success'] = false;
            $data['message'] = 'Not found';
            return response()->json($data);
        }

        \Storage::delete('public/admission_files/' . $file->filename . '.' . $file->filetype);

        $req = $this->studentInformationRequirement
                    ->where('student_information_id', request('student_information_id'))
                    ->where('admission_upload_type_id', request('admission_upload_type_id'))
                    ->where('admission_file_id', $id)
                    ->first();
        if ($req) {
            $req->delete();
        }

        $file->delete();

        $data['success'] = true;
        $data['message'] = 'Successfully deleted!';
        return response()->json($data);
    }

    public function saveRequirements()
    {
        $studentInformation = $this->studentInformation->where('slug', request('slug'))->first();

        if (!$studentInformation) {
            $data['success'] = false;
            $data['data'] = [];
            $data['message'] = 'Not found';
            return response()->json($data);
        }

        $updatedFields = [];

        foreach (request('requirements') as $key => $requirement) {

            $checkExist = $this->studentInformationRequirement->where('student_information_id', $studentInformation->id)
                                                              ->where('admission_file_id', $requirement['file_id'])
                                                              ->first();
            if (!$checkExist) {
                $req = new $this->studentInformationRequirement;
                $req->student_information_id = $studentInformation->id;
                $req->admission_file_id = $requirement['file_id'];
                $req->save();
            }
        }
        $admission = config('settings.admission_staging');

        if (config('app.env') == 'live') {
            $admission = config('settings.admission');
        }

        if (count(request('requirements'))) {
           //Email admissions
            Mail::to($admission)->send(
                new SubmitRequirementsMail($studentInformation, $updatedFields)
            );
        }

        $data['message'] = 'Successfully submitted';
        $data['success'] = true;
        $data['data'] = new StudentInformationResource($studentInformation);
        return response()->json($data);
    }

    public function updateInformation($id)
    {
        $studentInformation = $this->studentInformation->find($id);

        if (!$studentInformation) {
            $data['success'] = false;
            $data['data'] = [];
            $data['message'] = 'Not found';
            return response()->json($data);
        }

        $studentInformation->type_id = request('type_id');
        $studentInformation->program_id = request('program_id');
        $studentInformation->update();

        $data['message'] = 'Successfully updated.';
        $data['success'] = true;
        $data['data'] = new StudentInformationResource($studentInformation);
        return response()->json($data);
    }

    public function updateInformationStatus($slug)
    {
        $studentInformation = $this->studentInformation->where('slug', $slug)->first();

        if (!$studentInformation) {
            $data['success'] = false;
            $data['data'] = [];
            $data['message'] = 'Not found';
            return response()->json($data);
        }

        if (request('program_id')) {
            $studentInformation->program_id = request('program_id');
        }

        $studentInformation->status = request('status');
        $studentInformation->update();

        StudentInfoStatusLog::storeLogs($studentInformation->id, request('status'), request('admissions_officer'), request('remarks'));

        if (request('status') == 'For Interview') {
            Mail::to($studentInformation->email)->send(
                new ForInterviewMail($studentInformation)
            );
        }


        $data['message'] = 'Successfully updated.';
        $data['success'] = true;
        $data['data'] = new StudentInformationResource($studentInformation);
        return response()->json($data);
    }

    public function updateInformationRemarks($id)
    {
        $studentInformation = $this->studentInformation->find($id);

        if (!$studentInformation) {
            $data['success'] = false;
            $data['data'] = [];
            $data['message'] = 'Not found';
            return response()->json($data);
        }

        $studentInformation->interview_remarks = request('interview_remarks');
        $studentInformation->update();

        $data['message'] = 'Successfully updated.';
        $data['success'] = true;
        $data['data'] = new StudentInformationResource($studentInformation);
        return response()->json($data);
    }

    public function getInformations($status)
    {
        $paginateCount = 10;

        if (request('count_content')) {
            $paginateCount = request('count_content');
        }

        return StudentInformationResource::collection(
            $this->studentInformation->filterByStatus($status)
               ->filterByField(request('search_field'), request('search_data'))
               ->orderBy('created_at', 'desc')
               ->paginate($paginateCount)
        );
    }

    public function saveStudentTypes($infoID, $types)
    {
        foreach ($types as $key => $typeID) {
            $applyingAndProgram = new $this->applyingAndProgram();
            $applyingAndProgram->student_information_id = $infoID;
            $applyingAndProgram->ref_id = $typeID;
            $applyingAndProgram->ref_type = 'student_type';
            $applyingAndProgram->save();
        }
    }

    public function uploadAttachments($id)
    {
        $validator = Validator::make(request()->all(), [
            'files.*' => 'max:30000'
        ]);

        if ($validator->fails()) {
            $data['success'] = false;
            $data['response'] = $validator->messages();
            $data['message'] = 'The files may not be greater than 30000 kilobytes.';
            return response()->json($data);
        }

        $studentInformation = $this->studentInformation->find($id);

        if (!$studentInformation) {
            $data['success'] = false;
            $data['message'] = 'Not found';
            return response()->json($data);
        }

        foreach (request('files') as $key => $file) {
            $filenameWithExt = $file->getClientOriginalName();
            $filename = now()->format('dmYHisu');
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '.' . $extension;
            $path = $file->storeAs('public/acceptance_attachments', $fileNameToStore);

            $attachment = new $this->acceptanceLetterAttachment();
            $attachment->student_information_id = $id;
            $attachment->filename = $filename;
            $attachment->orig_filename =  $filenameWithExt;
            $attachment->filetype = $extension;
            $attachment->save();
        }

        $data['message'] = 'Successfully updated.';
        $data['success'] = true;
        $data['data'] = AcceptanceAttachmentResource::collection(
            $studentInformation->acceptanceAttachments
        );
        return response()->json($data);
    }

    public function sendAcceptanceMail($id)
    {
        DB::beginTransaction();

        try {
            $studentInformation = $this->studentInformation->find($id);

            if (!$studentInformation) {
                $data['success'] = false;
                $data['message'] = 'Not found';
                return response()->json($data);
            }

            $studentInformation->acceptance_letter = request('content');
            $studentInformation->acceptance_letter_sent_date = now();
            $studentInformation->status = 'For Reservation';
            $studentInformation->update();

            //Email acceptance letter
            Mail::to($studentInformation->email)->send(
                new SendAcceptanceLetterMail($studentInformation)
            );

            $data['message'] = 'Successfully sent.';
            $data['success'] = true;
            $data['data'] = new StudentInformationResource($studentInformation);
            DB::commit();
            return response()->json($data);
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['response'] = $e->getMessage();
            $data['message'] = 'Server Error.';
            DB::rollback();
            return response()->json($data);
        }
    }

    public function deleteAttachment($id)
    {
        $attachment = $this->acceptanceLetterAttachment->find($id);

        if (!$attachment) {
            $data['success'] = false;
            $data['message'] = 'Not found';
            return response()->json($data);
        }

        \Storage::delete('public/acceptance_attachments/' . $attachment->filename . '.' . $attachment->filetype);

        $attachment->delete();

        $data['success'] = true;
        $data['message'] = 'Successfully deleted!';
        return response()->json($data);
    }

    public function saveDesiredPrograms($infoID, $programs)
    {
        foreach ($programs as $key => $programID) {
            $applyingAndProgram = new $this->applyingAndProgram();
            $applyingAndProgram->student_information_id = $infoID;
            $applyingAndProgram->ref_id = $programID;
            $applyingAndProgram->ref_type = 'desired_program';
            $applyingAndProgram->save();
        }
    }

    public function download($status)
    {
        return Excel::download(new StudentInformationExport($status), 'student_informations.xlsx');
    }
}
