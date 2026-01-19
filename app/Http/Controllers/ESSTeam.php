<?php

namespace App\Http\Controllers;

use App\Models\Asset_wh;
use App\Models\Att_day_code;
use App\Models\Att_employee_registration;
use App\Models\Att_employee_registration_history;
use App\Models\Att_leave;
use App\Models\Att_leave_employee;
use App\Models\Att_leave_request;
use App\Models\Att_overtime_record;
use App\Models\Att_periode;
use App\Models\Att_reason_record;
use App\Models\Att_schedule_correction;
use App\Models\Att_shift;
use App\Models\Att_workgroup;
use App\Models\Att_workgroup_schedule;
use App\Models\Hrd_employee;
use App\Models\Hrd_employee_type;
use App\Models\Kjk_comp_departement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ESSTeam extends Controller
{
    function index(Request $request){

        $data = $this->getDataTeam($request);

        return view("_ess.team.index", $data);
    }

    function getDataTeam(Request $request){
        $emp_id = $request->emp ?? Auth::user()->emp_id;
        $user_id = $request->user_id ?? Auth::user()->id;
        $user = User::find($user_id);
        $personel = Hrd_employee::find($emp_id);

        // $departement = Kjk_comp_departement::find($user->uac_departement);
        $departement = \App\Models\Kjk_comp_position::find($personel->position_id);
        // dd($departement);

        $team = collect([]);

        if(!empty($departement)){
            $childs = [];
            $appr = new \App\Http\Controllers\ESSApproval();
            $childs = $appr->getPosChild($departement->id);
            // dd($childs);
            // $childs = \App\Models\Kjk_comp_position::where('parent_id', $departement->id ?? null)->get();
            // dd($childs->pluck("name", "id"));
            // dd($childs);
            // $depId = [];
            // $depId[] = $departement->id ?? null;
            // $depId = array_merge($childs->toArray(), $depId);

            // $userDep = User::where("company_id", $personel->company_id)
            //     ->whereIn("uac_departement", $depId)
            //     ->whereNotNull("emp_id")->get();

            $team = Hrd_employee::whereIn("position_id", $childs)
                ->where("id", "!=", $personel->id)
                ->orderBy("emp_name")
                ->get();
            // dd($team, $childs->pluck("id"));
        }

        $workgroups = Att_workgroup::where("company_id", $user->company_id)
            ->get();

        $leavegroups = Att_leave::where("company_id", $user->company_id)
            ->get();

        $registrations = Att_employee_registration::where("company_id", $user->company_id)
            ->whereIn("emp_id", $team->pluck("id"))
            ->get();

        $uImg = User::whereIn("emp_id", $team->pluck("id"))
            ->pluck("user_img", "emp_id");

        $dept = Hrd_employee_type::where("company_id", $user->company_id)
            ->get();

        $loc = Asset_wh::office()->where("company_id", $user->company_id)
            ->get();

        $history_change = Att_employee_registration_history::where('company_id', $user->company_id)
            ->whereIn("reg_id", $registrations->pluck("id"))
            ->orderBy("date", "desc")
            ->get();

        $ff = [
            "id_card" => "ID Card",
            "emp_id" => "Employee ID",
            "workgroup" => "Workgroup",
            "leavegroup" => "Leavegroup",
        ];

        $user_name = User::hris()->where('company_id', $user->company_id)
            ->pluck("name", "id");
        $wg_name = $workgroups->pluck("workgroup_name", "id");
        $lname = $leavegroups->pluck("leave_group_name", "id");

        $data = [
            "loc" => $loc,
            "team" => $team,
            "workgroups" => $workgroups,
            "leavegroups" => $leavegroups,
            "registrations" => $registrations,
            "uImg" => $uImg,
            "dept" => $dept,
            "history_change" => $history_change,
            "user_name" => $user_name,
            "ff" => $ff,
            "wg_name" => $wg_name,
            "lname" => $lname,
        ];

        return $data;
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

        $overtime = Att_overtime_record::where("emp_id", $reg->emp_id)
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

            $schedule = Att_workgroup_schedule::where("workgroup", $reg->wg->id)
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

            $att_record = Att_reason_record::where("emp_id", $reg->emp_id)
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
                    if($att->timin != "0000-00-00 00:00:00" && $att->timout != "0000-00-00 00:00:00"){
                        $col['status'] = "H";
                    } else {
                        $col['status'] = "M";
                    }
                }

                if(in_array($_d, $overtime->pluck("overtime_date")->toArray())){
                    $col['status'] = "C";
                }

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
                'sch' => $schedule
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

        return view("_ess.team.detail", compact("emp", "reg", "leave", "emp_leaves", "uImg", "request_leave", "reg", 'overtime'));
    }
}
