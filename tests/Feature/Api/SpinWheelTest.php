<?php

use App\Models\Admin;
use App\Models\Prize;
use App\Models\RankGroup;
use App\Models\User;
use App\Services\PrizeService;
use Symfony\Component\HttpFoundation\Response;

it('can spin the wheel & win prize', function () {
    $admin = Admin::factory()->create();
    $prize1 = Prize::factory()->create();
    $prize2 = Prize::factory()->create();
    $rankGroup = RankGroup::factory()->create();
    $user = User::factory()->create(['rank_group_id' => $rankGroup->id]);

    $this->actingAs($admin)->postJson("/api/rank-groups/$rankGroup->id/prizes", [
        'prize_id' => $prize1->id,
        'number' => 700000,
    ])->json();

    $this->actingAs($admin)->postJson("/api/rank-groups/$rankGroup->id/prizes", [
        'prize_id' => $prize2->id,
        'number' => 300000,
    ])->json();

    $response = $this->actingAs($user)->postJson('/api/spin-wheel', [
        'user_id' => $user->id,
    ]);

    expect($response->status())->toBe(Response::HTTP_OK)
        ->and($response->assertJsonStructure([
            'id',
            'name',
            'description',
            'created_at',
            'updated_at'
        ]));
})->group('api.spin-wheel');

it('can not spin the wheel if user does not have rank group', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/spin-wheel', [
        'user_id' => $user->id,
    ]);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
})->group('api.spin-wheel');

it('can not spin the wheel if there are no prizes assigned to rank group', function () {
    $admin = Admin::factory()->create();
    $rankGroup = RankGroup::factory()->create();
    $user = User::factory()->create(['rank_group_id' => $rankGroup->id]);

    $response = $this->actingAs($user)->postJson('/api/spin-wheel', [
        'user_id' => $user->id,
    ]);

    $response->assertStatus(Response::HTTP_NOT_FOUND);
})->group('api.spin-wheel');

it('can correctly calculate winning odds', function () {
    $prize25 = Prize::factory()->create();
    $prize75 = Prize::factory()->create();

    $rankGroup = RankGroup::factory()->create();

    $rankGroup->prizes()->attach($prize25, ['number' => 25]);
    $rankGroup->prizes()->attach($prize75, ['number' => 75]);

    expect(PrizeService::calculateWinningOdds($prize25, $rankGroup))->toBe(25.0)
        ->and(PrizeService::calculateWinningOdds($prize75, $rankGroup))->toBe(75.0);
})->group('api.spin-wheel');
