<?php

namespace App\Mail;

use App\Models\ConfigCompany;
use App\Models\Master_company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailConfig extends Mailable
{
    use Queueable, SerializesModels;

    private $user, $collabs;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $collabs)
    {
        $this->user = $user;
        $this->collabs = $collabs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $userFrom = $this->user;
        $userTo = $this->collabs;

        $comp = Master_company::find($userFrom['comp_id']);

        $link = route("account.setting.uc_verify")."?token=".$userTo['token'];

        $email = \Config::get("constants.MAIL_FROM_ADDRESS");

        return $this->from($address = $email, $name = \Config::get("constants.APP_LABEL"))
            ->subject("Undangan kontributor $comp->company_name")
            ->view("_email.test_mail")
            ->with([
                "userFrom" => $userFrom,
                "userTo" => $userTo,
                'comp' => $comp,
                "link" => $link,
            ]);
    }
}
