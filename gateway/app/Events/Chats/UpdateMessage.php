<?php

namespace App\Events\Chats;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Nyholm\Psr7\Request;

class UpdateMessage implements ShouldBroadcast
{
    public $user, $items;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $notification
     */
    public function __construct($user, $items)
    {
        $this->user = $user;
        $this->items = $items;
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user['user_id']);
    }

    public function broadcastAs()
    {
        return 'private.update_message';
    }
}
