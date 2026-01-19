<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee;
use App\Models\Master_educations;
use App\Models\Master_gender;
use App\Models\Master_industry;
use App\Models\Master_job_type;
use App\Models\Master_language;
use App\Models\Master_marital_status;
use App\Models\Master_religion;
use App\Models\Personel_blood_type;
use App\Models\Personel_profile;
use App\Models\User_add_family;
use App\Models\User_add_license;
use App\Models\User_experience;
use App\Models\User_formal_education;
use App\Models\User_language_skill;
use App\Models\User_mcu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ESSProfile extends Controller
{
    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    function index(Request $request){

        $user = Auth::user();
        $personel = Hrd_employee::find($user->emp_id);
        $profile = Personel_profile::where("user_id", $user->emp_id)->first();

        if($request->v == "nav"){

            $compact = [];

            $section = $request->section;

            $compact['section'] = $section;
            $compact['user'] = $user;
            $compact['personel'] = $personel;
            $compact['profile'] = $profile;

            if($section == "private_data"){
                $compact['marital_status'] = Master_marital_status::where(function($q) {
                    $q->where("company_id", Session::get("company_id"));
                    $q->orWhereNull("company_id");
                })->get();

                $compact['gender'] = Master_gender::where(function($q) {
                    $q->where("company_id", Session::get("company_id"));
                    $q->orWhereNull("company_id");
                })->get();

                $compact['religion'] = Master_religion::where(function($q) {
                    $q->where("company_id", Session::get("company_id"));
                    $q->orWhereNull("company_id");
                })->get();

                $compact['blood_type'] = Personel_blood_type::where(function($q) {
                    $q->where("company_id", Session::get("company_id"));
                    $q->orWhereNull("company_id");
                })->get();
            }

            if($section== "family_data"){
                $compact['data'] = User_add_family::where("user_id", $personel->id ?? null)
                    ->where("personel", 1)
                    ->get();

                $compact['master']['marital_status'] = Master_marital_status::pluck("name", "id");
                $compact['master']['gender'] = Master_gender::pluck("name", "id");
            }
            if($section == "education"){
                $compact['data'] = User_formal_education::where("user_id", $personel->id ?? null)
                    ->where("personel", 1)
                    ->get();

                $compact['degree'] = Master_educations::where(function($q) {
                    $q->where("company_id", Session::get("company_id"));
                    $q->orWhereNull("company_id");
                })->get();
            }
            if($section == "working_experience"){
                $compact['data'] = User_experience::where("user_id", $personel->id ?? null)
                    ->where("personel", 1)
                    ->get();

                $compact['master']['industry'] = Master_industry::hris(Session::get("company_id"))->pluck("name", "id");
                $compact['master']['job_type'] = Master_job_type::pluck("name", "id");
            }
            if($section == "language_skill"){
                $compact['data'] = User_language_skill::where("user_id", $personel->id ?? null)
                    ->where("personel", 1)
                    ->get();

                $compact['master']['language'] = Master_language::pluck("name", "id");
            }
            if($section == "medical_record"){
                $compact['data'] = User_mcu::where("user_id", $personel->id ?? null)
                    ->get();
            }
            if($section == "license"){
                $compact['data'] = User_add_license::where("user_id", $personel->id ?? null)
                    ->where("personel", 1)
                    ->get();
            }

            $view = view("_ess.profile.nav.$request->section", $compact)->render();

            return json_encode([
                "view" => $view
            ]);
        }

        return view("_ess.profile.index", compact("user", "personel"));
    }

    function update_private_data(Request $request){
        $tp = $request->post_type;

        $personel = Hrd_employee::find($request->personel_id);

        $user = $personel->user;

        $profile = Personel_profile::where("user_id", $personel->id)->first();
        if(empty($profile)){
            $profile = new Personel_profile();
            $profile->user_id = $personel->id;
        }

        $section = "tab_info";

        if($tp == "personal_info"){
            // $personel->emp_name = $request->name;
            // $personel->email = $request->email;
            $personel->phone = $request->phone;
            // $personel->emp_id = $request->emp_id;
            $profile->citizenship = $request->citizenship;
            $profile->marital_status = $request->marital_status;
            $profile->religion = $request->religion;
            $profile->gender = $request->gender;
            $profile->blood_type = $request->blood_type;
            $profile->height = $request->height;
            $profile->weight = $request->weight;
            $personel->emp_lahir = $this->sortDate($request->emp_lahir);
            $personel->emp_tmpt_lahir = $request->emp_tmpt_lahir;

            $file = $request->file("image");
            if(!empty($file)){
                $d = date("YmdHis");
                $newName = $d."_".$personel->id."_identity_".$file->getClientOriginalName();
                if($file->move($this->dir, $newName)){
                    $user->user_img = "media/attachments/$newName";
                }
            }
        }

        if($tp == "personel_detail"){
            // $profile->citizenship = $request->citizenship;
            // $profile->marital_status = $request->marital_status;
            // $profile->religion = $request->religion;
            // $profile->gender = $request->gender;
            // $profile->blood_type = $request->blood_type;
            // $profile->height = $request->height;
            // $profile->weight = $request->weight;
            // $personel->emp_lahir = $this->sortDate($request->emp_lahir);
            // $personel->emp_tmpt_lahir = $request->emp_tmpt_lahir;
        }

        if($tp == "identity_address"){
            $profile->identity_address = $request['identity']['address'] ?? "-";
            $profile->identity_zip_code = $request['identity']['zip_code'] ?? "-";
            $profile->identity_country = $request['identity']['country'] ?? "-";
            $profile->identity_city = $request['identity']['city'] ?? "-";
            $profile->identity_province = $request['identity']['province'] ?? "-";

            $section = "tab_address";
        }

        if($tp == "resident_address"){
            $profile->resident_address = $request['resident']['address'] ?? "-";
            $profile->resident_zip_code = $request['resident']['zip_code'] ?? "-";
            $profile->resident_country = $request['resident']['country'] ?? "-";
            $profile->resident_city = $request['resident']['city'] ?? "-";
            $profile->resident_province = $request['resident']['province'] ?? "-";

            $section = "tab_address";
        }

        if($tp == "document"){
            $profile->identity_type = "ktp";
            $profile->identity_number = $request->identity_number;
            $profile->npwp = $request->npwp;
            $profile->kitas = $request->kitas;
            $profile->bpjskes = $request->bpjs_kes;
            $profile->bpjstk = $request->bpjs_tk;

            $file = $request->file("file");
            if(!empty($file)){
                $d = date("YmdHis");
                $newName = $d."_".$personel->id."_identity_".$file->getClientOriginalName();
                if($file->move($this->dir, $newName)){
                    $profile->identity_file = "media/attachments/$newName";
                }
            }

            $file = $request->file("upload_npwp");
            if(!empty($file)){
                $d = date("YmdHis");
                $newName = $d."_".$personel->id."_npwp_".$file->getClientOriginalName();
                if($file->move($this->dir, $newName)){
                    $profile->npwp_file = "media/attachments/$newName";
                }
            }

            $file = $request->file("upload_bpjs_kes");
            if(!empty($file)){
                $d = date("YmdHis");
                $newName = $d."_".$personel->id."_bpjskes_".$file->getClientOriginalName();
                if($file->move($this->dir, $newName)){
                    $profile->bpjskes_file = "media/attachments/$newName";
                }
            }

            $file = $request->file("upload_bpjs_tk");
            if(!empty($file)){
                $d = date("YmdHis");
                $newName = $d."_".$personel->id."_bpjstk_".$file->getClientOriginalName();
                if($file->move($this->dir, $newName)){
                    $profile->bpjstk_file = "media/attachments/$newName";
                }
            }

            $file = $request->file("upload_kitas");
            if(!empty($file)){
                $d = date("YmdHis");
                $newName = $d."_".$personel->id."_bpjstk_".$file->getClientOriginalName();
                if($file->move($this->dir, $newName)){
                    $profile->kitas_file = "media/attachments/$newName";
                }
            }

            $section = "tab_document";
        }

        $profile->save();
        $personel->save();
        $user->save();


        return redirect()->back()->with([
            "ess_private_tab" => $section
        ]);
    }

    function sortDate($date){
        $d = explode("/", $date);
        krsort($d);

        $d = implode("-", $d);
        return $d;
    }
}
