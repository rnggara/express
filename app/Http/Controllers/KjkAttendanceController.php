<?php

namespace App\Http\Controllers;

use App\Models\Att_collect_datum;
use App\Models\Att_dashboard_widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use App\Models\Att_periode;
use App\Models\Att_employee_registration;
use App\Models\Att_leave_request;
use App\Models\Att_overtime_record;
use App\Models\Att_reason_condition;
use App\Models\Att_reason_name;
use App\Models\Att_schedule_correction;
use App\Models\Att_shift;
use App\Models\Att_workgroup;
use App\Models\Att_workgroup_schedule;
use App\Models\Kjk_comp_departement;
use App\Models\Att_reason_record;
use App\Models\Division;
use App\Models\Hrd_employee_type;
use App\Models\User_attendance;
use App\Models\User;

class KjkAttendanceController extends Controller
{
    function index(Request $request){
        Session::put("session_state", "attendance");
        Session::put("home_url", route("attendance.index"));


        $_today = [];

        $periodes = Att_periode::where("company_id", Session::get("company_id"))
            ->orderBy("start_date")
            ->get();

        $now = date("Y-m-d");

        $reg = Att_employee_registration::where("company_id", Session::get("company_id"))
            ->get();

        $workgroups = Att_workgroup::where("company_id", Session::get("company_id"))
            ->get();

        $reasons = Att_reason_condition::where("company_id", Session::get("company_id"))
            ->where("status", 1)
            ->get();

        $reason_names = Att_reason_name::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->where('status', 1)->where('show_dashboard', 1)->orderBy('status', 'desc')->get();

        $leave_request = Att_leave_request::where("company_id", Session::get("company_id"))
            ->whereYear("created_at", date("Y"))
            ->orderBy("created_at", 'desc')
            ->get();

        $today_leave = $leave_request->where('start_date', ">=", date("Y-m-d"))->where('end_date', "<=", date("Y-m-d"));

        $registrations = Att_employee_registration::where("company_id", Session::get("company_id"))
            ->get();

        $attRecord = \App\Models\Att_reason_record::whereIn("emp_id", $registrations->pluck("emp_id"))
            // ->whereNotIn("emp_id", $today_leave->pluck("emp_id"))
            ->orderBy('att_date')
            ->get();

        $hadir = 0;
        $mangkir = 0;
        $_today = [];
        $_yesterday = [];
        foreach($attRecord->where("att_date", date("Y-m-d")) as $item){
            foreach($item->reasons ?? [] as $val){
                $_today[$val['id']][] = $item->id;
            }
        }

        foreach($attRecord->where("att_date", date("Y-m-d", strtotime("-1 day"))) as $item){
            foreach($item->reasons ?? [] as $val){
                $_yesterday[$val['id']][] = $item->id;
            }
        }

        $widgets = Config::get("constants.ATTENDANCE_DASHBOARD_WIDGET");

        $widget_dashboard = Att_dashboard_widget::where([
            "status" => 1,
            "company_id" => Session::get('company_id')
        ])->get();

        $departements = Kjk_comp_departement::where("company_id", Session::get('company_id'))->get();

        if($request->type == "leave_request"){

            $leave_request = $leave_request->sortBy($request->sort_by);

            $view = view("_attendance.widgets.leave_request", compact("leave_request"));

            return json_encode([
                "view" => $view->render()
            ]);
        }

        if($request->type == "att_track"){
            $per = $periodes->where("id", $request->periode)->first();

            $user_dept = [];
            if(!empty($request->div)){
                $user_dept = User::where('company_id', Session::get("company_id"))->where("uac_departement", $request->div)
                    ->get();
            }

            $registrations = Att_employee_registration::where("company_id", Session::get("company_id"))
                ->where(function($q) use($user_dept){
                    if(!empty($user_dept)){
                        $q->whereIn("emp_id", $user_dept->pluck("emp_id"));
                    }
                })
                ->get();

            $reasons = \App\Models\Att_reason_record::whereIn("emp_id", $registrations->pluck("emp_id"))
                ->whereBetween("att_date", [$per->start_date, $per->end_date])
                ->where("reasons", "like", '%"id":'.$request->reason.'%')
                ->orderBy('att_date')
                ->get();

            $rcount = [];
            foreach($reasons as $item){
                if((!empty($item->timin) && $item->timin != "0000-00-00 00:00:00") && (!empty($item->timout) && $item->timout != "0000-00-00 00:00:00")){
                    $rcount[$item->att_date][] = $item->emp_id;
                }
            }

            $weeks = [];

            $start_date = $per->start_date;
            $end_date = $per->end_date;

            $dnow = $start_date;

            $iweek = intval(date("W", strtotime($start_date)));

            while ($dnow <= $end_date) {
                $n = date("N", strtotime($dnow));
                $weeks[$iweek][$n] = $dnow;
                if($n >= 7){
                    $iweek++;
                }
                $dnow = date("Y-m-d", strtotime($dnow." +1 day"));
            }

            return json_encode([
                "data" => $weeks,
                "reg" => $reg->count(),
                'rcount' => $rcount
            ]);
        }

        if($request->type == "att_data"){
            $date = $request->filter['date'];
            $dnow = date("Y-m-d");
            if(!empty($date)){
                $_date = explode("/", $date);
                krsort($_date);
                $dnow = implode("-", $_date);
            }

            $fwg = $request->filter['workgroup'];

            $row = [];

            $registrations = Att_employee_registration::where("company_id", Session::get("company_id"))
                ->where(function($q) use($fwg){
                    if(!empty($fwg)){
                        $q->where("workgroup", $fwg);
                    }
                })
                ->get();

            $periode = Att_periode::where("company_id", Session::get("company_id"))
                ->where(function($q) use($dnow){
                    $q->where("start_date", "<=", $dnow);
                    $q->where("end_date", ">=", $dnow);
                })->first();


            $perId = $periode->id ?? null;

            $schedule = Att_workgroup_schedule::where("periode", $perId)
                ->get();

            $shift_emp = [];
            foreach($schedule as $item){
                $detail = $item->detail ?? [];
                foreach($detail as $val){
                    $shift_emp[$item->workgroup][$val['date']] = intval($val['shift_id']);
                }
            }

            $wg_schedule = $schedule->pluck("detail", "workgroup");

            $emp_correction = Att_schedule_correction::where("company_id", Session::get('company_id'))
                ->get();
            $emp_ov = [];
            foreach($emp_correction as $item){
                $emp_ov[$item->emp_id][$item->date] = $item->shift ?? [];
            }

            $reasons = \App\Models\Att_reason_record::whereIn("emp_id", $registrations->pluck("emp_id"))
                ->where("att_date", $dnow)
                ->orderBy('att_date')
                ->get();
            $reason_att = [];
            foreach($reasons as $item){
                $reason_att[$item->emp_id][$item->att_date] = $item;
            }

            $rname = \App\Models\Att_reason_name::pluck("reason_name", "id");

            $shift_name = Att_shift::pluck("shift_id", "id");

            $freason = $request->filter['reason'];

            $holidays = \App\Models\Att_holiday::where('company_id', Session::get("company_id"))->pluck("name", "id");

            foreach($registrations as $item){
                if(isset($reason_att[$item->emp_id])){
                    foreach($reason_att[$item->emp_id] as $date => $data){
                        $col = [];
                        $rcon = $data->reason_condition ?? [];
                        $rs = $data->reasons ?? [];
                        $rid = [];
                        $hadir = '<span class="badge badge-danger">Mangkir</span>';
                        if($data->timin != "00:00:00" && $data->timout != "00:00:00"){
                            $hadir = "<div class='d-flex align-items-center'>";
                            foreach($rs as $rp){
                                $_rname = $rname[$rp['id']] ?? null;
                                $rid[]= $rp['id'];
                                if(!empty($_rname)){
                                    $hadir .= '<span class="me-2 badge badge-'.($_rname == "Hadir" ? "success" : ($_rname == "Mangkir" ? "danger" : "warning")).'">'.$_rname.'</span>';
                                }
                            }
                            $hadir .= "</div>";
                        }

                        $shift_id = $shift_emp[$item->workgroup][$date] ?? null;
                        if(isset($emp_ov[$item->emp_id])){
                            if(isset($emp_ov[$item->emp_id][$date])){
                                $shift_id = $emp_ov[$item->emp_id][$date];
                            }
                        }

                        $ename = "<div class='d-flex align-items-center'>".
                                "<div class='symbol me-3 symbol-40px'><div class='symbol-label' style='background-image : url(".(asset($item->emp->user->user_img ?? "images/image_placeholder.png")).")'></div></div>".
                                "<div class='d-flex flex-column'>" .
                                "<span class='fw-bold'>".$item->emp->emp_name."</span>".
                                "<span class=''>".$item->emp->emp_id."</span>".
                                "</div>".
                                "</div>";

                        $wgdiv = "<div class='d-flex flex-column'>" .
                            "<span class='fw-bold'>".$item->wg->workgroup_name."</span>".
                            "<span class=''>Shift ".($shift_name[$shift_id] ?? "-")."</span>".
                            "</div>";

                        $ovtHours = "-";

                        if($data->is_holiday == 1){
                            $_holiday = $holidays[$data->holiday_id] ?? "Holiday";
                            $hadir = '<span class="badge badge-secondary">'.$_holiday.'</span>';
                        }

                        if(!empty($data->ovtstart) && !empty($data->ovtend)){
                            $o1 = date_create($data->ovtstart);
                            $o2 = date_create($data->ovtend);
                            $o3 = date_diff($o1, $o2);
                            $d = $o3->format("%a");
                            $H = $o3->format("%h");

                            $ovtHours = (($d * 24) + $H)." hours";
                        }

                        $col[] = $ename;
                        $col[] = $wgdiv;
                        $col[] = date("d F Y", strtotime($data->att_date));
                        $col[] = $data->timin;
                        $col[] = $data->timout;
                        $col[] = $ovtHours;
                        $col[] = $hadir;

                        if(!empty($freason)){
                            if(in_array($freason, $rid)){
                                $row[] = $col;
                            }
                        } else {
                            $row[] = $col;
                        }
                    }
                }
            }

            return json_encode($row);
        }

        if($request->a == "table_today"){
            $r = $request->reason;
            $fwg = $request->workgroup;
            $fdep = $request->departement;

            $udep = User::hris()->where("company_id", Session::get('company_id'))
                ->whereNotNull("emp_id")
                ->where(function($q) use($fdep){
                    if(!empty($fdep)){
                        $q->where("uac_departement", $fdep);
                    }
                })
                ->get();

            $_reg = Att_employee_registration::where("company_id", Session::get("company_id"))
                ->where(function($q) use($fwg){
                    if(!empty($fwg)){
                        $q->where("workgroup", $fwg);
                    }
                })
                ->where(function($q) use($udep){
                    if(!empty($udep)){
                        $q->whereIn("emp_id", $udep->pluck("emp_id"));
                    }
                })
                ->get();

            $ereg = \App\Models\Att_reason_record::whereIn("emp_id", $_reg->pluck("emp_id"))
                ->where("att_date", date("Y-m-d"))
                ->where("reasons", "like", '%"id":'.$r.'%')
                ->orderBy('att_date')
                ->get();

            $wg_name = [];
            foreach($_reg as $item){
                $wg_name[$item->emp_id] = $item->wg->workgroup_name;
            }

            $shift_name = \App\Models\Att_shift::whereIn("id", $ereg->pluck("shift_id"))->pluck("shift_id", "id");

            $uimg = $udep->pluck("user_img", "emp_id");

            $rname = $reason_names->pluck("reason_name", "id");

            $row = [];
            foreach($ereg as $item){
                $col = [];
                $_asset = asset($uimg[$item->emp_id] ?? "images/image_placeholder.png");
                $_name = "<div class='d-flex align-items-center'>" .
                    "<div class='symbol symbol-40px me-5'><div class='symbol-label' style=\"background-image : url('$_asset')\"></div></div>" .
                    "<div class='d-flex flex-column'><span class='fw-bold'>".$item->emp->emp_name."</span><span>".$item->emp->emp_id."</span></div>" .
                    "</div>";
                $_wg = "<div class='d-flex flex-column'><span class='fw-bold'>".($wg_name[$item->emp_id] ?? "-")."</span><span>Shifts ".($shift_name[$item->shift_id] ?? "-")."</span></div>" ;
                $rs = $item->reasons ?? [];
                $hadir = '<span class="badge badge-outline badge-danger">Mangkir</span>';
                if($item->timin != "00:00:00" && $item->timout != "00:00:00"){
                    $hadir = "<div class='d-flex align-items-center'>";
                    foreach($rs as $rp){
                        $_rname = $rname[$rp['id']] ?? null;
                        if(!empty($_rname)){
                            $hadir .= '<span class="me-2 badge badge-outline badge-'.($_rname == "Hadir" ? "success" : ($_rname == "Mangkir" ? "danger" : "warning")).'">'.$_rname.'</span>';
                        }
                    }
                    $hadir .= "</div>";
                }
                $col[] = $_name;
                $col[] = $_wg;
                $col[] = $item->timin != "0000-00-00 00:00:00" ? date("H:i", strtotime($item->timin)) : "-";
                $col[] = $item->timout != "0000-00-00 00:00:00" ? date("H:i", strtotime($item->timout)) : "-";
                $col[] = $hadir;
                $row[] = $col;
            }

            return json_encode($row);
        }

        return view("_attendance.index", compact("_today", 'periodes', 'now', '_yesterday', 'workgroups', 'reasons', 'leave_request', 'widgets', 'widget_dashboard', 'departements', 'reason_names'));
    }

    function chart_widget($widget_key, Request $request){

        $chart = [];
        if($widget_key == "reason_trend"){
            $data = [];
            for ($i=1; $i <= 12; $i++) {
                $dm = date("Y")."-".sprintf("%02d", $i);
                $data[date("M", strtotime($dm))] = 0;
            }

            $records = Att_reason_record::whereYear("att_date", $request->year)
                ->where("reasons", "like", '%"id":'.$request->reason.'%')
                ->where('company_id', Session::get("company_id"))
                ->get();

            foreach($records as $item){
                $_m = date("M", strtotime($item->att_date));
                $data[$_m] += 1;
            }

            foreach($data as $key => $value){
                $col = [];
                $col['x'] = $key;
                $col['y'] = $value;
                $chart[] = $col;
            }

        } elseif($widget_key == "overtime_trend"){
            $data = [];
            for ($i=1; $i <= 12; $i++) {
                $dm = date("Y")."-".sprintf("%02d", $i);
                $data[date("M", strtotime($dm))] = 0;
            }
            $overtimes = Att_overtime_record::whereYear("overtime_date", $request->year)
                ->where(function($q) use($request){
                    if($request->departement != "_all"){
                        $q->where("departement", $request->departement);
                    }
                })->whereNull("rejected_at")
                ->whereNotNull('approved_at')->where('company_id', Session::get("company_id"))->get();
            // dd($overtimes);

            foreach($overtimes as $item){
                $ovtHours = $this->countHours($item->overtime_start_time, $item->overtime_end_time);
                $_m = date("M", strtotime($item->overtime_date));
                $data[$_m] += $ovtHours;
            }

            foreach($data as $key => $value){
                $col = [];
                $col['x'] = $key;
                $col['y'] = $value;
                $chart[] = $col;
            }
        } elseif($widget_key == "overtime_hours"){
            $dnow = date("Y-m");

            $overtimes = Att_overtime_record::where("overtime_date", "like", "$dnow%")
                ->whereNull("rejected_at")
                ->whereNotNull('approved_at')
                ->where('company_id', Session::get("company_id"))->get();

            $departements = Kjk_comp_departement::where('company_id', Session::get("company_id"))->get();
            $deptName = $departements->pluck("name", "id");
            $data = [];
            foreach($departements as $item){
                $data[$item->id] = 0;
            }


            foreach($overtimes as $item){
                $ovtHours = $this->countMinutes($item->overtime_start_time, $item->overtime_end_time);
                $data[$item->departement] += $ovtHours;
            }

            $thour = 0;

            $_chart = [];

            foreach($data as $key => $value){
                $col = [];
                $col['x'] = $deptName[$key];
                $col['y'] = $value;
                $thour += $value;
                if($value > 0){
                    $_chart[] = $col;
                }
            }

            $fweek = date("W", strtotime(date("Y-m-")."01"));
            $lweek = date("W", strtotime(date("Y-m-t")));
            $week = $lweek - $fweek;

            $avg = $thour / $week;

            $chart = [
                "chart" => $_chart,
                "avg" => $avg
            ];

            // dd($overtimes);
        } elseif($widget_key == "absence_rate"){
            $departements = Division::get();
            $deptName = $departements->pluck("name", "id");
            $data = [];
            foreach($departements as $item){
                $data[$item->id] = 0;
            }

            $dnow = date("Y-m");

            $records = Att_reason_record::where("att_date", "like", "$dnow%")
                ->where("reasons", "like", '%"id":'.$request->reason.'%')
                ->where('company_id', Session::get("company_id"))
                ->get();

            foreach($records as $item){
                if(!empty($item->emp->division) && isset($data[$item->emp->division])){
                    $data[$item->emp->division] += 1;
                }
            }

            $_chart = [];
            $totalHadir = 0;

            foreach($data as $key => $value){
                $col = [];
                $col['x'] = $deptName[$key];
                $col['y'] = $value;
                if($value > 0){
                    $totalHadir += $value;
                    $_chart[] = $col;
                }
            }

            $t = date("t");

            $total = $t * count($_chart);

            $fweek = date("W", strtotime(date("Y-m-")."01"));
            $lweek = date("W", strtotime(date("Y-m-t")));
            $week = $lweek - $fweek;
            $avg = 0;
            if($total > 0){
                $avg = number_format(($totalHadir / $total) * 100, 2, ".", "");
            }

            $chart = [
                "chart" => $_chart,
                "avg" => $avg,
                't' => $t
            ];
        } else {
            $labels = [
                "WFO", "Customers", "WFH", "WFA", "Other"
            ];

            $att = User_attendance::where('company_id', Session::get("company_id"))
                ->whereYear("clock_time", date("Y"))
                ->where("clock_type", "clock_in")
                ->get();

            $_att = [];
            foreach($att as $item){
                $_att[$item->location_type][] = $item->id;
            }

            $data = [];
            foreach($labels as $key => $v){
                $cnt = 0;
                if(isset($_att[$key + 1])){
                    $cnt = count($_att[$key + 1]);
                }
                $data[] = $cnt;
            }

            $chart = [
                "labels" => $labels,
                "chart" => $data
            ];
        }

        return json_encode($chart);
    }

    function countMinutes($d1, $d2){
        $date1 = date_create($d1);
        $date2 = date_create($d2);
        $d3 = date_diff($date1, $date2);
        $d = $d3->format("%a");
        $h = $d3->format("%h");
        $m = $d3->format("%i");
        $hours = ($d * 24) + $h;

        $minutes = ($hours * 60) + $m;

        return $minutes;
    }

    function countHours($d1, $d2){
        $date1 = date_create($d1);
        $date2 = date_create($d2);
        $d3 = date_diff($date1, $date2);
        $d = $d3->format("%a");
        $h = $d3->format("%h");
        $hours = ($d * 24) + $h;

        return $hours;
    }

    function update_widget($widget_key, Request $request){

        if($request->checked == "true"){
            $widget_dashboard = Att_dashboard_widget::where([
                "status" => 1,
                "company_id" => Session::get('company_id')
            ])->get();

            if($widget_dashboard->count() >= 4){
                return redirect()->back()->with([
                    "toast" => [
                        "message" => " Maaf, Anda hanya dapat mengaktifkan hingga 4 widget pada dashboard kustom Anda.",
                        "bg" => "bg-danger"
                    ]
                ]);
            }
        }

        $widget = Att_dashboard_widget::firstOrNew([
            "widget_key" => $widget_key,
            "company_id" => Session::get('company_id')
        ]);

        $widget->status = $request->checked == "true" ? 1 : 0;
        $widget->position = $request->position;
        $widget->save();

        return redirect()->back();
    }
}
