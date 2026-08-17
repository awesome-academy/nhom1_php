<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users, with basic search and role filtering.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        match ($request->input('sort')) {
            'oldest'   => $query->oldest(),
            'name_asc' => $query->orderBy('name', 'asc'),
            default    => $query->latest(),
        };

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'targetUser' => $user,
        ]);
    }

    /**
     * Update a user's basic info and role.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,user'],
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Cập nhật người dùng thành công.');
    }

    /**
     * Remove a user from the system.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Đã xoá người dùng.');
    }
}
