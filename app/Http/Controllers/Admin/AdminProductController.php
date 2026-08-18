<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'primaryImage'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->type))
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->category_id))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::whereNotNull('parent_id')->orWhereDoesntHave('children')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
            $data['is_active'] = $request->boolean('is_active', true);

            $product = Product::create($data);

            // 1. Lưu ảnh đại diện chính (Primary Image)
            if ($request->hasFile('primary_image')) {
                $path = $request->file('primary_image')->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => true,
                ]);
            }

            // 2. Lưu bộ sưu tập ảnh (Gallery Images)
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => false,
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['name']) . '-' . $product->id;
            $data['is_active'] = $request->boolean('is_active');

            $product->update($data);

            if ($request->hasFile('primary_image')) {
                $oldPrimary = $product->images()->where('is_primary', true)->first();
                if ($oldPrimary) {
                    Storage::disk('public')->delete($oldPrimary->image_path);
                    $oldPrimary->delete();
                }
                $path = $request->file('primary_image')->store('products', 'public');
                $product->images()->create(['image_path' => $path, 'is_primary' => true]);
            }

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create(['image_path' => $path, 'is_primary' => false]);
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            // 1. Xóa toàn bộ file ảnh vật lý trên storage disk
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            // 2. Xóa các quan hệ phụ thuộc
            $product->images()->delete();
            if (method_exists($product, 'variants')) {
                $product->variants()->delete();
            }

            // 3. Xóa sản phẩm
            $product->delete();
        });

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm thành công!');
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        if ($image->product_id === $product->id) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
            return back()->with('success', 'Đã xóa ảnh thành công.');
        }
        return back()->withErrors(['msg' => 'Ảnh không thuộc sản phẩm này.']);
    }
}