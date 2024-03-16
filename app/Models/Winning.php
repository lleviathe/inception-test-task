<?php

namespace App\Models;

use App\Data\WinningSnapshotData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Winning extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prize_id',
        'snapshot_data'
    ];

    protected function casts(): array
    {
        return [
            'snapshot_data' => WinningSnapshotData::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prize(): BelongsTo
    {
        return $this->belongsTo(Prize::class);
    }
}
