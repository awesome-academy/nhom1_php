<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display the authenticated user's order history.
     */
    public function index(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('user.orders.index', [
            'orders' => $orders,
        ]);
    }
}
