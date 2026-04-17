<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $table = 'occupations';

    protected $fillable = [
        'name',
        'data_status',
    ];

    protected $casts = [
        'data_status' => 'boolean',
    ];
}
