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

    public function __construct()
    {
        //
    }

    public function handle(NewCompanyCreated $event)
    {
        info('Company was created '. $event->company->id);
        $when = now()->addSeconds(1);
        Mail::to($event->user->email)->later($when, new NewCompanyCreatedMail($event->user, $event->company));
    }
}
