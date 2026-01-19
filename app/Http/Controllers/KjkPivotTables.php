<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User_profile;
use App\Models\Master_city;
use App\Models\Master_province;
use App\Models\User_formal_education;

class KjkPivotTables extends Controller
{
    function index(){

        return view("_bp.pivot.index");
    }

    function view($type, Request $request){

        if($request->a){
            if($type == "applicant"){
                $users = User::where("role_access", 'like', '%"applicant"%')->get();

                $profile = User_profile::whereIn("user_id", $users->pluck("id"))->get();
                $uProfile = [];
                foreach($profile as $item){
                    $uProfile[$item->user_id] = $item;
                }

                $city_name = Master_city::pluck("name", "id");
                $prov_name = Master_province::pluck("name", "id");

                $edu = User_formal_education::whereIn("user_id", $users->pluck("id"))
                    ->orderBy("start_date", "desc")
                    ->get();
                $uEdu = [];
                foreach($edu as $item){
                    $uEdu[$item->user_id] = $item;
                }

                $pivotData = [];

                foreach($users as $item){
                    $prf = $uProfile[$item->id] ?? [];
                    $ed = $uEdu[$item->id] ?? [];
                    $col = [];
                    $col['name'] = $item->name;
                    $col['email'] = $item->email;
                    $col['phone'] = $prf->phone ?? "-";
                    $col['gender'] = $prf->gender ?? "-";
                    $col['birth_date'] = $prf->birth_date ?? "-";
                    $col['religion'] = $prf->religion ?? "-";
                    $col['city'] = $city_name[$prf->city_id ?? 0] ?? "-";
                    $col['province'] = $prov_name[$prf->prov_id ?? 0] ?? "-";
                    $col['education'] = $ed->degree ?? "-";
                    $pivotData[] = $col;
                }

                $data = [
                    "data" => $pivotData
                ];

                return json_encode($data);
            }
        }

        return view("_bp.pivot.view", compact("type"));
    }
}
