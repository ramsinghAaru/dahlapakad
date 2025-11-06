<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('rating')->default(1000)->after('avatar');
            $table->integer('games_played')->default(0)->after('rating');
            $table->integer('games_won')->default(0)->after('games_played');
            $table->integer('highest_rating')->default(1000)->after('games_won');
            $table->string('rank', 50)->default('Beginner')->after('highest_rating');
            $table->timestamp('last_rank_update')->nullable()->after('rank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'rating',
                'games_played',
                'games_won',
                'highest_rating',
                'rank',
                'last_rank_update'
            ]);
        });
    }
};
