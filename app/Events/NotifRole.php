<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifRole implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $notif;
    public $role;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($notif, $role)
    {
        $this->notif = $notif;
        $this->role = $role;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [new Channel("role-$this->role")];
    }

    public function broadcastAs()
    {
        return 'getNotifRole';
    }
    public function broadcastWith()
    {
        return ['notif' => $this->notif];
    }
}
