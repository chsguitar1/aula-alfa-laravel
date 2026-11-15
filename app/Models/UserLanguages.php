<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLanguages extends Model
{
    protected $table = 'user_languages';

    protected $fillable = [
        'language',
        'user_id',
    ];
}
