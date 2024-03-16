<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Enums\LangEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'lang',
        'balance',
        'is_blocked',
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'lang' => LangEnum::class,
            'gender' => GenderEnum::class,
        ];
    }

    public function rankGroup(): BelongsTo
    {
        return $this->belongsTo(RankGroup::class);
    }

    public function winnings(): HasMany
    {
        return $this->hasMany(Winning::class);
    }
}
