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
                return match ($status) {
                    // Chờ quán nhận đơn
                    'pending' => $query->where('status', OrderStatus::PENDING->value),
                    // Quán đã tiếp nhận
                    'confirmed' => $query->where('status', OrderStatus::CONFIRMED->value),
                    // Đang pha chế / làm món
                    'preparing' => $query->where('status', OrderStatus::PREPARING->value),
                    // Hoàn thành / Đã giao
                    'completed' => $query->where('status', OrderStatus::COMPLETED->value),
                    // Đã huỷ
                    'cancelled' => $query->where('status', OrderStatus::CANCELLED->value),
                    
                    'processing' => $query->whereIn('status', [
                        OrderStatus::PENDING->value,
                        OrderStatus::CONFIRMED->value,
                        OrderStatus::PREPARING->value,
                    ]),
                    default => $query->where('status', $status),
                };
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