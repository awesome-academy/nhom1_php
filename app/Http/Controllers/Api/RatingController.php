<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreRatingRequest;
use App\Http\Requests\Api\UpdateRatingRequest;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function update(UpdateRatingRequest $request, Rating $rating): JsonResponse
    {
        $rating->update($request->validated());

        return response()->json($rating);
    }

    public function destroy(Request $request, Rating $rating): Response
    {
        $user = $request->user();

        abort_unless(
            $user !== null
                && (int) $user->getAuthIdentifier() === (int) $rating->user_id,
            403,
        );

        $rating->delete();

        return response()->noContent();
    }
}
