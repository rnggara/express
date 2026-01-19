<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use App\Models\Att_workgroup as Workgroup;
use App\Models\Kjk_comp_departement as Departement;
use App\Models\Att_employee_registration as Registrations;
use App\Models\Att_workgroup_schedule as Schedules;
use App\Models\Hrd_employee;
use App\Models\Att_periode;
use App\Models\Att_shift;
use App\Models\Att_day_code;
use App\Models\Att_employee_registration;
use App\Models\Att_leave;
use App\Models\Att_leave_employee;
use App\Models\Att_overtime_leave_day;
use App\Models\User;
use App\Models\Att_overtime_record as Overtime;
use Illuminate\Support\Facades\Auth;

class KjkAttOvertime extends Controller
{
    private $dir;

    public function __construct() {
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
        $this->$dir = str_replace("\\", "/", $dir);
    }

    function index(Request $request){

        $workgroups = Workgroup::where(function($q) {
            $q->whereNull("company_id");
            $q->orWhere("company_id", Session::get('company_id'));
        })->get();

        $depts = Departement::where(function($q) {
            $q->whereNull("company_id");
            $q->orWhere("company_id", Session::get('company_id'));
        })->get();

        $registrations = Registrations::where("company_id", Session::get('company_id'))
            ->get();

        $emp = Hrd_employee::where("company_id", Session::get("company_id"))
            ->whereIn("id", $registrations->pluck("emp_id"))
            ->orderBy("emp_name")
            ->get();

        $overtime = Overtime::where('company_id', Session::get("company_id"))
            ->orderBy('id', "desc")
            ->get();

        if($request->a == "reason"){
            $reg = $registrations->where("emp_id", $request->id)->first();

            if(!empty($reg)){
                $periode = Att_periode::where(function($q) use($request){
                    $q->where("start_date", "<=", $request->date);
                    $q->where("end_date", ">=", $request->date);
                })->first();


                $schedule = Schedules::where("workgroup", $reg->workgroup)
                    ->where("periode", $periode->id)
                    ->first();

                $data_schedule = collect($schedule->detail ?? [])->where("date", $request->date)->first();
                $shift = Att_shift::find($data_schedule['shift_id'] ?? null);

                $day_code = Att_day_code::select("id", "day_name")->find($shift->day_code ?? null);

                return json_encode([
                    "data" => $day_code,
                    "work_start" => $shift->schedule_in,
                    "work_end" => $shift->schedule_out,
                ]);
            }
        }

        if($request->a == "shift_date"){
            $reg = $registrations->where("emp_id", $request->id)->first();

            if(!empty($reg)){
                $periode = Att_periode::where(function($q) use($request){
                    $q->where("start_date", "<=", $request->date);
                    $q->where("end_date", ">=", $request->date);
                })->first();


                $schedule = Schedules::where("workgroup", $reg->workgroup)
                    ->where("periode", $periode->id)
                    ->first();

                $data_schedule = collect($schedule->detail ?? [])->where("date", $request->date)->first();
                $shift = Att_shift::find($data_schedule['shift_id'] ?? null);

                $time_in = $shift->schedule_in;
                $time_out = $shift->schedule_out;

                return json_encode([
                    "time_in" => $time_in,
                    "time_out" => $time_out,
                ]);
            } else {
                return json_encode([
                    "time_in" => "",
                    "time_out" => "",
                ]);
            }
        }

        $uImg = User::whereIn("emp_id", $emp->pluck("id"))
            ->pluck("user_img", "emp_id");

        return view("_attendance.overtime.index", compact("workgroups", 'depts', 'emp', 'overtime', 'uImg'));
    }

    function detail($id, Request $request){
        $overtime = Overtime::find($id);

        $personel = $overtime->emp;

        $approval = $request->act ?? "";

        $view = view("_attendance.overtime.detail", compact("personel", "overtime", "approval"));

        return json_encode([
            "view" => $view->render()
        ]);
    }

    function covertDate($date){
        $d = explode(" ", $date);
        $date = explode("-", $d[0]);
        krsort($date);
        $_date = implode("-", $date)." ".end($d);

        return $_date;
    }

    function getLastRef(){
        $lastLoan = Overtime::selectRaw("*, CAST(LEFT(reference, 3) as unsigned) as ref")->where("company_id", Session::get("company_id"))
            ->orderBy("ref", "desc")
            ->first();

        $last_ref = ($lastLoan->ref ?? 0) + 1;

        $company_id = sprintf("%03d", Session::get("company_id"));

        return sprintf("%03d", $last_ref)."/$company_id/OVT/".date("m/Y");
    }

    function store(Request $request, $created = null){
        // $conflict =
        $personel = \App\Models\Hrd_employee::find($request->emp);
        if(in_array($request->overtime_type, ["in", "out"])){
            $conflict = Overtime::where("emp_id", $request->emp)
                ->where("overtime_type", $request->overtime_type)
                ->where("overtime_date", $request->overtime_date)
                ->first();
        } else {
            $conflict = Overtime::where("emp_id", $request->emp)
                // ->where("overtime_type", $request->overtime_type)
                ->where("overtime_date", $request->overtime_date)
                ->first();
        }

        if(!empty($conflict)){
            if(!empty($request->mobile)){
                return [
                    'success' => false,
                    'data' => [],
                    'message' => "Tidak bisa melanjutkan proses request overtime. Data sudah ada"
                ];
            }
            return redirect()->back()->withErrors([
                "overtime_date" => "Tidak bisa melanjutkan proses request overtime. Data sudah ada"
            ])->withInput($request->all())->with([
                "modal" => "#modal_add_overtime"
            ]);
        }
        $overtime = new Overtime();
        $overtime->emp_id = $request->emp;
        $overtime->reason_id = $request->reason_id;
        $overtime->overtime_type = $request->overtime_type;
        $overtime->overtime_date = $request->overtime_date;
        $overtime->overtime_start_time = $request->start_date;
        $overtime->overtime_end_time = $request->end_date;
        $overtime->work_start = $request->sin ?? null;
        $overtime->work_end = $request->out ?? null;

        if(!empty($request->break_overtime)){
            $overtime->add_break = 1;
            $overtime->breaks = $request->break_shift;
        }

        $overtime->paid = $request->paid_type;
        if($request->paid_type == "days"){
            $overtime->days = $request->day ?? null;
        }
        $overtime->departement = $request->departement;
        $overtime->reference = $this->getLastRef();
        $overtime->company_id = $personel->company_id;
        $overtime->created_by = $created ?? Auth::id();

        $file = $request->file("file");
        if(!empty($file)){
            $_dir = str_replace("/", "\\", public_path("media/attachments"));
            $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
            $dir = str_replace("\\", "/", $dir);
            $fname = $file->getClientOriginalName();
            $d = date("Ymd");
            $newName = $d."_".$fname;
            if($file->move($dir, $newName)){
                $overtime->file_name = $fname;
                $overtime->file_address = "media/attachments/$newName";
            }
        }

        $overtime->save();

        if(!empty($request->mobile)){
            return [
                'success' => true,
                'data' => $overtime,
                'message' => "Permintaan Overtime berhasil dibuat"
            ];
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Successfully Add Data",
                "bg" => "bg-success"
            ]
        ]);
    }

    function grantChangeLeave($overtime){
        $reg = Att_employee_registration::where("emp_id", $overtime->emp_id)->first();
        $lg = Att_leave::find($reg->leavegroup);

        $mg = $lg->annual_expired_change ?? 0;

        $l = new KjkAttLeave();

        $exp = $l->getEndPeriode(date("Y-m-d"), $mg);

        $cl = new Att_overtime_leave_day();
        $cl->emp_id = $overtime->emp_id;
        $cl->overtime_id = $overtime->id;
        $cl->jatah = $overtime->days;
        $cl->date_expired = $exp;
        $cl->created_by = Auth::id();
        $cl->company_id = $reg->company_id;
        $cl->save();

        $lmp = new Att_leave_employee();
        $lmp->emp_id = $overtime->emp_id;
        $lmp->leavegroup = $reg->leavegroup;
        $lmp->type = "change";
        $lmp->start_periode = date("Y-m-d");
        $lmp->end_periode = $exp;
        $lmp->jatah = $overtime->days;
        $lmp->created_by = Auth::id();
        $lmp->company_id = $reg->company_id;
        $lmp->save();

        $overtime->leave_id = $lmp->id;
        $overtime->save();
    }
}
