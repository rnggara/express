<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    protected function sendResetLinkResponse(Request $request, $response)
    {
        $hris = $request->hris;
        if(empty($hris)){
            return redirect()->route("forgot.email.sent")->with('status', trans($response));
        } else {
            return redirect()->back()->with('status', trans($response));
        }
    }

    public function showEmailSent(){
        return view("auth.passwords.email_sent");
    }
}
