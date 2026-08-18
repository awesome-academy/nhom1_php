<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    // 98916 - Checkout
    public function checkout(Request $request, OrderService $orderService): JsonResponse
    {
        $order = $orderService->checkout($request->user()->id);

        return response()->json([
            'data' => $order,
        ], 201);
    }

    // 98917 - Order history
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->withCount('items')
            ->latest()
            ->paginate($perPage);

        return OrderResource::collection($orders);
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
}