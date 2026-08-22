<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UpdateSuggestionRequest;
use App\Http\Resources\AdminSuggestionResource;
use App\Models\Suggestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SuggestionController extends Controller
{
    /**
     * Show the suggestion management page.
     */
    public function manage(): View
    {
        return view('admin.suggestions.manage');
    }

    /**
     * Display a listing of all suggestions.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Suggestion::query()
            ->with(['user:id,name,email', 'reviewer:id,name,email'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $suggestions = $query->paginate(15);

        return AdminSuggestionResource::collection($suggestions);
    }

    /**
     * Review the specified suggestion.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'in:pending,reviewed,rejected'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $suggestion = Suggestion::findOrFail($id);

        $adminId = Auth::guard('admin')->id() ?? $request->user('admin')?->id ?? Auth::id();

        $suggestion->update([
            'status' => $request->input('status'),
            'admin_note' => $request->input('admin_note'),
            'reviewed_by' => $adminId,
        ]);

        $suggestion->load(['user:id,name,email', 'reviewer:id,name,email']);

        return response()->json([
            'message' => __('Đã cập nhật trạng thái góp ý thành công.'),
            'data' => new AdminSuggestionResource($suggestion),
        ]);
    }
}