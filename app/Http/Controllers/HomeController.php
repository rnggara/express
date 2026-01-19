<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Cuti;
use App\Models\Role;
use App\Models\User;
use App\Models\Module;
use App\Models\Mtg_mom;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use App\Models\Division;
use App\Models\Mtg_main;
use App\Models\Asset_pre;
use App\Models\Asset_sre;
use App\Models\Hrd_employee;
use App\Models\RoleDivision;
use App\Rms\RolesManagement;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\General_report;
use App\Models\Finance_business;
use App\Models\Notification_log;
use App\Models\Hrd_salary_update;
use App\Models\Marketing_project;
use App\Models\Hrd_salary_history;
use App\Models\General_meeting_zoom;
use App\Models\General_travel_order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\General_covid_employee;
use App\Models\Finance_business_detail;
use Spatie\Activitylog\Models\Activity;
use App\Models\General_meeting_scheduler_book;
use App\Models\General_meeting_scheduler_room;
use App\Models\General_meeting_scheduler_topic;
use App\Models\General_meeting_zoom_participant;
use App\Models\General_meeting_scheduler_timecheck;
use App\Models\General_ti;
use App\Models\Asset_wh;
use App\Models\Express_from;
use App\Models\Express_negara_tujuan;
use App\Models\Express_produk_kategori;
use App\Models\Express_produk_tipe;
use App\Models\Express_promo;
use App\Models\Express_refund_request;
use App\Models\Express_zone;
use App\Models\Hrd_employee_test;
use App\Models\Hrd_employee_test_result;
use App\Models\Job_bookmark;
use App\Models\Job_vacancy_model;
use App\Models\Kjk_disc_psikogram;
use App\Models\Kjk_mbti_psikogram;
use App\Models\Kjk_wpt_result;
use App\Models\Master_city;
use App\Models\Master_company;
use App\Models\Master_job_type;
use App\Models\Master_province;
use App\Models\Papikostik_psikogram;
use App\Models\User_job_applicant;
use App\Models\User_job_vacancy;
use App\Models\User_profile;
use App\Models\Master_specialization;
use App\Models\Kjk_search_history;
use App\Models\Kjk_job_view;
use App\Models\User_job_interview;
use Faker\Factory;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(\Config::get("constants.IS_BP") == 1){
            return $this->index_bp($request);
        } else {
            $cap = json_decode(Auth::user()->role_access, true);
            if(in_array("hris", $cap)){
                if(\Config::get("constants.PORTAL_STATE") == 3){
                    Session::put("session_state", "intranet");
                    Session::put("home_url", \URL::to("/"));

                    $others = ConfigCompany::whereIn("id", Session::get("comp_user"))
                        ->where("id", "!=", Session::get("company_id"))
                        ->get();


                    return view("home_hris", compact("others"));
                } elseif(\Config::get("constants.PORTAL_STATE") == 4){
                    Session::put("session_state", "ess");
                    Session::put("home_url", route('ess.index'));

                    $others = ConfigCompany::whereIn("id", Session::get("comp_user"))
                        ->where("id", "!=", Session::get("company_id"))
                        ->get();

                    return redirect()->route("ess.index");

                    return view("home_hris", compact("others"));
                } else {
                    return $this->index_ep($request);
                }
            } else {
                return $this->index_applicant($request);
            }
        }
    }

    function index_bp(Request $request){

        $orders = \App\Models\Express_book_order::orderBy("created_at", 'desc')
            ->get();

        return view("home_bp", compact("orders"));
    }

    function be_order(Request $request){

        $orders = \App\Models\Express_book_order::orderBy("created_at", 'desc')
            ->get();

        $book = \App\Models\Express_book_search::orderBy("created_at", 'desc')
            ->whereIn("id", $orders->pluck("book_id"))
            ->get()->keyBy("id");

        $vendor = \App\Models\Express_vendor::get()->keyBy("id");

        $fcharges = \App\Models\Express_fuel_charge::whereIn("vendor_id", $vendor->pluck("id"))
            ->whereNull("end_date")
            ->get()->groupBy("vendor_id");

        return view("_bp.orders", compact("orders", "book", "vendor", "fcharges"));
    }

    function be_order_views($id){
        $order = \App\Models\Express_book_order::find($id);

        $book = \App\Models\Express_book_search::find($order->book_id);

        $produk = \App\Models\Express_produk_tipe::find($book->produk_id);

        $items = $book->content ?? [];
        $content = $items['request'] ?? [];

        $total_berat = 0;
        $total_parcel = 0;
        foreach($content as $item){
            $volume = $item['panjang'] * $item['lebar'] * $item['tinggi'];
            $colv = round(($volume * $item['total_paket']) / 5000,1);
            $total_berat += $colv > ($item['berat'] * $item['total_paket']) ? $colv : ($item['berat'] * $item['total_paket']);
            $total_parcel += $item['total_paket'];
        }

        $negara = \App\Models\Express_negara_tujuan::find($book->tujuan_id);

        $vendor = \App\Models\Express_vendor::find($book->vendor_id);

        $listItem = $order['items'];

        $comp = ConfigCompany::find(Session::get("company_id"));


        return view("_bp.order_packing_list", compact("order", "negara", "book", "vendor", "total_berat", "total_parcel", "content", "listItem", "comp", 'produk'));
    }

    function be_order_invoice($id){
        $order = \App\Models\Express_book_order::find($id);

        $book = \App\Models\Express_book_search::find($order->book_id);

        $items = $book->content ?? [];
        $content = $items['request'] ?? [];

        $total_berat = 0;
        $total_parcel = 0;
        foreach($content as $item){
            $volume = $item['panjang'] * $item['lebar'] * $item['tinggi'];
            $colv = round(($volume * $item['total_paket']) / 5000,1);
            $total_berat += $colv > ($item['berat'] * $item['total_paket']) ? $colv : ($item['berat'] * $item['total_paket']);
            $total_parcel += $item['total_paket'];
        }

        $negara = \App\Models\Express_negara_tujuan::find($book->tujuan_id);

        $vendor = \App\Models\Express_vendor::find($book->vendor_id);

        $listItem = $order['items'];

        $comp = ConfigCompany::find(Session::get("company_id"));


        return view("_bp.order_packing_list_invoice", compact("order", "negara", "book", "vendor", "total_berat", "total_parcel", "content", "listItem", "comp"));
    }

    function be_address(){
        $comp = ConfigCompany::find(Session::get("company_id"));

        return view("_bp.address", compact("comp"));
    }

    function be_address_post(Request $request){
        $comp = ConfigCompany::find($request->id);
        $comp->address = $request->address;
        $comp->phone = $request->phone;
        $comp->p_subtitle = $request->business_hours;
        $comp->save();

        return redirect()->back();
    }

    function be_clients(Request $request){
        $bp = new \App\Http\Controllers\KjkBpController();

        return $bp->applicants_index();
    }

    function be_settings(Request $request){
        $zones = Express_zone::get();

        $grouped = [];
        foreach ($zones as $key => $item) {
            $vendors = $item->vendors ?? [];
            foreach($vendors as $i){
                $grouped[$i][] = $item;
            }
        }

        $countries = \App\Models\Express_negara_tujuan::orderBy("nama")->get();

        $vendors = \App\Models\Express_vendor::get()->keyBy('id');

        return view("_bp.countries", compact("countries", "vendors", "grouped", 'zones'));
    }

    function be_country_update_post_code(Request $request){
        $cn = \App\Models\Express_negara_tujuan::find($request->id);
        $cn->postcode = $request->postcode;
        $cn->save();

        return redirect()->back();
    }

    function be_settings_post(Request $request){
        if($request->submit == "store"){
            $cn = \App\Models\Express_negara_tujuan::findOrNew($request->id);
            $zones = [];
            foreach($request->zones as $c => $item){
                if(!empty($item)){
                    $zones[$c] = $item;
                }
            }
            $cn->nama = $request->nama;
            $cn->zones = $zones;
            // $cn->hpk = str_replace(",", ".", str_replace(".", "", $request->hpk ?? 0));
            // $cn->hpd = str_replace(",", ".", str_replace(".", "", $request->hpd ?? 0));
            $cn->save();
        } elseif($request->submit == "delete"){
            $cn = \App\Models\Express_negara_tujuan::find($request->id);

            if(!empty($cn)) $cn->delete();
        }

        return redirect()->back();
    }

    public function getDir($target) {
        $_dir = str_replace("/", "\\", public_path($target));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $dir = str_replace("\\", "/", $dir);

        return $dir;
    }

    function be_zone_pricing(Request $request){
        $countries = \App\Models\Express_vendor::orderBy("nama")->get();

        $fuel_charges = \App\Models\Express_fuel_charge::whereIn("vendor_id", $countries->pluck("id"))
            ->whereNull("end_date")
            ->get();

        return view("_bp.zone_pricing.index", compact("countries", "fuel_charges"));
    }

    function be_zone_pricing_detail($id, Request $request){
        $vendor = \App\Models\Express_vendor::find($id);

        $zones = \App\Models\Master_rate::where("vendor_id", $vendor->id)->get();

        $dataZone = Express_zone::get()->filter(function($item) use($vendor){
            $vendors = $item->vendors ?? [];
            if(in_array($vendor->id, $vendors)){
                return $item;
            }
        })->values();


        $skutp = \App\Models\Express_produk::where("vendor", "like", '%"'.$vendor->id.'"%')
            ->get();

        $sku = \App\Models\Express_produk_tipe::get();
        $sku_name = $sku->pluck("nama", "id");

        return view("_bp.zone_pricing.detail", compact("vendor", "zones", "sku_name", "skutp", "dataZone"));
    }

    function be_zone_pricing_store(Request $request){

        $vendor = \App\Models\Express_vendor::find($request->vendor);

        if($request->act == "update"){

            if($vendor->type == 1){
                $list = \App\Models\Master_rate::where("vendor_id", $request->vendor)
                    ->where("tipe_sku", $request->tipe_sku ?? null)
                    ->where("weight", $request->weight)->get()->keyBy("zone_id");

                $zones = $request->zone_pricing ?? [];

                foreach($zones as $zone_id => $price){
                    $zone_list = $list[$zone_id] ?? null;

                    if(empty($zone_list)){
                        $zone_list = new \App\Models\Master_rate();
                        $zone_list->vendor_id = $request->vendor;
                        $zone_list->weight = $request->weight;
                        $zone_list->zone_id = $zone_id;
                        $zone_list->tipe_sku = $request->tipe_sku ?? null;
                    }

                    $zone_list->price = str_replace(".", "", $price);
                    $zone_list->save();
                }
            } else {
                $new = Master_rate::find($request->id);
                $new->weight = $request->weight;
                $new->price = str_replace(".", "", $request->price);
                $new->tipe_sku = $request->tipe_sku ?? null;
                $new->save();
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Success",
                    "bg" => "bg-success"
                ]
            ]);

        } elseif($request->act == "delete"){
            $list = \App\Models\Master_rate::where("vendor_id", $request->vendor)
                ->where("tipe_sku", $request->tipe_sku ?? null)
                ->where("weight", $request->weight)->delete();

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Success",
                    "bg" => "bg-success"
                ]
            ]);
        } else {
            $exist = \App\Models\Master_rate::where("vendor_id", $request->vendor)
                ->where("tipe_sku", $request->tipe_sku ?? null)
                ->where("weight", $request->weight)->first();

            if(!empty($exist)){
                return redirect()->back()->with([
                    "toast" => [
                        "message" => "Data exist",
                        "bg" => "bg-danger"
                    ]
                ]);
            }

            if($vendor->type == 1){
                $zones = $request->zone_pricing ?? [];

                foreach($zones as $zone_id => $item){
                    $new = new \App\Models\Master_rate();
                    $new->vendor_id = $request->vendor;
                    $new->weight = $request->weight;
                    $new->zone_id = $zone_id;
                    $new->price = str_replace(".", "", $item);
                    $new->tipe_sku = $request->tipe_sku ?? null;
                    $new->save();
                }
            } else {
                $new = new \App\Models\Master_rate();
                $new->vendor_id = $request->vendor;
                $new->weight = $request->weight;
                $new->price = str_replace(".", "", $request->price);
                $new->tipe_sku = $request->tipe_sku ?? null;
                $new->save();
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Success",
                    "bg" => "bg-success"
                ]
            ]);
        }
    }

    function be_zone_multiplier(Request $request){
        $countries = \App\Models\Express_vendor::orderBy("nama")->get();


        return view("_bp.zone_multiplier.index", compact("countries"));
    }

    function be_zone_multiplier_detail($id, Request $request){
        $vendor = \App\Models\Express_vendor::find($id);

        $zones = \App\Models\Master_rate_multiplier::where("vendor_id", $vendor->id)->get();

        $dataZone = Express_zone::get()->filter(function($item) use($vendor){
            $vendors = $item->vendors ?? [];
            if(in_array($vendor->id, $vendors)){
                return $item;
            }
        })->values();

        return view("_bp.zone_multiplier.detail", compact("vendor", "zones", "dataZone"));
    }

    function be_zone_multiplier_store(Request $request){
        $vendor = \App\Models\Express_vendor::find($request->vendor);
        if($request->act == "update"){
            if ($vendor->type == 1) {
                $list = \App\Models\Master_rate_multiplier::where("vendor_id", $request->vendor)
                    ->where("weight_min", $request->weight_min)
                    ->where("weight_max", $request->weight_max)
                    ->get()->keyBy("zone_id");

                $zones = $request->zone_pricing ?? [];

                foreach($zones as $zone_id => $price){
                    $zone_list = $list[$zone_id] ?? null;

                    if(empty($zone_list)){
                        $zone_list = new \App\Models\Master_rate_multiplier();
                        $zone_list->vendor_id = $request->vendor;
                        $zone_list->weight_min = $request->weight_min;
                        $zone_list->weight_max = $request->weight_max;
                        $zone_list->zone_id = $zone_id;
                    }

                    $zone_list->price = str_replace(".", "", $price);
                    $zone_list->save();
                }
            } else {
                $new = Master_rate_multiplier::find($request->id);
                $new->weight_min = $request->weight_min;
                $new->weight_max = $request->weight_max;
                $new->price = str_replace(".", "", $request->price);
                $new->save();
            }
            

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Success",
                    "bg" => "bg-success"
                ]
            ]);

        } elseif($request->act == "delete"){
            $list = \App\Models\Master_rate_multiplier::where("vendor_id", $request->vendor)
                ->where("weight_min", $request->weight_min)
                ->where("weight_max", $request->weight_max)
                ->delete();

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Success",
                    "bg" => "bg-success"
                ]
            ]);
        } else {
            $exist = \App\Models\Master_rate_multiplier::where("vendor_id", $request->vendor)
                ->where(function($q) use($request){
                    $q->whereBetween('weight_min', [$request->weight_min, $request->weight_max])
                    ->orWhereBetween('weight_max', [$request->weight_min, $request->weight_max])
                    ->orWhere(function($query) use($request) {
                        $query->where("weight_min", "<", $request->weight_min);
                        $query->where("weight_max", ">", $request->weight_min);
                    })
                    ->orWhere(function($query) use($request) {
                        $query->where("weight_min", "<", $request->weight_max);
                        $query->where("weight_max", ">", $request->weight_max);
                    });
                })
                ->first();

            if(!empty($exist)){
                return redirect()->back()->with([
                    "toast" => [
                        "message" => "Data exist",
                        "bg" => "bg-danger"
                    ]
                ]);
            }

            if($vendor->type == 1){
                $zones = $request->zone_pricing ?? [];

                foreach($zones as $zone_id => $item){
                    $new = new \App\Models\Master_rate_multiplier();
                    $new->vendor_id = $request->vendor;
                    $new->weight_min = $request->weight_min;
                    $new->weight_max = $request->weight_max;
                    $new->zone_id = $zone_id;
                    $new->price = str_replace(".", "", $item);
                    $new->save();
                }
            } else {
                $new = new \App\Models\Master_rate_multiplier();
                $new->vendor_id = $request->vendor;
                $new->weight_min = $request->weight_min;
                $new->weight_max = $request->weight_max;
                $new->price = str_replace(".", "", $request->price);
                $new->save();
            }

            return redirect()->back()->with([
                "toast" => [
                    "message" => "Success",
                    "bg" => "bg-success"
                ]
            ]);
        }
    }

    function be_vendors(Request $request){

        $countries = \App\Models\Express_vendor::orderBy("nama")->get();

        $fuel_charges = \App\Models\Express_fuel_charge::whereIn("vendor_id", $countries->pluck("id"))
            ->get();

        if($request->act == "edit"){
            $vendor = \App\Models\Express_vendor::find($request->id);

            $fcharges = \App\Models\Express_fuel_charge::where("vendor_id", $vendor->id)
                ->whereNull("end_date")
                ->first();

            $data = [
                "vendor" => $vendor,
                "fcharges" => $fcharges
            ];

            return response()->json($data);
        }

        if($request->act == "zone"){
            $vendor = \App\Models\Express_vendor::find($request->id);
            $dataZone = Express_zone::get()->filter(function($item) use($vendor){
                $vendors = $item->vendors ?? [];
                if(in_array($vendor->id, $vendors)){
                    return $item;
                }
            })->values();

            $allZone = Express_zone::whereNotIn("id", $dataZone->pluck('id'))->get();

            $view = view("_bp.vendor_zone", compact("dataZone", 'vendor', 'allZone'));

            return response()->json([
                "view" => $view->render()
            ]);
        }

        return view("_bp.vendors", compact("countries", "fuel_charges"));
    }

    function be_vendors_zone_remove($vendor, $id){
        $zone = Express_zone::find($id);
        if(!empty($zone)){
            $vendors = $zone->vendors ?? [];
            $key = array_search($vendor, $vendors);
            if($key !== -1){
                unset($vendors[$key]);
                $newVendor = collect($vendors)->values()->toArray();
                $zone->vendors = $newVendor;
                $zone->save();
            }

            if(count($zone->vendors ?? []) == 0){
                $zone->delete();
            }
        }

        return redirect()->back();
    }

    function be_vendors_post_zone(Request $request){
        $submit = $request->submit;
        $vendor = $request->vendor;
        if($submit == "new"){
            $zone = new Express_zone();
            $zone->zone = $request->zone_code;
            $zone->name = $request->zone_name;
            $zone->vendors = [$vendor];
            $zone->save();
        } elseif($submit == 'assign'){
            $zone = Express_zone::find($request->zone_id);
            $vendors = $zone->vendors ?? [];
            $vendors[] = $vendor;
            $zone->vendors = $vendors;
            $zone->save();
        }

        return redirect()->back();
    }

    function currToFloat($amount){
        $v = str_replace(".", "", $amount);
        $v = str_replace(",", ".", $amount);
        return $v;
    }

    function be_vendors_post(Request $request){
        if($request->submit == "store"){
            $cn = \App\Models\Express_vendor::findOrNew($request->id);
            $cn->nama = $request->nama;
            $cn->type = $request->vendor_type;
            $file = $request->file('logo');
            if(!empty($file)){
                $path = $file->store("public/express/vendors");
                if($file->move($this->getDir("media/express/vendors"), $file->getClientOriginalName())){
                    $path = "media/express/vendors/".$file->getClientOriginalName();
                }
                $cn->logo_path = $path;
            }
            $cn->save();

            $fuel_charges = \App\Models\Express_fuel_charge::where("vendor_id", $cn->id)
                ->whereNull("end_date")
                ->first();

            $new = false;

            $price = str_replace(",", ".", str_replace(".", "", $request->price ?? 0));

            if(!empty($fuel_charges)){
                if($request->surcharge_type == 1){
                    if($fuel_charges->price != $price){
                        $fuel_charges->end_date = date("Y-m-d");
                        $fuel_charges->save();
                        $new = true;
                    }
                } else {
                    if($fuel_charges->fuel_surcharge != $price){
                        $fuel_charges->end_date = date("Y-m-d");
                        $fuel_charges->save();
                        $new = true;
                    }
                }
            }

            if($new){
                $new_fuel_charge = new \App\Models\Express_fuel_charge();
                $new_fuel_charge->vendor_id = $cn->id;
                $new_fuel_charge->surcharge_type = $request->surcharge_type ?? 0;
                if($request->surcharge_type == "0"){
                    $new_fuel_charge->fuel_surcharge = $price / 100;
                } else {
                    $new_fuel_charge->price = $price;
                }
                // $new_fuel_charge->price = str_replace(",", ".", str_replace(".", "", $request->price ?? 0));
                $new_fuel_charge->remote_area = str_replace(",", ".", str_replace(".", "", $request->remote_area ?? 0));
                $new_fuel_charge->remote_area_multiplier = str_replace(",", ".", str_replace(".", "", $request->remote_area_multiplier ?? 0));
                $new_fuel_charge->remote_area_limit = str_replace(",", ".", str_replace(".", "", $request->remote_area_limit ?? 0));
                $new_fuel_charge->restricted_destination_price = str_replace(",", ".", str_replace(".", "", $request->restricted_destination_price ?? 0));
                $new_fuel_charge->elevated_risk_destination_price = str_replace(",", ".", str_replace(".", "", $request->elevated_risk_destination_price ?? 0));
                $new_fuel_charge->oversize_dom_price = str_replace(",", ".", str_replace(".", "", $request->oversize_dom_price ?? 0));
                $new_fuel_charge->overweight_price = str_replace(",", ".", str_replace(".", "", $request->overweight_price ?? 0));
                $new_fuel_charge->overweight_limit = str_replace(",", ".", str_replace(".", "", $request->overweight_limit ?? 0));
                $new_fuel_charge->oversize_price = str_replace(",", ".", str_replace(".", "", $request->oversize_price ?? 0));
                $new_fuel_charge->oversize_limit = str_replace(",", ".", str_replace(".", "", $request->oversize_limit ?? 0));
                $new_fuel_charge->non_stackable_price = str_replace(",", ".", str_replace(".", "", $request->non_stackable_price ?? 0));
                $new_fuel_charge->insurance_price = str_replace(",", ".", str_replace(".", "", $request->insurance_price ?? 0));
                $new_fuel_charge->delivery_duty_price = str_replace(",", ".", str_replace(".", "", $request->delivery_duty_price ?? 0));
                $new_fuel_charge->export_declaration_price = str_replace(",", ".", str_replace(".", "", $request->export_declaration_price ?? 0));
                $new_fuel_charge->start_date = date("Y-m-d");
                $new_fuel_charge->surcharge_remote_area = $request->surcharge_remote_area ?? 0;
                $new_fuel_charge->surcharge_elevated_risk = $request->surcharge_elevated_risk ?? 0;
                $new_fuel_charge->surcharge_restricted_destination = $request->surcharge_restricted_destination ?? 0;
                $new_fuel_charge->surcharge_overweight = $request->surcharge_overweight ?? 0;
                $new_fuel_charge->surcharge_oversize = $request->surcharge_oversize ?? 0;
                $new_fuel_charge->surcharge_nsu = $request->surcharge_nsu ?? 0;
                $new_fuel_charge->surcharge_insurance = $request->surcharge_insurance ?? 0;
                $new_fuel_charge->surcharge_ddp = $request->surcharge_ddp ?? 0;
                $new_fuel_charge->surcharge_peb = $request->surcharge_peb ?? 0;
                $new_fuel_charge->surcharge_ncp = $request->surcharge_ncp ?? 0;
                $new_fuel_charge->save();
            } else {
                if(empty($fuel_charges)){
                    $fuel_charges = new \App\Models\Express_fuel_charge();
                    $fuel_charges->vendor_id = $cn->id;
                }

                if($request->surcharge_type == "0"){
                    $fuel_charges->fuel_surcharge = $price / 100;
                } else {
                    $fuel_charges->price = $price;
                }
                $fuel_charges->surcharge_type = $request->surcharge_type ?? 0;
                $fuel_charges->remote_area = str_replace(",", ".", str_replace(".", "", $request->remote_area ?? 0));
                $fuel_charges->remote_area_multiplier = str_replace(",", ".", str_replace(".", "", $request->remote_area_multiplier ?? 0));
                $fuel_charges->remote_area_limit = str_replace(",", ".", str_replace(".", "", $request->remote_area_limit ?? 0));
                $fuel_charges->overweight_price = str_replace(",", ".", str_replace(".", "", $request->overweight_price ?? 0));
                $fuel_charges->overweight_limit = str_replace(",", ".", str_replace(".", "", $request->overweight_limit ?? 0));
                $fuel_charges->oversize_price = str_replace(",", ".", str_replace(".", "", $request->oversize_price ?? 0));
                $fuel_charges->oversize_limit = str_replace(",", ".", str_replace(".", "", $request->oversize_limit ?? 0));
                $fuel_charges->restricted_destination_price = str_replace(",", ".", str_replace(".", "", $request->restricted_destination_price ?? 0));
                $fuel_charges->elevated_risk_destination_price = str_replace(",", ".", str_replace(".", "", $request->elevated_risk_destination_price ?? 0));
                $fuel_charges->non_stackable_price = str_replace(",", ".", str_replace(".", "", $request->non_stackable_price ?? 0));
                $fuel_charges->insurance_price = str_replace(",", ".", str_replace(".", "", $request->insurance_price ?? 0));
                $fuel_charges->delivery_duty_price = str_replace(",", ".", str_replace(".", "", $request->delivery_duty_price ?? 0));
                $fuel_charges->export_declaration_price = str_replace(",", ".", str_replace(".", "", $request->export_declaration_price ?? 0));
                $fuel_charges->oversize_dom_price = str_replace(",", ".", str_replace(".", "", $request->oversize_dom_price ?? 0));
                $fuel_charges->surcharge_remote_area = $request->surcharge_remote_area ?? 0;
                $fuel_charges->surcharge_elevated_risk = $request->surcharge_elevated_risk ?? 0;
                $fuel_charges->surcharge_restricted_destination = $request->surcharge_restricted_destination ?? 0;
                $fuel_charges->surcharge_overweight = $request->surcharge_overweight ?? 0;
                $fuel_charges->surcharge_oversize = $request->surcharge_oversize ?? 0;
                $fuel_charges->surcharge_nsu = $request->surcharge_nsu ?? 0;
                $fuel_charges->surcharge_insurance = $request->surcharge_insurance ?? 0;
                $fuel_charges->surcharge_ddp = $request->surcharge_ddp ?? 0;
                $fuel_charges->surcharge_peb = $request->surcharge_peb ?? 0;
                $fuel_charges->surcharge_ncp = $request->surcharge_ncp ?? 0;
                $fuel_charges->save();
            }


        } elseif($request->submit == "delete"){
            $cn = \App\Models\Express_vendor::find($request->id);

            if(!empty($cn)) $cn->delete();
        }

        return redirect()->back();
    }

    function be_promo(Request $request){
        $promo = Express_promo::orderBy('start_date', "desc")->get();
        $promo_src = config("constants.promo_src");

        if($request->act == "random"){
            // $faker = Factory::create();
            $kode = strtoupper(Str::random(8));

            return response()->json([
                "kode" => $kode
            ]);
        }

        if($request->act == "edit"){
            $promo = Express_promo::find($request->id);

            return response()->json([
                "data" => $promo
            ]);
        }

        return view("_bp.promo", compact("promo", "promo_src"));
    }

    function be_refund_store(Request $request){
        if($request->submit == "approve"){
            $data = Express_refund_request::find($request->id);
            $data->transfer_at = date("Y-m-d H:i:s");
            $data->transfer_by = Auth::id();
            $data->save();

            $order = $data->order;
            $order->status = -2;
            $order->save();
        } else {
            $data = Express_refund_request::find($request->id);
            $order = $data->order;
            $data->delete();
            $order->status = 2;
            $order->save();
        }

        return redirect()->back();
    }

    function be_promo_store(Request $request){
        if($request->submit == "store"){
            $promo = Express_promo::findOrNew($request->id);
            $promo->code = $request->code;
            $promo->description = $request->description;
            $promo->start_date = $request->start_date;
            $promo->end_date = $request->end_date;
            $promo->source = $request->source;
            $promo->amount = $request->source == 0 ? $this->numberValue($request->amount) : 0;
            $promo->formula = $request->source == 1 ? $request->formula : null;
            $promo->target = $request->source == 1 ? $request->target : null;
            $promo->amount_limit = $request->source == 1 ? $this->numberValue($request->amount_limit) : null;
            $promo->save();
        } elseif($request->submit == "delete"){
            $promo = Express_promo::find($request->id);
            if(!empty($promo)) $promo->delete();
        }

        return redirect()->back();
    }

    function be_refund(Request $request){
        $refunds = Express_refund_request::orderBy('created_at', "desc")->get();

        return view("_bp.refund", compact("refunds"));
    }

    function numberValue($value){
        $nominal = str_replace('.', '', $value);
        $nominal = str_replace(',', '.', $nominal);
        return $nominal;
    }

    function order_konfirmasi(Request $request){
        $order = \App\Models\Express_book_order::find($request->deposit_id);
        $bkirim = $this->numberValue($request->biaya_kirim);
        $fcharge = $this->numberValue($request->fuel_surcharge);
        $koreksi_biaya = $bkirim + $fcharge;
        $order->remote_area = $request->remote_areas;
        $order->elevated_risk = $request->elevated_risk;
        $order->restricted_destination = $request->restricted_destination;
        $order->outstanding_payment = $this->numberValue($request->outstanding_payment);
        $order->koreksi_biaya_kirim = $bkirim;
        $order->koreksi_fuel_charge = $fcharge;
        $order->koreksi_notes = $request->koreksi_notes;
        $order->koreksi_fumigasi = $this->numberValue($request->fumigasi);
        $order->koreksi_delivery_duty = $this->numberValue($request->delivery_duty);
        $order->koreksi_export_declare = $this->numberValue($request->export_declare);
        $order->ddp_amount = $this->numberValue($request->ddp_amount);
        $order->koreksi_asuransi = $this->numberValue($request->asuransi);
        $order->koreksi_vat = $this->numberValue($request->vat);
        $order->koreksi_biaya = $koreksi_biaya + $order->koreksi_fumigasi + $order->koreksi_delivery_duty + $order->koreksi_export_declare + $order->koreksi_vat + $order->koreksi_asuransi + $order->ddp_amount - $order->promo_amount + $order->restricted_destination + $order->remote_areas + $order->elevated_risk;
        $status = 2;
        if($order->koreksi_biaya == $order->total_biaya + $order->vat){
            $status = 3;
            $order->reweight_payment_at = date("Y-m-d H:i:s");
            $order->reweight_payment_by = Auth::id();
        }
        $order->status = $status;
        $order->koreksi_at = date("Y-m-d H:i:s");
        $order->koreksi_by = Auth::id();
        $order->save();

        return redirect()->back();
    }

    function order_awb(Request $request){
        $order = \App\Models\Express_book_order::find($request->deposit_id);
        $order->nomor_resi = $request->nomor_resi;
        $order->nomor_awb = $request->nomor_awb;
        $order->input_awb_at = date("Y-m-d H:i:s");
        $order->input_awb_by = Auth::id();
        $order->status = 3;
        $awb_file = $request->file("awb_file");
        if(!empty($awb_file)){
            if($awb_file->move($this->getDir("media/express/vendors"), $awb_file->getClientOriginalName())){
                $path = "media/express/vendors/".$awb_file->getClientOriginalName();
                $order->awb_file = $path;
            }
        }
        $order->save();

        return redirect()->back();
    }

    function order_done(Request $request){
        $order = \App\Models\Express_book_order::find($request->deposit_id);
        $order->received_at = date("Y-m-d H:i:s");
        $order->received_by = Auth::id();
        $order->received_by = 1;
        $order->save();

        return redirect()->back();
    }

    function dashboardChart(Request $request){
        $type = $request->type;

        $monthId = [1=> "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

        $categories = [];
        for($i = 1; $i <= date("n"); $i++){
            $categories[] = $monthId[$i];
        }

        $chart = [];

        if($type == "applicant"){

            $applicant = User::where('role_access', "like", '%"applicant"%')->get();

            $uProfile = User_profile::whereIn("user_id", $applicant->pluck("id"))->pluck("gender", "user_id");
            $uGender = [
                "Pria" => 0,
                "Wanita" => 0
            ];

            foreach($uProfile as $item){
                if(isset($uGender[$item])){
                    $uGender[$item] +=1;
                }
            }

            $app_reg = [];
            foreach($applicant as $item){
                $n = date("n", strtotime($item->created_at));
                $mgender = $uProfile[$item->id] ?? "Pria";
                $app_reg[$mgender][$monthId[$n]][] = 1;
            }

            foreach($app_reg as $i => $item){
                foreach($item as $m => $val){
                    $app_reg[$i][$m] = array_sum($val);
                }
            }

            foreach($categories as $i => $item){
                $chart["Pria"][] = isset($app_reg["Pria"][$item]) ? $app_reg["Pria"][$item] : 0;
                $chart["Wanita"][] = isset($app_reg["Wanita"][$item]) ? $app_reg["Wanita"][$item] : 0;
            }
        } elseif($type == "perusahaan"){
            $companies = Master_company::get();
            $app_reg = [];
            foreach($companies as $item){
                $n = date("n", strtotime($item->created_at));
                $app_reg[$monthId[$n]][] = 1;
            }

            foreach($categories as $i => $item){
                $chart[] = isset($app_reg[$item]) ? array_sum($app_reg[$item]) : 0;
            }
        } elseif($type == "posisi"){
            $jobs = User_job_vacancy::get();

            $job_views = Kjk_job_view::whereIn("job_id", $jobs->pluck("id"))->get();

            $data_pos = [];
            foreach($jobs as $item){
                $viws = $job_views->where("job_id", $item->id)->count();
                $data_pos[$item->position][] = $viws;
            }

            $pos = [];
            foreach($data_pos as $key => $item){
                $data_pos[$key] = array_sum($item);
            }

            foreach($data_pos as $key => $item){
                $col = [];
                $col['label'] = $key;
                $col['sum'] = $item;
                $pos[] = $col;
            }

            usort($pos, function($a, $b){
                return $a['sum'] < $b['sum'];
            });

            $top = [];
            $categories = [];
            for($i = 0; $i < 10; $i++){
                if(isset($pos[$i])){
                    $top[] = $pos[$i]['sum'];
                    $categories[] = $pos[$i]['label'];
                }
            }

            $chart = $top;
        } elseif($type == "job"){
            $jobs = User_job_vacancy::get();
            $mComp = Master_company::pluck("company_name", "id");
            $data_pos = [];
            foreach($jobs as $item){
                if(isset($mComp[$item->company_id])){
                    $data_pos[$mComp[$item->company_id]][] = 1;
                }
            }

            $pos = [];
            foreach($data_pos as $key => $item){
                $data_pos[$key] = array_sum($item);
            }

            foreach($data_pos as $key => $item){
                $col = [];
                $col['label'] = $key;
                $col['sum'] = $item;
                $pos[] = $col;
            }

            usort($pos, function($a, $b){
                return $a['sum'] < $b['sum'];
            });

            $top = [];
            $categories = [];
            for($i = 0; $i < 10; $i++){
                if(isset($pos[$i])){
                    $top[] = $pos[$i]['sum'];
                    $categories[] = $pos[$i]['label'];
                }
            }

            $chart = $top;
        } elseif($type == "map"){
            $ch = curl_init();
             // set url
            curl_setopt($ch, CURLOPT_URL, "https://cdn.amcharts.com/lib/5/geodata/json/$request->map.json");

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);

            $companies = Master_company::get();
            $prov = Master_province::pluck("id_map", "id");
            $data = [];
            foreach($companies as $item){
                $prov_name = $prov[$item->prov_id] ?? "";
                if($prov_name != ""){
                    $data[$prov_name] = isset($data[$prov_name]) ? $data[$prov_name] + 1 : 1;
                }
            }

            $result = [
                "data" => $data,
                "map" => json_decode($output)
            ];

            return $result;
        }

        $data = [
            "data" => $chart,
            "categories" => $categories
        ];

        return json_encode($data);
    }

    function index_ep(Request $request){
        $comp = Master_company::find(Auth::user()->comp_id);

        $jobCollabs = User_job_vacancy::where("collaborators", "like", '%"'.Auth::id().'"%')->get();
        // dd($jobCollabs, Auth::id());

        $job_vac = User_job_vacancy::where("user_id", Auth::id())
            ->orWhereIn("id", $jobCollabs->pluck("id"))
            ->get();
        $job_app = User_job_applicant::whereIn("job_id", $job_vac->pluck('id'))->orderBy("created_at", 'desc')->get();
        $job_pos = $job_vac->pluck("position", "id");

        $users = User::get();

        $user_name = $users->pluck("name", "id");
        $user_img = $users->pluck("user_img", "id");

        $activity = [];

        foreach($job_app->take(3) as $item){
            if(isset($user_name[$item->user_id])){

                $act = "Melamar di";
                $date = date("Y-m-d", strtotime($item->created_at));
                if(!empty($item->need_reschedule)){
                    $act = "Mengajukan jadwal interview di";
                    $date = date("Y-m-d", strtotime($item->updated_at));
                }


                $col = [];
                $col['name'] = $user_name[$item->user_id];
                $col['activity'] = $act;
                $col['job'] = $job_pos[$item->job_id] ?? "-";
                $col['date'] = $date;
                $col['image'] = $user_img[$item->user_id] ?? 'theme/assets/media/avatars/blank.png';
                $activity[] = $col;
            }
        }

        usort($activity, function($a, $b){
            return strtotime($b['date']) - strtotime($a['date']);
        });

        $kalenderAct = [];
        $interviews = User_job_interview::whereIn("job_app_id", $job_app->pluck("id"))
            ->orderBy("int_date", "desc")
            ->get();
        foreach($interviews as $item){
            $job = $job_app->where("id", $item->job_app_id)->first();
            if(!empty($job)){
                if(isset($user_name[$job->user_id])){
                    $col = [];
                    $col['date'] = $item->int_date;
                    $col['name'] = $user_name[$job->user_id];
                    $col['activity'] = "Interview di";
                    $col['job'] = $job_pos[$job->job_id] ?? "-";
                    $col['image'] = $user_img[$job->user_id] ?? 'theme/assets/media/avatars/blank.png';
                    $kalenderAct[$item->job_app_id][] = $col;
                }
            }
        }

        $job_views = Kjk_job_view::whereIn("job_id", $job_vac->pluck("id"))->get();

        return view("home_ep", compact('job_vac', "job_app", "activity", "kalenderAct", "job_views", "user_name"));
    }

    function index_applicant(Request $request){
        $lamaran = User_job_applicant::where("user_id", Auth::id())->orderBy("created_at", "desc")->take(2)->get();

        $bookmark = Job_bookmark::where("user_id", Auth::id())->orderBy("updated_at", "desc")->take(2)->get();

        $job_id = $lamaran->pluck("job_id")->toArray();
        $job_id = array_merge($job_id, $bookmark->pluck("job_id")->toArray());

        $vacancies = User_job_vacancy::whereIn("id", $job_id)->inRandomOrder()->get();

        $companies = Master_company::whereIn('id', $vacancies->pluck("company_id"))->get();

        $job_type = Master_job_type::get();
        $city = Master_city::whereIn("id", $companies->pluck("city_id"))->get();
        $prov = Master_province::whereIn("id", $companies->pluck("prov_id"))->get();

        $test = Hrd_employee_test::get();

        $test_take = Hrd_employee_test_result::where("user_id", Auth::id())->get();
        $test_taken = array_unique($test_take->pluck("test_id")->toArray() ?? []);

        $produk = Express_produk_tipe::get();
        $kategori = Express_produk_kategori::get();
        $dari = Express_from::get();
        $tujuan = Express_negara_tujuan::get();

        $saldoDeposit = \App\Http\Controllers\ExpresDeposit::getDepositBalance();

        $myOrder = \App\Models\Express_book_order::where("created_by", Auth::id())->get();

        return view("home", compact('lamaran', 'companies', 'job_type', "city", "prov", "bookmark", "saldoDeposit", "vacancies", "test", "test_taken", "produk", "kategori", "dari", "tujuan", "myOrder"));
    }

    function test_page(){
        $test = Hrd_employee_test::where("company_id", Session::get('company_id'))->orderBy('order_num')->get();
        $res = Hrd_employee_test_result::where("user_id", Auth::id())
            ->whereNotNull("result_end")
            ->whereDate('created_at', ">=",  date("Y-m-d", strtotime("3 months ago")))
            ->orderBy("id", "desc")
            ->get();

        $wpt = Kjk_wpt_result::where("user_id", Auth::id())->get();
        $mbti = Kjk_mbti_psikogram::where("user_id", Auth::id())->get();
        $disc = Kjk_disc_psikogram::where("user_id", Auth::id())->get();
        $papi = Papikostik_psikogram::where("user_id", Auth::id())->get();

        $wpt_iq = \App\Models\Kjk_wpt_score_iq::pluck("iq", "score");
        $wpt_interpretasi = \App\Models\Kjk_wpt_interpretasi::pluck("label", "score");
        return view('employee.test.list', compact("test", "res", "wpt", "mbti", "disc", "papi", "wpt_iq", "wpt_interpretasi"));
    }

    function faq_page(){
        return view("faq");
    }

    function activity_log(){
        //get activity log
        $ddate = date("Y-m-d", strtotime("-7 days"));
        $activity = Activity::where('causer_id', Auth::id())
            ->where('created_at', '>=', $ddate)
            ->get();

        $actByDay = [];
        foreach ($activity as $key => $value) {
            $dday = date("Y_m_d", strtotime($value->created_at));
            $actByDay[$dday][] = date("Y-m-d H:i:s", strtotime($value->created_at));
            sort($actByDay[$dday]);
        }

        $act = [];
        if (count($actByDay) > 0) {
            $i = 0;
            foreach ($actByDay as $key => $value) {
                $start = strtotime($value[0]);
                $end = strtotime($value[count($value) - 1]);
                $hour = round(abs($end - $start)/(60*60)) . " hour(s)";
                $act[$i]['start'] = $value[0];
                $act[$i]['end'] = $value[count($value) - 1];
                $act[$i]['hours'] = $hour;
                $act[$i]['date'] = date("Y-m-d", strtotime($value[0]));
                $i++;
            }
        }

        $success = false;
        $data = "Data not found";

        if (count($act) > 0) {
            $success = true;
            $data = $act;
        }

        $result = array(
            "success" => $success,
            "data" => $data
        );

        return json_encode($result);
    }

    function menu_list(){
        $menu = Module::whereNotNull('route')->get();

        $hasAction = Session::get('company_user_rc');

        $data = [];
        foreach($menu as $item){
            $txt = ucwords($item->desc).", ".$item->name;
            if(isset($hasAction[$item->name])){
                $action = $hasAction[$item->name];
                if (isset($action['access'])) {
                    $data[] = $txt;
                }
            }
        }

        return json_encode($data);
    }

    function menu_redirect(Request $request){
        $txt = explode(",", $request->menu);
        $menu = Module::where('name', str_replace(" ", "", end($txt)))->first();

        if(!empty($menu)){
            return redirect()->route($menu->route);
        }

        return redirect()->back()->with('menu-back', 'no menu');
    }

    function notif_clear($t, $i){
        $type = base64_decode($t);
        $id = base64_decode($i);

        $notif = Notification_log::find($id);
        if($type == "clear"){
            $notif->deleted_by = Auth::user()->username;
            $notif->save();
            $notif->delete();
        }

        return redirect()->back();
    }

    function forbidden_access(){
        return view("_error.403");
    }

    function notif_view($id){
        $notif = Notification_log::find($id);

        $notif->clicked = 1;
        $notif->save();

        $rNotif = $this->notif_list(true);

        Session::put('notifications_count', $rNotif);

        $url = $notif->url;
        $exp = explode("public", $url);

        $url = \URL::to(end($exp));

        $data = [
            "count" => $rNotif,
            "url" => $url
        ];

        return json_encode($data);
    }

    function notif_list($count = null){
        $minDate = date("Y-m-d", strtotime("-4 days"));
        $notif = Notification_log::where('id_users', 'like', '%"'.Auth::id().'"%')
            ->where("created_at", ">", $minDate)
            ->where("clicked", 0)
            ->whereNotNull("id_item")
            ->orderBy('created_at', 'desc')
            ->whereNull('action_at')
            ->distinct()
            ->groupBy("text")
            ->get(['text', 'id', 'id_item', 'item_type', 'url']);

        if($count){
            return count($notif);
        }

        Session::put('notifications_count', count($notif));

        $view = view("layouts.notif", compact("notif"))->render();

        $data = [
            "count" => count($notif),
            "view" => $view
        ];

        return json_encode($data);
    }

    function modal_pdf($file_name){
        $file = asset("pdfs/$file_name.pdf");
        return view("layouts._modal_pdf", compact("file_name", "file"));
    }
}
