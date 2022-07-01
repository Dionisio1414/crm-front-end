<?php

namespace App\Events\Notifications;

//use App\Users\Model\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Nyholm\Psr7\Request;

class PrivateMessage implements ShouldBroadcast
{
    public $user, $message, $items, $users;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $notification
     */
    public function __construct($user, $message, $items)
    {
        $this->user = $user;
        $this->message = $message;
        $this->items = $items;
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
        return 'private.message';
    }
}
