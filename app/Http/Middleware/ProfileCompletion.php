<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        $state = Session::get('state') ?? null;

        if(!empty($state)){
            $access_revoked = \DB::table('oauth_access_tokens')
                ->where('name', 'like', "%$state%")
                ->first();

            if(!empty($access_revoked) && $access_revoked->revoked == 1){
                // Auth::logout();

                // $request->session()->invalidate();

                // $request->session()->regenerateToken();

                // $redirect = config("sso.server"). "logout-user?intended=".urlencode(route("login"));

                // redirect($redirect);
            }
        }

        if($user->complete_profile == 0){
            return redirect()->route('complete.profile');
        }

        return $next($request);
    }
}
