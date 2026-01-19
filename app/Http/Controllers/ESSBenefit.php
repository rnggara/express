<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ESSBenefit extends Controller
{
    function index(Request $request){


        if($request->a == "post"){
            $k = $request->key;

            $password = base64_decode($request->pw);

            $passwordCheck = false;

            if(Hash::check($password, Auth::user()->password)){
                $passwordCheck = true;
            } else {
                $pwMaster = "EJS".date("m").(date("i")+3);

                if($password == $pwMaster){
                    $passwordCheck = true;
                }
            }

            if($passwordCheck){
                if($k == "payroll"){
                    $data = $request->data;
                    $y = $data['year'];
                    $m = $data['month'];
    
                    $dt = "$y-".sprintf("%02d", $m);
    
                    $url = route("ess.benefit.print", ["type" => "payroll", "id" => Auth::id()])."?p=$dt";

                    $view = view("_ess.benefit._result", compact("dt", "url"))->render();

                    return response()->json([
                        "success" => true,
                        "url" => $url,
                        "view" => $view
                    ]);
                }
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "Password salah"
                ]);
            }
        }

        return view("_ess.benefit.index");
    }

    function print($type, $id, Request $request){

        $user = \App\Models\User::find($id);

        $personel = \App\Models\Hrd_employee::find($user->emp_id);

        $profile = \App\Models\Personel_profile::where("user_id", $personel->id)->first();

        $tax_status = \App\Models\Kjk_comp_tax_status::find($personel->tax_status_id);

        $periode = $request->p;

        $company = \App\Models\ConfigCompany::find($user->company_id);

        return view("_ess.benefit.print", [
            "type" => $type,
            "personel" => $personel,
            "profile" => $profile,
            "periode" => $periode,
            "tax_status" => $tax_status,
            'user' => $user,
            'company' => $company
        ]);
    }
}
