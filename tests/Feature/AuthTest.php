<?php

use App\Enums\AuthenticableTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\LangEnum;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('authenticableTypes', [AuthenticableTypeEnum::Admin, AuthenticableTypeEnum::User]);

it('can register user', function () {
    $response = $this->postJson('/api/register', [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'gender' => fake()->randomElement(GenderEnum::values()),
        'lang' => fake()->randomElement(LangEnum::values()),
        'username' => fake()->userName(),
        'email' => fake()->safeEmail(),
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    expect($response->status())
        ->toBe(201)
        ->and($response->json('user'))
        ->toHaveKeys([
            'id',
            'first_name',
            'last_name',
            'gender',
            'lang',
            'username',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at'
        ]);

    $this->assertDatabaseHas('users', [
        'email' => $response->json('user.email'),
    ]);
});

it('can authenticate', function ($authenticableType) {
    $authenticatable = match ($authenticableType) {
        AuthenticableTypeEnum::Admin => Admin::factory()->create(),
        AuthenticableTypeEnum::User => User::factory()->create(),
    };

    $response = $this->postJson('/api/login', [
        'type' => $authenticableType,
        'email' => $authenticatable->email,
        'password' => 'password',
    ]);

    expect($response->status())
        ->toBe(200)
        ->and($response->json('access_token'))
        ->toBeString();
})->with('authenticableTypes');

it('can logout', function ($authenticatableType) {
    $authenticatable = match ($authenticatableType) {
        AuthenticableTypeEnum::Admin => Admin::factory()->create(),
        AuthenticableTypeEnum::User => User::factory()->create(),
    };

    $token = $authenticatable->createToken('authToken')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/logout');

    expect($response->status())
        ->toBe(200)
        ->and($authenticatable->tokens()->count())
        ->toBe(0);
})->with('authenticableTypes');
