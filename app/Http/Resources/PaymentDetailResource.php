<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentDetailResource extends JsonResource
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
            'description' => $this->description,
            'or_number' => $this->or_number,
            'mode' => $this->mode,
            'subtotal_order' => $this->subtotal_order,
            'convenience_fee' => $this->convenience_fee,
            'total_amount_due' => $this->total_amount_due,
            'charges' => $this->charges,
            'request_id' => $this->request_id,
            'response_id' => $this->response_id,
            'response_message' => $this->response_message,
            'payment_action_info' => $this->payment_action_info,
            'status' => $this->status,
            'sy_reference' => $this->sy_reference,
            'updated_at' => $this->updated_at,
            //'is_nonbank' => $this->pmethod == 'nonbank_otc' ? true : false
        ];
    }
}
