<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductListResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{

    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Product::where('is_active', true)
            ->with(['category', 'primaryImage']);

        // --- Filtering ---

        // Lọc theo type (food | drink)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Lọc theo category_id
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo khoảng giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // --- Sorting ---
        $sort = $request->query('sort', 'name_asc');

        match ($sort) {
            'name_asc'    => $query->orderBy('name', 'asc'),
            'name_desc'   => $query->orderBy('name', 'desc'),
            'price_asc'   => $query->orderBy('price', 'asc'),
            'price_desc'  => $query->orderBy('price', 'desc'),
            'rating_desc' => $query->orderByRaw('(
                                SELECT COALESCE(AVG(r.rating), 0)
                                FROM ratings r
                                WHERE r.product_id = products.id
                             ) DESC'),
            default       => $query->orderBy('name', 'asc'),
        };

        // --- Pagination ---
        $perPage = min((int) $request->query('per_page', 15), 100);

        return ProductListResource::collection($query->paginate($perPage));
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
