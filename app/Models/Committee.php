<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $table = 'committees';
    protected $fillable = [
        'user_id',
        'committee_name_id',
        'designation_id',
        'data_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function designation()
    {
        return $this->belongsTo(CommitteeDesignation::class, 'designation_id');
    }

    public function committeeName()
    {
        return $this->belongsTo(CommitteeName::class, 'committee_name_id');
    }
    
}
