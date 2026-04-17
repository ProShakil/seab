<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';
    protected $fillable = [
        'type',
        'title',
        'image',
        'video_url',
        'embed_url',
    ];
    
}
