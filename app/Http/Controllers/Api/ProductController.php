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
    /**
     * GET /api/products
     *
     * Danh sách sản phẩm đang hoạt động.
     * Eager load: ảnh đại diện (primaryImage) + danh mục (category).
     * Chưa có filter, sort, pagination theo yêu cầu Task #98903.
     */
    public function index(): AnonymousResourceCollection
    {
        $products = Product::where('is_active', true)
            ->with(['category', 'primaryImage'])
            ->get();

        return ProductListResource::collection($products);
    }

    /**
     * GET /api/products/{id}
     *
     * Chi tiết 1 sản phẩm đang hoạt động.
     * Eager load: toàn bộ album ảnh (images) + tất cả biến thể (variants) + danh mục (category).
     * Chưa có average rating (thuộc Task #98905).
     */
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
