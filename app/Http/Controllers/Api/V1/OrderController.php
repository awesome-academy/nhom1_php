<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {}

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->checkout(
            $request->user(),
            $request->validated('note'),
        );

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(201);
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $this->orderService->listForUser($request->user());

        return OrderResource::collection($orders);
    }

    public function show(Request $request, int $order): OrderResource
    {
        $orderModel = $this->orderService->findForUser($request->user(), $order);

        return new OrderResource($orderModel);
    }

    public function cancel(Request $request, int $order): OrderResource
    {
        $orderModel = $this->orderService->cancel($request->user(), $order);

        return new OrderResource($orderModel);
    }
}
