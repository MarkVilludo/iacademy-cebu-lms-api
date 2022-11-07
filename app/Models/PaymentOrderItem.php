<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrderItem extends Model
{
    use HasFactory;

    public function item()
    {
        return $this->belongsTo(PaymentItem::class, 'item_id', 'id');
    }
}
