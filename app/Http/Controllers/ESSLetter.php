<?php

namespace App\Http\Controllers;

use App\Models\Hrd_contract_template;
use App\Models\Personel_employment_letter_request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ESSLetter extends Controller
{
    function index(){

        $last_ref = $this->getLastRef();

        $emp_id = Auth::user()->emp_id;

        $letter_type = Hrd_contract_template::where('company_id', Session::get('company_id'))->get();

        $letter_request = Personel_employment_letter_request::where('emp_id', $emp_id)
            ->orderBy("id", "desc")
            ->get();

        return view("_ess.letter.index", compact("letter_type", "letter_request", "last_ref"));
    }

    function add(Request $request, $created = null){
        $personel = \App\Models\Hrd_employee::find($request->emp);

        $loan = new Personel_employment_letter_request();
        $loan->emp_id = $request->emp;
        $loan->letter_type = $request->loan_type;
        $loan->company_id = $personel->company_id;
        $loan->created_by = $created ?? Auth::id();
        $loan->ref_num = $this->getLastRef();
        $loan->reason = $request->reason;
        $loan->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Employment Letter Request Created",
                "bg" => "bg-success"
            ]
        ]);
    }

    function get_letter(Request $request, $emp_id){
        $data = Personel_employment_letter_request::where("emp_id", $emp_id)
            ->orderBy("id", "desc")
            ->get();

        return $data;
    }

    function delete($id){
        $d = Personel_employment_letter_request::find($id);
        if(!empty($d)) $d->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Employment Letter Request deleted",
                "bg" => "bg-danger"
            ]
        ]);
    }

    function getLastRef(){
        $lastLoan = Personel_employment_letter_request::selectRaw("*, CAST(LEFT(ref_num, 3) as unsigned) as ref")->where("company_id", Session::get("company_id"))
            ->orderBy("ref", "desc")
            ->first();

        $last_ref = ($lastLoan->ref ?? 0) + 1;

        $company_id = sprintf("%03d", Session::get("company_id"));

        return sprintf("%03d", $last_ref)."/$company_id/LTR/".date("m/Y");
    }
}
