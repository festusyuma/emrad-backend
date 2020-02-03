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
     * @var RetailerOrder $order
     */
    public $retailerOrders;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $retailerOrders)
    {
        $this->user = $user;
        $this->retailerOrders = $retailerOrders;
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
                                                        'order' => $this->retailerOrders,
                                                    ])->subject("New Order");
                                                    
    }
}
