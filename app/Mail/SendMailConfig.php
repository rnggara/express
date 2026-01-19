<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailConfig extends Mailable
{
    use Queueable, SerializesModels;

    private $data, $f;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($f, $data)
    {
        $this->f = $f;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $f = $this->f;
        $this->$f($this->data);
    }

    function change_password($data){

        $email = \Config::get("constants.MAIL_FROM_ADDRESS");

        return $this->from($address = $email, $name = \Config::get("constants.APP_LABEL"))
            ->subject("Change Password")
            ->view("_email.change_password")
            ->with($data);
    }
}
