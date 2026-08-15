<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Dùng cho endpoint GET /api/products (danh sách).
     * Chỉ trả các trường cần thiết cho listing + ảnh đại diện (is_primary = true).
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'price'          => $this->price,
            'type'           => $this->type,
            'stock_quantity' => $this->stock_quantity,
            'category'       => $this->when(
                $this->relationLoaded('category'),
                fn () => [
                    'id'   => $this->category?->id,
                    'name' => $this->category?->name,
                    'slug' => $this->category?->slug,
                ]
            ),
            'primary_image'  => $this->when(
                $this->relationLoaded('primaryImage'),
                fn () => $this->primaryImage
                    ? new ProductImageResource($this->primaryImage)
                    : null
            ),
        ];
    }
}
