<?php

namespace App\Http\Controllers;

use App\Models\Att_leave_employee;
use App\Models\Att_leave_request;
use App\Models\Att_reason_condition;
use App\Models\Att_reason_type;
use App\Models\Hrd_employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ESSLeave extends Controller
{
    function index(Request $request){

        // $personel = Hrd_employee::find(Auth::user()->emp_id);

        // $leave = Att_leave_employee::where("emp_id", $personel->id ?? null)
        //     ->where("end_periode", ">=", date("Y-m-d"))
        //     ->get();


        // $reason_types = Att_reason_type::where(function($q) {
        //     $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
        //     $q->orWhere('is_default', 1);
        // })->where('code', "!=", '00')->get();

        // $rcon = Att_reason_condition::whereIn("reason_type_id", $reason_types->pluck("id"))
        //     ->where('company_id', Session::get("company_id"))
        //     ->get();
        // // dd($rcon, $reason_types);

        // $leave_history = Att_leave_request::where('company_id', Session::get("company_id"))
        //     ->where("emp_id", $personel->id ?? null)
        //     ->where(function($q){
        //         $q->whereNotNull("approved_at");
        //         $q->orWhereNotNull("rejected_at");
        //     })
        //     ->get();

        // $kjkOvt = new KjkAttLeave();

        // $last_ref = $kjkOvt->getLastRef("request");
        // $last_ex = $kjkOvt->getLastRef("extend");

        // if($request->a == "detail"){
        //     $item = Att_leave_request::find($request->id);
        //     $rt = $reason_types->where("id", $item->reason_type)->first();
        //     $rs = $rcon->where("id", $item->reason_id)->first();

        //     $view = view("_ess.leave.detail", compact("item", 'rt', 'rs'))->render();

        //     return json_encode([
        //         "view" => $view
        //     ]);
        // }

        $data = $this->getDataLeave($request);

        return view("_ess.leave.index", $data);
    }

    function getDataLeave(Request $request){
        $emp_id = $request->emp ?? Auth::user()->emp_id;
        $user_id = $request->user_id ?? Auth::user()->id;
        $user = User::find($user_id);
        $personel = Hrd_employee::find($emp_id);

        $leave = Att_leave_employee::where("emp_id", $personel->id ?? null)
            ->where("end_periode", ">=", date("Y-m-d"))
            ->get();


        $reason_types = Att_reason_type::where(function($q) use($personel) {
            $q->where("company_id", $personel->company_id)->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->where('code', "!=", '00')->get();

        $rcon = Att_reason_condition::whereIn("reason_type_id", $reason_types->pluck("id"))
            ->where('company_id', $personel->company_id)
            ->get();
        // dd($rcon, $reason_types);

        $leave_history = Att_leave_request::where('company_id', $personel->company_id)
            ->where("emp_id", $personel->id ?? null)
            ->where(function($q){
                $q->whereNotNull("approved_at");
                $q->orWhereNotNull("rejected_at");
            })
            ->get();

        $kjkOvt = new KjkAttLeave();

        $last_ref = $kjkOvt->getLastRef("request");
        $last_ex = $kjkOvt->getLastRef("extend");

        if($request->a == "detail"){
            $item = Att_leave_request::find($request->id);
            $rt = $reason_types->where("id", $item->reason_type)->first();
            $rs = $rcon->where("id", $item->reason_id)->first();

            $view = view("_ess.leave.detail", compact("item", 'rt', 'rs'))->render();

            return json_encode([
                "view" => $view
            ]);
        }

        $data = [
            "leave" => $leave,
            "rcon" => $rcon,
            "reason_types" => $reason_types,
            "personel" => $personel,
            "leave_history" => $leave_history,
            "last_ref" => $last_ref,
            "last_ex" => $last_ex,
        ];

        return $data;
    }
}
