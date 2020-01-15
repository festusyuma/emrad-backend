<?php

namespace Emrad\Mail;

use Emrad\User;
use Emrad\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class NewCompanyCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var array $company
     */
    public $company;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Company $company)
    {
        $this->user = $user;
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.new_company_created')->with([
                                                        'user' => $this->user,
                                                        'company' => $this->company,
                                                        // 'url' => URL::signedRoute('verificationapi.verify', ['user' => $this->user->id])
                                                        'url' => "https://emrad.now.sh/verification?token={{ $this->user->id }}"
                                                    ]);
    }
}
