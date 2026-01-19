<?php

namespace App\Http\Controllers;

use App\Exports\JobReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User_job_vacancy;
use App\Models\User_job_applicant;
use App\Models\Master_company;
use App\Models\Kjk_job_view;
use App\Models\User;
use App\Models\User_experience;
use App\Models\User_formal_education;
use App\Models\Hrd_employee_test;
use App\Models\Hrd_employee_test_result;
use App\Models\User_informal_education;
use App\Models\User_language_skill;
use App\Models\User_skill;
use App\Models\User_portofolio;
use App\Models\User_medsos;
use App\Models\User_add_family;
use App\Models\User_add_id_card;
use App\Models\User_add_license;
use App\Models\User_profile;
use App\Models\Master_province;
use App\Models\Master_city;
use App\Models\Master_language;
use App\Models\Master_proficiency;
use App\Models\User_interview_group;
use App\Models\Master_job_type;
use App\Models\Master_industry;
use App\Models\Master_marital_status;
use App\Models\Master_gender;
use App\Models\Kjk_company_photo;
use Maatwebsite\Excel\Facades\Excel;

class KjkJobReport extends Controller
{
    private $columnExport;

    public function __construct() {
        $this->columnExport = [
            "name" => "Nama Lengkap",
            "birth_date" => "Tanggal Lahir",
            "email" => "Email",
            "religion" => "Agama",
            "gender" => "Jenis Kelamin",
            "phone" => "Nomor Telepon",
            "married" => "Status Pernikahan",
            "province" => "Provinsi",
            "city" => "Kota",
            "address" => "Alamat",
            "exp" => "Pengalaman Kerja",
            "edu" => "Pendidikan",
            "salary" => "Gaji yang diharapkan",
            "website" => "Website",
            "behance" => "Behance",
            "dribble" => "Dribble",
            "github" => "Github",
            "mobile" => "Mobile",
            "other_link" => "Link Lain",
            "test_result" => ["1", "2", "9", "13", "16", "17"]
        ];
    }

    function index(Request $request){
        $jobCollabs = User_job_vacancy::where("collaborators", "like", '%"'.Auth::id().'"%')->get();
        $myComp = Master_company::find(Auth::user()->comp_id);
        $job_list = User_job_vacancy::where("user_id", Auth::id())
            ->orWhereIn("id", $jobCollabs->pluck("id"))
            ->orderBy("id", "desc")
            ->get();

        $applicant = User_job_applicant::whereIn("job_id", $job_list->pluck("id"))
            ->get();

        $views = Kjk_job_view::whereIn("job_id", $job_list->pluck("id"))
            ->get();

        $users = User::whereIn('id', $job_list->pluck("user_id"))->get()->pluck("name", "id");

        $user_collab = User::where("comp_id", Auth::user()->comp_id)
            ->where("id", "!=", Auth::id())
            ->get();

        $a = $request->a ?? null;
        if($a == "applicant"){
            return $this->detail(0, $a);
        }

        return view("_employer.job_report.index", compact("job_list", "users", "applicant", "views", "user_collab", "a", "jobCollabs"));
    }

    function preview($id){
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

        $total_applicant = User_job_applicant::where("job_id", $id)->get();

        $comp_photos = Kjk_company_photo::where("company_id", $job->company_id)->take(4)->get();

        $banner = $comp_photos->first();


        return view("_employer.job_report.preview", compact("job", "applicant", "provinsi", "kota", "company", "comp_prov", "comp_city", "comp_industry", "total_applicant", "comp_photos", "banner"));
    }

    function detail($id, $all = null){
        if($all != null){
            $jobCollabs = User_job_vacancy::where("collaborators", "like", '%"'.Auth::id().'"%')->get();
            $job = User_job_vacancy::where("user_id", Auth::id())
                ->orWhereIn("id", $jobCollabs->pluck("id"))->get();
        } else {
            $job = User_job_vacancy::find($id);
        }

        $applicant = User_job_applicant::where(function($q) use($all, $job){
            if(!empty($all)){
                $q->whereIn("job_id", $job->pluck("id"));
            } else {
                $q->where("job_id", $job->id);
            }
        })->where("status", "<=", 3)->orderBy("created_at", "desc")->get();

        $users = User::whereIn('id', $applicant->pluck("user_id"))->get();

        $exp = User_experience::whereIn("user_id", $users->pluck("id"))
            ->orderBy("start_date")
            ->get();
        $exp_list = [];
        foreach($exp as $item){
            $exp_list[$item->user_id][] = $item;
        }

        $edu = User_formal_education::whereIn("user_id", $users->pluck("id"))
            ->orderBy("start_date", "desc")
            ->get();
        $edu_list = [];
        foreach($edu as $item){
            $edu_list[$item->user_id][] = $item;
        }

        $testSel = Hrd_employee_test::get();

        $test = Hrd_employee_test_result::whereIn('user_id', $applicant->pluck("id"))
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

        foreach($users as $item){
            $uExp = $exp_list[$item->id] ?? [];
            $item->yoe = 0;
            $item->salary = 0;
            if(!empty($uExp)){
                $first = $uExp[0];
                $last = end($uExp);
                if(!empty($first) && ($first->start_date != "-" && !empty($first->start_date))){
                    $d1 = date_create(date("Y-m-d", strtotime($first->start_date)));
                    $d2 = date_create(date("Y-m-d"));
                    $diff = date_diff($d1, $d2);
                    $item->yoe = $diff->format("%y");
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

        $columnExport = $this->columnExport;

        $test_name = $testSel->pluck("label", "id");

        if(!empty($all)){
            $a = $all;
            $job_list = $job;
            $job_name = $job->pluck("position", "id")->toArray();
            return view("_employer.job_report.index_applicant", compact("job", "job_list", 'applicant', 'users', "a", "job_name", "province", "columnExport", 'test_name'));
        }


        return view("_employer.job_report.detail", compact("job", 'applicant', 'users', "province", "columnExport", 'test_name'));
    }

    function export_excel(Request $request){
        $columns = $request->column;
        $jobIds = [];
        $all = $request->type;
        if($all == "_all"){
            $jobIds = User_job_vacancy::where("user_id", Auth::id())->get();
        } elseif($all == "bp"){
            $jobIds = [];
        } {
            $jobIds = User_job_vacancy::where("id", $request->id)->get();;
        }
		return Excel::download(new JobReportExport($this->columnExport, $columns, $jobIds, $all), 'job-report.xlsx');
	}

    function nonaktifkan(Request $request){
        $job = User_job_vacancy::find($request->id);

        $job->activate_at = empty($job->activate_at) ? date("Y-m-d H:i:s") : null;

        $job->save();

        return redirect()->back();
    }

    function detail_applicant($id, Request $request){
        $job_applicant = User_job_applicant::find($id);
        $job = User_job_vacancy::find($job_applicant->job_id);
        $applicant = User::find($job_applicant->user_id);

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
            ->whereNotNull("proficiency")
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

        $returnUri = route("job_report.detail", $job_applicant->job_id);
        if($request->a){
            if($request->a == "applicant"){
                $returnUri = route("job_report.index")."?a=applicant";
            }
        }

        $wpt = \App\Models\Kjk_wpt_result::whereIn("test_result_id", $test_result->pluck("id"))
            ->get();

        $wpt_iq = \App\Models\Kjk_wpt_score_iq::pluck("iq", "score");
        $wpt_interpretasi = \App\Models\Kjk_wpt_interpretasi::pluck("label", "score");

        $data['family'] = User_add_family::where("user_id", $id)->get();
        $data['license'] = User_add_license::where("user_id", $id)->get();
        $data['id_card'] = User_add_id_card::where("user_id", $id)->first();
        $data['marital_status'] = Master_marital_status::get();
        $data['gender'] = Master_gender::get();

        return view("_employer.job_report.detail_applicant", compact("data","profile","returnUri", 'wpt',"exp",'wpt_iq', 'wpt_interpretasi', "test_list", "test", "job", "job_applicant", "applicant", "experience", "edu_formal", "edu_informal", "languages", "skills", "portofolio", "add_family", "add_id_card", "add_license"));
    }

    function update(Request $request){
        $job_applicant = User_job_applicant::find($request->id);
        $job_applicant->status = $request->submit;
        $job_applicant->save();

        return redirect()->to($request->returnUri);
    }

    function assign_collaborator(Request $request){
        $job = User_job_vacancy::find($request->job_id);

        $job->collaborators = json_encode($request->user_id ?? []);
        $job->save();

        return redirect()->back()->with(["msg" => "Kobalorator Berhasil di tambahkan"]);
    }

    function get_collaborator($id){
        $job = User_job_vacancy::find($id);

        $data = [];
        if(!empty($job)){
            $collaborators = json_decode($job->collaborators ?? "[]");
            $users = User::select("id", "name", "dept", "do_code")->whereIn("id", $collaborators)->get();
            foreach($users as $item){
                $col = [];
                $col['id'] = $item->id;
                $col['name'] = $item->name;
                $col['pos'] = ($item->dept ?? "")." ".($item->do_code ?? "");
                $data[] = $col;
            }
        }

        return json_encode([
            "data" => $data
        ]);
    }

    function backlog(Request $request){
        $list_id = json_decode($request->list_id ?? "[]");

        $backlog_at = date("Y-m-d H:i:s");

        $group_code = null;

        if($request->interview == 2){
            $group_code = base64_encode(Auth::id()."_$backlog_at");
        }

        $group = [];

        foreach($list_id as $item){
            $applicant = User_job_applicant::find($item);
            $applicant->status = 2;
            $applicant->backlog_at = $backlog_at;
            $applicant->backlog_by = Auth::id();
            $applicant->group_code = $group_code;
            $applicant->save();

            if($request->interview == 2){
                $group[$group_code][$applicant->job_id] = $applicant->job_id;
            }
        }

        foreach($group as $code => $job_id){
            foreach($job_id as $item){
                $group = new User_interview_group();
                $group->job_id = $item;
                $group->user_id = Auth::id();
                $group->group_code = $code;
                $group->save();
            }
        }

        return redirect()->back()->with(["msg" => [
            "title" => "Kandidat Sudah di tambahkan  ke backlog",
            "message" => "Kandidat berhasil di tambahkan ke backlog silahkan atur jadwal interview di kalender"
        ]]);
    }

    function reject_applicant(Request $request){
        $list_id = json_decode($request->list_id ?? "[]");

        $backlog_at = date("Y-m-d H:i:s");

        foreach($list_id as $item){
            $applicant = User_job_applicant::find($item);
            $applicant->status = -1;
            $applicant->reject_at = $backlog_at;
            $applicant->reject_by = Auth::id();
            $applicant->save();
        }

        return redirect()->back()->with(["msg" => [
            "title" => "Kandidat telah di Reject",
            "message" => "Kandidat berhasil di Reject"
        ]]);
    }
}
