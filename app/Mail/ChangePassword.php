<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangePassword extends Mailable
{
    use Queueable, SerializesModels;

    public $userFullName;
    public $pathToImage;
    public $pathToAttach;

    public function __construct($userFullName)
    {
        $this->userFullName = $userFullName;
        $this->pathToImage = public_path('images/logo-240.png');
        // $this->pathToAttach = $pathToAttach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[SSO] Hệ thống Xác thực tập trung SSO')
            ->view('emails.changePassword')
            // ->attach($this->pathToAttach)
            ;
    }
}
