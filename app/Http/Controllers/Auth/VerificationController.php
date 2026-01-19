<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illumintate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = "/verification-complete";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', [
            "except" => [
                "verify",
                "showVerificationComplete",
                "resend"
            ]
        ]);
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        $user = User::find($request->route("id"));
        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            return $this->showVerificationExpired();
            throw new AuthorizationException();
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return $this->showVerificationExpired();
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect($this->redirectPath())->with('verified', true);
    }

    public function resend(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $user->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }

    public function showVerificationExpired(){
        return view("auth.verification_expired");
    }

    public function showVerificationComplete(){
        return view("auth.verified");
    }
}
