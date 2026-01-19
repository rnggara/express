<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    private $title, $_view, $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $_view, $data)
    {
        $this->title = $title;
        $this->_view = $_view;
        $this->data = $data;
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
            ->subject($this->title)
            ->view($this->_view)
            ->with($this->data);
    }
}
