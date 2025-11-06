<?php

namespace App\Events;

use App\Models\Room;
use App\Models\Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AllPlayersReady implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $game;
    public $redirectUrl;

    /**
     * Create a new event instance.
     */
    public function __construct(Room $room, Game $game)
    {
        $this->room = $room;
        $this->game = $game;
        $this->redirectUrl = route('game.play', $room->code);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('room.' . $this->room->id);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'all.players.ready';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'room_id' => $this->room->id,
            'game_id' => $this->game->id,
            'redirect_url' => $this->redirectUrl,
            'message' => 'All players are ready! Starting game...',
            'game_started' => true
        ];
    }
}
