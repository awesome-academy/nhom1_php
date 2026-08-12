<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_variant_id' => $this->product_variant_id,
            'product_name' => $this->product_name,
            'unit_price' => number_format((float) $this->unit_price, 2, '.', ''),
            'quantity' => $this->quantity,
            'subtotal' => number_format((float) $this->subtotal, 2, '.', ''),
            'product_variant' => $this->when(
                $this->relationLoaded('productVariant') && $this->productVariant !== null,
                fn () => [
                    'id' => $this->productVariant->id,
                    'name' => $this->productVariant->name,
                ]
            ),
        ];
    }
}
