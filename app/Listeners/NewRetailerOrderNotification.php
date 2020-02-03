<?php

namespace Emrad\Listeners;

use Emrad\Events\NewRetailerOrderEvent;
use Illuminate\Support\Facades\Mail;
use Emrad\Mail\NewRetailerOrderMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewRetailerOrderNotification
{
    public $tries = 3;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewRetailerOrderEvent  $event
     * @return void
     */
    public function handle(NewRetailerOrderEvent $event)
    {
        $when = now()->addSeconds(1);
        // Mail::to($event->user->email)->later($when, new NewRetailerOrderMail($event->user, $event->retailerOrders));
        Mail::to(['anselm@intellchub.com', 'kunle@intellchub.com', 'adeniyi_jaiyeoloa@yahoo.com', 'damilare@suvenia.com' ])->later($when, new NewRetailerOrderMail($event->user, $event->retailerOrders));
    }
}
