<?php

namespace App\Http\Controllers;

use App\Models\Kjk_crm_leads_contact;
use App\Models\Kjk_crm_product;
use App\Models\Marketing_clients;
use App\Models\Marketing_lead_files;
use App\Models\Marketing_leads;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class KjkCrmArchive extends Controller
{
    function index(){

        $uId = Auth::id();

        $opportunities = Marketing_leads::archived()->where("company_id", Session::get("company_id"))
            ->where(function($q) use($uId){
                $q->where("contributors", 'like', '%"'.$uId.'"%');
                $q->orWhere("partner", $uId);
                $q->orWhere("created_by", $uId);
            })
            ->withoutGlobalScopes()
            ->orderBy("archive_at", "desc")
            ->get();

        $products = Kjk_crm_product::archived()->where("company_id", Session::get("company_id"))
            ->withoutGlobalScopes()
            ->orderBy("archive_at", "desc")
            ->get();

        $clients = Marketing_clients::archived()->where("company_id", Session::get("company_id"))
            ->withoutGlobalScopes()
            ->orderBy("archive_at", "desc")
            ->get();

        $contacts = Kjk_crm_leads_contact::archived()->where("company_id", Session::get("company_id"))
            ->withoutGlobalScopes()
            ->orderBy("archive_at", "desc")
            ->get();

        $files = Marketing_lead_files::archived()->where("company_id", Session::get("company_id"))
            ->withoutGlobalScopes()
            ->orderBy("archive_at", "desc")
            ->get();
        $users = User::hris()->where("company_id", Session::get("company_id"))
            ->get();

        $user_name = $users->pluck("name", "id");
        $user_img = $users->pluck("user_img", "id");
        $user_phone = $users->pluck("phone", "id");
        $user_email = $users->pluck("email", "id");
        $comPluck = Marketing_clients::where("company_id", Session::get("company_id"))->pluck("company_name", "id");
        $leads = Marketing_leads::where("company_id", Session::get('company_id'))->whereNotNull("products")->get();

        $pl = [];

        foreach($leads as $item){
            $pr = json_decode($item->products ?? "[]", true);
            if(is_array($pr)){
                foreach($pr as $pr_id){
                    $pl[$pr_id][] = $item->id;
                }
            }
        }

        return view("_crm.archive.index", compact("opportunities", "products", "clients", "contacts", "files", "user_name", "user_img", "user_phone", "user_email", "comPluck", "pl"));
    }

    function recover($type, $id){
        $detail = [];
        if($type == "leads"){
            $detail = Marketing_leads::withoutGlobalScopes()->findOrFail($id);
        } elseif($type == "perusahaan"){
            $detail = Marketing_clients::withoutGlobalScopes()->findOrFail($id);
        } elseif($type == "kontak"){
            $detail = Kjk_crm_leads_contact::withoutGlobalScopes()->findOrFail($id);
        } elseif($type == "product"){
            $detail = Kjk_crm_product::withoutGlobalScopes()->findOrFail($id);
        }

        if(!empty($detail)){
            $detail->archive_at = null;
            $detail->archive_by = Auth::id();
            $detail->save();
        }

        return redirect()->back();
    }
}
