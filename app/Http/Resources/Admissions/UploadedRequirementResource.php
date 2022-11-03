<?php

namespace App\Http\Resources\Admissions;

use Illuminate\Http\Resources\Json\JsonResource;

class UploadedRequirementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'file_path' => @$this->file->getUrlAttribute(),
            'file_type' => @$this->uploadType->label,
        ];
    }
}
