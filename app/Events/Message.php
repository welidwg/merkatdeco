<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Message implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $chat_id;
    public $sender_id;
    public $receiver_id;
    public function __construct($chat_id, $sender_id, $receiver_id)
    {
        //
        $this->chat_id = $chat_id;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $id
            = $this->chat_id + $this->receiver_id;
        return [new Channel("chat-" . $id)];
    }

    public function broadcastAs()
    {
        return 'chat';
    }
    public function broadcastWith()
    {
        return ['sender' => $this->sender_id, "chat" => $this->chat_id, "rec" => $this->receiver_id];
    }
}
