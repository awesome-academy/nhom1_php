<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Dùng cho endpoint GET /api/products/{id} (chi tiết).
     * Trả đầy đủ: thông tin sản phẩm + toàn bộ album ảnh + tất cả biến thể + summary đánh giá.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'type' => $this->type,
            'stock_quantity' => $this->stock_quantity,
            'is_active' => $this->is_active,
            'ratings_avg_rating' => round((float) ($this->ratings_avg_rating ?? 0), 1),
            'ratings_count' => $this->ratings_count ?? 0,
            'category' => $this->when(
                $this->relationLoaded('category'),
                fn () => [
                    'id' => $this->category?->id,
                    'name' => $this->category?->name,
                    'slug' => $this->category?->slug,
                ]
            ),
            'images' => ProductImageResource::collection(
                $this->whenLoaded('images')
            ),
            'variants' => ProductVariantResource::collection(
                $this->whenLoaded('variants')
            ),
        ];
    }
}
