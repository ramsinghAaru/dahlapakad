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
            if (!Schema::hasColumn('users', 'rating')) {
                $table->unsignedInteger('rating')->default(1000);
            }
            if (!Schema::hasColumn('users', 'games_played')) {
                $table->unsignedInteger('games_played')->default(0);
            }
            if (!Schema::hasColumn('users', 'games_won')) {
                $table->unsignedInteger('games_won')->default(0);
            }
            if (!Schema::hasColumn('users', 'highest_rating')) {
                $table->unsignedInteger('highest_rating')->default(1000);
            }
            if (!Schema::hasColumn('users', 'rank')) {
                $table->string('rank', 50)->default('Beginner');
            }
            if (!Schema::hasColumn('users', 'last_rank_update')) {
                $table->timestamp('last_rank_update')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't drop the columns in the down method to prevent data loss
        // If you need to rollback, you can create a new migration to drop these columns
    }
};
