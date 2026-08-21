<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Thống kê số liệu KPI tổng quan
        $totalRevenue = (float) Order::where('status', OrderStatus::COMPLETED->value)->sum('total_amount');
        $pendingOrdersCount = Order::where('status', OrderStatus::PENDING->value)->count();
        $preparingOrdersCount = Order::where('status', OrderStatus::PREPARING->value)->count();
        $usersCount = User::where('role', 'user')->count();
        $productsCount = Product::count();
        $lowStockCount = Product::where('stock_quantity', '<=', 5)->count();

        // 2. Biểu đồ doanh thu 7 ngày qua
        $sevenDaysAgo = Carbon::today()->subDays(6);
        $rawRevenueByDate = Order::where('status', OrderStatus::COMPLETED->value)
            ->whereDate('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $chartLabels = [];
        $chartRevenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateObj = Carbon::today()->subDays($i);
            $dateStr = $dateObj->toDateString();
            
            // Format nhãn: "Th 2", "Th 3"... hoặc "21/08"
            $chartLabels[] = $dateObj->format('d/m');
            $chartRevenueData[] = (float) ($rawRevenueByDate[$dateStr] ?? 0);
        }

        // 3. Tỷ trọng sản phẩm theo danh mục gốc (Top 4 + Khác)
        $parentCategories = Category::whereNull('parent_id')->withCount('products')->get();
        $categoryLabels = [];
        $categoryCounts = [];
        foreach ($parentCategories as $cat) {
            $categoryLabels[] = $cat->name;
            $categoryCounts[] = $cat->total_products_count ?? $cat->products_count;
        }

        // 4. Top 5 sản phẩm bán chạy nhất (từ đơn completed)
        $topProducts = OrderItem::whereHas('order', function ($q) {
                $q->where('status', OrderStatus::COMPLETED->value);
            })
            ->select('product_id', 'product_name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_earned'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $recentOrders = Order::with(['user', 'items'])
            ->latest('created_at')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'pendingOrdersCount',
            'preparingOrdersCount',
            'usersCount',
            'productsCount',
            'lowStockCount',
            'chartLabels',
            'chartRevenueData',
            'categoryLabels',
            'categoryCounts',
            'topProducts',
            'recentOrders'
        ));
    }
}