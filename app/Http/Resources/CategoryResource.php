<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Biến đổi danh mục thành response chi tiết kèm parent và children trực tiếp.
     * Dùng cho endpoint GET /api/v1/categories/{id}
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'parent'      => $this->when(
                $this->parent_id !== null,
                fn () => [
                    'id'   => $this->parent?->id,
                    'name' => $this->parent?->name,
                    'slug' => $this->parent?->slug,
                ]
            ),
            'children'    => CategoryResource::collection($this->whenLoaded('children')),
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
        ];
    }
}
