<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontMessage extends Model
{
    protected $table = 'front_messages';

    protected $fillable = [
        'president_message',
        'vice_president_message',
        'mission',
        'vision',
        'about_seab',
        'membership_process'
    ];
}
