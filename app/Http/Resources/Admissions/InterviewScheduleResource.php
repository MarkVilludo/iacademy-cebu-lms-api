<?php

namespace App\Http\Resources\Admissions;

use Illuminate\Http\Resources\Json\JsonResource;

class InterviewScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'student_information_id' => $this->student_information_id,
            'date' => $this->date,
            'time_from' => date('h:i A' , strtotime($this->time_from)),
            'time_to' => date('h:i A' , strtotime($this->time_to))
        ];
    }
}
