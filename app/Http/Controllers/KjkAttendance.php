<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee;
use App\Models\User;
use App\Models\User_attendance;
use App\Models\Kjk_attendance_report_template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KjkAttendance extends Controller
{
    function report_page(Request $request){

        $list = Kjk_attendance_report_template::where("company_id", Session::get("company_id"))->get();
        $cby = User::whereIn("id", $list->pluck("created_by"))->get()->pluck("name", "id");

        return view("employee.attendance.report_index", compact("list", "cby"));
    }

    function report_add(Request $request){

        if(!empty($request->a)){
            if($request->a == "row"){
                $v = view("employee.attendance._row")->render();

                return $v;
            }
        }

        $template = [];
        $columns = [];

        return view("employee.attendance.report_page", compact("template", 'columns'));
    }

    function report_edit($id){
        $template = Kjk_attendance_report_template::find($id);

        $columns = json_decode($template->columnDefs, true);

        return view("employee.attendance.report_page", compact("template", "columns"));
    }

    function report_delete($id){
        $template = Kjk_attendance_report_template::find($id);
        $template->delete();

        return redirect()->back();
    }

    function report_result($id, Request $request){
        $template = Kjk_attendance_report_template::find($id);

        $columns = json_decode($template->columnDefs, true);

        if($request->a == "table"){

            $data = [];

            if($request->show == 1){
                $_from = explode(' ', $request->from);
                $_from1 = explode("/", $_from[0]);
                krsort($_from1);
                $from = implode("-", $_from1)." ".$_from[1];

                $_to = explode(' ', $request->to);
                $_to1 = explode("/", $_to[0]);
                krsort($_to1);
                $to = implode("-", $_to1)." ".$_to[1];

                $order = $template->cin_order > $template->cout_order ? "desc" : "asc";
                
                $attendance = User_attendance::where("company_id", Session::get('company_id'))
                    ->whereBetween("created_at", [$from, $to])
                    ->whereIn("clock_type", ["clock_in", "clock_out"])
                    ->orderBy("created_at")
                    ->get();

                $user_attend = [];

                foreach($attendance as $item){
                    $user_attend[$item->user_id][$item->clock_type][] = $item;
                }
                

                $users = User::whereIn("id", $attendance->pluck("user_id"))
                    ->get();

                $uId = $users->pluck("id", "emp_id");

                $emp = Hrd_employee::where("company_id", Session::get('company_id'))
                    ->whereIn("id", $users->pluck("emp_id"))
                    ->orderBy("emp_name")
                    ->get();

                $empName = $emp->pluck("emp_name", "id");

                $ctype = ["clock_in", "clock_out"];
                
                if($template->type == 2){
                    if($template->cin_order > $template->cout_order){
                        rsort($ctype);
                    }
                    foreach($emp as $i => $item){
                        $_user = $uId[$item->id] ?? null;

                        if(!empty($_user)){
                            $_uClock = $user_attend[$_user] ?? null;
                            if(!empty($_uClock)){
                                foreach($ctype as $cl){
                                    if(isset($_uClock[$cl])){
                                        $el = $_uClock[$cl];
                                        $_el = null;
                                        if($cl == "clock_out"){
                                            $_el = end($el);
                                        } else {
                                            $_el = $el[0];
                                        }

                                        $col = [];
                                        $col['#'] = $i+1;
                                        $col['id'] = $item->id;
                                        $col['employee_name'] = $item->emp_name;
                                        $col['company_id'] = $item->company_id;
                                        $col['company_name'] = Session::get("company_name_parent");
                                        $col['time'] = $_el->clock_time;
                                        $col['type'] = $_el->clock_type == "clock_in" ? $template->cin_val : $template->cout_val;
                                        $data[] = $col;
                                    }
                                }
                            }
                        }
                    }
                } else {
                    foreach($emp as $i => $item){
                        $_user = $uId[$item->id] ?? null;
            
                        $clock_in = "-";
                        $clock_out = "-";
                        $break_in = "-";
                        $break_out = "-";
            
                        if(!empty($_user)){
                            $_uClock = $user_attend[$_user] ?? null;
                            if(!empty($_uClock)){
                                foreach($ctype as $cl){
                                    if(isset($_uClock[$cl])){
                                        $el = $_uClock[$cl];
                                        if($cl == "clock_out"){
                                            $_el = end($el);
                                            $$cl = $_el->clock_time;
                                        } else {
                                            $$cl = $el[0]->clock_time;
                                        }
                                    }
                                }
                            }
                        }
            
                        $col = [];
                        $col['#'] = $i+1;
                        $col['id'] = $item->id;
                        $col['employee_name'] = $item->emp_name;
                        $col['company_id'] = $item->company_id;
                        $col['company_name'] = Session::get("company_name_parent");
                        $col['check_in'] = $clock_in;
                        $col['check_out'] = $clock_out;
                        $col['break_in'] = $break_in;
                        $col['break_out'] = $break_out;
                        $data[] = $col;
                    }
                }
            }
            
            $res = [
                "data" => $data
            ];

            return json_encode($res);
        }

        return view("employee.attendance.report_result", compact("template", "columns"));
    }

    function report_store(Request $request){

        $_fdate = explode("/", $request->from_date);
        krsort($_fdate);
        $fdate = implode("-", $_fdate)." ".$request->from_time;

        $_tdate = explode("/", $request->to_date);
        krsort($_tdate);
        $tdate = implode("-", $_tdate)." ".$request->to_time;

        $template = Kjk_attendance_report_template::findOrNew($request->template_id);
        $template->name = $request->template_name;
        $template->from_date = $fdate;
        $template->to_date = $tdate;
        $template->type = $request->type;
        if($request->type == 2){
            $template->cin_val = $request->cin_val;
            $template->cin_order = $request->cin_order;
            $template->cout_val = $request->cout_val;
            $template->cout_order = $request->cout_order;
        } else {
            $template->cin_val = null;
            $template->cin_order = null;
            $template->cout_val = null;
            $template->cout_order = null;
        }

        $columns = [];
        $_format = $request->format_selected;
        foreach($request->columns as $i => $col){
            $cols = [];
            $cols['column'] = $col;
            $cols['format'] = $_format[$i] ?? "text";
            $columns[] = $cols;
        }

        $template->columnDefs = json_encode($columns);
        $template->created_by = Auth::id();
        $template->company_id = Session::get("company_id");
        $template->save();

        return redirect()->route("attendance.report");
    }
}
