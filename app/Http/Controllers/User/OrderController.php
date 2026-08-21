<?php

namespace App\Http\Controllers\User;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display the authenticated user's order history with status filtering.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $orders = $request->user()
            ->orders()
            ->with([
                'items.product.primaryImage',
                'items.productVariant',
            ])
            ->when($status && $status !== 'all', function ($query) use ($status) {
                if ($status === 'processing') {
                    // Nhóm đơn đang chuẩn bị / xử lý
                    return $query->whereIn('status', [
                        OrderStatus::PENDING->value,
                        OrderStatus::PREPARING->value,
                    ]);
                }

                // Lọc riêng đơn Đã xác nhận
                if ($status === 'confirmed') {
                    return $query->where('status', OrderStatus::CONFIRMED->value);
                }

                if ($status === 'completed') {
                    return $query->where('status', OrderStatus::COMPLETED->value);
                }

                if ($status === 'cancelled') {
                    return $query->where('status', OrderStatus::CANCELLED->value);
                }

                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); 

        return view('user.orders.index', [
            'orders' => $orders,
            'currentStatus' => $status ?? 'all',
        ]);
    }
}