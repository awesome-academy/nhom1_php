<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductListResource;
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
            ->with(['category', 'images', 'variants'])
            ->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        return new ProductDetailResource($product);
    }
}
