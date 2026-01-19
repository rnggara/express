<?php

namespace App\Http\Controllers;

use App\Models\Att_employee_registration;
use App\Models\Att_employee_registration_history;
use App\Models\Att_leave;
use App\Models\Att_leave_employee;
use App\Models\Att_leave_request;
use App\Models\Att_periode;
use App\Models\Att_schedule_correction;
use App\Models\Att_shift;
use App\Models\Att_workgroup;
use App\Models\Att_workgroup_schedule;
use Illuminate\Http\Request;
use App\Models\Hrd_employee;
use App\Models\Hrd_employee_type;
use App\Models\Kjk_employee_location;
use App\Models\Asset_wh;
use App\Models\Att_day_code;
use App\Models\Att_overtime_record;
use App\Models\Att_reason_record;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class KjkAttRegistration extends Controller
{
    function index(Request $request){
        $emp = Hrd_employee::where("company_id", Session::get("company_id"))
            ->orderBy("emp_name")
            ->get();

        $workgroups = Att_workgroup::where("company_id", Session::get("company_id"))
            ->get();

        $leavegroups = Att_leave::where("company_id", Session::get("company_id"))
            ->get();

        $registrations = Att_employee_registration::where("company_id", Session::get('company_id'))
            ->whereIn("emp_id", $emp->pluck("id"))
            ->get();

        $uImg = User::whereIn("emp_id", $emp->pluck("id"))
            ->pluck("user_img", "emp_id");

        $dept = Hrd_employee_type::where("company_id", Session::get("company_id"))
            ->get();

        $loc = Asset_wh::office()->where("company_id", Session::get('company_id'))
            ->get();

        if($request->a == "check"){
            if($request->type == "id_card"){
                $exist = Att_employee_registration::where("id_card", $request->id)->first();
            } elseif($request->type == "employee_id"){
                $exist = Hrd_employee::where("emp_id", $request->id)->first();
            }

            $success = true;
            $message = "";
            if(!empty($exist)){
                $success = false;
                $message = "ID baru yang Anda masukkan sudah dimiliki oleh karyawan lain";
            }

            return json_encode([
                "success" => $success,
                "message" => $message
            ]);
        }

        if($request->a == "m_att"){
            $reg = $registrations->where('id', $request->id)->first();
            $_emp = $emp->where("id", $reg->emp_id)->first();

            $locs = Kjk_employee_location::where("emp_id", $_emp->id)
                ->get()->pluck("wh_id");

            return json_encode([
                "mobile_att" => $_emp->mobile_att,
                "wfa" => $_emp->anywhere,
                "locations" => $locs
            ]);
        }

        $history_change = Att_employee_registration_history::where('company_id', Session::get('company_id'))
            ->whereIn("reg_id", $registrations->pluck("id"))
            ->orderBy("date", "desc")
            ->get();

        $ff = [
            "id_card" => "ID Card",
            "emp_id" => "Employee ID",
            "workgroup" => "Workgroup",
            "leavegroup" => "Leavegroup",
        ];

        $user_name = User::hris()->where('company_id', Session::get('company_id'))
            ->pluck("name", "id");
        $wg_name = $workgroups->pluck("workgroup_name", "id");
        $lname = $leavegroups->pluck("leave_group_name", "id");

        return view("_attendance.registration.index", compact("loc", "emp", 'workgroups', 'ff', 'wg_name', 'lname', 'leavegroups', 'registrations', 'uImg', 'dept', 'history_change', 'user_name'));
    }

    function detail($id, Request $request){
        $emp = Hrd_employee::find($id);
        $reg = Att_employee_registration::where("emp_id", $id)->first();
        $emp_leaves = Att_leave_employee::where("emp_id", $emp->id)
            ->whereIn("type", ['annual', 'long'])
            ->get();
            $uImg = User::where("emp_id", $emp->id)
            ->pluck("user_img", "emp_id");
        $leave['jatah'] = 0;
        $leave['used'] = 0;
        $leave['reserve'] = 0;
        foreach($emp_leaves as $item){
            if(date("Y-m-d") < $item->end_periode){
                // $total_leaves = $item->leave["$item->type"."_total_leaves"] ?? [];
                $jatah = $item->jatah ?? 0;
                $terpakai = $item->used - $item->anulir + $item->unrecorded;
                $reserved = $item->reserved ?? 0;
                $sold = $item->sold ?? 0;
                // $total_leaves = $item->leave["$item->type"."_total_leaves"] ?? [];
                // foreach ($total_leaves as $key => $vv) {
                //     $jatah += ($vv['total_leave'] ?? $vv['total_leaves']) ?? 0;
                // }
                $anulir = $item->anulir;
                $sisa = $jatah - $terpakai - $reserved - $sold;
                $leave['jatah'] += $jatah;
                $leave['used'] += $terpakai;
                $leave['used'] += $sold;
                $leave['reserve'] += $item->reserved;
            }
        }

        $request_leave = Att_leave_request::where('company_id', Session::get("company_id"))
            ->where("emp_id", $emp->id)
            ->orderBy("created_at", "desc")
            ->get();

        $reg = $emp->reg;

        $overtime = Att_overtime_record::where("emp_id", $reg->emp_id ?? null)
            ->get();

        if($request->a == "schedule"){
            $nowD = $request->month;
            if(!empty($request->act)){
                $cond = $request->act == "prev" ? "-1 month" : "+1 month";
                $nowD = date("Y-m", strtotime("$request->month $cond"));
            }
            $prevD = date("Y-m", strtotime($nowD." -1 month"));
            $nextD = date("Y-m", strtotime($nowD." +1 month"));
            $periode = Att_periode::where("company_id", Session::get('company_id'))
                ->where(function($q) use($nowD){
                    $q->where("start_date", "like", "$nowD%");
                    $q->orWhere("end_date", "like", "$nowD%");
                })->get();

            $periode_prev = Att_periode::where("company_id", Session::get('company_id'))
                ->where(function($q) use($prevD){
                    $q->where("start_date", "like", "$prevD%");
                    $q->orWhere("end_date", "like", "$prevD%");
                })->get();
            $periode_next = Att_periode::where("company_id", Session::get('company_id'))
                ->where(function($q) use($nextD){
                    $q->where("start_date", "like", "$nextD%");
                    $q->orWhere("end_date", "like", "$nextD%");
                })->get();

            $schedule = Att_workgroup_schedule::where("workgroup", $reg->wg->id)
                ->whereIn("periode", $periode->pluck("id"))
                ->get();

            $data_schedule = [];
            foreach($schedule as $item){
                foreach($item->detail as $seq => $val){
                    $col = [];
                    $col['date'] = $val['date'];
                    $col['n'] = $val['n'];
                    $col['shift'] = $val['shift_id'];
                    $data_schedule[$val['date']] = $col;
                }
            }

            $t = date("t", strtotime($nowD));

            $sch = [];

            $seq = 0;

            $shifts = Att_shift::where(function($q) {
                $q->where("company_id", Session::get("company_id"));
                $q->orWhere("is_default", 1);
            })->get();
            $shift_id = $shifts->pluck('shift_id', "id");
            $shift_color = $shifts->pluck('shift_color', "id");

            $dataCorrection = Att_schedule_correction::where("emp_id", $emp->id)
                ->where('date', "like", "$nowD%")
                ->get();

            $corrections = [];
            foreach($dataCorrection as $item){
                $col = [];
                $n = date("N", strtotime($item->date));
                $col['date'] = $item->date;
                $col['n'] = $n;
                $col['shift'] = $shift_id[$item['shift_id']];
                $col['color'] = $shift_color[$item['shift_id']];
                $col['shift_id'] = $item['shift_id'];
                $corrections[$item->date] = $col;
            }

            for ($i=1; $i <= $t ; $i++) {
                $_d = $nowD."-".sprintf("%02d", $i);

                $n = date("N", strtotime($_d));

                $col = [];
                $col['date'] = $_d;
                $col['label'] = date("d M", strtotime($_d));
                $col['shift'] = "N/A";
                $col['color'] = "#333";
                if(isset($data_schedule[$_d])){
                    $val = $data_schedule[$_d];
                    $col['shift'] = $shift_id[$val['shift']];
                    $col['color'] = $shift_color[$val['shift']];
                }

                if(isset($corrections[$_d])){
                    $val = $corrections[$_d];
                    $col['shift'] = $val['shift'];
                    $col['color'] = $val['color'];
                }

                $sch[$seq][$n] = $col;
                if($n >= 7){
                    $seq++;
                }
            }

            $view = view("_attendance.registration._schedule_table", compact("sch"))->render();

            return json_encode([
                "view" => $view,
                "next" => $periode_next->count() > 0 ? true : false,
                "prev" => $periode_prev->count() > 0 ? true : false,
                "periode" => $nowD,
                "periode_label" => date("F Y", strtotime($nowD)),
            ]);
        }

        if($request->a == "attendance"){

            $nowD = $request->month;
            if(!empty($request->act)){
                $cond = $request->act == "prev" ? "-1 month" : "+1 month";
                $nowD = date("Y-m", strtotime("$request->month $cond"));
            }
            $prevD = date("Y-m", strtotime($nowD." -1 month"));
            $nextD = date("Y-m", strtotime($nowD." +1 month"));
            $periode = Att_periode::where("company_id", Session::get('company_id'))
                ->where(function($q) use($nowD){
                    $q->where("start_date", "like", "$nowD%");
                    $q->orWhere("end_date", "like", "$nowD%");
                })->get();

            $periode_prev = Att_periode::where("company_id", Session::get('company_id'))
                ->where(function($q) use($prevD){
                    $q->where("start_date", "like", "$prevD%");
                    $q->orWhere("end_date", "like", "$prevD%");
                })->get();
            $periode_next = Att_periode::where("company_id", Session::get('company_id'))
                ->where(function($q) use($nextD){
                    $q->where("start_date", "like", "$nextD%");
                    $q->orWhere("end_date", "like", "$nextD%");
                })->get();

            $schedule = Att_workgroup_schedule::where("workgroup", $reg->wg->id ?? null)
                ->whereIn("periode", $periode->pluck("id"))
                ->get();

            $data_schedule = [];
            $schedules = [];
            foreach($schedule as $item){
                foreach($item->detail as $seq => $val){
                    $col = [];
                    $col['date'] = $val['date'];
                    $col['n'] = $val['n'];
                    $col['shift'] = $val['shift_id'];
                    $data_schedule[$val['date']] = $col;
                }
            }

            $att_record = Att_reason_record::where("emp_id", $reg->emp_id ?? null)
                ->whereYear("att_date", date("Y", strtotime($nowD)))
                ->get();

            $_att = [];

            foreach($att_record as $item){
                $_att[$item->att_date] = $item;
            }

            $t = date("t", strtotime($nowD));

            $sch = [];

            $seq = 0;

            $shifts = Att_shift::where(function($q) {
                $q->where("company_id", Session::get("company_id"));
                $q->orWhere("is_default", 1);
            })->get();
            $shift_id = $shifts->pluck('day_code', "id");
            $shift_color = $shifts->pluck('shift_color', "id");
            $dataCorrection = Att_schedule_correction::where("emp_id", $emp->id)
                ->where('date', "like", "$nowD%")
                ->get();

            $emp_ovr = [];
            foreach($dataCorrection as $item){
                $emp_ovr[$item->date] = $item;
            }

            $holidays = \App\Models\Att_holiday::where("company_id", $emp->company_id)
                ->pluck("holiday_date")->toArray();

            $corrections = [];
            foreach($dataCorrection as $item){
                $col = [];
                $n = date("N", strtotime($item->date));
                $col['date'] = $item->date;
                $col['n'] = $n;
                $col['shift'] = $shift_id[$item['shift_id']];
                $col['color'] = $shift_color[$item['shift_id']];
                $col['shift_id'] = $item['shift_id'];
                $corrections[$item->date] = $col;
            }

            $shift_id = $shifts->pluck('day_code', "id");

            for ($i=1; $i <= $t ; $i++) {
                $_d = $nowD."-".sprintf("%02d", $i);

                $n = date("N", strtotime($_d));

                $col = [];
                $col['date'] = $_d;
                $col['label'] = date("d", strtotime($_d));
                $col['status'] = null;
                $col['color'] = "light-secondary";

                if(isset($_att[$_d])){

                    $att = $_att[$_d];
                    if($att->timin != "0000-00-00 00:00:00" && !empty($att->timin)){
                        $col['status'] = "H";
                    } else {
                        $col['status'] = "M";
                    }
                }

                if(in_array($_d, $request_leave->whereNotNull("approved_at")->pluck("overtime_date")->toArray())){
                    $col['status'] = "C";
                }

                $dcode = null;

                if(isset($data_schedule[$_d])){
                    $dcode = $shift_id[$data_schedule[$_d]['shift']];

                    if($_d < date("Y-m-d")){
                        if($dcode == 1){
                            $col['color'] = "light-secondary";
                        } elseif($dcode == 2){
                            $col['color'] = "secondary";
                        } elseif($dcode == 3){
                            $col['color'] = "secondary";
                        }
                    }
                }

                if($col['status'] == "H"){
                    $col['color'] = "success";
                } elseif($col['status'] == "M"){
                    $col['color'] = "danger";
                } elseif($col['status'] == "C"){
                    $col['color'] = "primary";
                } else {
                    // if($_d < date("Y-m-d") && !in_array($_d, $holidays) && $dcode == 1){
                    //     $col['color'] = "danger";
                    //     $col['status'] = "M";
                    // }
                }

                $sch[$seq][$n] = $col;
                if($n >= 7){
                    $seq++;
                }
            }

            $view = view("_attendance.registration._att_table", compact("sch"))->render();

            $holidays = \App\Models\Att_holiday::where('holiday_date', "like", "$nowD%")
                ->where("company_id", Session::get("company_id"))
                ->pluck("holiday_date")->toArray();

            $att_data = [];
            foreach($att_record as $item){

                if(!in_array($item->att_date, $holidays)){

                    $att_data[$item->att_date] = $item;
                }
            }

            $ovt_tp = [];
            foreach($overtime as $item){
                $ovt_tp[$item->overtime_date][] = $item;
            }

            foreach($schedule as $item){
                foreach($item->detail as $val){
                    if(stripos($val['date'], $nowD) !== false){
                        $col = [];
                        $col['date'] = $val['date'];
                        $col['shift'] = $val['shift_id'];
                        if(isset($emp_ovr[$val['date']])){
                            $col['date'] = $emp_ovr[$val['date']]['date'];
                            $col['shift'] = $emp_ovr[$val['date']]['shift_id'];
                        }
                        $schedules[] = $col;
                    }
                }
            }

            // dd($schedules);

            $shift_code = $shifts->pluck("shift_id", "id");
            $day_code = $shifts->pluck("day_code", "id");

            $day_name = Att_day_code::pluck("day_name", "id");

            $rr = \App\Models\Att_reason_name::get();

            $rname = $rr->pluck("reason_name", "id");
            $rcolor = $rr->pluck("color", "id");

            $att_list = view("_attendance.registration._att_list", compact("schedules", 'shift_code', 'day_code', 'day_name', "att_data", 'rname', 'rcolor', 'ovt_tp'))->render();

            $total['hadir'] = $att_record->where("timin", "!=", "0000-00-00 00:00:00")->where("timout", "!=", "0000-00-00 00:00:00")->whereNull('is_holiday')->count();
            $total['mangkir'] = $att_record->where("timin", "0000-00-00 00:00:00")->where("timout", "0000-00-00 00:00:00")->whereNull('is_holiday')->count();

            $_t = 0;
            $d1 = date_create(date("Y-m-d"));
            $d2 = date_create(date("Y-m-d", strtotime(date("Y")."-01-01")));
            $d3 = date_diff($d1, $d2);
            $_t = $d3->format("%a");

            $total['att_perform'] = ($total['hadir'] / $_t) * 100;
            $total['absence_rate'] = ($total['mangkir'] / $_t) * 100;

            $total['leave'] = Att_leave_request::where("emp_id", $emp->id)
                ->whereYear("start_date", date("Y"))
                ->whereNotNull("approved_at")
                ->count();

            return json_encode([
                "view" => $view,
                "next" => $periode_next->count() > 0 ? true : false,
                "prev" => $periode_prev->count() > 0 ? true : false,
                "periode" => $nowD,
                "periode_label" => date("F Y", strtotime($nowD)),
                "total" => $total,
                'att_list' => $att_list,
                'sch' => $schedule,
                'data' => [
                    'schedules' => $schedules,
                    'shift_code' => $shift_code,
                    'day_code' => $day_code,
                    'day_name' => $day_name,
                    'att_data' => $att_data,
                    'rname' => $rname,
                    'rcolor' => $rcolor,
                    'ovt_tp' => $ovt_tp,
                    'sch' => $sch
                ]
            ]);
        }

        if($request->a == "overtime"){

            $nowD = $request->month;
            if(!empty($request->act)){
                $cond = $request->act == "prev" ? "-1 month" : "+1 month";
                $nowD = date("Y-m", strtotime("$request->month $cond"));
            }
            $prevD = date("Y-m", strtotime($nowD." -1 month"));
            $nextD = date("Y-m", strtotime($nowD." +1 month"));
            $periode = Att_periode::where("company_id", Session::get('company_id'))
                ->where(function($q) use($nowD){
                    $q->where("start_date", "like", "$nowD%");
                    $q->orWhere("end_date", "like", "$nowD%");
                })->get();

            $periode_prev = Att_periode::where("company_id", Session::get('company_id'))
                ->where(function($q) use($prevD){
                    $q->where("start_date", "like", "$prevD%");
                    $q->orWhere("end_date", "like", "$prevD%");
                })->get();
            $periode_next = Att_periode::where("company_id", Session::get('company_id'))
                ->where(function($q) use($nextD){
                    $q->where("start_date", "like", "$nextD%");
                    $q->orWhere("end_date", "like", "$nextD%");
                })->get();

            $schedule = Att_workgroup_schedule::where("workgroup", $reg->wg->id)
                ->whereIn("periode", $periode->pluck("id"))
                ->get();

            $data_schedule = [];
            foreach($schedule as $item){
                foreach($item->detail as $seq => $val){
                    $col = [];
                    $col['date'] = $val['date'];
                    $col['n'] = $val['n'];
                    $col['shift'] = $val['shift_id'];
                    $data_schedule[$val['date']] = $col;
                }
            }

            $t = date("t", strtotime($nowD));

            $sch = [];

            $seq = 0;

            $shifts = Att_shift::where(function($q) {
                $q->where("company_id", Session::get("company_id"));
                $q->orWhere("is_default", 1);
            })->get();
            $shift_id = $shifts->pluck('day_code', "id");
            $shift_color = $shifts->pluck('shift_color', "id");
            $dataCorrection = Att_schedule_correction::where("emp_id", $emp->id)
                ->where('date', "like", "$nowD%")
                ->get();

            $corrections = [];
            foreach($dataCorrection as $item){
                $col = [];
                $n = date("N", strtotime($item->date));
                $col['date'] = $item->date;
                $col['n'] = $n;
                $col['shift'] = $shift_id[$item['shift_id']];
                $col['color'] = $shift_color[$item['shift_id']];
                $col['shift_id'] = $item['shift_id'];
                $corrections[$item->date] = $col;
            }

            $shift_id = $shifts->pluck('day_code', "id");

            for ($i=1; $i <= $t ; $i++) {
                $_d = $nowD."-".sprintf("%02d", $i);

                $n = date("N", strtotime($_d));

                $col = [];
                $col['date'] = $_d;
                $col['label'] = date("d", strtotime($_d));
                $col['day_code'] = null;
                $col['color'] = "secondary";

                if(in_array($_d, $overtime->pluck("overtime_date")->toArray())){
                    if(isset($data_schedule[$_d])){
                        $val = $data_schedule[$_d];
                        $col['day_code'] = $shift_id[$val['shift']];
                    }

                    if(isset($corrections[$_d])){
                        $val = $corrections[$_d];
                        $col['day_code'] = $shift_id[$val['shift']];
                    }

                    if($col['day_code'] == 1){
                        $col['color'] = "success";
                    } elseif($col['day_code'] == 2){
                        $col['color'] = "danger";
                    } elseif($col['day_code'] == 3){
                        $col['color'] = "primary";
                    }
                }

                $sch[$seq][$n] = $col;
                if($n >= 7){
                    $seq++;
                }
            }

            $view = view("_attendance.registration._overtime_table", compact("sch"))->render();

            return json_encode([
                "view" => $view,
                "next" => $periode_next->count() > 0 ? true : false,
                "prev" => $periode_prev->count() > 0 ? true : false,
                "periode" => $nowD,
                "periode_label" => date("F Y", strtotime($nowD)),
            ]);
        }

        return view("_attendance.registration.detail", compact("emp", "reg", "leave", "emp_leaves", "uImg", "request_leave", "reg", 'overtime'));
    }

    function store(Request $request){
        $validator = Validator::make($request->all(), [
            "emp" => "required",
            "id_card" => "required",
            "workgroup" => "required",
            "leavegroup" => "required",
        ], [
            "emp.required" => "Employee Name is required",
            "id_card.required" => "ID Card is required",
            "workgroup.required" => "Workgroup is required",
            "leavegroup.required" => "Leavegroup is required",
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with([
                "modal" => "modal_new_registration"
            ]);
        }

        $emp = Hrd_employee::find($request->emp);

        $emp->mobile_att = $request->mobile_att;

        if($request->mobile_att == 1){
            $emp->anywhere = $request->wfa;
        }

        $emp->save();

        $myLoc = Kjk_employee_location::where("emp_id", $emp->id)->get();

        $emp_loc = $request->locations ?? [];
        foreach($emp_loc as $item){
            if(!in_array($item, $myLoc->pluck("wh_id")->toArray())){
                $eloc = new Kjk_employee_location();
                $eloc->wh_id = $item;
                $eloc->emp_id = $emp->id;
                $eloc->company_id = $emp->company_id;
                $eloc->created_by = Auth::id();
                $eloc->save();
            }
        }

        $registration = Att_employee_registration::findOrNew($request->id);
        if(empty($request->id)){
            $registration->company_id = Session::get("company_id");
            $registration->created_by = Auth::id();
        }
        $registration->emp_id = $request->emp;
        $registration->id_card = $request->id_card;
        $registration->workgroup = $request->workgroup;
        $registration->leavegroup = $request->leavegroup;
        $registration->date1 = $request->date1;
        $registration->date2 = $request->date2;
        $registration->date3 = $request->date3;
        if(!empty($request->id)){
            $registration->status = $request->status ?? 0;
        }

        $registration->save();

        $cutiControll = new KjkAttLeave();

        $cutiControll->cronCuti($registration->emp_id, Auth::id());

        // $lg = Att_leave::find($request->leavegroup);
        // $annual = $lg->annual_total_leaves ?? [];
        // $long = $lg->long_total_leaves ?? [];
        // $special = $lg->special_total_leaves ?? [];

        // $start_periode = $emp->join_date ?? $emp->created_at;
        // if($lg->show_type == 1){
        //     $lpr = explode("/", $lg->start_leave_periode);
        //     krsort($lpr);
        //     $start_periode = date("Y")."-".implode("-", $lpr);
        // }

        // $dyear = 0;
        // $d1 = date_create($emp->join_date ?? $emp->created_at);
        // $d2 = date_create(date("Y-m-d"));
        // $d3 = date_diff($d2, $d1);
        // $dyear = $d3->format("%y");

        // $mass = $lg->mass_leave ?? null;

        // if(!empty($annual)){
        //     $end_periode = date("Y-m-d", strtotime("$start_periode +$lg->annual_leave_expired months"));
        //     $jatah = 0;
        //     foreach($annual as $item){
        //         if($dyear >= $item['range_from'] && $dyear <= $item['range_to']){
        //             $jatah = $item['total_leave'];
        //             break;
        //         }
        //     }
        //     $lemp = new Att_leave_employee();
        //     $lemp->emp_id = $request->emp;
        //     $lemp->type = "annual";
        //     $lemp->leavegroup = $request->leavegroup;
        //     $lemp->start_periode = $start_periode;
        //     $lemp->end_periode = $end_periode;
        //     $lemp->jatah = $jatah;
        //     $lemp->minus_limit = $lg->annual_over_right;
        //     $lemp->company_id = Session::get("company_id");
        //     $lemp->created_by = Auth::id();
        //     $lemp->save();
        // }

        // if(!empty($long)){
        //     $end_periode = date("Y-m-d", strtotime("$start_periode +$lg->long_expired months"));
        //     $isExist = Att_leave_employee::where("emp_id", $request->emp)
        //         ->where("leavegroup", $request->leavegroup)
        //         ->where("end_periode", ">=", $start_periode)
        //         ->first();
        //     if(empty($isExist)){
        //         $jatah = 0;
        //         foreach($long as $item){
        //             if($dyear >= $item['lama_kerja']){
        //                 $jatah = $item['total_leave'];
        //             }
        //         }
        //         $lemp = new Att_leave_employee();
        //         $lemp->emp_id = $request->emp;
        //         $lemp->type = "long";
        //         $lemp->leavegroup = $request->leavegroup;
        //         $lemp->start_periode = $start_periode;
        //         $lemp->end_periode = $end_periode;
        //         $lemp->jatah = $jatah;
        //         $lemp->company_id = Session::get("company_id");
        //         $lemp->created_by = Auth::id();
        //         $lemp->save();
        //     }
        // }

        // if(!empty($special)){
        //     $end_periode = date("Y-m-d", strtotime("$start_periode +$lg->annual_leave_expired months"));
        //     $jatah = 0;
        //     foreach($special as $item){
        //         $jatah += $item['total_leaves'];
        //     }
        //     $lemp = new Att_leave_employee();
        //     $lemp->emp_id = $request->emp;
        //     $lemp->type = "special";
        //     $lemp->leavegroup = $request->leavegroup;
        //     $lemp->start_periode = $start_periode;
        //     $lemp->end_periode = $end_periode;
        //     $lemp->jatah = $jatah;
        //     $lemp->company_id = Session::get("company_id");
        //     $lemp->created_by = Auth::id();
        //     $lemp->save();
        // }

        // if(!empty($mass)){
        //     $end_periode = date("Y-m-d", strtotime("$start_periode +$lg->annual_leave_expired months"));
        //     $jatah = $lg->mass_leave_total;
        //     $lemp = new Att_leave_employee();
        //     $lemp->emp_id = $request->emp;
        //     $lemp->type = "mass";
        //     $lemp->leavegroup = $request->leavegroup;
        //     $lemp->start_periode = $start_periode;
        //     $lemp->end_periode = $end_periode;
        //     $lemp->jatah = $jatah;
        //     $lemp->company_id = Session::get("company_id");
        //     $lemp->created_by = Auth::id();
        //     $lemp->save();
        // }

        return redirect()->back()->with([
            "toast" => [
                "message" => empty($request->id) ? "Successfully Assign Employee" : "Successfully Update Data",
                "bg" => "bg-success"
            ],
            "tab" => "tab_general",
            "drawer" => $request->id ?? null
        ]);
    }

    function update(Request $request){
        $type = $request->type;
        $old = $request->old;
        if($type == "id_card"){
            $validator = Validator::make($request->all(), [
                "emp" => "required",
                "date" => "required",
                "new" => "required",
            ], [
                "emp.required" => "Employee Name is required",
                "date.required" => "Date is required",
                "new.required" => "New ID Card is required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => "modal_edit_id_card"
                ])->withInput($request->all());
            }

            $registration = Att_employee_registration::where("emp_id", $request->emp)->first();
            if(empty($registration)){
                return redirect()->back()->withErrors([
                    "registration" => "No Registration found"
                ])->with([
                    "modal" => "modal_edit_id_card"
                ]);
            }


        } elseif($type == "emp_id"){
            $validator = Validator::make($request->all(), [
                "emp" => "required",
                "date" => "required",
                "new" => "required",
            ], [
                "emp.required" => "Employee Name is required",
                "date.required" => "Date is required",
                "new.required" => "New Employee ID is required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => "modal_edit_employee_id"
                ])->withInput($request->all());
            }

            $registration = Att_employee_registration::where("emp_id", $request->emp)->first();
            if(empty($registration)){
                return redirect()->back()->withErrors([
                    "registration" => "No Registration found"
                ])->with([
                    "modal" => "modal_edit_employee_id"
                ]);
            }
        } elseif($type == "workgroup"){
            $validator = Validator::make($request->all(), [
                "emp" => "required",
                "date" => "required",
                "new" => "required",
            ], [
                "emp.required" => "Employee Name is required",
                "date.required" => "Date is required",
                "new.required" => "New Workgroup is required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => "modal_edit_workgroup_id"
                ])->withInput($request->all());
            }

            $registration = Att_employee_registration::where("emp_id", $request->emp)->first();
            $old = $registration->workgroup;
            if(empty($registration)){
                return redirect()->back()->withErrors([
                    "registration" => "No Registration found"
                ])->with([
                    "modal" => "modal_edit_workgroup_id"
                ]);
            }
        } elseif($type == "leavegroup"){
            $validator = Validator::make($request->all(), [
                "emp" => "required",
                "date" => "required",
                "new" => "required",
            ], [
                "emp.required" => "Employee Name is required",
                "date.required" => "Date is required",
                "new.required" => "New Leavegroup is required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => "modal_edit_leavegroup"
                ])->withInput($request->all());
            }

            $registration = Att_employee_registration::where("emp_id", $request->emp)->first();
            $old = $registration->leavegroup;
            if(empty($registration)){
                return redirect()->back()->withErrors([
                    "registration" => "No Registration found"
                ])->with([
                    "modal" => "modal_edit_leavegroup"
                ]);
            }
        } elseif($type == "mobile_att"){
            $registration = Att_employee_registration::find($request->id);

            $emp = Hrd_employee::find($registration->emp_id);

            $emp->mobile_att = $request->mobile_att;

            if($request->mobile_att == 1){
                $emp->anywhere = $request->wfa;
            }

            $emp->save();

            $myLoc = Kjk_employee_location::where("emp_id", $emp->id)->get();

            $emp_loc = $request->locations ?? [];

            Kjk_employee_location::where("emp_id", $emp->id)
                ->whereNotIn("wh_id", $emp_loc)
                ->delete();

            foreach($emp_loc as $item){
                if(!in_array($item, $myLoc->pluck("wh_id")->toArray())){
                    $eloc = new Kjk_employee_location();
                    $eloc->wh_id = $item;
                    $eloc->emp_id = $emp->id;
                    $eloc->company_id = $emp->company_id;
                    $eloc->created_by = Auth::id();
                    $eloc->save();
                }
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Successfully Update Data",
                    "bg" => "bg-success"
                ],
                "tab" => $request->tab ?? "tab_general",
                "drawer" => $request->id ?? null
            ]);

        }

        $history = new Att_employee_registration_history();
        $history->type = $type;
        $history->reg_id = $registration->id;
        $history->date = $request->date;
        $history->new_data = $request->new;
        $history->old_data = $old;
        $history->company_id = Session::get("company_id");
        $history->created_by = Auth::id();
        $history->save();

        if(date("Y-m-d") >= $request->date){
            $this->cronUpdate($history->id);
            // $registration = Att_employee_registration::where("emp_id", $request->emp)->first();
            // if($type == "workgroup"){
            //     $registration->workgroup = $request->new;
            // }

            // if($type == "leavegroup"){
            //     $old_lg = $registration->leavegroup;
            //     $registration->leavegroup = $request->new;

            //     // $lemp = Att_leave_employee::where("emp_id", $registration->emp_id)
            //     //     ->where("leavegroup", $old_lg)
            //     //     ->orderBy("start_date", "desc")
            //     //     ->get();
            //     // foreach($lemp as $item){
            //     //     $item->leavegroup = $registration->leavegroup;
            //     //     $item->save();
            //     // }

            //     // $cutiControll = new KjkAttLeave();

            //     // $cutiControll->cronCuti($registration->emp_id, Auth::id());
            // }

            // if($type == "id_card"){
            //     $registration->id_card = $request->new;
            // }

            // if($type == "emp_id"){
            //     $emp = $registration->emp;
            //     $emp->emp_id = $request->new_data;
            //     $emp->save();
            // }

            // $registration->save();

            // $history->apply_at = date("Y-m-d H:i:s");
            // $history->apply_by = Auth::id();
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Successfully Update Data",
                "bg" => "bg-success"
            ],
            "tab" => $request->tab ?? "tab_general",
            "drawer" => $request->id ?? null
        ]);
    }

    function cronUpdate($id = null){
        $changes = Att_employee_registration_history::where("company_id", Session::get('company_id'))
            ->where(function($q) use($id){
                if(empty($id)){
                    $q->where("date", "<=", date("Y-m-d"));
                } else {
                    $q->where('id', $id);
                }
            })->whereNull("apply_at")
            ->get();

        $regs = Att_employee_registration::whereIn("id", $changes->pluck("reg_id"))->get();
        $_reg = [];
        foreach($regs as $item){
            $_reg[$item->id] = $item;
        }

        $lem = Att_leave_employee::whereIn("emp_id", $regs->pluck("emp_id"))
            ->orderBy("start_periode", "desc")->get();
        $ll = [];
        foreach($lem as $item){
            $ll[$item->emp_id][] = $item;
        }

        $lea = Att_leave_request::whereIn("emp_id", $regs->pluck("emp_id"))->whereNotNUll("record_id")->get();
        $le = [];
        foreach($lea as $item){
            $le[$item->emp_id][$item->record_id] = $item;
        }

        foreach($changes as $item){
            $reg = $_reg[$item->reg_id] ?? [];
            if(!empty($reg)){
                if($item->type == "emp_id"){
                    $emp = $reg->emp;
                    $emp->emp_id = $item->new_data;
                    $emp->save();
                } elseif($item->type == "leavegroup"){
                    $old_lg = $reg->leavegroup;

                    $lemp = collect($ll[$reg->emp_id] ?? [])->where("leavegroup", $old_lg);
                    $balanceL = [];
                    foreach($lemp as $iel){
                        // $item->leavegroup = $reg->leavegroup;
                        if($iel->end_periode > date("Y-m-d") && $iel->type != "change"){
                            $col = [];
                            $col['used'] = $iel->used;
                            $col['sold'] = $iel->sold;
                            $col['reserved'] = $iel->reserved;
                            $col['anulir'] = $iel->anulir;
                            $col['minus_used'] = $iel->minus_used;
                            $col['id'] = $iel->id;
                            $balanceL[$iel->type] = $col;
                            $iel->end_periode = date("Y-m-d", strtotime($item->date."-1 day"));
                            $iel->save();
                        }
                    }

                    $reg->leavegroup = $item->new_data;
                    $reg->save();

                    $cutiControll = new KjkAttLeave();

                    $cutiControll->cronCuti($reg->emp_id, Auth::id());

                    $lemp = Att_leave_employee::where("emp_id", $reg->emp_id)
                        ->where("leavegroup", $reg->leavegroup)
                        ->orderBy("start_periode", "desc")
                        ->get();

                    foreach($lemp as $iel){
                        $iel->start_periode = date("Y-m-d", strtotime($item->date));
                        $ibal = $balanceL[$iel->type] ?? [];
                        $iel->used = $balanceL[$iel->type]['used'] ?? 0;
                        $iel->sold = $balanceL[$iel->type]['sold'] ?? 0;
                        $iel->reserved = $balanceL[$iel->type]['reserved'] ?? 0;
                        $iel->anulir = $balanceL[$iel->type]['anulir'] ?? 0;
                        $iel->minus_used = $balanceL[$iel->type]['minus_used'] ?? 0;
                        $balanceL[$iel->type] = $ibal;
                        $iel->save();

                        $ibId = $ibal['id'] ?? null;

                        $_le = $le[$reg->emp_id][$ibId] ?? [];
                        if(!empty($_le)){
                            $_le->record_id = $iel->id;
                            $_le->save();
                        }
                    }
                } else {
                    $reg[$item->type] = $item->new_data;
                    $reg->save();
                }

                $item->apply_at = date("Y-m-d H:i:s");
                $item->apply_by = $id ?? "cron";
                $item->save();
            }
        }
    }
}
