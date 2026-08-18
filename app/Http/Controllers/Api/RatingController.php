<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreRatingRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class RatingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRatingRequest $request, Product $product): JsonResponse
    {
        $rating = $product->ratings()->create([
            'user_id' => $request->user()->id,
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
        ]);

        return response()->json($rating, 201);
    }
}
