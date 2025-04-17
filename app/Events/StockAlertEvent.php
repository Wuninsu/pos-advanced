<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockAlertEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title;
    public $message;
    public $type;

    public $data;
    public function __construct($data)
    {
        $this->data = $data;
        $this->title = $data['title'];
        $this->message = $data['message'];
        $this->type = $data['type'];
    }

    public function broadcastOn()
    {
        return new Channel('notifications'); // Public or use Private if needed
    }


    public function broadcastAs()
    {
        return 'StockAlertEvent';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return $this->data;
    }
}
