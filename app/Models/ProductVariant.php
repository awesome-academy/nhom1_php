<?php

namespace App\Models;

use App\Enums\VariantGroup;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'name', 'variant_group', 'extra_price'];

    protected function casts(): array
    {
        return [
            'variant_group' => VariantGroup::class,
            'extra_price'   => 'decimal:2',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
