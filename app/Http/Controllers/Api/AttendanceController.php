<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User_attendance;
use App\Models\Hrd_employee;
use App\Models\Kjk_employee_location;
use App\Models\Kjk_employee_home;
use App\Models\Asset_wh;
use App\Models\Marketing_clients;
use App\Models\Artikel_model;
use App\Models\Preference_config;
use App\Helpers\Notification;
use App\Models\Hrd_employee_question;
use App\Models\Hrd_employee_question_point;
use App\Models\Master_marital_status;
use App\Models\Att_employee_registration;
use Illuminate\Http\Request;

class AttendanceController extends BaseController
{
    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/user"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }
    function getDateTime(){
        $time = date("H:i:s");
        $date = date("Y-m-d");

        $_time = explode(":", $time);
        $break = false;
        if($_time[0] > 12 && $_time[0] <= 13){
            $break = true;
        }

        $data = [
            "time" => $time,
            "date" => $date,
            "break" => $break,
            "time_12" => date("H:i a")
        ];

        return $this->sendResponse($data, "success");
    }

    function dateId($value){
        $id_month = [1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $d = date("d", strtotime($value));
        $m = date("n", strtotime($value));
        $y = date("Y", strtotime($value));
        $F = $id_month[$m];
        $date = "$d $F $y";
        if(empty($value)){
            $date = "-";
        }
        return $date;
    }

    function get_articles(Request $request){
        $user = User::where("api_token", $request->token)->first();
        $articles = Artikel_model::where('company_id', $user->company_id ?? null)
            // ->inRandomOrder()
            ->take(5)
            ->get();

        $data = [];

        foreach($articles as $article){
            $row = [];
            $row['id'] = $article->id;
            $row['title'] = $article->subject;
            $row['description'] = $article->description;
            $row['thumbnail'] = asset($article->thumbnail);
            $row['banner'] = asset($article->drawing);
            $row['author'] = $article->created_by;
            $row['date'] = $article->created_at;
            $row['dmy'] = date("d/m/Y", strtotime($article->created_at));
            $row['dFy'] = $this->dateId($article->created_at);
            $data[] = $row;
        }

        return $this->sendResponse($data, "success");
    }

    function getDetail($id, Request $request){
        $today = date("Y-m-d");
        $attendance = User_attendance::where("user_id", $id)
            ->where("clock_time", "like", "$today%")
            ->orderBy('id')
            ->get();
        $clockoutTime = null;
        $clockinTime = null;
        $breakoutTime = null;
        $breakinTime = null;

        $isEmp = false;
        $empId = null;

        $attType = 4;
        $locId = null;
        $locName = "";

        $locations = [];

        $question_personal = [];
        $question_random = [];

        $user = User::find($id);
        $anywhere = false;
        if(!empty($user->emp_id)){
            $emp = $user->emp;
            $anywhere = $emp->anywhere == 1 ? true : false;
            if(!empty($emp) && empty($emp->expel) && empty($emp->finalize_expel)){
                $empId = $emp->id;
                $isEmp = true;

                $empLoc = Kjk_employee_location::where("emp_id", $emp->id)->get();

                $loc = Asset_wh::whereIn("id", $empLoc->pluck("wh_id"))->get();

                foreach($empLoc as $item){
                    $locWh = $loc->where("id", $item->wh_id)->first();
                    if(!empty($locWh)){
                        if(!empty($locWh->latitude) && !empty($locWh->longitude)){
                            if(is_numeric($locWh->latitude) && is_numeric($locWh->longitude)){
                                try {
                                    $d = $this->getDistance($locWh->latitude, $locWh->longitude, $request->latitude, $request->longitude);
                                    // if($locWh->id == 2){
                                    //     dd($d, $locWh->longitude2);
                                    // }
                                    $col = [];
                                    $col['type'] = 1;
                                    $col['location_id'] = $locWh->id;
                                    $col['name'] = $locWh->name;
                                    $col['address'] = $locWh->address ?? "-";
                                    $col['inRadius'] = $d <= $locWh->longitude2 ? true : false;
                                    $col['order'] = $d <= $locWh->longitude2 ? 4 : 0;
                                    $locations[] = $col;
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            }
                        }
                    }
                }

                $client = Marketing_clients::whereNotNull("latitude")
                    ->whereNotNull("longitude")
                    ->get();
                foreach($client as $item){
                    if(!empty($item->latitude) && !empty($item->longitude)){
                        $d = $this->getDistance($item->latitude, $item->longitude, $request->latitude, $request->longitude);
                        if($d <= $item->longitude2){
                            $col = [];
                            $col['type'] = 2;
                            $col['location_id'] = $item->id;
                            $col['name'] = $locWh->name;
                            $col['address'] = $locWh->address ?? "-";
                            $col['inRadius'] = true;
                            $col['order'] = 3;
                            $locations[] = $col;
                            $attType = 2;
                            $locId = $item->id;
                            $locName = $locWh->company_name;
                            break;
                        }
                    }
                }

                // $empHome = Kjk_employee_home::where("emp_id", $emp->id)->get();
                // foreach($empHome as $item){
                //     if(!empty($item->latitude) && !empty($item->longitude)){
                //         $d = $this->getDistance($item->latitude, $item->longitude, $request->latitude, $request->longitude);
                //         if($d <= $item->radius){
                //             $col = [];
                //             $col['type'] = 3;
                //             $col['location_id'] = $item->id;
                //             $col['name'] = $item->name;
                //             $col['address'] = $item->address;
                //             $col['inRadius'] = true;
                //             $col['order'] = 2;
                //             $locations[] = $col;
                //             $attType = 3;
                //             $locId = $item->id;
                //             $locName = $item->name;
                //             break;
                //         }
                //     }
                // }

                if(!empty($emp->latitude) && !empty($emp->longitude)){
                    if(is_numeric($emp->latitude) && is_numeric($emp->longitude)){
                        $d = $this->getDistance($emp->latitude, $emp->longitude, $request->latitude, $request->longitude);
                        $col = [];
                        $col['type'] = 3;
                        $col['location_id'] = null;
                        $col['name'] = null;
                        $col['address'] = $emp->address;
                        $col['inRadius'] = $d <= 500 ? true : false;
                        $col['order'] = $d <= 500 ? 2 : 0;
                        $locations[] = $col;
                    }
                }

                if(!empty($emp->emp_lahir)){
                    $col = [];
                    $col['label'] = "Tanggal berapakah anda lahir?";
                    $col['point'][] = [
                        "label" => date("d/m/Y", strtotime($emp->emp_lahir)),
                        "is_true" => 1
                    ];

                    for($i = 0; $i < 2; $i++){
                        $rand_d = rand(1,30);
                        $rand_m = rand(1,12);
                        $rand_y = rand(1,3);

                        $_col = [];
                        $_col['label'] = $rand_d .'/'. $rand_m .'/'.date("Y", strtotime("+$rand_y years"));
                        $_col['is_true'] = 0;
                        $col['point'][] = $_col;
                    }

                    shuffle($col['point']);

                    $question_personal[] = $col;
                }

                if(!empty($emp->emp_tmpt_lahir)){
                    $col = [];
                    $col['label'] = "Di kota manakah anda lahir?";
                    $col['point'][] = [
                        "label" => ucwords($emp->emp_tmpt_lahir),
                        "is_true" => 1
                    ];

                    $others = ["Jakarta", "Bandung", "Yogyakarta", "Surabaya", "Malang"];

                    shuffle($others);

                    for($i = 0; $i < 3; $i++){
                        $_c = $others[$i];
                        if($_c != $emp->emp_tmpt_lahir){
                            if(count($col['point']) < 3){
                                $_col = [];
                                $_col['label'] = $_c;
                                $_col['is_true'] = 0;
                                $col['point'][] = $_col;
                            }
                        }
                    }

                    shuffle($col['point']);

                    $question_personal[] = $col;
                }

                $profile = \App\Models\Personel_profile::where("user_id", $emp->id)->first();

                if(!empty($profile->marital_status ?? null)){
                    $marital_status = Master_marital_status::pluck("name", "id");
                    if(isset($marital_status[$profile->marital_status])){
                        $col = [];
                        $col['label'] = "Status perkawinan anda adalah?";
                        $col['point'][] = [
                            "label" => $marital_status[$profile->marital_status],
                            "is_true" => 1
                        ];
                        foreach($marital_status as $id => $name){
                            if($id != $profile->marital_status){
                                $_col = [];
                                $_col['label'] = $name;
                                $_col['is_true'] = 0;
                                $col['point'][] = $_col;
                            }
                        }

                        shuffle($col['point']);

                        $question_personal[] = $col;
                    }
                }

                if(!empty($emp->emp_position)){
                    $col = [];
                    $col['label'] = "Posisi anda adalah sebagai?";
                    $col['point'][] = [
                        "label" => $emp->emp_position,
                        "is_true" => 1
                    ];

                    $others = ["Staff Finance", "Staff Marketing", "Manager Finance", "Manger Marketing", "Staff HRD", "Manager HRD"];

                    shuffle($others);

                    for($i = 0; $i < 3; $i++){
                        $_c = $others[$i];
                        if($_c != $emp->emp_tmpt_lahir){
                            if(count($col['point']) < 3){
                                $_col = [];
                                $_col['label'] = $_c;
                                $_col['is_true'] = 0;
                                $col['point'][] = $_col;
                            }
                        }
                    }

                    shuffle($col['point']);

                    $question_personal[] = $col;
                }
                

                if(count($question_personal) == 0){
                    $question_personal = Hrd_employee_question::select("id", "label")->where("test_id", -1)
                        ->where("company_id", $user->company_id)
                        ->inRandomOrder()
                        ->get();

                    if($question_personal->count() > 0){
                        foreach($question_personal as $item){
                            $question_select = Hrd_employee_question_point::select("label", "is_true")->where("question_id", $item->id)
                                ->inRandomOrder()
                                ->get();
                            $item->point = $question_select;
                        }
                    }
                }
            }
        }

        if($anywhere){
            $col = [];
            $col['type'] = 4;
            $col['location_id'] = null;
            $col['name'] = null;
            $col['address'] = "";
            $col['inRadius'] = true;
            $col['order'] = 1;
            $locations[] = $col;
        }

        $locations = collect($locations)->sortByDesc("order")->values();

    	if(count($attendance) > 0){
    		$clockin = $attendance->where("clock_type", "clock_in")->first();
    		if(!empty($clockin)){
    			$clockinTime = date("H:i", strtotime($clockin->clock_time));
                $user->location_type = 2;
    		}

            $breakout = $attendance->where("clock_type", "break_out")->first();
    		if(!empty($breakout)){
    			$breakoutTime = date("H:i", strtotime($breakout->clock_time));
                $user->location_type = 3;
    		}

    		$breakin = $attendance->where("clock_type", "break_in")->sortByDesc("id")->first();
	    	if(!empty($breakin)){
    			$breakinTime = date("H:i", strtotime($breakin->clock_time));
                $user->location_type = 4;
    		}

            $clockout = $attendance->where("clock_type", "clock_out")->sortByDesc("id")->first();
	    	if(!empty($clockout)){
    			$clockoutTime = date("H:i", strtotime($clockout->clock_time));
                $user->location_type = 1;
    		}

            $user->save();
    	} else {
            $user->location_type = 1;
            $user->save();
        }

        $locType = $user->location_type ?? 1;

        $day = [1=>"Senin", "Selasa","Rabu", "Kamis", "Jum'at", "Sabtu", "Minggu"];
        $month = [1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $d = date("N");
        $bulan = date("n");

        $_day = $day[$d] ?? $day[0];

        $today = $_day.", ".date("d")." ".$month[$bulan]." ".date("Y");

        $pref = Preference_config::where("id_company", $user->company_id)->first();

        $hide_break = $pref->hide_break_session ?? 0;

        $schedule = [
            "clock_in" => $pref->clock_in ?? "09:00:00",
            "break_out" => $pref->break_out ?? "12:00:00",
            "break_in" => $pref->break_in ?? "13:00:00",
            "clock_out" => $pref->clock_out ?? "15:00:00"
        ];

        $question_random = Hrd_employee_question::select("id", "label")->where("test_id", -1)
            ->where("company_id", $user->company_id)
            ->inRandomOrder()
            ->get();

        if($question_random->count() > 0){
            foreach($question_random as $item){
                $question_select = Hrd_employee_question_point::select("label", "is_true")->where("question_id", $item->id)
                    ->inRandomOrder()
                    ->get();
                $item->point = $question_select;
            }
        }

    	$data = [
    		"time" => date("Y-m-d H:i:s"),
    		"clockout" => $clockoutTime,
    		"clockin" => $clockinTime,
            "breakout" => $breakoutTime,
            "breakin" => $breakinTime,
            "isEmp" => $isEmp,
            'locType' => $locType,
            "time_12" => date("h:i a"),
            "today" => $today,
            "attendance_type" => $attType,
            "location_id" => $locId,
            "emp_id" => $empId,
            "location_name" => $locName,
            "locations" => $locations,
            "hide_break" => $hide_break,
            "schedule" => $schedule,
            'question_random' => $question_random,
            'question_personal' => $question_personal

    	];

    	return $this->sendResponse($data, "success");
    }

    function getDistance($latitude1, $longitude1, $latitude2, $longitude2, $earth_radius = 6371000){
        $d = 0;
        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        return $d;
    }

    function attendance_post(Request $request){

        $location = $request->location;
        $addr = $request->address;
        if(!empty($location) && $location > 0){
            $loc = Asset_wh::find($location);
            if(!empty($loc)){
                $addr = $loc->address;
                if(!empty($loc->latitude) && !empty($loc->longitude)){
                    $d = $this->getDistance($request->latitude, $request->longitude, $loc->latitude, $loc->longitude);
                    if($d > ($loc->longitude2 ?? 0)){
                        return $this->sendError("Lokasi anda tidak didalam radius lokasi kantor");
                    }
                } else {
                    return $this->sendError("Akun anda tidak dapat absen di kantor ini");
                }
            } else {
                return $this->sendError("Akun anda tidak dapat absen di kantor ini");
            }
        }

        $_time = explode(" ", $request->time);

        $clock_time = date("Y-m-d H:i:s");
        if(count($_time) > 1){
            $clock_time = $request->time;
        } else {
            $clock_time = date("Y-m-d")." ".$request->time;
        }

        $defaultTimeZone = 7;

        if(!empty($request->time_zone)){
            if($request->time_zone != $defaultTimeZone){
                $offset = $defaultTimeZone - $request->timezone;
                $utc = date("Y-m-d H:i:s", strtotime("-7 hours $clock_time"));
                $clock_time = date("Y-m-d H:i:s", strtotime($request->timezone." hours $utc"));
            }
        }

        $user = User::find($request->id);
        $attendance = new User_attendance();
        $attendance->location_type = $request->location_type;
        $attendance->user_id = $request->id;
        $attendance->clock_type = $request->type;
        $attendance->clock_time = $clock_time;
        $attendance->company_id = $user->company_id;
        $attendance->longitude = $request->longitude;
        $attendance->latitude = $request->latitude;
        $attendance->address = $addr;
        $attendance->notes = $request->notes;
        $attendance->loc_id = $request->location;
        $attendance->condition = $request->condition;
        $file = $request->file('image');
        if(!empty($file)){
            $dd = date('Y_m_d_H_i_s', strtotime($request->time));
            $filename = "[attendance-$user->id][$dd]".$file->getClientOriginalName();
            $dir = str_replace("\\", "/", public_path('media/user'));
            $_dir = str_replace("prototype/public_html", "public_html/kerjaku/assets", $dir);
            // $tdir = str_replace("/", "\\", $_dir);
            if($file->move($this->dir, $filename)){
                $attendance->images = $filename;
            } else {
                return $this->sendError('Failed to upload file');
            }
        }

        if($attendance->save()){

            $dLate = 0;

            $_late = null;

            $pref = Preference_config::where("id_company", $user->company_id)->first();

            if($attendance->clock_type == "clock_in"){
                $_late = date("Y-m-d", strtotime($attendance->clock_time))." ".($pref->clock_in ?? "09:00:00");
            } elseif($attendance->clock_type == "clock_out"){
                $_late = date("Y-m-d", strtotime($attendance->clock_time))." ".($pref->clock_out ?? "17:30:00");
            }

            if(!empty($_late)){
                $d1 = date_create($attendance->clock_time);
                $d2 = date_create($_late);
                $diff = date_diff($d1, $d2);
                $late = $diff->format("%r%h:%r%i");

                $expLate = explode(":", $late);
                if($expLate[1] != 0){
                    $dLate = $expLate[1];
                    if($expLate[0] != 0){
                        $dLate += $expLate[0] * 60;
                    }
                }
            }

            if($attendance->clock_type == "clock_in"){
                $attendance->is_late = $dLate < 0 ? 1 : 0;
            } elseif($attendance->clock_type == "clock_out") {
                $attendance->is_late = $dLate > 0 ? 1 : 0;
            }
            $attendance->save();

            $next = 1;
            if($request->type == "clock_in"){
                $next = 2;
            } elseif($request->type == "break_out"){
                $next = 3;
            } elseif($request->type == "break_in"){
                $next = 4;
            } else{
                $next = 1;
            }

            $user->location_type = $next;
            $user->save();

            $this->reasoning($attendance);

            $attendance->next = $next;

            $msg = ucwords(str_replace("_", " ", $attendance->clock_type));
            $tm = date("H:i:s", strtotime($attendance->clock_time));
            return $this->sendResponse($attendance, "$msg at $tm");
        } else {
            return $this->sendError('Failed to update data');
        }
    }

    function reasoning($attendance){
        $user = User::find($attendance->user_id);

        $emp = Hrd_employee::find($user->emp_id);
        if(!empty($emp)){
            $reg = Att_employee_registration::where("emp_id", $emp->id)->first();

            if(!empty($reg)){
                $date = date("Y-m-d", strtotime($attendance->clock_time));
                $att_record =  \App\Models\Att_reason_record::where("emp_id", $emp->id)
                    ->where("att_date", $date)
                    ->first();

                if(empty($att_record)){
                    if($attendance->clock_type == "clock_out"){
                        $date = date("Y-m-d", strtotime($date." -1 day"));
                        $att_record =  \App\Models\Att_reason_record::where("emp_id", $emp->id)
                            ->where("att_date", $date)
                            ->first();
                    } elseif($attendance->clock_type == "clock_in"){

                        $wg_user = $reg->pluck("workgroup", "emp_id");

                        $periode = \App\Models\Att_periode::where("company_id", $emp->company_id)
                            ->where(function($q) use($date){
                                $q->where("start_date", "<=", $date);
                                $q->where("end_date", ">=", $date);
                            })->get();

                        $schedule = \App\Models\Att_workgroup_schedule::whereIn("periode", $periode->pluck("id"))
                            ->where("workgroup", $reg->workgroup)
                            ->get();
                        $wg_schedule = [];
                        foreach($schedule as $item){
                            $wg_schedule = array_merge($wg_schedule ?? [], $item->detail);
                        }

                        $sch = collect($wg_schedule)->where("date", $date)->first();

                        $emp_correction = \App\Models\Att_schedule_correction::where("company_id", $emp->company_id)
                            ->where("date", $date)
                            ->first();

                        $shiftId = $sch['shift_id'] ?? 0;
                        if(!empty($emp_correction)){
                            $shiftId = $emp_correction->shift_id;
                        }

                        $sh = \App\Models\Att_shift::find($shiftId);
                        $dayCode = $sh->day_code ?? 0;

                        $att_record = new \App\Models\Att_reason_record();
                        $att_record->emp_id = $emp->id;
                        $att_record->att_date = $date;
                        $att_record->day_code = $dayCode;
                        $att_record->shift_id = $sh->id;
                        $att_record->created_by = $user->id;
                        $att_record->company_id = $user->company_id;
                        $att_record->save();

                    }
                }

                if(!empty($att_record)){
                    if($attendance->clock_type == "clock_in"){
                        $att_record->timin = $attendance->clock_time;
                    } elseif($attendance->clock_type == "break_out"){
                        $att_record->break_start = $attendance->clock_time;
                    } elseif($attendance->clock_type == "break_in"){
                        $att_record->break_end = $attendance->clock_time;
                    } elseif($attendance->clock_type == "clock_out"){
                        $att_record->timout = $attendance->clock_time;
                    }

                    $att_record->save();

                    $rtype = \App\Models\Att_reason_type::where("code", '00')
                        ->where(function($q) use($emp){
                            $q->where('company_id', $emp->company_id);
                            $q->orWhere('is_default', 1);
                        })
                        ->get();

                    $condition = \App\Models\Att_reason_condition::where('company_id', $emp->company_id)
                        ->whereIn("reason_type_id", $rtype->pluck("id"))
                        ->orderBy("process_sequence")
                        ->get();

                    $reason_active = \App\Models\Att_reason_name::where(function($q) use($emp) {
                        $q->where('company_id', $emp->company_id);
                        $q->orWhere('is_default', 1);
                    })
                        ->where('status', 1)->pluck("id")->toArray();

                    $reason_condition = [];

                    $reason_pengganti = [];

                    $con_reason = $condition->pluck("reason_name_id", "id");
                    $con_seq = $condition->pluck("process_sequence", "id");

                    $holidays = \App\Models\Att_holiday::where("company_id", $emp->company_id)
                        ->where("holiday_date", $date)
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

                        if($attendance->clock_type == "clock_out"){
                            if(in_array("time_out", $cond)){
                                $string .= $string != "" ? " && " : "";
                                $minute = null;
                                if(!empty($item->time_out)){
                                    $minute = '$schedule_out';
                                }
                                $string .= '$time_out '.$item->time_out_condition.' '.($minute ?? "''");
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
                    $att_record['updated_by'] = $user->id;
                    $att_record['updated_at'] = date("Y-m-d H:i:s");

                    if(isset($holidays[$date])){
                        $att_record['is_holiday'] = 1;
                        $att_record['holiday_id'] = $holidays[$date];
                    }

                    $att_record->save();
                }
            }
        }
    }

    function assignAtt(Request $request){
        $start_date = $request->s;
        $end_date = $request->e;
        $reg = \App\Models\Att_employee_registration::where("company_id", $request->c)
            ->get();

        $user = $reg->pluck("id", "emp_id");
        $wg_user = $reg->pluck("workgroup", "emp_id");

        $periode = \App\Models\Att_periode::get();

        $schedule = \App\Models\Att_workgroup_schedule::whereIn("periode", $periode->pluck("id"))
            ->get();
        $wg_schedule = [];
        foreach($schedule as $item){
            $wg_schedule[$item->workgroup] = array_merge($wg_schedule[$item->workgroup] ?? [], $item->detail);
        }

        $emp_correction = \App\Models\Att_schedule_correction::get();
        $emp_ov = [];
        foreach($emp_correction as $item){
            $emp_ov[$item->emp_id][$item->date] = $item->shift ?? [];
        }

        $shifts = \App\Models\Att_shift::get();
        $shift = [];
        foreach($shifts as $item){
            $shift[$item->id] = $item;
        }


        $rExist = \App\Models\Att_reason_record::whereBetween('att_date', [$start_date, $end_date])->get();

        $re = [];
        foreach($rExist as $item){
            $re[$item->emp_id][$item->att_date] = $item->id;
        }

        $size = 10;

        $page = ($request->p - 1) * $size;
        $attData = [];

        $date = $start_date;

        try {
            while($date <= $end_date){
                foreach($reg as $item){
    
                    $emp_id = $item->emp_id;
        
                    $wgId = $wg_user[$item->emp_id] ?? [];
        
                    if(!empty($wgId)){
        
                        $_sch = $wg_schedule[$wgId] ?? [];
                        if(!empty($_sch)){
                            $sch = collect($_sch)->where("date", $date)->first();
                            $shift_id = intval($sch['shift_id'] ?? null);
            
                            if(isset($emp_ov[$emp_id])){
                                if(isset($emp_ov[$emp_id][$date])){
                                    $_shift = $emp_ov[$emp_id][$date];
                                    $shift_id = $_shift['id'];
                                }
                            }
        
                            $rdata = $re[$emp_id][$date] ?? null;
        
                            if(!$sch['day_off'] && !$sch['holiday']){
                                if(empty($rdata)){
                                    $sh = $shift[$shift_id] ?? [];
                                    if(!empty($sh)){
                                        $dayCode = $sh->day_code;
                                        $userId = $user[$item->emp_id];
                                        $att_record = [];
                                        $att_record['emp_id'] = $item->emp_id;
                                        $att_record['att_date'] = $date;
                                        $att_record['day_code'] = $dayCode;
                                        $att_record['shift_id'] = $sh->id;
                                        $att_record['created_by'] = $userId;
                                        $att_record['company_id'] = $item->company_id;
                                        $attData[] = $att_record;
                                    }
                                }
                            }
                        }
                    }
                }
                $date = date("Y-m-d", strtotime("$date +1 day"));
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        \App\Models\Att_reason_record::insert($attData);
    }

    function attDaily(){
        $reg = \App\Models\Att_employee_registration::get();

        $user = $reg->pluck("id", "emp_id");
        $wg_user = $reg->pluck("workgroup", "emp_id");

        $date = date("Y-m-d");

        $periode = \App\Models\Att_periode::where(function($q) use($date){
                $q->where("start_date", "<=", $date);
                $q->where("end_date", ">=", $date);
            })->get();

        $schedule = \App\Models\Att_workgroup_schedule::whereIn("periode", $periode->pluck("id"))
            ->get();
        $wg_schedule = [];
        foreach($schedule as $item){
            $wg_schedule[$item->workgroup] = array_merge($wg_schedule[$item->workgroup] ?? [], $item->detail);
        }

        $emp_correction = \App\Models\Att_schedule_correction::get();
        $emp_ov = [];
        foreach($emp_correction as $item){
            $emp_ov[$item->emp_id][$item->date] = $item->shift ?? [];
        }

        $shifts = \App\Models\Att_shift::get();
        $shift = [];
        foreach($shifts as $item){
            $shift[$item->id] = $item;
        }

        foreach($reg as $item){

            $emp_id = $item->emp_id;

            $wg = $wg_user[$item->emp_id] ?? [];

            if(!empty($wg)){

                $sch = collect($wg)->where("date", $date)->first();
                $shift_id = intval($sch['shift_id'] ?? null);

                if(isset($emp_ov[$emp_id])){
                    if(isset($emp_ov[$emp_id][$date])){
                        $_shift = $emp_ov[$emp_id][$date];
                        $shift_id = $_shift['id'];
                    }
                }

                $sh = $shift[$shift_id] ?? [];
                if(!empty($sh)){
                    $dayCode = $sh->day_code;
                    if($dayCode == 1){
                        $userId = $user[$item->emp_id];
                        $att_record = new \App\Models\Att_reason_record();
                        $att_record->emp_id = $item->emp_id;
                        $att_record->att_date = $date;
                        $att_record->day_code = $dayCode;
                        $att_record->shift_id = $sh->id;
                        $att_record->created_by = $userId;
                        $att_record->company_id = $item->company_id;
                        $att_record->save();
                    }
                }
            }
        }
    }

    function attendance_history($id, Request $request){

        $bulan = [1=> "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $mnow = date("n", strtotime($request->date));
        $ynow = date("Y", strtotime($request->date));

        $mbefore = date("Y-m", strtotime($request->date." -1 month"));
        $mnext = date("Y-m", strtotime($request->date." +1 month"));

        $t = date("t", strtotime($request->date));

        $attendance = User_attendance::select("id", "clock_type", "created_at", "clock_time", "is_late")->where('user_id', $id)
            ->where("created_at", "like", "$request->date%")
            ->orderBy("created_at")
            ->get();

        $attends = [];

        foreach($attendance as $item){
            $m = date("Y-m-d", strtotime($item->created_at));
            $item->time = date("H:i", strtotime($item->created_at));
            $attends[$m][$item->clock_type][] = $item;
        }

        $days = [];
        for($i = 1; $i <= $t; $i++){
            $col = [];
            $d = date("Y-m-", strtotime($request->date)).sprintf("%02d", $i);
            $mi = date("n", strtotime($request->date));
            $col['date'] = $d;
            $col['label'] = date("d", strtotime($col['date']))." ".$bulan[$mi]." ".date("Y", strtotime($col['date']));
            $col['day'] = $i;
            $col['attendance'] = $attends[$col['date']] ?? [];
            $days[] = $col;
        }

        $monthNow = $bulan[$mnow]." ".$ynow;

        $resp = [
            "monthNow" => $monthNow,
            "date" => $request->date,
            "monthBefore" => $mbefore,
            "monthNext" => $mnext,
            'days' => $days,
            'monthlyAttendance' => $attendance->where("clock_type", "clock_in")->count(),
            'daySelected' => date("Y-m-d"),
            "current" => $days[date("j") - 1]
        ];

        return $this->sendResponse($resp, "Success");

        // $t = 3;
        // $n = date("j");
        // $days = [];
        // $hari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
        // $hariShort = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
        // for($i = $n - 2; $i <= $n; $i++){

        //     $d = $i;
        //     if($i <= 0){
        //         $dlast = $_m = date("t", strtotime("-1 month $request->date"));
        //         $d = $dlast - abs($i);
        //     }

        //     $_m = date("Y-m-".sprintf("%02d", $d));
        //     if($i > $n || $i <= 0){
        //         $_m = date("Y-m-", strtotime("-1 month $request->date")).sprintf("%02d", $d);
        //     }

        //     $w = date("w", strtotime($_m));
        //     $col = [];
        //     $col['tanggal'] = intval($d);
        //     $col['date'] = $_m;
        //     $col['label'] = $hariShort[$w];
        //     $days[] = $col;
        // }

        // $hari_ini = $hari[date("w")].", ".date("d")." ".$bulan[date("n")]." ".date("Y");

        // $absensi = User_attendance::where('user_id', $id)
        //     ->whereDate("created_at", date("Y-m-d"))
        //     ->orderBy("created_at")
        //     ->get();
        // $check_in = $absensi->where("clock_type", "clock_in")->count();
        // $check_out = $absensi->where("clock_type", "clock_out")->count();

        // $resp = [
        //     "days" => $days,
        //     "hari_ini" => $hari_ini,
        //     "check_in" => $check_in,
        //     "check_out" => $check_out,
        // ];

        // return $this->sendResponse($resp, "sukses");
    }

    function attendance_get_history($id, Request $request){
        $absensi = User_attendance::where('user_id', $id)
            ->whereDate("created_at", date("Y-m-d", strtotime($request->date)))
            ->orderBy("created_at")
            ->get();
        $clock_in = [];
        $clock_out = [];
        foreach($absensi as $item){
            $dLate = 0;
            if(in_array($item->clock_type, ["clock_in", "clock_out"])){
                $_late = date("Y-m-d 07:30:00");
                if($item->clock_type == "clock_in"){
                    $_late = date("Y-m-d", strtotime($item->created_at))." 07:30:00";
                } elseif($item->clock_type == "clock_out"){
                    $_late = date("Y-m-d", strtotime($item->created_at))." 17:30:00";
                }
                $d1 = date_create($item->created_at);
                $d2 = date_create($_late);
                $diff = date_diff($d1, $d2);
                $late = $diff->format("%r%h:%r%i");

                $expLate = explode(":", $late);
                if($expLate[1] < 0){
                    $dLate = $expLate[1];
                    if($expLate[0] < 0){
                        $dLate += $expLate[0] * 60;
                    }
                }
            }
            $item->dateFormat = date("d-m-Y", strtotime($item->created_at));
            $item->timeFormat = date("H:i:s", strtotime($item->created_at));
            $item->terlambat = abs($dLate);
        }

        $clock_in = $absensi->where("clock_type", "clock_in")->take(1)->toArray();
        $clock_out = array_values($absensi->where("clock_type", "clock_out")->sortByDesc('created_at')->take(1)->toArray());

        $resp = [
            "clock_in" => $clock_in,
            "clock_out" => $clock_out,
        ];

        return $this->sendResponse($resp, "sukses");
    }

    function get_locations(Request $request){
        $user = User::where("api_token", $request->token)->first();

        $locations = [];
        if(!empty($user)){
            $emp_id = $user->emp_id;
            $loc = Kjk_employee_location::where("emp_id", $emp_id)
                ->get();
            if($loc->count() > 0){
                $locations = Asset_wh::select(['id', 'name as text', "name", "latitude", "longitude", "longitude2 as radius"])->whereIn("id", $loc->pluck("wh_id"))->get()->toArray();
            }
        }

        return $this->sendResponse($locations, "success");
    }

    function check_radius(Request $request){

        $longitude2 = $request->longitude;
        $latitude2 = $request->latitude;

        $eligible = false;

        $user = User::find($request->user_id);

        $wh = Asset_wh::find($request->loc_id);

        $msg = "Maaf anda tidak dapat melakukan absen di $wh->name karena anda belum terdaftar di lokasi tersebut";

        $emp_id = Hrd_employee::find($user->emp_id ?? null);
        $d = 0;
        if(!empty($emp_id)){
            $_loc = Kjk_employee_location::where("wh_id", $wh->id)
                ->where("emp_id", $emp_id->id)
                ->first();
            if(!empty($_loc)){
                $msg = "Maaf anda tidak dapat melakukan absen di $wh->name karena anda tidak berada di dalam radius lokasi";

                $latitude1 = $wh->latitude;
                $longitude1 = $wh->longitude;
                $radius = $wh->longitude2;

                $earth_radius = 6371000;

                $dLat = deg2rad($latitude2 - $latitude1);
                $dLon = deg2rad($longitude2 - $longitude1);

                $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
                $c = 2 * asin(sqrt($a));
                $d = $earth_radius * $c;

                if($d <= $radius){
                    $msg = "success";
                    $eligible = true;
                }
            }
        }

        $res = [
            "eligible" => $eligible,
            "distance" => $d
        ];

        return $this->sendResponse($res, $msg);
    }

    function get_map_coordinates(Request $request){
        $user = User::where("api_token", $request->token)->first();
        $f = $request->f;

        if(empty($user)){
            return $this->sendError("incorrect credential");
        } else {
            $attendance = User_attendance::where("user_id", $user->id)
                ->where(function($q) use($f){
                    if(!empty($f)){
                        if($f == 1){
                            $d = date("Y-m-d", strtotime("monday this week"));
                        } elseif($f == 2){
                            $d = date("Y-m-")."01";
                        } elseif($f == 3){
                            $d = date("Y")."-01-01";
                        }

                        $q->where("clock_time", ">=", $d);
                    }
                })
                ->where('clock_type', "clock_in")
                ->orderBy("created_at", "desc")
                ->get();

            $coordinates = [];
            foreach($attendance as $item){
                $address = trim(ltrim($item->address ?? "-", ","));
                $coordinates[] = [
                    "latitude" => $item->latitude,
                    "longitude" => $item->longitude,
                    'type' => $item->location_type,
                    'address' => $address
                ];
            }

            return $this->sendResponse($coordinates ,"success");
        }
    }

    function reminder_attendance(Request $request){

        $state = $request->state;

        $dnow = date("Y-m-d");

        $users_attendance = User_attendance::where("created_at", "like", "$dnow%")
            ->where("clock_type", $state)
            ->pluck("user_id");

        $user = User::whereNotIn("id", $users_attendance)
            ->get();

        $holiday = \App\Models\Att_holiday::where("holiday_date", date("Y-m-d"))->get();
        $holidayUser = [];
        foreach($holiday as $item){
            $holidayUser[$item->company_id][] = $item->holiday_date;
        }

        $dev_ids = [];
        $_userComp_id = [];
        foreach($user as $item){
            $dev = json_decode($item->device_ids ?? "[]", true);
            if(is_array($dev)){
                $_holiday = $holidayUser[$item->company_id] ?? [];
                if(count($_holiday) == 0){
                    $dev_ids = array_merge($dev_ids, $dev);
                    $_col= [];
                    $_col['user_id'] = $item->name;
                    $_col['company_id'] = $item->company_id;
                    $_userComp_id[] = $_col;
                }
            }
        }

        $msg = "";

        $hour_now = intval(date("H"));
        $minute_now = intval(date("i"));

        if($state == "clock_in"){
            if($request->a == 1){
                $msg = "Pengingat absen masuk 5 menit sebelum batas waktu 08:30 WIB";
            } else {
                $msg = "Waktu menunjukan pukul $hour_now lewat $minute_now WIB. Harap segera melakukan absen masuk";
            }
        } else {
            $msg = "Waktu menunjukan pukul $hour_now lewat $minute_now WIB. Jika anda sudah pulang kerja, jangan lupa untuk melakukan absensi";
        }

        $_data = [
            "title" => "Absen",
            "body" => $msg,
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
        ];

        $notif = Notification::sendFCM($dev_ids, $_data);

        return $this->sendResponse($notif, "");

        // $time_start = [
        //     "hour" => 8,
        //     "minute" => 30
        // ];
        // $time_off = [
        //     "hour" => 7,
        //     "minute" => 0
        // ];

        // if($time_start['hour'] - $hour_now == 1){
        //     if($time_start['minute'] - $minute_now == 5){

        //     }
        // }
    }
}
