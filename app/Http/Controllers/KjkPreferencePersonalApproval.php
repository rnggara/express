<?php

namespace App\Http\Controllers;

use App\Models\Kjk_pref_approval_transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KjkPreferencePersonalApproval extends Controller
{
    function index(){

        $list = ["workgroup", "job_level", "job_grade", "employee_status", "position", "departement", "location", "acting_position"];

        $data = Kjk_pref_approval_transfer::where("company_id", Session::get("company_id"))
            ->pluck("checked", "key_id");

        return view("_crm.preferences.personel.approval.index", compact("list", "data"));
    }

    function save(Request $request){

        $list = ["workgroup", "job_level", "job_grade", "employee_status", "position", "departement", "location", "acting_position"];

        $transfer = $request->transfer ?? [];

        foreach($list as $item){
            $checked = $transfer[$item] ?? 0;

            $data = Kjk_pref_approval_transfer::where([
                "key_id" => $item,
                "company_id" => Session::get('company_id')
            ])->first();
            if(empty($data)){
                $data = new Kjk_pref_approval_transfer();
                $data->key_id = $item;
                $data->company_id = Session::get('company_id');
                $data->created_by = Auth::id();
            }

            $data->checked = $checked;
            $data->updated_by = Auth::id();
            $data->save();
        }

        return redirect()->back();
    }
}
