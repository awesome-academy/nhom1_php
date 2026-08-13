<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 
        'type', 'price', 'stock_quantity', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

   
}
