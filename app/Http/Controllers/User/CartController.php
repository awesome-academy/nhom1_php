<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the authenticated user's cart page.
     *
     * The cart itself is loaded/mutated client-side via the existing
     * /api/cart, /api/cart/items and /api/checkout endpoints, so this
     * action only needs to render the page shell.
     */
    public function index(): View
    {
        return view('user.cart.index');
    }
}