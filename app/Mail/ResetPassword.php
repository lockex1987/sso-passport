<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $userFullName;
    public $resetToken;

    public function __construct($userFullName, $resetToken)
    {
        $this->userFullName = $userFullName;
        $this->resetToken = $resetToken;
    }

    public function build()
    {
        return $this->subject('[SSO] Hệ thống Xác thực tập trung SSO')
            ->view('emails.resetPassword');
    }
}
