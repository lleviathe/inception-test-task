<?php

namespace App\Http\Controllers;

use App\Data\WinningSnapshotData;
use App\Exceptions\NoPrizesAssignedToRankGroupException;
use App\Exceptions\UserDoesNotHaveRankGroupException;
use App\Http\Resources\PrizeResource;
use App\Models\Prize;
use App\Models\User;
use App\Models\Winning;
use App\Services\PrizeService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class WheelController extends Controller
{
    public function __construct(
        private readonly PrizeService $wheelService,
    ) {
    }

    public function spin(): JsonResponse
    {
        $user = auth()->user();

        try {
            $prize = $this->wheelService->getPrize($user);

            return PrizeResource::make($prize)->response();
        } catch (NoPrizesAssignedToRankGroupException $e) {
            return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (UserDoesNotHaveRankGroupException $e) {
            return response()->json($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function getPossiblePrizes(): JsonResponse
    {
        $user = auth()->user();
        $rankGroup = $user->rankGroup;

        return PrizeResource::collection($rankGroup->prizes)->response();
    }
}
