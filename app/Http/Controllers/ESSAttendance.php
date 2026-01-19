<?php

namespace App\Http\Controllers;

use App\Models\Att_day_code;
use App\Models\Att_employee_registration;
use App\Models\Att_leave_request;
use App\Models\Att_periode;
use App\Models\Att_reason_condition;
use App\Models\Att_reason_name;
use App\Models\Att_reason_record;
use App\Models\Att_reason_type;
use App\Models\Att_shift;
use App\Models\Att_workgroup_schedule;
use App\Models\Hrd_employee;
use App\Models\Personel_attendance_correction_request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ESSAttendance extends Controller
{
    function index(Request $request){

        $periode = \App\Models\Att_periode::where("company_id", Session::get("company_id"))
            ->where(function($q){
                $q->where("start_date", "<=", date("Y-m-d"));
                $q->where("end_date", ">=", date("Y-m-d"));
            })
            ->first();

        $days = [];
        $d = date("Y-m-d");
        $ft = $periode->start_date;
        $first = $ft;
        while ($first <= $d) {
            $days[] = $first;
            $first = date("Y-m-d", strtotime("$first +1 day"));
        }
        rsort($days);

        $personel = Hrd_employee::find(Auth::user()->emp_id);

        $reg = Att_employee_registration::where("emp_id", $personel->id)
            ->first();

        // $periode = Att_periode::where(function($q) use($d, $ft){
        //     // $q->where("start_date", "<=", $ft)->where("start_date", ">=", $d);
        //     // $q->orWhere("end_date", "<=", $ft)->where("end_date", ">=", $d);
        //     // $q->whereBetween("start_date", [$ft, $d]);
        //     // $q->orWhereBetween("end_date", [$ft, $d]);
        // })->where('company_id', Session::get("company_id"))->get();

        // foreach($periode as $item){

        // }

        // dd($periode, $ft, $d);

        $sch = Att_workgroup_schedule::where("workgroup", $reg->workgroup ?? null)
            ->where("periode", $periode->id)
            ->get();

        $detail = [];
        foreach($sch as $item){
            $detail = array_merge($item->detail, $detail);
        }

        // dd($detail);

        $detail = collect($detail)->whereBetween("date", [$ft, $d]);
        $schedule = array_values($detail->toArray());
        $schedule = collect($schedule);

        $shift = Att_shift::where("company_id", Session::get("company_id"))->get();

        $shDc = $shift->pluck("day_code", "id");

        $dayCode = Att_day_code::pluck("day_name", "id");

        $attRecord = Att_reason_record::where("emp_id", $personel->id)
            ->whereBetween("att_date", [$ft, $d])
            ->get();

        $attData = [];
        foreach($attRecord as $item){
            $attData[$item->att_date] = $item;
        }

        $attReason = Att_reason_name::pluck("reason_name", "id");
        $attReasonColor = Att_reason_name::pluck("color", "id");

        $reason_types = Att_reason_type::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->where('code', "!=", '00')->get();

        $rcon = Att_reason_condition::whereIn("reason_type_id", $reason_types->pluck("id"))
            ->where('company_id', Session::get("company_id"))
            ->get();

        $kjkOvt = new KjkAttLeave();

        $last_ref_leave = $kjkOvt->getLastRef("request");

        $last_ref = $this->getLastRef();

        $history = [];
        $attCorr = Personel_attendance_correction_request::where("emp_id", $personel->id)
            ->orderBy("id", "desc")
            ->get();

        $leaveCorr = Att_leave_request::where("emp_id", $personel->id)
            ->where("cr", 1)
            ->orderBy("id", "desc")
            ->get();

        foreach($attCorr as $item){
            $col = [];
            $status = "<span class='badge badge-secondary'>Waiting</span>";
            if (!empty($item->approved_at)) {
                $status = "<span class='badge badge-outline badge-success'>Approved</span>";
            }
            if (!empty($item->rejected_at)) {
                $status = "<span class='badge badge-outline badge-danger'>Rejected</span>";
            }
            $url = route("ess.attendance.index")."?a=detail&id=".$item->id;
            $col['id'] = $item->id;
            $col['type'] = "attendance";
            $col['correction_type'] = "Clock In & Clock Out";
            $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
            $col['request_detail'] = "<div class='d-flex flex-column gap-2'>";
            $col['request_detail'] .= "<span>Clock In : ".($item->last_clock_in ??  "N/A")." <i class='fa fa-arrow-right text-dark'></i> $item->clock_in</span>";
            $col['request_detail'] .= "<span>Clock In : ".($item->last_clock_out ??  "N/A")." <i class='fa fa-arrow-right text-dark'></i> $item->clock_out</span>";
            $col['request_detail'] .= "</div>";
            $col['date'] = $item->created_at;
            $col['status'] = $status;
            $col['action'] = '<button type="button" class="btn btn-icon btn-sm" onclick="show_detail('.$item->id.',\''.$url.'\')">
                                <i class="fi fi-rr-menu-dots-vertical"></i>
                            </button>';

            $history[] = $col;
        }

        foreach($leaveCorr as $item){
            $col = [];
            $status = "<span class='badge badge-secondary'>Waiting</span>";
            if (!empty($item->approved_at)) {
                $status = "<span class='badge badge-outline badge-success'>Approved</span>";
            }
            if (!empty($item->rejected_at)) {
                $status = "<span class='badge badge-outline badge-danger'>Rejected</span>";
            }
            $url = route("ess.leave.index")."?a=detail&id=".$item->id;
            $col['id'] = $item->id;
            $col['type'] = "leave";
            $col['correction_type'] = "Leave";
            $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
            $col['request_detail'] = "";
            $col['date'] = $item->created_at;
            $col['status'] = $status;
            $col['action'] = '<button type="button" class="btn btn-icon btn-sm" onclick="show_detail('.$item->id.',\''.$url.'\')">
                                <i class="fi fi-rr-menu-dots-vertical"></i>
                            </button>';
            $history[] = $col;
        }

        $history = collect($history)->sortByDesc("date");

        if($request->a == "detail"){
            $item = Personel_attendance_correction_request::find($request->id);

            $view = view("_ess.attendance.detail", compact("item"))->render();

            return json_encode([
                "view" => $view
            ]);
        }

        return view("_ess.attendance.index", compact("days", "schedule", "shDc", "dayCode", "attData", "attReason", "attReasonColor", "last_ref_leave", "rcon", "reason_types", "last_ref", "history"));
    }

    function getLastRef(){

        $lastLoan = Personel_attendance_correction_request::selectRaw("*, CAST(LEFT(ref_num, 3) as unsigned) as ref")->where("company_id", Session::get("company_id"))
                ->orderBy("ref", "desc")
                ->first();

            $last_ref = ($lastLoan->ref ?? 0) + 1;

            $company_id = sprintf("%03d", Session::get("company_id"));

            return sprintf("%03d", $last_ref)."/$company_id/ATT/".date("m/Y");
    }

    function add(Request $request, $created = null){
        $tp = $request->att_type;

        $personel = \App\Models\Hrd_employee::find($request->emp);

        if($tp == "attendance"){
            $loan = new Personel_attendance_correction_request();
            $loan->emp_id = $request->emp;
            $loan->date = $request->dt;
            $loan->last_clock_in = $request->ccin;
            $loan->last_clock_out = $request->ccout;
            $loan->clock_in = $request->cin;
            $loan->clock_out = $request->cout;
            $loan->company_id = $personel->company_id;
            $loan->created_by = $created ?? Auth::id();
            $loan->ref_num = $this->getLastRef();
            $loan->reason = $request->reason_att;
            $loan->save();

            if(!empty($request->is_mobile)){
                return [
                    'success' => true,
                    'data' => $loan,
                    'message' => "Permintaan Koreksi Kehadiran berhasil dibuat"
                ];
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Pengajuan Koreksi Kehadiran berhasil dibuat",
                    "bg" => "bg-success"
                ]
            ]);
        } else {
            $kjkOvt = new KjkAttLeave();

            return $kjkOvt->request_leave($request, 1, $created);
        }
    }

    function delete($id){
        $r = Personel_attendance_correction_request::find($id);

        if(!empty($r)) $r->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Pengajuan berhasil dibatalkan",
                "bg" => "bg-success"
            ]
        ]);
    }
}
