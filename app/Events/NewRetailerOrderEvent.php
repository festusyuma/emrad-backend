<?php

namespace Emrad\Events;

use Emrad\Models\RetailerOrder;
use Emrad\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewRetailerOrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $retailerOrders;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $retailerOrders)
    {
        // dd($user, $retailerOrder);
        $this->user = $user;
        $this->retailerOrders = $retailerOrders;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
