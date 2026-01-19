<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User_experience;
use App\Models\User;
use App\Models\User_formal_education;
use App\Models\Hrd_employee_test;
use App\Models\Hrd_employee_test_result;
use App\Models\Kjk_employer_bookmark;
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
use App\Models\Master_job_level;
use App\Models\Master_educations;
use App\Models\Master_industry;
use App\Models\Master_specialization;
use App\Models\Master_proficiency;
use App\Models\Master_marital_status;
use App\Models\Master_gender;

class KjkSearchTalent extends Controller
{
    function index(Request $request){

        $sort = [
            "location" => "Preferred Work Location",
            "salary_idr" => "Salary (IDR)",
            "industry" => "Industries",
            "language" => "Language",
            "yoe" => "Years of Experience",
            "edu" => "Education Level",
            "edu_pos" => "Education Position Level",
            "specialization" => "Specialization",
        ];

        $dataSort['location'] = Master_province::pluck("name", "id");
        $dataSort['industry'] = Master_industry::pluck("name", "id");
        $dataSort['language'] = Master_language::pluck("name", "id");
        $dataSort['edu'] = Master_educations::pluck("name", "name");
        $dataSort['specialization'] = Master_specialization::pluck("name", "id");
        $dataSort['edu_pos'] = Master_job_level::pluck("name", "id");
        $dataSort['yoe'] = [
            "0" => "<1 Tahun",
            "1" => "1 Tahun",
            "2" => "2 Tahun",
            "3" => "3 Tahun",
            "4" => "4 Tahun",
            "5" => "5 Tahun",
            ">5" => "> 5 Tahun",
        ];
        $dataSort['salary_idr'] = [
            "1" => "< 1.000.000",
            "2" => "1.000.000 - 5.000.000",
            "3" => "> 5.000.000",
        ];

        if($request->a){
            if($request->a == "tag"){
                if($request->q == "jabatan"){
                    $list = [];
                    $jabatan_list = User_experience::whereNotNull("position")->get()->pluck("position");
                    $list = array_unique($jabatan_list->count() == 0 ? [] : $jabatan_list->toArray());
                    $arr = [
                        "tags" => array_values(count($list) == 0 ? [] : $list)
                    ];

                    return json_encode($arr);
                }

                if($request->q == "skill"){
                    $list = [];
                    $skill_list = User_skill::whereNotNull("skill_name")->get()->pluck("skill_name");
                    $list = array_unique($skill_list->count() == 0 ? [] : $skill_list->toArray());
                    $arr = [
                        "tags" => array_values(count($list) == 0 ? [] : $list),
                    ];

                    return json_encode($arr);
                }

                if($request->q == "pengalaman"){
                    $list = [];
                    $jabatan_list = User_experience::whereNotNull("position")->get()->pluck("position");
                    $list = array_unique($jabatan_list->count() == 0 ? [] : $jabatan_list->toArray());
                    $arr = [
                        "tags" => array_values(count($list) == 0 ? [] : $list)
                    ];

                    return json_encode($arr);
                }
            }
        }

        return view("_employer.search_talent.index", compact("sort", "dataSort"));
    }

    function search(Request $request){
        $jabatan = json_decode($request->jabatan ?? "[]");
        $skill = json_decode($request->skill ?? "[]");
        $pengalaman = json_decode($request->pengalaman ?? "[]");
        $filter['location'] = $request->cklocation ?? [];
        $filter['salary_idr'] = $request->cksalary_idr ?? [];
        $filter['industry'] = $request->ckindustry ?? [];
        $filter['language'] = $request->cklanguage ?? [];
        $filter['yoe'] = $request->ckyoe ?? [];
        $filter['edu'] = $request->ckedu ?? [];
        $filter['edu_pos'] = $request->ckedu_pos ?? [];
        $filter['specialization'] = $request->ckspecialization ?? [];
        $fname = $request->name;
        $enableFilter = false;
        foreach($filter as $key => $item){
            if(count($item) > 0 && $key != "yoe"){
                $enableFilter = true;
                break;
            }
        }

        $uid_filter = [];

        if($enableFilter){

            $user_filter_loc = User_profile::whereIn("prov_id", $filter['location'])->pluck("user_id")->toArray();
            // $uid_filter = array_merge($uid_filter, $user_filter_loc);
            $user_filter_exp = User_experience::where(function($q) use($filter){
                if(count($filter['salary_idr']) > 0){
                    foreach($filter['salary_idr'] as $salary_idr){
                        if($salary_idr == 1){
                            $min = 0;
                            $max = 1000000;
                            $q->orWhereBetween("salary", [$min, $max]);
                        } elseif($salary_idr == 2){
                            $min = 1000000;
                            $max = 5000000;
                            $q->orWhereBetween("salary", [$min, $max]);
                        } else {
                            $min = 5000000;
                            $q->orWhere("salary", ">=" ,$min);
                        }
                    }
                }

                $q->orWhereIn("industry", $filter['industry']);

                $q->orWhereIn("specialization", $filter['specialization']);

                $q->orWhereIn("job_type", $filter['edu_pos']);
            })->where(function($q) use($user_filter_loc){
                if(count($user_filter_loc) > 0){
                    $q->whereIn("user_id", $user_filter_loc);
                }
            })->pluck("user_id")->toArray();

            $user_filter_lang = User_language_skill::whereIn("language", $filter['language'])->where(function($q) use($user_filter_loc){
                if(count($user_filter_loc) > 0){
                    $q->whereIn("user_id", $user_filter_loc);
                }
            })->pluck("user_id")->toArray();
            $user_filter_edu = User_formal_education::whereIn("degree", $filter['edu'])->where(function($q) use($user_filter_loc){
                if(count($user_filter_loc) > 0){
                    $q->whereIn("user_id", $user_filter_loc);
                }
            })->pluck("user_id")->toArray();
            $uid_filter = array_merge($uid_filter, $user_filter_loc);
        }


        $uid1 = User_experience::where(function($q) use($uid_filter, $enableFilter, $jabatan){

            $q->where(function($p) use($jabatan){
                if(count($jabatan) > 0){
                    foreach($jabatan as $item){
                        $p->orWhere("position", "like", "%$item->value%");
                    }
                }
            });
        })->where(function($q) use($pengalaman){
            if(count($pengalaman) > 0){
                foreach($pengalaman as $item){
                    $q->orWhere("position", "like", "%$item->value%");
                }
            }
        })->get();

        $uid2 = User_skill::where(function($q) use($skill){
            if(count($skill) > 0){
                foreach($skill as $item){
                    $q->orWhere("skill_name", "like", "%$item->value%");
                }
            } else {
                $q->where("skill_name", "-1111");
            }
        })->get();

        $uid = [];
        $uid = array_merge($uid, $uid1->pluck("user_id")->toArray());
        $uid = array_merge($uid, $uid2->pluck("user_id")->toArray());
        // $uid = array_merge($uid, $uid_filter);

        $uid = array_unique($uid);

        $searchBy = false;

        if(count($jabatan) > 0 || count($skill) || count($pengalaman) > 0){
            $searchBy = true;
        }

        $applicant = User::where(function($q) use($uid, $enableFilter, $searchBy, $uid_filter, $fname){
            $id_filter = [];
            if($enableFilter){
                $id_filter = array_merge($id_filter, $uid_filter);
            }

            if($searchBy){
                $id_filter = array_merge($id_filter, $uid);
            }

            if(!empty($fname)){
                $q->where("name", "like", "%$fname%");
            }

            if($searchBy || $enableFilter){
                $_f = array_intersect($uid, $uid_filter);
                $q->whereIn("id", $_f);
            }
        })->where('role_access', 'like', '%"applicant"%')
        ->whereNotNull("email_verified_at")
        ->where('company_id', Session::get("company_id"))->get();

        $applicant_exp = User_experience::whereIn("user_id", $applicant->pluck("id"))
            ->orderBy("start_date", "desc")
            ->get();

        $user_profile = User_profile::whereIn("user_id", $applicant->pluck("id"))->get();
        $up = [];
        foreach($user_profile as $item){
            $up[$item->user_id] = $item;
        }

        // $applicant = User::whereIn("id", $applicant_exp->pluck("user_id"))->where('role_access', 'like', '%"applicant"%')->where('company_id', Session::get("company_id"))->paginate(6);

        $exp_list = [];
        foreach($applicant_exp as $item){
            $item->yoe = null;
            if(!empty($item->start_date) && $item->start_date != "-"){
                    $end_date = $item->end_date != null ? $item->end_date : date("Y-m-d");
                $d1 = date_create(date("Y-m-d H:i:s", strtotime($item->start_date)));
                $d2 = date_create(date("Y-m-d H:i:s", strtotime($end_date)));
                $diff = date_diff($d1, $d2);
                $y = $diff->format("%y");
                $item->yoe = $y;
            }
            $show = [];
            if(count($filter['yoe']) > 0){
                foreach($filter['yoe'] as $yoe){
                    if($yoe == ">5"){
                        if($item->yoe > 5){
                            $show[] = true;
                        }
                    } else {
                        if($item->yoe == $yoe){
                            $show[] = true;
                        }
                    }
                }
            }

            $item->yoe_arr = $show;

            if(count($filter['yoe']) > 0){
                if(in_array(true, $show)){
                    $exp_list[$item->user_id][] = $item;
                }
            } else {
                $exp_list[$item->user_id][] = $item;
            }
        }

        $applicant_edu = User_formal_education::whereIn("user_id", $applicant->pluck("id"))
            ->orderBy("start_date", "desc")
            ->get();
        $edu_list = [];
        foreach($applicant_edu as $item){
            $edu_list[$item->user_id][] = $item;
        }

        $testSel = Hrd_employee_test::where("category_id", 1)
            ->get();

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

        $bookmarked = Kjk_employer_bookmark::where("employer_id", Auth::id())
            ->whereIn('applicant_id', $applicant->pluck("id"))
            ->get()->pluck('bookmarked', "applicant_id");

        $appSort = [];

        foreach($applicant as $item){
            $score = 0;
            $exp = $exp_list[$item->id] ?? [];
            $edu = $edu_list[$item->id] ?? [];
            if(!empty($edu)){
                $score++;
            }
            if(!empty($exp)){
                $score++;
            }
            if(isset($test_list[$item->id])){
                $score++;
            }
            $item->score = $score;
            $appSort[] = $item;
        }

        $applicant = collect($appSort)->sortByDesc("score")->paginate(6);

        $lastPage = $applicant->lastPage();
        $currentPage = $applicant->currentPage();

        $view = view("_employer.search_talent._search", compact("applicant", "exp_list", "edu_list", "test_list", "bookmarked", 'lastPage', 'currentPage', 'up'));

        $res = [
            "view" => $view->render(),
            'uid' => $uid_filter,
            "enableFilter" => $enableFilter
        ];

        return json_encode($res);
    }

    function bookmark_page(){
        $uid = Kjk_employer_bookmark::where("employer_id", Auth::id())->where("bookmarked", 1)->get()->pluck("applicant_id");
        $applicant = User::whereIn("id", $uid)->paginate();

        $applicant_exp = User_experience::whereIn("user_id", $applicant->pluck("id"))
            ->orderBy("start_date", "desc")
            ->get();
        $exp_list = [];
        foreach($applicant_exp as $item){
            $item->yoe = null;
            if(!empty($item->start_date) && $item->start_date != "-"){
                $end_date = $item->end_date != null ? $item->end_date : date("Y-m-d");
                $d1 = date_create(date("Y-m-d H:i:s", strtotime($item->start_date)));
                $d2 = date_create(date("Y-m-d H:i:s", strtotime($end_date)));
                $diff = date_diff($d1, $d2);
                $y = $diff->format("%y");
                $item->yoe = $y;
                $exp_list[$item->user_id][] = $item;
            }
        }

        $applicant_edu = User_formal_education::whereIn("user_id", $applicant->pluck("id"))
            ->orderBy("start_date", "desc")
            ->get();
        $edu_list = [];
        foreach($applicant_edu as $item){
            $edu_list[$item->user_id][] = $item;
        }

        $testSel = Hrd_employee_test::where("category_id", 1)
            ->get();

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

        // dd("test");

        $bookmarked = Kjk_employer_bookmark::where("employer_id", Auth::id())
            ->whereIn('applicant_id', $applicant->pluck("id"))
            ->get()->pluck('bookmarked', "applicant_id");

        $view = view("_employer.search_talent._search", compact("applicant", "exp_list", "edu_list", "test_list", "bookmarked"));


        return view("_employer.search_talent.bookmark", compact("view", "uid"));
    }

    function detail($id, Request $Request){
        $applicant = User::find($id);

        $experience = User_experience::where('user_id', $applicant->id)
            ->orderBy("start_date", "desc")
            ->get();
        foreach($experience as $item){
            $item->yoe = null;
            if(!empty($item->start_date) && $item->start_date != "-"){
                $end_date = $item->end_date != null ? $item->end_date : date("Y-m-d");
                $d1 = date_create(date("Y-m-d H:i:s", strtotime($item->start_date)));
                $d2 = date_create(date("Y-m-d H:i:s", strtotime($end_date)));
                $diff = date_diff($d1, $d2);
                $y = $diff->format("%y");
                $item->yoe = $y;
            }
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

        $bookmarked = Kjk_employer_bookmark::firstOrNew([
            "employer_id" => Auth::id(),
            "applicant_id" => $applicant->id
        ]);

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

        $wpt = \App\Models\Kjk_wpt_result::whereIn("test_result_id", $test_result->pluck("id"))
            ->get();

        $wpt_iq = \App\Models\Kjk_wpt_score_iq::pluck("iq", "score");
        $wpt_interpretasi = \App\Models\Kjk_wpt_interpretasi::pluck("label", "score");


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

        return view("_employer.search_talent.detail", compact("data", 'wpt', "wpt_iq", "wpt_interpretasi", "profile", "exp", "test_list", "test", "bookmarked", "applicant", "experience", "edu_formal", "edu_informal", "languages", "skills", "portofolio", "add_family", "add_id_card", "add_license"));
    }

    function bookmark(Request $request){
        $employer_id = Auth::id();
        $applicant_id = $request->id;

        $bookmark = Kjk_employer_bookmark::firstOrNew([
            "employer_id" => $employer_id,
            "applicant_id" => $applicant_id
        ]);

        if(empty($bookmark->bookmarked)){
            $bookmark->bookmarked = 1;
        } else {
            $bookmark->bookmarked = 0;
        }

        $bookmark->save();

        $arr = [
            "bookmark" => $bookmark->bookmarked == 1 ? true : false
        ];

        return json_encode($arr);
    }
}
