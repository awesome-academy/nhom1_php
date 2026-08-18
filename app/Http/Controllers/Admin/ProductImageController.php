<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{

    public function store(StoreProductImageRequest $request, Product $product): JsonResponse
    {
        $path = $request->file('image')->store("product-images/{$product->id}", 'public');

        try {
            $image = DB::transaction(function () use ($request, $product, $path): ProductImage {
                $isPrimary = $request->boolean('is_primary') || ! $product->images()->exists();

                if ($isPrimary) {
                    $product->images()->update(['is_primary' => false]);
                }

                return $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                ]);
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw $exception;
        }

        return (new ProductImageResource($image))
            ->response()
            ->setStatusCode(201);
    }


    public function destroy(Product $product, ProductImage $image): JsonResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        DB::transaction(function () use ($product, $image): void {
            $wasPrimary = $image->is_primary;
            $image->delete();

            if ($wasPrimary) {
                $product->images()->oldest('id')->first()?->update(['is_primary' => true]);
            }
        });

        Storage::disk('public')->delete($image->image_path);

        return response()->json(null, 204);
    }
}
