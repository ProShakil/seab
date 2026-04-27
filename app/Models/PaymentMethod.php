<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable([
    'name',
    'type',
    'account_number',
    'account_name',
    'description',
    'status',
])]
class PaymentMethod extends Model
{
    protected $table = 'payment_methods'; 
    //
}
