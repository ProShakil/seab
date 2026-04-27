<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable([
    'user_id',
    'reunion_period_id',
    'payment_date',
    'trx_id',
    'reference',
    'payment_method',
    'payment_status'
])]
class Payment extends Model
{
    protected $table = 'payments'; 
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reunionPeriod()
    {
        return $this->belongsTo(ReunionPeriod::class, 'reunion_period_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }
}
