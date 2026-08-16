<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSuggestionRequest;
use App\Http\Resources\SuggestionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SuggestionController extends Controller
{
    /**
     * Display a listing of the user's suggestions.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $suggestions = $request->user()->suggestions()
            ->latest()
            ->paginate();

        return SuggestionResource::collection($suggestions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSuggestionRequest $request): JsonResponse
    {
        $suggestion = $request->user()->suggestions()->create([
            'content' => $request->validated()['content'],
            'status' => 'pending',
        ]);

        return response()->json(new SuggestionResource($suggestion), 201);
    }
}
