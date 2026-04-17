<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    protected $table = 'relationships';

    protected $fillable = [
        'name',
        'data_status',
    ];

    protected $casts = [
        'data_status' => 'boolean',
    ];
}
