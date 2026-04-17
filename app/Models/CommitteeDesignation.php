<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeDesignation extends Model
{
    protected $table = 'committee_designations';
    protected $fillable = [
        'name',
        'data_status',
    ];

    protected $casts = [
        'data_status' => 'boolean',
    ];
}
