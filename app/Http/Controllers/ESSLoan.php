<?php

namespace App\Http\Controllers;

use App\Models\Personel_loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ESSLoan extends Controller
{
    function index(){

        $last_ref = $this->getLastRef();

        $emp_id = Auth::user()->emp_id;

        $myLoan = Personel_loan::where("emp_id", $emp_id)
            ->orderBy("id", "desc")
            ->get();

        return view("_ess.loan.index", compact("last_ref", "myLoan"));
    }

    function get_loan(Request $request, $emp_id){
        $myLoan = Personel_loan::where("emp_id", $emp_id)
            ->orderBy("id", "desc")
            ->get();

        return $myLoan;
    }

    function add(Request $request, $created = null){
        $personel = \App\Models\Hrd_employee::find($request->emp);
        $loan = new Personel_loan();
        $loan->emp_id = $request->emp;
        $loan->loan_type = $request->loan_type;
        $loan->nominal = $this->replaceNumber($request->nominal);
        $loan->company_id = $personel->company_id;
        $loan->created_by = $created ?? Auth::id();
        $loan->ref_num = $this->getLastRef();
        $loan->reason = $request->reason;
        $loan->save();

        if(!empty($request->is_mobile)){
            return [
                'success' => true,
                'data' => $loan,
                'message' => "Permintaan Pinjaman berhasil dibuat"
            ];
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Request Loan created",
                "bg" => "bg-success"
            ]
        ]);
    }

    function delete($id){
        $d = Personel_loan::find($id);
        if(!empty($d)) $d->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Request Loan deleted",
                "bg" => "bg-danger"
            ]
        ]);
    }

    function replaceNumber($string){
        $s1 = str_replace(".", "", $string);
        $s2 = str_replace(",", ".", $s1);

        return $s2;
    }

    function getLastRef(){
        $lastLoan = Personel_loan::selectRaw("*, CAST(LEFT(ref_num, 3) as unsigned) as ref")->where("company_id", Session::get("company_id"))
            ->orderBy("ref", "desc")
            ->first();

        $last_ref = ($lastLoan->ref ?? 0) + 1;

        $company_id = sprintf("%03d", Session::get("company_id"));

        return sprintf("%03d", $last_ref)."/$company_id/LOAN/".date("m/Y");
    }
}
