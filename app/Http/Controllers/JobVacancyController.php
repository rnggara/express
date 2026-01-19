<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee_test;
use App\Models\Job_vacancy_model;
use App\Models\Job_vacancy_model_users;
use App\Models\Kjk_company_photo;
use App\Models\Kjk_job_ad_package;
use App\Models\Master_city;
use App\Models\Master_company;
use App\Models\Master_district;
use App\Models\Master_educations;
use App\Models\Master_industry;
use App\Models\Master_job_level;
use App\Models\Master_job_type;
use App\Models\Master_province;
use App\Models\Master_specialization;
use App\Models\User;
use App\Models\User_experience;
use App\Models\User_job_vacancy;
use App\Models\User_job_vacancy_question;
use App\Models\User_job_vacancy_question_point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class JobVacancyController extends Controller
{
    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    function index(){

        $job_type = Master_job_type::get();

        if(\Config::get("constants.IS_BP") == 0){
            $myCompany = Master_company::where("id",Auth::user()->comp_id)->get();
            $users = User::where('id', Auth::id())->get();
            $list = User_job_vacancy::where("user_id", Auth::id())->orderBy("id")->get();
        } else {
            $list = User_job_vacancy::orderBy("id")->get();
            $users = User::whereIn("id", $list->pluck("user_id"));
            $myCompany = Master_company::get();
        }
        return view("job.index", compact("list", "myCompany", "job_type", "users"));
    }

    function showJobAdd(Request $request){

        $job_ad = User_job_vacancy::find($request->id);

        $prov = Master_province::get();
        $city = Master_city::get();

        $test = Hrd_employee_test::where("company_id", Session::get("company_id"))->get();

        $job_type = Master_job_type::get();
        $job_spec = Master_specialization::get();
        $jabatan = Master_job_level::get();
        $edu = Master_educations::get();

        if($request->t){
            if($request->t == "kecamatan"){
                $f = json_decode($request->f, true);

                $kec_sel = [];

                if($f['prov'] == null){
                    $kec_sel = Master_district::select(['id', 'name as text', 'city_id'])->where('name', "like", "%$request->q%")->get();
                } else {
                    if($f['city'] != null){
                        $kec_sel = Master_district::select(['id', 'name as text', 'city_id'])->where("city_id", $f['city'])->where('name', "like", "%$request->q%")->get();
                    } else {
                        $_city = $city->where("prov_id", $f['prov']);
                        $kec_sel = Master_district::select(['id', 'name as text', 'city_id'])->whereIn("city_id", $_city->pluck("id"))->where('name', "like", "%$request->q%")->get();
                    }
                }

                foreach($kec_sel as $item){
                    if(!empty($company) && $item->id == $item->kec_id){
                        $item->selected = true;
                    }
                }

                $arr = [
                    "results" => $kec_sel
                ];

                return json_encode($arr);
            }
        }

        $company = Master_company::find(Auth::user()->comp_id);
        $industri = Master_industry::find($company->industry_id ?? null);
        $compImages = Kjk_company_photo::where('company_id', $company->id ?? null)->get();

        $packages = Kjk_job_ad_package::where('company_id', $company->id ?? null)
            ->where("status", 1)
            ->get();

        $kec = Master_district::where('id', $company->kec_id ?? 0)->get();
        $kec_name = $kec->pluck("name", "id");

        $rekomendasiSoal = User_job_vacancy_question::where("company_id", Auth::user()->company_id)->get();

        if($request->id){
            if($request->e == "soal"){
                $soal = User_job_vacancy_question::where("job_id", $request->id)->get();
                $point = User_job_vacancy_question_point::whereIn("q_id", $soal->pluck("id"))->get();


                $dataSoal = [];
                foreach($soal as $item){
                    $soal_point = $point->where("q_id", $item->id);
                    $spoint = [];
                    foreach($soal_point as $val){
                        $col = [];
                        $col['id'] = $val->id;
                        $col['label'] = $val->label;
                        $col['is_true'] = $val->is_true;
                        $spoint[] = $col;
                    }
                    $icol = [];
                    $icol['soal'] = $item->label;
                    $icol['type'] = $item->type;
                    $icol['id'] = $item->id;
                    $icol['is_required'] = $item->is_required;
                    $icol['options'] = $spoint;
                    $dataSoal[] = $icol;
                }

                return json_encode([
                    "data" => $dataSoal
                ]);
            }
        }

        return view("job.add_page", compact('job_ad', 'prov', "city", "test", "job_type", "job_spec", "jabatan", "edu", "company", "industri", "compImages", "packages", "kec_name", "rekomendasiSoal"));
    }

    function add(Request $request){
        $company = Master_company::find(Auth::user()->comp_id);
        $job = User_job_vacancy::find($request->job_id);
        $soal = $request->soal ?? [];
        if(empty($job)){
            $job = new User_job_vacancy();
            $job->user_id = Auth::id();
            $job->created_by = Auth::user()->id;
            $job->comp_id = Session::get("company_id");
        } else {
            $job_soal = [];
            foreach($soal as $item){
                if(isset($item['id'])){
                    $job_soal[] = $item['id'];
                }
            }
            $js = User_job_vacancy_question::where("job_id", $job->id)->whereNotIn("id", $job_soal)->pluck("id")->toArray();

            if(count($js) > 0){
                User_job_vacancy_question::whereIn("id", $js)->delete();
                User_job_vacancy_question_point::whereIn("q_id", $js)->delete();
            }
        }

        $test_sel = [];
        $_test = $request->test ?? [];
        $_test_point = $request->test_point;
        foreach($_test as $i => $item){
            $col = [];
            $col['id'] = $i;
            $col['point'] = $_test_point[$i];
            $test_sel[] = $col;
        }

        $package = Kjk_job_ad_package::find($request->package);

        $pdate = explode("/", $request->tanggal_posting);
        $pdate_label = $pdate[2]."-".$pdate[1]."-".$pdate[0];

        $job->company_id = $company->id ?? null;
        $job->position = $request->position;
        $job->job_type = $request->job_type;
        $job->jabatan = $request->jabatan;
        $job->yoe = $request->yoe;
        $job->job_spec = $request->job_spec;
        $job->edu = $request->edu;
        $job->prov_id = $request->prov_id;
        $job->city_id = $request->city_id;
        $job->kec_id = $request->kec_id;
        $job->kode_pos = $request->kode_pos;
        $job->detail_lokasi = $request->detail_lokasi;
        $job->salary_min = str_replace(",", "", $request->salary_min);
        $job->salary_max = str_replace(",", "", $request->salary_max);
        $job->show_salary = $request->show_salary ?? 0;
        $job->test_selected = json_encode($test_sel);
        $job->job_description = $request->job_description;
        $job->package_id = $package->id ?? null;
        $job->posting_date = $pdate_label;
        $job->confirm_at = null;
        $job->rejected_at = null;
        $job->save();


        $q_sel = $request->q_sel;
        foreach($soal as $i => $item){
            $_isSel = $q_sel[$i] ?? 0;
            $sId = $item['id'] ?? null;
            $nQ = User_job_vacancy_question::findOrNew($sId);
            $nQ->job_id = $job->id;
            $nQ->label = $item['label'];
            $nQ->type = $item['type'];
            $nQ->is_required = $_isSel;
            $nQ->company_id = $job->comp_id;
            $nQ->save();

            $opt = $item['opt'] ?? [];
            foreach($opt as $opVal){
                $opId = $opVal['id'] ?? null;
                $nQp = User_job_vacancy_question_point::findOrNew($opId);
                $nQp->q_id = $nQ->id;
                $nQp->label = $opVal['label'];
                $nQp->is_true = $opVal['is_true'] == true ? 1 : 0;
                $nQp->save();
            }
        }

        if(!empty($company)){
            $company->descriptions = $request->description_company;
            $company->youtube_link = $request->youtube_link;
            // if($company->package != $package->id){
            //     $company->package_id = $request->package;
            //     $company->job_credit = $package->job_credit;
            //     $company->search_credit = $request->search_applicant;
            // } else {
            //     $company->job_credit -= 1;
            // }

            $company->save();
        }

        return redirect()->route("job_report.index");

        // $package = $request->package;
        // if(!empty($package)){
        //     return redirect()->route("job.confirmation", $job->id);
        // } else {
        //     // masuk halaman job ad sudah dibuat dan menunggu konfirmasi penayangan
        // }
    }

    function confirmation_page($id){
        $job = User_job_vacancy::find($id);
        $company = Master_company::find($job->company_id);
        $package = Kjk_job_ad_package::find($job->package_id);
        $test = Hrd_employee_test::where("company_id", Session::get("company_id"))->get();

        $job_type = Master_job_type::get();
        $job_spec = Master_specialization::get();
        $jabatan = Master_job_level::get();
        $edu = Master_educations::get();

        $prov = Master_province::find($company->prov_id ?? null);
        $city = Master_city::find($company->city_id ?? null);
        $kec = Master_district::find($company->kec_id ?? null);

        $user = User::find(Auth::id());

        return view("job.job_ad_confirmation", compact("job", "company", "package", "test", "job_type", "job_spec", "jabatan", "edu", "user", 'prov', 'city', 'kec'));
    }

    function delete($id){
        User_job_vacancy::find($id)->delete();

        return redirect()->back();
    }
}
