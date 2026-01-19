<?php

namespace App\Http\Controllers;

use App\Models\Att_day_code;
use App\Models\Att_employee_registration;
use App\Models\Att_holiday;
use App\Models\Att_leave;
use App\Models\Att_leave_category;
use App\Models\Att_leave_employee;
use App\Models\Att_leave_extend;
use App\Models\Att_leave_request;
use App\Models\Att_leave_sold;
use App\Models\Att_periode;
use App\Models\Att_reason_condition;
use App\Models\Att_reason_type;
use App\Models\Att_schedule_correction;
use App\Models\Att_shift;
use App\Models\Att_workgroup_schedule;
use App\Models\Hrd_employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class KjkAttLeave extends Controller
{

    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }
    function index(Request $request){

        $leavegroups = Att_leave::where("company_id", Session::get("company_id"))
            ->get();

        $leaveCat = Att_leave_category::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->where('show', 1)->get();

        $leave_balance = Att_employee_registration::where("company_id", Session::get("company_id"))
            ->orderBy("date1")
            ->get();

        $emp_leaves = Att_leave_employee::where('company_id', Session::get("company_id"))
            ->get();

        $epl = [];

        foreach($emp_leaves as $item){
            $periode = date("Y", strtotime($item->start_periode));
            $epl[$item->emp_id][$periode][$item->type] = $item;
        }

        $emp = Hrd_employee::whereIn("id", $leave_balance->pluck("emp_id"))->get();

        $uImg = User::whereIn("emp_id", $leave_balance->pluck("emp_id"))
            ->pluck("user_img", "emp_id");

        $reason_types = Att_reason_type::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->where('code', "!=", '00')->get();

        $rcon = Att_reason_condition::whereIn("reason_type_id", $reason_types->pluck("id"))
            ->where('company_id', Session::get("company_id"))
            ->get();

        $request_leave = Att_leave_request::where('company_id', Session::get("company_id"))
            ->orderBy("created_at", "desc")
            ->get();

        $leave_history = Att_leave_request::where('company_id', Session::get("company_id"))
            ->where(function($q){
                $q->whereNotNull("approved_at");
                $q->orWhereNotNull("rejected_at");
            })
            ->get();

        $leave_sold = Att_leave_sold::where('company_id', Session::get("company_id"))
            ->orderBy("created_at", "desc")
            ->get();

        $leave_extend = Att_leave_extend::where('company_id', Session::get("company_id"))
            ->orderBy("created_at", "desc")
            ->get();

        if($request->a == "table"){
            if($request->e == "sold"){
                $registrations = Att_employee_registration::where('emp_id', $request->id)
                    ->first();

                $emp_leaves = Att_leave_employee::where('emp_id', $request->id)
                    ->where("leavegroup", $registrations->leavegroup)
                    ->whereIn("type", ['annual', 'long'])
                    ->get();

                $view = view("_attendance.leave._table_registration", compact("registrations", 'emp_leaves'))->render();

                return json_encode([
                    "view" => $view
                ]);
            }

            if($request->e == "extend"){
                $registrations = Att_employee_registration::where('emp_id', $request->id)
                    ->first();

                $emp_leaves = Att_leave_employee::where('emp_id', $request->id)
                    ->where("leavegroup", $registrations->leavegroup)
                    ->whereIn("type", ['annual', 'long'])
                    ->get();

                $periode = [];
                foreach($emp_leaves as $item){
                    $pr = date("Y", strtotime($item->start_periode));
                    $_pr = collect($periode);
                    if(empty($_pr->where("text", $pr)->first())){
                        $col = [];
                        $col['id'] = $pr;
                        $col['text'] = date("Y", strtotime($item->start_periode));
                        $periode[] = $col;
                    }
                }

                $view = view("_attendance.leave._table_registration", compact("registrations", 'emp_leaves'))->render();

                return json_encode([
                    "view" => $view,
                    'periode' => $periode
                ]);
            }

            if($request->e == "balance"){
                $emp_leaves = $emp_leaves->where("type", $request->leave_group);
                $epl = [];
                foreach($emp_leaves as $item){
                    $periode = date("Y", strtotime($item->start_periode));
                    $epl[$item->emp_id][$periode] = $item;
                }
                $view = view("_attendance.leave._leave_table", compact("epl", 'leaveCat', "leavegroups", 'rcon', 'leave_balance', 'uImg', 'reason_types', 'request_leave', 'emp', 'leave_history', 'leave_sold', 'leave_extend'));

                return json_encode([
                    "view" => $view->render()
                ]);
            }
        }

        return view("_attendance.leave.index", compact("epl", 'leaveCat', "leavegroups", 'rcon', 'leave_balance', 'uImg', 'reason_types', 'request_leave', 'emp', 'leave_history', 'leave_sold', 'leave_extend'));
    }

    function detail($type, $id, Request $request){
        if($type == "request"){
            $leave_request = Att_leave_request::find($id);

            $users = User::hris()->where("company_id", Session::get("company_id"));

            $uImg = $users->pluck("user_img", "emp_id");

            $userAction = User::find($leave_request->approved_by ?? $leave_request->rejected_at);

            $registrations = Att_employee_registration::where('emp_id', $leave_request->emp_id)
                ->first();

            $emp_leaves = Att_leave_employee::where('emp_id', $leave_request->emp_id)
                ->where("leavegroup", $registrations->leavegroup)
                ->get();

            $rcon = Att_reason_condition::find($leave_request->reason_id);

            $leave['jatah'] = 0;
            $leave['used'] = 0;
            $leave['reserve'] = 0;
            foreach($emp_leaves as $item){
                if(date("Y-m-d") < $item->end_periode){
                    // $total_leaves = $item->leave["$item->type"."_total_leaves"] ?? [];
                    // foreach ($total_leaves as $key => $vv) {
                    //     $leave['jatah'] += ($vv['total_leave'] ?? $vv['total_leaves']) ?? 0;
                    // }
                    $leave['jatah'] += $item->jatah;
                    $leave['used'] += $item->used + $item->unrecorded;
                    $leave['used'] += $item->sold;
                    $leave['used'] -= $item->anulir;
                    $leave['reserve'] += $item->reserved;
                }
            }

            $approval = $request->approval ?? "";

            $view = view("_attendance.leave.detail_request", compact("leave_request", 'uImg', 'leave', 'registrations', 'userAction', 'emp_leaves', 'approval', 'rcon'))->render();
        } elseif($type == "sold"){

            $sold_req = Att_leave_sold::find($id);

            $pr = date("Y", strtotime($sold_req->periode));

            $emp_leaves = Att_leave_employee::where('emp_id', $sold_req->emp_id)
                ->whereYear("start_periode", "like", "$pr%")
                ->where("type", $sold_req->type)->first();

            $users = User::hris()->where("company_id", Session::get("company_id"));

            $uImg = $users->pluck("user_img", "emp_id");

            $userAction = User::find($sold_req->approved_by ?? $sold_req->rejected_at);

            $leave['jatah'] = $emp_leaves->jatah;
            $leave['used'] = $emp_leaves->used;
            $leave['used'] += $emp_leaves->sold;
            $leave['used'] += $emp_leaves->unrecorded;
            $leave['used'] -= $emp_leaves->anulir;
            $leave['reserve'] = $emp_leaves->reserved;

            // $leave['jatah'] = 0;
            // $leave['used'] = ($emp_leaves->used ?? 0) + ($emp_leaves->sold ?? 0);
            // $leave['reserve'] = $emp_leaves->reserved ?? 0;
            // $annual_leave = $emp_leaves->leave->annual_total_leaves ?? [];
            // foreach ($annual_leave as $key => $vv) {
            //     $leave['jatah'] += $vv['total_leave'] ?? 0;
            // }

            $view = view("_attendance.leave.detail_sold", compact("sold_req", 'uImg', 'leave','userAction', 'emp_leaves'))->render();
        } elseif($type == "extend"){
            $extend_req = Att_leave_extend::find($id);

            $emp_leaves = Att_leave_employee::where('emp_id', $extend_req->emp_id)->get();

            $users = User::hris()->where("company_id", Session::get("company_id"));

            $uImg = $users->pluck("user_img", "emp_id");

            $userAction = User::find($extend_req->approved_by ?? $extend_req->rejected_at);

            $leave['jatah'] = 0;
            $leave['used'] = 0;
            $leave['reserve'] = 0;
            foreach($emp_leaves as $item){
                if(date("Y-m-d") < $item->end_periode){
                    $leave['jatah'] += $item->jatah;
                    $leave['used'] += $item->used;
                    $leave['used'] += $item->sold;
                    $leave['used'] += $item->unrecorded;
                    $leave['used'] -= $item->anulir;
                    $leave['reserve'] += $item->reserved;
                }
            }

            $view = view("_attendance.leave.detail_extend", compact("extend_req", 'uImg', 'leave','userAction', 'emp_leaves'))->render();
        } elseif($type == "edit-leave"){
            $personel = Hrd_employee::find($id);

            $registrations = Att_employee_registration::where('emp_id', $personel->id)
                ->first();

            $user = $personel->user ?? [];

            $uImg = $user->user_img ?? null;

            $emp_leaves = Att_leave_employee::where('emp_id', $personel->id)
                ->where("leavegroup", $registrations->leavegroup)
                ->where("end_periode", ">", date("Y-m-d"))
                ->get();

            $leave['jatah'] = 0;
            $leave['used'] = 0;
            $leave['reserve'] = 0;
            foreach($emp_leaves as $item){
                if(date("Y-m-d") < $item->end_periode){
                    // $total_leaves = $item->leave["$item->type"."_total_leaves"] ?? [];
                    // foreach ($total_leaves as $key => $vv) {
                    //     $leave['jatah'] += ($vv['total_leave'] ?? $vv['total_leaves']) ?? 0;
                    // }
                    $leave['jatah'] += $item->jatah;
                    $leave['used'] += $item->used;
                    $leave['used'] += $item->sold;
                    $leave['used'] += $item->unrecorded;
                    $leave['used'] -= $item->anulir;
                    $leave['reserve'] += $item->reserved;
                }
            }

            $view = view("_attendance.leave._edit_leave", compact("emp_leaves", "personel", "uImg", "leave"))->render();
        }

        return json_encode([
            'view' => $view
        ]);
    }

    function formatDate($string){
        if(empty($string)){
            return null;
        }
        $d = explode("/", $string);
        krsort($d);
        $date = implode("-", $d);
        return $date;
    }

    function getLastRef($type){

        if($type == "request"){
            $lastLoan = Att_leave_request::selectRaw("*, CAST(LEFT(ref_num, 3) as unsigned) as ref")->where("company_id", Session::get("company_id"))
                ->orderBy("ref", "desc")
                ->first();

            $last_ref = ($lastLoan->ref ?? 0) + 1;

            $company_id = sprintf("%03d", Session::get("company_id"));

            return sprintf("%03d", $last_ref)."/$company_id/LVR/".date("m/Y");
        } elseif($type == "extend"){
            $lastLoan = Att_leave_extend::selectRaw("*, CAST(LEFT(ref_num, 3) as unsigned) as ref")->where("company_id", Session::get("company_id"))
                ->orderBy("ref", "desc")
                ->first();

            $last_ref = ($lastLoan->ref ?? 0) + 1;

            $company_id = sprintf("%03d", Session::get("company_id"));

            return sprintf("%03d", $last_ref)."/$company_id/LVE/".date("m/Y");
        }
    }

    function request_leave(Request $request, $cr = null, $created = null){
        $validator = Validator::make($request->all(), [
            "emp" => "required",
            "reason" => "required",
            "leave_used" => "required",
            "date" => "required",
            "ref_num" => "required"
        ], [
            "emp.required" => "Employee Name is required",
            "reason.required" => "Reason Type is required",
            "leave_used.required" => "Reason Used is required",
            "date.required" => "Date is required",
            "ref_num.required" => "Reference Number is required"
        ]);

        if($validator->fails()){
            if(!empty($request->mobile)){
                return [
                    'success' => false,
                    'data' => $validator->errors(),
                    'message' => "validation"
                ];
            }

            return redirect()->back()->withErrors($validator)
                ->with([
                    "tab" => "tab_leave_request",
                    "modal" => "modal_create_request_leave"
                ])->withInput($request->all());
        }

        $date = explode(" - ", $request->date);
        $start_date = $this->formatDate($date[0]);
        $end_date = $this->formatDate($date[1]);

        $personel = \App\Models\Hrd_employee::find($request->emp);

        $overlap = Att_leave_request::where("emp_id", $request->emp)
            ->where(function($q) use($start_date, $end_date){
                $q->whereBetween("start_date", [$start_date, $end_date]);
                $q->orWhereBetween("end_date", [$start_date, $end_date]);
            })->whereNull('rejected_at')->get();
        if($overlap->count() > 0){
            $_date = date("d/m/Y", strtotime($start_date));
            $_edate = date("d/m/Y", strtotime($end_date));
            if(!empty($request->mobile)){
                return [
                    'success' => false,
                    'data' => "",
                    'message' => "Permintaan Cuti tidak dapat dilakukan karena ada permintaan cuti aktif antara tanggal $_date - $_edate"
                ];
            }
            return redirect()->back()->withErrors([
                "date" => "Permintaan Cuti tidak dapat dilakukan karena ada permintaan cuti aktif antara tanggal $_date - $_edate"
            ])
            ->with([
                "tab" => "tab_leave_request",
                "modal" => "modal_create_request_leave"
            ])->withInput($request->all());
        }

        $reg = Att_employee_registration::where("emp_id", $request->emp)->first();

        $per = Att_periode::where(function($q) use($start_date){
            $q->where("start_date", "<=", $start_date);
            $q->where("end_date", ">=", $start_date);
        })->where("company_id", $personel->company_id)->first();

        $sch = Att_workgroup_schedule::where("workgroup", $reg->workgroup)
            ->where("periode", $per->id)
            ->first();

        $corr = Att_schedule_correction::where("emp_id", $request->emp)
            ->whereBetween("date", [$start_date, $end_date])
            ->get();
        $emp_cor = [];
        foreach($corr as $item){
            $emp_cor[$item->date] = $item->shift_id;
        }

        $shifts = Att_shift::where(function($q) use($personel){
            $q->where("company_id", $personel->company_id);
            $q->orWhere("is_default", 1);
        })->pluck("day_code", "id");

        $sdc = $shifts->pluck("day_code", "id");
        $shol = $shifts->where("shift_id", "HOL")->first();

        $dcode = Att_day_code::where(function($q) use($personel){
            $q->where("company_id", $personel->company_id);
            $q->orWhere("is_default", 1);
        })->pluck("attend", "id");

        $holidays = Att_holiday::whereBetween('holiday_date', [$start_date, $end_date])
            ->pluck("id", "holiday_date");


        $det = collect($sch->detail ?? []);
        $det_array = [];
        foreach($det as $i => $item){
            if(isset($emp_cor[$item['date']])){
                $shift = $emp_cor[$item['date']];
                $dc = $sdc[$shift] ?? null;
                $item['shift_id'] = $shift;
                $item['day_code'] = $dc;
                if($dcode[$dc]){
                    $item['day_off'] = !$dcode[$dc];
                }
                if(!$reg->wg->replace_holiday_flag){
                    if(isset($holidays[$item['date']])){
                        $item['shift_id'] = $shol->id;
                        $item['day_code'] = $shol->day_code;
                        $item['holiday'] = true;
                        $item['day_off'] = true;
                        // $item['is']
                    }
                }
            }

            $det_array[$item['date']] = $item;
        }

        $days = 0;
        $d1 = $start_date;
        while($d1 <= $end_date){
            $N = date("N", strtotime($d1));
            if(isset($det_array[$d1])){
                $_el = $det_array[$d1];
                if(!$_el['day_off']){
                    $days++;
                }
            }
            $d1 = date("Y-m-d", strtotime($d1." +1 day"));
        }

        $rcon = Att_reason_condition::find($request->leave_used);

        $balance = Att_leave_employee::where("emp_id", $request->emp)
            ->whereDate("end_periode", ">=", $start_date)
            ->where("type", $rcon->leave_type)
            ->orderBy("start_periode")
            ->get();

        $bl = 0;

        if($balance->count() > 0){
            if($balance->count() == 1){
                $bln = $balance->first();
                if(!empty($bln)){
                    if(date("Y-m-d") <= $bln->end_periode){
                        $bl += $bln->jatah;
                        $bl -= ($bln->used - $bln->anulir + $bln->sold + $bln->reserved + $bln->unrecorded);
                        if($bl < $days){
                            $bl += $bln->minus_limit;
                        }
                    }
                }
            } else {
                foreach($balance as $item){
                    if(date("Y-m-d") <= $item->end_periode){
                        $bl += $item->jatah;
                        $bl -= ($item->used - $item->anulir + $item->sold + $item->reserved + $item->unrecorded);
                        $lbl = $bl;
                        if($lbl < $days){
                            $jatah = false;
                            $bl += $item->minus_limit;
                        }
                    }
                }
            }
        }

        if($bl < $days){
            if(!empty($request->mobile)){
                return [
                    'success' => false,
                    'data' => "",
                    'message' => "Jatah cuti tidak cukup"
                ];
            }
            return redirect()->back()->withErrors([
                "date" => "Jatah cuti tidak cukup"
            ])
            ->with([
                "tab" => "tab_leave_request",
                "modal" => "modal_create_request_leave"
            ])->withInput($request->all());
        }

        $r = $balance->first();

        $req = new Att_leave_request();
        $req->emp_id = $request->emp;
        $req->reason_type = $request->reason;
        $req->leave_used = $rcon->leave_type;
        $req->reason_id = $request->leave_used;
        $req->start_date = $start_date;
        $req->end_date = $end_date;
        $req->ref_num = $this->getLastRef("request");
        $req->notes = $request->notes;
        $req->total_day = $days;
        $req->company_id = $personel->company_id;
        $req->created_by = $created ?? Auth::id();
        $req->cr = $cr;
        if($rcon->cut_leave){
            $req->record_id = $r->id;
        }

        $file = $request->file("attachment");
        if(!empty($file)){
            $newName = "LR_".$file->getClientOriginalName();
            if($file->move($this->dir, $newName)){
                $req->file_name = $file->getClientOriginalName();
                $req->file_url = "media/attachments/$newName";
            }
        }

        $req->save();

        if(!empty($request->mobile)){
            return [
                'success' => true,
                'data' => $req,
                'message' => "Permintaan Cuti berhasil dibuat"
            ];
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Successfully Add Request Leave",
                "bg" => "bg-success"
            ],
            "tab" => "tab_leave_request",
        ]);
    }

    function sold_leave(Request $request){
        $validator = Validator::make($request->all(), [
            "emp" => "required",
            "type_leave" => "required",
            "days" => "required",
            "periode" => "required",
        ], [
            "emp.required" => "Employee Name is required",
            "type_leave.required" => "Leave Type is required",
            "days.required" => "Days is required",
            "periode.required" => "Periode is required",
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)
                ->with([
                    "tab" => "tab_sold_leave",
                    "modal" => "modal_create_sold_leave"
                ])->withInput($request->all());
        }

        $sold = new Att_leave_sold();
        $sold->emp_id = $request->emp;
        $sold->type = $request->type_leave;
        $sold->days = $request->days;
        $sold->periode = $request->periode;
        $sold->company_id = Session::get("company_id");
        $sold->created_by = Auth::id();

        $sold->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Successfully Add Sold Leave",
                "bg" => "bg-success"
            ],
            "tab" => "tab_sold_leave",
        ]);
    }

    function extend_leave(Request $request){
        $validator = Validator::make($request->all(), [
            "emp" => "required",
            "type_leave" => "required",
            "months" => "required",
        ], [
            "emp.required" => "Employee Name is required",
            "type_leave.required" => "Leave Type is required",
            "months.required" => "Months is required",
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)
                ->with([
                    "tab" => "tab_extend_leave",
                    "modal" => "modal_create_extend_leave"
                ])->withInput($request->all());
        }

        $extend = new Att_leave_extend();
        $extend->emp_id = $request->emp;
        $extend->type = $request->type_leave;
        $extend->months = $request->months;
        $extend->periode = $request->type_leave == "annual" ? $request->periode : null;
        $extend->company_id = Session::get("company_id");
        $extend->created_by = Auth::id();
        $extend->ref_num = $this->getLastRef("extend");

        $extend->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Successfully Add Extend Leave",
                "bg" => "bg-success"
            ],
            "tab" => "tab_extend_leave",
        ]);
    }

    function approve(Request $request){
        $type = $request->type;
        if($type == "request"){
            $dnow = date("Y-m-d");
            $req = Att_leave_request::find($request->id);

            if($request->submit == "approve"){
                $pr = date("Y", strtotime($req->start_date));
                $rcon = Att_reason_condition::find($req->reason_id);
                $emp_leaves = Att_leave_employee::where('emp_id', $req->emp_id)
                    ->where(function($q) use($req){
                        $q->where("end_periode", ">=", $req->start_date);
                        $q->where("start_periode", "<=", $req->start_date);
                    })
                    ->where("type", $rcon->leave_type)->first();

                $tgl_anulir = json_decode($req->tgl_anulir ?? "[]", true);

                if($rcon->cut_leave){
                    $reserved = $req->total_day * $rcon->leave_days;
                    $emp_leaves->reserved += $reserved;
                    // $bl = $emp_leaves->jatah;
                    // $bl -= ($emp_leaves->used + $emp_leaves->anulir + $emp_leaves->sold + $emp_leaves->reserved);
                    // $emp_leaves->minus_used = abs($bl) * $rcon->leave_days;
                    $emp_leaves->save();
                }

                $req->approved_at = date("Y-m-d H:i:s");
                $req->approved_by = Auth::id();
            } else {
                $req->rejected_at = date("Y-m-d H:i:s");
                $req->rejected_by = Auth::id();
            }

            $req->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Successfully ".ucwords($request->submit)." Leave Request",
                    "bg" => "bg-success"
                ],
                "tab" => "tab_leave_request",
                "type" => $type,
                "drawer" => $request->id
            ]);
        } elseif($type == "sold"){
            $req = Att_leave_sold::find($request->id);

            if($request->submit == "approve"){
                $pr = date("Y", strtotime($req->periode));
                $emp_leaves = Att_leave_employee::where('emp_id', $req->emp_id)
                        ->whereYear("start_periode", "$pr")
                        ->where("type", $req->type)->first();

                $emp_leaves->sold = ($emp_leaves->sold ?? 0) + $req->days;
                $emp_leaves->save();
                $req->approved_at = date("Y-m-d H:i:s");
                $req->approved_by = Auth::id();
            } else {
                $req->rejected_at = date("Y-m-d H:i:s");
                $req->rejected_by = Auth::id();
            }

            $req->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Successfully ".ucwords($request->submit)." Sold Leave",
                    "bg" => "bg-success"
                ],
                "tab" => "tab_sold_leave",
                "type" => $type,
                "drawer" => $request->id
            ]);
        } elseif($type == "extend"){
            $req = Att_leave_extend::find($request->id);

            if($request->submit == "approve"){
                $pr = $req->periode;
                $emp_leaves = Att_leave_employee::where('emp_id', $req->emp_id)
                    ->whereYear("start_periode", "$pr")
                    ->where("type", $req->type)->first();

                $emp_leaves->end_periode = date("Y-m-d", strtotime($emp_leaves->end_periode." +$req->months months"));
                $emp_leaves->save();

                $req->approved_at = date("Y-m-d H:i:s");
                $req->approved_by = Auth::id();
            } else {
                $req->rejected_at = date("Y-m-d H:i:s");
                $req->rejected_by = Auth::id();
            }

            $req->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Successfully ".ucwords($request->submit)." Extend Leave",
                    "bg" => "bg-success"
                ],
                "tab" => "tab_extend_leave",
                "type" => $type,
                "drawer" => $request->id
            ]);
        }
    }

    function getEndPeriode($start_periode, $duration){
        $d = intval(date("d", strtotime($start_periode)));
        $m = intval(date("m", strtotime($start_periode)));
        $y = intval(date("Y", strtotime($start_periode)));
        $_newM = $m + $duration;
        if($_newM > 12){
            $_newM = $_newM - 12;
            $y++;
        }
        $exp = $y."-".sprintf("%02d", $_newM);

        $_nd = intval(date("t", strtotime("$exp")));
        if($d > $_nd){
            $d = $_nd;
        }

        $date = $y."-".sprintf("%02d", $_newM)."-".sprintf("%02d", $d);

        return $date;
    }

    public function cronCuti($id = null, $createdBy = null, $startPeriode = null){
        $registrations = Att_employee_registration::where(function($q) use($id){
                if(!empty($id)){
                    $q->where("emp_id", $id);
                }
            })
            ->get();

        $lgs = Att_leave::whereIn("id", $registrations->pluck("leavegroup"))->get();
        $lgItem = [];
        $last_annual = [];
        foreach($lgs as $item){
            $lgItem[$item->id] = $item;
        }

        $leaveBalance = Att_leave_employee::whereIn("emp_id", $registrations->pluck("emp_id"))->get();
        $lb = [];
        foreach($leaveBalance as $item){
            $lb[$item->emp_id][] = $item;
            if($item->type == "annual"){
                $y = date("Y", strtotime($item->start_periode));
                $last_annual[$item->emp_id][$y] = $item;
            }
        }

        try {
            foreach($registrations as $item){
                $lg = $lgItem[$item->leavegroup] ?? [];
                $emp = $item->emp;
                if(!empty($lg) && !empty($emp)){
                    $annual = $lg->annual_total_leaves ?? [];
                    $long = $lg->long_total_leaves ?? [];
                    $special = $lg->special_total_leaves ?? [];
                    $mass = $lg->mass_leave ?? null;

                    $start_periode = $emp->join_date ?? $emp->created_at;
                    if($lg->show_type == 1){
                        $lpr = explode("/", $lg->start_leave_periode);
                        krsort($lpr);
                        $start_periode = date("Y")."-".implode("-", $lpr);
                    }

                    $start_periode = date("Y")."-".date("m-d", strtotime($start_periode));

                    $dnow = date("Y-m-d");

                    $cr = false;

                    if($dnow >= $start_periode){
                        $cr = true;
                    } else {
                        $start_periode = date("Y-m-d", strtotime($start_periode. " -1 year"));
                        $cr = true;
                    }

                    if($cr){
                        $dyear = 0;
                        $d1 = date_create($emp->join_date ?? $emp->created_at);
                        $d2 = date_create($dnow);
                        $d3 = date_diff($d2, $d1);
                        $dyear = $d3->format("%y");

                        $lbemp = collect($lb[$emp->id] ?? []);

                        $nowAnnual = $lbemp->where("type", "annual")->filter(function ($item) use ($start_periode) {
                            // replace stristr with your choice of matching function
                            $_y = date("Y", strtotime($start_periode));
                            return false !== stristr($item->start_periode, $_y);
                        })->first();
                        $nowLong = $lbemp->where("type", "long")->filter(function ($item) use ($start_periode) {
                            // replace stristr with your choice of matching function
                            $_y = date("Y", strtotime($start_periode));
                            return false !== stristr($item->start_periode, $_y);
                        })->first();
                        $nowSpecial = $lbemp->where("type", "special")->filter(function ($item) use ($start_periode) {
                            // replace stristr with your choice of matching function
                            $_y = date("Y", strtotime($start_periode));
                            return false !== stristr($item->start_periode, $_y);
                        })->first();
                        $nowMass = $lbemp->where("type", "mass")->filter(function ($item) use ($start_periode) {
                            // replace stristr with your choice of matching function
                            $_y = date("Y", strtotime($start_periode));
                            return false !== stristr($item->start_periode, $_y);
                        })->first();

                        if(!empty($annual) && empty($nowAnnual)){

                            $lanual = collect($last_annual[$emp->id] ?? [])->last();
                            $minus_saldo = 0;
                            if(!empty($lanual)){
                                $minus_saldo = $lanual->minus_used;
                            }

                            $end_periode = $this->getEndPeriode($start_periode, $lg->annual_leave_expired);
                            $jatah = 0;
                            foreach($annual as $item){
                                if($dyear >= $item['range_from'] && $dyear <= $item['range_to']){
                                    $jatah = $item['total_leave'];
                                    break;
                                }
                            }
                            $lemp = new Att_leave_employee();
                            $lemp->emp_id = $emp->id;
                            $lemp->type = "annual";
                            $lemp->leavegroup = $lg->id;
                            $lemp->start_periode = date("Y-m-d", strtotime($start_periode));
                            $lemp->end_periode = date("Y-m-d", strtotime($end_periode));
                            $lemp->minus_limit = $lg->annual_over_right;
                            $lemp->minus_balance = $minus_saldo;
                            $lemp->used = $minus_saldo;
                            $lemp->jatah = $jatah;
                            $lemp->company_id = $emp->company_id;
                            $lemp->created_by = $createdBy ?? "cron";
                            $lemp->save();
                        }

                        if(!empty($long) && empty($nowLong)){
                            $end_periode = $this->getEndPeriode($start_periode, $lg->long_expired);
                            $isExist = Att_leave_employee::where("emp_id", $emp->id)
                                ->where("leavegroup", $lg->id)
                                ->where("end_periode", ">=", $start_periode)
                                ->first();
                            $jatah = 0;
                            foreach($long as $item){
                                if($dyear >= $item['lama_kerja']){
                                    $jatah = $item['total_leave'];
                                }
                            }
                            if(empty($isExist)){
                                $lemp = new Att_leave_employee();
                                $lemp->emp_id = $emp->id;
                                $lemp->type = "long";
                                $lemp->leavegroup = $lg->id;
                                $lemp->start_periode = date("Y-m-d", strtotime($start_periode));
                                $lemp->end_periode = date("Y-m-d", strtotime($end_periode));
                                $lemp->jatah = $jatah;
                                $lemp->company_id = $emp->company_id;
                                $lemp->created_by = $createdBy ?? "cron";
                                $lemp->save();
                            }
                        }

                        if(!empty($special) && empty($nowSpecial)){
                            $end_periode = $this->getEndPeriode($start_periode, $lg->annual_leave_expired);
                            $jatah = 0;
                            foreach($special as $item){
                                $jatah += $item['total_leaves'];
                            }
                            $lemp = new Att_leave_employee();
                            $lemp->emp_id = $emp->id;
                            $lemp->type = "special";
                            $lemp->leavegroup = $lg->id;
                            $lemp->start_periode = date("Y-m-d", strtotime($start_periode));
                            $lemp->end_periode = date("Y-m-d", strtotime($end_periode));
                            $lemp->jatah = $jatah;
                            $lemp->company_id = $emp->company_id;
                            $lemp->created_by = $createdBy ?? "cron";
                            $lemp->save();
                        }

                        if(!empty($mass) && empty($nowMass)){
                            $end_periode = $this->getEndPeriode($start_periode, $lg->annual_leave_expired);
                            $jatah = $lg->mass_leave_total;
                            $lemp = new Att_leave_employee();
                            $lemp->emp_id = $emp->id;
                            $lemp->type = "mass";
                            $lemp->leavegroup = $lg->id;
                            $lemp->start_periode = date("Y-m-d", strtotime($start_periode));
                            $lemp->end_periode = date("Y-m-d", strtotime($end_periode));
                            $lemp->jatah = $jatah;
                            $lemp->company_id = $emp->company_id;
                            $lemp->created_by = $createdBy ?? "cron";
                            $lemp->save();
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    function update_leave(Request $request){

        $leave = $request->leave;

        $personel = Hrd_employee::find($request->emp_id);

        $emp_leaves = Att_leave_employee::where("emp_id", $personel->id)
            ->whereIn('id', array_keys($leave))
            ->get();
        $eleave = [];
        foreach($emp_leaves as $item){
            $eleave[$item->id] = $item;
        }

        foreach($leave as $id => $val){
            $_l = $eleave[$id] ?? [];
            if(!empty($_l)){
                $_l->jatah = $val['jatah'];
                $used = $_l->used - ($_l->sold + $_l->anulir);
                $_l->unrecorded = $val['used'] - $used;
                $_l->reserved = $val['reserved'];
                $_l->save();
            }
        }

        return redirect()->back()->with([
            "tab" => "tab_leave_balance",
            "el" => $personel->id,
            "toast" => [
                "message" => "Cuti telah diubah",
                "bg" => "bg-success"
            ]
        ]);
    }
}
