<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

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
     * Lấy tất cả ID của danh mục hiện tại và toàn bộ cây con (BFS).
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

