<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipType extends Model
{
    protected $table = 'membership_types';

    protected $fillable = [
        'name',
        'data_status',
    ];

    protected $casts = [
        'data_status' => 'boolean',
    ];
}
