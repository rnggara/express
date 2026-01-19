<?php

namespace App\Http\Controllers;

use App\Models\Att_leave_request;
use App\Models\Att_overtime_record;
use App\Models\Hrd_employee;
use App\Models\Kjk_comp_position;
use App\Models\Personel_attendance_correction_request;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ESSApproval extends Controller
{
    function index(Request $request){

        // $personel = Hrd_employee::find(Auth::user()->emp_id);

        // $myRequest = [];

        // $leaveRequest = Att_leave_request::where("emp_id", $personel->id)
        //     ->get();

        // $overtimeRequest = Att_overtime_record::where("emp_id", $personel->id)
        //     ->get();

        // $attCorr = Personel_attendance_correction_request::where("emp_id", $personel->id)
        //     ->orderBy("id", "desc")
        //     ->get();

        // foreach($leaveRequest as $item){
        //     $col = [];
        //     $status = "<span class='badge badge-secondary'>Menunggu</span>";
        //     if (!empty($item->approved_at)) {
        //         $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
        //     }
        //     if (!empty($item->rejected_at)) {
        //         $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
        //     }
        //     $col['url'] = route("ess.leave.index")."?a=detail&id=".$item->id;
        //     $col['request_type'] = empty($item->cr) ? "Cuti - Cuti" : "Koreksi Kehadiran - Cuti";
        //     $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
        //     $col['request_detail'] = date("d-m-Y", strtotime($item->start_date))." - ".date("d-m-Y", strtotime($item->end_date));
        //     $col['ref_num'] = $item->ref_num;
        //     $col['status'] = $status;
        //     $col['id'] = $item->id;
        //     $col['date'] = $item->created_at;
        //     $col['type'] = "leave";
        //     $myRequest[] = $col;
        // }

        // foreach($overtimeRequest as $item){
        //     $col = [];
        //     $status = "<span class='badge badge-secondary'>Menunggu</span>";
        //     if (!empty($item->approved_at)) {
        //         $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
        //     }
        //     if (!empty($item->rejected_at)) {
        //         $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
        //     }
        //     $col['url'] = route("ess.overtime.detail", $item->id);
        //     $col['request_type'] = "Lembur";
        //     $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
        //     $col['request_detail'] = date("d-m-Y", strtotime($item->overtime_date))." (".date("H:i", strtotime($item->overtime_start_time))."-".date("H:i", strtotime($item->overtime_end_time)).")";
        //     $col['ref_num'] = $item->ref_num;
        //     $col['status'] = $status;
        //     $col['id'] = $item->id;
        //     $col['date'] = $item->created_at;
        //     $col['type'] = "overtime";
        //     $myRequest[] = $col;
        // }

        // foreach($attCorr as $item){
        //     $col = [];
        //     $status = "<span class='badge badge-secondary'>Menunggu</span>";
        //     if (!empty($item->approved_at)) {
        //         $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
        //     }
        //     if (!empty($item->rejected_at)) {
        //         $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
        //     }
        //     $col['url'] = route("ess.attendance.index")."?a=detail&id=".$item->id;
        //     $col['id'] = $item->id;
        //     $col['type'] = "attendance";
        //     $col['ref_num'] = $item->ref_num;
        //     $col['request_type'] = "Koreksi Kehadiran - Jam Masuk & Jam Keluar";
        //     $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
        //     $col['request_detail'] = "<div class='d-flex flex-column gap-2'>";
        //     $col['request_detail'] .= "<span>Clock In : ".($item->last_clock_in ??  "N/A")." <i class='fa fa-arrow-right text-dark'></i> $item->clock_in</span>";
        //     $col['request_detail'] .= "<span>Clock In : ".($item->last_clock_out ??  "N/A")." <i class='fa fa-arrow-right text-dark'></i> $item->clock_out</span>";
        //     $col['request_detail'] .= "</div>";
        //     $col['date'] = $item->created_at;
        //     $col['status'] = $status;
        //     $col['action'] = '<button type="button" class="btn btn-icon btn-sm">
        //                         <i class="fi fi-rr-menu-dots-vertical"></i>
        //                     </button>';
        //     $myRequest[] = $col;
        // }

        // $myRequest = collect($myRequest)->sortByDesc("date");

        // $posId = $personel->position_id;

        // $posChild = Kjk_comp_position::where("parent_id", $posId)->get();

        // $under = Hrd_employee::where("company_id", Auth::user()->company_id)
        //     ->whereIn("position_id", $posChild->pluck("id"))->get();

        // $empUnder = [];
        // foreach($under as $item){
        //     $empUnder[$item->id] = $item;
        // }
        // // dd($under, $posChild, $posId);

        // $approvalLeave = Att_leave_request::whereIn("emp_id", $under->pluck("id"))
        //     ->whereNull("approved_at")
        //     ->whereNull("rejected_at")
        //     ->get();
        // $approvalOvertime = Att_overtime_record::whereIn("emp_id", $under->pluck("id"))
        //     ->whereNull("approved_at")
        //     ->whereNull("rejected_at")
        //     ->get();

        // $approvalCorr = Personel_attendance_correction_request::whereIn("emp_id", $under->pluck("id"))
        //     ->whereNull("approved_at")
        //     ->whereNull("rejected_at")
        //     ->orderBy("id", "desc")
        //     ->get();

        // $approval = [];
        // foreach($leaveRequest as $item){
        //     $col = [];
        //     $status = "<span class='badge badge-secondary'>Menunggu</span>";
        //     if (!empty($item->approved_at)) {
        //         $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
        //     }
        //     if (!empty($item->rejected_at)) {
        //         $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
        //     }
        //     $col['request_type'] = empty($item->cr) ? "Cuti - Cuti" : "Koreksi Kehadiran - Cuti";
        //     $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
        //     $col['request_detail'] = date("d-m-Y", strtotime($item->start_date))." - ".date("d-m-Y", strtotime($item->end_date));
        //     $col['status'] = $status;
        //     $col['ref_num'] = $item->ref_num;
        //     $col['id'] = $item->id;
        //     $col['emp_id'] = $item->emp_id;
        //     $col['type'] = "leave";
        //     $approval[] = $col;
        // }

        // foreach($overtimeRequest as $item){
        //     $col = [];
        //     $status = "<span class='badge badge-secondary'>Menunggu</span>";
        //     if (!empty($item->approved_at)) {
        //         $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
        //     }
        //     if (!empty($item->rejected_at)) {
        //         $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
        //     }
        //     $col['request_type'] = "Lembur";
        //     $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
        //     $col['request_detail'] = date("d-m-Y", strtotime($item->overtime_date))." (".date("H:i", strtotime($item->overtime_start_time))."-".date("H:i", strtotime($item->overtime_end_time)).")";
        //     $col['ref_num'] = $item->ref_num;
        //     $col['status'] = $status;
        //     $col['id'] = $item->id;
        //     $col['emp_id'] = $item->emp_id;
        //     $col['type'] = "overtime";
        //     $approval[] = $col;
        // }

        // foreach($approvalCorr as $item){
        //     $col = [];
        //     $status = "<span class='badge badge-secondary'>Menunggu</span>";
        //     if (!empty($item->approved_at)) {
        //         $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
        //     }
        //     if (!empty($item->rejected_at)) {
        //         $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
        //     }
        //     $col['url'] = route("ess.attendance.index")."?a=detail&id=".$item->id;
        //     $col['id'] = $item->id;
        //     $col['type'] = "attendance";
        //     $col['ref_num'] = $item->ref_num;
        //     $col['request_type'] = "Koreksi Kehadiran - Jam Masuk & Jam Keluar";
        //     $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
        //     $col['request_detail'] = "<div class='d-flex flex-column gap-2'>";
        //     $col['request_detail'] .= "<span>Clock In : ".($item->last_clock_in ??  "N/A")." <i class='fa fa-arrow-right text-dark'></i> $item->clock_in</span>";
        //     $col['request_detail'] .= "<span>Clock In : ".($item->last_clock_out ??  "N/A")." <i class='fa fa-arrow-right text-dark'></i> $item->clock_out</span>";
        //     $col['request_detail'] .= "</div>";
        //     $col['date'] = $item->created_at;
        //     $col['status'] = $status;
        //     $col['action'] = '<button type="button" class="btn btn-icon btn-sm">
        //                         <i class="fi fi-rr-menu-dots-vertical"></i>
        //                     </button>';
        //     $col['emp_id'] = $item->emp_id;
        //     $approval[] = $col;
        // }

        // $approval = collect($approval)->sortByDesc("date");

        // dd($posChild->pluck("name"));

        $data = $this->getApprovalList($request);

        return view("_ess.approval.index", $data);
    }

    function getPosChild($posId){
        $childs = [];
        $child = Kjk_comp_position::where("parent_id", $posId)->get();
        foreach($child as $item){
            $hasChild = Kjk_comp_position::where("parent_id", $item->id)->count();
            $childs[] = $item->id;

            if($hasChild > 0){
                $childs = array_merge($childs, $this->getPosChild($item->id));
            }
        }

        return $childs;
    }

    function getApprovalList(Request $request){
        $emp_id = $request->emp ?? Auth::user()->emp_id;
        $user_id = $request->user_id ?? Auth::user()->id;
        $user = User::find($user_id);

        $personel = Hrd_employee::find($emp_id);

        $myRequest = [];

        $leaveRequest = Att_leave_request::where("emp_id", $personel->id)
            ->get();

        $overtimeRequest = Att_overtime_record::where("emp_id", $personel->id)
            ->get();

        $attCorr = Personel_attendance_correction_request::where("emp_id", $personel->id)
            ->orderBy("id", "desc")
            ->get();

        foreach($leaveRequest as $item){
            $col = [];
            $status = "<span class='badge badge-secondary'>Menunggu</span>";
            if (!empty($item->approved_at)) {
                $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
            }
            if (!empty($item->rejected_at)) {
                $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
            }
            $col['url'] = route("ess.leave.index")."?a=detail&id=".$item->id;
            $col['request_type'] = empty($item->cr) ? "Cuti - Cuti" : "Koreksi Kehadiran - Cuti";
            $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
            $col['request_detail'] = date("d-m-Y", strtotime($item->start_date))." - ".date("d-m-Y", strtotime($item->end_date));
            $col['ref_num'] = $item->ref_num;
            $col['status'] = $status;
            $col['id'] = $item->id;
            $col['date'] = $item->created_at;
            $col['type'] = "leave";
            $myRequest[] = $col;
        }

        foreach($overtimeRequest as $item){
            $col = [];
            $status = "<span class='badge badge-secondary'>Menunggu</span>";
            if (!empty($item->approved_at)) {
                $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
            }
            if (!empty($item->rejected_at)) {
                $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
            }
            $col['url'] = route("ess.overtime.detail", $item->id);
            $col['request_type'] = "Lembur";
            $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
            $col['request_detail'] = date("d-m-Y", strtotime($item->overtime_date))." (".date("H:i", strtotime($item->overtime_start_time))."-".date("H:i", strtotime($item->overtime_end_time)).")";
            $col['ref_num'] = $item->ref_num;
            $col['status'] = $status;
            $col['id'] = $item->id;
            $col['date'] = $item->created_at;
            $col['type'] = "overtime";
            $myRequest[] = $col;
        }

        foreach($attCorr as $item){
            $col = [];
            $status = "<span class='badge badge-secondary'>Menunggu</span>";
            if (!empty($item->approved_at)) {
                $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
            }
            if (!empty($item->rejected_at)) {
                $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
            }
            $col['url'] = route("ess.attendance.index")."?a=detail&id=".$item->id;
            $col['id'] = $item->id;
            $col['type'] = "attendance";
            $col['ref_num'] = $item->ref_num;
            $col['request_type'] = "Koreksi Kehadiran - Jam Masuk & Jam Keluar";
            $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
            $col['request_detail'] = "<div class='d-flex flex-column gap-2'>";
            $col['request_detail'] .= "<span>Clock In : ".($item->last_clock_in ??  "N/A")." <i class='fa fa-arrow-right text-dark'></i> $item->clock_in</span>";
            $col['request_detail'] .= "<span>Clock In : ".($item->last_clock_out ??  "N/A")." <i class='fa fa-arrow-right text-dark'></i> $item->clock_out</span>";
            $col['request_detail'] .= "</div>";
            $col['date'] = $item->created_at;
            $col['status'] = $status;
            $col['action'] = '<button type="button" class="btn btn-icon btn-sm">
                                <i class="fi fi-rr-menu-dots-vertical"></i>
                            </button>';
            $myRequest[] = $col;
        }

        $myRequest = collect($myRequest)->sortByDesc("date");

        $posId = $personel->position_id;

        $posChildId = $this->getPosChild($posId);

        $posChild = Kjk_comp_position::whereIn("id", $posChildId)->get();

        $under = Hrd_employee::where("company_id", $user->company_id)
            ->whereIn("position_id", $posChild->pluck("id"))->get();

        $empUnder = [];
        foreach($under as $item){
            $empUnder[$item->id] = $item;
        }
        // dd($under, $posChild, $posId);

        $approvalLeave = Att_leave_request::whereIn("emp_id", $under->pluck("id"))
            ->whereNull("approved_at")
            ->whereNull("rejected_at")
            ->get();
        $approvalOvertime = Att_overtime_record::whereIn("emp_id", $under->pluck("id"))
            ->whereNull("approved_at")
            ->whereNull("rejected_at")
            ->get();

        $approvalCorr = Personel_attendance_correction_request::whereIn("emp_id", $under->pluck("id"))
            ->whereNull("approved_at")
            ->whereNull("rejected_at")
            ->orderBy("id", "desc")
            ->get();

        $approval = [];
        foreach($leaveRequest as $item){
            $col = [];
            $status = "<span class='badge badge-secondary'>Menunggu</span>";
            if (!empty($item->approved_at)) {
                $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
            }
            if (!empty($item->rejected_at)) {
                $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
            }
            $col['request_type'] = empty($item->cr) ? "Cuti - Cuti" : "Koreksi Kehadiran - Cuti";
            $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
            $col['request_detail'] = date("d-m-Y", strtotime($item->start_date))." - ".date("d-m-Y", strtotime($item->end_date));
            $col['status'] = $status;
            $col['ref_num'] = $item->ref_num;
            $col['id'] = $item->id;
            $col['emp_id'] = $item->emp_id;
            $col['type'] = "leave";
            $approval[] = $col;
        }

        foreach($overtimeRequest as $item){
            $col = [];
            $status = "<span class='badge badge-secondary'>Menunggu</span>";
            if (!empty($item->approved_at)) {
                $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
            }
            if (!empty($item->rejected_at)) {
                $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
            }
            $col['request_type'] = "Lembur";
            $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
            $col['request_detail'] = date("d-m-Y", strtotime($item->overtime_date))." (".date("H:i", strtotime($item->overtime_start_time))."-".date("H:i", strtotime($item->overtime_end_time)).")";
            $col['ref_num'] = $item->ref_num;
            $col['status'] = $status;
            $col['id'] = $item->id;
            $col['emp_id'] = $item->emp_id;
            $col['type'] = "overtime";
            $approval[] = $col;
        }

        foreach($approvalCorr as $item){
            $col = [];
            $status = "<span class='badge badge-secondary'>Menunggu</span>";
            if (!empty($item->approved_at)) {
                $status = "<span class='badge badge-outline badge-success'>Disetujui</span>";
            }
            if (!empty($item->rejected_at)) {
                $status = "<span class='badge badge-outline badge-danger'>Ditolak</span>";
            }
            $col['url'] = route("ess.attendance.index")."?a=detail&id=".$item->id;
            $col['id'] = $item->id;
            $col['type'] = "attendance";
            $col['ref_num'] = $item->ref_num;
            $col['request_type'] = "Koreksi Kehadiran - Jam Masuk & Jam Keluar";
            $col['request_date'] = date("d-m-Y", strtotime($item->created_at));
            $col['request_detail'] = "<div class='d-flex flex-column gap-2'>";
            $col['request_detail'] .= "<span>Clock In : ".($item->last_clock_in ??  "N/A")." <i class='fa fa-arrow-right text-dark'></i> $item->clock_in</span>";
            $col['request_detail'] .= "<span>Clock In : ".($item->last_clock_out ??  "N/A")." <i class='fa fa-arrow-right text-dark'></i> $item->clock_out</span>";
            $col['request_detail'] .= "</div>";
            $col['date'] = $item->created_at;
            $col['status'] = $status;
            $col['action'] = '<button type="button" class="btn btn-icon btn-sm">
                                <i class="fi fi-rr-menu-dots-vertical"></i>
                            </button>';
            $col['emp_id'] = $item->emp_id;
            $approval[] = $col;
        }

        $approval = collect($approval)->sortByDesc("date");

        $data = [
            "myRequest" => $myRequest,
            "approval" => $approval,
            "posChild" => $posChild,
            "empUnder" => $empUnder,
        ];

        return $data;
    }

    function approve($type, Request $request){
        if($type == "attendance"){
            $t = Personel_attendance_correction_request::class;
        } elseif($type == "leave"){
            $t = Att_leave_request::class;
        } elseif($type == "overtime"){
            $t = Att_overtime_record::class;
        }

        $data = $t::find($request->id);

        $emp = Hrd_employee::find($data->emp_id);

        if(!empty($data)){
            if($request->submit == "reject"){
                $data->rejected_at = date("Y-m-d H:i:s");
                $data->rejected_at = Auth::id();
                $data->rejected_notes = $request->rejected_notes;
            } else {
                $data->approved_at = date("Y-m-d H:i:s");
                $data->approved_at = Auth::id();

                if($type == "attendance"){
                    $actual = [];
                    $actual['actual_timin'] = $data->clock_in;
                    $actual['actual_timout'] = $data->clock_out;
                    $actual['actual_break_start'] = $data->clock_out;
                    $actual['actual_break_end'] = $data->clock_out;
                    $this->correctionAttendance($data->emp_id, $data->date, $actual);
                }
            }
            $data->save();
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Pengajuan $emp->emp_name berhasil di".($request->submit == "reject" ? "tolak" : "setujui"),
                "bg" => "bg-".($request->submit == "reject" ? "danger" : "success")
            ],
            "tab" => "tab_approval"
        ]);
    }

    function correctionAttendance($emp_id, $date, $actual){
        $emp_id = $emp_id;

        $reg = \App\Models\Att_employee_registration::where("emp_id", $emp_id)->first();

        $att_record =  \App\Models\Att_reason_record::where("emp_id", $emp_id)
            ->where("att_date", $date)
            ->first();

        $pr = \App\Models\Att_periode::where("company_id", Session::get('company_id'))
            ->where(function($q) use($date){
                $q->where("start_date", "<=", $date);
                $q->where("end_date", ">=", $date);
            })->get();

        $wgSch = \App\Models\Att_workgroup_schedule::where("workgroup", $reg->workgroup ?? null)
            ->whereIn("periode", $pr->pluck("id"))
            ->get();

        $schedules = [];
        foreach($wgSch as $item){
            $schedules = array_merge($schedules, $item->detail ?? []);
        }

        $sch = collect($schedules)->where("date", $date)->first();

        if(empty($att_record)){
            $att_record = new \App\Models\Att_reason_record();
            $att_record->emp_id = $emp_id;
            $att_record->att_date = $date;
            $att_record->shift_id = $sch['shift_id'];
            $shift = \App\Models\Att_shift::find($att_record->shift_id);
            $att_record->day_code = $shift->day_code;
        }

        $att_record->timin = $actual['actual_timin'];

        $att_record->timout = $actual['actual_timout'];

        // $att_record->break_start = $actual['actual_break_start'] ?? $att_record->break_start;

        // $att_record->break_end = $actual['actual_break_end'] ?? $att_record->break_end;

        $rtype = \App\Models\Att_reason_type::where("code", '00')
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

        $day_code = $att_record->day_code ?? null;
        $shift_code = $att_record->shift_id ?? null;

        $_shift = \App\Models\Att_shift::find($att_record->shift_id);

        $schedule_in = $_shift->schedule_in;
        $schedule_out = $_shift->schedule_out;

        $reason_id = "";
        $col = [];

        if ($time_in != "" && $time_out != "") {
            $reason_id = 1;
        }

        $date = $date;

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

        if(isset($holidays[$date])){
            $att_record['is_holiday'] = 1;
            $att_record['holiday_id'] = $holidays[$date];
        }

        $att_record->save();
    }
}
