<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'nome',
        'preco',
        'unidade_medida',
    ];
    public function itens(): HasMany
    {
        return $this->hasMany(ProductItens::class);
    }
}
