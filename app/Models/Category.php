<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['parent_id', 'name', 'slug', 'description'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenWithNested()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Accessor: Đếm tổng sản phẩm của chính danh mục này và toàn bộ danh mục con.
     */
    public function getTotalProductsCountAttribute(): int
    {
        if ($this->relationLoaded('children') && $this->children->isEmpty()) {
            return (int) ($this->products_count ?? 0);
        }

        $allIds = $this->getAllDescendantIds();
        return \App\Models\Product::whereIn('category_id', $allIds)->count();
    }


    /**
     * Lấy tất cả ID của danh mục hiện tại và toàn bộ cây con.
     * Dùng để query sản phẩm của cả cây danh mục.
     */
    public function getAllDescendantIds(): array
    {
        $ids = [$this->id];
        $queue = [$this->id];

        while (!empty($queue)) {
            $children = static::whereIn('parent_id', $queue)->pluck('id')->toArray();
            $ids = array_merge($ids, $children);
            $queue = $children;
        }

        return $ids;
    }
}

