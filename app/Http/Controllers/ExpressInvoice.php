<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpressInvoice extends Controller
{
    function index(){

        $orders = \App\Models\Express_book_order::where("status", 3)
            ->orderBy("created_at", "desc")
            ->get();

        return view("_express.invoice.index", compact("orders"));
    }
}
