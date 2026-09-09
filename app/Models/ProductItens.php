<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductItens extends Model
{
    protected $fillable = [
        'product_id',
        'quantidade',
        'cor',
        'valor',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
