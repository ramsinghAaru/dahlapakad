<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get user stats (replace with your actual stats logic)
        $stats = [
            'games_played' => $user->games_played ?? 0,
            'wins' => $user->wins ?? 0,
            'points' => $user->points ?? 0,
            'rank' => $user->rank_position ?? 'N/A',
            'level' => $user->level ?? 1,
            'xp' => $user->xp ?? 0,
            'xp_to_next_level' => $user->xp_to_next_level ?? 1000,
            'level_progress' => min(100, (($user->xp ?? 0) / ($user->xp_to_next_level ?? 1000)) * 100)
        ];
        
        // Get active room if exists
        $activeRoom = $user->activeRoom ?? null;
        
        return view('dashboard', [
            'activeRoom' => $activeRoom,
            'stats' => $stats
        ]);
    }
}
