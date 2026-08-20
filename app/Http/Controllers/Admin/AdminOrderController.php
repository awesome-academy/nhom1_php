<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminOrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * 98919 - Admin Order List / Detail / Status Transition APIs.
 */
class AdminOrderController extends Controller
{
    /**
     * GET /api/admin/orders
     * Danh sách tất cả orders (không giới hạn theo user).
     * Hỗ trợ pagination, lọc theo status, sắp xếp mới nhất trước.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        $query = Order::query()
            ->with('user')
            ->withCount('items')
            ->latest();

        if ($request->filled('status')) {
            $statusValue = $request->query('status');
            $status = OrderStatus::tryFrom($statusValue);

            if ($status === null) {
                abort(Response::HTTP_UNPROCESSABLE_ENTITY, "Invalid status value: {$statusValue}");
            }

            $query->where('status', $status->value);
        }

        $orders = $query->paginate($perPage);

        return AdminOrderResource::collection($orders);
    }

    /**
     * GET /api/admin/orders/{id}
     * Chi tiết một order của bất kỳ user nào.
     */
    public function show(int $id): AdminOrderResource|JsonResponse
    {
        $order = Order::query()
            ->with(['items', 'user'])
            ->find($id);

        if (! $order) {
            return response()->json([
                'message' => 'Order not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return new AdminOrderResource($order);
    }

    /**
     * PATCH /api/admin/orders/{id}/status
     * Admin chuyển trạng thái order.
     * Body: { "status": "confirmed" | "preparing" | "completed" | "cancelled" }
     */
    public function updateStatus(
        Request $request,
        int $id,
        OrderService $orderService
    ): AdminOrderResource|JsonResponse {
        $request->validate([
            'status' => [
                'required',
                'string',
                'in:' . implode(',', array_column(OrderStatus::cases(), 'value')),
            ],
        ]);

        $newStatus = OrderStatus::from($request->input('status'));

        try {
            $order = $orderService->adminTransitionStatus($id, $newStatus);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Order not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return (new AdminOrderResource($order))
            ->additional(['message' => 'Order status updated successfully.'])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
