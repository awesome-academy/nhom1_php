<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display the public menu / shop listing page (User Discovery & Shopping).
     *
     * The product grid itself is populated client-side from the existing
     * GET /api/products endpoint (search, type, category, price range,
     * rating and sort filters), so the page stays snappy without full
     * reloads. This method only needs to hand the category tree over to
     * the sidebar.
     */
    public function index(): View
    {
        $categories = Category::whereNull('parent_id')
            ->with(['children' => fn ($query) => $query->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('user.menu.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Display a single product's detail page: images, price, rating &
     * reviews, social sharing links and (for drinks) the variant options
     * that were configured for that specific product (size / sugar / ice /
     * topping) — only the groups that actually exist for the product are
     * rendered.
     */
    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'images', 'variants'])
            ->loadAvg('ratings', 'rating')
            ->loadCount('ratings');

        $product->load([
            'ratings' => fn ($query) => $query->with('user:id,name,avatar')->latest(),
        ]);

        $relatedProducts = Product::query()
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage')
            ->latest()
            ->limit(4)
            ->get();

        return view('user.menu.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}