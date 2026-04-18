<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable([
    'site_title',
    'headline',
    'subtitle',
    'logo',
    'favicon'
])]

class SiteSetting extends Model
{
    //
}
