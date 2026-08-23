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
     * Hiển thị danh sách khách hàng (role = user).
     */
    public function index(Request $request): View
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

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Mở form chỉnh sửa thông tin user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'targetUser' => $user,
        ]);
    }

    /**
     * Cập nhật thông tin cá nhân của user (không sửa email & role).
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', __('Đã cập nhật thông tin người dùng thành công.'));
    }

    /**
     * Xóa người dùng khỏi hệ thống.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', __('Đã xoá người dùng thành công.'));
    }
}