<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Get the authenticated user's profile
     */
    public function profile()
    {
        $user = Auth::user()->load(['activeRoom.players']);
        
        $activeRoom = null;
        if ($user->activeRoom) {
            $player = $user->players()->where('room_id', $user->activeRoom->id)->first();
            
            $activeRoom = [
                'code' => $user->activeRoom->code,
                'status' => $user->activeRoom->status,
                'player_count' => $user->activeRoom->players->count(),
                'players' => $user->activeRoom->players->map(function($player) {
                    return [
                        'name' => $player->name,
                        'seat' => $player->seat,
                        'avatar' => $player->avatar,
                        'is_you' => $player->user_id === Auth::id()
                    ];
                }),
                'your_seat' => $player->seat ?? null,
                'created_at' => $user->activeRoom->created_at->diffForHumans(),
                'game_in_progress' => $user->activeRoom->status === 'in_progress',
                'can_start' => $user->activeRoom->players->count() === 4 && 
                              $user->activeRoom->status === 'waiting'
            ];
        }
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'rating' => $user->rating,
            'rank' => $user->rank,
            'games_played' => $user->games_played,
            'games_won' => $user->games_won,
            'win_rate' => $user->games_played > 0 
                ? round(($user->games_won / $user->games_played) * 100, 2) 
                : 0,
            'highest_rating' => $user->highest_rating,
            'member_since' => $user->created_at->diffForHumans(),
            'active_room' => $activeRoom
        ]);
    }

    /**
     * Update the authenticated user's profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'username' => [
                'sometimes', 
                'string', 
                'max:50', 
                'alpha_dash',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'sometimes', 
                'string', 
                'email', 
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'current_password' => ['required_with:password', 'current_password'],
            'password' => ['sometimes', 'confirmed', 'min:8'],
        ]);
        
        // Update basic info
        $user->fill($request->only(['name', 'username', 'email']));
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->only(['id', 'name', 'username', 'email', 'avatar'])
        ]);
    }
    
    /**
     * Get the leaderboard
     */
    public function leaderboard(Request $request)
    {
        $perPage = min($request->input('per_page', 50), 100);
        $page = max(1, $request->input('page', 1));
        
        $query = User::select([
            'id', 'name', 'username', 'avatar', 'rating', 'rank', 
            'games_played', 'games_won', 'highest_rating', 'created_at'
        ])
        ->where('games_played', '>', 0)
        ->orderBy('rating', 'desc');
        
        // Filter by rank if provided
        if ($rank = $request->input('rank')) {
            $query->where('rank', $rank);
        }
        
        // Get paginated results
        $leaderboard = $query->paginate($perPage, ['*'], 'page', $page);
        
        // Add position (rank) to each user
        $rankedUsers = $leaderboard->getCollection()->map(function($user, $key) use ($leaderboard) {
            $userArray = $user->toArray();
            $userArray['position'] = ($leaderboard->currentPage() - 1) * $leaderboard->perPage() + $key + 1;
            $userArray['win_rate'] = $user->games_played > 0 
                ? round(($user->games_won / $user->games_played) * 100, 2) 
                : 0;
            return $userArray;
        });
        
        // Get current user's position if authenticated
        $currentUserPosition = null;
        if (Auth::check()) {
            $currentUserId = Auth::id();
            $currentUserPosition = User::where('rating', '>', Auth::user()->rating)
                ->where('id', '!=', $currentUserId)
                ->count() + 1;
                
            // If current user is not in the current page, add them to the results
            if (!$leaderboard->contains('id', $currentUserId)) {
                $currentUser = Auth::user();
                $currentUserData = array_merge($currentUser->toArray(), [
                    'position' => $currentUserPosition,
                    'win_rate' => $currentUser->games_played > 0 
                        ? round(($currentUser->games_won / $currentUser->games_played) * 100, 2) 
                        : 0
                ]);
                $leaderboard->push((object)$currentUserData);
            }
        }
        
        return response()->json([
            'data' => $rankedUsers,
            'current_page' => $leaderboard->currentPage(),
            'per_page' => $leaderboard->perPage(),
            'total' => $leaderboard->total(),
            'current_user_position' => $currentUserPosition,
            'ranks' => [
                'Grand Master' => User::where('rank', 'Grand Master')->count(),
                'Master' => User::where('rank', 'Master')->count(),
                'Expert' => User::where('rank', 'Expert')->count(),
                'Advanced' => User::where('rank', 'Advanced')->count(),
                'Intermediate' => User::where('rank', 'Intermediate')->count(),
                'Beginner' => User::where('rank', 'Beginner')->count(),
                'Newbie' => User::where('rank', 'Newbie')->count(),
            ]
        ]);
    }
}
