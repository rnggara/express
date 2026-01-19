<?php

namespace App\Http\Controllers;

use App\Models\Master_city;
use App\Models\Master_educations;
use App\Models\Master_gender;
use App\Models\Master_industry;
use App\Models\Master_job_level;
use App\Models\Master_job_type;
use App\Models\Master_language;
use App\Models\Master_marital_status;
use App\Models\Master_proficiency;
use App\Models\Master_province;
use App\Models\Master_religion;
use App\Models\Master_specialization;
use App\Models\User;
use App\Models\User_attachments;
use App\Models\User_complete_profile;
use App\Models\User_experience;
use App\Models\User_formal_education;
use App\Models\User_informal_education;
use App\Models\User_language_skill;
use App\Models\User_portofolio;
use App\Models\User_profile;
use App\Models\User_skill;
use App\Models\User_medsos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KjkCompleteProfileController extends Controller
{

    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    function page(){
        $user = Auth::user();
        if($user->id_rms_roles_divisions == 44){
            return view("completion_page");
        } else {
            $user->complete_profile = 1;
            $user->save();
            return view("completion_page_employer");
        }
    }

    function skip(){
        $user = User::find(Auth::id());
        $user->complete_profile = 1;
        $user->save();

        return redirect()->to("/");
    }

    function index(){
        $user = User::find(Auth::id());
        $profile = User_profile::where("user_id", $user->id)->first();
        $city = Master_city::get();
        $province = Master_province::get();
        $gender = Master_gender::get();
        $marital_status = Master_marital_status::get();
        $religion = Master_religion::get();
        $jabatan = Master_job_level::get();
        $job_type = Master_job_type::get();
        $industri = Master_industry::get();
        $specialization = Master_specialization::get();
        $ledu = Master_educations::get();
        $languages = Master_language::get();
        $proficiency = Master_proficiency::get();
        $cp = User_complete_profile::firstOrNew(["user_id" => Auth::id()]);
        foreach($cp->getAttributes() as $key => $item){
            if($key != "id" && $key != "user_id"){
                $cp->$key = null;
            }
        }
        $cp->save();
        $medsos = User_medsos::where("user_id", $user->id)->first();
        return view("completion", compact("user", "profile", "medsos", "city", "province", "gender", "marital_status", "religion", "jabatan", "job_type", "industri", "specialization", "ledu", "languages", "proficiency"));
    }

    function step(Request $request){
        $cp = User_complete_profile::firstOrNew(["user_id" => Auth::id()]);
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype/public_html", "public_html/kerjaku/assets", $_dir);
        $dir = str_replace("\\", "/", $dir);
        $data = [];
        foreach($request->all() as $key => $item){
            if($key != "a" && $key != "_token"){
                $isFile = $request->file($key);
                if(!empty($isFile)){
                    if(is_array($isFile)){
                        foreach($isFile as $files){
                            $newName = date("YmdHis")."_".$files->getClientOriginalName();
                            if($files->move($this->dir, $newName)){
                                $data[$key][] = "media/attachments/$newName";
                            }
                        }
                    } else {
                        $newName = date("YmdHis")."_".$isFile->getClientOriginalName();
                        if($isFile->move($this->dir, $newName)){
                            $data[$key] = "media/attachments/$newName";
                        }
                    }
                } else {
                    $data[$key] = $item;
                }
            }
        }

        $cp[$request->a] = json_encode($data);
        $cp->save();

        $submit = false;
        if(!empty($cp->personal_data)){
            $submit = true;
        }

        $arr = [
            "submit" => $submit
        ];

        return json_encode($arr);
    }

    function post(Request $request){
        $cp = User_complete_profile::firstOrNew(["user_id" => Auth::id()]);

        if(!empty($cp->personal_data)){
            $pd = json_decode($cp->personal_data, true);
            $newPd = User_profile::where("user_id", Auth::id())->first();
            if(empty($newPd)){
                $newPd = new User_profile();
                $newPd->user_id = Auth::id();
            }
            $newPd->user_id = Auth::id();
            foreach($pd as $key => $item){
                if($key != "_token"){
                    if($key == "salary_expect"){
                        $_v = str_replace(".", "", $item);
                        $newPd->$key = str_replace(",", ".", $_v);
                    } else {
                        $newPd->$key = $item;
                    }
                }
            }
            $newPd->save();
        }

        if(!empty($cp->pengalaman_kerja)){
            $expArr = json_decode($cp->pengalaman_kerja, true);
            $comp_name = $expArr["company_name"];
            $salary = $expArr['salary'] ?? [];
            $position = $expArr['position'] ?? [];
            $job_level = $expArr['job_level'] ?? [];
            $specialization = $expArr['specialization'] ?? [];
            $industry = $expArr['industry'] ?? [];
            $location = $expArr['location'] ?? [];
            $descriptions = $expArr['descriptions'] ?? [];
            $achievements = $expArr['achievements'] ?? [];
            $reference = $expArr['reference'] ?? [];
            $phone = $expArr['phone'] ?? [];
            $start_year = $expArr['start_year'] ?? [];
            $start_month = $expArr['start_month'] ?? [];
            $end_year = $expArr['end_year'] ?? [];
            $end_month = $expArr['end_month'] ?? [];
            $still = $expArr['still'] ?? [];
            $ref_pos = $expArr['ref_pos'] ?? [];
            $resign_reason = $expArr['resign_reason'] ?? [];
            foreach($comp_name as $i => $item){
                if($item != ""){
                    $exp = new User_experience();
                    $exp->user_id = Auth::id();

                    $sal = str_replace(".", "", $salary[$i] ?? 0);

                    $exp->company = $item;
                    $exp->salary = str_replace(",", ".", $sal);
                    $exp->position = $position[$i] ?? null;
                    $exp->job_level = $job_level[$i] ?? null;
                    $exp->specialization = $specialization[$i] ?? null;
                    $exp->industry = $industry[$i] ?? null;
                    $exp->location = $location[$i] ?? null;
                    $exp->descriptions = $descriptions[$i] ?? null;
                    $exp->achievements = $achievements[$i] ?? null;
                    $exp->reference = $reference[$i] ?? null;
                    $exp->phone = $phone[$i] ?? null;
                    $exp->ref_pos = $ref_pos[$i] ?? null;
                    $exp->resign_reason = $resign_reason[$i] ?? null;

                    $exp->start_date = $start_year[$i]."-".$start_month[$i];
                    if(isset($still[$i])){
                        $still = $still[$i];
                        if(!empty($still)){
                            $exp->still = 1;
                            $exp->end_date = null;
                        }
                    } else {
                        $exp->still = 0;
                        $exp->end_date = $end_year[$i]."-".$end_month[$i];
                    }

                    $exp->save();

                    $classId = $exp->id;
                    $className = "User_experience";
                    $classExp = explode("_", $className);
                    $fNameArr = [];
                    for ($i=1; $i < count($classExp); $i++) {
                        $fNameArr[] = $classExp[$i];
                    }

                    if(isset($expArr["attachments"])){
                        $attachments = $expArr["attachments"];
                        if(isset($attachments[$i])){
                            $att = new User_attachments();
                            $att->user_id = Auth::id();
                            $att->className = $className;
                            $att->class_id = $classId;
                            $att->file_address = "media/attachments/".$attachments[$i];
                            $att->file_name = end(explode("/", $attachments[$i]));
                            $att->save();
                        }
                    }
                }
            }
        }

        if(!empty($cp->pendidikan)){
            $eduArr = json_decode($cp->pendidikan, true);
            $degree = $eduArr["degree"] ?? [];
            $field_of_study = $eduArr["field_of_study"] ?? [];
            $school_name = $eduArr["school_name"] ?? [];
            $grade = $eduArr["grade"] ?? [];
            $fo_start_month = $eduArr["fo_start_month"] ?? [];
            $fo_start_year = $eduArr["fo_start_year"] ?? [];
            $fo_end_month = $eduArr["fo_end_month"] ?? [];
            $fo_end_year = $eduArr["fo_end_year"] ?? [];
            $fo_attachments = $eduArr["fo_attachments"] ?? [];
            $fo_descriptions = $eduArr["fo_descriptions"] ?? [];
            $fo_still = $eduArr["fo_still"] ?? [];
            foreach($degree as $i => $item){
                if($item != ""){
                    $edu = new User_formal_education();
                    $edu->user_id = Auth::id();
                    $edu->degree = $item;
                    $edu->field_of_study = $field_of_study[$i];
                    $edu->school_name = $school_name[$i];
                    $edu->grade = $grade[$i];
                    $edu->start_date = $fo_start_year[$i]."-".$fo_start_month[$i];
                    if(isset($fo_still[$i])){
                        $still = $fo_still[$i];
                        if(!empty($still)){
                            $edu->still = 1;
                            $edu->end_date = null;
                        }
                    } else {
                        $edu->still = 0;
                        $edu->end_date = $fo_end_year[$i]."-".$fo_end_month[$i];
                    }
                    $edu->descriptions = $fo_descriptions[$i];
                    $edu->save();

                    $className = "User_formal_education";
                    $classId = $edu->id;

                    if(isset($fo_attachments[$i])){
                        $att = new User_attachments();
                        $att->user_id = Auth::id();
                        $att->className = $className;
                        $att->class_id = $classId;
                        $att->file_address = "media/attachments/".$fo_attachments[$i];
                        $_attArr = explode("/", $fo_attachments[$i]);
                        $att->file_name = end($_attArr);
                        $att->save();
                    }
                }
            }

            $course_name = $eduArr["course_name"] ?? [];
            $vendor = $eduArr["vendor"] ?? [];
            $info_start_month = $eduArr["info_start_month"] ?? [];
            $info_start_year = $eduArr["info_start_year"] ?? [];
            $info_end_month = $eduArr["info_end_month"] ?? [];
            $info_end_year = $eduArr["info_end_year"] ?? [];
            $info_descriptions = $eduArr["info_descriptions"] ?? [];
            $info_attachments = $eduArr["info_attachments"] ?? [];
            $info_still = $eduArr["info_still"] ?? [];;
            foreach($course_name as $i => $item){
                if($item != ""){
                    $edu = new User_informal_education();
                    $edu->user_id = Auth::id();
                    $edu->course_name = $item;
                    $edu->vendor = $vendor[$i];
                    $edu->start_date = $info_start_year[$i]."-".$info_start_month[$i];
                    if(isset($info_still[$i])){
                        $still = $info_still[$i];
                        if(!empty($still)){
                            $edu->still = 1;
                            $edu->end_date = null;
                        }
                    } else {
                        $edu->still = 0;
                        $edu->end_date = $info_end_year[$i]."-".$info_end_month[$i];
                    }
                    $edu->descriptions = $info_descriptions[$i];
                    $edu->save();

                    $className = "User_informal_education";
                    $classId = $edu->id;

                    if(isset($info_attachments[$i])){
                        $att = new User_attachments();
                        $att->user_id = Auth::id();
                        $att->className = $className;
                        $att->class_id = $classId;
                        $att->file_address = "media/attachments/".$info_attachments[$i];
                        $_attArr = explode("/", $info_attachments[$i]);
                        $att->file_name = end($_attArr);
                        $att->save();
                    }
                }
            }
        }

        if(!empty($cp->kemampuan_bahasa)){
            $langs = json_decode($cp->kemampuan_bahasa, true);
            $lang_id = $langs["language"];
            $writing = $langs["writing"];
            $reading = $langs["reading"];
            $speaking = $langs["speaking"];
            foreach($lang_id as $i => $item){
                if($item != ""){
                    $skill = new User_language_skill();
                    $skill->user_id = Auth::id();
                    $skill->language = $item;
                    $skill->reading = $reading[$i];
                    $skill->speaking = $speaking[$i];
                    $skill->writing = $writing[$i];
                    $skill->save();
                }
            }
        }

        if(!empty($cp->kemampuan)){
            $langs = json_decode($cp->kemampuan, true);
            $skill_name = $langs["skill_name"];
            $proficiency = $langs["proficiency"];
            foreach($skill_name as $i => $item){
                $skill = new User_skill();
                $skill->user_id = Auth::id();
                $skill->skill_name = $item;
                $skill->proficiency = $proficiency[$i];
                $skill->save();
            }
        }

        if(!empty($cp->portofolio)){
            $porto = json_decode($cp->portofolio, true);
            $port = User_portofolio::where("user_id", $request->id)->first();
            if(empty($port)){
                $port = new User_portofolio();
                $port->user_id = Auth::id();
            }

            foreach($porto as $key => $item){
                if(!in_array($key, ["_token", "type", "id"])){
                    if(!$request->hasFile($key)){
                        $port->$key = $item;
                    }
                }
            }

            $port->save();
        }

        if(!empty($cp->medsos)){
            $jsMedsos = json_decode($cp->medsos, true);
            $medsos = User_medsos::where("user_id", $request->id)->first();
            if(empty($medsos)){
                $medsos = new User_medsos();
                $medsos->user_id = Auth::id();
            }

            foreach($jsMedsos as $key => $item){
                if(!in_array($key, ["_token", "type", "id"])){
                    if(!$request->hasFile($key)){
                        $medsos->$key = $item;
                    }
                }
            }

            $medsos->save();
        }

        $user = User::find(Auth::id());
        $user->complete_profile = 1;
        $user->save();

        return redirect()->to("/");
    }
}
