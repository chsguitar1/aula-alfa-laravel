<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductItens extends Model
{
    protected $fillable = [
        'quantidade',
        'cor',
        'valor',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
