<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Request $request, OrderService $orderService): JsonResponse
    {
        $order = $orderService->checkout($request->user()->id);

        return response()->json([
            'data' => $order,
        ], 201);
    }
}
