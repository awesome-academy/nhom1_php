<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Biến đổi sản phẩm thành response cơ bản cho danh sách.
     * Dùng cho endpoint GET /api/v1/categories/{id}/products
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'description'    => $this->description,
            'type'           => $this->type,
            'price'          => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'is_active'      => $this->is_active,
            'category'       => $this->when(
                $this->relationLoaded('category'),
                fn () => [
                    'id'   => $this->category?->id,
                    'name' => $this->category?->name,
                    'slug' => $this->category?->slug,
                ]
            ),
        ];
    }
}
