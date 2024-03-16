<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rank\StoreRankRequest;
use App\Http\Requests\Rank\UpdateRankRequest;
use App\Http\Resources\RankResource;
use App\Models\Rank;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RankController extends Controller
{
    public function index(): JsonResponse
    {
        $ranks = Rank::with('rankGroup');

        return RankResource::collection($ranks)->response();
    }

    public function store(StoreRankRequest $request): JsonResponse
    {
        $rank = Rank::create($request->validated());

        return (new RankResource($rank))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Rank $rank): JsonResponse
    {
        return RankResource::make($rank)->response();
    }

    public function update(UpdateRankRequest $request, Rank $rank): JsonResponse
    {
        $rank->update($request->validated());

        return RankResource::make($rank)->response();
    }

    public function destroy(Rank $rank): JsonResponse
    {
        $rank->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
