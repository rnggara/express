<?php

namespace App\Http\Controllers;

use App\Models\Att_day_code;
use App\Models\Att_employee_registration;
use App\Models\Att_leave;
use App\Models\Att_leave_employee;
use App\Models\Att_leave_request;
use App\Models\Att_overtime_leave_day;
use App\Models\Att_periode;
use App\Models\Att_reason_condition;
use App\Models\Att_reason_type;
use App\Models\Att_shift;
use App\Models\Att_workgroup;
use App\Models\Att_workgroup_schedule;
use App\Models\Att_schedule_correction;
use App\Models\Kjk_comp_departement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

class KjkAttCorrection extends Controller
{
    function index(Request $request){

        $periodes = Att_periode::where('company_id', Session::get('company_id'))
            ->where(function($q) {
                $q->whereYear("start_date", ">=", date("Y") - 1);
                $q->orWhereYear("start_date", date("Y", strtotime("+1 year")));
            })->get();

        $workgroups = Att_workgroup::where("company_id", Session::get('company_id'))
            ->get();

        $shifts = Att_shift::where(function($q) {
            $q->where("company_id", Session::get("company_id"));
            $q->orWhere("is_default", 1);
        })->get();

        $dayCode = Att_day_code::where(function($q) {
            $q->where("company_id", Session::get("company_id"));
            $q->orWhere("is_default", 1);
        })->pluck("day_name", "id");

        $departements = \App\Models\Hrd_employee_type::where("company_id", Session::get("company_id"))->get();

        if($request->a == "table-employee"){
            $registrations = Att_employee_registration::where("company_id", Session::get("company_id"))
                ->get();

            $periode = Att_periode::find($request->periode);

            $shifts = Att_shift::where(function($q) {
                $q->where("company_id", Session::get("company_id"));
                $q->orWhere("is_default", 1);
            })->get();
            $shift_id = $shifts->pluck('shift_id', "id");
            $shift_color = $shifts->pluck('shift_color', "id");

            $sch = [];

            $seq = 0;

            $_n = 1;

            $corrections = [];

            $data_schedule = [];

            if(!empty($periode)){
                $nowD = date("Y-m", strtotime($periode->end_date));

                $t = date("t", strtotime($nowD));

                for ($i=1; $i <= $t ; $i++) {
                    $_d = $nowD."-".sprintf("%02d", $i);

                    $n = date("N", strtotime($_d));
                    $_n++;

                    $col = [];
                    $col['n'] = $n;
                    $col['date'] = $_d;
                    $col['label'] = date("D d/m", strtotime($_d));
                    $sch[$seq][$_d] = $col;
                    if($_n > 7){
                        $_n = 1;
                        $seq++;
                    }
                }

                $prSel = Att_periode::where("company_id", Session::get('company_id'))
                    ->where(function($q) use($nowD){
                        $q->where("start_date", "like", "$nowD%");
                        $q->orWhere("end_date", "like", "$nowD%");
                    })->get();

                $schedule = Att_workgroup_schedule::where("company_id", Session::get("company_id"))
                    ->whereIn("periode", $prSel->pluck("id"))
                    ->get();

                $dataCorrection = Att_schedule_correction::whereIn("emp_id", $registrations->pluck("emp_id"))
                    ->where('date', "like", "$nowD%")
                    ->get();

                foreach($dataCorrection as $item){
                    $col = [];
                    $n = date("N", strtotime($item->date));
                    $col['date'] = $item->date;
                    $col['n'] = $n;
                    $col['shift'] = $shift_id[$item['shift_id']] ?? null;
                    $col['color'] = $shift_color[$item['shift_id']] ?? null;
                    $col['shift_id'] = $item['shift_id'];
                    $corrections[$item->emp_id][$item->date] = $col;
                }

                foreach($schedule as $item){
                    foreach($item->detail as $val){
                        if(isset($shift_id[$val['shift_id']])){
                            $col = [];
                            $col['date'] = $val['date'];
                            $col['n'] = $val['n'];
                            $col['shift'] = $shift_id[$val['shift_id']];
                            $col['color'] = $shift_color[$val['shift_id']];
                            $col['shift_id'] = $val['shift_id'];
                            $data_schedule[$item->workgroup][$val['date']] = $col;
                        }
                    }
                }
            }
            $uImg = User::whereIn("emp_id", $registrations->pluck("emp_id"))
                    ->pluck("user_img", "emp_id");

            $view = view("_attendance.correction._table", compact("registrations", "sch", "data_schedule", "uImg", 'shifts', 'dayCode', 'corrections', 'nowD'))->render();

            return json_encode([
                "view" => $view,
                "total_sequence" => $seq,
                "data" => $data_schedule
            ]);
        }

        if($request->a == "table-workgroup"){
            $periode = Att_periode::find($request->periode);

            $nowD = date("Y-m", strtotime($periode->start_date ?? date("Y-m-d")));

            $t = date("t", strtotime($nowD));

            $sch = [];

            $seq = 0;

            $_n = 1;

            for ($i=1; $i <= $t ; $i++) {
                $_d = $nowD."-".sprintf("%02d", $i);

                $n = date("N", strtotime($_d));
                $_n++;

                $col = [];
                $col['n'] = $n;
                $col['label'] = date("D d/m", strtotime($_d));
                $sch[$seq][$_d] = $col;
                if($_n > 7){
                    $_n = 1;
                    $seq++;
                }
            }

            $shifts = Att_shift::where(function($q) {
                $q->where("company_id", Session::get("company_id"));
                $q->orWhere("is_default", 1);
            })->get();
            $shift_id = $shifts->pluck('shift_id', "id");
            $shift_color = $shifts->pluck('shift_color', "id");

            $prSel = Att_periode::where("company_id", Session::get('company_id'))
                ->where(function($q) use($nowD){
                    $q->where("start_date", "like", "$nowD%");
                    $q->orWhere("end_date", "like", "$nowD%");
                })->get();

            $schedule = Att_workgroup_schedule::where("company_id", Session::get("company_id"))
                ->whereIn("periode", $prSel->pluck("id"))
                ->get();

            $data_schedule = [];
            foreach($schedule as $item){
                foreach($item->detail as $val){
                    if(isset($shift_id[$val['shift_id']])){
                        $col = [];
                        $col['date'] = $val['date'];
                        $col['n'] = $val['n'];
                        $col['shift'] = $shift_id[$val['shift_id']];
                        $col['color'] = $shift_color[$val['shift_id']];
                        $col['shift_id'] = $val['shift_id'];
                        $data_schedule[$item->workgroup][$val['date']] = $col;
                    }
                }
            }

            $view = view("_attendance.correction._table_workgroup", compact("workgroups", "sch", "data_schedule", 'shifts', 'dayCode'))->render();

            return json_encode([
                "view" => $view,
                "total_sequence" => $seq
            ]);
        }

        if($request->a == "table-attendance"){
            $periode = Att_periode::find($request->periode);
            $reasons = \App\Models\Att_reason_name::where(function($q){
                $q->where("is_default", 1);
                $q->orWhere("company_id", Session::get("company_id"));
            })->where("status", 1)->get();

            $dept = $request->dept;

            $emp = \App\Models\Hrd_employee::where("company_id", Session::get("company_id"))
                ->where(function($q) use($dept){
                    if(!empty($dept)){
                        $q->where("emp_type", $dept);
                    }
                })
                ->get();

            $registrations = Att_employee_registration::where("company_id", Session::get("company_id"))
                ->whereIn("emp_id", $emp->pluck("id"))
                ->get();

            $att_data = [];

            if(!empty($periode)){
                $holidays = \App\Models\Att_holiday::whereBetween('holiday_date', [$periode->start_date, $periode->end_date])
                    ->where("company_id", Session::get("company_id"))
                    ->pluck("holiday_date")->toArray();

                $att_reason = \App\Models\Att_reason_record::where("company_id", Session::get("company_id"))
                    ->whereBetween("att_date", [$periode->start_date, $periode->end_date])
                    ->get();

                foreach($att_reason as $item){
                    if(!in_array($item->att_date, $holidays)){
                        foreach($item->reasons ?? [] as $rp){
                            $att_data[$item->emp_id][$rp['id']][] = $item;
                        }
                    }
                }
            }

            $view = view("_attendance.correction._table_attendance", compact("reasons", 'registrations', 'att_data', 'periode'))->render();

            return json_encode([
                "view" => $view,
                "att_data" => $att_data
            ]);
        }


        return view("_attendance.correction.index", compact('periodes', 'workgroups', 'shifts', 'dayCode', 'departements'));
    }

    function att_detail_edit($id, Request $request){
        $reg = Att_employee_registration::find($id);

        $holidays = \App\Models\Att_holiday::where('holiday_date', $request->date)
            ->where("company_id", Session::get("company_id"))
            ->first();

        $date = $request->date;

        $leave = \App\Models\Att_leave_request::where("emp_id", $reg->emp_id)
            ->where(function($q) use($date){
                $q->where("start_date", "<=", $date);
                $q->where("end_date", ">=", $date);
            })->first();

        $att_record = \App\Models\Att_reason_record::where("emp_id", $reg->emp_id)
            ->where("att_date", $request->date)
            ->where(function($q) use($holidays, $leave){
                if(!empty($holidays)){
                    $q->where("att_date", "!=", $holidays->holiday_date);
                }

                // if(!empty($leave)){
                //     $q->whereNotBetween("att_date", [$leave->start_date, $leave->end_date]);
                // }
            })
            ->first();
        $personel = $reg->emp;

        $per = Att_periode::where("start_date", "<=", $date)
            ->where("end_date", ">=", $date)
            ->first();

        $sch = Att_workgroup_schedule::where("workgroup", $reg->workgroup)
            ->where("periode", $per->id ?? null)
            ->first();

        $sch_detail = collect($sch->detail ?? []);
        $tday = $sch_detail->where("date", $date)->first() ?? [];

        $_shToday = $tday['shift_id'];

        $correction = Att_schedule_correction::where("emp_id", $reg->emp_id)
            ->where("date", $date)->first();

        if(!empty($correction)){
            $_shToday = $correction->shift_id;
        }

        $_shift = Att_shift::find($_shToday);


        $day_code = Att_day_code::where(function($q) {
            $q->where("is_default", 1);
            $q->orWhere("company_id", Session::get("company_id"));
        })->get();

        $overtime = \App\Models\Att_overtime_record::where("emp_id", $reg->emp_id)
            ->where("overtime_date", $date)
            ->whereNotNull("approved_at")
            ->first();

        $depts = \App\Models\Kjk_comp_departement::where("company_id", Session::get("company_id"))->get();

        $reason_types = Att_reason_type::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->where("code", "!=", '00')->get();

        $rcon = Att_reason_condition::where("reason_type_id", $reason_types->pluck("id"))
            ->get();

        $rr = \App\Models\Att_reason_name::get();

        $rname = $rr->pluck("reason_name", "id");
        $rcolor = $rr->pluck("color", "id");


        $view = view("_attendance.correction._edit", compact("reg", "reason_types", "att_record", 'personel', 'date', 'day_code', 'overtime', 'depts', 'holidays', 'leave', '_shift', 'rcon', 'rname', 'rcolor'));

        return json_encode([
            "view" => $view->render()
        ]);
    }

    function attendance_correction(Request $request){

        $emp_id = $request->emp_id;

        $att_record =  \App\Models\Att_reason_record::where("emp_id", $emp_id)
            ->where("att_date", $request->date)
            ->first();

        $att_record->timin = $request->actual_timin;

        $att_record->timout = $request->actual_timout;

        $att_record->break_start = $request->actual_break_start;

        $att_record->break_end = $request->actual_break_end;

        $rtype = Att_reason_type::where("code", '00')
            ->where(function($q) {
                $q->where('company_id', Session::get("company_id"));
                $q->orWhere('is_default', 1);
            })
            ->get();

        $condition = \App\Models\Att_reason_condition::where('company_id', Session::get("company_id"))
            ->whereIn("reason_type_id", $rtype->pluck("id"))
            ->orderBy("process_sequence")
            ->get();

        $reason_active = \App\Models\Att_reason_name::where(function($q) {
            $q->where('company_id', Session::get("company_id"));
            $q->orWhere('is_default', 1);
        })
            ->where('status', 1)->pluck("id")->toArray();

        $reason_condition = [];

        $reason_pengganti = [];

        $con_reason = $condition->pluck("reason_name_id", "id");
        $con_seq = $condition->pluck("process_sequence", "id");

        $holidays = \App\Models\Att_holiday::where("company_id", Session::get('company_id'))
            ->where("holiday_date", $request->date)
            ->pluck("id", "holiday_date");

        foreach($condition as $item){
            $string = "";
            $cond = array_keys($item->conditions);
            if(!empty($item->rp_detail)){
                $reason_pengganti[$item->id] = $item->rp_detail;
            }

            if(in_array("schedule", $cond)){
                $string .= '$day_code == '.$item->schedule_id;
            }

            if(in_array("shift_code", $cond)){
                $shifts = implode(",", $item->shift_code ?? []);
                $string .= $string != "" ? " && " : "";
                $string .= 'in_array($shift_code, ['.$shifts.'])';
            }

            if(in_array("time_in", $cond)){
                $string .= $string != "" ? " && " : "";
                $minute = null;
                if(!empty($item->time_in)){
                    $h = intval(date("H", strtotime(date("Y-m-d")." ".$item->time_in))) * 60;
                    $m = intval(date("i", strtotime(date("Y-m-d")." ".$item->time_in)));
                    $minute = $h + $m;

                    $minute = 'date("Y-m-d H:i:s", strtotime($schedule_in." -'.$minute.' minutes"))';
                    $string .= '($time_in <> "" && $time_in '.$item->time_in_condition.' '.($minute ?? "''").")";
                } else {
                    $string .= '$time_in '.$item->time_in_condition.' '.($minute ?? "''");
                }
            }

            if(in_array("time_out", $cond)){
                $string .= $string != "" ? " && " : "";
                $minute = null;
                if(!empty($item->time_out)){
                    $minute = '$schedule_out';
                }
                $string .= '$time_out '.$item->time_out_condition.' '.($minute ?? "''");
            }

            if(in_array("late_in", $cond)){
                $string .= $string != "" ? " && " : "";
                $minute = null;
                if(!empty($item->late_in)){
                    $h = intval(date("H", strtotime(date("Y-m-d")." ".$item->late_in))) * 60;
                    $m = intval(date("i", strtotime(date("Y-m-d")." ".$item->late_in)));
                    $minute = $h + $m;
                }
                $string .= '$late_in '.$item->late_in_condition.' '.($minute ?? "''");
            }

            if(in_array("fast_out", $cond)){
                $string .= $string != "" ? " && " : "";
                $minute = null;
                if(!empty($item->fast_out)){
                    $h = intval(date("H", strtotime(date("Y-m-d")." ".$item->fast_out))) * 60;
                    $m = intval(date("i", strtotime(date("Y-m-d")." ".$item->fast_out)));
                    $minute = $h + $m;
                }
                $string .= '$fast_out '.$item->fast_out_condition.' '.($minute ?? "''");
            }
            $reason_id = 6;
            if(in_array($item->reason_name_id, $reason_active)){
                $reason_id = $item->id;
            }

            $reason_condition[] = "if($string){ return $reason_id; } else {return false;}";
        }

        $time_in = $att_record->timin == "0000-00-00 00:00:00" ? "" : $att_record->timin;
        $time_out = $att_record->timout == "0000-00-00 00:00:00" ? "" : $att_record->timout;

        $day_code = $att_record->day_code;
        $shift_code = $att_record->shift_id;

        $_shift = \App\Models\Att_shift::find($att_record->shift_id);

        $schedule_in = $_shift->schedule_in;
        $schedule_out = $_shift->schedule_out;

        $reason_id = "";
        $col = [];

        if ($time_in != "" && $time_out != "") {
            $reason_id = 1;
        }

        $date = $request->date;

        $collectController = new \App\Http\Controllers\KjkAttCollectData();

        $late_in = $collectController->getMinuteDiff(date("Y-m-d H:i:s", strtotime($time_in)), date("Y-m-d H:i:s", strtotime($date." ".$_shift->schedule_in)));
        $fast_out = $collectController->getMinuteDiff(date("Y-m-d H:i:s", strtotime($date." ".$_shift->schedule_out)), date("Y-m-d H:i:s", strtotime($time_out)));

        $rid = [];

        foreach($reason_condition as $reason){
            $eval = eval($reason);
            if($eval){
                $reason_id = $con_reason[$eval] ?? 1;
                if(!in_array($eval, $rid)){
                    $rid[] = $eval;
                }
            }
        }

        $_r = [];
        foreach($rid as $k => $cpr){
            if(isset($reason_pengganti[$cpr])){
                $rp = $reason_pengganti[$cpr];
                $ovr = false;
                $_seq = 0;
                foreach($rp as $p){
                    if(in_array($p, $rid)){
                        if($con_seq[$p] >= $_seq){
                            $_seq = $con_seq[$p];
                            $col = [];
                            $col['seq'] = $con_seq[$p];
                            $col['id'] = intval($p);
                            $_r[] = intval($p);
                            $ovr = !$ovr ? true : true;
                        }
                    }
                }

                if(!$ovr){
                    $col = [];
                    $col['seq'] = $con_seq[$cpr];
                    $col['id'] = $cpr;
                    $_r[] = $cpr;
                }
            } else {
                $col = [];
                $col['seq'] = $con_seq[$cpr];
                $col['id'] = $cpr;
                $_r[] = $cpr;
            }
        }

        foreach(array_unique($_r) as $rp){
            $col = [];
            $col['seq'] = $con_seq[$rp];
            $col['id'] = $con_reason[$rp];
            $creasons[] = $col;
        }

        $_creasons = collect($creasons)->sortBy("seq")->toArray();

        // dd($reason_id, $time_in, $time_out, $reason_condition, $day_code, $shift_code, ($day_code == 1 && $time_in == '' && $time_out == '') ? 4 : false);

        $att_record['reason_values'] = [
            "late_in" => $late_in,
            "fast_out" => $fast_out,
        ];

        $att_record['reasons'] = $_creasons;
        $att_record['reason_id'] = $reason_id;
        $att_record['updated_by'] = Auth::id();
        $att_record['updated_at'] = date("Y-m-d H:i:s");

        if(isset($holidays[$request->date])){
            $att_record['is_holiday'] = 1;
            $att_record['holiday_id'] = $holidays[$request->date];
        }

        $att_record->save();

        $leave = $request->leave ?? [];
        $validatorLeave = Validator::make($leave, [
            "reason" => "required",
            "leave_used" => "required",
        ]);

        if(!$validatorLeave->fails()){

            $rcon = Att_reason_condition::find($leave['leave_used']);

            $lemp = Att_leave_employee::where("emp_id", $request->emp_id)
                ->where("type", $rcon->leave_type)
                ->where(function($q) use($leave){
                    $q->where("start_periode", "<=", $leave['date']);
                    $q->where("end_periode", ">=", $leave['date']);
                })->orderBy("start_date")->get();
            $bl = 0;
            $days = 1;
            if($lemp->count() > 0){
                if($lemp->count() == 1){
                    $bln = $lemp->first();
                    if(!empty($bln)){
                        if(date("Y-m-d") <= $bln->end_periode){
                            $bl += $bln->jatah;
                            $bl -= ($bln->used + $bln->anulir + $bln->sold + $bln->reserved);
                            if($bl < $days){
                                $bl += $bln->minus_limit;
                            }
                        }
                    }
                } else {
                    foreach($lemp as $item){
                        if(date("Y-m-d") <= $item->end_periode){
                            $bl += $item->jatah;
                            $bl -= ($item->used + $item->anulir + $item->sold + $item->reserved);
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
                return redirect()->back()->with([
                    "toast" => [
                        "message" => "Jatah cuti tidak cukup",
                        "bg" => "bg-danger"
                    ]
                ]);
            }

            $r = $lemp->first();

            $prevLeave = Att_leave_request::where("emp_id", $request->emp_id)
                ->where(function($q) use($leave){
                    $q->where("start_date", "<=", $leave['date']);
                    $q->where("end_date", ">=", $leave['date']);
                })->first();

            if(!empty($prevLeave)){
                $anulir = json_decode($prevLeave->tanggal_anulir ?? "[]", true);
                if(!in_array($request->date, $anulir)){
                    $anulir[] = $request->date;
                }
                $prevLeave->tanggal_anulir = json_encode($anulir);
                $prevLeave->save();
                if(!empty($lemp)){
                    $lemp->anulir += 1;
                    $lemp->save();
                }
            }

            $req = new Att_leave_request();
            $req->emp_id = $request->emp_id;
            $req->reason_type = $leave['reason'];
            $req->leave_used = $rcon->leave_type;
            $req->reason_id = $leave['leave_used'];
            $req->leave_used = $leave['leave_used'];
            $req->start_date = $leave['date'];
            $req->end_date = $leave['date'];
            $req->ref_num = $leave['ref_num'];
            $req->notes = $leave['notes'];
            $req->total_day = 1;
            $req->company_id = Session::get("company_id");
            $req->created_by = Auth::id();
            $req->record_id = $r->id;
            $req->used_at = date("Y-m-d H:i:s");
            $req->used_by = Auth::id();

            $file = $leave['attachment'] ?? null;
            if(!empty($file)){
                $newName = "LR_".$file->getClientOriginalName();
                $_dir = str_replace("/", "\\", public_path("media/attachments"));
                $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
                $dir = str_replace("\\", "/", $dir);
                if($file->move($dir, $newName)){
                    $req->file_name = $file->getClientOriginalName();
                    $req->file_url = "media/attachments/$newName";
                }
            }
            $req->approved_at = date("Y-m-d H:i:s");
            $req->approved_by = Auth::id();

            $req->save();


            if(!empty($r)){
                if($rcon->cut_leave){
                    if($r->used >= $r->jatah){
                        $r->minus_used += ($rcon->leave_days * 1);
                    } else {
                        $r->used += ($rcon->leave_days * 1);
                    }
                    $r->save();
                }
            }

            $att_record['timin'] = "";
            $att_record['timout'] = "";
            $att_record['day_code'] = 2;
            $att_record['reason_id'] = $leave['leave_used'];
            $_reason['seq'] = 1;
            $_reason['id'] = $leave['leave_used'];
            $att_record['reasons'] = [$_reason];
            $att_record->save();
        }

        $validator = Validator::make($request->all(), [
            "overtime_type" => "required",
            "start_date" => "required",
            "end_date" => "required",
        ]);


        if(!$validator->fails()){
            if(in_array($request->overtime_type, ["in", "out"])){
                $conflict = \App\Models\Att_overtime_record::where("emp_id", $emp_id)
                    ->where("overtime_type", $request->overtime_type)
                    ->where("overtime_date", $request->overtime_date)
                    ->first();
            } else {
                $conflict = \App\Models\Att_overtime_record::where("emp_id", $emp_id)
                    // ->where("overtime_type", $request->overtime_type)
                    ->where("overtime_date", $request->overtime_date)
                    ->first();
            }
            if(empty($conflict)){
                $overtime = \App\Models\Att_overtime_record::where("emp_id", $emp_id)
                    ->where("overtime_date", $date)->first();
                if(empty($overtime)){
                    $overtime = new \App\Models\Att_overtime_record();
                    $overtime->emp_id = $emp_id;
                    $overtime->overtime_date = $date;
                    $overtime->company_id = Session::get('company_id');
                }
                $overtime->reason_id = $att_record->day_code;
                $overtime->overtime_type = $request->overtime_type;
                $overtime->overtime_start_time = $request->start_date;
                $overtime->overtime_end_time = $request->end_date;

                if(!empty($request->break_overtime)){
                    $overtime->add_break = 1;
                    $overtime->breaks = $request->break_shift;
                }

                $overtime->paid = $request->paid_type;
                if($request->paid_type == "days"){
                    $overtime->days = $request->day ?? null;
                }
                $overtime->departement = $request->departement;
                $overtime->reference = $request->reference;
                $overtime->approved_at = date("Y-m-d H:i:s");
                $overtime->approved_by = Auth::id();

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

                $clData = new \App\Http\Controllers\KjkAttCollectData();

                $ovt_start = date("Y-m-d H:i:s", strtotime($date." ".$overtime->overtime_start_time));
                $ovt_end = date("Y-m-d H:i:s", strtotime($date." ".$overtime->overtime_end_time));
                $sout = date("Y-m-d H:i:s", strtotime($date." ".$schedule_out));
                $sin = date("Y-m-d H:i:s", strtotime($date." ".$schedule_in));
                $ovth = $clData->getMinuteDiff($ovt_end, $ovt_start);

                if($overtime->overtime_type == "out"){
                    if($ovt_start < $sout){
                        $ovt_start = $sout;
                    }
                } else {
                    if($ovt_end > $sin){
                        $ovt_end = $sin;
                    }
                }

                if($overtime->overtime_type == "in"){
                    $att_record['ovtstartin'] = $ovt_start;
                    $att_record['ovtendin'] = $ovt_end;
                    $att_record['ovthoursin'] = $ovth;
                } else {
                    $att_record['ovtstart'] = $ovt_start;
                    $att_record['ovtend'] = $ovt_end;
                    $att_record['ovthours'] = $ovth;
                }

                $att_record->save();

                if($overtime->paid == "days"){
                    $cl = new KjkAttOvertime();
                    $cl->grantChangeLeave($overtime);
                }
            }
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Successfully Correction Data",
                "bg" => "bg-success"
            ],
            "drawer" => $date
        ]);
    }

    function att_detail($id, Request $request){
        $reg = Att_employee_registration::find($id);
        $periode = Att_periode::find(base64_decode($request->p));

        $att_reason = \App\Models\Att_reason_record::where("company_id", Session::get("company_id"))
            ->where("emp_id", $reg->emp_id)
            ->whereBetween("att_date", [$periode->start_date, $periode->end_date])
            ->get();

        $holidays = \App\Models\Att_holiday::whereBetween('holiday_date', [$periode->start_date, $periode->end_date])
            ->where("company_id", Session::get("company_id"))
            ->pluck("holiday_date")->toArray();

        $total['hadir'] = 0;
        $total['terlambat'] = 0;
        $total['overtime'] = 0;
        $total['mangkir'] = 0;
        $total['cuti'] = 0;
        $total['sakit'] = 0;
        $att_data = [];
        foreach($att_reason as $item){

            if(!in_array($item->att_date, $holidays)){
                if((!empty($item->timin) && $item->timin != "0000-00-00 00:00:00") && (!empty($item->timout) && $item->timout != "0000-00-00 00:00:00")){
                    $total['hadir'] += 1;

                    if($item->timin > $item->shift->schedule_in){
                        $total['terlambat'] += 1;
                    }
                } else {
                    $total['mangkir'] +=1;
                }


                $att_data[$item->att_date] = $item;
            }
        }

        $data_schedule = Att_workgroup_schedule::where("periode", $periode->id)
            ->where("workgroup", $reg->workgroup)
            ->first();

        $correction = Att_schedule_correction::where("emp_id", $reg->emp_id)
            ->whereBetween("date", [$periode->start_date, $periode->end_date])
            ->get();
        $emp_ovr = [];
        foreach($correction as $item){
            $emp_ovr[$item->date] = $item;
        }

        $schedules = [];

        $shift = Att_shift::where("company_id", Session::get("company_id"))->orWhere("is_default", 1)->get();

        $shift_code = $shift->pluck("shift_id", "id");
        $day_code = $shift->pluck("day_code", "id");

        $day_name = Att_day_code::pluck("day_name", "id");

        $rr = \App\Models\Att_reason_name::get();

        $rname = $rr->pluck("reason_name", "id");
        $rcolor = $rr->pluck("color", "id");

        $overtime = \App\Models\Att_overtime_record::where("emp_id", $reg->emp_id)
            ->whereBetween("overtime_date", [$periode->start_date, $periode->end_date])
            ->whereNotNull("approved_at")
            ->get();

        $ovt_tp = [];
        foreach($overtime as $item){
            $ovt_tp[$item->overtime_date][] = $item;
        }

        // $ovt_tp = $overtime->pluck("overtime_type", "overtime_date");

        foreach($data_schedule->detail ?? [] as $item){
            $col = [];
            $col['date'] = $item['date'];
            $col['shift'] = $item['shift_id'];
            if(isset($emp_ovr[$item['date']])){
                $col['date'] = $emp_ovr[$item['date']]['date'];
                $col['shift'] = $emp_ovr[$item['date']]['shift_id'];
            }
            $schedules[] = $col;
        }

        return view("_attendance.correction.detail", compact("periode", "ovt_tp", "reg", "rname", 'att_data', 'total', 'schedules', 'shift_code', 'day_code', 'day_name', 'rcolor'));
    }

    function employee_correction(Request $request){
        $data = json_decode($request->data ?? "[]", true);
        foreach($data as $idEmp => $item){
            if($item['checked']){
                foreach($item['data'] as $val){
                    $correction = Att_schedule_correction::firstOrNew([
                        "emp_id" => $idEmp,
                        "date" => $val['date']
                    ]);

                    $correction->shift_id = $val['shift'];
                    if(empty($correction->id)){
                        $correction->company_id = Session::get('company_id');
                        $correction->created_by = Auth::id();
                    }

                    $correction->updated_by = Auth::id();
                    $correction->save();
                }
            }
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Successfully Edit Data Schedule",
                'bg' => "bg-success"
            ],
            "tab" => "tab_employee"
        ]);
    }

    function workgroup_correction(Request $request){
        $data = json_decode($request->data, true);

        $wg = Att_workgroup::find($data['id']);

        if(empty($wg)){
            return redirect()->back();
        }

        $periode = Att_periode::where('company_id', Session::get('company_id'))
            ->where(function($q) use($data){
                $q->where("start_date", "<=", $data['date']);
                $q->where("end_date", ">=", $data['date']);
            })
            ->first();

        $wg_schedule = Att_workgroup_schedule::where("workgroup", $wg->id)
            ->where("periode", $periode->id)
            ->where('company_id', Session::get("company_id"))
            ->first();

        if(empty($wg_schedule)){
            return redirect()->back();
        }

        $schedules = collect($wg_schedule->detail);

        $index = $schedules->search(function($res) use($data){
            return $res['date'] == $data['date'];
        });

        $correction = $schedules->where('date', $data['date'])->first();

        $shift = Att_shift::find($data['shift']);

        $breaks = $shift->break_shifts;

        $dcode = Att_day_code::find($shift->day_code);

        $correction['shift_id'] = $shift->id;
        $correction['time_in'] = $shift->schedule_in;
        $correction['time_out'] = $shift->schedule_out;
        $correction['break_1'] = $breaks[0]->start ?? "-";
        $correction['break_2'] = $breaks[1]->start ?? "-";
        $correction['day_off'] = $dcode->attend == 1 ? false : true;

        $schedules[$index] = $correction;

        $wg_schedule->detail = $schedules;
        $wg_schedule->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Successfully Edit Data Schedule",
                'bg' => "bg-success"
            ],
            "tab" => "tab_workgroup"
        ]);
    }
}
