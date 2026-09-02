<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'price', 'unitOfMeasurement'];

    public function productItens() {
        return $this->hasMany(ProductItens::class);
    }
}
