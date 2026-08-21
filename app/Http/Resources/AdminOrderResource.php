<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 98919 - Resource cho Admin Order API.
 * Thêm thông tin user so với OrderResource thông thường.
 */
class AdminOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'status'       => $this->status,
            'total_amount' => $this->total_amount,
            'note'         => $this->note,
            'item_count'   => $this->when(isset($this->items_count), $this->items_count),
            'items'        => OrderItemResource::collection($this->whenLoaded('items')),
            'user'         => $this->when(
                $this->relationLoaded('user'),
                fn () => [
                    'id'    => $this->user->id,
                    'name'  => $this->user->name,
                    'email' => $this->user->email,
                    'phone' => $this->user?->phone,
                ]
            ),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
