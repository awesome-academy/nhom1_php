<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Admin Login Layout
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
