<?php

namespace App\Http\Controllers;

use App\Models\Att_collect_datum;
use App\Models\Att_employee_registration;
use App\Models\Att_machine_name;
use App\Models\Att_periode;
use App\Models\Att_workgroup_schedule;
use App\Models\Att_schedule_correction;
use App\Models\Att_shift;
use App\Models\Hrd_employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CollectDataImport;
use App\Models\Att_overtime_record;
use App\Models\Att_reason_condition;
use App\Models\Att_reason_type;

class KjkAttCollectData extends Controller
{

    private $dir;

    public function __construct() {
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
        $this->$dir = str_replace("\\", "/", $dir);
    }

    function index(Request $request){
        $machines = Att_machine_name::where('company_id', Session::get('company_id'))->get();
        if($request->a == "view"){
            return $this->view_data($request);
        }
        $history = Att_collect_datum::where("company_id", Session::get('company_id'))
            ->orderBy("start_date", "desc")
            ->get();

        $last = Att_collect_datum::where("company_id", Session::get('company_id'))
            ->orderBy("end_date", "desc")
            ->first();
        $last_collect_data = null;
        $last_daily_process = null;
        if(!empty($last)){
            $last_collect_data = date("d F Y", strtotime($last->created_at));
            $last_daily_process = date("d F Y", strtotime($last->end_date));
        }

        $user_name = User::hris()->whereIn("id", $history->pluck('created_by'))->pluck("name", "id");
        return view("_attendance.collect_data.index", compact("machines", "history", "user_name", "last_collect_data", "last_daily_process", 'last'));
    }

    function history($id){
        $history = Att_collect_datum::find($id);

        $machine = Att_machine_name::find($history->machine_id);

        $user_name = User::find($history->created_by);

        $total_data = 0;
        $detail = $history->att_data;
        foreach ($detail as $key => $value) {
            foreach($value as $_val){
                $valid = collect($_val)->where("valid", 1)->count();
                $total_data += $valid >= 1 ? 1 : 0;
            }
        }

        $ext = $machine->program->program_type ?? "txt";

        if($ext == "txt" || $ext == "csv"){
            $content = file_get_contents(__DIR__."/../../../../public_html/assets/$history->file_address");
            $data = explode("\r\n", $content);
            $row = [];
            $cell['emp_id'] = $machine->id_card;
            $cell['date'] = $machine->date_format;
            $cell['time'] =  $machine->time_format;
            $cell['absensi'] = $machine->absensi_code;
            $timeZone = null;
        } else {
            $d = date("Ymd");
            $content = Excel::toCollection(new CollectDataImport, __DIR__."/../../../../public_html/assets/$history->file_address");
            $data = $content[0];
            $n = 97;
            $chr = ord("b");
            $row = [];
            $timeZone = Config::get("app.timezone");
            $cell['emp_id'] = ord(strtolower($machine->id_card['column'])) - $n;
            $cell['date'] = ord(strtolower($machine->date_format['column'])) - $n;
            $cell['time'] = ord(strtolower($machine->time_format['column'])) - $n;
            $cell['absensi'] = ord(strtolower($machine->absensi_code['column'])) - $n;
        }

        $registrations = Att_employee_registration::where("company_id", Session::get("company_id"))->get();
        $reg = [];
        foreach($registrations as $item){
            $tp = $machine->id_card['type'] == "emp_id" ? $item->emp_id : $item->id_card;
            $reg[$tp] = $item;
        }

        return view("_attendance.collect_data.history", compact("history", "machine", "user_name", "total_data", "detail", 'data', 'cell', 'reg', 'timeZone'));
    }

    function view_data(Request $request){
        $machine = Att_machine_name::find($request->machine);

        $collect = Att_collect_datum::where("machine_id", $machine->id ?? null)
            ->where(function($q) use($request){
                $q->where("start_date", "<=", $request->start_date);
                $q->where("end_date", ">=", $request->start_date);
            })
            ->first();

        $registrations = Att_employee_registration::where("company_id", Session::get("company_id"))->get();
        $reg = [];
        foreach($registrations as $item){
            $tp = ($machine->id_card['type'] ?? null) == "emp_id" ? $item->emp_id : $item->id_card;
            $reg[$tp] = $item;
        }

        // $att = $collect->att_data;

        $periode = Att_periode::where("company_id", Session::get("company_id"))
            ->where(function($q) use($request){
                $q->where("start_date", "<=", $request->start_date);
                $q->where("end_date", ">=", $request->start_date);
            })->first();


        $perId = $periode->id ?? null;

        $schedule = Att_workgroup_schedule::where("periode", $perId)
            ->get();

        $wg_schedule = $schedule->pluck("detail", "workgroup");

        $emp_correction = Att_schedule_correction::where("company_id", Session::get('company_id'))
            ->get();
        $emp_ov = [];
        foreach($emp_correction as $item){
            $emp_ov[$item->emp_id][$item->date] = $item->shift ?? [];
        }

        $reasons = \App\Models\Att_reason_record::whereIn("emp_id", $registrations->pluck("emp_id"))
            ->where(function($q) use($request){
                if(empty($request->end_date)){
                    $q->where("att_date", $request->start_date);
                } else {
                    $q->whereBetween("att_date", [$request->start_date, $request->end_date]);
                }
            })
            ->orderBy('att_date')
            ->get();
        $reason_att = [];
        foreach($reasons as $item){
            $reason_att[$item->emp_id][$item->att_date] = $item;
        }

        $holidays = \App\Models\Att_holiday::where('company_id', Session::get("company_id"))->pluck("name", "id");

        $r = \App\Models\Att_reason_name::get();

        $rname = \App\Models\Att_reason_name::pluck("reason_name", "id");
        $rcolor = \App\Models\Att_reason_name::pluck("color", "id");

        if($reasons->count()> 0){
            $row = [];

            foreach($registrations as $item){
                if(isset($reason_att[$item->emp_id])){
                    foreach($reason_att[$item->emp_id] as $date => $data){
                        $col = [];
                        $rcon = $data->reason_condition ?? [];
                        $rs = $data->reasons ?? [];
                        $hadir = '<span class="badge badge-outline badge-danger">Mangkir</span>';
                        if($data->timin != "00:00:00" && $data->timout != "00:00:00"){
                            $hadir = "<div class='d-flex align-items-center'>";
                            foreach($rs as $rp){
                                $_rname = $rname[$rp['id']] ?? null;
                                if(!empty($_rname)){
                                    $cl = $rcolor[$rp['id']];
                                    $hadir .= '<span class="me-2 badge badge-outline text-white" style="background-color: '.$cl.'">'.$_rname.'</span>';
                                }
                            }
                            $hadir .= "</div>";
                        }

                        if($data->is_holiday == 1){
                            $_holiday = $holidays[$data->holiday_id] ?? "Holiday";
                            $hadir = '<span class="badge badge-outline badge-secondary">'.$_holiday.'</span>';
                        }

                        $col['emp_name'] = $item->emp->emp_name;
                        $col['emp_id'] = $item->emp->emp_id;
                        $col['card_id'] = $item->id_card;
                        $col['date'] = $data->att_date;
                        $col['time_in'] = $data->timin;
                        $col['time_out'] = $data->timout;
                        $col['remarks'] = $hadir;
                        // $col['class'] = $col['remarks'] == "" ? 'secondary' : (($col['remarks'] == "Hadir" || $col['remarks'] == "Early In") ? "success" : ($col['remarks'] == "Mangkir" ? "danger" : "warning"));
                        $row[] = $col;
                    }
                }
            }

            $start_date = $request->start_date;
            $end_date = $request->end_date;

            $view = view("_attendance.collect_data._table", compact("row", 'collect', 'start_date', 'end_date'));
            return json_encode([
                "view" => $view->render(),
                "success" => true,
                "reproses" => $request->reproses == 1 ? true : false,
                'res' => $reason_att
            ]);
        } else {
            return json_encode([
                "success" => false,
            ]);
        }
    }

    function process(Request $request){
        $machine = Att_machine_name::find($request->machine_name);
        $ext = $machine->program->program_type ?? "txt";
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $file = $request->file("file");
        if(stripos($ext, $file->extension()) === -1){
            return redirect()->back()->withErrors([
                "file" => "Invalid file extensions"
            ])->withInput($request->all());
        }

        $oldName = $file->getClientOriginalName();

        $newName = date("YmdHis")."_".$file->getClientOriginalName();

        if($file->move("tmp", $newName)){
            if($ext == "txt" || $ext == "csv"){
                $content = file_get_contents("tmp/$newName");
                $data = explode("\r\n", $content);
                $row = [];
                $cell['emp_id'] = $machine->id_card;
                $cell['date'] = $machine->date_format;
                $cell['time'] =  $machine->time_format;
                $cell['absensi'] = $machine->absensi_code;
                $timeZone = null;
                    // foreach($data as $key => $item){
                    //     if($key >= $machine->start_row){
                    //         if($item != ""){
                    //             $col = [];
                    //             $keyId = substr($item, $machine->id_card['position'], $machine->id_card['width']);
                    //             if(!is_numeric($keyId)){
                    //                 $lzero = 0;
                    //                 for ($i=0; $i < strlen($keyId); $i++) {
                    //                     $iKey = $keyId[$i];
                    //                     if(!is_numeric($iKey)){
                    //                         $lzero = $i;
                    //                         break;
                    //                     }
                    //                 }
                    //                 $item = str_repeat("0", $machine->id_card['width'] - $lzero)."$item";
                    //                 $keyId = intval(substr($item, $machine->id_card['position'], $machine->id_card['width']));
                    //             }
                    //             $year = substr($item, $machine->date_format['year']['position'], $machine->date_format['year']['width']);
                    //             $month = substr($item, $machine->date_format['month']['position'], $machine->date_format['month']['width']);
                    //             $date = substr($item, $machine->date_format['date']['position'], $machine->date_format['date']['width']);
                    //             $hour = substr($item, $machine->time_format['hour']['position'], $machine->time_format['hour']['width']);
                    //             $minute = substr($item, $machine->time_format['minute']['position'], $machine->time_format['minute']['width']);
                    //             $att = substr($item, $machine->absensi_code['position'], $machine->absensi_code['width']);
                    //             $date = "$year-$month-$date";
                    //             $col['time'] = "$hour:$minute";
                    //             $row[$date][$keyId][$att] = $col;
                    //         }
                    //     }
                    // }

                    // $newRow = [];
                    // $dnow = $start_date;
                    // while ($dnow <= $end_date) {
                    //     if(isset($row[$dnow])){
                    //         $newRow[$dnow] = $row[$dnow];
                    //     }
                    //     $dnow = date("Y-m-d", strtotime("$dnow +1 day"));
                    // }
            } else {
                $d = date("Ymd");
                $content = Excel::toCollection(new CollectDataImport, "tmp/$newName");
                $data = $content[0];
                $n = 97;
                $chr = ord("b");
                $row = [];
                $timeZone = Config::get("app.timezone");
                $cell['emp_id'] = ord(strtolower($machine->id_card['column'])) - $n;
                $cell['date'] = ord(strtolower($machine->date_format['column'])) - $n;
                $cell['time'] = ord(strtolower($machine->time_format['column'])) - $n;
                $cell['absensi'] = ord(strtolower($machine->absensi_code['column'])) - $n;
                    // foreach($data as $item){
                    //     foreach($item as $key => $val){
                    //         if($key >= $machine->start_row){
                    //             $keyId = $val[$cellIdCard];
                    //             $_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val[$cellDate], $timeZone);
                    //             $_time = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($val[$cellTime], $timeZone);
                    //             $date = date("Y-m-d", strtotime($_date->format("Y-m-d")));
                    //             $time = date("H:i", $_time);
                    //             $att = $val[$cellAbsensi];
                    //             $col = [];
                    //             $col['time'] = $time;
                    //             $row[$date][$keyId][$att] = $col;
                    //         }
                    //     }
                    // }

                    // $newRow = [];
                    // $dnow = $start_date;
                    // while ($dnow <= $end_date) {
                    //     if(isset($row[$dnow])){
                    //         $newRow[$dnow] = $row[$dnow];
                    //     }
                    //     $dnow = date("Y-m-d", strtotime("$dnow +1 day"));
                    // }
            }

            $registrations = Att_employee_registration::where("company_id", Session::get("company_id"))->get();
            $reg = [];
            foreach($registrations as $item){
                $tp = $machine->id_card['type'] == "emp_id" ? $item->emp->emp_id : $item->id_card;
                $reg[$tp] = $item;
            }

            $inputs = [];
            foreach($request->all() as $key => $item){
                if(!in_array($key, ["_token", "file"])){
                    $inputs[$key] = $item;
                }
            }

            $target_file = "tmp/$newName";

            return view("_attendance.collect_data.process", compact("machine", "data", "cell", "timeZone", "reg", "target_file", "inputs", "oldName"))->withInput($request->all());
        } else {
            return redirect()->back()->with([
                "toast" => [
                    "message" => "Upload failed",
                    "bg" => "bg-danger"
                ],
                "trigger" => "select[name=machine_name]"
            ])->withInput($request->all());
        }

        // $reproses = $request->reproses ?? null;
        // if(empty($reproses)){
        //     $collect = Att_collect_datum::where("start_date", $start_date)
        //         ->where("company_id", Session::get("company_id"))
        //         ->where("machine_id", $machine->id)
        //         ->first();
        //     if(!empty($collect)){
        //         return redirect()->back()->withErrors([
        //             "start_date" => "Maaf, Mohon Perhatikan Tanggal Last Collect Data"
        //         ])->withInput($request->all())->with([
        //             "trigger" => "select[name=machine_name]"
        //         ]);
        //     }
        // }

        // if(count($newRow) > 0){
        //     $d = date("Ymd");
        //     $newName = $d."_".$file->getClientOriginalName();
        //     $_dir = str_replace("/", "\\", public_path("media/attachments"));
        //     $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
        //     $dir = str_replace("\\", "/", $dir);
        //     if($file->move($dir, $newName)){
        //         if(!empty($reproses)){
        //             $collect = Att_collect_datum::where("start_date", $start_date)
        //                 ->where("company_id", Session::get("company_id"))
        //                 ->where("machine_id", $machine->id)
        //                 ->first();
        //         } else {
        //             $collect = new Att_collect_datum();
        //         }
        //         $collect->machine_id = $machine->id;
        //         $collect->start_date = $start_date;
        //         $collect->end_date = $end_date;
        //         $collect->att_data = $newRow;
        //         $collect->created_by = Auth::id();
        //         $collect->company_id = Session::get("company_id");
        //         $collect->file_name = $file->getClientOriginalName();
        //         $collect->file_address = "media/attachments/$newName";
        //         $collect->save();

        //         return redirect()->back()->with([
        //             "toast" => [
        //                 "message" => "Successful Collect Data & Process",
        //                 "bg" => "bg-success"
        //             ],
        //             "trigger" => "select[name=machine_name]"
        //         ])->withInput($request->all());
        //     } else {
        //         return redirect()->back()->with([
        //             "toast" => [
        //                 "message" => "Upload failed",
        //                 "bg" => "bg-danger"
        //             ],
        //             "trigger" => "select[name=machine_name]"
        //         ])->withInput($request->all());
        //     }
        // }
    }

    function finalize_process(Request $request){
        $machine = Att_machine_name::find($request->machine_name);

        $mtype = $machine->id_card['type'] ?? "emp_id";

        $ext = $machine->program->program_type ?? "txt";
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $file = $request->target_file;

        $fname = explode("/", $file);

        $reproses = $request->reproses;

        $reg = Att_employee_registration::where('company_id', Session::get("company_id"))
            ->get();

        $_emp_id = [];

        foreach($reg as $item){
            if($mtype == "emp_id"){
                $_emp_id[$item->emp->emp_id] = $item->emp_id;
            } else {
                $_emp_id[$item->id_card] = $item->emp_id;
            }
        }

        $newRow = [];
        foreach($request->row ?? [] as $key => $item){
            $col = [];
            $col['time'] = $item['time'];
            $col['valid'] = $item['valid'];
            $col['remarks'] = $item['remarks'];
            $col['date'] = $item['date'];
            $col['att'] = $item['att'];
            $col['dtime'] = date("Y-m-d H:i:s", strtotime($item['date']." ".$item['time']));
            $emp_id = $_emp_id[$item['emp_id']] ?? null;
            if(!empty($emp_id)){
                $newRow[$emp_id][] = $col;
            }
        }

        $att = [];

        $last_datum = Att_collect_datum::where("company_id", Session::get("company_id"))
            ->orderBy("end_date", 'desc')
            ->first();

        $sproc = $start_date;
        if(!empty($last_datum) && empty($reproses)){
            $sproc = date("Y-m-d", strtotime($last_datum->end_date." +1 day"));
        }

        $_rr = [];
        foreach($reg as $item){
            if(empty($reproses)){
                $_rr[$item->emp_id] = [];
            } else {
                if(in_array($item->emp_id, array_keys($newRow))){
                    $_rr[$item->emp_id] = [];
                }
            }
        }

        $_dnow = $sproc;
        while($_dnow <= $end_date){
            $att[$_dnow] = $_rr;

            $_dnow = date("Y-m-d", strtotime($_dnow." +1 day"));
        }

        foreach($newRow as $emp_id => $item){
            $col = collect($item)->sortBy("dtime");
            $sdate = $start_date;
            // $att[$sdate][$emp_id] = [];
            $in = $machine->in_code;
            foreach($item as $val){
                if($in == $val['att']){
                    $sdate = $val['date'];
                }
                $row = $val;
                if($sdate >= $sproc && empty($reprose)){
                    $att[$sdate][$emp_id][$val['att']][] = $row;
                } else {
                    $att[$sdate][$emp_id][$val['att']][] = $row;
                }
            }
        }

        ksort($att);

        $_att = [];
        foreach($att as $sdate => $item){
            foreach($item as $emp_id => $val){
                $att_in = collect($val[$machine->in_code] ?? [])->first();
                $att_out = collect($val[$machine->out_code] ?? [])->last();

                $_att[$sdate][$emp_id][$machine->in_code] = $att_in;
                $_att[$sdate][$emp_id][$machine->out_code] = $att_out;
                if(!empty($machine->break_start_code)){
                    $att_break_start = collect($val[$machine->break_start_code] ?? [])->first();
                    $_att[$sdate][$emp_id][$machine->break_start_code] = $att_break_start;
                }

                if(!empty($machine->break_end_code)){
                    $att_break_end = collect($val[$machine->break_end_code] ?? [])->first();
                    $_att[$sdate][$emp_id][$machine->break_end_code] = $att_break_end;
                }
            }
        }

        $inputed_data = [];

        $user = $reg->pluck("id", "emp_id");
        $wg_user = $reg->pluck("workgroup", "emp_id");

        $periode = Att_periode::where("company_id", Session::get("company_id"))
            ->where(function($q) use($start_date, $end_date){
                $q->where("start_date", "<=", $end_date);
                $q->where("end_date", ">=", $start_date);
            })->get();

        $schedule = Att_workgroup_schedule::whereIn("periode", $periode->pluck("id"))
            ->get();
        $wg_schedule = [];
        foreach($schedule as $item){
            $wg_schedule[$item->workgroup] = array_merge($wg_schedule[$item->workgroup] ?? [], $item->detail);
        }

        $emp_correction = Att_schedule_correction::where("company_id", Session::get('company_id'))
            ->get();
        $emp_ov = [];
        foreach($emp_correction as $item){
            $emp_ov[$item->emp_id][$item->date] = $item->shift ?? [];
        }

        $shifts = Att_shift::where(function($q) {
            $q->where('company_id', Session::get("company_id"));
            $q->orWhereNull('company_id');
        })->get();
        $shift = [];
        foreach($shifts as $item){
            $shift[$item->id] = $item;
        }

        $rtype = Att_reason_type::where("code", '00')
            ->where(function($q) {
                $q->where('company_id', Session::get("company_id"));
                $q->orWhere('is_default', 1);
            })
            ->get();


        $condition = Att_reason_condition::where('company_id', Session::get("company_id"))
            ->whereIn("reason_type_id", $rtype->pluck("id"))
            ->where("status", 1)
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
            ->whereBetween("holiday_date", [$start_date, $end_date])
            ->pluck("id", "holiday_date");

        foreach($condition as $item){
            $string = "";
            $cond = array_keys($item->conditions);
            if(!empty($item->rp_detail) && $item->reason_pengganti){
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


        foreach($_att as $date => $item){
            foreach($item as $emp_id => $val){
                $el = collect($val)->sortBy('time');
                $_att[$date][$emp_id] = $el->toArray();
                if($date <= $end_date){
                    // dd($_att, $end_date, $date, $el, $val);
                    $clock_in = $val[$machine->in_code] ?? null;
                    $clock_out = $val[$machine->out_code] ?? null;
                    $break_out = $val[$machine->break_start_code] ?? null;
                    $break_in = $val[$machine->break_end_code] ?? null;

                    $_wg = $wg_user[$emp_id] ?? [];

                    // if(!empty($clock_in)){

                    // }

                    if(isset($user[$emp_id])){
                        if(isset($wg_schedule[$_wg])){
                            $inputed_data[] = $this->toRegister($emp_id, $machine->id, "clock_in", $clock_in, $wg_schedule[$_wg], $date);
                            $inputed_data[] = $this->toRegister($emp_id, $machine->id, "clock_out", $clock_out, $wg_schedule[$_wg], $date);
                            $inputed_data[] = $this->toRegister($emp_id, $machine->id, "break_out", $break_out, $wg_schedule[$_wg], $date);
                            $inputed_data[] = $this->toRegister($emp_id, $machine->id, "break_in", $break_in, $wg_schedule[$_wg], $date);
                        }
                    }



                    // if(!empty($clock_out)){
                    //     if(isset($user[$emp_id])){


                    //     }
                    // }



                    // if(!empty($break_out)){
                    //     if(isset($user[$emp_id])){


                    //     }
                    // }



                    // if(!empty($break_in)){
                    //     if(isset($user[$emp_id])){


                    //     }
                    // }
                }
            }
        }

        $att_record = [];

        foreach($reg as $item){
            $_wg = $wg_user[$item->emp_id] ?? null;
            $addRow = false;
            if(empty($reproses)){
                $addRow = true;
            } else {
                if(in_array($item->emp_id, array_keys($newRow))){
                    $addRow = true;
                }
            }
            if(!empty($_wg)){
                $_dnow = $start_date;
                while($_dnow <= $end_date && $addRow){
                    $sch = collect($wg_schedule[$_wg] ?? [])->where("date", $_dnow)->first();
                    $time_out = $sch['time_out'] ?? null;
                    $shift_id = intval($sch['shift_id'] ?? null);
                    $col = [];
                    $col['time_in'] = "";
                    $col['time_out'] = "";
                    $col['break_start'] = "";
                    $col['break_end'] = "";
                    $col['date'] = $_dnow;
                    $col['shift_id'] = $shift_id;
                    $att_record[$_dnow][$item->emp_id] = $col;
                    $_dnow = date("Y-m-d", strtotime($_dnow." +1 day"));
                }
            }
        }

        $_users = User::whereIn("emp_id", array_keys($_rr))
            ->where("company_id", Session::get("company_id"))
            ->get();

        $uemp = $_users->pluck("emp_id", "id");

        $att_mobile = \App\Models\User_attendance::whereIn("user_id", $_users->pluck("id"))
            ->whereNull("personel")
            ->where(function($q) use($start_date, $end_date){
                $q->whereDate("clock_time", ">=", $start_date);
                $q->whereDate("clock_time", "<=", $end_date);
            })->get();

        $atm = [];

        foreach($att_mobile as $item){
            if(isset($uemp[$item->user_id])){
                $atm[$uemp[$item->user_id]][date("Y-m-d", strtotime($item->clock_time))][$item->clock_type] = $item;
            }
        }

        foreach($inputed_data as $item){
            $_date = date("Y-m-d", strtotime($item['date_att']));
            $col = $att_record[$_date][$item['user_id']] ?? [];
            if($item['clock_type'] == "clock_in"){
                $col['time_in'] = $item['clock_time'];
            } elseif($item['clock_type'] == "clock_out") {
                $col['time_out'] = $item['clock_time'];
            } elseif($item['clock_type'] == "break_out") {
                $col['break_start'] = $item['clock_time'];
            } elseif($item['clock_type'] == "break_in") {
                $col['break_end'] = $item['clock_time'];
            }
            $col['date'] = $_date;
            $col['shift_id'] = $item['shift_id'];
            $att_record[$_date][$item['user_id']] = $col;
        }

        foreach($att_record as $date => $item){
            foreach($item as $user_id => $value){
                $_atm = $atm[$user_id] ?? [];
                $_atmD = $_atm[$date] ?? [];
                $_in = $_atmD['clock_in'] ?? [];
                $_out = $_atmD['clock_out'] ?? [];
                $_bout = $_atmD['break_out'] ?? [];
                $_bin = $_atmD['break_in'] ?? [];

                if(!empty($_in)){
                    if((empty($value['time_in']) || $value['time_in'] == "") || ($_in->clock_time < $value["time_in"])){
                        $value['time_in'] = $_in->clock_time;
                    }
                }

                if(!empty($_out)){
                    if((empty($value['time_out']) || $value['time_out'] == "") || ($_out->clock_time < $value["time_out"])){
                        $value['time_out'] = $_out->clock_time;
                    }
                }

                if(!empty($_bout)){
                    if((empty($value['break_start']) || $value['break_start'] == "") || ($_bout->clock_time < $value["break_start"])){
                        $value['break_start'] = $_bout->clock_time;
                    }
                }

                if(!empty($_bin)){
                    if((empty($value['break_end']) || $value['break_end'] == "") || ($_bin->clock_time < $value["break_end"])){
                        $value['break_end'] = $_bin->clock_time;
                    }
                }

                $att_record[$date][$user_id] = $value;
            }
        }

        $atReason = Att_reason_condition::where("company_id", Session::get("company_id"))->get();
        $atR = [];
        foreach($atReason as $item){
            $atR[$item->id] = $item;
        }


        $att_reason = $atReason->pluck("reason_name_id", "id");

        $leave = \App\Models\Att_leave_request::whereIn("emp_id", $reg->pluck("emp_id"))
            // ->whereNotNull("approved_at")
            ->get();

        $emps = \App\Models\Hrd_employee::whereIn("id", $reg->pluck("emp_id"))->get();
        $emp_data = [];
        foreach($emps as $item){
            $emp_data[$item->id] = $item;
        }

        $leave_record = [];
        $data_cuti = [];
        foreach($leave as $item){
            $col = [];
            $col['start'] = $item->start_date;
            $col['end'] = $item->end_date;
            $col['approved'] = $item->approved_at;
            $leave_record[$item->emp_id][$item->id] = $col;
            $data_cuti[$item->id] = $item;
        }

        $scdate = collect(array_keys($att_record));

        $overtime = \App\Models\Att_overtime_record::whereBetween("overtime_date", [$scdate->first(), $scdate->last()])
            ->where("company_id", Session::get('company_id'))
            ->get();

        $ovt_emp = [];
        foreach($overtime as $ovt){
            $ovt_emp[$ovt->emp_id][$ovt->overtime_date][] = $ovt;
        }

        $anulir_cuti = [];
        $leave_used = [];

        $attRInput = [];

        foreach($att_record as $_date => $value){
            foreach($value as  $emp_id => $item){
                $_wg = $wg_user[$emp_id] ?? [];
                $time_in = $item['time_in'] ?? '';
                $time_out = $item['time_out'] ?? '';
                $shift_id = $item['shift_id'];
                $date = $item['date'];

                $_shift = $shift[$shift_id] ?? [];

                if(!empty($_shift)){
                    $day_code = $_shift->day_code;
                    $shift_code = $shift_id;

                    $schedule_in = $_shift->schedule_in;
                    $schedule_out = $_shift->schedule_out;
                    $reason_id = null;
                    $col = [];
                    $creasons = [];

                    if ($item['time_in'] != "" && $item['time_out'] != "") {
                        $col['reason_id'] = 1;
                    }

                    $late_in = $this->getMinuteDiff(date("Y-m-d H:i:s", strtotime($time_in)), date("Y-m-d H:i:s", strtotime($item['date']." ".$_shift->schedule_in)));
                    $fast_out = $this->getMinuteDiff(date("Y-m-d H:i:s", strtotime($item['date']." ".$_shift->schedule_out)), date("Y-m-d H:i:s", strtotime($time_out)));
                    // $late_in = $time_in;
                    // $fast_out = $time_out;

                    $rid = [];

                    foreach($reason_condition as $reason){
                        $eval = eval($reason);
                        if($eval){
                            $col['reason_id'] = $con_reason[$eval] ?? 1;
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

                    $ovt_start = null;
                    $ovt_end = null;
                    $ovtHours = null;
                    $col['ovtstart'] = null;
                    $col['ovtend'] = null;
                    $col['ovthours'] = null;
                    $col['ovtstartin'] = null;
                    $col['ovtendin'] = null;
                    $col['ovthoursin'] = null;

                    if(isset($ovt_emp[$emp_id])){
                        $oemp = $ovt_emp[$emp_id];
                        if(isset($oemp[$date])){
                            foreach($oemp[$date] as $omp){
                                $ovt_start = date("Y-m-d H:i:s", strtotime($date." ".$omp->overtime_start_time));
                                $ovt_end = date("Y-m-d H:i:s", strtotime($date." ".$omp->overtime_end_time));
                                $sout = date("Y-m-d H:i:s", strtotime($date." ".$schedule_out));
                                $sin = date("Y-m-d H:i:s", strtotime($date." ".$schedule_in));
                                $ovtHours = $this->getMinuteDiff($ovt_end, $ovt_start);

                                if($omp->overtime_type == "in"){
                                    if($time_in != ""){
                                        if($ovt_end > $sin){
                                            $ovt_end = $sin;
                                        }
                                        if($ovt_start < $time_in){
                                            $ovt_start = $time_in;
                                        }
                                        $col['ovtstartin'] = $ovt_start;
                                        $col['ovtendin'] = $ovt_end;
                                        $col['ovthoursin'] = $ovtHours;
                                    }
                                } else {
                                    if($time_out != ""){
                                        if($ovt_start < $sout){
                                            $ovt_start = $sout;
                                        }
                                        if($ovt_end > $time_out){
                                            $ovt_end = $time_out;
                                        }
                                        $col['ovtstart'] = $ovt_start;
                                        $col['ovtend'] = $ovt_end;
                                        $col['ovthours'] = $ovtHours;
                                    }
                                }
                            }
                        }
                    }

                    // $col['emp_id'] = $emp_id;
                    $col['timin'] = $item['time_in'];
                    $col['timout'] = $item['time_out'];
                    $col['break_start'] = $item['break_start'] ?? null;
                    $col['break_end'] = $item['break_end'] ?? null;
                    $col['day_code'] = $_shift->day_code;
                    $col['reasons'] = $_creasons;
                    // $col['ovtstart'] = $ovt_start;
                    // $col['ovtend'] = $ovt_end;
                    // $col['ovthours'] = $ovtHours;
                    $col['late_in'] = $late_in;
                    // $col['att_date'] = $item['date'];
                    $col['reason_values'] = [
                        "late_in" => $late_in,
                        "fast_out" => $fast_out,
                    ];
                    $col['shift_id'] = $shift_id;
                    $col['company_id'] = Session::get('company_id');
                    $col['created_by'] = Auth::id();
                    $col['created_at'] = date("Y-m-d H:i:s");

                    if(isset($holidays[$date])){
                        $col['is_holiday'] = 1;
                        $col['holiday_id'] = $holidays[$date];
                    }

                    $_leave = $leave_record[$emp_id] ?? [];
                    if(count($_leave) > 0){
                        $_leave = collect($_leave) ?? [];
                        $_date = $item['date'];
                        $isLeave = false;
                        $_idcuti = null;
                        foreach($_leave as $icut => $_l){
                            if($_date >= $_l['start'] && $_date <= $_l['end']){
                                if(!empty($_l['approved'])){
                                    $isLeave = true;
                                }
                                $_idcuti = $icut;
                                break;
                            }
                        }

                        if($isLeave && ($time_in == "" && $time_out == "")){
                            $leave_used[$emp_id][] = $data_cuti[$_idcuti];
                            $col['timin'] = "";
                            $col['timout'] = "";
                            $col['day_code'] = 2;
                            $_rId = $data_cuti[$_idcuti]->reason_id;
                            $col['reason_id'] = $att_reason[$_rId] ?? 2;
                            $_reason['seq'] = 1;
                            $_reason['id'] = $att_reason[$_rId] ?? 2;
                            $col['reasons'] = [$_reason];
                        } else {
                            if($_idcuti != null){
                                $anulir_cuti[$emp_id][$_idcuti][] = $_date;
                            }
                        }
                    }

                    // auto overtime :: autoOvertime
                        if($time_in != "" ){
                            if($_shift->automatic_overtime && !empty($_shift->overtime_in)){
                                $autoIn = explode(":", $_shift->overtime_in);
                                $H = intval($autoIn[0]);
                                $m = intval($autoIn[1]);
                                $i = intval($autoIn[2]);
                                $_in = date("Y-m-d H:i:s", strtotime($item['date']." ".$_shift->schedule_in));
                                $_newIn = date("Y-m-d H:i:s", strtotime("$_in +$H hours $m minutes $i seconds"));
                                // dd($item['time_in'], $_newIn);
                                if(!empty($item['time_in'])){
                                    if($item['time_in'] < $_newIn){
                                        $_ovIn = $item['time_in'];
                                        if(!empty($col['ovtendin'])){
                                            if($_ovIn >= $col['ovtendin']){
                                                $_ovIn = $col['ovtendin'];
                                            }
                                        }
                                        $autoOvtIn = $this->getMinuteDiff(date("Y-m-d H:i:s", strtotime($_ovIn)), date("Y-m-d H:i:s", strtotime($_newIn)));
                                        $_emp = $emp_data[$emp_id];
                                        $ovt = $this->autoOvertime($_emp, "auto_in", $_ovIn, $_newIn, $_shift->day_code);
                                        $col['autoOvtIn'] = abs($autoOvtIn);
                                        $col['autoOvtInId'] = $ovt->id;
                                        $col['autoOvtInDetail'] = [
                                            "start" => $_ovIn,
                                            "end" => $_newIn
                                        ];
                                    }
                                }
                            }
                        }

                        if($time_out != ""){
                            if($_shift->automatic_overtime && !empty($_shift->overtime_out)){
                                $autoIn = explode(":", $_shift->overtime_out);
                                $H = intval($autoIn[0]);
                                $m = intval($autoIn[1]);
                                $i = intval($autoIn[2]);
                                $_out = date("Y-m-d H:i:s", strtotime($item['date']." ".$_shift->schedule_out));
                                $_newOut = date("Y-m-d H:i:s", strtotime("$_out -$H hours $m minutes $i seconds"));
                                if(!empty($item['time_out'])){
                                    if($item['time_out'] > $_newOut){
                                        $_ovOut = $item['time_out'];
                                        if(!empty($col['ovtstart'])){
                                            if($_ovOut >= $col['ovtstart']){
                                                $_ovOut = $col['ovtstart'];
                                            }
                                        }
                                        $autoOvtOut = $this->getMinuteDiff(date("Y-m-d H:i:s", strtotime($_ovOut)), date("Y-m-d H:i:s", strtotime($_newOut)));
                                        $_emp = $emp_data[$emp_id];
                                        $ovt = $this->autoOvertime($_emp, "auto_out", $_ovOut, $_newOut, $_shift->day_code);
                                        $col['autoOvtOut'] = abs($autoOvtOut);
                                        $col['autoOvtOutId'] = $ovt->id;
                                        $col['autoOvtOutDetail'] = [
                                            "start" => $_newOut,
                                            "end" => $_ovOut
                                        ];
                                    }
                                }
                            }
                        }
                    // end auto overtime

                    // $col['emp_id'] = $emp_id;
                    // $col['att_date'] = $item['date'];
                    $attRInput[] = $col;

                    \App\Models\Att_reason_record::updateOrCreate(
                        ["emp_id" => $emp_id, "att_date" => $item['date']],
                        $col
                    );
                }
            }
        }

        // dd($attRInput);

        $leave_emp = \App\Models\Att_leave_employee::whereIn("emp_id", $reg->pluck("emp_id"))
            ->where(function($q) use($start_date, $end_date){
                $q->where("start_periode", "<=", $end_date);
                $q->where("end_periode", ">=", $start_date);
            })
            ->orderBy('start_periode')->get();
        $lemp = [];
        foreach($leave_emp as $item){
            $lemp[$item->emp_id][] = $item;
        }

        foreach($leave_used as $emp_id => $item){
            foreach($item as $p){
                if(empty($p->used_at)){
                    $p->used_at = date("Y-m-d H:i:s");
                    $p->used_by = Auth::id();
                    $p->save();
                }
                // $anulir = 0;
                // $used = 0;
                // $_atr = $atR[$p->reason_id] ?? [];
                // if(!empty($_atr)){
                //     if($_atr->cut_leave){
                //         $mlt = $_atr->leave_days;
                //         $lb = collect($lemp[$emp_id] ?? [])->where("id", $_atr->leave_type)->first();
                //         if(empty($p->used_at)){
                //             $used += ($p->total_day * $mlt);
                //             $anulir += count($p->tanggal_anulir ?? []);

                //         }

                //         if(!empty($lb)){
                //             $p->record_id = $lb->id;
                //             $p->save();
                //             $lb->used += $used - $anulir;
                //             $lb->reserved -= $used - $anulir;
                //             $lb->save();
                //         }
                //     }
                // }
            }
        }

        $data_cuti = collect($data_cuti);
        foreach($data_cuti->whereIn("emp_id", array_keys($anulir_cuti)) as $item){
            $_anulir = $anulir_cuti[$item->emp_id] ?? [];
            if(!empty($_anulir)){
                $_an = $_anulir[$item->id] ?? [];
                if(!empty($_an)){
                    $_tgl = json_decode($item->tanggal_anulir ?? "[]", true);
                    foreach($_an as $i){
                        if(!in_array($i, $_tgl)){
                            $_tgl[] = $i;
                        }
                    }
                    // if(!empty($item->approved_at)){
                    //     $_start = $item->start_date;
                    //     $_lemp = collect($lemp[$item->emp_id] ?? [])->where("type", $item->leave_used)->where("start_periode" ,"<=", $_start)->where("end_periode", ">=", $_start)->first();
                    //     if(!empty($_lemp)){
                    //         $_atr = $atR[$item->reason_id] ?? [];
                    //         if(empty($item->used_at)){
                    //             $mlt = $_atr->leave_days ?? 1;
                    //             $_lemp->anulir = $_lemp->anulir + (count($_tgl) * $mlt);
                    //             $_lemp->save();
                    //             $item->used_at = date("Y-m-d H:i:s");
                    //             $item->used_by = Auth::id();
                    //             $item->record_id = null;
                    //             $item->save();
                    //         }
                    //     }
                    // }
                    if(!empty($item->approved_at)){
                        $item->tanggal_anulir = json_encode($_tgl);
                        $item->save();
                    }
                }
            }
        }

        $dataUsed = [];
        $dataReserved = [];
        $dataAnulir = [];
        foreach($leave->whereNotNull("approved_at") as $item){
            if(!empty($item->record_id)){
                if(!empty($item->used_at)){
                    $anulir = json_decode($item->tanggal_anulir ?? "[]");
                    $dataUsed[$item->record_id][] = $item->total_day - count($anulir);
                } else {
                    $dataReserved[$item->record_id][] = $item->total_day;
                }
                if(!empty($item->tanggal_anulir)){
                    $dataAnulir[$item->record_id][] = $item->tanggal_anulir;
                }
            }
        }

        foreach($leave_emp as $item){
            $_used = $dataUsed[$item->id] ?? [];
            $_an = $dataAnulir[$item->id] ?? [];
            $_rsv = $dataReserved[$item->id] ?? [];
            if(!empty($_used)){
                if($item->used >= $item->jatah){
                    $minus = array_sum($_used) - $item->used;
                    $item->minus_used = $minus;
                } else {
                    $item->used = array_sum($_used);
                }
            }
            $item->reserved = array_sum($_rsv);
            if(!empty($_an)){
                $anl = 0;
                foreach($_an as $p){
                    $anl += count(json_decode($p ?? "[]", true));
                }
                $item->anulir = $anl;
            }
            $item->save();
        }

        $_dir = str_replace("/", "\\", public_path("collect_data"));
        $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
        $dir = str_replace("\\", "/", $dir);
        try {
            $collect = new Att_collect_datum();
            $collect->machine_id = $machine->id;
            $collect->start_date = $start_date;
            $collect->end_date = $end_date;
            $collect->att_data = $newRow;
            $collect->created_by = Auth::id();
            $collect->company_id = Session::get("company_id");
            $collect->file_name = end($fname);
            $collect->file_address = "collect_data/".end($fname);
            $collect->save();

            return redirect()->route("attendance.collect_data.index")->with([
                "toast" => [
                    "message" => "Successful Collect Data and Process",
                    "bg" => "bg-success"
                ],
                "trigger" => "select[name=machine_name]"
            ])->withInput($request->all());
            // if(\File::move($file, "$dir/".end($fname))){


            // } else {
            //     return redirect()->route("attendance.collect_data.index")->with([
            //         "toast" => [
            //             "message" => "Upload failed",
            //             "bg" => "bg-danger"
            //         ],
            //         "trigger" => "select[name=machine_name]"
            //     ])->withInput($request->all());
            // }
        } catch (\Throwable $th) {
            return redirect()->route("attendance.collect_data.index")->with([
                "toast" => [
                    "message" => $th->getMessage(),
                    "bg" => "bg-danger"
                ],
                "trigger" => "select[name=machine_name]"
            ])->withInput($request->all());
        }
    }

    function autoOvertime($emp, $type, $start, $end, $day_code){
        $overtime = Att_overtime_record::where("emp_id", $emp->id)
            ->where("overtime_type", $type)
            ->where("overtime_date", date("Y-m-d", strtotime($start)))
            ->first();
        if(empty($overtime)){
            $overtime = new Att_overtime_record();
            $overtime->emp_id = $emp->id;
            $overtime->overtime_type = $type;
            $overtime->company_id = Session::get('company_id');
        }
        $overtime->reason_id = $day_code;
        $overtime->overtime_date = date("Y-m-d", strtotime($start));
        $overtime->overtime_start_time = date("H:i:s", strtotime($start));
        $overtime->overtime_end_time = date("H:i:s", strtotime($end));
        $overtime->departement = $emp->user->uac_departement ?? null;
        $overtime->created_by = Auth::id();
        $overtime->approved_at = date("Y-m-d H:i:s");
        $overtime->approved_by = Auth::id();
        $overtime->save();

        return $overtime;
    }

    function toRegister($emp_id, $mId, $type, $clock, $wg, $date){
        $sch = collect($wg)->where("date", $date)->first();
        $time_out = $sch['time_out'] ?? null;
        $shift_id = intval($sch['shift_id'] ?? null);

        if(isset($emp_ov[$emp_id])){
            if(isset($emp_ov[$emp_id][$date])){
                $_shift = $emp_ov[$emp_id][$date];
                $time_out = $_shift['schedule_out'];
                $shift_id = $_shift['id'];
            }
        }

        $isLate = 0;
        if(($clock['time'] ?? null) < $time_out){
            $isLate = 1;
        }

        $col = [];
        // $col['user_id'] = $emp_id;
        // $col['clock_type'] = "clock_out";
        // $col['location_type'] = 1;
        // $col['clock_time'] = $date." ".$clock_out['time'];
        // $col['company_id'] = Session::get('company_id');
        $col['machine_id'] = $mId;
        $col['is_late'] = $isLate;
        $col['shift_id'] = $shift_id;
        $col['created_at'] = date("Y-m-d H:i:s");

        \App\Models\User_attendance::withoutGlobalScope('mobile')->updateOrCreate(
            ["user_id" => $emp_id,
            "clock_type" => $type,
            "clock_time" => $clock['dtime'] ?? null,
            "company_id" => Session::get('company_id'),
            // "machine_id" => $machine->id,
            "location_type" => 1,
            "personel" => 1
            ],
            $col
        );
        $col['user_id'] = $emp_id;
        $col['clock_type'] = $type;
        $col['location_type'] = 1;
        $col['clock_time'] = $clock['dtime'] ?? null;
        $col['date_att'] = $date;
        $col['company_id'] = Session::get('company_id');
        return $col;
    }

    function getMinuteDiff($time1, $time2){
        $dIn1 = date_create(date("Y-m-d H:i:s", strtotime($time1)));
        $dIn2 = date_create(date("Y-m-d H:i:s", strtotime($time2)));
        $dIn3 = date_diff($dIn2, $dIn1);

        $hIn = $dIn3->format("%r%h");
        $mIn = $dIn3->format("%r%i");
        $sIn = $dIn3->format("%r%s");

        $minute = ($hIn * 60) + $mIn;

        return $minute;
    }

    function sumAtt(){
        $data = [];

        $start_date = "2024-02-26";
        $end_date = "2024-03-25";

        $reg = \App\Models\Att_employee_registration::where("company_id", Session::get("company_id"))->get();

        $emp = \App\Models\Hrd_employee::whereIn("id", $reg->pluck("emp_id"))->get();

        foreach($emp as $item){
            $data[] = $this->summaryAttendance($item->id, $start_date, $end_date);
        }

        $data = collect($data)->sortBy("id");

        return view("sum_att", compact("data"));
    }

    function summaryAttendance($emp_id, $start_date, $end_date){

        if(empty($emp_id) || empty($start_date)){
            return $this->sendError("missing parameter");
        }

        if (date('Y-m-d', strtotime($start_date)) != $start_date){
            return $this->sendError("Invalid Format Start Date");
        }

        if(!empty($end_date)){
            if (date('Y-m-d', strtotime($end_date)) != $end_date){
                return $this->sendError("Invalid Format Start Date");
            }
        }

        $personel = Hrd_employee::find($emp_id);

        if(empty($personel)){
            return $this->sendError("Employee ID not found");
        }

        $reg = \App\Models\Att_employee_registration::where("emp_id", $personel->id)->first();

        $user = $personel->user ?? [];

        $attendance = \App\Models\Att_reason_record::where("emp_id", $personel->id)
            ->where(function($q) use($start_date, $end_date){
                if(empty($end_date)){
                    $q->where("att_date", $start_date);
                } else {
                    $q->whereDate("att_date", ">=", $start_date);
                    $q->whereDate("att_date", "<=", $end_date);
                }
            })
            ->get();

        $attR = [];
        foreach($attendance as $item){
            // $d = date("Y-m-d", strtotime($item))
            $attR[$item->att_date] = $item;
        }

        $periode = \App\Models\Att_periode::where("company_id", $personel->company_id)
            ->where(function($q) use($start_date, $end_date){
                if(empty($end_date)){
                    $q->where("start_date", "<=", $start_date);
                    $q->where("end_date", ">=", $start_date);
                }
            })->get();

        $schedule = \App\Models\Att_workgroup_schedule::whereIn("periode", $periode->pluck("id"))
            ->where("workgroup", $reg->workgroup ?? null)
            ->get();
        $wg_schedule = [];
        foreach($schedule as $item){
            $wg_schedule = array_merge($wg_schedule ?? [], $item->detail);
        }

        $emp_correction = \App\Models\Att_schedule_correction::where("company_id", $personel->company_id)
            ->where("emp_id", $personel->id)
            ->get();
        $emp_ov = [];
        foreach($emp_correction as $item){
            $emp_ov[$item->date] = $item->shift ?? [];
        }

        $holidays = \App\Models\Att_holiday::where("company_id", $personel->company_id)
            ->whereBetween("holiday_date", [$start_date, $end_date])
            ->pluck("id", "holiday_date");

        $att = [];

        $dnow = $start_date;

        $ldate = $end_date ?? $start_date;

        $shift = \App\Models\Att_shift::where(function($q) use($personel){
            $q->whereNull("company_id");
            $q->orWhere("company_id", $personel->company_id);
        })->get();

        $dcData = \App\Models\Att_day_code::get();

        $day_code = $dcData->pluck('day_name', "id");
        $day_id = $dcData->pluck("day_code", "id");

        $reasons = \App\Models\Att_reason_name::get();
        $rname = $reasons->pluck("reason_name", "id");
        $rcolor = $reasons->pluck("color", "id");
        $rId = $reasons->pluck("reason_id", "id");

        $shift_name = $shift->pluck("shift_name", "id");
        $shift_id = $shift->pluck("shift_id", "id");
        $shift_clr = $shift->pluck("shift_color", "id");
        $shift_dc = $shift->pluck("day_code", "id");
        $shift_in = $shift->pluck("schedule_in", "id");
        $shift_out = $shift->pluck("schedule_out", "id");
        $bend = $shift->pluck("break_out", "id");

        $wd = 0;
        $od = 0;
        $hd = 0;

        $working_hour = 0;
        $break_time = 0;
        $late = 0;
        $home_early = 0;
        $overtime = 0;
        $sday = 0;
        $hadir = 0;

        if(!empty($reg)){
            for ($dnow = $start_date; $dnow <= $ldate ; $dnow = date("Y-m-d", strtotime("$dnow +1 day"))) {
                $N = date("N", strtotime($dnow));
                if($N < 6){
                    $sday++;
                }
                $row = [];
                $sch = collect($wg_schedule)->where("date", $dnow)->first();

                $r = $attR[$dnow] ?? [];

                // if(empty($r)){
                //     // $r = new \App\Models\Att_reason_record();
                // }

                // $sId = $sch['shift_id'] ?? null;
                $sId = $emp_ov[$dnow]->shift_id ?? ($sch['shift_id'] ?? null);
                // if(isset($emp_ov[$dnow])) $sId = $emp_ov[$dnow]->shift_id ?? null;
                $dc = $shift_dc[$sId] ?? 2;

                $rr = [];

                foreach($r->reasons ?? [] as $v){
                    $_c = [];
                    $_c['reason_id'] = $rId[$v['id']];
                    $_c['name'] = $rname[$v['id']];
                    $_c['color'] = $rcolor[$v['id']];
                    $rr[] = $_c;
                }

                $row['shift_id'] = $shift_id[$sId] ?? '-';
                $row['shift'] = $shift_name[$sId] ?? '-';
                $row['shift_color'] = $shift_clr[$sId] ?? "#000";
                $row['day_code_id'] = $day_id[$dc];
                $row['day_code'] = $day_code[$dc] ?? "Offday";
                $row['reason_attendance'] = $rr;

                $row['date'] = $dnow;
                $att[] = $row;

                $dd = $dcData->where("id", $dc)->first();

                if($dd->attend == 1){
                    $wd += 1;
                } else {
                    if($dd->is_holiday == 1){
                        $hd += 1;
                    } else {
                        $od += 1;
                    }
                }

                if(!empty($r)){
                    $in = $r->timin;
                    $out = $r->timout ?? $shift_out[$sId];

                    if(!empty($r->timin)){
                        $hadir += 1;
                    }

                    $whM = $this->getMinuteDiff($out, $in);
                    $working_hour += $whM;

                    $bbend = $r->break_end ?? ($bend[$sId] ?? null);

                    if(!empty($r->break_start) && !empty($bbend)){
                        $break_time += abs($this->getMinuteDiff($bbend, $r->break_start));
                    }

                    $rv = $r->reason_values;
                    $rl = $rv['late_in'] ?? 0;
                    if(!empty($r->timin)){
                        
                        // $rn = $this->getMinuteDiff($r->timin, $shift_in[$sId])
                        if($rl > 0){
                            $late += $rl;
                        }
                    }

                    // if($out < $shift_out[$sId]){
                    //     $fv = $this->getMinuteDiff($shift_out[$sId], $out);
                    //     $home_early += $fv;
                    // }

                    if(!empty($r->timout)){
                        $rf = $rv['fast_out'] ?? 0;
                        if($rf > 0){
                            $home_early += $rv['fast_out'] ?? 0;
                        }
                    }

                    if(!empty($r->ovtstartin) && !empty($r->ovtendin)){
                        $ovtIn = $this->getMinuteDiff($r->ovtendin, $r->ovtstartin);
                        $overtime += $ovtIn;
                    }

                    if(!empty($r->ovtstart) && !empty($r->ovtend)){
                        $ovtOut = $this->getMinuteDiff($r->ovtstart, $r->ovtend);
                        $overtime += $ovtOut;
                    }
                }
            }
        }

        $sg = \DB::table("temp_employee_salary_group")->where("emp_id", $personel->id)->first();
        $pid = \DB::table("temp_salary_periode")->where("salary_group_id", $sg->salary_group_id ?? null)->first();

        $wh['H'] = floor($working_hour / 60);
        $wh['M'] = $working_hour - ($wh["H"] * 60);

        $bt['H'] = floor($break_time / 60);
        $bt['M'] = $break_time - ($bt["H"] * 60);

        $lt['H'] = floor($late / 60);
        $lt['M'] = $late - ($lt["H"] * 60);

        $he['H'] = floor($home_early / 60);
        $he['M'] = $home_early - ($he["H"] * 60);

        $ov['H'] = floor($overtime / 60);
        $ov['M'] = $overtime - ($ov["H"] * 60);

        $col = [];
        $col['working_hours'] = sprintf("%02d", $wh['H']).":".sprintf("%02d", $wh['M']);
        $col['effective_working_hours'] = $col['working_hours'];
        $col['break_time'] = sprintf("%02d", $bt['H']).":".sprintf("%02d", $bt['M']);
        $col['late'] = sprintf("%02d", $lt['H']).":".sprintf("%02d", $lt['M']);
        $col['home_early'] = sprintf("%02d", $he['H']).":".sprintf("%02d", $he['M']);
        $col['overtime'] = sprintf("%02d", $ov['H']).":".sprintf("%02d", $ov['M']);
        $col['overtime_index'] = $col['overtime'];
        $col['workday'] = $wd;
        $col['off_day'] = $od;
        $col['national_day'] = $hd;
        $col['salary_cut_off_day'] = $sday;
        $col['working_group_code'] = $reg->wg->id ?? null;
        $col['working_group_date'] = $reg->date2 ?? null;
        $col['leave_group_code'] = $reg->leave->id ?? null;
        $col['leave_group_date'] = $reg->date3 ?? null;
        $col['company_id'] = $personel->company_id;
        $col['employee_id'] = $personel->id;
        $col['salary_group_id'] = $sg->salary_group_id ?? null;
        $col['period_id'] = $pid->period_id ?? null;
        $col['start_date'] = $start_date;
        $col['end_date'] = $end_date;
        $col['hadir'] = $hadir;
        // $col['attendance'] = $att;

        return $col;
    }
}
