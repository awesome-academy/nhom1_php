<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    // 98916 - Checkout
    public function checkout(
        Request $request,
        OrderService $orderService
    ): JsonResponse {
        $order = $orderService->checkout($request->user()->id);

        return response()->json([
            'data' => $order,
        ], 201);
    }

    // 98917 - User order history
    public function index(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::query()
            ->where('user_id', $request->user()->id)
        // Eager load thêm primaryImage
            ->with(['items.product.primaryImage'])
            ->when($status && $status !== 'all', function ($query) use ($status) {
            $query->where('status', $status);
        })
            ->latest()
            ->paginate(10);
            
        return view('user.orders.index', compact('orders'));
    }

    // 98917 - Order detail
    public function show(Request $request, int $id): OrderResource|JsonResponse
    {
        $order = Order::query()
            ->where('user_id', $request->user()->id)
            ->with('items')
            ->find($id);

        if (! $order) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        return new OrderResource($order);
    }

    // 98918 - Cancel pending/confirmed order
    public function cancel(Request $request, int $id, OrderService $orderService): OrderResource|JsonResponse
    {
        try {
            $order = $orderService->cancelOrder(
                userId: $request->user()->id,
                orderId: $id,
            );
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        return new OrderResource($order);
    }

    
}