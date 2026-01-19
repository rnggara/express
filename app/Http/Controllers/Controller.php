<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        // if (get_config() == 0){
        //     redirect()->route('install');
        // }

        $par = "web_locale";
        if(!Cookie::has($par)){
            Cookie::queue(Cookie::make($par, "id"));
        }

        Cookie::queue($par, "id");

        App::setLocale("id");
    }
}
