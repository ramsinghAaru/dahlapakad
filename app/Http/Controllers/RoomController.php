<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Display a listing of available game rooms.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all active rooms that are waiting for players
        $rooms = Room::where('status', 'waiting')
            ->withCount('users')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('rooms.index', [
            'rooms' => $rooms
        ]);
    }

    /**
     * Show the form for creating a new room.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Store a newly created room in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_name' => 'nullable|string|max:50',
            'game_type' => 'required|in:standard,tournament,practice',
            'max_players' => 'required|integer|min:2|max:6',
            'trump_method' => 'required|integer|in:1,2,3',
            'is_public' => 'boolean',
        ]);

        // Generate a unique room code
        do {
            $code = strtoupper(Str::random(6));
        } while (Room::where('code', $code)->exists());

        try {
            DB::beginTransaction();
            // Create the room
            $room = Room::create([
                'code' => $code,
                'name' => $validated['room_name'] ?? null,
                'trump_method' => $validated['trump_method'],
                'status' => 'waiting',
                'settings' => [
                    'is_public' => $validated['is_public'] ?? false,
                    'max_players' => $validated['max_players'],
                    'game_type' => $validated['game_type'],
                    'created_by' => auth()->id(),
                ]
            ]);
           
            // Add the creator as the first player
            $user = auth()->user();
           
            // Add the creator as the first player with all required fields
            $room->users()->attach($user->id, [
                'is_owner' => true,
                'joined_at' => now(),
                'name' => $user->name,
                'seat' => 'N', // Changed from 'seat_position' to 'seat'
                'is_ready' => false,
                'partner_seat' => null, // Added missing required field
                'device_id' => null,    // Added missing required field
                'avatar' => null        // Added missing required field
            ]);
            DB::commit();

            return redirect()->route('rooms.show', $room->code)
                ->with('success', 'Room created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Room creation failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to create room. Please try again.');
        }
    }

    /**
     * Display the specified room.
     *
     * @param  string  $code
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    /**
     * Display the specified room or return room data as JSON.
     *
     * @param  string  $code
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show($code)
    {
        $room = Room::where('code', $code)
            ->with(['players.user', 'games' => function($query) {
                $query->latest()->first();
            }])
            ->firstOrFail();

        $user = auth()->user();
        $currentPlayer = null;
        
        // For API/JSON responses
        if (request()->wantsJson()) {
            return response()->json($room);
        }

        // For web view
        if ($user) {
            $currentPlayer = $room->players->firstWhere('user_id', $user->id);
        }
        
        $isInRoom = $currentPlayer !== null;
        $isOwner = $isInRoom && $currentPlayer->is_owner;
        $isReady = $isInRoom && $currentPlayer->is_ready;

        // If user is not in the room and room is full, redirect back with error
        if (!$isInRoom && $room->users->count() >= ($room->settings['max_players'] ?? 4)) {
            return redirect()->route('play')
                ->with('error', 'This room is already full.');
        }

        // If user is not in the room, add them as a player
        if ($user && !$isInRoom) {
            try {
                // Get the next available seat
                $takenSeats = $room->players->pluck('seat')->filter()->toArray();
                $availableSeats = array_diff(['N', 'E', 'S', 'W'], $takenSeats);
                
                if (empty($availableSeats)) {
                    return back()->with('error', 'This room is already full.');
                }
                
                $seat = reset($availableSeats);
                $partnerSeat = $this->getPartnerSeat($seat);
                
                // Create a new player
                $room->players()->create([
                    'user_id' => $user->id,
                    'is_owner' => false,
                    'name' => $user->name,
                    'seat' => $seat,
                    'partner_seat' => $partnerSeat,
                    'is_ready' => false,
                    'avatar' => $user->avatar,
                    'joined_at' => now()
                ]);
                
                // Refresh the room data
                $room->load('players.user');
                $isInRoom = true;
                $currentPlayer = $room->players->firstWhere('user_id', $user->id);
            } catch (\Exception $e) {
                \Log::error('Failed to join room: ' . $e->getMessage());
                return back()->with('error', 'Failed to join the room. Please try again.');
            }
        }

        // Get the current user's ready status
        $isReady = false;
        if ($user) {
            $pivotData = $room->users->find($user->id)->pivot ?? null;
            $isReady = $pivotData ? (bool)$pivotData->is_ready : false;
        }

        return view('rooms.show', [
            'room' => $room,
            'isOwner' => $isOwner,
            'isReady' => $isReady,
            'currentPlayer' => $currentPlayer,
            'playerCount' => $room->players->count(),
            'maxPlayers' => 4, // Fixed at 4 for this game
        ]);
    }

    /**
     * Handle joining a room via AJAX or form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function join(Request $r, $code)
    {
        $room = Room::where('code', $code)->firstOrFail();
        
        if ($room->status !== 'waiting') {
            // If game is in progress, return current game state
            if ($room->status === 'in_progress') {
                $game = $room->games()->latest()->first();
                return response()->json([
                    'status' => 'game_in_progress',
                    'game_id' => $game->id,
                    'room_status' => $room->status,
                    'message' => 'Game is already in progress'
                ], 200);
            }
            abort(423, 'Game is not accepting new players');
        }
        
        $user = $r->user();
        
        // Check if user is already in this room
        if ($user) {
            $existingPlayer = $room->players()->where('user_id', $user->id)->first();
            if ($existingPlayer) {
                return response()->json([
                    'seat' => $existingPlayer->seat,
                    'partner' => $existingPlayer->partner_seat,
                    'status' => 'reconnected',
                    'room_status' => $room->status,
                    'player_count' => $room->players()->count(),
                    'message' => 'Reconnected to your seat'
                ]);
            }
        }
        
        // Check if room is full
        $playerCount = $room->players()->count();
        if ($playerCount >= 4) {
            return response()->json([
                'status' => 'room_full',
                'room_status' => $room->status,
                'player_count' => $playerCount,
                'message' => 'Room is full'
            ], 409);
        }

        // Assign seat
        $seats = ['N', 'E', 'S', 'W'];
        $taken = $room->players()->pluck('seat')->all();
        $seat = null;
        
        foreach ($seats as $s) {
            if (!in_array($s, $taken)) {
                $seat = $s;
                break;
            }
        }
        
        if (!$seat) {
            abort(500, 'No available seats');
        }
        
        $partner = ['N' => 'S', 'E' => 'W', 'S' => 'N', 'W' => 'E'][$seat];
        
        // Create player
        $display = $r->string('name') ?: ($user->username ?? 'Player');
        $avatar = $r->string('avatar') ?: ($user->avatar ?? null);
        
        $player = $room->players()->create([
            'user_id' => $user?->id,
            'name' => $display,
            'seat' => $seat,
            'partner_seat' => $partner,
            'device_id' => $r->string('device_id'),
            'avatar' => $avatar,
        ]);
        
        // Reload to get updated player count
        $room->load('players');
        $playerCount = $room->players->count();
        
        $response = [
            'seat' => $seat,
            'partner' => $partner,
            'room_status' => $room->status,
            'player_count' => $playerCount,
            'players' => $room->players->map(function($p) use ($user) {
                return [
                    'name' => $p->name,
                    'seat' => $p->seat,
                    'avatar' => $p->avatar,
                    'is_you' => $p->user_id === $user?->id
                ];
            }),
            'message' => 'Joined room successfully'
        ];

        // If room is full, start the game
        if ($playerCount === 4) {
            $game = $this->startGame($room);
            $response['game_id'] = $game->id;
            $response['message'] = 'Game is starting!';
        }

        return response()->json($response);
    }

    public function listPublicRooms(Request $request)
    {
        $query = Room::withCount('players')
            ->where('status', 'waiting')
            ->where('settings->is_public', true)
            ->orderBy('created_at', 'desc');

        // Filter by game type if provided
        if ($gameType = $request->input('game_type')) {
            $query->where('settings->game_type', $gameType);
        }

        $rooms = $query->get()->map(function($room) {
            return [
                'code' => $room->code,
                'player_count' => $room->players_count,
                'created_at' => $room->created_at,
                'settings' => $room->settings,
            ];
        });

        return response()->json($rooms);
    }

    public function leaveRoom(Request $request, $code)
    {
        $room = Room::where('code', $code)->firstOrFail();
        $user = $request->user();
        
        if ($user) {
            $player = $room->players()->where('user_id', $user->id)->first();
        } else {
            $player = $room->players()->where('device_id', $request->string('device_id'))->first();
        }
        
        if (!$player) {
            abort(404, 'Player not found in this room');
        }
        
        $player->delete();
        
        // If no players left, delete the room
        if ($room->players()->count() === 0) {
            $room->delete();
            return response()->json(['message' => 'Room deleted']);
        }
        
        return response()->json(['message' => 'Left the room']);
    }
    
    /**
     * Handle starting a new game in the room.
     *
     * @param  string  $code  The room code
     * @return \Illuminate\Http\Response
     */
    /**
     * Toggle the ready status of the current user
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function toggleReady($code)
    {
        try {
            // Find the room and eager load players with user data
            $room = Room::with(['players.user'])->where('code', $code)->firstOrFail();
            
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.'
                ], 401);
            }
            
            // Find the player record for the current user
            $player = $room->players()->where('user_id', $user->id)->first();
            
            if (!$player) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not a player in this room.'
                ], 403);
            }
            
            // Toggle ready status
            $isReady = !$player->is_ready;
            
            // Update the player's ready status
            $player->update([
                'is_ready' => $isReady
            ]);
            
            // Broadcast the status update to all users in the room
            broadcast(new \App\Events\PlayerStatusUpdated($room, $player, $isReady))->toOthers();
            
            // Reload the room data with fresh player data
            $room->load(['players.user']);
            
            // Check if all players are ready
            $allReady = $this->checkAllPlayersReady($room);
            
            $game = null;
            $gameUrl = null;
            $message = $isReady ? 'You are now ready!' : 'You are no longer ready.';
            
            // If all players are ready, start the game
            if ($allReady) {
                try {
                    // Create a new game
                    $game = $room->games()->create([
                        'hand_no' => 1,
                        'phase' => 'five_card_phase',
                        'dealer_seat' => 'N',
                        'turn_seat' => 'E',
                        'trump_suit' => null,
                        'deck' => json_encode([]),
                        'hands' => json_encode([]),
                        'centre_pile' => json_encode([]),
                        'last_trick' => json_encode([]),
                        'tricks_taken' => json_encode(['N' => 0, 'E' => 0, 'S' => 0, 'W' => 0]),
                        'tens_taken' => json_encode(['NS' => 0, 'EW' => 0]),
                        'consecutive_hands' => json_encode(['NS' => 0, 'EW' => 0]),
                        'kots' => json_encode(['NS' => 0, 'EW' => 0])
                    ]);
                    
                    // Reset all players' ready status
                    $room->players()->update(['is_ready' => false]);
                    
                    // Update room status to 'playing' (must be one of: waiting, dealing, playing, finished)
                    $room->update(['status' => 'playing']);
                    
                    // Broadcast the event to all users in the room
                    event(new \App\Events\AllPlayersReady($room, $game));
                    
                    return response()->json([
                        'success' => true,
                        'is_ready' => $isReady,
                        'all_ready' => true,
                        'game_started' => true,
                        'redirect_url' => route('game.play', $room->code),
                        'message' => 'All players ready! Starting game...'
                    ]);
                    
                } catch (\Exception $e) {
                    \Log::error('Error starting game: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to start game: ' . $e->getMessage()
                    ], 500);
                }
            }
            
            return response()->json([
                'success' => true,
                'is_ready' => $isReady,
                'all_ready' => $allReady,
                'game_started' => $allReady,
                'game' => $game,
                'redirect_url' => $allReady ? route('game.play', $room->code) : null
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in toggleReady: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ready status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Check if all players in the room are ready
     *
     * @param  \App\Models\Room  $room
     * @return bool
     */
    protected function checkAllPlayersReady(Room $room)
    {
        // Eager load players with their user data
        $room->load(['players.user']);
        
        $players = $room->players;
        
        // Need at least 2 players to start
        if ($players->count() < 2) {
            return false;
        }
        
        $ownerFound = false;
        $allReady = true;
        
        // Check all players' status
        foreach ($players as $player) {
            if ($player->is_owner) {
                $ownerFound = true;
                // If owner is not ready, return false immediately
                if (!$player->is_ready) {
                    return false;
                }
            }
            
            // If any player is not ready, set flag to false
            if (!$player->is_ready) {
                $allReady = false;
            }
        }
        
        // Only return true if we found the owner and all players are ready
        return $ownerFound && $allReady;
    }
    
    /**
     * Handle starting a new game in the room.
     *
     * @param  string  $code  The room code
     * @return \Illuminate\Http\Response
     */
    public function startGameRequest($code)
    {
        // Find the room by code with the players and creator relationship loaded
        $room = Room::with(['players', 'creator'])
            ->where('code', $code)
            ->firstOrFail();
        
        try {
            $currentUserId = auth()->id();
            
            // Check if all players are ready
            if (!$this->checkAllPlayersReady($room)) {
                return response()->json([
                    'success' => false,
                    'message' => 'All players must be ready to start the game.'
                ], 400);
            }
            
            // Start the game
            $game = $this->startGame($room);
            
            if (!$game) {
                throw new \Exception('Failed to start the game.');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Game started successfully!',
                'game_started' => true,
                'redirect_url' => route('game.play', $room->code)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Start a new game in the room.
     *
     * @param  \App\Models\Room  $room
     * @return \App\Models\Game
     * @throws \Exception
     */
    protected function startGame(Room $room)
    {
        // Make sure we have at least 2 players
        $players = $room->players()->get();
        if ($players->count() < 2) {
            throw new \Exception('Cannot start game with ' . $players->count() . ' players. Need at least 2.');
        }
        
        // Update room status
        $room->update(['status' => 'in_progress']);
        
        try {
            // Create a new game record
            $game = $room->games()->create([
                'hand_no' => 1,
                'phase' => 'five_card_phase',
                'dealer_seat' => 'N', // Default dealer, can be randomized
                'turn_seat' => 'E',   // Next player after dealer
                'trump_suit' => null,
                'deck' => json_encode([]), // Will be populated in the game logic
                'hands' => json_encode([]), // Will be populated in the game logic
                'centre_pile' => json_encode([]),
                'last_trick' => json_encode([]),
                'tricks_taken' => json_encode(['N' => 0, 'E' => 0, 'S' => 0, 'W' => 0]),
                'tens_taken' => json_encode(['NS' => 0, 'EW' => 0]),
                'consecutive_hands' => json_encode(['NS' => 0, 'EW' => 0]),
                'kots' => json_encode(['NS' => 0, 'EW' => 0])
            ]);
            
            // Reset all players' ready status for the next game
            $room->players()->update(['is_ready' => false]);
            
            return $game;
            
        } catch (\Exception $e) {
            \Log::error('Error starting game: ' . $e->getMessage());
            throw new \Exception('Failed to start game: ' . $e->getMessage());
        }
    }
    
    public function endGame($code)
    {
        $room = Room::where('code', $code)->firstOrFail();
        
        // Update user rankings based on game results
        $this->updateRankings($room);
        
        // Reset room status
        $room->update(['status' => 'waiting']);
        
        // Optionally, archive the game or clear player data
        
        return response()->json(['message' => 'Game ended and rankings updated']);
    }
    
    protected function updateRankings(Room $room)
    {
        // Get the latest game for this room
        $game = $room->games()->latest()->first();
        
        if (!$game) {
            return;
        }
        
        // Get the winning team (simplified example)
        $winningTeam = $game->tens_taken['NS'] > $game->tens_taken['EW'] ? 'NS' : 'EW';
        
        // Update player rankings
        $room->players()->with('user')->chunk(100, function($players) use ($winningTeam) {
            foreach ($players as $player) {
                if ($player->user) {
                    $isWinner = in_array($player->seat, str_split($winningTeam));
                    $player->user->updateRanking($isWinner);
                }
            }
        });
    }
}
