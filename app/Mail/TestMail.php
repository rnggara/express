<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = \Config::get("constants.MAIL_FROM_ADDRESS");
        return $this->from($address = $email, $name = \Config::get("constants.APP_LABEL"))
            ->subject("Test Email")
            ->view("_email.test")
            ->with([
                "userFrom" => $email,
                "userTo" => "ranggaanggara8@gmail.com",
            ]);
    }
}
