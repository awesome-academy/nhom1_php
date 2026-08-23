<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Request $request, OrderService $orderService): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone'         => ['required', 'string', 'max:20'],
            'address'       => ['required', 'string', 'max:500'],
            'note'          => ['nullable', 'string', 'max:500'],
        ], [
            'customer_name.required' => 'Vui lòng cung cấp họ và tên người nhận.',
            'phone.required'         => 'Vui lòng nhập số điện thoại nhận hàng.',
            'address.required'       => 'Vui lòng nhập địa chỉ nhận hàng.',
        ]);

        $user = $request->user();

        // Tự động lưu phone & address vào profile nếu user chưa có
        if (empty($user->phone) || empty($user->address)) {
            $user->update([
                'phone'   => $user->phone ?: $validated['phone'],
                'address' => $user->address ?: $validated['address'],
            ]);
        }

        $order = $orderService->checkout(
            userId: $user->id,
            customerName: $validated['customer_name'],
            phone: $validated['phone'],
            address: $validated['address'],
            note: $validated['note'] ?? null
        );

        return response()->json([
            'message' => 'Đặt hàng thành công!',
            'data'    => $order,
        ], 201);
    }

    public function index(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with(['items.product.primaryImage'])
            ->when($status && $status !== 'all', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10);
            
        return view('user.orders.index', compact('orders'));
    }

    public function show(Request $request, int $id): OrderResource|JsonResponse
    {
        $order = Order::query()
            ->where('user_id', $request->user()->id)
            ->with('items')
            ->find($id);

        if (! $order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        return new OrderResource($order);
    }

    public function cancel(Request $request, int $id, OrderService $orderService): OrderResource|JsonResponse
    {
        try {
            $order = $orderService->cancelOrder(
                userId: $request->user()->id,
                orderId: $id,
            );
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        return new OrderResource($order);
    }
}