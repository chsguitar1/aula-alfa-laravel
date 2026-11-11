<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLanguages extends Model
{
    protected $fillable = [
        'language',
        'user_id',
    ];
}
