<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profile';

    protected $attributes = ['description' => ''];

    protected $fillable = [
        'type',
        'address',
        'user_id',
    ];
}
