<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    public const TYPE_ADMIN = 'admin';

    // A migration original (2026_08_27_000637) cria a tabela no singular: "profile".
    protected $table = 'profile';

    protected $fillable = [
        'type',
        'address',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
