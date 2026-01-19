<?php

namespace App\Http\Controllers;

use App\Models\Artikel_model;
use App\Models\Express_from;
use App\Models\Express_negara_tujuan;
use App\Models\Express_produk_kategori;
use App\Models\Express_produk_tipe;
use App\Models\Hrd_employee_test;
use App\Models\Pref_landing_applicant;
use App\Models\Pref_landing_employer;
use App\Models\User_stories_model;
use App\Models\Kjk_job_view;
use App\Models\User_job_vacancy;
use App\Models\Master_company;
use App\Models\Master_province;
use App\Models\Pref_image_slider;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    function index(){

        $isTrial = \Config::get("constants.IS_TRIAL") ?? 0;
        if($isTrial){
            $url = route('login.guest');
            $m = date("m");
            $s = (date("i") * 1) + 3;
            $params = (http_build_query([
                "email" => "guest@adagavclif.com",
                "password" => "EJS$m$s",
                "role" => "applicant"
            ]));

            if (empty(\Auth::user())) {
                return redirect($url."?".$params);
            }
        }

        $portal_state = \Config::get("constants.PORTAL_STATE");
        if($portal_state == 2){
            return $this->index_applicant();
        } elseif($portal_state == 3){
            if(\Config::get("constants.IS_PORTAL") == 1){
                return redirect()->route("login");
            } else {
                return $this->index_employer();
            }
        } else {
            return redirect()->route("login");
        }
    }

    function index_applicant(){
        $lp_applicant = Pref_landing_applicant::where("company_id", 1)->first();
        $test_id = [];
        if(!empty($lp_applicant)){
            for ($i=1; $i <= 3 ; $i++) {
                $test_id[] = $lp_applicant["um_test_id$i"];
            }
        }
        $test = Hrd_employee_test::whereIn("id", $test_id)->get();
        $mon = date("Y-m-d", strtotime("monday this week"));
        $sun = date("Y-m-d", strtotime("sunday this week"));
        $hot_artikel = Artikel_model::orderBy("param1", "desc")
            // ->whereBetween("created_at", [$mon, $sun])
            ->first();
        $artikel = Artikel_model::orderBy("created_at", "desc")
            // ->whereBetween("created_at", [$mon, $sun])
            ->where("id", "!=", $hot_artikel->id ?? null)
            ->orderBy("param1", 'desc')
            ->take(3)
            ->get();

        $newArtikel = Artikel_model::orderBy("created_at", "desc")
            ->where("id", "!=", $hot_artikel->id ?? null)
            ->whereNotIn("id", $artikel->pluck("id"))
            ->take(6)
            ->get();

        $job_view = Kjk_job_view::get();

        $job_list = User_job_vacancy::select("id", "prov_id", "position", "job_description", "company_id", "yoe", "salary_min", "salary_max", "show_salary", "job_type", "job_spec")->whereIn("id", $job_view->pluck("job_id"))->get();

        $jComp = Master_company::whereIn("id", $job_list->pluck("company_id"))->get();
        $compName = $jComp->pluck("company_name", "id");
        $compImg = $jComp->pluck("icon", "id");

        $prov_job = Master_province::pluck("name","id");
        foreach($job_list as $item){
            $item->views = $job_view->where("job_id", $item->id)->count();
            $item->province = $prov_job[$item->prov_id] ?? "-";
            $item->company_name = $compName[$item->company_id] ?? "-";
            $item->company_img = $compImg[$item->company_id] ?? null;
        }

        $produk = Express_produk_tipe::get();
        $kategori = Express_produk_kategori::get();
        $dari = Express_from::get();
        $tujuan = Express_negara_tujuan::get();

        $sliders = Pref_image_slider::orderBy('order')->get();

        return view("welcome", compact("lp_applicant", "test", "artikel", "hot_artikel", "newArtikel", "job_list", "produk", "kategori", "dari", "tujuan", 'sliders'));
    }

    function index_employer(){
        $lp_employer = Pref_landing_employer::where("company_id", 1)->first();
        $user_stories = User_stories_model::where("company_id", 1)
            ->inRandomOrder()
            ->take(3)
            ->get();
        return view("welcome_employer", compact("lp_employer", "user_stories"));
    }
}
