<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'product_name',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (OrderItem $item): void {
            $item->subtotal = round((float) $item->unit_price * $item->quantity, 2);
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getDisplayImageAttribute(): string
    {
        if ($this->variant && !empty($this->variant->image_url)) {
            return Storage::url($this->variant->image_url);
        }

        if ($this->product && $this->product->primaryImage) {
            return Storage::url($this->product->primaryImage->image_url);
        }

        if ($this->product && $this->product->images && $this->product->images->isNotEmpty()) {
            return Storage::url($this->product->images->first()->image_url);
        }

        return 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=200&auto=format&fit=crop';
    }
}
