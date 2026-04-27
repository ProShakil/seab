<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable([
    'title',
    'fee',
    'start_date',
    'end_date',
    'receipt_model',
    'data_status'
])]
class ReunionPeriod extends Model
{
    protected $table = 'reunion_periods'; 
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
