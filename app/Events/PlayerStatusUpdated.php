<?php

namespace App\Events;

use App\Models\Room;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $player;
    public $isReady;

    /**
     * Create a new event instance.
     */
    public function __construct(Room $room, $player, $isReady)
    {
        $this->room = $room;
        $this->player = $player;
        $this->isReady = $isReady;
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
        return 'player.status.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'player_id' => $this->player->id,
            'user_id' => $this->player->user_id,
            'is_ready' => $this->isReady,
            'room_id' => $this->room->id,
            'message' => $this->isReady ? 'Player is ready' : 'Player is not ready'
        ];
    }
}
