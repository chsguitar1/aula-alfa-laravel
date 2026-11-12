<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLanguages extends Model
{
    protected $table = 'user_languages';
    protected $fillable = ['user_id', 'language'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
