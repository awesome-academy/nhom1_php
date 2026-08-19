<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Phục vụ cả API Client và Admin Category Management.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'slug'=> $this->slug,
            'description'=> $this->description,
            'parent_id'=> $this->parent_id,
            'parent'=> $this->whenLoaded('parent', function () {
                return $this->parent ? [
                    'id'   => $this->parent->id,
                    'name' => $this->parent->name,
                    'slug' => $this->parent->slug,
                ] : null;
            }),
            'children'=> CategoryResource::collection($this->whenLoaded('children')),
            'products_count'=> $this->whenCounted('products'),
            'total_products_count' => $this->total_products_count, 
            'created_at'=> $this->created_at?->toIso8601String(),
            'updated_at'=> $this->updated_at?->toIso8601String(),
        ];
    }
}
