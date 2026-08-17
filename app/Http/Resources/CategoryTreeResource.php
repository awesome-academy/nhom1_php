<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTreeResource extends JsonResource
{
    /**
     * Biến đổi danh mục thành dạng cây cha-con (đệ quy).
     * Dùng cho endpoint GET /api/v1/categories
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'children'    => static::collection($this->whenLoaded('childrenWithNested')),
        ];
    }
}
