<?php

namespace App\Http\Controllers;

use App\Models\Job_bookmark;
use App\Models\Job_vacancy_model;
use App\Models\Master_city;
use App\Models\Master_company;
use App\Models\Master_educations;
use App\Models\Master_gender;
use App\Models\Master_industry;
use App\Models\Master_job_type;
use App\Models\Master_province;
use App\Models\Master_specialization;
use App\Models\User_job_applicant;
use App\Models\User_job_vacancy;
use App\Models\Kjk_job_report;
use App\Models\Kjk_job_view;
use App\Models\User_job_vacancy_question;
use App\Models\User_job_vacancy_question_point;
use App\Models\User_profile;
use App\Models\User;
use App\Models\Kjk_search_history;
use App\Models\Kjk_job_searched;
use App\Models\Kjk_company_photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class JobController extends Controller
{
    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    function index(Request $request){
        $job_list = User_job_vacancy::where("closed", 0)
            ->orderBy("id", "desc")
            ->get();

        $companies = Master_company::whereIn("id", $job_list->pluck("company_id"))
            ->get();

        $province = Master_province::whereIn("id", $companies->pluck("prov_id"))->get();
        $city = Master_city::whereIn("id", $companies->pluck("city_id"))->get();

        $job_type = Master_job_type::whereIn("id", $job_list->pluck("job_type"))->get();

        $bookmark = Job_bookmark::where("user_id", Auth::id())
            ->whereIn("job_id", $job_list->pluck("id"))
            ->get();

        $flokasi = Master_province::orderBy("name")->pluck("name", "id");
        $fspec = Master_specialization::orderBy("name")->pluck("name", "id");
        $ftype = Master_job_type::pluck("name");
        $fedu = Master_educations::pluck("name");
        $fgender = Master_gender::pluck("name");

        $profile = User_profile::where("user_id", Auth::id())->first();

        $searchHistory = Kjk_search_history::where("user_id", Auth::id())
            ->where("created_at", "like", date("Y-m", strtotime("last month"))."%")
            ->get();
        if($searchHistory->count() > 0){
            $user_pref = [
                "lokasi" => [],
                "spec" => []
            ];
            foreach($searchHistory as $item){
                $user_pref["lokasi"] = array_merge($user_pref["lokasi"], $item->lokasi);
                $user_pref["spec"] = array_merge($user_pref["spec"], $item->spec);
            }
            foreach($user_pref as $key => $item){
                $col = [];
                foreach($item as $val){
                    $col[$val]['id'] = $val;
                    $col[$val]['sum'] = isset($col[$val]) && isset($col[$val]['sum']) ? $col[$val]['sum'] + 1 : 1;
                }
                $user_pref[$key] = $col;
            }

            usort($user_pref['lokasi'], function($a, $b){
                return $a['sum'] < $b['sum'];
            });

            usort($user_pref['spec'], function($a, $b){
                return $a['sum'] < $b['sum'];
            });

            $user = User::find(Auth::id());
            $user->search_prefs = $user_pref;
            $user->save();
        }

        $user = User::find(Auth::id());


        $locSelected = [];
        $specSelected = [];

        // if(!empty($user->search_prefs)){
        //     $upref = $user->search_prefs;
        //     if(isset($upref['lokasi'])){
        //         for($i = 0; $i < 3; $i++){
        //             if(isset($upref["lokasi"][$i])){
        //                 $locSelected[] = $upref["lokasi"][$i]['id'];
        //             }
        //         }
        //     }

        //     if(isset($upref['spec'])){
        //         for($i = 0; $i < 3; $i++){
        //             if(isset($upref["spec"][$i])){
        //                 $specSelected[] = $upref["spec"][$i]['id'];
        //             }
        //         }
        //     }
        // } else {
        //     if(!empty($profile)){
        //         if(!empty($profile->prov_id)){
        //             $locSelected[] = $profile->prov_id;
        //         }
        //     }
        // }

        if($request->a){
            if($request->a == "location"){
                $city = Master_city::orderBy("name")->get()->pluck("name");
                $data = [
                    "locations" => $city
                ];

                if($request->t == "autocomplete"){
                    $city = Master_city::where('name', "like", "%$request->term%")->orderBy("name")->get()->pluck("name");
                    return json_encode($city);
                }

                return json_encode($data);
            }
        }

        $cari = json_decode($request->cari ?? "[]", true);

        if(isset($cari['loc'])){
            $_loc = [];
            $_loc = [];
            $_loc['value'] = $cari['loc'];

            $cari['loc'] = json_encode([$_loc]);
        }

        if(!empty($cari)){
            $locSelected = [];
        }

        return view('job.index_applicant', compact("job_list", "cari", "job_type", "companies", "city", "province", "bookmark", 'flokasi', 'fspec', 'ftype', 'fedu', 'fgender', "locSelected", "specSelected"));
    }

    function cari_guest(Request $request){
        $route = route("applicant.job_guest.index");

        $search = [];

        if(!empty($request->position)){
            $search['pos'] = $request->position;
        }

        if(!empty($request->lokasi)){
            $search['loc'] = $request->lokasi;
        }


        $route .= "?cari=".urlencode(json_encode($search));

        return redirect()->to($route);
    }

    function detail($id){
        $views = new Kjk_job_view();
        $views->job_id = $id;
        $views->user_id = Auth::id();
        $views->save();
        $job = User_job_vacancy::find($id);
        $job_type = Master_job_type::find($job->job_type)->first();
        $job->job_type_label = $job_type->name ?? "Fulltime";


        $applicant = User_job_applicant::where("user_id", Auth::id())
            ->where("job_id", $id)
            ->first();

        $profile = Session::get("app_profile");

        $company = Master_company::find($job->company_id);

        $provinsi = Master_province::find($profile->prov_id ?? null);
        $kota = Master_city::find($profile->city_id ?? null);

        $comp_prov = Master_province::find($company->prov_id ?? null);
        $comp_city = Master_city::find($company->city_id ?? null);
        $comp_industry = Master_industry::find($company->industry_id ?? null);
        $bookmark = Job_bookmark::where("user_id", Auth::id())
            ->where("job_id", $job->id)
            ->first();

        $total_applicant = User_job_applicant::where("job_id", $id)->get();

        $comp_photos = Kjk_company_photo::where("company_id", $job->company_id)->take(4)->get();

        $banner = $comp_photos->first();

        $job_ads = User_job_vacancy::where("company_id", $job->company_id)->orderBy("created_at", "desc")
            ->whereNotNUll("confirm_at")
            ->get();

        return view("job.detail_applicant", compact("job", "banner", "job_ads", "applicant", "provinsi", "kota", "company", "comp_prov", "comp_city", "comp_industry", "bookmark", "total_applicant", "comp_photos"));
    }

    function apply(Request $request){
        $apply = User_job_applicant::where("job_id", $request->id)
            ->where("user_id", Auth::id())
            ->first();
        if(empty($apply)){
            $apply = new User_job_applicant();
            $apply->job_id = $request->id;
            $apply->answers = json_encode($request->question ?? "[]");
            $apply->user_id = Auth::id();

            $className = "User_job_applicant";
            $classExp = explode("_", $className);
            $fNameArr = [];
            for ($i=1; $i < count($classExp); $i++) {
                $fNameArr[] = $classExp[$i];
            }
            $fName = implode("_", $fNameArr);

            foreach($request->all() as $key => $item){
                if(!in_array($key, ["_token", "id", "question"])){
                    if(!$request->hasFile($key)){
                        $apply->$key = $item;
                    } else {
                        $file = $request->file($key);
                        $newName = $fName."_$key-".$file->getClientOriginalName();
                        $_dir = str_replace("/", "\\", public_path("media/attachments"));
                        $dir = str_replace("prototype\public_html", "public_html\kerjaku\assets", $_dir);
                        $dir = str_replace("\\", "/", $dir);
                        if($file->move($this->dir, $newName)){
                            $apply->$key = "media/attachments/$newName";
                        }
                    }
                }
            }

            $apply->save();

            $job = User_job_vacancy::find($apply->job_id);

            $company = Master_company::find($job->company_id);

            $data = [];

            $data['users'][] = "$job->user_id";
            $collaborators = $job->collabotators ?? [];
            array_merge($data['users'], $collaborators);

            $data['text'] = Auth::user()->name." melamar pada job ad $job->position";

            $data['url'] = route("job_report.detail_applicant", $apply->id);

            $data['id'] = $apply->id;

            \Notif_kjk::notifSave($data);
        }

        return redirect()->route("applicant.job.applied");
    }

    function applied(){
        return view("job.applied");
    }

    function searchWhereRaw($request, $col){
        $freq = $request ?? [];
        $whereRaw = null;

        if($col == "job_type"){
            $jtype = Master_job_type::whereIn("name", $freq)->get();
            foreach($jtype as $i => $v){
                if($i == 0){
                    $whereRaw = " ($col = $v->id";
                } else {
                    $whereRaw .= " or $col = $v->id";
                }
            }
        } elseif($col == "job_spec"){
            $jspec = Master_specialization::whereIn('id', $freq)->get();
            foreach($jspec as $i => $v){
                if($i == 0){
                    $whereRaw = " ($col = $v->id";
                } else {
                    $whereRaw .= " or $col = $v->id";
                }
            }
        } else {
            foreach($freq as $i => $v){
                if($i == 0){
                    $whereRaw = " ($col like '%$v%'";
                } else {
                    $whereRaw .= " or $col like '%$v%'";
                }
            }
        }

        if(!empty($whereRaw)){
            $whereRaw .= ")";
        }


        return $whereRaw;
    }

    function apply_page($id){
        $job = User_job_vacancy::find($id);
        $job_type = Master_job_type::find($job->job_type)->first();
        $job->job_type_label = $job_type->name ?? "Fulltime";

        $applicant = User_job_applicant::where("user_id", Auth::id())
            ->where("job_id", $id)
            ->first();

        $profile = User_profile::where("user_id", Auth::id())->first();

        $company = Master_company::find($job->company_id);

        $provinsi = Master_province::find($profile->prov_id ?? null);
        $kota = Master_city::find($profile->city_id ?? null);

        $comp_prov = Master_province::find($job->prov_id ?? null);
        $comp_city = Master_city::find($job->city_id ?? null);
        $comp_industry = Master_industry::find($job->industry_id ?? null);
        $bookmark = Job_bookmark::where("user_id", Auth::id())
            ->where("job_id", $job->id)
            ->first();

        $applicant = Auth::user();

        $job_question = User_job_vacancy_question::where("job_id", $job->id)->get();
        $job_opt = User_job_vacancy_question_point::whereIn("q_id", $job_question->pluck('id'))->get();

        return view("job.apply_page", compact("job", "applicant", "provinsi", "kota", "company", "comp_prov", "comp_city", "comp_industry", "bookmark", "job_question", "job_opt", "profile", "applicant"));
    }

    function search(Request $request){
        $job_label = $request->job;
        $locs = $request->loc ?? [];

        $province = Master_city::whereIn("name", $locs)->get();
        $loc_id = $province->pluck("id") ?? [0];

        $comp = Master_company::whereIn("city_id", $loc_id)->get();

        $comp_id = $comp->pluck("id") ?? [0];

        $prov = Master_province::whereIn("name", $request->flokasi ?? [])->get();

        $comp_prov = Master_company::whereIn("prov_id", $prov->pluck("id"))->get();

        $comp_prov_id = Master_company::pluck("prov_id", "id");
        $prov_name = Master_province::pluck("name", "id");

        $whereSpec = $this->searchWhereRaw($request->fspec, "job_spec");
        $whereType = $this->searchWhereRaw($request->ftype, "job_type");
        $whereEdu = $this->searchWhereRaw($request->fedu, "job_description");
        $whereGender = $this->searchWhereRaw($request->fgender, "job_description");

        $is_search = $request->is_search;

        if($is_search){
            $search = new Kjk_search_history();
            $search->user_id = Auth::id() ?? 0;
            $search->lokasi = $prov->pluck("id")->toArray() ?? [];
            $search->spec = $request->fspec ?? [];
            $search->job_type = $request->ftype ?? [];
            $search->edu = $request->fedu ?? [];
            $search->salary = $request->fsalary ?? [];
            $search->save();
        }

        $fsalary = $request->fsalary ?? [];
        $whereSalary = null;
        foreach($fsalary as $i => $v){
            if($i == 0){
                $whereSalary .= "(";
            } else {
                $whereSalary .= " or ";
            }
            if($v == "1.000.000 - 5.000.000"){
                $whereSalary .= "(salary_min >= 1000000 and salary_min <= 5000000)";
            } elseif($v == "5.000.000 - 10.000.000"){
                $whereSalary .= "(salary_min >= 5000000 and salary_min <= 10000000)";
            } elseif($v == "> 10.000.000"){
                $whereSalary .= "(salary_min > 10000000)";
            }
        }
        if(!empty($whereSalary)){
            $whereSalary .= ")";
        }

        $specName = Master_specialization::pluck("name", "id");

        $flokasi = $request->flokasi ?? [];


        $job_list = User_job_vacancy::where("closed", 0)
            ->where(function($q) use($comp, $job_label, $comp_prov, $loc_id, $flokasi) {
                if(!empty($job_label)){
                    $q->where("position", "like", "%$job_label%");
                }

                if(count($loc_id) > 0){
                    $q->whereIn("company_id", $comp->pluck("id"));
                }

                if(count($flokasi) > 0){
                    $q->whereIn("company_id", $comp_prov->pluck("id"));
                }
            })->whereNotNull("confirm_at")->where(function($q){
                if(!empty(Session::get('company_id'))){
                    $q->where("comp_id", Session::get("company_id"));
                }
            })
            // ->whereRaw($whereLok ?? " 1")
            ->whereRaw($whereSpec ?? " 1")
            ->whereRaw($whereType ?? " 1")
            ->whereRaw($whereSalary ?? " 1")
            ->whereRaw($whereEdu ?? " 1")
            ->whereRaw($whereGender ?? " 1")
            ->orderBy('confirm_at', "desc")
            ->get();

        foreach($job_list as $item){
            if($is_search){
                $searched = Kjk_job_searched::firstOrNew(["job_id" => $item->id]);
                $searched->count = $searched->count != null ? $searched->count + 1 : 1;
                $searched->save();
            }

            $item->prov_name = $prov_name[$comp_prov_id[$item->company_id] ?? 0] ?? "-";
            $item->spec_name = $specName[$item->job_spec] ?? "-";
        }

        $sort = $request->sort;
        if(!empty($sort)){
            $sortKey = "";
            if($sort == "Lokasi"){
                $sortKey = "prov_name";
            } elseif($sort == "Salary") {
                $sortKey = "salary_min";
            } elseif($sort == "Job Title") {
                $sortKey = "position";
            } elseif($sort == "Specialis"){
                $sortKey = "spec_name";
            } elseif($sort == "Strata Pendidikan"){
                $sortKey = "edu";
            } elseif($sort == "Tipe Pekerjaan"){
                $sortKey = "job_type";
            }

            if($sortKey != "") $job_list = $job_list->sortBy($sortKey);
        }

        $companies = Master_company::whereIn("id", $job_list->pluck("company_id"))
            ->get();

        $province = Master_province::whereIn("id", $companies->pluck("prov_id"))->get();
        $city = Master_city::whereIn("id", $companies->pluck("city_id"))->get();

        $job_type = Master_job_type::whereIn("id", $job_list->pluck("job_type"))->get();

        $bookmark = Job_bookmark::where("user_id", Auth::id())
            ->whereIn("job_id", $job_list->pluck("id"))
            ->get();

        $applied = User_job_applicant::where("user_id", Auth::id())
            ->whereIn("job_id", $job_list->pluck("id"))
            ->get();

        $view = view("job.search_job", compact("job_list", "job_type", "companies", "city", "province", "bookmark", "applied"))->render();

        $res = [
            "view" => $view
        ];

        return json_encode($res);
    }

    function bookmark(Request $request){
        $job = User_job_vacancy::find($request->id);

        $bookmark = Job_bookmark::where(["job_id" => $job->id, "user_id" => Auth::id()])->first();

        $booked = false;
        if(empty($bookmark)){
            $bookmark = new Job_bookmark();
            $bookmark->job_id = $job->id;
            $bookmark->user_id = Auth::id();
            $bookmark->save();
            $booked = true;
        } else {
            $bookmark->delete();
        }

        return json_encode([
            "booked" => $booked
        ]);
    }

    function report(Request $request){
        $job = User_job_vacancy::find($request->id_job);
        $report = new Kjk_job_report();
        $report->job_id = $request->id_job;
        $report->user_id = Auth::id() ?? 0;
        $report->email = $request->pelapor;
        $report->headline = $request->headline;
        $report->descriptions = $request->deskripsi;
        $report->company_id = Session::get("company_id") ?? ($job->comp_id ?? null);
        $report->save();

        return redirect()->back()->with("report", "done");
    }
}
