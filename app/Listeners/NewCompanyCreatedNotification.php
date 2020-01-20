<?php

namespace Emrad\Listeners;

use Emrad\Events\NewCompanyCreated;
use Illuminate\Support\Facades\Mail;
use Emrad\Mail\NewCompanyCreatedMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewCompanyCreatedNotification
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
     * @param  NewCompanyCreated  $event
     * @return void
     */
    public function handle(NewCompanyCreated $event)
    {
        $when = now()->addSeconds(1);
        Mail::to($event->user->email)->later($when, new NewCompanyCreatedMail($event->user, $event->company));
    }
}
