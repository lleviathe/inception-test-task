<?php

use App\Models\Admin;
use App\Models\Prize;
use App\Models\RankGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

it('can assign prize to rank group', function () {
    $admin = Admin::factory()->create();
    $prize = Prize::factory()->create();
    $rankGroup = RankGroup::factory()->create();

    $response = $this->actingAs($admin, 'admin')->postJson('/api/prizes/assign', [
        'prize_id' => $prize->id,
        'rank_group_id' => $rankGroup->id,
        'number' => 1000000,
    ]);

    sleep(1);

    $response->assertStatus(Response::HTTP_OK);
    $this->assertDatabaseHas('prize_rank_group', [
        'prize_id' => $prize->id,
        'rank_group_id' => $rankGroup->id,
        'number' => 1000000,
    ]);
})->group('api.prize');

it('can not assign prize that already exists in rank group', function () {
    $admin = Admin::factory()->create();
    $prize = Prize::factory()->create();
    $rankGroup = RankGroup::factory()->create();

    $rankGroup->prizes()->attach($prize, ['number' => 1000000]);

    $response = $this->actingAs($admin, 'admin')->postJson('/api/prizes/assign', [
        'prize_id' => $prize->id,
        'rank_group_id' => $rankGroup->id,
        'number' => 1000000,
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $this->assertDatabaseCount('prize_rank_group', 1);
})->group('api.prize');
