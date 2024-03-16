<?php

namespace App\Http\Controllers;

use App\Http\Requests\Prize\AssignPrizeToRankGroupRequest;
use App\Http\Requests\Prize\StorePrizeRequest;
use App\Http\Requests\Prize\UpdatePrizeRequest;
use App\Jobs\AssignPrizeToRankGroup;
use App\Models\Prize;
use App\Services\PrizeService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PrizeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Prize::all());
    }

    public function show(Prize $prize): JsonResponse
    {
        return response()->json($prize);
    }

    public function store(StorePrizeRequest $request): JsonResponse
    {
        $prize = Prize::create($request->validated());

        return response()->json($prize, Response::HTTP_CREATED);
    }

    public function update(UpdatePrizeRequest $request, Prize $prize): JsonResponse
    {
        $prize->update($request->validated());

        return response()->json($prize);
    }

    public function destroy(Prize $prize): JsonResponse
    {
        $prize->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
