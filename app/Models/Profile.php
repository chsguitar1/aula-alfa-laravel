<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public const TYPE_ADMIN = 'admin';

    protected $table = 'profile';

    protected $fillable = [
        'type',
        'address',
        'user_id'
    ];
}
