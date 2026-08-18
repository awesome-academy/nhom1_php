<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'type',
        'price',
        'stock_quantity',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type' => ProductType::class,
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Quan hệ lấy Ảnh đại diện chính (Primary Image)
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessor: Tự động trích xuất Product Summary từ Description
    public function getSummaryAttribute(): string
    {
        if (Str::contains($this->description, '【Product Summary】')) {
            return Str::of($this->description)
                ->after('【Product Summary】')
                ->before('【Mô tả chi tiết】')
                ->trim();
        }

        return Str::limit(strip_tags($this->description), 120);
    }

    // Accessor: Tự động trích xuất Full Description
    public function getFullDescriptionAttribute(): string
    {
        if (Str::contains($this->description, '【Mô tả chi tiết】')) {
            return Str::of($this->description)
                ->after('【Mô tả chi tiết】')
                ->trim();
        }

        return $this->description ?? '';
    }
}
