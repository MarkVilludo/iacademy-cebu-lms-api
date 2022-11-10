<?php

namespace App\Http\Resources\Admissions;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\StudentInformationRequirement;
use App\Models\AdmissionUploadType;

class StudentInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $uploadTypes = $this->getUploadTypes(['valid_id', 'psa', 'tor', 'passport', 'payment', 'reservation_fee']);
        $enrollmentUploadTypes = $this->getUploadTypes(['report_card', 'good_moral_certificate', 'tor', 'psa', 'waiver', 'initial_fee']);

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'email' => $this->email,
            'date_of_birth' => $this->date_of_birth,
            'address' => $this->address,
            'status' => $this->status,
            'school' => $this->school,
            'mobile_number' => $this->mobile_number,
            'tel_number' => $this->tel_number,
            'student_type_title' => $this->studentType ? $this->studentType->title : '',
            'student_type' => $this->studentType ? $this->studentType->type : '',
            'desired_program' => $this->desiredProgram ? $this->desiredProgram->title : '',
            'type_id' => $this->studentType ? $this->studentType->id : '',
            'program_id' => $this->desiredProgram ? $this->desiredProgram->id : '',
            'upload_types' => $uploadTypes,
            'interview_remarks' => $this->interview_remarks,
            'acceptance_letter' => $this->acceptance_letter,
            'acceptance_letter_attachments' => AcceptanceAttachmentResource::collection($this->whenLoaded('acceptanceAttachments')),
            'acceptance_letter_sent_date' => $this->acceptance_letter_sent_date ? $this->acceptance_letter_sent_date->format('F d, Y') : '',
            'slug' => $this->slug,
            'logs' => $this->logs,
            'schedule_date' => $this->schedule ? $this->schedule->date : '',
            'schedule_time_from' => $this->schedule ? $this->schedule->time_from : '',
            'schedule_time_to' => $this->schedule ? $this->schedule->time_to : '',
            'payments' => $this->payments,
            'uploaded_requirements' => $this->uploadedRequirements ? UploadedRequirementResource::collection($this->uploadedRequirements) : [],
        ];
    }

    // if ($this->studentType->id == 1) { //UG- Freshman
    //         $keys = ['waiver', 'psa', 'recommendation_form', 'form_128', 'initial_fee'];
    //     }

   

    public function getUploadTypes($keys)
    {
        $uploadTypes = collect();

        $studentUploadTypes = $this->studentType ? AdmissionUploadTypeResource::collection($this->studentType->uploadTypes()->whereIn('key', $keys)->get()) : collect([]);

        return $this->getAdmissionFile($studentUploadTypes);
    }

    public function getAdmissionFile($studentUploadTypes)
    {
        $uploadTypes = collect();

        foreach ($studentUploadTypes->sortBy('order') as $key => $studentUploadType) {
            $requirement = StudentInformationRequirement::where('student_information_id', $this->id)
                                                 ->where('admission_upload_type_id', $studentUploadType->id)
                                                 ->first();

            $file = $requirement ? ($requirement->file ? new AdmissionFileResource($requirement->file) : '') : '';

            $uploadTypes->push([
                'id' => $studentUploadType->id,
                'label' => $studentUploadType->label,
                'key' => $studentUploadType->key,
                'file' => $file,
                'order' => $studentUploadType->order,
                'is_loading' => false,
                'required' => in_array($studentUploadType->key, ['payment', 'reservation_fee']) ? true : false
            ]);
        }

        return $uploadTypes;
    }
}
