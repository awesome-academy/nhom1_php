<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $unitPrice = (float) $this->product->price;

        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => number_format($unitPrice, 2, '.', ''),
            'line_total' => number_format($unitPrice * $this->quantity, 2, '.', ''),
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => number_format($unitPrice, 2, '.', ''),
            ],
            'product_variant' => $this->when(
                $this->productVariant !== null,
                fn () => [
                    'id' => $this->productVariant->id,
                    'name' => $this->productVariant->name,
                ]
            ),
        ];
    }
}
