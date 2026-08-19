<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:500'],
            'full_description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['food', 'drink'])],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'primary_image_index' => ['nullable', 'integer', 'min:0'],

            'variants'                  => ['nullable', 'array'],
            'variants.*.variant_group'  => ['required_with:variants', 'string', 'in:size,sugar,ice,topping'],
            'variants.*.name'           => ['required_with:variants', 'string', 'max:100'],
            'variants.*.extra_price'    => ['required_with:variants', 'numeric', 'min:0'],
        ];
    }
}