<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\ESSApproval;
use App\Http\Controllers\ESSAttendance;
use App\Http\Controllers\ESSCash;
use App\Http\Controllers\ESSDashboard;
use App\Http\Controllers\ESSLetter;
use App\Http\Controllers\ESSLoan;
use App\Http\Controllers\ESSTeam;
use App\Http\Controllers\ESSLeave;
use App\Http\Controllers\KjkAttLeave;
use App\Http\Controllers\KjkAttOvertime;
use App\Http\Controllers\KjkAttRegistration;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hrd_employee;

class ESSController extends BaseController
{

    private $essDashboard, $kjkAtt, $essAtt, $essLoan, $essCash, $essLetter, $kjkOvt, $user, $essTeam, $essLeave, $essApproval;

    function __construct(Request $request){
        $this->essDashboard = new ESSDashboard();
        $this->kjkAtt = new KjkAttRegistration();
        $this->essAtt = new ESSAttendance();
        $this->essLoan = new ESSLoan();
        $this->essCash = new ESSCash();
        $this->essLetter = new ESSLetter();
        $this->kjkOvt = new KjkAttOvertime();
        $this->essTeam = new ESSTeam();
        $this->essLeave = new ESSLeave();
        $this->essApproval = new ESSApproval();
        $this->user = User::where("api_token", $request->token)->first();

        if(empty($this->user)){
            return $this->sendError("token tidak valid");
        }
    }

    function get_summary_data(Request $request){
        $emp_id = $this->user->emp_id;

        $tp = $request->tp ?? "mtd";

        $start_date = ($tp == "ytd") ? date("Y")."-01-01" : date("Y-m")."-01";
        $end_date = date("Y-m-d");

        $dataSummary = $this->essDashboard->getSummaryData($emp_id, $start_date, $end_date);

        $data = json_decode($dataSummary, true);

        $res = $data['data'] ?? [];

        if(!empty($res)){
            return $this->sendResponse($res, "");
        } else {
            return $this->sendError("Permintaan data gagal");
        }
    }

    function get_list_data(Request $request){
        $user = User::where("api_token", $request->token)->first();

        if(empty($user)){
            return $this->sendError("token tidak valid");
        }

        $emp_id = $user->emp_id;

        $essDashboard = new ESSDashboard();

        $personel = Hrd_employee::find($emp_id);

        $k = $request->type;
        $tg = $request->tg ?? "#pengajuan-content";

        $dataSummary = $essDashboard->getListData($personel, $k, $tg);

        $data = json_decode($dataSummary, true);

        $res = $data['data'] ?? [];

        if(!empty($res)){
            return $this->sendResponse($res, "");
        } else {
            return $this->sendError("Permintaan data gagal");
        }
    }

    function get_attendance_data(Request $request){
        $emp_id = $this->user->emp_id;

        $dataSummary = $this->kjkAtt->detail($emp_id, $request);

        $data = json_decode($dataSummary, true);

        $res = $data['data'] ?? [];

        if(!empty($res)){
            return $this->sendResponse($res, "");
        } else {
            return $this->sendError("Permintaan data gagal");
        }
    }

    function get_loan(Request $request){
        $data = $this->essLoan->get_loan($request, $this->user->emp_id);

        if(!empty($data)){
            return $this->sendResponse($data, "");
        } else {
            return $this->sendError("Data tidak ditemukan");
        }
    }

    function get_leave(Request $request){
        $data = $this->essLeave->getDataLeave($request);

        if(!empty($data)){
            return $this->sendResponse($data, "");
        } else {
            return $this->sendError("Data tidak ditemukan");
        }
    }

    function get_cash(Request $request){
        $data = $this->essCash->get_cash($request, $this->user->emp_id);

        if(!empty($data)){
            return $this->sendResponse($data, "");
        } else {
            return $this->sendError("Data tidak ditemukan");
        }
    }

    function get_letter(Request $request){
        $data = $this->essLetter->get_letter($request, $this->user->emp_id);

        if(!empty($data)){
            return $this->sendResponse($data, "");
        } else {
            return $this->sendError("Data tidak ditemukan");
        }
    }

    function get_team(Request $request){
        try {
            $data = $this->essTeam->getDataTeam($request);

            if(!empty($data)){
                return $this->sendResponse($data, "");
            } else {
                return $this->sendError("Data tidak ditemukan");
            }
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    function get_approval_list(Request $request){
        try {
            $data = $this->essApproval->getApprovalList($request);

            if(!empty($data)){
                return $this->sendResponse($data, "");
            } else {
                return $this->sendError("Data tidak ditemukan");
            }
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    function request_leave(Request $request){
        $data = $this->kjkAtt->request_leave($request, $request->cr ?? null, $this->user->id);

        if($data['success']){
            return $this->sendResponse($data['data'], $data['message']);
        } else {
            return $this->sendError($data['message'], $data['data']);
        }
    }

    function request_overtime(Request $request){
        $data = $this->kjkOvt->store($request, $this->user->id);

        if($data['success']){
            return $this->sendResponse($data['data'], $data['message']);
        } else {
            return $this->sendError($data['message'], $data['data']);
        }
    }

    function request_attendance_correction(Request $request){
        $data = $this->essAtt->add($request, $this->user->id);

        if($data['success']){
            return $this->sendResponse($data['data'], $data['message']);
        } else {
            return $this->sendError($data['message'], $data['data']);
        }
    }

    function request_loan(Request $request){
        $data = $this->essLoan->add($request, $this->user->id);

        if($data['success']){
            return $this->sendResponse($data['data'], $data['message']);
        } else {
            return $this->sendError($data['message'], $data['data']);
        }
    }

    function request_cash(Request $request){
        $data = $this->essCash->add($request, $this->user->id);

        if($data['success']){
            return $this->sendResponse($data['data'], $data['message']);
        } else {
            return $this->sendError($data['message'], $data['data']);
        }
    }

    function request_employment_letter(Request $request){
        $data = $this->essLetter->add($request, $this->user->id);

        if($data['success']){
            return $this->sendResponse($data['data'], $data['message']);
        } else {
            return $this->sendError($data['message'], $data['data']);
        }
    }
}

