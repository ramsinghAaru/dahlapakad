<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop all tables in reverse order of dependency
        $tables = ['moves', 'games', 'players', 'sessions', 'rooms', 'users', 'password_resets'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
            }
        }

        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('username')->unique()->nullable();
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Create password resets table
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Create rooms table
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8)->unique();
            $table->enum('status', ['waiting', 'dealing', 'playing', 'finished'])->default('waiting');
            $table->unsignedTinyInteger('trump_method')->default(1);
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        // Create players table
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_owner')->default(false);
            $table->string('name');
            $table->string('seat')->nullable();
            $table->string('partner_seat')->nullable();
            $table->string('device_id')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamp('joined_at')->useCurrent();
            $table->boolean('is_ready')->default(false);
            $table->timestamps();
        });

        // Create games table
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('hand_no')->default(1);
            $table->enum('phase', ['five_card_phase', 'full_deal', 'playing', 'scoring'])->default('five_card_phase');
            $table->string('dealer_seat');
            $table->string('turn_seat');
            $table->string('trump_suit')->nullable();
            $table->json('deck');
            $table->json('hands');
            $table->json('centre_pile')->default('[]');
            $table->json('last_trick')->default('[]');
            $table->json('tricks_taken')->default('{"N":0,"E":0,"S":0,"W":0}');
            $table->json('tens_taken')->default('{"NS":0,"EW":0}');
            $table->json('consecutive_hands')->default('{"NS":0,"EW":0}');
            $table->json('kots')->default('{"NS":0,"EW":0}');
            $table->string('next_dealer_seat')->nullable();
            $table->timestamps();
        });

        // Create moves table
        Schema::create('moves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->string('seat');
            $table->string('card');
            $table->unsignedInteger('trick_index');
            $table->timestamps();
        });

        // Create sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration
        // To roll back, you would need to manually drop the database
    }
};
