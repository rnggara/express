<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel_model;

class KjkArtikelController extends Controller
{
    function index(){
        $hot_artikel = Artikel_model::orderBy("param1", "desc")
            // ->whereBetween("created_at", [$mon, $sun])
            ->first();
        $artikel = Artikel_model::orderBy("created_at", "desc")
            // ->whereBetween("created_at", [$mon, $sun])
            ->where("id", "!=", $hot_artikel->id ?? null)
            ->take(3)
            ->get();

        $newArtikel = Artikel_model::orderBy("created_at", "desc")
            ->where("id", "!=", $hot_artikel->id ?? null)
            ->take(6)
            ->get();
        return view("_applicant.artikel.index", compact("hot_artikel", "artikel", "newArtikel"));
    }

    function detail($id){
        $artikel = Artikel_model::find($id);
        $artikel->status += 1;
        $artikel->save();
        return view("_applicant.artikel.detail", compact("artikel"));
    }
}
