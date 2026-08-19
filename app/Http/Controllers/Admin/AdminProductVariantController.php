<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VariantGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductVariantResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class AdminProductVariantController extends Controller
{
    /**
     * Add a variant to a product.
     */
    public function store(Request $request, int $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $variant = $product->variants()->create($this->validatedData($request));

        return (new ProductVariantResource($variant))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Update a variant that belongs to the requested product.
     */
    public function update(Request $request, int $id, int $variantId): JsonResponse
    {
        $product = Product::findOrFail($id);
        $variant = $this->findProductVariant($product, $variantId);

        $variant->update($this->validatedData($request));

        return (new ProductVariantResource($variant->fresh()))->response();
    }

    /**
     * Permanently delete a variant that belongs to the requested product.
     */
    public function destroy(int $id, int $variantId): Response
    {
        $product = Product::findOrFail($id);
        $this->findProductVariant($product, $variantId)->delete();

        return response()->noContent();
    }

    /**
     * @return array{name: string, variant_group: string, extra_price: numeric}
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'variant_group' => ['required', Rule::enum(VariantGroup::class)],
            'extra_price' => ['required', 'numeric', 'min:0'],
        ]);
    }

    private function findProductVariant(Product $product, int $variantId): ProductVariant
    {
        return $product->variants()->findOrFail($variantId);
    }
}
