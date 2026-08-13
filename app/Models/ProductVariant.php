<?php

namespace App\Models;

use App\Enums\VariantGroup;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'name', 'variant_group', 'extra_price'];

    protected $casts = [
        'variant_group' => VariantGroup::class,
    ];

}
