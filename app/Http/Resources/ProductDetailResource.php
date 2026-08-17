<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Dùng cho endpoint GET /api/products/{id} (chi tiết).
     * Trả đầy đủ: thông tin sản phẩm + toàn bộ album ảnh + tất cả biến thể.
     * Chưa bao gồm average rating (thuộc Task #98905).
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'description'    => $this->description,
            'price'          => $this->price,
            'type'           => $this->type,
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
            'images'    => ProductImageResource::collection(
                $this->whenLoaded('images')
            ),
            'variants'  => ProductVariantResource::collection(
                $this->whenLoaded('variants')
            ),
        ];
    }
}
