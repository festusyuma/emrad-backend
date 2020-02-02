<?php

namespace Emrad\Mail;

use Emrad\User;
use Emrad\Models\RetailerOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class NewRetailerOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var array $company
     */
    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, RetailerOrder $order)
    {
        $this->user = $user;
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.new_retailer_order')->with([
                                                        'user' => $this->user,
                                                        'company' => $this->order,
                                                    ])->subject("New Order");
    }
}
