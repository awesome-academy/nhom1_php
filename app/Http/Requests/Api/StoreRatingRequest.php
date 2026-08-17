<?php

namespace App\Http\Requests\Api;

use App\Enums\OrderStatus;
use App\Models\Rating;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $product = $this->route('product');

        return $user->orders()
            ->where('status', OrderStatus::COMPLETED)
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            $product = $this->route('product');

            if ($user && $product) {
                $exists = Rating::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('rating', 'You have already rated this product.');
                }
            }
        });
    }
}
