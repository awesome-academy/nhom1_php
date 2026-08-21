<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'product_variant_id',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (CartItem $item): void {
            if ($item->quantity < 1) {
                throw new InvalidArgumentException('Cart item quantity must be at least 1.');
            }
        });
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function lineTotal(): float
    {
        $this->loadMissing('product', 'productVariant');

        $price = $this->product->price + ($this->productVariant?->extra_price ?? 0);

        return (float) $price * $this->quantity;

    }

    public function getUnitPriceAttribute(): float
    {
        $this->loadMissing('product', 'productVariant');
        return (float) (($this->product?->price ?? 0) + ($this->productVariant?->extra_price ?? 0));
    }
}
