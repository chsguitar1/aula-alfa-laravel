<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductItens extends Model
{
    use HasUuids;

    protected $fillable = ['quantity', 'color', 'value'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
