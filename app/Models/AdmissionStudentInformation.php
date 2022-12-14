<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class AdmissionStudentInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function scopeOrderByField($query, $field, $orderBy)
    {
        if ($field && $orderBy) {
            //if field is status
            return $query->orderBy($field, $orderBy);
        } else {
            return $query->orderBy('created_at', 'DESC');
        }
    }

    protected $casts = [
        'acceptance_letter_sent_date' => 'date'
    ];

    public function studentType()
    {
        return $this->belongsTo(
            'App\Models\AdmissionStudentType',
            'type_id',
        );
    }

    public function desiredProgram()
    {
        return $this->belongsTo(
            'App\Models\AdmissionDesiredProgram',
            'program_id',
        );
    }

    public function studentTypes()
    {
        return $this->belongsToMany(
            'App\Models\AdmissionStudentType',
            'admission_student_applying_and_programs',
            'student_information_id',
            'ref_id'
        )->where('ref_type', 'student_type');
    }

    public function desiredPrograms()
    {
        return $this->belongsToMany(
            'App\Models\AdmissionDesiredProgram',
            'admission_student_applying_and_programs',
            'student_information_id',
            'ref_id'
        )->where('ref_type', 'desired_program');
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status != 'all') {
            return $query->where('status', $status);
        }
    }

    public function scopeFilterByField($query, $field, $searchData)
    {
        if ($field && $searchData) {
            if ($field == 'id') {
                $searchData = str_replace('000000', '', $searchData);
                return $query->where($field, 'like', '%' . $searchData . '%');
            }

            if ($field == 'first_name' || $field == 'last_name') {
                return $query->where('first_name', 'like', '%' . $searchData . '%')
                                  ->orWhere('last_name', 'like', '%' . $searchData . '%')
                                  ->orWhereRaw("concat(first_name, ' ', last_name) like '%$searchData%' ");
            }

            if ($field == 'student_type_title') {
                return $query->whereHas('studentType', function ($query) use ($field, $searchData) {
                            $query->where('title', 'LIKE', "%{$searchData}%");
                });
            }

            if ($field == 'program') {
                return $query->whereHas('desiredProgram', function ($query) use ($field, $searchData) {
                            $query->where('title', 'LIKE', "%{$searchData}%");
                });
            }

            return $query->where($field, 'like', '%' . $searchData . '%');
        }
    }

    public function acceptanceAttachments()
    {
        return $this->hasMany('App\Models\AcceptanceLetterAttachment', 'student_information_id');
    }

    public function uploadedRequirements()
    {
        return $this->hasMany('App\Models\StudentInformationRequirement', 'student_information_id');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\StudentInfoStatusLog', 'student_information_id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\PaymentDetail', 'student_information_id');
    }
    public function schedule()
    {
        return $this->belongsTo('App\Models\AdmissionInterviewSchedule', 'id', 'student_information_id');
    }
}
