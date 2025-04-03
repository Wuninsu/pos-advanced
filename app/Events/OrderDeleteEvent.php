<?php

namespace App\Events;

use App\Models\OrdersModel;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderDeleteEvent implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $user;
    public $admin;
    /**
     * Create a new event instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    public function broadcastOn(): array
    {
        return ['my-channel'];
    }

    public function broadcastAs(): array
    {
        return ['delete-request'];
    }
    // public function __construct(OrdersModel $order, User $user)
    // {
    //     $this->order = $order;
    //     $this->user = $user;
    //     // $this->admin = $admin;
    // }


    // public function broadcastOn()
    // {
    //     return ['channel-01'];
    // }

    // public function broadcastAs()
    // {
    //     return 'event-01';
    // }
}
