<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LangSwitchController extends Controller
{
    function switch(Request $request){
        $par = "web_locale";
        if(!Cookie::has($par)){
            Cookie::queue(Cookie::make($par, $request->locale));
        }

        Cookie::queue($par, $request->locale);

        return json_encode(true);
    }
}
