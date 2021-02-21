<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
//use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PrivateMessage implements ShouldBroadcast
{
    // Dispatchable,
    use InteractsWithSockets, SerializesModels;

    public $user_id;
    public $msg;

    public function __construct($user_id, $msg)
    {
        $this->user_id = $user_id;
        $this->msg = $msg;
    }

    public function broadcastOn()
    {
        return new Channel('user.'.$this->user_id);
        //return new PrivateChannel('private.user.'.$this->user_id);
    }
}
