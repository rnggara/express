<?php

namespace App\Http\Controllers;

use App\Models\Personel_cash_advance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ESSCash extends Controller
{
    function index(){

        $last_ref = $this->getLastRef();

        $emp_id = Auth::user()->emp_id;

        $myCashAdvance = Personel_cash_advance::where("emp_id", $emp_id)
            ->orderBy("id", "desc")
            ->get();

        return view("_ess.cash.index", compact("last_ref", "myCashAdvance"));
    }

    function get_cash(Request $request, $emp_id){
        $data = Personel_cash_advance::where("emp_id", $emp_id)
            ->orderBy("id", "desc")
            ->get();

        return $data;
    }

    function add(Request $request, $created = null){

        $personel = \App\Models\Hrd_employee::find($request->emp);

        $list = $request->list;
        foreach($list as $i => $item){
            $item['amount'] = $this->replaceNumber($item['amount']);
            $list[$i] = $item;
        }

        $loan = new Personel_cash_advance();
        $loan->emp_id = $request->emp;
        $loan->cash_type = $request->loan_type;
        $loan->nominal = $this->replaceNumber($request->nominal);
        $loan->company_id = $personel->company_id;
        $loan->created_by = $created ?? Auth::id();
        $loan->ref_num = $this->getLastRef();
        $loan->reason = $request->reason;
        $loan->detail_cash = $list;
        $loan->save();

        if(!empty($created)){
            return [
                'success' => true,
                'data' => $loan,
                'message' => "Permintaan Penarikan Tunai berhasil dibuat"
            ];
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Request Cash Advance created",
                "bg" => "bg-success"
            ]
        ]);
    }

    function delete($id){
        $d = Personel_cash_advance::find($id);
        if(!empty($d)) $d->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Request Cash Advance deleted",
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
        $lastLoan = Personel_cash_advance::selectRaw("*, CAST(LEFT(ref_num, 3) as unsigned) as ref")->where("company_id", Session::get("company_id"))
            ->orderBy("ref", "desc")
            ->first();

        $last_ref = ($lastLoan->ref ?? 0) + 1;

        $company_id = sprintf("%03d", Session::get("company_id"));

        return sprintf("%03d", $last_ref)."/$company_id/CAD/".date("m/Y");
    }
}
