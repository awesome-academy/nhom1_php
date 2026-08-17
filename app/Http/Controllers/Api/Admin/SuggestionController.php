<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\UpdateSuggestionRequest;
use App\Http\Resources\AdminSuggestionResource;
use App\Models\Suggestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SuggestionController extends Controller
{
    /**
     * Display a listing of all suggestions.
     */
    public function index(): AnonymousResourceCollection
    {
        $suggestions = Suggestion::query()
            ->with(['user:id,name,email', 'reviewer:id,name,email'])
            ->latest()
            ->paginate();

        return AdminSuggestionResource::collection($suggestions);
    }

    /**
     * Review the specified suggestion.
     */
    public function update(UpdateSuggestionRequest $request, Suggestion $suggestion): JsonResponse
    {
        $suggestion->update([
            ...$request->safe()->only(['status', 'admin_note']),
            'reviewed_by' => $request->user()->id,
        ]);

        $suggestion->load(['user:id,name,email', 'reviewer:id,name,email']);

        return response()->json(new AdminSuggestionResource($suggestion));
    }
}
