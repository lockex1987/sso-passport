<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Register extends Mailable
{
    use Queueable, SerializesModels;

    public $fullName;
    public $verifyToken;

    public function __construct($fullName, $verifyToken)
    {
        $this->fullName = $fullName;
        $this->verifyToken = $verifyToken;
    }

    public function build()
    {
        return $this->subject('[SSO] Hệ thống Xác thực tập trung SSO')
            ->view('emails.register');
    }
}
