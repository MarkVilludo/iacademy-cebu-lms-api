<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'price',
        'type',
        'remarks'
    ];

    public function scopeSearch($query, $field, $keyword)
    {
        if ($keyword) {
            if ($field == 'title') {
                return $query->where('title', 'LIKE', "%{$keyword}%");
            }
        }
    }
}
