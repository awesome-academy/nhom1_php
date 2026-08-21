<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $primaryImage = $this->product?->images?->first()?->image_path 
                     ?? $this->product?->image;

        $imageUrl = $primaryImage 
            ? (filter_var($primaryImage, FILTER_VALIDATE_URL) ? $primaryImage : asset('storage/' . $primaryImage))
            : asset('images/default-food.png');

        // Đơn giá = Giá gốc món + Phụ thu biến thể (nếu có)
        $unitPrice = (float) (($this->product?->price ?? 0) + ($this->productVariant?->extra_price ?? 0));
        $lineTotal = (float) ($unitPrice * $this->quantity);

        return [
            'id'                 => $this->id,
            'product_id'         => $this->product_id,
            'product_variant_id' => $this->product_variant_id,
            'product_name'       => $this->product?->name,
            'variant_name'       => $this->productVariant?->name,
            'image_url'          => $imageUrl, 
            'unit_price'         => $unitPrice,
            'quantity'           => (int) $this->quantity,
            'line_total'         => $lineTotal,
            'product'            => [
                'id'        => $this->product?->id,
                'name'      => $this->product?->name,
                'price'     => (float) ($this->product?->price ?? 0),
                'image_url' => $imageUrl,      
            ],
            'variant'            => $this->productVariant ? [
                'id'          => $this->productVariant->id,
                'name'        => $this->productVariant->name,
                'extra_price' => (float) $this->productVariant->extra_price,
            ] : null,
        ];
    }
}