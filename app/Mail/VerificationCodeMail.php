<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationCode;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($verificationCode, $name)
    {
        $this->verificationCode = $verificationCode;
        $this->name = $name;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Verification Code')
                    ->html(
                        "<h1>Hello {$this->name}</h1>
                        <p>Your verification code is: <strong>{$this->verificationCode}</strong></p>
                        <p>Use this code to verify your email.</p>"
                    );
    }
}
