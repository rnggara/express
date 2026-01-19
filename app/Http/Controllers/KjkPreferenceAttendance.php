<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Att_holiday;
use App\Models\Att_holiday_category;
use App\Models\Att_periode;
use App\Models\Att_pref;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Functions as FnHelper;
use App\Models\Att_day_code;
use App\Models\Att_leave;
use App\Models\Att_leave_category;
use App\Models\Att_leave_grant_type;
use App\Models\Att_machine_name;
use App\Models\Att_machine_type;
use App\Models\Att_ovt_group;
use App\Models\Att_ovt_group_calculation;
use App\Models\Att_ovt_index;
use App\Models\Att_reason_name;
use App\Models\Att_ovt_rounded;
use App\Models\Att_reason_condition;
use App\Models\Att_reason_group;
use App\Models\Att_reason_type;
use App\Models\Att_report_type;
use App\Models\Att_workgroup_patern;
use App\Models\Att_workgroup;
use App\Models\Att_shift;
use App\Models\Att_employee_registration;
use App\Models\Att_leave_employee;
use Illuminate\Support\Facades\Validator;

class KjkPreferenceAttendance extends Controller
{

    private $dirView;
    public function __construct() {
        $this->dirView = "_crm.preferences.attendance";
    }

    // reason name
    public function reason_name_index(Request $request){
        $reason_names = Att_reason_name::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->orderBy('status', 'desc')->get();

        $reason_types = Att_reason_type::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        $report_types = Att_report_type::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        $leave_types = Att_leave_category::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        $day_codes = Att_day_code::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        $shifts = Att_shift::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        $rconditions = Att_reason_condition::where("company_id", Session::get("company_id"))
            ->orderBy("status", "desc")
            ->orderBy("process_sequence")
            ->get();

        $rgroup = Att_reason_group::where("company_id", Session::get("company_id"))
            ->get();

        if($request->a == "reason_group"){
            $othGroup = Att_reason_group::where("company_id", Session::get("company_id"))
                ->where("id", "!=", $request->id)
                ->get();

            $gr = $rgroup->where("id", $request->id)->first();

            $othReason = [];
            foreach($othGroup as $item){
                $othReason = array_merge($othReason, $item->reasons ?? []);
            }

            $rr = Att_reason_name::where(function($q) {
                $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                $q->orWhere('is_default', 1);
            })->where('status', 1)->whereNotIn('id', $othReason)->orderBy('status', 'desc')->get();

            $view = view("$this->dirView.reason.reason_group", compact("gr", 'rr'))->render();

            return json_encode([
                "view" => $view
            ]);
        }

        $rname = $reason_names->where('status', 1)->pluck("reason_name", "id");

        return view("$this->dirView.reason.index", compact('reason_names', 'rname', 'reason_types', 'report_types', 'day_codes', 'shifts', 'rconditions', 'leave_types', 'rgroup'));
    }

    function reason_name_detail($type, $id){
        $response = [];
        if($type == "name"){
            $reason = Att_reason_name::find($id);
            $view = view("$this->dirView.reason.detail_name", compact('reason'))->render();

            $response = [
                "view" => $view,
            ];
        } else {
            $reason_types = Att_reason_type::where(function($q) {
                $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                $q->orWhere('is_default', 1);
            })->get();

            $report_types = Att_report_type::where(function($q) {
                $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                $q->orWhere('is_default', 1);
            })->get();

            $day_codes = Att_day_code::where(function($q) {
                $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                $q->orWhere('is_default', 1);
            })->get();

            $shifts = Att_shift::where(function($q) {
                $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                $q->orWhere('is_default', 1);
            })->get();

            $reason_names = Att_reason_name::where(function($q) {
                $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                $q->orWhere('is_default', 1);
            })->get();

            $condition = Att_reason_condition::find($id);

            $leave_types = Att_leave_category::where(function($q) {
                $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                $q->orWhere('is_default', 1);
            })->get();

            $rconditions = Att_reason_condition::where("company_id", Session::get("company_id"))
                ->orderBy("status", "desc")
                ->orderBy("process_sequence")
                ->get();

            $bgColor = ['schedule' => "primary", "shift_code" => "info", "time_in" => "warning", "time_out" => "danger",
                "late_in" => "light-warning", "fast_out" => "light-danger", "overtime" => "light-primary"];

            $view = view("$this->dirView.reason.detail_condition", compact('condition', 'reason_types', 'report_types', 'rconditions', 'day_codes', 'shifts', 'reason_names', 'bgColor', 'leave_types'))->render();

            $response = [
                "view" => $view,
            ];
        }

        return json_encode($response);
    }

    function reason_name_delete($type, $id){
        if($type == "name"){
            $reason = Att_reason_name::find($id);
            if(empty($reason)){
                $message = "Data not found";
            } else {
                $reason->delete();
                $message = "Data has been deleted";
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => $message,
                    "bg" => "bg-danger"
                ],
                "tab" => "tab_reason_name"
            ]);
        } elseif($type == "condition") {
            $reason = Att_reason_condition::find($id);
            if(empty($reason)){
                $message = "Data not found";
            } else {
                $reason->delete();
                $message = "Data has been deleted";
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => $message,
                    "bg" => "bg-danger"
                ],
                "tab" => "tab_reason_condition"
            ]);
        } elseif($type == "group-del"){
            $ex = explode("_", $id);
            $gr = Att_reason_group::find($ex[0]);
            $rr = Att_reason_name::find($ex[1]);
            $key = array_search($ex[1], $gr->reasons);
            $r = $gr->reasons;
            unset($r[$key]);
            $r = array_values($r);
            $gr->reasons = $r;
            $gr->save();
            return redirect()->back()->with([
                "toast" => [
                    "message" => "$rr->reason_name removed from $gr->group_name",
                    "bg" => "bg-danger"
                ],
                "tab" => "tab_reason_grouping"
            ]);
        } elseif($type == "group-non"){
            $rr = Att_reason_name::find($id);
            $rr->status = 0 ;
            $rr->save();
            return redirect()->back()->with([
                "toast" => [
                    "message" => "$rr->reason_name telah di Non Aktifkan",
                    "bg" => "bg-danger"
                ],
                "tab" => "tab_reason_grouping"
            ]);
        }
    }

    function reason_name_store(Request $request){
        $type = $request->type;
        if($type == "name"){
            $validator = Validator::make($request->all(), [
                "reason_id" => "required",
                "reason_name" => "required",
                "reason_color" => "required"
            ],[
                "reason_id.required" => "Reason ID is required",
                "reason_name.required" => "Reason Name is required",
                "reason_color.required" => "Reason Color is required",
            ]);

            if($validator->fails()){
                $dataR = [
                    'tab' => "tab_reason_name"
                ];
                if(empty($request->id)){
                    $dataR['modal'] = "modal_add_reason_name";
                } else {
                    $dataR['drawer'] = $request->id;
                }
                return redirect()->back()->withErrors($validator)
                    ->with($dataR);
            }

            if(empty($request->id)){
                $conflict = Att_reason_name::where(function($q) {
                        $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                        $q->orWhere('is_default', 1);
                    })
                    ->where("reason_id", $request->reason_id)
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "reason_id" => "Conflict Reason ID"
                    ]);
                }
            }

            $reason = Att_reason_name::findOrNew($request->id);
            $reason->reason_id = strtoupper($request->reason_id);
            $reason->reason_name = $request->reason_name;
            $reason->color = $request->reason_color;
            $reason->show_dashboard = $request->show_dashboard ?? 0;
            if(empty($request->id)){
                $reason->company_id = Session::get('company_id');
                $reason->created_by = Auth::id();
            }

            if(!empty($request->id)){
                $reason->status = $request->status ?? 0;
            }

            $reason->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Sucessfully added Reason Name",
                    "bg" => "bg-success"
                ],
                "tab" => "tab_reason_name",
                "type" => $type,
                "drawer" => $request->id
            ]);
        } elseif($type == "condition") {
            $validator = Validator::make($request->all(), [
                'reason_name_id' => 'required',
                'reason_type' => 'required',
                'process_sequence' => 'required',
                'reason_sequence' => 'required',
                'report_type' => 'required',
            ],[
                "reason_name_id.required" => "Reason Name is required",
                "reason_type.required" => "Reason Type is required",
                "process_sequence.required" => "Process Sequence is required",
                "reason_sequence.required" => "Reason Sequence is required",
                "report_type.required" => "Report Type is required",
            ]);

            if($validator->fails()){
                $dataR = [
                    'tab' => "tab_reason_condition"
                ];
                if(empty($request->id)){
                    $dataR['modal'] = "modal_add_reason_condition";
                } else {
                    $dataR['drawer'] = $request->id;
                }
                return redirect()->back()->withErrors($validator)
                    ->with($dataR);
            }

            $reason_condition = Att_reason_condition::findOrNew($request->id);
            if(empty($request->id)){
                $reason_condition->company_id = Session::get("company_id");
                $reason_condition->created_by = Auth::id();
            }
            $reason_condition->conditions = $request->condition ?? [];
            $reason_condition->schedule_id = $request->schedule_id;
            $reason_condition->shift_code = $request->shift_code;
            $reason_condition->time_in = $request->time_in['time'];
            $reason_condition->time_in_condition = $request->time_in['condition'];
            $reason_condition->time_out = $request->time_out['time'];
            $reason_condition->time_out_condition = $request->time_out['condition'];
            $reason_condition->late_in = $request->late_in['time'];
            $reason_condition->late_in_condition = $request->late_in['condition'];
            $reason_condition->fast_out = $request->fast_out['time'];
            $reason_condition->fast_out_condition = $request->fast_out['condition'];
            $reason_condition->overtime = $request->overtime['time'];
            $reason_condition->overtime_condition = $request->overtime['condition'];
            $reason_condition->reason_name_id = $request->reason_name_id;
            $reason_condition->reason_type_id = $request->reason_type;
            $reason_condition->process_sequence = $request->process_sequence;
            $reason_condition->reason_sequence = $request->reason_sequence;
            $reason_condition->report_type_id = $request->report_type;
            $reason_condition->cut_leave = $request->cut_leave ?? 0;
            $reason_condition->ess = $request->ess ?? 0;
            $reason_condition->rp_detail = $request->rp_detail ?? null;
            $reason_condition->reason_pengganti = $request->reason_pengganti ?? 0;
            $reason_condition->leave_type = !empty($request->cut_leave) ? $request->leave_type : null;
            $reason_condition->leave_days = !empty($request->cut_leave) ? $request->leave_days : null;
            if(!empty($request->id)){
                $reason_condition->status = $request->status ?? 0;
            }

            $reason_condition->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => empty($request->id) ? "Sucessfully added Reason Condition" : "Data successfully Updated",
                    'bg' => "bg-success"
                ],
                "tab" => "tab_reason_condition",
                "type" => "condition",
                "drawer" => empty($request->id) ? null : $request->id
            ]);
        } elseif($type == "group") {
            $validator = Validator::make($request->all(), [
                "color" => "required",
                "group_name" => "required",
            ],[
                "color.required" => "Color is required",
                "group_name.required" => "Group Name is required",
            ]);

            if($validator->fails()){
                $dataR = [
                    'tab' => "tab_reason_grouping"
                ];
                if(empty($request->id)){
                    $dataR['modal'] = "modal_add_group";
                } else {
                    $dataR['drawer'] = $request->id;
                }
                return redirect()->back()->withErrors($validator)
                    ->with($dataR);
            }

            if(empty($request->id)){
                $conflict = Att_reason_name::where(function($q) {
                        $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                        $q->orWhere('is_default', 1);
                    })
                    ->where("reason_id", $request->reason_id)
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "reason_id" => "Conflict Reason ID"
                    ]);
                }
            }

            $reason = Att_reason_group::findOrNew($request->id);
            $reason->group_name = $request->group_name;
            $reason->color = $request->color;
            if(empty($request->id)){
                $reason->company_id = Session::get('company_id');
                $reason->created_by = Auth::id();
            }

            if(!empty($request->id)){
                $reason->status = $request->status ?? 0;
            }

            $reason->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Sucessfully added Group Reason",
                    "bg" => "bg-success"
                ],
                "tab" => "tab_reason_grouping",
                "type" => $type,
                "drawer" => $request->id
            ]);
        } elseif($type == "group_add"){
            $group = Att_reason_group::find($request->id);
            $group->reasons = $request->reasons;
            $group->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Sucessfully added Reason to Group $group->group_name",
                    "bg" => "bg-success"
                ],
                "tab" => "tab_reason_grouping",
                "type" => $type,
            ]);
        }

        return redirect()->back();
    }

    public function workgroup_index(Request $request){

        $day_codes = Att_day_code::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        $color_palletes = \Config::get("constants.COLOR_PALLET");

        $shifts = Att_shift::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        $paterns = Att_workgroup_patern::where('company_id', Session::get("company_id"))
            ->get();

        $workgroups = Att_workgroup::where("company_id", Session::get("company_id"))
            ->get();

        if($request->a == "generate_id"){
            $tp = $request->t;
            if($tp == "patern"){
                $last_patern = Att_workgroup_patern::where('company_id', Session::get('company_id'))
                    ->orderBy("patern_id", "desc")
                    ->first();
                $num = 1;
                if(!empty($last_patern)){
                    $patern_id = $last_patern->patern_id;
                    $num = intval(substr($patern_id, 2)) + 1;
                }

                $patern_id = "PP". sprintf("%03d", $num);

                return json_encode([
                    'id' => $patern_id
                ]);
            }

            if($tp == "workgroup"){
                $last_workgroup = Att_workgroup::where('company_id', Session::get('company_id'))
                    ->orderBy("workgroup_id", "desc")
                    ->first();
                $num = 1;
                if(!empty($last_workgroup)){
                    $workgroup_id = $last_workgroup->workgroup_id;
                    $num = intval(substr($workgroup_id, 2)) + 1;
                }

                $workgroup_id = "WG". sprintf("%03d", $num);

                return json_encode([
                    'id' => $workgroup_id
                ]);
            }
        }

        return view("$this->dirView.workgroup.index", compact("day_codes", 'color_palletes', 'shifts', 'paterns', 'workgroups'));
    }

    public function workgroup_detail($type, $id){
        $response = [];

        if($type == "shift"){
            $day_codes = Att_day_code::where(function($q) {
                $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                $q->orWhere('is_default', 1);
            })->get();

            $color_palletes = \Config::get("constants.COLOR_PALLET");

            $shift = Att_shift::find($id);

            $view = view("$this->dirView.workgroup.detail_shift", compact("day_codes", 'color_palletes', 'shift'));
        } elseif($type == "patern"){
            $patern = Att_workgroup_patern::find($id);

            $shifts = Att_shift::where(function($q) {
                $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                $q->orWhere('is_default', 1);
            })->get();

            $shift_data = [];
            foreach($shifts as $item){
                $col = [];
                $col['id'] = $item->id;
                $col['shift_id'] = $item->shift_id;
                $col['shift_color'] = $item->shift_color;
                $shift_data[$item->id] = $col;
            }

            $view = view("$this->dirView.workgroup.detail_patern", compact('patern', 'shift_data'));
        } else {
            $workgroup = Att_workgroup::find($id);

            $paterns = Att_workgroup_patern::where('company_id', Session::get("company_id"))
                ->get();

            $view = view("$this->dirView.workgroup.detail_workgroup", compact('workgroup', 'paterns'));

        }

        $response = [
            "view" => $view->render()
        ];

        return json_encode($response);
    }

    public function workgroup_delete($type, $id){
        $tab = "tab_shift";
        if($type == "shift"){
            $data = Att_shift::find($id);
            $tab = "tab_shift";
        } elseif($type == "patern"){
            $data = Att_workgroup_patern::find($id);
            $tab = "tab_patern";
        } else {
            $data = Att_workgroup::find($id);
            $tab = "tab_workgroup";
        }

        if(empty($data)){
            $message = "Data not found";
        } else {
            $data->delete();
            $message = "Data has been deleted";
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => $message,
                "bg" => "bg-danger"
            ],
            "tab" => $tab
        ]);
    }

    public function workgroup_store(Request $request){
        $type = $request->type;
        if($type == "shift"){
            $validator = Validator::make($request->all(), [
                "shift_id" => "required",
                "shift_name" => "required",
                "day_code" => "required",
                "shift_color" => "required",
            ], [
                "shift_id.required" => "Shift ID is Required",
                "shift_name.required" => "Shift Name is Required",
                "day_code.required" => "Day Code is Required",
                "shift_color.required" => "Shift Color is Required",
            ]);

            if($validator->fails()){
                $dataR = [
                    "tab" => "tab_shift"
                ];
                if(empty($request->id)){
                    $dataR['modal'] = "modal_add_shift";
                } else {
                    $dataR['drawer'] = $request->id;
                }
                return redirect()->back()->withErrors($validator)
                    ->with($dataR);
            }

            if(empty($request->id)){
                $conflict = Att_shift::where(function($q) {
                        $q->where("company_id", Session::get('company_id'));
                        $q->orWhere('is_default', 1);
                    })
                    ->where("shift_id", $request->shift_id)
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "shift_id" => "Conflict Shift ID"
                    ])->with([
                        "modal" => "modal_add_shift"
                    ]);
                }
            }

            $shift = Att_shift::findOrNew($request->id);
            if(empty($request->id)){
                $shift->company_id = Session::get('company_id');
                $shift->created_by = Auth::id();
            }

            $shift->shift_id = $request->shift_id;
            $shift->shift_name = $request->shift_name;
            $shift->day_code = $request->day_code;
            $shift->shift_color = $request->shift_color;
            $shift->schedule_in = $request->time_in;
            $shift->schedule_out = $request->time_out;
            $shift->add_break_shift = $request->add_break_shift ?? 0;
            $shift->break_shifts = empty($request->add_break_shift) ? null : $request->break_shift;
            $shift->automatic_overtime = $request->automatic_overtime ?? 0;
            $shift->overtime_in = $request->overtime_in;
            $shift->overtime_out = $request->overtime_out;

            if(!empty($request->id)){
                $shift->status = $request->status ?? 0;
            }

            $shift->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => empty($request->id) ? "Sucessfully added Shift" : "Data successfully Updated",
                    'bg' => "bg-success"
                ],
                "tab" => "tab_shift",
                "type" => $type,
                "drawer" => empty($request->id) ? null : $request->id
            ]);
        } elseif($type == "patern"){

            $validator = Validator::make($request->all(), [
                "patern_id" => "required",
                "patern_name" => "required",
                "shifting_type" => "required",
            ], [
                "patern_id.required" => "Patern ID is Required",
                "patern_name.required" => "Patern Name is Required",
                "shifting_type.required" => "Shifting Type is Required",
            ]);

            if($validator->fails()){
                $dataR = [
                    "tab" => "tab_patern"
                ];
                if(empty($request->id)){
                    $dataR['modal'] = "modal_add_patern";
                } else {
                    $dataR['drawer'] = $request->id;
                }
                return redirect()->back()->withErrors($validator)
                    ->with($dataR);
            }

            if(empty($request->id)){
                $conflict = Att_workgroup_patern::where(function($q) {
                        $q->where("company_id", Session::get('company_id'));
                    })
                    ->where("patern_id", $request->patern_id)
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "patern_id" => "Conflict Patern ID"
                    ])->with([
                        "modal" => "modal_add_patern"
                    ]);
                }
            }

            $patern = Att_workgroup_patern::findOrNew($request->id);
            if(empty($request->id)){
                $patern->company_id = Session::get('company_id');
                $patern->created_by = Auth::id();
            }

            $patern->patern_id = $request->patern_id;
            $patern->patern_name = $request->patern_name;
            $patern->type = $request->shifting_type;
            $patern->sequences = $request->sequences;

            if(!empty($request->id)){
                $patern->status = $request->status ?? 0;
            }

            $patern->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => empty($request->id) ? "Sucessfully added Patern" : "Data successfully Updated",
                    'bg' => "bg-success"
                ],
                "tab" => "tab_patern",
                "type" => $type,
                "drawer" => empty($request->id) ? null : $request->id
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                "workgroup_id" => "required",
                "workgroup_name" => "required",
                "start_date" => "required",
                "patern" => "required",
            ], [
                "workgroup_id.required" => "Workgroup ID is Required",
                "workgroup_name.required" => "Workgroup Name is Required",
                "start_date.required" => "Start Date is Required",
                "patern.required" => "Patern is Required",
            ]);

            if($validator->fails()){
                $dataR = [
                    "tab" => "tab_workgroup"
                ];
                if(empty($request->id)){
                    $dataR['modal'] = "modal_add_workgroup";
                } else {
                    $dataR['drawer'] = $request->id;
                }
                return redirect()->back()->withErrors($validator)
                    ->with($dataR);
            }

            if(empty($request->id)){
                $conflict = Att_workgroup::where(function($q) {
                        $q->where("company_id", Session::get('company_id'));
                    })
                    ->where("workgroup_id", $request->workgroup_id)
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "workgroup_id" => "Conflict Patern ID"
                    ])->with([
                        "modal" => "modal_add_workgroup"
                    ]);
                }
            }

            $wg = Att_workgroup::findOrNew($request->id);
            if(empty($request->id)){
                $wg->company_id = Session::get('company_id');
                $wg->created_by = Auth::id();
            }

            $wg->workgroup_id = $request->workgroup_id;
            $wg->workgroup_name = $request->workgroup_name;
            $wg->start_date = $request->start_date;
            $wg->patern = $request->patern;
            $wg->sequence = $request->sequence;
            $wg->replace_holiday_flag = $request->replace_holiday_flag ?? 0;

            if(!empty($request->id)){
                $wg->status = $request->status ?? 0;
            }

            $wg->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => empty($request->id) ? "Sucessfully added Workgroup" : "Data successfully Updated",
                    'bg' => "bg-success"
                ],
                "tab" => "tab_workgroup",
                "type" => $type,
                "drawer" => empty($request->id) ? null : $request->id
            ]);
        }
    }

    public function leave_index(){

        $categories = Att_leave_category::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->where("special", 1)->get();

        $reason_cond = Att_reason_condition::whereIn("leave_type", $categories->pluck("leave_used"))
            ->where("company_id", Session::get("company_id"))
            ->get();

        $grant_types = Att_leave_grant_type::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        $leaves = Att_leave::where("company_id", Session::get("company_id"))
            ->get();

        return view("$this->dirView.leave.index", compact('categories', 'leaves', 'grant_types', 'reason_cond'));
    }

    function leave_detail($id){
        $leave = Att_leave::find($id);

        $categories = Att_leave_category::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        $grant_types = Att_leave_grant_type::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        $reason_cond = Att_reason_condition::whereIn("leave_type", $categories->pluck("leave_used"))
            ->where("company_id", Session::get("company_id"))
            ->get();

        $view = view("$this->dirView.leave.detail", compact('categories', 'leave', 'grant_types', 'reason_cond'));

        $response = [
            "view" => $view->render()
        ];

        return json_encode($response);
    }

    function leave_delete($id){
        $reason = Att_leave::find($id);
        if(empty($reason)){
            $message = "Data not found";
        } else {
            $reason->delete();
            $message = "Data has been deleted";
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => $message,
                "bg" => "bg-danger"
            ],
        ]);
    }

    function leave_store(Request $request){

        $validator = Validator::make($request->all(), [
            'leave_group_id' => 'required',
            'leave_group_name' => 'required',
        ],[
            "leave_group_id.required" => "Leave Group ID is required",
            "leave_group_name.required" => "Leave Group Name is required",
        ]);

        if($validator->fails()){
            $dataR = [];
            if(empty($request->id)){
                $dataR['modal'] = "modal_add_leave_group";
            } else {
                $dataR['drawer'] = $request->id;
            }
            return redirect()->back()->withErrors($validator)
                ->with($dataR);
        }

        if(empty($request->id)){
            $conflict = Att_leave::where(function($q) {
                    $q->where("company_id", Session::get('company_id'));
                })
                ->where("leave_group_id", $request->leave_group_id)
                ->first();
            if(!empty($conflict)){
                return redirect()->back()->withErrors([
                    "leave_group_id" => "Conflict Leave Group ID"
                ])->with([
                    "modal" => "modal_add_leave_group"
                ]);
            }
        }

        $leave = Att_leave::findOrNew($request->id);
        if(empty($request->id)){
            $leave->company_id = Session::get('company_id');
            $leave->created_by = Auth::id();
            $leave->start_leave_periode = date("Y")."-01-01";
            $grant_type = Att_leave_grant_type::where("is_default", 1)->first();
            $leave->grant_leave_type = $grant_type->id ?? 1;
        }

        $_key = ['_token', "id", "annual_total_leaves", "long_total_leaves", "special_total_leaves", 'mass_leave', 'mass_leave_total', 'submit'];

        foreach($request->all() as $key => $item){
            if(!in_array($key, $_key)){
                $leave[$key] = $item ?? null;
            }
        }

        $leave->mass_leave = $request->mass_leave ?? null;
        $leave->mass_leave_total = $request->mass_leave_total ?? null;

        $leave->show_by_join_date = $request->show_by_join_date ?? null;
        $leave->show_by_cut_off = $request->show_by_cut_off ?? null;
        $leave->annual_pay_end_periode = $request->annual_pay_end_periode ?? null;
        $leave->long_pay_end_periode = $request->long_pay_end_periode ?? null;

        $annual_total_leaves = $request->annual_total_leaves ?? [];
        $long_total_leaves = $request->long_total_leaves ?? [];
        $special_total_leaves = $request->special_total_leaves ?? [];

        $atl = [];
        foreach($annual_total_leaves as $item){
            if($item['range_from'] != ""){
                $atl[] = $item;
            }
        }

        $ltl = [];
        foreach($long_total_leaves as $item){
            if($item['lama_kerja'] != ""){
                $ltl[] = $item;
            }
        }

        $stl = [];
        foreach($special_total_leaves as $item){
            if($item['reason'] != ""){
                $stl[] = $item;
            }
        }

        $leave->annual_total_leaves = $atl;
        $leave->long_total_leaves = $ltl;
        $leave->special_total_leaves = $stl;
        $leave->start_leave_periode = $request->start_leave_periode;
        $leave->grant_leave_type = $request->grant_leave_type;
        $leave->show_type = $request->show_type;

        if(!empty($request->id)){
            $leave->status = $request->status ?? 0;
        }

        $leave->save();

        if($request->submit == "apply"){
            $reg = Att_employee_registration::where("leavegroup", $leave->id)
                ->where("company_id", Session::get("company_id"))
                ->get();

            $_reg = [];
            foreach($reg as $item){
                $_reg[$item->emp_id] = $item;
            }

            $lemp = Att_leave_employee::where("leavegroup", $leave->id)
                ->whereIn("emp_id", $reg->pluck("emp_id"))->get();
            $dnow = date("Y-m-d");

            $kl = new KjkAttLeave();

            foreach($lemp as $item){
                $rr = $_reg[$item->emp_id] ?? [];
                if(!empty($rr)){
                    $emp = $rr->emp;
                    if(!empty($emp)){
                        $dyear = 0;
                        $d1 = date_create($emp->join_date ?? $emp->created_at);
                        $d2 = date_create($dnow);
                        $d3 = date_diff($d2, $d1);
                        $dyear = $d3->format("%y");

                        $annual = $leave->annual_total_leaves ?? [];
                        $long = $leave->long_total_leaves ?? [];
                        $special = $leave->special_total_leaves ?? [];
                        $mass = $leave->mass_leave ?? null;

                        if($item->type == "annual" && !empty($annual)){
                            $jatah = 0;
                            foreach($annual as $_item){
                                if($dyear >= $_item['range_from'] && $dyear <= $_item['range_to']){
                                    $jatah = $_item['total_leave'];
                                    break;
                                }
                            }
                            $item->jatah = $jatah;
                            $start_periode = $item->start_periode;
                            $end_periode = $kl->getEndPeriode($start_periode, $leave->annual_leave_expired);
                            $item->end_periode = $end_periode;
                            $item->minus_limit = $leave->annual_over_right;
                        }

                        if($item->type == "long" && !empty($long)){
                            $jatah = 0;
                            foreach($long as $_item){
                                if($dyear >= $_item['lama_kerja']){
                                    $jatah = $_item['total_leave'];
                                }
                            }
                            $item->jatah = $jatah;
                            $start_periode = $item->start_periode;
                            $end_periode = $kl->getEndPeriode($start_periode, $leave->long_expired);
                            $item->end_periode = $end_periode;
                        }

                        if($item->type == "special" && !empty($special)){
                            $jatah = 0;
                            foreach($special as $_item){
                                $jatah += $_item['total_leaves'];
                            }
                            $item->jatah = $jatah;
                            $start_periode = $item->start_periode;
                            $end_periode = $kl->getEndPeriode($start_periode, $leave->long_expired);
                            $item->end_periode = $end_periode;
                        }

                        if($item->type == "mass" && !empty($mass)){
                            $jatah = $leave->mass_leave_total;
                            $item->jatah = $jatah;
                            $start_periode = $item->start_periode;
                            $end_periode = $kl->getEndPeriode($start_periode, $leave->long_expired);
                            $item->end_periode = $end_periode;
                        }

                        $item->save();
                    }
                }
            }
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => empty($request->id) ? "Sucessfully added Leave Group" : "Data successfully Updated",
                'bg' => "bg-success"
            ],
            "drawer" => empty($request->id) ? null : $request->id
        ]);
    }

    public function overtime_index(){
        $prefs = Att_pref::where("company_id", Session::get('company_id'))->first();
        $rots = Att_ovt_rounded::where("company_id",Session::get('company_id'))->get();

        $indexes = Att_ovt_index::where("company_id", Session::get("company_id"))->get();

        $day_codes = Att_day_code::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })
        ->get();

        $groups = Att_ovt_group::where('company_id', Session::get('company_id'))->get();

        return view("$this->dirView.overtime.index", compact("prefs", 'rots', "indexes", 'day_codes', 'groups'));
    }

    function overtime_detail($type, $id){
        $response = [];
        if($type == "index"){
            $ovt = Att_ovt_index::find($id);

            $view = view("$this->dirView.overtime.detail_index", compact("ovt"))->render();
            $response = [
                "view" => $view
            ];
        } else {
            $ovt = Att_ovt_group::find($id);

            $indexes = Att_ovt_index::where("company_id", Session::get("company_id"))->get();

            $day_codes = Att_day_code::where(function($q) {
                $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                $q->orWhere('is_default', 1);
            })
            ->get();

            $view = view("$this->dirView.overtime.detail_group", compact("ovt", 'indexes', 'day_codes'))->render();
            $response = [
                "view" => $view
            ];
        }

        return json_encode($response);
    }

    function overtime_delete($type, $id){
        if($type == "index"){
            $reason = Att_ovt_index::find($id);
            if(empty($reason)){
                $message = "Data not found";
            } else {
                $reason->delete();
                $message = "Data has been deleted";
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => $message,
                    "bg" => "bg-danger"
                ],
                "tab" => "tab_overtime_index"
            ]);
        } else {
            $reason = Att_ovt_group::find($id);
            if(empty($reason)){
                $message = "Data not found";
            } else {
                $reason->delete();
                $message = "Data has been deleted";
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => $message,
                    "bg" => "bg-danger"
                ],
                "tab" => "tab_overtime_group"
            ]);
        }
    }

    function overtime_store(Request $request){
        if($request->type == "index"){
            $validator = Validator::make($request->all(), [
                'ovt_id' => 'required',
                'ovt_index' => 'required',
            ]);

            if($validator->fails()){
                $dataR = [
                    "tab" => "tab_overtime_index",
                    'type' => $request->type
                ];
                if(empty($request->id)){
                    $dataR['modal'] = "modal_add_overtime_index";
                } else {
                    $dataR['drawer'] = $request->id;
                }
                return redirect()->back()->withErrors($validator)
                    ->with($dataR);
            }

            if(empty($request->id)){
                $conflict = Att_ovt_index::where(function($q) {
                        $q->where("company_id", Session::get('company_id'));
                    })
                    ->where("ovt_id", $request->ovt_id)
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "ovt_id" => "Conflict Overtime Index ID"
                    ])->with([
                        "tab" => "tab_overtime_index",
                        "modal" => "modal_add_overtime_index"
                    ]);
                }
            }

            $ovt_index = Att_ovt_index::findOrNew($request->id);
            if(empty($request->id)){
                $ovt_index->company_id = Session::get('company_id');
                $ovt_index->created_by = Auth::id();
            }
            $ovt_index->ovt_id = $request->ovt_id;
            $ovt_index->ovt_index = $request->ovt_index;
            if(!empty($request->id)){
                $ovt_index->status = $request->status ?? 0;
            }

            $ovt_index->save();
            return redirect()->back()->with([
                "toast" => [
                    "message" => empty($request->id) ? "Sucessfully added Overtime Index" : "Data successfully Updated",
                    'bg' => "bg-success"
                ],
                "tab" => "tab_overtime_index",
                'type' => $request->type,
                "drawer" => empty($request->id) ? null : $request->id
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'group_id' => 'required',
                'day_code' => 'required',
                'group_name' => 'required',
            ], [
                'group_id.required' => "Group ID is required",
                'day_code.required' => "Day Code is required",
                'group_name.required' => "Group Name is required",
            ]);

            if($validator->fails()){
                $dataR = [
                    "tab" => "tab_overtime_group",
                    'type' => $request->type
                ];
                if(empty($request->id)){
                    $dataR['modal'] = "modal_add_overtime_group";
                } else {
                    $dataR['drawer'] = $request->id;
                }
                return redirect()->back()->withErrors($validator)
                    ->with($dataR);
            }

            if(empty($request->id)){
                $conflict = Att_ovt_group::where(function($q) {
                        $q->where("company_id", Session::get('company_id'));
                    })
                    ->where("group_id", $request->group_id)
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "group_id" => "Conflict Overtime Group ID"
                    ])->with([
                        "tab" => "tab_overtime_group",
                        "modal" => "modal_add_overtime_group"
                    ]);
                }
            }

            $ovt_group = Att_ovt_group::findOrNew($request->id);
            if(empty($request->id)){
                $ovt_group->company_id = Session::get('company_id');
                $ovt_group->created_by = Auth::id();
            }
            $ovt_group->group_id = $request->group_id;
            $ovt_group->group_name = $request->group_name;
            $ovt_group->day_code = $request->day_code;

            if(!empty($request->id)){
                $ovt_group->status = $request->status ?? 0;
            }

            $index_kemenaker = $request->index_kemenaker;
            $ovt_group->index_kemenaker = $index_kemenaker ?? 0;

            $ovt_group->save();

            Att_ovt_group_calculation::where("group_id", $ovt_group->id)->delete();

            if(!empty($index_kemenaker)){
                $calc = $request->calculation ?? [];
                foreach($calc as $item){
                    $cc = new Att_ovt_group_calculation();
                    $cc->group_id = $ovt_group->id;
                    $cc->ovt_index_id = $item['index'];
                    $cc->range_from = $item['range_start'];
                    $cc->range_to = $item['range_end'];
                    $cc->company_id = $ovt_group->company_id;
                    $cc->created_by = Auth::id();
                    $cc->save();
                }
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => empty($request->id) ? "Sucessfully added Overtime Group" : "Data successfully Updated",
                    'bg' => "bg-success"
                ],
                "tab" => "tab_overtime_group",
                'type' => $request->type,
                "drawer" => empty($request->id) ? null : $request->id
            ]);

        }
    }

    public function machine_type_index(Request $request){
        $programs = Att_machine_type::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })
        ->get();

        $names = Att_machine_name::where("company_id", Session::get('company_id'))->get();
        if($request->a == "program_type"){
            $program = $programs->where("id", $request->id)->first();
            $modalId = $request->modal;
            $detail = $names->where("id", $request->name)->first();

            if($program->program_type == "txt" || $program->program_type == "csv"){
                $view = view("$this->dirView.machine_type._format_text", compact('modalId', 'detail'));
            } else {
                $view = view("$this->dirView.machine_type._format_excel", compact('modalId', 'detail'));
            }

            return json_encode([
                "success" => true,
                "view" => $view->render()
            ]);
        }
        return view("$this->dirView.machine_type.index", compact("programs", "names"));
    }

    function machine_type_detail($type, $id){
        $response = [];
        if($type == "program"){
            $program = Att_machine_type::find($id);


            $view = view("$this->dirView.machine_type.detail_program", compact("program"))->render();
            $response = [
                "view" => $view
            ];
        } else {
            $machine_name = Att_machine_name::find($id);

            $programs = Att_machine_type::where(function($q) {
                $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                $q->orWhere('is_default', 1);
            })
            ->get();

            $view = view("$this->dirView.machine_type.detail_machine_name", compact("machine_name", 'programs'))->render();
            $response = [
                "view" => $view
            ];
        }

        return json_encode($response);
    }

    function machine_type_delete($type, $id){
        if($type == "program"){
            $reason = Att_machine_type::find($id);
            if(empty($reason)){
                $message = "Data not found";
            } else {
                $reason->delete();
                $message = "Data has been deleted";
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => $message,
                    "bg" => "bg-danger"
                ],
                "tab" => "tab_collect_program"
            ]);
        } else {
            $reason = Att_machine_name::find($id);
            if(empty($reason)){
                $message = "Data not found";
            } else {
                $reason->delete();
                $message = "Data has been deleted";
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => $message,
                    "bg" => "bg-danger"
                ],
                "tab" => "tab_machine_name"
            ]);
        }
    }

    function machine_type_store(Request $request){
        if($request->type == "program"){
            $validator = Validator::make($request->all(), [
                'program_id' => 'required',
                'program_name' => 'required',
                'program_type' => 'required',
            ]);

            if($validator->fails()){
                $dataR = [
                    "tab" => "tab_collect_program",
                    'type' => $request->type
                ];
                if(empty($request->id)){
                    $dataR['modal'] = "modal_add_collect_program";
                } else {
                    $dataR['drawer'] = $request->id;
                }
                return redirect()->back()->withErrors($validator)
                    ->with($dataR);
            }

            if(empty($request->id)){
                $conflict = Att_machine_type::where(function($q) {
                        $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
                        $q->orWhere('is_default', 1);
                    })
                    ->where("program_id", $request->program_id)
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "program_id" => "Conflict Program ID"
                    ])->with([
                        "tab" => "tab_collect_program",
                        "modal" => "modal_add_collect_program"
                    ]);
                }
            }

            $program = Att_machine_type::findOrNew($request->id);
            $program->program_id = $request->program_id;
            $program->program_name = $request->program_name;
            $program->program_type = $request->program_type;
            if(empty($request->id)){
                $program->company_id = Session::get('company_id');
                $program->created_by = Auth::id();
            }

            if(!empty($request->id)){
                $program->status = $request->status ?? 0;
            }

            $program->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => empty($request->id) ? "Sucessfully added Machine Methode" : "Data successfully Updated",
                    'bg' => "bg-success"
                ],
                "tab" => "tab_collect_program",
                'type' => $request->type,
                "drawer" => empty($request->id) ? null : $request->id
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'machine_id' => 'required',
                'machine_name' => 'required',
                'program_id' => 'required',
            ], [
                "machine_id.required" => "Machine ID is required",
                "machine_name.required" => "Machine Name is required",
                "program_id.required" => "Program ID is required",
            ]);

            if($validator->fails()){
                $dataR = [
                    "tab" => "tab_machine_name",
                    'type' => $request->type
                ];
                if(empty($request->id)){
                    $dataR['modal'] = "modal_add_machine";
                } else {
                    $dataR['drawer'] = $request->id;
                }
                return redirect()->back()->withErrors($validator)
                    ->with($dataR);
            }

            if(empty($request->id)){
                $conflict = Att_machine_name::where(function($q) {
                        $q->where("company_id", Session::get('company_id'));
                    })
                    ->where("machine_id", $request->machine_id)
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "machine_id" => "Conflict Machine ID"
                    ])->with([
                        "tab" => "tab_machine_name",
                        "modal" => "modal_add_machine"
                    ]);
                }
            }

            $program = Att_machine_name::findOrNew($request->id);
            $program->machine_id = $request->machine_id;
            $program->machine_name = $request->machine_name;
            $program->program_id = $request->program_id;
            $program->in_code = $request->in_code;
            $program->out_code = $request->out_code;
            $program->break_start_code = $request->break_start_code;
            $program->break_end_code = $request->break_end_code;
            $program->absensi_code = $request->code;
            $program->date_format = $request->date;
            $program->time_format = $request->time;
            $program->id_card = $request->key;
            $program->start_row = $request->start_row;
            $program->error_pathfile = $request->error_pathfile;
            if(empty($request->id)){
                $program->company_id = Session::get('company_id');
                $program->created_by = Auth::id();
            }

            if(!empty($request->id)){
                $program->status = $request->status ?? 0;
            }

            $program->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => empty($request->id) ? "Sucessfully added Machine Name" : "Data successfully Updated",
                    'bg' => "bg-success"
                ],
                "tab" => "tab_machine_name",
                'type' => $request->type,
                "drawer" => empty($request->id) ? null : $request->id
            ]);
        }
    }

    public function holiday_table_index(Request $request){

        $year = $request->year ?? date("Y");

        $holidays = Att_holiday::where("company_id", Session::get('company_id'))
            ->whereYear('holiday_date', $year)
            ->orderBy("holiday_date")
            ->get();

        $categories = Att_holiday_category::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        return view("$this->dirView.holiday_table.index", compact("holidays", "categories", 'year'));
    }

    public function holiday_table_detail($id){
        $holiday = Att_holiday::find($id);

        $categories = Att_holiday_category::where(function($q) {
            $q->where("company_id", Session::get('company_id'))->where("is_default", 0);
            $q->orWhere('is_default', 1);
        })->get();

        $view = view("$this->dirView.holiday_table.detail", compact('holiday', "categories"))->render();

        $response = [
            "view" => $view,
        ];

        return json_encode($response);
    }

    public function holiday_table_store(Request $request){
        $request->validate([
            "name" => "required",
            "holiday_date" => "required",
            "category" => "required",
        ], [
            "name.required" => "Name Holiday is Required",
            "holiday_date.required" => "Holiday Date is Required",
            "category.required" => "Holiday Category is Required",
        ]);

        $holiday = Att_holiday::findOrNew($request->id);
        if(empty($request->id)){
            $holiday->company_id = Session::get('company_id');
            $holiday->created_by = Auth::id();
        }

        $holiday->holiday_date = $request->holiday_date;
        $holiday->name = $request->name;
        $holiday->category_id = $request->category;
        $holiday->send_notification = $request->send_email ?? 0;
        if(!empty($request->id)){
            $holiday->status = $request->status ?? 0;
        }

        $holiday->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => empty($request->id) ? "Successfully added $holiday->name" : "Data successfuly Updated",
                "bg" => "bg-success"
            ],
            "detail" => $request->id
        ]);
    }

    public function holiday_table_delete($id){
        $holiday = Att_holiday::find($id);

        if(empty($holiday)){
            $message = "Data not found";
        } else {
            $holiday->delete();
            $message = "Data has been deleted";
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => $message,
                "bg" => "bg-danger"
            ]
        ]);
    }

    public function holiday_table_assign_year(Request $request){
        $year = $request->year;
        $url = "https://api-harilibur.vercel.app/api?year=$year";

        $holidays = FnHelper::http_request($url, "GET");

        $success = false;

        if($holidays['success']){
            $dates = collect($holidays['data']);

            $row = [];

            $cat = Att_holiday_category::where("is_default", 1)->first();

            $cat_id = $cat->id ?? 1;

            foreach($dates->where("is_national_holiday", true) as $item){
                $col = [];
                $col['holiday_date'] = $item['holiday_date'];
                $col['name'] = $item['holiday_name'];
                $col['category_id'] = $cat_id;
                $col['created_at'] = date("Y-m-d H:i:s");
                $col['created_by'] = Auth::user()->id;
                $col['company_id'] = Session::get('company_id');
                $row[] = $col;
            }

            Att_holiday::insert($row);

            $success = true;
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => $success ? "Successfully added Holidays" : "Failed",
                "bg" => $success ? "bg-success" : "bg-danger"
            ]
        ]);
    }

    public function periode_index(Request $request){
        $periodes = Att_periode::selectRaw("*, YEAR(start_date) as ye")->where("company_id", Session::get('company_id'))
            ->orderBy("start_date")
            ->get();

        if($request->a == "pname"){
            $list = [];
            $sdate = $request->start_date;
            $edate = $request->end_date;
            $i = 0;
            if(!empty($sdate) && !empty($edate)){
                $dn = date("Y-m", strtotime($sdate));
                while($dn <= $edate){
                    $col = [];
                    $col['id'] = $i++;
                    $col['text'] = date("F Y", strtotime($dn));
                    $list[] = $col;
                    $dn = date("Y-m", strtotime($dn." +1 month"));
                }
            }

            return json_encode([
                "results" => $list
            ]);
        }

        if($request->a == "months"){
            $list = [];
            $sdate = $request->start_date;
            $edate = $request->end_date;
            $i = 0;
            if(!empty($sdate) && !empty($edate)){
                $dn = date("Y-m", strtotime($sdate));
                while($dn <= $edate){
                    $col = [];
                    $col['id'] = $i++;
                    $col['text'] = date("F Y", strtotime($dn));
                    $list[] = $col;
                    $dn = date("Y-m", strtotime($dn." +1 month"));
                }
            }

            $sel = $request->pname;
            $months = $request->months - 1;

            $text = $list[$sel]['text'];

            $last_date = $edate;

            for ($i=0; $i < $months ; $i++) {
                $nper = ($i + 1) * count($list);
                $n = count($list) - 1;
                $nsdate = date("Y-m", strtotime($last_date));
                $nedate = date("Y-m", strtotime($nsdate." +$n month"));

                $nl = [];

                $j = 0;
                if(!empty($nsdate) && !empty($nedate)){
                    $dn = date("Y-m", strtotime($nsdate));
                    while($dn <= $nedate){
                        $col = [];
                        $col['id'] = $j++;
                        $col['text'] = date("F Y", strtotime($dn));
                        $nl[] = $col;
                        // $list[] = $col;
                        $dn = date("Y-m", strtotime($dn." +1 month"));
                    }
                }

                $sl = $nl[$sel]['text'] ?? $nsdate."-".$nedate;
                $text .= ", $sl";

                $last_date = $nedate;
            }

            return json_encode([
                "text" => $text,
                'list' => $list
            ]);
        }

        return view("$this->dirView.periode.index", compact("periodes"));
    }

    public function periode_detail($id){
        $periode = Att_periode::find($id);

        $view = view("$this->dirView.periode.detail", compact('periode'))->render();

        $response = [
            "view" => $view,
        ];

        return json_encode($response);
    }

    public function periode_store(Request $request){
        $request->validate([
            "start_date" => "required",
            "end_date" => "required",
        ], [
            "start_date.required" => "Start Date is Required",
            "end_date.required" => "End Date is Required",
        ]);

        $list = [];
        $sdate = $request->start_date;
        $edate = $request->end_date;
        $i = 0;
        if(!empty($sdate) && !empty($edate)){
            $dn = date("Y-m", strtotime($sdate));
            while($dn <= $edate){
                $col = [];
                $col['id'] = $i++;
                $col['text'] = date("F Y", strtotime($dn));
                $list[] = $col;
                $dn = date("Y-m", strtotime($dn." +1 month"));
            }
        }

        $sel = $request->pname;
        $months = $request->months - 1;
        $data = [];

        $cd = [];
        $cd['start_date'] = $sdate;
        $cd['end_date'] = $edate;
        $cd['pname'] = $list[$sel]['text'];
        $data[] = $cd;

        $last_date = $edate;

        for ($i=0; $i < $months ; $i++) {
            $nper = ($i + 1) * count($list);
            $n = count($list) - 1;
            $nsdate = date("Y-m", strtotime($last_date));
            $nedate = date("Y-m", strtotime($nsdate." +$n month"));
            $sd = explode("-", $sdate);
            $ed = explode("-", $edate);
            $nds = end($sd);
            $nde = end($ed);

            if($nds > 30){
                $nds = date("t", strtotime($nsdate));
            }

            if($nde > 30){
                $nde = date("t", strtotime($nedate));
            }

            $nl = [];

            $j = 0;
            if(!empty($nsdate) && !empty($nedate)){
                $dn = date("Y-m", strtotime($nsdate));
                while($dn <= $nedate){
                    $col = [];
                    $col['id'] = $j++;
                    $col['text'] = date("F Y", strtotime($dn));
                    $nl[] = $col;
                    // $list[] = $col;
                    $dn = date("Y-m", strtotime($dn." +1 month"));
                }
            }

            $cd = [];
            $cd['start_date'] = $nsdate."-$nds";
            $cd['end_date'] = $nedate."-$nde";
            $cd['pname'] = $nl[$sel]['text'];
            $data[] = $cd;

            $last_date = $cd['end_date'];
        }

        $conflict = Att_periode::where("company_id", Session::get('company_id'))
            ->where(function($q) use($sdate, $last_date){
                $q->whereBetween("start_date", [$sdate, $last_date]);
                $q->orWhereBetween("end_date", [$sdate, $last_date]);
            })->first();
        if(!empty($conflict)){
            return redirect()->back()->withErrors([
                "start_date" => "Conflict Date"
            ]);
        }

        $auto = $request->auto;
        if(empty($auto)){
            if(empty($request->pname)){
                return redirect()->back()->withErrors([
                    "pname" => "Periode Name is Required"
                ]);
            }

            $periode = Att_periode::findOrNew($request->id);
            if(empty($request->id)){
                $periode->company_id = Session::get('company_id');
                $periode->created_by = Auth::id();
            }

            $periode->start_date = $request->start_date;
            $periode->end_date = $request->end_date;
            $periode->name = $request->pname;
            if(!empty($request->id)){
                $periode->status = $request->status ?? 0;
            }

            $periode->save();
            $msg = "Successfully added $periode->name";
        } else {
            foreach($data as $item){
                $periode = new Att_periode();
                $periode->company_id = Session::get('company_id');
                $periode->created_by = Auth::id();
                $periode->start_date = $item['start_date'];
                $periode->end_date = $item['end_date'];
                $periode->name = $item['pname'];

                $periode->save();
            }

            $msg = "Successfully added Periods";
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => empty($request->id) ? $msg : "Data successfuly Updated",
                "bg" => "bg-success"
            ],
            "detail" => $request->id
        ]);
    }

    public function periode_delete($id){
        $holiday = Att_periode::find($id);

        if(empty($holiday)){
            $message = "Data not found";
        } else {
            $holiday->delete();
            $message = "Data has been deleted";
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => $message,
                "bg" => "bg-danger"
            ]
        ]);
    }

    public function preferences_index(){
        $prefs = Att_pref::where("company_id", Session::get('company_id'))->first();
        return view("$this->dirView.preferences.index", compact("prefs"));
    }

    public function preferences_store(Request $request){
        $prefs = Att_pref::findOrNew($request->id);

        if(empty($request->id)){
            $prefs->company_id = Session::get('company_id');
            $prefs->created_by = Auth::id();
        }

        if($request->type == "overtime"){
            $prefs->ovt_rounded_value = $request->ovt_rounded_value ?? 0;
            $prefs->ovt_round = $request->ovt_round ?? 0;
            $prefs->ovt_join_round = $request->ovt_join_round ?? 0;
            $prefs->ovt_split_calculate = $request->ovt_split_calculate ?? 0;
            $prefs->ovt_late_in = $request->ovt_late_in ?? 0;
            $prefs->ovt_permission = $request->ovt_permission ?? 0;
            $prefs->ovt_in_minutes = $request->ovt_in_minutes;
            $prefs->ovt_out_minutes = $request->ovt_out_minutes;

            $range_overtime = $request->range_overtime ?? [];
            Att_ovt_rounded::where("company_id", Session::get('company_id'))->delete();
            foreach($range_overtime as $item){
                $rotId = $item['id'] ?? null;
                $rot = Att_ovt_rounded::findOrNew($rotId);
                $rot->range_from = $item['range_from'];
                $rot->range_mid = $item['range_mid'];
                $rot->range_to = $item['range_to'];
                $rot->company_id = Session::get("company_id");
                $rot->created_by = Auth::id();

                $rot->save();
            }

        } else {
            foreach($request->all() as $key => $item){
                if(!in_array($key, [
                    "id",
                    "_token",
                    "type"
                ])) {
                    $exp = explode("/", $item);
                    $val = (count($exp) > 1) ? $this->formatDate($item) : $item;
                    $prefs[$key] = $val;
                }
            }
        }

        $prefs->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Sucessfully Update Data",
                "bg" => "bg-success"
            ],
            "tab" => $request->type
        ]);
    }

    function formatDate($date){
        $d = explode(" ", $date);
        $ymd = explode("/", $d[0]);
        krsort($ymd);
        $ymd = implode("-", $ymd);
        $d[0] = $ymd;
        $d = implode(" ", $d);

        return $d;
    }
}
