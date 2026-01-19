<?php

namespace App\Http\Controllers;

use App\Models\Express_deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpresDeposit extends Controller
{
    function index(){

        $deposit = Express_deposit::where("user_id", Auth::id())
            ->orderBy("created_at" ,"desc")
            ->get();

        $saldo = $deposit->whereNotNull('confirm_at')->sum('amount');

        return view("_express.deposit.index", compact("deposit", "saldo"));
    }

    public static function getDepositBalance(){
        $saldo = 0;
        $deposit = Express_deposit::where("user_id", Auth::id())
            ->orderBy("created_at" ,"desc")
            ->get();

        if($deposit->count() > 0){
            $saldo = $deposit->whereNotNull('confirm_at')->sum('amount');
        }

        return $saldo;
    }

    function deposit(Request $request){
        $nominal = str_replace('.', '', $request->nominal);
        $nominal = str_replace(',', '.', $nominal);
        if($request->submit == "withdraw"){
            $nominal = $nominal * -1;
        }
        $deposit = new Express_deposit();
        $deposit->user_id = Auth::id();
        $deposit->amount = $nominal;
        $deposit->status = "created";
        $deposit->type = ucwords($request->submit);
        if($request->submit == "withdraw"){
            $deposit->status = "confirmed";
            $deposit->confirm_at = date("Y-m-d H:i:s");
        }
        $deposit->save();

        return redirect()->back()->with([
            "notif" => ucwords($request->submit)." berhasil dibuat",
            "type" => "success"
        ]);
    }

    function confirm(Request $request){
        $deposit = Express_deposit::find($request->deposit_id);
        $deposit->status = "confirmed";
        $deposit->confirm_at = date("Y-m-d H:i:s");
        $deposit->save();

        return redirect()->back()->with([
            "notif" => "Deposit berhasil dikonfirmasi",
            "type" => "success"
        ]);
    }

    function delete($id){
        $deposit = Express_deposit::find($id);
        $deposit->delete();

        return redirect()->back()->with([
            "notif" => "Deposit berhasil dibatalkan",
            "type" => "success"
        ]);
    }
}
