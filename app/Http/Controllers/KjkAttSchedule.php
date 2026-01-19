<?php

namespace App\Http\Controllers;

use App\Models\Att_day_code;
use App\Models\Att_holiday;
use App\Models\Att_periode;
use App\Models\Att_shift;
use App\Models\Att_workgroup;
use App\Models\Att_workgroup_patern;
use App\Models\Att_workgroup_schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KjkAttSchedule extends Controller
{
    function index(Request $request){

        $workgroups = Att_workgroup::where("company_id", Session::get("company_id"))
            ->get();

        $periods = Att_periode::where("company_id", Session::get("company_id"))
            ->where(function($q){
                $q->whereYear("start_date", date("Y"));
                $q->orWhereYear("end_date", date("Y"));
            })
            ->orderBy("start_date")
            ->get();

        if($request->a == "schedule"){
            $wg = $workgroups->where("id", $request->w)->first();
            $pd = $periods->where("id", $request->p)->first();

            $now = date("Y-m-d");
            // if($now > $pd->end_date){
            //     return json_encode([
            //         "success" => false,
            //         "message" => "Maaf, periode bulan yang Anda pilih sudah tidak aktif.",
            //     ]);
            // }

            $schedule = Att_workgroup_schedule::where("company_id", Session::get("company_id"))
                ->where("workgroup",$wg->id)
                ->where("periode", $pd->id)
                ->first();

            $list = $schedule->detail ?? [];

            return json_encode([
                "success" => true,
                "message" => "",
                "data" => [
                    "from" => $pd->start_date,
                    "from_label" => date("d F Y", strtotime($pd->start_date)),
                    "to" => $pd->end_date,
                    "to_label" => date("d F Y", strtotime($pd->end_date)),
                    "wg" => $wg->workgroup_name,
                    "pr" => $pd->name,
                    "list" => $list ?? null
                ]
            ]);
        }

        return view("_attendance.schedule.index", compact('workgroups', 'periods'));
    }

    function generateSchedule($wg, $from, $to){

        $pattern = Att_workgroup_patern::find($wg->patern);

        $sequences = $pattern->sequences;
        $shifts = Att_shift::where(function($q) {
            $q->where("company_id", Session::get("company_id"));
            $q->orWhere("is_default", 1);
        })->get();

        $soff = $shifts->first();

        $sh = [];
        foreach($shifts as $item){
            $sh[$item->id] = $item;
        }

        $shift_hol = Att_shift::where("day_code", 3)->first();

        $dnow = $from;
        $seq = 0;
        $schedule = [];
        $dayCode = Att_day_code::where(function($q) {
            $q->where("company_id", Session::get("company_id"));
            $q->orWhere("is_default", 1);
        })->pluck("attend", 'id');
        $hari = [1=> "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
        $d = date("N", strtotime($dnow));

        $holiday = Att_holiday::whereBetween("holiday_date", [$from, $to])
            ->where("company_id", Session::get("company_id"))
            ->pluck("name", "holiday_date");
        while($dnow <= $to){
            $N = date("N", strtotime($dnow));
            $col = [];
            $el = $sequences[$seq][$N - 1];
            $shift = $sh[$el] ?? $soff;
            $dcode = $dayCode[$shift->day_code] ?? [];
            $break = $shift->break_shifts ?? [];

            $isHoliday = $holiday[$dnow] ?? null;

            $col['shift_id'] = empty($isHoliday) ? $el : $shift_hol->id;
            $col['sequence'] = $seq;
            $col['day'] = $hari[$N];
            $col['n'] = $N;
            $col['date_label'] = date("d F Y", strtotime($dnow));
            $col['date'] = $dnow;

            $col['time_in'] = empty($isHoliday) ? ($shift->schedule_in ?? "-") : "-";
            $col['time_out'] = empty($isHoliday) ? ($shift->schedule_out ?? "-") : "-";
            $col['break_1'] = empty($isHoliday) ? ($break[0]['start'] ?? "-") : "-";
            $col['break_2'] = empty($isHoliday) ? ($break[1]['start'] ?? "-") : "-";
            $col['day_off'] = empty($isHoliday) ? ($dcode == 1 ? false : true) : true;
            $col['holiday'] = empty($isHoliday) ? false : true;
            if($wg->replace_holiday_flag == 1){
                $col['shift_id'] = $el;
                $col['time_in'] = ($shift->schedule_in ?? "-");
                $col['time_out'] = ($shift->schedule_out ?? "-");
                $col['break_1'] = ($break[0]['start'] ?? "-");
                $col['break_2'] = ($break[1]['start'] ?? "-");
                $col['day_off'] = $dcode == 1 ? false : true;
                $col['holiday'] = false;
            }
            $schedule[] = $col;

            $d = $N;
            if($N == 7){
                $d = 1;
                $seq++;
                if(!isset($sequences[$seq])){
                    $seq = 0;
                }
            }

            $dnow = date("Y-m-d", strtotime($dnow." +1 day"));
        }

        return $schedule;
    }

    function generate(Request $request){
        $wg = Att_workgroup::find($request->workgroup);
        $pr = Att_periode::find($request->periode);
        $from = $request->from_date;
        $to = $request->to_date;

        $data[$pr->id] = $this->generateSchedule($wg, $from, $to);

        $auto = $request->auto;
        if(!empty($auto)){
            $periods = Att_periode::where("company_id", Session::get('company_id'))
                ->where("start_date", ">", $pr->start_date)
                ->orderBy("start_date")
                ->limit($request->months)
                ->get();

            if($periods->count() != $request->months){
                return json_encode([
                    "success" => false,
                    "message" => "Error, please create the Periods first"
                ]);
            }

            foreach($periods as $item){
                $data[$item->id] = $this->generateSchedule($wg, $item->start_date, $item->end_date);
            }
        }

        $_list = [];

        foreach($data as $id => $item){
            $schedule = Att_workgroup_schedule::firstOrNew([
                "workgroup" => $wg->id,
                "periode" => $id
            ]);
            $schedule->workgroup = $wg->id;
            $schedule->periode = $id;
            $schedule->detail = $item;
            $schedule->created_by = Auth::id();
            $schedule->company_id = Session::get('company_id');
            $schedule->save();
            $_list = array_merge($_list, $item);
        }

        return json_encode([
            "success" => true,
            "data" => $_list,
            "wg" => $wg->workgroup_name,
            "pr" => $pr->name,
            "message" => $request->type == "new" ? "Successfully Generate Schedule" : "Successfully Re-Generate Schedule"
        ]);
    }
}
