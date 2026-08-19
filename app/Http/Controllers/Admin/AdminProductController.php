<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function index(Request $request) : View
    {
        $query = Product::query()->with(['category', 'primaryImage']);

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
        $categoryId = $request->integer('category_id');
        $selectedCategory = Category::with('children')->find($categoryId);

        if ($selectedCategory) {
            $categoryIds = $selectedCategory->children->pluck('id')->push($selectedCategory->id);
            $query->whereIn('category_id', $categoryIds);
        }
    }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->input('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->input('status') === 'inactive') {
            $query->where('is_active', false);
        }

        match ($request->input('sort')) {
            'name_asc' => $query->orderBy('name', 'asc'),
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'stock_asc' => $query->orderBy('stock_quantity', 'asc'),
            default => $query->latest('id'),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::with('parent')->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request): void {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $this->uniqueSlug($validated['name']),
                'description' => $this->composeDescription(
                    $validated['summary'] ?? null,
                    $validated['full_description'] ?? null
                ),
                'type' => $validated['type'],
                'price' => $validated['price'],
                'stock_quantity' => $validated['stock_quantity'],
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->storeImages($product, $request);
            $this->syncVariants($product, $request);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã tạo sản phẩm mới.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::with('children')->orderBy('name')->get();
        $product->load(['images', 'variants']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $product): void {
            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $validated['name'] !== $product->name
                    ? $this->uniqueSlug($validated['name'], $product->id)
                    : $product->slug,
                'description' => $this->composeDescription(
                    $validated['summary'] ?? null,
                    $validated['full_description'] ?? null
                ),
                'type' => $validated['type'],
                'price' => $validated['price'],
                'stock_quantity' => $validated['stock_quantity'],
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->storeImages($product, $request);
            $this->syncVariants($product, $request);
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Đã cập nhật sản phẩm thành công.');
    }

    private function syncVariants(Product $product, Request $request): void
    {
        $product->variants()->delete();

        if ($request->input('type') === 'drink' && $request->has('variants')) {
            foreach ($request->input('variants') as $item) {
                if (!empty($item['name'])) {
                    $product->variants()->create([
                        'variant_group' => $item['variant_group'],
                        'name'          => trim($item['name']),
                        'extra_price'   => $item['extra_price'] ?? 0,
                    ]);
                }
            }
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã xoá sản phẩm.');
    }

    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        $wasPrimary = $image->is_primary;

        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        if ($wasPrimary) {
            $product->images()->oldest('id')->first()?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Đã xoá ảnh sản phẩm.');
    }

    /**
     * Promote an existing image to be the primary/listing image.
     */
    public function setPrimaryImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        DB::transaction(function () use ($product, $image): void {
            $product->images()->where('id', '!=', $image->id)->update(['is_primary' => false]);
            $image->update(['is_primary' => true]);
        });

        return back()->with('success', 'Đã đặt làm ảnh đại diện.');
    }

    /**
     * Store any newly uploaded images for the product.
     * First image of a product with no existing images becomes primary automatically
     * (or whichever index the admin marked via primary_image_index on create).
     */
    private function storeImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $hadImages = $product->images()->exists();
        $primaryIndex = $request->filled('primary_image_index')
            ? (int) $request->input('primary_image_index')
            : null;

        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('products', 'public');

            $isPrimary = ! $hadImages && ($primaryIndex === $index || ($primaryIndex === null && $index === 0));

            $product->images()->create([
                'image_path' => $path,
                'is_primary' => $isPrimary,
            ]);

            if ($isPrimary) {
                $hadImages = true; // only one primary per batch
            }
        }

        if (! $product->images()->where('is_primary', true)->exists()) {
            $product->images()->oldest('id')->first()?->update(['is_primary' => true]);
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $i = 1;

        while (
            Product::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }

    private function composeDescription(?string $summary, ?string $full): string
    {
        $summary = trim((string) $summary);
        $full = trim((string) $full);

        if ($summary === '' && $full === '') {
            return '';
        }

        return "【Product Summary】\n{$summary}\n\n【Mô tả chi tiết】\n{$full}";
    }
}