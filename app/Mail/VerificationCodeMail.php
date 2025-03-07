<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $url;

    public function __construct($code)
    {
        $this->code = $code;
        $this->url = url('/verify-email?code=' . $code);
    }

    public function build()
    {
        return $this->subject('Your Verification Code')
            ->view('emails.verification')
            ->with([
                'code' => $this->code,
                'url'  => $this->url
            ]);
    }
}
