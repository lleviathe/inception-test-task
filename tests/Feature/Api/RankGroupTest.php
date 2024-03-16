<?php

use App\Models\Admin;
use App\Models\Prize;
use App\Models\Rank;
use App\Models\RankGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

it('correctly updates ranks in a rank group', function () {
    $admin = Admin::factory()->create();
    $rankGroup = RankGroup::factory()->create();
    $existingRanks = Rank::factory()->count(2)->create(['rank_group_id' => $rankGroup->id]);
    $newRanks = Rank::factory()->count(3)->create(['rank_group_id' => null]);

    $retainRankId = $existingRanks->first()->id;
    $addRanksIds = $newRanks->pluck('id')->push($retainRankId)->all();

    $this->actingAs($admin, 'admin')
        ->patchJson("/api/rank-groups/$rankGroup->id/ranks", ['rank_ids' => $addRanksIds])
        ->assertOk();

    foreach ($addRanksIds as $rankId) {
        expect(Rank::find($rankId)->rank_group_id)->toBe($rankGroup->id);
    }

    $unlinkedRankId = $existingRanks->skip(1)->first()->id;

    expect(Rank::find($unlinkedRankId)->rank_group_id)->toBeNull();
})->group('api.rank-groups');

it('can assign prize to rank group', function () {
    $admin = Admin::factory()->create();
    $prize = Prize::factory()->create();
    $rankGroup = RankGroup::factory()->create();

    $response = $this->actingAs($admin, 'admin')->postJson("/api/rank-groups/$rankGroup->id/prizes", [
        'prize_id' => $prize->id,
        'number' => 1000000,
    ]);

    expect($response->status())->toBe(Response::HTTP_OK);

    $this->assertDatabaseHas('prize_rank_group', [
        'prize_id' => $prize->id,
        'rank_group_id' => $rankGroup->id,
        'number' => 1000000,
    ]);
})->group('api.assign-prize', 'api.rank-groups');

it('can not assign prize that already exists in rank group', function () {
    $admin = Admin::factory()->create();
    $prize = Prize::factory()->create();
    $rankGroup = RankGroup::factory()->create();

    $rankGroup->prizes()->attach($prize, ['number' => 1000000]);

    $response = $this->actingAs($admin, 'admin')->postJson("/api/rank-groups/$rankGroup->id/prizes", [
        'prize_id' => $prize->id,
        'number' => 1000000,
    ]);

    expect($response->status())->toBe(Response::HTTP_BAD_REQUEST);

    $this->assertDatabaseCount('prize_rank_group', 1);
})->group('api.assign-prize', 'api.rank-groups');
