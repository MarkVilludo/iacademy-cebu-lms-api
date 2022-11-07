<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModePaymentResource extends JsonResource
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
            'name' => $this->name,
            'pchannel' => $this->pchannel,
            'pmethod' => $this->pmethod,
            'image_url' => $this->image_url,
            'type' => $this->type,
            'charge' => $this->charge,
            'is_nonbank' => $this->pmethod == 'nonbank_otc' ? true : false
        ];
    }
}
