<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\DB;

class AdminCategoryController extends Controller
{
    /**
     * GET /admin/categories
     * Danh sách đầy đủ category cho admin kèm phân trang, tìm kiếm hoặc lọc cha/con.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Category::query()->with(['parent'])->withCount('products');

        if ($request->filled('keyword')) {
            $keyword = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $request->keyword);
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }

        if ($request->has('parent_id')) {
            $parentId = $request->get('parent_id');
            if ($parentId === 'null' || is_null($parentId)) {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $parentId);
            }
        }

        $perPage = $request->input('per_page', 15);
        $categories = $query->latest('id')->paginate($perPage);

        return CategoryResource::collection($categories);
    }

    /**
     * POST /admin/categories
     * Tạo mới category.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return (new CategoryResource($category))
            ->additional(['message' => 'Category created successfully.'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * GET /admin/categories/{category}
     * Chi tiết category kèm các category con liên kết.
     */
    public function show(Category $category): JsonResponse
    {
        $category->load(['parent', 'children'])->loadCount('products');

        return (new CategoryResource($category))
            ->additional(['message' => 'Category retrieved successfully.'])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * PUT/PATCH /admin/categories/{category}
     * Cập nhật thông tin category.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return (new CategoryResource($category->fresh(['parent'])))
            ->additional(['message' => 'Category updated successfully.'])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * DELETE /admin/categories/{category}
     * Xóa category.
     */
    public function destroy(Category $category): JsonResponse
    {
        if ($category->products()->exists()) {
            return response()->json([
                'message' => 'Cannot delete category that contains products.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::transaction(function () use ($category) {
            $category->children()->update(['parent_id' => null]);
            $category->delete();
        });

        return response()->json([
            'message' => 'Category deleted successfully.',
        ], Response::HTTP_OK);
    }
}