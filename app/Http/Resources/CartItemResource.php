<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lineTotal = round($this->lineTotal(), 2);

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name,
            'product_variant_id' => $this->product_variant_id,
            'variant_name' => $this->productVariant?->name,
            'quantity' => $this->quantity,
            'unit_price' => $this->quantity > 0
                ? round($lineTotal / $this->quantity, 2)
                : 0,
            'line_total' => $lineTotal,
        ];
    }
}
