<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Notification;
use App\Http\Controllers\Controller;
use App\Models\ConfigCompany;
use App\Providers\RouteServiceProvider;
use Helper_function;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm(Request $request, $token = null)
    {
        $parent = ConfigCompany::whereNull('id_parent')->orderBy('id')->get();

        $req_uri = $request->getRequestUri();
        $server_host = $request->server('HTTP_HOST');
        $_host = explode(".", $server_host);
        if($_host[0] == "backend"){
            $_host[0] = "hris";
            $server_host = implode(".", $_host);
            $uri = "https://$server_host$req_uri";

            return redirect()->to($uri);
        }

        if (isset($request->i)) {
            $all_company = ConfigCompany::where('tag', $request->i)->get();
            if(!empty($all_company)){
                $kdp = ConfigCompany::find($all_company[0]->id);
                if(!empty($kdp->id_parent)){
                    $parent = $parent->where('id', $kdp->id_parent)->first();
                } else {
                    $parent = $kdp;
                }
            }
        } else {
            $all_company = ConfigCompany::all();
            $kdp = "";
            $parent = $parent->first();
        }
        return view('auth.passwords.reset', [
            'parent_comp' => $parent,
        ])->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        Notification::sendMailPasswordChanged($user->name, $user->email);

        event(new PasswordReset($user));
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return redirect()->route('forgot.password.complete')
                            ->with('status', trans($response));
    }

    public function showComplete(){
        return view("auth.passwords.password_reset");
    }
}
