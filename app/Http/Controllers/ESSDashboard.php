<?php

namespace App\Http\Controllers;

use App\Models\Att_leave_request;
use App\Models\Att_overtime_record;
use App\Models\Att_reason_group;
use App\Models\Hrd_employee;
use App\Models\Kjk_comp_position;
use App\Models\Personel_attendance_correction_request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client as Http;

class ESSDashboard extends Controller
{
    function index(Request $request){
        $state = "ess";
        Session::put("session_state", $state);
        Session::put("home_url", route("ess.index"));

        $reasonGroup = Att_reason_group::where("company_id", Session::get("company_id"))->get();

        $personel = Hrd_employee::find(Auth::user()->emp_id);

        $posId = $personel->position_id;

        $posChild = Kjk_comp_position::where("parent_id", $posId)->get();

        $hasApproval = $posChild->count() > 0 ? true : false;

        $listcat = [
            "leave" => "Cuti",
            "attendance" => "Koreksi Kehadiran",
            "overtime" => "Lembur"
        ];

        $incorrect_att = [];

        $d1 = date("Y-m-")."01";
        $dnow = date("Y-m-d");

        $rs = \App\Models\Att_reason_record::where("emp_id", $personel->id)
            ->whereBetween("att_date", [$d1, $dnow])
            ->get();

        $_shift = \App\Models\Att_shift::whereIn("id", $rs->pluck('shift_id'))->get();

        $rname = \App\Models\Att_reason_name::where(function($q){
            $q->whereNull("company_id");
            $q->orWhere("company_id", Session::get('company_id'));
        })->pluck("reason_name", "id");

        foreach($rs as $item){
            $sh = $_shift->where("id", $item->shift_id)->first();
            $reasons = $item->reasons ?? [];
            $col = [];
            $col['id'] = $item->id;
            $col['date'] = date("d-m-Y", strtotime($item->att_date));
            $col['remarks'] = "";

            if((empty($item->timin) && empty($item->timout))){
                $col["remarks"] = "Mangkir";
            } else {
                if(empty($item->timin)){
                    $col['remarks'] = "Tidak ada Jam Masuk";
                }

                if(empty($item->timout)){
                    $col['remarks'] = "Tidak ada Jam Keluar";
                }

                if(!empty($item->timin) && !empty($item->timout)){
                    if(empty($reasons)){
                        $col['remarks'] = "Mangkir";
                    } else {
                        foreach($reasons as $k){
                            if($k['id'] != 1){
                                $col['remarks'] .= $rname[$k['id']];
                                break;
                            }
                        }
                    }
                }
            }

            if($col['remarks'] != ""){
                $col['remarks'] = rtrim($col['remarks']);
                $incorrect_att[] = $col;
            }
        }
        // dd($incorrect_att);

        // $incorrect_att = Personel_attendance_correction_request::where("emp_id", $personel->id)->where("company_id", Session::get("company_id"))->orderBy("created_at", "desc")->limit(5)->get();

        if($request->a == "att_summary"){
            $tp = $request->tp;

            $start_date = ($tp == "ytd") ? date("Y")."-01-01" : date("Y-m")."-01";
            $end_date = date("Y-m-d");

            $emp_id = Auth::user()->emp_id;

            return $this->getSummaryData($emp_id, $start_date, $end_date);

            // $kjkCdata = new KjkAttCollectData();

            // $data = [];

            // $pctg_att = 0;
            // $pctg_not_att = 0;
            // $att_day = 0;
            // $not_att_day = 0;
            // $ovt = 0;
            // $late = 0;

            // if(!empty($emp_id)){
            //     $data = $kjkCdata->summaryAttendance($emp_id, $start_date, $end_date);

            //     $wd = $data['workday'] ?? 0;

            //     $_ovt = explode(":", $data['overtime'] ?? "00:00");
            //     $_late = explode(":", $data['hadir'] ?? "00:00");

            //     $ovt = intval($_ovt[0]);
            //     $late = intval($_late[0]);
            //     $att_day = $data['hadir'] ?? 0;
            //     $not_att_day = $wd - $att_day;
            //     if($wd > 0){
            //         $pctg_att = number_format(($att_day / $wd) * 100, 2, ",", "");
            //         $pctg_not_att = number_format(($not_att_day / $wd) * 100, 2, ",", "");
            //     }
            // }

            // $view = view("_ess.widgets.att_summary", compact("pctg_att", "pctg_not_att", "att_day", "not_att_day", "ovt", "late"))->render();

            // return json_encode([
            //     "view" => $view,
            //     "data" => $data
            // ]);
        }

        if($request->a == "list"){
            $k = $request->k;

            $tg = $request->tg;

            return $this->getListData($personel, $k, $tg);

            // $_users = Hrd_employee::whereIn("position_id", $posChild->pluck("id"))->get();

            // $list = [];

            // if($k == "leave"){
            //     $data = Att_leave_request::whereNull("approved_at")
            //         ->where(function($q) use($tg, $personel, $_users){
            //             if($tg == "#pengajuan-content"){
            //                 $q->where("emp_id", $personel->id);
            //             } else {
            //                 $q->whereIn("emp_id", $_users->pluck("id"));
            //             }
            //         })->whereNull("cr")->where("company_id", Session::get("company_id"))->orderBy("created_at", "desc")->get();

            //     foreach($data as $im){
            //         $col = [];
            //         $desc = "";
            //         $sd = date("d-m-Y", strtotime($im->start_date));
            //         $ed = date("d-m-Y", strtotime($im->end_date));
            //         $desc .= ucwords($im->leave_used)." Leave request from $sd - $ed ($im->total_day hari)";
            //         $col['id'] = $im->id;
            //         $col['approved_at'] = $im->approved_at;
            //         $col['created_at'] = $im->created_at;
            //         $col['desc'] = $desc;
            //         $col['url'] = route("ess.leave.index")."?a=detail&id=".$im->id;
            //         $list[] = $col;
            //     }
            // } elseif($k == "overtime"){
            //     $data = Att_overtime_record::whereNull("approved_at")
            //         ->where(function($q) use($tg, $personel, $_users){
            //             if($tg == "#pengajuan-content"){
            //                 $q->where("emp_id", $personel->id);
            //             } else {
            //                 $q->whereIn("emp_id", $_users->pluck("id"));
            //             }
            //         })->where("company_id", Session::get("company_id"))->orderBy("created_at", "desc")->get();

            //     foreach($data as $im){
            //         $col = [];
            //         $desc = "";
            //         if($tg == "#pengajuan-content"){
            //             $desc = date("d-m-Y", strtotime($im->overtime_date))." (".date("H:i", strtotime($im->overtime_start_time))." - ".date("H:i", strtotime($im->overtime_end_time)).")";
            //         } else {

            //         }
            //         $col['id'] = $im->id;
            //         $col['approved_at'] = $im->approved_at;
            //         $col['created_at'] = $im->created_at;
            //         $col['desc'] = $desc;
            //         $col['url'] = route("ess.leave.index")."?a=detail&id=".$im->id;
            //         $list[] = $col;
            //     }
            // } else {
            //     $data = Personel_attendance_correction_request::whereNull("approved_at")
            //         ->where(function($q) use($tg, $personel, $_users){
            //             if($tg == "#pengajuan-content"){
            //                 $q->where("emp_id", $personel->id);
            //             } else {
            //                 $q->whereIn("emp_id", $_users->pluck("id"));
            //             }
            //         })->where("company_id", Session::get("company_id"))->orderBy("created_at", "desc")->get();

            //     foreach($data as $im){
            //         $col = [];
            //         $desc = "";
            //         if($tg == "#pengajuan-content"){

            //         } else {

            //         }

            //         $desc .= "<div class='d-flex flex-column gap-3'><span>Jam Masuk & Jam Keluar</span>";
            //         $desc .= "<span>".date("d-m-Y", strtotime($im->date))."</span>";
            //         if(!empty($im->clock_in)){
            //             $lclockIn = empty($im->last_clock_in) ? "N/A" : date("H:i", strtotime($im->last_clock_in));
            //             $clockin = date("H:i", strtotime($im->clock_in));
            //             $desc .= "<span>Jam Masuk : $lclockIn <i class='fi fi-rr-arrow-right'></i> $clockin</span>";
            //         }
            //         if(!empty($im->clock_out)){
            //             $lclockout = empty($im->last_clock_out) ? "N/A" : date("H:i", strtotime($im->last_clock_out));
            //             $clockout = date("H:i", strtotime($im->clock_out));
            //             $desc .= "<span>Jam Keluar : $lclockout <i class='fi fi-rr-arrow-right'></i> $clockout</span>";
            //         }
            //         $desc .= "</div>";
            //         $col['id'] = $im->id;
            //         $col['approved_at'] = $im->approved_at;
            //         $col['created_at'] = $im->created_at;
            //         $col['desc'] = $desc;
            //         $col['url'] = route("ess.leave.index")."?a=detail&id=".$im->id;
            //         $list[] = $col;
            //     }

            //     $data = Att_leave_request::whereNull("approved_at")
            //         ->where(function($q) use($tg, $personel, $_users){
            //             if($tg == "#pengajuan-content"){
            //                 $q->where("emp_id", $personel->id);
            //             } else {
            //                 $q->whereIn("emp_id", $_users->pluck("id"));
            //             }
            //         })->whereNotNUll("cr")->where("company_id", Session::get("company_id"))->orderBy("created_at", "desc")->get();

            //     foreach($data as $im){
            //         $col = [];
            //         $desc = "";
            //         $sd = date("d-m-Y", strtotime($im->start_date));
            //         $ed = date("d-m-Y", strtotime($im->end_date));
            //         $desc .= ucwords($im->leave_used)." Leave request from $sd - $ed ($im->total_day hari)";
            //         $col['id'] = $im->id;
            //         $col['approved_at'] = $im->approved_at;
            //         $col['created_at'] = $im->created_at;
            //         $col['desc'] = $desc;
            //         $col['url'] = route("ess.leave.index")."?a=detail&id=".$im->id;
            //         $list[] = $col;
            //     }

            //     $list = collect($list)->sortByDesc("created_at");
            // }

            // $view = view("_ess.widgets.list", compact("list", "tg"));

            // return json_encode([
            //     "view" => $view->render()
            // ]);
        }

        if($request->a == "take_attendance"){

            $step = $request->step ?? null;

            $data = null;

            if(empty($step)){


                $response = $this->take_attendance();

                if($response['success']){
                    $data = $response['data'];
                }

                $qrandom = $data['question_random'] ?? [];

                Session::put("att_qrandom", $qrandom);

                $dtarget = Session::get("att_waiting") ?? null;
                // dd($dtarget, date("Y-m-d H:i:s"));

                if(!empty($dtarget)){
                    if(date("Y-m-d H:i:s") < $dtarget){
                        $d1 = date_create(date("Y-m-d H:i:s"));
                        $d2 = date_create($dtarget);
                        $d3 = date_diff($d2, $d1);

                        $s = $d3->format("%s");

                        $waitAtt = $dtarget;
    
                        $view = view("_ess.widgets.att_waiting", compact("waitAtt", "s"))->render();
                    } else {
                        Session::put("att_question_wrong", 0);
                        $view = view("_ess.widgets.take_attendance", compact("data"))->render();   
                    }
                } else {
                    Session::put("att_question_wrong", 0);
                    $view = view("_ess.widgets.take_attendance", compact("data"))->render();   
                }
            } elseif(in_array($step, ["clock_in", "clock_out", "break_out", "break_in"])){
                Session::put("att_state", $step);
                if($step == "clock_in"){
                    $declareState = ["1" => "Sangat Sehat", "Kurang Sehat", "Tidak Sehat"];
                    $view = view("_ess.widgets.att_declare", compact("declareState"))->render();
                } else {
                    $view = view("_ess.widgets.att_cam")->render();
                }
            } else {
                $expState = explode("_", $step);

                if($expState[0] == "declare"){
                    Session::put("att_declare", $expState[1]);

                    $qrandom = Session::get("att_qrandom");

                    $question = $qrandom[rand(0, count($qrandom)) - 1];

                    $view = view("_ess.widgets.att_question", compact("question"))->render();
                }

                if($expState[0] == "question"){
                    $isTrue = $expState[1];

                    $wrongCount = Session::get("att_question_wrong") ?? 0;

                    if($isTrue == 0){
                        $wrongCount++;

                        Session::put("att_question_wrong", $wrongCount);

                        if($wrongCount < 3){
                            $qrandom = Session::get("att_qrandom");

                            $question = $qrandom[rand(0, count($qrandom)) - 1];

                            $view = view("_ess.widgets.att_question", compact("question"))->render();
                        } else {
                            $waitAtt = date("Y-m-d H:i:s", strtotime("+30 second"));
                            Session::put("att_waiting", $waitAtt);

                            $view = view("_ess.widgets.att_waiting", compact("waitAtt"))->render();
                        }
                    } else {
                        $view = view("_ess.widgets.att_cam")->render();
                    }
                }
            }

            return json_encode([
                "view" => $view,
                "data" => $data
            ]);
        }

        $personelOnboarding = new \App\Http\Controllers\PersonelOnboarding();

        $brDetail = $personelOnboarding->upload_data(Auth::id(), true);

        $brDetailCount = $brDetail->count();

        if($brDetailCount == $brDetail->whereNotNull("action_at")->count()){
            $brDetail = collect([]);
        }

        $boardTask = collect($personelOnboarding->approve_data(Auth::id(), true));

        return view("_ess.index", compact("reasonGroup", "hasApproval", "listcat", "incorrect_att", "brDetail", "boardTask"));
    }

    function getListData($personel, $k, $tg){
        $posId = $personel->position_id;

        $posChild = Kjk_comp_position::where("parent_id", $posId)->get();

        $_users = Hrd_employee::whereIn("position_id", $posChild->pluck("id"))->get();

        $list = [];

        if($k == "leave"){
            $data = Att_leave_request::whereNull("approved_at")
                ->where(function($q) use($tg, $personel, $_users){
                    if($tg == "#pengajuan-content"){
                        $q->where("emp_id", $personel->id);
                    } else {
                        $q->whereIn("emp_id", $_users->pluck("id"));
                    }
                })->whereNull("cr")->where("company_id", Session::get("company_id"))->orderBy("created_at", "desc")->get();

            foreach($data as $im){
                $col = [];
                $desc = "";
                $sd = date("d-m-Y", strtotime($im->start_date));
                $ed = date("d-m-Y", strtotime($im->end_date));
                $desc .= ucwords($im->leave_used)." Leave request from $sd - $ed ($im->total_day hari)";
                $col['id'] = $im->id;
                $col['approved_at'] = $im->approved_at;
                $col['created_at'] = $im->created_at;
                $col['desc'] = $desc;
                $col['url'] = route("ess.leave.index")."?a=detail&id=".$im->id;
                $list[] = $col;
            }
        } elseif($k == "overtime"){
            $data = Att_overtime_record::whereNull("approved_at")
                ->where(function($q) use($tg, $personel, $_users){
                    if($tg == "#pengajuan-content"){
                        $q->where("emp_id", $personel->id);
                    } else {
                        $q->whereIn("emp_id", $_users->pluck("id"));
                    }
                })->where("company_id", Session::get("company_id"))->orderBy("created_at", "desc")->get();

            foreach($data as $im){
                $col = [];
                $desc = "";
                if($tg == "#pengajuan-content"){
                    $desc = date("d-m-Y", strtotime($im->overtime_date))." (".date("H:i", strtotime($im->overtime_start_time))." - ".date("H:i", strtotime($im->overtime_end_time)).")";
                } else {

                }
                $col['id'] = $im->id;
                $col['approved_at'] = $im->approved_at;
                $col['created_at'] = $im->created_at;
                $col['desc'] = $desc;
                $col['url'] = route("ess.leave.index")."?a=detail&id=".$im->id;
                $list[] = $col;
            }
        } else {
            $data = Personel_attendance_correction_request::whereNull("approved_at")
                ->where(function($q) use($tg, $personel, $_users){
                    if($tg == "#pengajuan-content"){
                        $q->where("emp_id", $personel->id);
                    } else {
                        $q->whereIn("emp_id", $_users->pluck("id"));
                    }
                })->where("company_id", Session::get("company_id"))->orderBy("created_at", "desc")->get();

            foreach($data as $im){
                $col = [];
                $desc = "";
                if($tg == "#pengajuan-content"){

                } else {

                }

                $desc .= "<div class='d-flex flex-column gap-3'><span>Jam Masuk & Jam Keluar</span>";
                $desc .= "<span>".date("d-m-Y", strtotime($im->date))."</span>";
                if(!empty($im->clock_in)){
                    $lclockIn = empty($im->last_clock_in) ? "N/A" : date("H:i", strtotime($im->last_clock_in));
                    $clockin = date("H:i", strtotime($im->clock_in));
                    $desc .= "<span>Jam Masuk : $lclockIn <i class='fi fi-rr-arrow-right'></i> $clockin</span>";
                }
                if(!empty($im->clock_out)){
                    $lclockout = empty($im->last_clock_out) ? "N/A" : date("H:i", strtotime($im->last_clock_out));
                    $clockout = date("H:i", strtotime($im->clock_out));
                    $desc .= "<span>Jam Keluar : $lclockout <i class='fi fi-rr-arrow-right'></i> $clockout</span>";
                }
                $desc .= "</div>";
                $col['id'] = $im->id;
                $col['approved_at'] = $im->approved_at;
                $col['created_at'] = $im->created_at;
                $col['desc'] = $desc;
                $col['url'] = route("ess.leave.index")."?a=detail&id=".$im->id;
                $list[] = $col;
            }

            $data = Att_leave_request::whereNull("approved_at")
                ->where(function($q) use($tg, $personel, $_users){
                    if($tg == "#pengajuan-content"){
                        $q->where("emp_id", $personel->id);
                    } else {
                        $q->whereIn("emp_id", $_users->pluck("id"));
                    }
                })->whereNotNUll("cr")->where("company_id", Session::get("company_id"))->orderBy("created_at", "desc")->get();

            foreach($data as $im){
                $col = [];
                $desc = "";
                $sd = date("d-m-Y", strtotime($im->start_date));
                $ed = date("d-m-Y", strtotime($im->end_date));
                $desc .= ucwords($im->leave_used)." Leave request from $sd - $ed ($im->total_day hari)";
                $col['id'] = $im->id;
                $col['approved_at'] = $im->approved_at;
                $col['created_at'] = $im->created_at;
                $col['desc'] = $desc;
                $col['url'] = route("ess.leave.index")."?a=detail&id=".$im->id;
                $list[] = $col;
            }

            $list = collect($list)->sortByDesc("created_at");
        }

        $view = view("_ess.widgets.list", compact("list", "tg"));

        return json_encode([
            "view" => $view->render(),
            'data' => [
                "list" => $list,
                "tg" => $tg
            ]
        ]);
    }

    function getSummaryData($emp_id, $start_date, $end_date){
        $kjkCdata = new KjkAttCollectData();

        $emp_id = Auth::user()->emp_id;

        $data = [];

        $pctg_att = 0;
        $pctg_not_att = 0;
        $att_day = 0;
        $not_att_day = 0;
        $ovt = 0;
        $late = 0;

        if(!empty($emp_id)){
            $data = $kjkCdata->summaryAttendance($emp_id, $start_date, $end_date);
            // dd($data);

            $wd = $data['workday'] ?? 0;

            $_ovt = explode(":", $data['overtime'] ?? "00:00");
            $_late = explode(":", $data['late'] ?? "00:00");

            $ovt = intval($_ovt[0] > 0 ? $_ovt[0] : 0);
            $late = intval($_late[0] > 0 ? $_late[0] : 0);
            $att_day = $data['hadir'] ?? 0;
            $not_att_day = $wd - $att_day;
            if($wd > 0){
                $pctg_att = number_format(($att_day / $wd) * 100, 2, ",", "");
                $pctg_not_att = number_format(($not_att_day / $wd) * 100, 2, ",", "");
            }
        }

        $view = view("_ess.widgets.att_summary", compact("pctg_att", "pctg_not_att", "att_day", "not_att_day", "ovt", "late"))->render();

        return json_encode([
            "view" => $view,
            "data" => [
                "pctg_att" => $pctg_att,
                "pctg_not_att" => $pctg_not_att,
                "att_day" => $att_day,
                "not_att_day" => $not_att_day,
                "ovt" => $ovt,
                "late" => $late,
            ]
        ]);
    }

    function attend(Request $request){
        $wh = Auth::user()->uaclocation ?? [];
        $user = Auth::user();
        $api_token = $user->api_token;
        $url = "https://backend.kerjaku.cloud/api/attendance/post?token=$api_token";
        $query = [
            [
                "name" => "location",
                'contents' => $wh->id ?? 0,
            ],
            [
                "name" => "address",
                'contents' => $wh->address ?? null,
            ],
            [
                "name" => "longitude",
                'contents' => $wh->longitude ?? null,
            ],
            [
                "name" => "latitude",
                'contents' => $wh->latitude ?? null,
            ],
            [
                "name" => "time",
                'contents' => date("Y-m-d H:i:s"),
            ],
            [
                "name" => "id",
                'contents' => $user->id,
            ],
            [
                "name" => "location_type",
                'contents' => empty($wh) ? 4 : 1,
            ],
            [
                "name" => "type",
                'contents' => Session::get("att_state"),
            ],
            [
                "name" => "notes",
                'contents' => Session::get("att_notes"),
            ],
            [
                "name" => "condition",
                'contents' => Session::get("att_declare"),
            ],
            [
                'name'     => 'image',
                'contents' => file_get_contents($request->file('webcam')->getPathname()),
                'filename' => date("YmdHis")."_att_$user->id.".$request->file('webcam')->getClientOriginalExtension()
            ],
        ];


        $http = new Http();

        $response = $http->post($url, [
            "multipart" => $query
        ]);
        Session::put("att_waiting", null);
        Session::put("att_question_wrong", 0);
        Session::put("att_qrandom", null);
        Session::put("att_declare", null);
        Session::put("att_notes", null);
        Session::put("att_state", null);

        return json_decode((string) $response->getBody(), true);
    }

    function take_attendance(){
        $user = Auth::user();

        $api_token = $user->api_token;

        $http = new Http();

        $url = "https://backend.kerjaku.cloud/api/attendance/detail/$user->id?token=$api_token";

        $response = $http->get($url);

        return json_decode((string) $response->getBody(), true);
    }
}
