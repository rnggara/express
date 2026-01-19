<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index(){
        Mail::to("ranggaanggara8@gmail.com")->send(new TestMail);
        dd("test");

        return "Email telah dikirim";

        // makeRandomToken();
    }
}
