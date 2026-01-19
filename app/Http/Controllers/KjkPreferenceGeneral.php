<?php

namespace App\Http\Controllers;

use App\Models\Master_gender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class KjkPreferenceGeneral extends Controller
{

    private $dir, $uploadDir;

    public function __construct() {
        $this->dir = "_crm.preferences.general";
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
        $this->uploadDir = str_replace("\\", "/", $dir);
    }

    function basic_information_index(){
        $user = Auth::user();
        $genders = Master_gender::where(function($q){
            $q->whereNull("company_id");
            $q->orWhere("company_id", Session::get('company_id'));
        })->get();
        return view("$this->dir.basic_information.index", compact("user", "genders"));
    }

    function basic_information_post(Request $request){
        $user = Auth::user();
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->phone = $request->phone;

        $file = $request->file("image") ?? [];
        if(!empty($file)){
            $d = date("YmdHis");
            $newName = $d."_".$user->id."_profile_".$file->getClientOriginalName();
            if($file->move($this->uploadDir, $newName)){
                $user->user_img = "media/attachments/$newName";
            }
        }
        $user->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Basic Information has been saved",
                "bg" => "bg-success"
            ]
        ]);
    }

    function password_index(){
        return view("$this->dir.password.index");
    }

    function password_post(Request $request){
        $user = Auth::user();

        $password = Hash::make($request->password);
        $user->password = $password;
        $user->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Password has been successfully change",
                "bg" => "bg-success"
            ]
        ]);
    }
}
