<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable([
    'title',
    'data_status'
])]
class breaking_news extends Model
{
    protected $table = 'breaking_news';
}
