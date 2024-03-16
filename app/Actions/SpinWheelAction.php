<?php

namespace App\Actions;

use App\Exceptions\NoPrizesAssignedToRankGroupException;
use App\Exceptions\UserDoesNotHaveRankGroupException;
use App\Models\Prize;
use App\Models\User;
use App\Services\PrizeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class SpinWheelAction
{
    use AsAction;

    public function handle(User $user): Prize
    {
        $rankGroup = $user->rankGroup ?? throw new UserDoesNotHaveRankGroupException();
        $rankGroup->prizes->count() === 0 && throw new NoPrizesAssignedToRankGroupException();

        return $rankGroup->prizes->first(function (Prize $prize) use (&$randomWeight, $rankGroup) {
            // Decrease the random weight by the number of the current prize
            $randomWeight -= $prize->pivot->number;

            // Try to get the winning odds from the cache
            $winningOdds = cache()->get("winning_odds:{$prize->id}:{$rankGroup->id}");

            // If the winning odds are not in the cache, calculate them and store them in the cache
            if ($winningOdds === null) {
                $winningOdds = PrizeService::calculateWinningOdds($prize, $rankGroup);

                // Store the calculated winning odds in the cache
                cache()->forever("winning_odds:{$prize->id}:{$rankGroup->id}", $winningOdds);
            }

            // Return true if the random weight is less than or equal to zero, indicating that this prize has been won
            return $randomWeight <= 0;
        });
    }

    public function asController(Request $request): JsonResponse
    {
        try {
            $prize = $this->handle(auth('web')->user());

            return response()->json($prize);
        } catch (UserDoesNotHaveRankGroupException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        } catch (NoPrizesAssignedToRankGroupException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
