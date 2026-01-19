<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends BaseController
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
        return $this->sendResponse(trans($response, [], "id"), "status");
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return $this->sendError(trans($response, [], "id"));
    }

    protected function validateEmail(Request $request)
    {
        $validator = Validator::make($request->all(),['email' => 'required|email']);

        return $validator->fails();
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = $this->validateEmail($request);
        if($validator){
            return $this->sendError("Email yang anda masukan salah");   
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
}
