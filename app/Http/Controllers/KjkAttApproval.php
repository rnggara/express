<?php

namespace App\Http\Controllers;

use App\Models\Att_employee_registration;
use App\Models\Att_leave;
use App\Models\Att_leave_employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Att_overtime_record as Overtime;
use App\Models\Att_leave_request as Leave;
use App\Models\Att_overtime_leave_day;
use App\Models\Att_reason_record;
use App\Models\User;

class KjkAttApproval extends Controller
{
    function index(){

        $overtime = Overtime::where('company_id', Session::get("company_id"))
            ->orderBy('id', "desc")
            ->get();

        $request_leave = Leave::where('company_id', Session::get("company_id"))
            ->get();

        $uImg = User::whereIn("emp_id", $request_leave->pluck("emp_id"))
            ->pluck("user_img", "emp_id");

        return view("_attendance.approval.index", compact('overtime', 'request_leave', 'uImg'));
    }

    function approve(Request $request){
        $type = $request->type;

        if($type == "overtime"){
            $t = Overtime::find($request->id);
        } elseif($type == "leave"){
            $t = Leave::find($request->id);
        }

        if($request->submit == "approve"){
            $t->approved_at = date("Y-m-d H:i:s");
            $t->approved_by = Auth::id();
        } elseif($request->submit == "reject"){
            $t->rejected_at = date("Y-m-d H:i:s");
            $t->rejected_by = Auth::id();
        } elseif($request->submit == "cancel"){
            $t->approved_at = null;
            $t->approved_by = null;
            $t->rejected_at = null;
            $t->rejected_by = null;
            if($type == "leave"){
                $t->used_at = null;
                $t->used_by = null;
            }
        } elseif($request->submit == "delete"){
            $t->deleted_by = Auth::id();
        }

        $t->save();

        if($request->submit == "delete"){
            $t->delete();
        }

        if($type == "overtime"){
            if($t->paid == "days"){
                if($request->submit == "approve"){
                    $cl = new KjkAttOvertime();
                    $cl->grantChangeLeave($t);
                } else {
                    $tl = Att_overtime_leave_day::where("emp_id", $t->emp_id)
                        ->where("overtime_id", $t->id)->first();
                    if(!empty($tl)) $tl->delete();
                    $atl = Att_leave_employee::find($t->leave_id);
                    if(!empty($atl)) $atl->delete();

                    $rc = Att_reason_record::where("emp_id", $t->emp_id)
                        ->where('att_date', $t->overtime_date)
                        ->first();
                    if(!empty($rc)){
                        if($t->overtime_type == "in"){
                            $rc->ovtstartin = null;
                            $rc->ovtendin = null;
                            $rc->ovthoursin = null;
                            $rc->save();
                        } else {
                            $rc->ovtstart = null;
                            $rc->ovtend = null;
                            $rc->ovthours = null;
                            $rc->save();
                        }
                    }
                }
            }
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => $request->submit == "approve" ? "Successfully ".ucwords($request->submit)." Request" : "Successfully ".ucwords($request->submit)." Request",
                "bg" => $request->submit == "approve" ? "bg-success" : "bg-danger",
            ]
        ]);
    }
}
