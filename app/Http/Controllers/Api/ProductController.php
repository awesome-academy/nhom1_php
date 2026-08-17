<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\RatingResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Product::where('is_active', true)
            ->with(['category', 'primaryImage'])
            ->get();

        return ProductListResource::collection($products);
    }

    public function show(int $id): ProductDetailResource|JsonResponse
    {
        $product = Product::where('is_active', true)
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->with(['category', 'images', 'variants'])
            ->find($id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        return new ProductDetailResource($product);
    }

    public function ratings(int $id): JsonResponse
    {
        $product = Product::where('is_active', true)
            ->find($id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        $ratings = $product->ratings()
            ->with('user:id,name,avatar')
            ->latest()
            ->get(['id', 'user_id', 'rating', 'comment', 'created_at']);

        return RatingResource::collection($ratings)
            ->additional([
                'meta' => [
                    'total' => $ratings->count(),
                ],
                'message' => 'Ratings retrieved successfully.',
            ])
            ->response();
    }
}
