<?php

namespace App\Http\Controllers;

use App\Models\Att_overtime_record;
use App\Models\Kjk_comp_departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ESSOvertime extends Controller
{
    function index(){

        $depts = Kjk_comp_departement::where('company_id', Session::get("company_id"))->get();

        $overtimes = Att_overtime_record::where("emp_id", Auth::user()->emp_id)
            ->whereNotIn("overtime_type", ["auto_in", "auto_out"])
            ->orderBy("overtime_date", "desc")
            ->orderBy("overtime_start_time", "desc")
            ->get();

        $kjkOvt = new KjkAttOvertime();

        $last_ref = $kjkOvt->getLastRef();

        $dcode = \App\Models\Att_day_code::whereIn("id", $overtimes->pluck("reason_id"))->pluck("day_name", "id");

        return view("_ess.overtime.index", compact("depts", "overtimes", "last_ref", "dcode"));
    }

    function detail($id){
        $overtime = Att_overtime_record::find($id);

        $dep = Kjk_comp_departement::find($overtime->departement);

        $dcode = \App\Models\Att_day_code::find($overtime->reason_id);

        $view = view("_ess.overtime.detail", compact("overtime", "dep", "dcode"))->render();

        return json_encode([
            "view" => $view
    ]);
    }

    function delete($id){
        $overtime = Att_overtime_record::find($id);
        if(!empty($overtime)){
            $msg = "Overtime archived";
            $overtime->delete();
        } else {
            $msg = "Overtime not found";
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => $msg,
                "bg" => "bg-danger"
            ]
        ]);
    }
}
