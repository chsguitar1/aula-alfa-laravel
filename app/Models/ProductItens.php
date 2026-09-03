<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $product_id
 * @property int $quantity
 * @property string $color
 * @property float $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItens newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItens newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItens query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItens whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItens whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItens whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItens whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItens whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItens whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItens whereValue($value)
 *
 * @mixin \Eloquent
 */
class ProductItens extends Model
{
    use HasUuids;

    protected $fillable = ['quantity', 'color', 'value'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
