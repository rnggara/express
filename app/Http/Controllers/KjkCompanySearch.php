<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Master_province;
use App\Models\Master_city;
use App\Models\Master_industry;
use App\Models\Master_company;
use App\Models\Kjk_company_review;
use App\Models\Master_job_type;
use App\Models\User_job_vacancy;

class KjkCompanySearch extends Controller
{

    private $rev_list;

    function __construct(){
        $this->rev_list = [
            1=> "Keseimbangan kerja/hidup", "Pengembangan Karir", "Benefi di perusahan", "Pengelolaan", "Lingkungan Kerja", "Jam Kerja", "Senior leadership", "Keanekaragaman & Budaya"
        ];
    }

    function index(){

        $flokasi = Master_province::orderBy("name")->pluck("name", "id");
        $findustri = Master_industry::orderBy("name")->pluck("name", "id");
        $fkaryawan = ["<50", "51-100", "101-500", "501-1000", "1001-5000", "5000+"];

        return view("_applicant.cs.index", compact("flokasi", "findustri", "fkaryawan"));
    }

    function search(Request $request){

        $flokasi = $request->flokasi ?? [];
        $fkaryawan = $request->fkaryawan ?? [];
        $findustri = $request->findustri ?? [];
        $fname = $request->name;
        $floc = json_decode($request->loc ?? "[]", true);
        $fcity = [];
        foreach($floc as $item){
            $fcity[] = $item['id'];
        }

        $companies = Master_company::where(function($q) use($fname, $flokasi, $fkaryawan, $findustri, $fcity){
            if($fname != ""){
                $q->where("company_name", "like", "%$fname%");
            }

            if(count($flokasi) > 0){
                $q->whereIn("prov_id", $flokasi);
            }

            if(count($fcity) > 0){
                $q->whereIn("city_id", $fcity);
            }

            if(count($fkaryawan) > 0){
                $q->whereIn("skala_usaha", $fkaryawan);
            }

            if(count($findustri) > 0){
                $q->whereIn("industry_id", $findustri);
            }
        })->get();

        $prov = Master_province::pluck("name", "id");
        $city = Master_city::pluck("name", "id");
        $industry = Master_industry::pluck("name", "id");

        $view = view("_applicant.cs.search", compact("companies", 'prov', 'city', "industry"))->render();

        return json_encode([
            "view" => $view
        ]);
    }

    function getLocation(){
        $loc = Master_city::select("id", "name as value")->get()->toArray();

        return json_encode([
            "locations" => $loc
        ]);
    }

    function detail($id, Request $request){
        $menu_view = $request->v ?? "tentang";
        $company = Master_company::find($id);

        $prov = Master_province::find($company->prov_id);
        $city = Master_city::find($company->city_id);
        $industry = Master_industry::find($company->industry_id);

        $reviews = Kjk_company_review::where("company_id", $company->id)
            ->orderBy("created_at", "desc")
            ->get();

        $overall_avg = 0;

        if($reviews->count() > 0){
            $sum = $reviews->sum("overall_rating");
            $overall_avg = round($sum / $reviews->count());
        }

        $rating_avg = [];
        $rev_list = $this->rev_list;
        foreach($rev_list as $i => $item){
            $rating_avg[$i] = 0;
        }

        foreach($reviews as $item){
            foreach($rev_list as $i => $rl){
                $rating_avg[$i] += $item["rating_$i"];
            }
        }

        $rev_paginate = Kjk_company_review::where("company_id", $company->id)
            ->orderBy("created_at", "desc")
            ->paginate(2);

        $salary_avg_pctg = $reviews->count() > 0 ? ($reviews->where("salary_avg", ">=", 1)->count() / $reviews->count()) * 100 : 0;
        $is_recommended_pctg = $reviews->count() > 0 ? ($reviews->where("is_recommended", 1)->count() / $reviews->count()) * 100 : 0;

        $job_ads = User_job_vacancy::where("company_id", $company->id)->orderBy("created_at", "desc")
            ->whereNotNUll("confirm_at")
            ->get();

        return view("_applicant.cs.detail", compact("company", "job_ads", "prov", "menu_view", "city", "industry", "reviews", "rating_avg", "overall_avg", "salary_avg_pctg", "is_recommended_pctg", "rev_list", "rev_paginate"));
    }

    function review($id){
        $company = Master_company::find($id);

        $job_type = Master_job_type::get();

        $rev_list = $this->rev_list;

        return view("_applicant.cs.review", compact("company", "job_type", "rev_list"));
    }

    function review_post(Request $request){

        $rev = new Kjk_company_review();
        $rev->company_id = $request->id;
        $rev->user_id = Auth::id();
        $rev->overall_rating = $request->overall_rating;
        $rev->is_recommended = $request->is_recommended;
        $rev->salary_avg = $request->salary_avg;
        $rev->is_employee = $request->is_employee;
        $rev->position = $request->position;
        $rev->job_type = $request->job_type;
        $rev->title = $request->title;
        $rev->pros = $request->pros;
        $rev->cons = $request->cons;
        $rev->stress_level = $request->stress_level;

        $ratings = $request->rating;
        foreach($ratings as $i => $item){
            $rev["rating_$i"] = $item;
        }

        $rev->save();

        return redirect()->route("app.cs.detail", $rev->company_id);
    }

    function review_list($id, Request $request){

        $rating = $request->filter;

        $rev = $request->kategori;

        $reviews = Kjk_company_review::where(function($q) use($rating, $rev){
            if(!empty($rating)){
                if(empty($rev)){
                    $q->where("overall_rating", $rating);
                } else {
                    $q->where("rating_$rev", $rating);
                }
            }
        })->where("company_id", $id)->orderBy('created_at', "desc")->paginate(2);

        $currentPage = $reviews->currentPage();

        $job_type = Master_job_type::pluck("name", "id");

        $view = view("_applicant.cs.review_list", compact("reviews", "currentPage", "job_type"))->render();

        return json_encode([
            "view" => $view,
            "currentPage" => $currentPage,
            "lastPage" => $reviews->lastPage(),
            "nextUrl" => $reviews->nextPageUrl(),
            "prevUrl" => $reviews->previousPageUrl(),
        ]);
    }

    function job_ads($id, Request $request){
        $job_ads = User_job_vacancy::where("company_id", $id)
            ->whereNotNull("confirm_at")
            ->orderBy("confirm_at", "desc")
            ->paginate(4);

        $company = Master_company::find($id);

        $view = view("_applicant.cs.job_list", compact("job_ads", "company"))->render();

        return json_encode([
            "view" => $view,
            "currentPage" => $job_ads->currentPage(),
            "lastPage" => $job_ads->lastPage(),
            "nextUrl" => $job_ads->nextPageUrl(),
            "prevUrl" => $job_ads->previousPageUrl(),
        ]);
    }
}
