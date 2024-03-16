<?php

use App\Models\Prize;
use App\Models\RankGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prize_rank_group', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Prize::class);
            $table->foreignIdFor(RankGroup::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prize_rank_group');
    }
};
