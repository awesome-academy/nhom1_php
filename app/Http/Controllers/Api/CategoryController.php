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
    /**
     * GET /api/categories
     *
     * Trả về danh sách tất cả danh mục dưới dạng cây cha-con.
     * Chỉ query các danh mục gốc (parent_id IS NULL) và eager load
     * các danh mục con lồng sâu tối đa 2 cấp.
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::whereNull('parent_id')
            ->with('childrenWithNested')
            ->get();

        return CategoryTreeResource::collection($categories);
    }

    /**
     * GET /api/categories/{id}
     *
     * Trả về thông tin chi tiết của 1 danh mục theo ID,
     * kèm danh mục cha (nếu có) và danh sách con trực tiếp.
     */
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

    /**
     * GET /api/categories/{id}/products
     *
     * Trả về danh sách sản phẩm thuộc danh mục này và toàn bộ cây con.
     * Dùng BFS để lấy tất cả descendant category IDs, sau đó
     * query 1 lần duy nhất với whereIn — tránh N+1.
     */
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
