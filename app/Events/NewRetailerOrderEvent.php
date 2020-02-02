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
    public $order;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, RetailerOrder $retailerOrder)
    {
        // dd($user, $company);
        $this->user = $user;
        $this->retailerOrder = $retailerOrder;
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
