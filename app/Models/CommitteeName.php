<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeName extends Model
{
    protected $table = 'committee_names';

    protected $fillable = [
        'name',
        'data_status',
    ];

    protected $casts = [
        'data_status' => 'boolean',
    ];
    public function members()
    {
        return $this->hasMany(Committee::class, 'committee_name_id');
    }
}