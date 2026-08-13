<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 
        'type', 'price', 'stock_quantity', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type'      => ProductType::class,
    ];

   
}
