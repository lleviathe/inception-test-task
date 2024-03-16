<?php

namespace App\Http\Controllers;

use App\Http\Requests\Prize\AssignPrizeToRankGroupRequest;
use App\Http\Requests\RankGroup\ChooseRanksRequest;
use App\Http\Requests\RankGroup\StoreRankGroupRequest;
use App\Http\Requests\RankGroup\UpdateRankGroupRequest;
use App\Http\Resources\RankGroupResource;
use App\Jobs\AssignPrizeToRankGroup;
use App\Models\Rank;
use App\Models\RankGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RankGroupController extends Controller
{
    public function index(): JsonResponse
    {
        $rankGroups = RankGroup::with('ranks', 'prizes')->get();

        return RankGroupResource::collection($rankGroups)->response();
    }

    public function store(StoreRankGroupRequest $request): JsonResponse
    {
        $input = $request->validated();
        $rankGroup = RankGroup::create($input);

        return RankGroupResource::make($rankGroup)->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RankGroup $rankGroup): JsonResponse
    {
        return RankGroupResource::make($rankGroup)->response();
    }

    public function update(UpdateRankGroupRequest $request, RankGroup $rankGroup): JsonResponse
    {
        $input = $request->validated();
        $rankGroup->update($input);

        return RankGroupResource::make($rankGroup)->response();
    }

    public function chooseRanks(ChooseRanksRequest $request, RankGroup $rankGroup): JsonResponse
    {
        $input = $request->validated();

        DB::transaction(function () use ($input, $rankGroup) {
            $currentRankIds = $rankGroup->ranks->pluck('id')->toArray();

            $ranksToAdd = array_diff($input['rank_ids'], $currentRankIds);
            $ranksToRemove = array_diff($currentRankIds, $input['rank_ids']);

            if (!empty($ranksToAdd)) {
                Rank::whereIn('id', $ranksToAdd)
                    ->update(['rank_group_id' => $rankGroup->id]);
            }

            if (!empty($ranksToRemove)) {
                Rank::whereIn('id', $ranksToRemove)
                    ->update(['rank_group_id' => null]);
            }
        });

        return response()->json(['rank_group' => $rankGroup->load('ranks')]);
    }

    public function assignPrize(AssignPrizeToRankGroupRequest $request, RankGroup $rankGroup): JsonResponse
    {
        $input = $request->validated();

        AssignPrizeToRankGroup::dispatch($input['prize_id'], $input['number'], $rankGroup);

        return response()->json(status: Response::HTTP_OK);
    }

    public function destroy(RankGroup $rankGroup): JsonResponse
    {
        $rankGroup->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
