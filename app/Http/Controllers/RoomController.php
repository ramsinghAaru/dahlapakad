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
            $room->users()->attach(auth()->id(), [
                'is_owner' => true,
                'joined_at' => now(),
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
            ->with(['users' => function($query) {
                $query->select('users.id', 'users.name', 'users.avatar')
                    ->withPivot('is_owner', 'joined_at');
            }, 'players', 'games' => function($query) {
                $query->latest()->first();
            }])
            ->firstOrFail();

        $user = auth()->user();
        
        // For API/JSON responses
        if (request()->wantsJson()) {
            return response()->json($room);
        }

        // For web view
        $isInRoom = $user && $room->users->contains($user->id);
        $isOwner = $isInRoom && $room->users->find($user->id)->pivot->is_owner;

        // If user is not in the room and room is full, redirect back with error
        if (!$isInRoom && $room->users->count() >= ($room->settings['max_players'] ?? 4)) {
            return redirect()->route('play')
                ->with('error', 'This room is already full.');
        }

        // If user is not in the room, add them
        if ($user && !$isInRoom) {
            try {
                $room->users()->attach($user->id, [
                    'is_owner' => false,
                    'joined_at' => now(),
                ]);
                
                // Refresh the room data
                $room->load('users');
            } catch (\Exception $e) {
                \Log::error('Failed to join room: ' . $e->getMessage());
                return back()->with('error', 'Failed to join the room. Please try again.');
            }
        }

        return view('rooms.show', [
            'room' => $room,
            'isOwner' => $isOwner,
            'playerCount' => $room->users->count(),
            'maxPlayers' => $room->settings['max_players'] ?? 4,
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
    
    protected function startGame(Room $room)
    {
        // Make sure we have exactly 4 players
        $players = $room->players()->get();
        if ($players->count() !== 4) {
            throw new \Exception('Cannot start game with ' . $players->count() . ' players. Need exactly 4.');
        }
        
        // Update room status
        $room->update(['status' => 'in_progress']);
        
        // Create a new game instance
        $gameController = app(\App\Http\Controllers\GameController::class);
        $game = $gameController->start(new Request(), $room->code);
        
        // Notify all players that the game is starting
        // This would typically be handled by a WebSocket or similar real-time communication
        
        return $game;
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
