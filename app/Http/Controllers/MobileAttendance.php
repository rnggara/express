<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee;
use App\Models\Hrd_employee_type;
use App\Models\User;
use App\Models\User_attendance;
use App\Models\ConfigCompany;
use App\Models\Preference_config;
use App\Models\Asset_wh;
use App\Models\Kjk_employee_location;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AppExport;
use Yajra\DataTables\Facades\DataTables;

class MobileAttendance extends Controller
{
    function index(Request $request){
        $dnow = $request->d ?? date("Y-m-d");

        $compid = Session::get('company_id');

        $comp_ids = [];
        $comp = ConfigCompany::find(Session::get('company_id'));
        if (empty($comp->id_parent)) {
            $childCompany = ConfigCompany::select("id")
                ->where('id_parent', $comp->id)
                ->get();
            foreach($childCompany as $ids){
                $comp_ids[] = $ids->id;
            }
        } else {
            $comp_ids[] = $comp->id_parent;
        }

        $comp_ids[] = Session::get('company_id');

        $eType = Hrd_employee_type::whereIn('company_id', $comp_ids)
            ->where('company_exclude', 'not like', '%"'.$compid.'"%')
            ->orWhereNull("company_exclude")
            ->get();
        $t = $request->t ?? null;

        $emp = Hrd_employee::where("company_id", Session::get("company_id"))
            ->where(function($q) use($t){
                if(!empty($t)){
                    $q->where("emp_type", $t);
                }
            })
            ->whereNull("expel")
            ->whereNull("finalize_expel")
            ->orderBy("emp_name")
            ->get();

        $user = User::whereIn("emp_id", $emp->pluck('id'))
            ->get();

        $data = (object)[
            "periode_from" => $request->periode_from ?? date("d/m/Y", strtotime($dnow)),
            "periode_to" => $request->periode_to ?? date("d/m/Y", strtotime($dnow)),
            "columns" => $request->columns ?? ["name", "email"],
            "clockin_from" => $request->clockin_from ?? "",
            "clockin_to" => $request->clockin_to ?? "",
            "breakout_from" => $request->breakout_from ?? "",
            "breakout_to" => $request->breakout_to ?? "",
            "breakin_from" => $request->breakin_from ?? "",
            "breakin_to" => $request->breakin_to ?? "",
            "clockout_from" => $request->clockout_from ?? "",
            "clockout_to" => $request->clockout_to ?? "",
            "locations" => $request->locations ?? null,
            "departements" => $request->departements ?? null,

        ];

        $dataAttendance = $this->export($data);

        $clock = User_attendance::whereIn("user_id", $user->pluck("id"))
            ->where("clock_time", "like", "$dnow%");

        $clock_in = User_attendance::whereIn("user_id", $user->pluck("id"))
            ->whereDate("clock_time", $dnow)->where("clock_type", "clock_in")->orderBy("clock_time")->get();
        $clock_out = User_attendance::whereIn("user_id", $user->pluck("id"))
            ->whereDate("clock_time", $dnow)->where("clock_type", "clock_out")->orderBy("clock_time", "desc")->get();
        $break_in = User_attendance::whereIn("user_id", $user->pluck("id"))
            ->whereDate("clock_time", $dnow)->where("clock_type", "break_in")->orderBy("clock_time")->get();
        $break_out = User_attendance::whereIn("user_id", $user->pluck("id"))
            ->whereDate("clock_time", $dnow)->where("clock_type", "break_out")->orderBy("clock_time", "desc")->get();

        $userAssign = User::whereNull('emp_id')
            ->where("company_id", Session::get("company_id"))
            ->orderBy("name")
            ->get();

        $locs = Asset_wh::whereNotNull("longitude")
            ->whereNotNull("latitude")
            ->get();

        foreach($clock_in as $item){
            $item->location = "Anywhere";
            if($item->location_type == 1){
                $item->location = "Office";
            } elseif($item->location_type == 2){
                $item->location = "Customer";
            } elseif($item->location_type == 3){
                $item->location = "Home";
            } elseif($item->location_type == "O"){
                foreach($locs as $val){
                    $long = false;
                    if(($item->longitude >= $val->longitude && $item->longitude <= $val->longitude2) || ($item->longitude <= $val->longitude && $item->longitude >= $val->longitude2)){
                        $long = true;
                    }

                    $lat = false;
                    if(($item->latitude >= $val->latitude && $item->latitude <= $val->latitude2) || ($item->latitude <= $val->latitude && $item->latitude >= $val->latitude2)){
                        $lat = true;
                    }

                    if($long && $lat){
                        $item->location = $val->name;
                        $item->btn_class = $val->btn_class;
                    }
                }
            }
        }

        foreach($clock_out as $item){
            $item->location = "Anywhere";
            if($item->location_type == 1){
                $item->location = "Office";
            } elseif($item->location_type == 2){
                $item->location = "Customer";
            } elseif($item->location_type == 3){
                $item->location = "Home";
            } elseif($item->location_type == "O"){
                foreach($locs as $val){
                    $long = false;
                    if(($item->longitude >= $val->longitude && $item->longitude <= $val->longitude2) || ($item->longitude <= $val->longitude && $item->longitude >= $val->longitude2)){
                        $long = true;
                    }

                    $lat = false;
                    if(($item->latitude >= $val->latitude && $item->latitude <= $val->latitude2) || ($item->latitude <= $val->latitude && $item->latitude >= $val->latitude2)){
                        $lat = true;
                    }

                    if($long && $lat){
                        $item->location = $val->name;
                        $item->btn_class = $val->btn_class;
                    }
                }
            }
        }

        $divisions = Division::where('name', "!=", "admin")->pluck("name", "id");

        // $dataAtt = $this->export($request);

        return view("employee.attendance.index", compact("dataAttendance", "data", "emp", "divisions", "user", "t", "eType", "clock_in", "clock_out", "dnow", "userAssign", 'break_out', "break_in", "locs"));
    }

    function report_personal(Request $request){
        $year = date("Y");
        $yearmin5 = $year - 5;
        $yearplus5 = $year + 5;

        $emp_id = Hrd_employee::whereNull("expire")->where("company_id", Session::get("company_id"))->get();

        $users = User::hris()->where('company_id', Session::get("company_id"))
            ->whereIn("emp_id", $emp_id->pluck("id"))
            ->whereNotNull('emp_id')
            ->orderBy('name')->get();

        if($request->a == "table"){
            $user = User::find($request->user);

            $period = "$request->year-".sprintf("%02d", $request->month);

            $attendance = User_attendance::where("user_id", $user->id)
                ->where("clock_time", 'like', "$period%")
                ->orderBy("clock_time")
                ->get();

            $pref = Preference_config::where("id_company", Session::get("company_id"))->first();
            $cOut = $pref->clock_out ?? "";

            $dataHadir = [];

            $hadir = 0;
            $telat = 0;
            $dataTelat = [];
            $tTelat = 0;

            foreach($attendance as $item){
                $item->telat = false;
                $item->total_telat = 0;
                $dt = date("Y-m-d", strtotime($item->clock_time));
                if($item->clock_type == "clock_in"){
                    $hadir++;
                    $dLate = 0;

                    $_late = date("Y-m-d", strtotime($item->clock_time))." ".($pref->clock_in ?? "08:30:00");
                    $d1 = date_create($item->clock_time);
                    $d2 = date_create($_late);
                    $diff = date_diff($d2, $d1);
                    $late = $diff->format("%r%h:%r%i");

                    $expLate = explode(":", $late);
                    $dLate = 0;
                    if($expLate[0] != 0){
                        $dLate += $expLate[0] * 60;
                        $dLate += $expLate[1];
                    } else {
                        if($expLate[1] > 15){
                            $dLate = $expLate[1];
                        }
                    }

                    if($dLate > 0){
                        $item->telat = true;
                        $item->total_telat += $dLate;
                        $dataTelat[$dt] = $item;
                        $telat++;
                        $tTelat += $dLate;
                    }
                }
                if(!isset($dataHadir[$dt][$item->clock_type])) $dataHadir[$dt][$item->clock_type] = $item;
            }

            $tHadir = 0;

            foreach($dataHadir as $dt => $dh){
                if(isset($dh['clock_in']) && $dt != date("Y-m-d")){
                    $dIn = $dh['clock_in']->clock_time;
                    $dOut = $dh['clock_out']->clock_time ?? null;
                    if(!empty($dIn) && !empty($dOut)){
                        $d1 = date_create($dIn);
                        $d2 = date_create($dOut);
                        $diff = date_diff($d1, $d2);
                        $late = $diff->format("%r%h:%r%i");
                        $_h = $diff->format("%h");
                        $_m = $diff->format("%i");
                        $tHadir += ($_h * 60) + $_m;
                    }
                }
            }

            $t = date("t", strtotime($period));
            $hari = [1=> "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];

            $weekend = 0;

            $mangkir = 0;
            $_t = $period == date("Y-m") ? date("j") : $t;

            for($i = 1; $i <= $_t; $i++){
                $dt = "$period-".sprintf("%02d", $i);
                if(date('N', strtotime($dt)) >= 6){
                    $weekend++;
                }
            }

            $hadir = count($dataHadir);
            $telat = count($dataTelat);

            $mangkir = ($_t - $weekend) - $attendance->where("clock_type", "clock_in")->count();

            return view("employee.attendance._personal_att", compact("period", "user", "dataHadir", "t", "hari", "hadir", 'telat', 'tHadir', 'tTelat', "mangkir"));

            // return json_encode([
            //     "view" => $view
            // ]);
        }

        return view("employee.attendance.report_personal", compact('year', 'yearmin5', 'yearplus5', "users"));
    }

    function report_index(Request $request){

        $year = date("Y");
        $yearmin5 = $year - 5;
        $yearplus5 = $year + 5;

        if($request->a == "table"){
            $user = User::hris()->where("company_id", Session::get("company_id"))
                ->orderBy("name")
                ->get();

            $period = "$request->year-".sprintf("%02d", $request->month);

            $attendance = User_attendance::whereIn("user_id", $user->pluck("id"))
                ->where("clock_time", 'like', "$period%")
                ->get();

            $weekend = 0;

            $periodNow = date("Y-m");
            $today = date("j");

            $t = date("t", strtotime($period));
            if($periodNow == $period){
                $t = $today;
            }

            for($i = 1; $i <= $t; $i++){
                $dt = "$period-".sprintf("%02d", $i);
                if(date('N', strtotime($dt)) >= 6){
                    $weekend++;
                }
            }

            $dataAtt = [];
            $dataHadir = [];

            $pref = Preference_config::where("id_company", Session::get("company_id"))->first();
            $cOut = $pref->clock_out ?? null;

            foreach($attendance as $item){

                $item->telat = false;
                $item->total_telat = 0;
                if($item->clock_type == "clock_in"){
                    $dLate = 0;

                    $_late = date("Y-m-d", strtotime($item->clock_time))." ".($pref->clock_in ?? "08:30:00");
                    $d1 = date_create($item->clock_time);
                    $d2 = date_create($_late);
                    $diff = date_diff($d2, $d1);
                    $late = $diff->format("%r%h:%r%i");

                    $expLate = explode(":", $late);
                    if($expLate[0] != 0){
                        $dLate += $expLate[0] * 60;
                        $dLate += $expLate[1];
                    } else {
                        if($expLate[1] > 15){
                            $dLate = $expLate[1];
                        }
                    }

                    if($dLate > 0){
                        $item->telat = true;
                        $item->total_telat = $dLate;
                    }
                }
                $dataAtt[$item->user_id][] = $item;

                $dt = date("Y-m-d", strtotime($item->clock_time));
                if(!isset($dataHadir[$item->user_id][$dt][$item->clock_type])) $dataHadir[$item->user_id][$dt][$item->clock_type] = $item->clock_time;
            }

            $col = [];
            $num = 1;

            foreach($user as $item){
                if(isset($dataAtt[$item->id])){

                    $el = collect($dataAtt[$item->id]);

                    $_dataHadir = $dataHadir[$item->id] ?? [];

                    $totalHadir = "00:00";
                    $tHadir = 0;

                    $cHadir = [];

                    if(!empty($_dataHadir)){
                        foreach($_dataHadir as $dt => $dh){
                            if(isset($dh['clock_in']) && $dt <= date("Y-m-d")){
                                $dIn = $dh['clock_in'];
                                $dOut = $dh['clock_out'] ?? null;
                                $cHadir[$dt] = $dh;
                                if(!empty($dIn) && !empty($dOut)){
                                    $d1 = date_create($dIn);
                                    $d2 = date_create($dOut);
                                    $diff = date_diff($d1, $d2);
                                    $late = $diff->format("%r%h:%r%i");
                                    $_h = $diff->format("%h");
                                    $_m = $diff->format("%i");
                                    $tHadir += ($_h * 60) + $_m;
                                }
                            }
                        }
                    }

                    $hIn = floor($tHadir / 60);
                    $mIn = $tHadir - ($hIn * 60);
                    $totalHadir = sprintf("%02d", $hIn).":".sprintf("%02d", $mIn);

                    $telatMinutes = $el->where('telat', true)->sum("total_telat");
                    $h = floor($telatMinutes / 60);
                    $m = $telatMinutes - ($h * 60);

                    $totalTelat = sprintf("%02d", $h).":".sprintf("%02d", $m);
                    $hadir = $el->where("clock_type", "clock_in")->count();
                    $hadir = count($cHadir);

                    $mangkir = ($t - $weekend) - $hadir;

                    $remarks = "";
                    if($hadir > 1 && $tHadir == 0){
                        $remarks = "Absen masuk tapi tidak absen keluar";
                    }

                    $row = [];
                    $row['no'] = $num++;
                    $row['nik'] = $item->emp->emp_id ?? "-";
                    $row['nama'] = $item->name;
                    $row['departement'] = $item->uacdepartement->name ?? "-";
                    $row['hadir'] = $hadir;
                    $row['total_hadir'] = $totalHadir;
                    $row['telat'] = $el->where('telat', true)->count();
                    $row['total_telat'] = $totalTelat;
                    $row['mangkir'] = $mangkir < 0 ? 0 :$mangkir;
                    $row['remark'] = $remarks;
                    $col[] = $row;
                }
            }

            return DataTables::collection($col)
                ->rawColumns(['nama'])
                ->with("periode", date("F Y", strtotime($period)))
                ->make(true);
        }

        return view("employee.attendance.report", compact("year", 'yearmin5', 'yearplus5'));
    }

    function export($request){
        // dd($request->all());
        $dfrom = explode("/", $request->periode_from);
        krsort($dfrom);
        $dfrom = implode("-", $dfrom);

        $dto = explode("/", $request->periode_to);
        krsort($dto);
        $dto = implode("-", $dto);

        $columns = $request->columns;

        $timeRange["clock_in"]['from'] = $request->clockin_from;
        $timeRange["clock_in"]['to'] = $request->clockin_to;
        $timeRange["break_out"]['from'] = $request->breakout_from;
        $timeRange["break_out"]['to'] = $request->breakout_to;
        $timeRange["break_in"]['from'] = $request->breakin_from;
        $timeRange["break_in"]['to'] = $request->breakin_to;
        $timeRange["clock_out"]['from'] = $request->clockout_from;
        $timeRange["clock_out"]['to'] = $request->clockout_to;

        $locations = [$request->locations];

        $office = [];
        $loc_type = [];
        foreach($locations as $item){
            if($item != null){
                $_txt = explode("-", $item);
                if($_txt[0] == "off"){
                    $office[] = end($_txt);
                } else {
                    $loc_type[] = end($_txt);
                }
            }
        }

        $dRange = [];
        $dnow = $dfrom;
        while($dnow <= $dto){
            $dRange[$dnow] = [];
            $dnow = date("Y-m-d", strtotime("+1 day $dnow"));
        }

        $dept = $request->departements;

        $empDiv = [];
        $emp = Hrd_employee::where('company_id', Session::get("company_id"))
            ->where(function($q) use($dept){
                if(!empty($dept)){
                    $q->where("division", $dept);
                }
            })
            ->get();
        foreach($emp as $item){
            $empDiv[$item->id] = $item->div->name ?? "-";
        }

        $wh = Asset_wh::whereIn("id", $office);

        $emp_loc = Kjk_employee_location::whereIn("wh_id", $wh->pluck("id"))
            ->whereIn("emp_id", $emp->pluck('id'))
            ->pluck("emp_id");

        $users = User::where(function($q) use($emp, $emp_loc){
            if(count($emp_loc) > 0){
                $q->whereIn("emp_id", $emp_loc);
            } else {
                $q->whereIn("emp_id", $emp->pluck("id"));
            }
        })->where('company_id', Session::get("company_id"))->where("role_access", "like", '%"hris"%')->get();

        $user_loc = $users->pluck("id");
        $userData = [];

        $row = [];

        foreach($users as  $item){
            $col = [];
            foreach($columns as $colName){
                $col[$colName] = $item[$colName];
            }
            $col['division'] = $empDiv[$item->emp_id] ?? "-";
            $userData[$item->id] = $col;
            $row[$item->id] = $dRange;
        }

        $attendance = User_attendance::where('company_id', Session::get("company_id"))->where(function($q) use($dfrom, $dto){
            $q->whereDate("clock_time", ">=",$dfrom);
            $q->whereDate("clock_time", "<=",$dto);
        })->where(function($q) use($office, $user_loc){
            if(count($office) > 0){
                $q->where("location_type", 1);
                $q->where("loc_id", $office[0]);
                // $q->whereIn("user_id", $user_loc);
            }
        })->where(function($q) use($loc_type){
            if(count($loc_type) > 0){
                $q->whereIn("location_type", $loc_type);
            }
        })->where(function($q) use($timeRange){
            foreach($timeRange as $k => $item){
                if(!empty($item['from']) && !empty($item['to'])){
                    $q->OrWhereRaw("(clock_type = '$k' and CAST(clock_time as TIME) between '".$item['from']."' and '".$item['to']."')");
                }
            }
        })->orderBy("clock_time")->get();

        foreach($attendance as $item){
            if(isset($userData[$item->user_id])){
                $d = date("Y-m-d", strtotime($item->clock_time));
                $el = $userData[$item->user_id];
                $atp = $item->clock_type;
                $col = [];
                foreach($columns as $colName){
                    $col[$colName] = $el[$colName];
                }
                $attTime = date("H:i", strtotime($item->clock_time));
                $col['id'] = $item->id;
                $col['type'] = $item->clock_type;
                $col['lokasi'] = $item->location_type;
                $col['clock_time'] = $item->clock_time;
                $col['btn_class'] = $item->btn_class;
                $col['address'] = $item->address;
                $col['time'] = date("H:i", strtotime($item->clock_time));
                $col['created_at'] = $item->created_at;
                $col['division'] = $el['division'];
                $row[$item->user_id][$d][$atp][] = (object)$col;
            }
        }

        return $row;

        $attCols = ["clock_in", "break_out", "break_out", "clock_out"];

        $view = view("employee.attendance.export", compact("columns", "row", "attCols"));

        $title = "Absensi_$dfrom-$dto";
        $wh_first = $wh->first();
        if(!empty($wh_first)){
            $title .= "_$wh_first->name";
        }

        return Excel::download(new AppExport($view, $title), "$title.xlsx");
    }

    function view_attendance($type, $id){
        $attendance = User_attendance::find($id);

        return view("employee.attendance.view", compact("attendance", "type"));
    }

    function assign_user(Request $request){
        $user = User::find($request->user);
        $user->emp_id = $request->id;
        $user->save();

        return redirect()->back();
    }

    function map_page($id, Request $request){
        $emp = Hrd_employee::find($id);

        if($request->t){
            if($request->t == "map"){

                $user = User::where("emp_id", $emp->id)->first();

                $attendance = User_attendance::where("user_id", $user->id ?? null)
                    ->where('clock_type', "clock_in")
                    ->orderBy("created_at", "desc")
                    ->get();

                $coordinates = [];
                foreach($attendance as $item){
                    $coordinates[] = [
                        "loc" => [$item->latitude, $item->longitude],
                        "title" => $item->id,
                    ];
                }

                $response = [
                    "success" => true,
                    "data" => $coordinates
                ];

                return json_encode($response);

                // $project = Asset_wh::whereNotNull('longitude')
                //     ->whereNotNull('latitude')
                //     ->whereIn('office', ['1', '2'])
                //     ->whereIn('company_id', $request->comps)
                //     ->get();
                // $company_bg = ConfigCompany::all()->pluck('bgcolor', 'id');

                // $loc = [];
                // foreach ($project as $key => $value) {
                //     $row = [];
                //     $row['loc'] = [];
                //     $row['title'] = "<a href='#' data-toggle='modal' data-target='#modalCrewLoc' onclick='listCrew(\"office\",".$value->id.")'>".$value->name."</a>";
                //     $row['bg'] = $company_bg[$value->company_id];
                //     $row['loc'][] = $value->longitude;
                //     $row['loc'][] = $value->latitude;
                //     $loc[] = $row;
                // }

                // if ($loc){
                //     $success = true;
                //     $message = "Success";
                //     $data = $loc;
                // } else {
                //     $success = false;
                //     $message = "Failed";
                //     $data = "No data found";
                // }

                // $response = [
                //     "success" => $success,
                //     "messages" => $message,
                //     "data" => $data
                // ];

                // return json_encode($response);
            }
        }

        return view("employee.attendance.map", compact("emp"));
    }

    function workHourIndex(){

        $preferences = Preference_config::where("id_company", Session::get("company_id"))->first();

        return view("employee.attendance.wh", compact("preferences"));
    }

    function workHourSave(Request $request){
        $pref = Preference_config::where('id_company',$request['id_company'])->first();
        if ($pref === null){
            $pref = new Preference_config();
            $pref->id_company = $request['id_company'];
        }

        $pref->clock_in = $request['clock_in'];
        $pref->clock_out = $request['clock_out'];
        $pref->break_out = $request['break_out'];
        $pref->break_in = $request['break_in'];
        $pref->hide_break_session = $request->hide_break ?? null;
        $pref->save();

        return redirect()->back();
    }
}
