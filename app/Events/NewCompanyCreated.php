<?php

namespace Emrad\Events;

use Emrad\Models\Company;
use Emrad\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewCompanyCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $company;

    public function __construct(User $user, Company $company)
    {
        $this->user = $user;
        $this->company = $company;
    }


    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('channel-name');
    }
}
