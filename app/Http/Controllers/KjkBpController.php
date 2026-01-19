<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\ConfigCompany;
use App\Models\User;
use App\Models\User_job_vacancy;
use App\Models\Master_company;
use App\Models\User_job_applicant;
use App\Models\Kjk_job_view;
use App\Models\Master_province;
use App\Models\Master_city;
use App\Models\Master_job_type;
use App\Models\Master_industry;
use App\Models\Kjk_company_review;
use App\Models\Hrd_employee_test;
use App\Models\User_experience;
use App\Models\User_formal_education;
use App\Models\User_informal_education;
use App\Models\User_language_skill;
use App\Models\User_skill;
use App\Models\User_portofolio;
use App\Models\User_medsos;
use App\Models\User_add_family;
use App\Models\User_add_id_card;
use App\Models\User_add_license;
use App\Models\User_profile;
use App\Models\Master_language;
use App\Models\Master_proficiency;
use App\Models\Master_gender;
use App\Models\Master_marital_status;
use App\Models\Hrd_employee_test_result;
use DateTime;

class KjkBpController extends Controller
{

    function __construct(){
        Session::put("company_id", 1);
    }

    function clients_index(){

        $clients = ConfigCompany::get();

        $list = [
            "accounts" => "Accounts",
            "personal" => "Personel",
            "attendance" => "Mobile Attendance",
            "locations" => "Locations",
            "clients" => "Clients",
            "variables" => "Variables",
            "test" => "Test",
        ];

        return view("_bp.clients.index", compact("clients", "list"));
    }

    function clients_navigate(Request $request){
        $target = $request->navigate_to;
        $comp_id = $request->company_id;

        Session::put("company_id", $comp_id);

        if($target == "accounts"){
            return redirect()->route("company.user", base64_encode($comp_id));
        } elseif($target == "personal"){
            return redirect()->route("employee.index");
        } elseif($target == "attendance"){
            return redirect()->route("emp.mt.index");
        } elseif($target == "locations"){
            return redirect()->route("wh.index");
        } elseif($target == "clients"){
            return redirect()->route("marketing.client.index");
        } elseif($target == "variabels"){
            return redirect()->route("employee_variables", $comp_id);
        } elseif($target == "test"){
            return redirect()->route("hrd.test.index");
        }

        return redirect()->back();
    }

    function employers_index(){

        $comps = Master_company::get();

        $prov = Master_province::whereIn("id", $comps->pluck("prov_id"))->pluck("name", "id");

        $employers = User::whereIn("comp_id", $comps->pluck("id"))
            ->whereNotNull("is_owner");
        $employers_name = $employers->pluck("name", "id");
        $employers_email = $employers->pluck("email", "id");

        $job_ads = User_job_vacancy::whereIn("company_id", $comps->pluck("id"))->get();

        $indus = Master_industry::whereIn("id", $comps->pluck("industry_id"))->pluck("name", "id");

        $reviews = Kjk_company_review::whereIn("company_id", $comps->pluck("id"));

        return view("_bp.employers.index", compact("employers_name", "employers_email", "job_ads", "comps", "prov", "indus", "reviews"));
    }

    function employers_job_ads($id){
        $myComp = Master_company::find($id);
        $job_list = User_job_vacancy::where("company_id", $myComp->id)
            ->orderBy("id", "desc")
            ->get();

        $applicant = User_job_applicant::whereIn("job_id", $job_list->pluck("id"))
            ->get();

        $views = Kjk_job_view::whereIn("job_id", $job_list->pluck("id"))
            ->get();

        $users = User::whereIn('id', $job_list->pluck("user_id"))->get()->pluck("name", "id");

        $user_collab = User::where("comp_id", $myComp->comp_id)
            ->get();

        return view("_bp.employers.job_ads", compact("job_list", "users", "applicant", "views", "user_collab", "myComp"));
    }

    function employers_company($id){
        $mComp = Master_company::find($id);

        $comp_prov = Master_province::find($mComp->prov_id);
        $comp_city = Master_city::find($mComp->city_id);

        return view("_bp.employers.company", compact('mComp', "comp_city", "comp_prov"));
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    function applicants_index(){

        $applicants = User::where("role_access", "like", '%"applicant"%')
            ->get();

        $job_applied = User_job_applicant::whereIn("user_id", $applicants->pluck("id"))->orderBy("created_at", "desc")->get();

        $job = User_job_vacancy::WhereIn("id", $job_applied->pluck("job_id"))->get();

        $job_name = $job->pluck("position", "id")->toArray();
        $job_id = $job_applied->pluck("job_id", "user_id")->toArray();

        $mComp = Master_company::pluck("company_name", "id")->toArray();

        $columnExport = \Config::get("constants.EXPORT_COLUMNS_APPLICANT");
        $test_name = Hrd_employee_test::pluck("label", "id");

        $exp = User_experience::whereIn("user_id", $applicants->pluck("id"))
            ->orderBy("start_date")
            ->get();
        $exp_list = [];
        foreach($exp as $item){
            $exp_list[$item->user_id][] = $item;
        }

        $edu = User_formal_education::whereIn("user_id", $applicants->pluck("id"))
            ->orderBy("start_date", "desc")
            ->get();
        $edu_list = [];
        foreach($edu as $item){
            $edu_list[$item->user_id][] = $item;
        }

        $testSel = Hrd_employee_test::get();

        $test = Hrd_employee_test_result::whereIn('user_id', $applicants->pluck("id"))
            ->whereIn('test_id', $testSel->pluck("id"))
            ->whereNotNull("result_detail")
            ->whereNotNull("result_point")
            ->where("result_point","<=", "100")
            ->orderBy("result_point", "desc")
            ->get();
        $test_list = [];
        foreach($test as $item){
            $test_list[$item->user_id][] = $item;
        }

        $province = Master_province::pluck("name", "id");

        foreach($applicants as $item){
            $uExp = $exp_list[$item->id] ?? [];
            $item->yoe = 0;
            $item->salary = 0;
            if(!empty($uExp)){
                $first = $uExp[0];
                $last = end($uExp);
                if(!empty($first) && ($first->start_date != "-" && !empty($first->start_date))){
                    if($this->validateDate($first->start_date)){
                        $d1 = date_create($first->start_date);
                        $d2 = date_create(date("Y-m-d"));
                        $diff = date_diff($d1, $d2);
                        $item->yoe = $diff->format("%y");
                    }
                }

                $item->salary = $last->salary;
            }

            $item->edu = "-";
            $uEdu = $edu_list[$item->id] ?? [];
            if(!empty($uEdu)){
                $first = $uEdu[0];
                $item->edu = $first->degree." ".$first->field_of_study;
            }
            $test_score = 0;
            if(isset($test_list[$item->id])){
                $test_score = $test_list[$item->id][0]->result_point;
            }
            $score_class = "danger";
            if($test_score >= 50 && $test_score < 80){
                $score_class = "warning";
            } elseif($test_score >= 80){
                $score_class = "success";
            }

            $item->score = $test_score;
            $item->score_class = $score_class;
        }

        $test_name = $testSel->pluck("label", "id");

        return view("_bp.applicants.index", compact("applicants", "job_applied", "mComp", "columnExport", "test_name", "province", "job_name"));
    }

    function applicants_detail($id, Request $Request){
        $applicant = User::find($id);

        $experience = User_experience::where('user_id', $applicant->id)
            ->orderBy("start_date", "desc")
            ->get();
        foreach($experience as $item){
            $end_date = $item->end_date != null ? $item->end_date : date("Y-m-d");
            $d1 = date_create(date("Y-m-d H:i:s", strtotime($item->start_date)));
            $d2 = date_create(date("Y-m-d H:i:s", strtotime($end_date)));
            $diff = date_diff($d1, $d2);
            $y = $diff->format("%y");
            $item->yoe = $y;
        }

        $edu_formal = User_formal_education::where('user_id', $applicant->id)
            ->orderBy("start_date", "desc")
            ->get();

        $edu_informal = User_informal_education::where('user_id', $applicant->id)
            ->orderBy("start_date", "desc")
            ->get();

        $languages = User_language_skill::where('user_id', $applicant->id)
            ->get();

        $skills = User_skill::where('user_id', $applicant->id)
            ->get();

        $portofolio = User_portofolio::where('user_id', $applicant->id)
            ->first();
        $medsos = User_medsos::where('user_id', $applicant->id)
            ->get();
        $add_family = User_add_family::where('user_id', $applicant->id)
            ->get();
        $add_id_card = User_add_id_card::where('user_id', $applicant->id)
            ->get();
        $add_license = User_add_license::where('user_id', $applicant->id)
            ->get();

        $test = Hrd_employee_test::get();

        $test_result = Hrd_employee_test_result::where('user_id', $applicant->id)
            ->whereIn('test_id', $test->pluck("id"))
            ->whereNotNull("result_detail")
            ->whereNotNull("result_point")
            ->where("result_point","<=", "100")
            ->orderBy("result_point", "desc")
            ->get();
        $test_list = [];
        foreach($test_result as $item){
            $test_list[$item->test_id][] = $item;
        }

        $exp = $experience->first() ?? [];

        $profile = User_profile::where("user_id", $applicant->id)->first();

        $data['prov'] = Master_province::get();
        $data['city'] = Master_city::get();
        $data['language'] = Master_language::get()->pluck("name", "id");
        $data['proficiency'] = Master_proficiency::get()->pluck("name", "id");

        $data['family'] = User_add_family::where("user_id", $id)->get();
        $data['license'] = User_add_license::where("user_id", $id)->get();
        $data['id_card'] = User_add_id_card::where("user_id", $id)->first();
        $data['marital_status'] = Master_marital_status::get();
        $data['gender'] = Master_gender::get();

        $wpt = \App\Models\Kjk_wpt_result::whereIn("test_result_id", $test_result->pluck("id"))
            ->get();

        $wpt_iq = \App\Models\Kjk_wpt_score_iq::pluck("iq", "score");
        $wpt_interpretasi = \App\Models\Kjk_wpt_interpretasi::pluck("label", "score");
        $back = route("bp.applicants.index");

        return view("_employer.search_talent.detail", compact("data","back","profile", "wpt", "wpt_iq", "wpt_interpretasi", "exp", "test_list", "test", "applicant", "experience", "edu_formal", "edu_informal", "languages", "skills", "portofolio", "add_family", "add_id_card", "add_license"));
    }

    function applicants_status($id){
        $user = User::find($id);

        $user->suspended = $user->suspended == 1 ? null : 1;
        $user->save();

        return redirect()->back();
    }

    function job_ads_index(){
        $job_ads = User_job_vacancy::orderBy("created_at", "desc")
            ->get();

        $user = User::whereIn("id", $job_ads->pluck("user_id"))->pluck("name", "id");

        $company = Master_company::whereIn("id",$job_ads->pluck("company_id"))->pluck("company_name", "id");

        return view("_bp.job_ads.index", compact("job_ads", "user", "company"));
    }

    function job_ads_review($id){
        $job = User_job_vacancy::find($id);
        $job_type = Master_job_type::find($job->job_type)->first();
        $job->job_type_label = $job_type->name ?? "Fulltime";

        $profile = Session::get("app_profile");

        $company = Master_company::find($job->company_id);

        $provinsi = Master_province::find($profile->prov_id ?? null);
        $kota = Master_city::find($profile->city_id ?? null);

        $comp_prov = Master_province::find($job->prov_id ?? null);
        $comp_city = Master_city::find($job->city_id ?? null);
        $comp_industry = Master_industry::find($company->industry_id ?? null);


        return view("_bp.job_ads.review", compact("job", "provinsi", "kota", "company", "comp_prov", "comp_city", "comp_industry"));
    }

    function job_ads_review_post(Request $request){

        $job = User_job_vacancy::find($request->id);

        if($request->submit == 1){
            $job->confirm_at = date("Y-m-d H:i:s");
            $job->confirm_by = Auth::id();
        } else {
            $job->rejected_at = date("Y-m-d H:i:s");
            $job->rejected_by = Auth::id();
            $job->rejected_notes = $request->reason;
        }

        $job->save();

        return redirect()->back()->with(["tayang" => $request->submit]);
    }
}
