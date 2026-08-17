<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryTreeResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::whereNull('parent_id')
            ->with('childrenWithNested')
            ->get();

        return CategoryTreeResource::collection($categories);
    }


    public function show(int $id): CategoryResource|JsonResponse
    {
        $category = Category::with(['parent', 'children'])->find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.',
            ], 404);
        }

        return new CategoryResource($category);
    }


    public function products(int $id, Request $request): AnonymousResourceCollection|JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.',
            ], 404);
        }

        $categoryIds = $category->getAllDescendantIds();

        $perPage = min((int) $request->query('per_page', 15), 100);

        $products = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->with('category')
            ->paginate($perPage);

        return ProductResource::collection($products);
    }
}
