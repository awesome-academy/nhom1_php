<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('name') && !$this->filled('slug')) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
    }

    public function rules(): array
    {
        $category = $this->route('category');
        $invalidParentIds = [];

        if ($category instanceof Category) {
            $invalidParentIds = $category->children()->pluck('id')->push($category->id)->toArray();
        } elseif (is_numeric($category)) {
            $categoryModel = Category::with('children')->find($category);
            $invalidParentIds = $categoryModel 
                ? $categoryModel->children->pluck('id')->push($categoryModel->id)->toArray() 
                : [(int) $category];
        }

        return [
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:120',
                Rule::unique('categories', 'slug')->ignore($category instanceof Category ? $category->id : $category),
            ],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                Rule::notIn($invalidParentIds), 
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}