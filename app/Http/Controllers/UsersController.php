<?php

namespace App\Http\Controllers;

use App\Helpers\Notification;
use App\Mail\MailConfig;
use DB;
use Session;
use App\Models\User;
use App\Models\Action;
use App\Models\Module;
use App\Models\Users_zakat;
use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\RolePrivilege;
use App\Models\UserPrivilege;
use App\Models\Hrd_salary_archive;
use App\Models\Hrd_att_transaction;
use App\Models\Hrd_employee_test;
use App\Models\Hrd_employee_test_result;
use App\Models\Job_bookmark;
use App\Models\Kjk_company_photo;
use App\Models\Master_gender;
use App\Models\Master_marital_status;
use App\Models\Master_religion;
use App\Models\Master_province;
use Illuminate\Support\Str;
use App\Models\Master_city;
use App\Models\User_formal_education;
use App\Models\User_informal_education;
use App\Models\User_attachments;
use App\Models\Master_educations;
use App\Models\Master_industry;
use App\Models\Master_job_level;
use App\Models\Master_job_type;
use App\Models\Master_language;
use App\Models\Master_proficiency;
use App\Models\Master_specialization;
use App\Models\Master_company;
use App\Models\Master_district;
use App\Models\User_collaborator;
use App\Models\User_add_family;
use App\Models\User_add_id_card;
use App\Models\User_add_license;
use App\Models\User_experience;
use App\Models\User_job_applicant;
use App\Models\User_job_vacancy;
use App\Models\User_language_skill;
use App\Models\User_portofolio;
use App\Models\User_medsos;
use App\Models\User_skill;
use App\Models\User_profile;
use App\Models\User_job_interview;
use App\Models\Kjk_disc_desc_line;
use App\Models\Kjk_disc_line1;
use App\Models\Kjk_disc_line2;
use App\Models\Kjk_disc_line3;
use App\Models\Kjk_disc_psikogram;
use App\Models\Kjk_mbti_key;
use App\Models\Kjk_mbti_psikogram;
use App\Models\Kjk_mbti_analysis;
use App\Models\Kjk_mbti_analysis_identifier;
use App\Models\Kjk_wpt_age;
use App\Models\Kjk_wpt_result;
use App\Models\Kjk_wpt_score_iq;
use App\Models\Kjk_wpt_interpretasi;
use App\Models\Hrd_employee_question_point;
use App\Models\Papikostik_parameter;
use App\Models\Papikostik_psikogram;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\View\FileViewFinder;

class UsersController extends Controller
{

    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    public function getCompany(Request $request){
        $users = ConfigCompany::where('company_name','like','%'.$request->searchTerm.'%')
            ->where('id', '!=', $request->comp)
            ->get();

        $data = [];
        foreach ($users as $value){
            $data[] = array(
                "id" => $value->id,
                "text" => $value->company_name
            );
        }
        return response()->json($data);
    }

    public function getUsers($id_company,Request $request){
        $arr = array(
            'company_id' => $id_company,
        );

        $usersComp = User::where('company_id', $request->comp)->get();
        $userExist = [];
        foreach($usersComp as $user){
            $userExist[] = $user->username;
        }

        $users = User::where($arr)
            ->whereNotIn('username', $userExist)
            ->where('name','like','%'.$request->searchTerm.'%')
            ->get();
        $data = [];
        foreach ($users as $value){
            $data[] = array(
                "id" => $value->id,
                "text" => $value->name
            );
        }
        return response()->json($data);
    }

    function detail(Request $Request){
        $applicant = User::find(Auth::id());

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

        $data['family'] = User_add_family::where("user_id", Auth::id())->get();
        $data['license'] = User_add_license::where("user_id", Auth::id())->get();
        $data['id_card'] = User_add_id_card::where("user_id", Auth::id())->first();
        $data['marital_status'] = Master_marital_status::get();
        $data['gender'] = Master_gender::get();

        return view("users._view_detail", compact("data","profile", "exp", 'wpt', 'wpt_iq', 'wpt_interpretasi', "test_list", "test", "applicant", "experience", "edu_formal", "edu_informal", "languages", "skills", "portofolio", "add_family", "add_id_card", "add_license"))->render();
    }

    public function getDetailUser(Request $request){
        $id = Auth::id();
        $user = User::where('id',$id)->first();
        $userComp = User::where('username', $user->username)
            ->where('company_id', Session::get('company_id'))
            ->first();
        $mnths = [];
        for ($i=date('n'); $i >= 1 ; $i--) {
            $mnths[$i] = date('F', strtotime(date('Y')."-".$i));
        }

        $emp = null;
        $arch = [];

        if (!empty($userComp)) {
            if (!empty($userComp->emp_id)) {
                $emp = Hrd_employee::find($userComp->emp_id);
                $archive = Hrd_salary_archive::where('emp_id', $userComp->emp_id)->get();
                foreach ($archive as $key => $value) {
                    $mn = explode("-", $value->archive_period);
                    if (date('Y') == 2021) {
                        if ($mn[0] >= 3) {
                            $arch[$value->archive_period] = explode("-", $value->archive_period);
                        }
                    }
                }
            }
        }

        $userEmp = User::where('username', $user->username)
            ->whereNotNull('emp_id')
            ->first();

        $clockin = null;
        $clockout = null;
        if(!empty($userEmp)){
            $session = Hrd_att_transaction::where("user_id", $userEmp->id)->orderBy('id')->get();
            if(count($session) > 0){
                $clockin = $session[0]->trans_time;
                if(count($session) > 1){
                    $clockout = $session[count($session) - 1]->trans_time;
                }
            }
        }

        $v = $request->v ?? "personal_data";

        $data = [];
        $profile = User_profile::where("user_id", $id)->first();

        if($v == "view_profile"){
            $data['view'] = $this->detail($request);
        } elseif($v == "personal_data"){
            $data['gender'] = Master_gender::get();
            $data['marital_status'] = Master_marital_status::get();
            $data['religion'] = Master_religion::get();
            $data['prov'] = Master_province::get();
            $data['city'] = Master_city::get();
            $data['act'] = $request->act ?? "profile";
        } elseif($v == "education"){
            $data['formal'] = User_formal_education::where("user_id", $user->id)->orderBy("start_date", "desc")->get();
            $data['informal'] = User_informal_education::where("user_id", $user->id)->orderBy("start_date", "desc")->get();
            $class_ids = [];
            $class_ids = array_merge($class_ids, $data['formal']->pluck("id")->toArray());
            $class_ids = array_merge($class_ids, $data['informal']->pluck("id")->toArray());
            $data['documents'] = User_attachments::whereIn("className", ["User_formal_education", "User_informal_education"])
                ->where(function($q) use($data){
                    $q->whereIn("class_id", $data['formal']->pluck("id")->toArray());
                    $q->orWhereIn('class_id', $data['informal']->pluck("id")->toArray());
                })
                ->where("user_id", $user->id)
                ->orderBy("id", "desc")
                ->get();
        } elseif($v == "experience"){
            $data['experience'] = User_experience::where("user_id", $user->id)->orderBy('start_date', "desc")->get();
            $data['documents'] = User_attachments::whereIn("className", ["User_experience"])
                ->whereIn("class_id", $data['experience']->pluck("id"))
                ->where("user_id", $user->id)
                ->orderBy("id", "desc")
                ->get();

            $data['job_level'] = Master_job_level::whereIn("id", $data['experience']->pluck("job_level"))->get();
            $data['job_type'] = Master_job_type::whereIn("id", $data['experience']->pluck("job_type"))->get();
            $data['specialization'] = Master_specialization::whereIn("id", $data['experience']->pluck("specialization"))->get();
            $data['industry'] = Master_industry::whereIn("id", $data['experience']->pluck("industry"))->get();
        } elseif($v == "skill"){
            $data['skill'] = User_skill::where("user_id", $user->id)->orderBy("id", "desc")->get();
            $data['language_skill'] = User_language_skill::where("user_id", $user->id)->orderBy("id", "desc")->get();

            $data['proficiency'] = Master_proficiency::whereIn("id", $data['skill']->pluck('proficiency'))->get();
            $data['language'] = Master_language::whereIn("id", $data['language_skill']->pluck('language'))->get();
        } elseif($v == "portofolio"){
            $data['portofolio'] = User_portofolio::where("user_id", $user->id)->get();
            $data['documents'] = User_attachments::whereIn("className", ["User_portofolio"])
                ->whereIn("class_id", $data['portofolio']->pluck("id"))
                ->where("user_id", $user->id)
                ->orderBy("id", "desc")
                ->get();
        } elseif($v == "company_profile"){
            $data['prov'] = Master_province::get();
            $data['city'] = Master_city::get();
            $data['company'] = Master_company::find(Auth::user()->comp_id);
            $data['industry'] = Master_industry::get();
        } elseif($v == "test_result"){
            $data['test'] = Hrd_employee_test::orderBy('order_num')->get();
            $data['res'] = Hrd_employee_test_result::where("user_id", Auth::id())
                ->whereNotNull("result_end")
                ->whereDate('created_at', ">=",  date("Y-m-d", strtotime("3 months ago")))
                ->orderBy("created_at", "desc")
                ->get();
            $data['wpt'] = \App\Models\Kjk_wpt_result::whereIn("test_result_id", $data['res']->pluck('id'))->first();

            $data['wpt_iq'] = \App\Models\Kjk_wpt_score_iq::pluck("iq", "score");
            $data['wpt_interpretasi'] = \App\Models\Kjk_wpt_interpretasi::pluck("label", "score");
        } elseif($v == "additional_information"){
            $data['family'] = User_add_family::where("user_id", Auth::id())->get();
            $data['license'] = User_add_license::where("user_id", Auth::id())->get();
            $data['id_card'] = User_add_id_card::where("user_id", Auth::id())->first();
            $data['marital_status'] = Master_marital_status::get();
            $data['gender'] = Master_gender::get();
        } elseif($v == "media_sosial"){
            $data['media_sosial'] = User_medsos::where("user_id", $user->id)->get();
        }

        if($request->_name){
            $restitle = "";
            $resurl = "";
            $isFile = false;
            $id = $request->_id;
            $act = $request->_act;
            $_name = $request->_name;
            if($request->_name == "personal_data"){
                $resview = view("users.modal.personal_data", compact("user", "data"))->render();
                $restitle = "Tambahkan ".trans("user.personal_data");
                $resurl = route("account.edit.about");
                if(!empty($act) && $act == "delete"){
                    $resurl = route("account.personal.delete");
                    $resview = view("users.modal.delete", compact("id", "_name"))->render();
                }
            } elseif($_name == "skill" || $_name == "language-skill"){
                $restitle = ucwords(str_replace("-", " ", $_name));
                if($_name == "language-skill"){
                    $restitle = "Kemampuan Bahasa";
                } else {
                    $restitle = "Kemampuan Khusus";
                }
                $resurl = route('account.skill.store');
                $proficiency = Master_proficiency::get();
                $languages = Master_language::get();

                $_detail = [];
                if(!empty($id)){
                    if($_name == "skill"){
                        $_detail = User_skill::find($id);
                    } else {
                        $_detail = User_language_skill::find($id);
                    }
                }

                $resview = view("users.modal.skill", compact("_name", "proficiency", "languages", "_detail"))->render();
                if(!empty($act) && $act == "delete"){
                    $resurl = route("account.skill.delete");
                    $resview = view("users.modal.delete", compact("id", "_name"))->render();
                }
            } elseif($request->_name == "portofolio"){
                $restitle = ucwords(str_replace("_", " ", $_name));
                $resurl = route('account.port.store');

                $_detail = [];
                if(!empty($id)){
                    $_detail = User_portofolio::find($id);
                }

                $resview = view("users.modal.portofolio", compact("_name", "_detail"))->render();
            } elseif($request->_name == "experience"){
                $restitle = "Pengalaman Kerja";
                $resurl = route('account.exp.store');
                $_jabatan = Master_job_level::get();
                $_job_type = Master_job_type::get();
                $_specialization = Master_specialization::get();
                $_industry = Master_industry::get();

                $_detail = [];
                if(!empty($id)){
                    $_detail = User_experience::find($id);
                }

                $resview = view("users.modal.experience", compact("_detail", "_jabatan", "_job_type", "_specialization", "_industry"))->render();

                if(!empty($act) && $act == "delete"){
                    $resurl = route("account.exp.delete");
                    $resview = view("users.modal.delete", compact("id", "_name"))->render();
                }
            } elseif($request->_name == "formal-education") {
                $resurl = route("account.edu.store");
                $restitle = "Pendidikan Formal";
                $isFile = true;
                $ledu = Master_educations::get();
                $_detail = [];
                if(!empty($id)){
                    $_detail = User_formal_education::find($id);
                }

                $resview = view("users.modal.$request->_name", compact("ledu", "_detail"))->render();

                if(!empty($act) && $act == "delete"){
                    $resurl = route("account.edu.delete");
                    $resview = view("users.modal.delete", compact("id", "_name"))->render();
                }
            } elseif($request->_name == "informal-education") {
                $restitle = "Pendidikan Informal";
                $resurl = route("account.edu.store");
                $isFile = true;
                $ledu = Master_educations::get();
                $_detail = [];
                if(!empty($id)){
                    $_detail = User_informal_education::find($id);
                }
                $resview = view("users.modal.$request->_name", compact("ledu", "_detail"))->render();

                if(!empty($act) && $act == "delete"){
                    $resurl = route("account.edu.delete");
                    $resview = view("users.modal.delete", compact("id", "_name"))->render();
                }
            } elseif($_name == "family"){
                $restitle = "Informasi Keluarga";
                $resurl = route("account.add.store", ["type"=>"family"]);
                $marital_status = Master_marital_status::get();
                $gender = Master_gender::get();
                $_detail = [];
                if(!empty($id)){
                    $_detail = User_add_family::find($id);
                }
                $resview = view("users.modal.family", compact("marital_status", "gender", "_detail"))->render();
                if(!empty($act) && $act == "delete"){
                    $resurl = route("account.add.delete", ["type"=>"family"]);
                    $resview = view("users.modal.delete", compact("id", "_name"))->render();
                }
            } elseif($_name == "license"){
                $restitle = "Lisensi";
                $resurl = route("account.add.store", ["type"=>"license"]);
                $_detail = [];
                if(!empty($id)){
                    $_detail = User_add_license::find($id);
                }
                $resview = view("users.modal.license", compact("_detail"))->render();
                if(!empty($act) && $act == "delete"){
                    $resurl = route("account.add.delete", ["type"=>"license"]);
                    $resview = view("users.modal.delete", compact("id", "_name"))->render();
                }
            } elseif($_name == "ktp"){
                $restitle = "KTP";
                $resurl = route("account.add.store", ["type"=>"ktp"]);
                $_detail = [];
                if(!empty($id)){
                    $_detail = User_add_id_card::find($id);
                }
                $resview = view("users.modal.ktp", compact("_detail"))->render();
                if(!empty($act) && $act == "delete"){
                    $resurl = route("account.add.delete", ["type"=>"ktp"]);
                    $resview = view("users.modal.delete", compact("id", "_name"))->render();
                }
            } elseif($_name == "media_sosial"){
                $restitle = ucwords(str_replace("_", " ", $_name));
                $resurl = route('account.medsos.store');

                $_detail = [];
                if(!empty($id)){
                    $_detail = User_medsos::find($id);
                }

                $resview = view("users.modal.media_sosial", compact("_name", "_detail"))->render();
            }


            $resfoot = view("users.modal.footer", compact("act"))->render();


            $res = [
                "title" => $restitle,
                "url" => $resurl,
                "view" => $resview,
                "foot" => $resfoot,
                "mode" => $request->_name,
                'isFile' => $isFile
            ];

            return json_encode($res);
        }

        $aside = [
            [
                "label" => "personal_data",
                "id" => "Data Personal",
                "icon" => "rr-user"
            ],
            [
                "label" => "education",
                "id" => "Pendidikan",
                "icon" => "rr-books"
            ],
            [
                "label" => "experience",
                "id" => "Pengalaman Kerja",
                "icon" => "rr-briefcase"
            ],
            [
                "label" => "skill",
                "id" => "Kemampuan",
                "icon" => "rr-list-check"
            ],
            [
                "label" => "portofolio",
                "id" => "Portofolio",
                "icon" => "rr-globe"
            ],
            [
                "label" => "media_sosial",
                "id" => "Media Sosial",
                "icon" => "rr-mobile-notch"
            ],
            [
                "label" => "test_result",
                "id" => "Hasil Tes",
                "icon" => "rr-book-alt"
            ],
            [
                "label" => "additional_information",
                "id" => "Informasi Tambahan",
                "icon" => "rr-document"
            ],
        ];

        return view('users.detail',[
            'user' => $user,
            'mnths' => $mnths,
            'emp' => $emp,
            'arch' => $arch,
            'userComp' => $userEmp,
            'clockin' => $clockin,
            'clockout' => $clockout,
            'v' => $v,
            'data' => $data,
            "profile" => $profile,
            'aside_menu' => $aside
        ]);
    }

    function updateProfile(Request $request){
        $user = User::find(Auth::id());

        if($request->type == "image"){
            $file = $request->file("profile_img");
            if(!empty($file)){
                $d = date("YmdHis");
                $newName = $d."_".Auth::id()."_profile_".$file->getClientOriginalName();
                if($file->move($this->dir, $newName)){
                    $user->user_img = "media/attachments/$newName";
                    $user->save();
                }
            }
        } elseif($request->type == "bank"){
            $user->bank_name = $request->bank_name;
            $user->rek_no = $request->rek_no;
            $user->save();
        } elseif($request->type == "account"){
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->about = $request->about;
            $user->address = $request->address;
            $user->save();
        } else {
            $user->phone = $request->phone;
            $user->save();
        }

        return redirect()->back();
    }

    function change_password(Request $request){
        $user = User::find(Auth::id());
        $user->password = Hash::make($request->password);
        $user->save();

        Notification::sendMailPasswordChanged($user->name, $user->email);

        return redirect()->back();
    }

    function personalDelete(Request $request){
        $profile = User_profile::find($request->id);
        $profile->delete();

        return redirect()->back();
    }

    function addStore($type, Request $request){
        $dir = $this->dir;
        if($type == "family"){
            $d = substr($request->tgl_lahir, 0, 2);
            $m = substr($request->tgl_lahir, 3, 2);
            $y = substr($request->tgl_lahir, 6, 4);
            $family = User_add_family::findOrNew($request->id);
            $family->user_id = Auth::id();
            $family->name = $request->nama;
            $family->hubungan = $request->hubungan;
            $family->marital_id = $request->marital_id;
            $family->jenis_kelamin = $request->jenis_kelamin;
            $family->tgl_lahir = "$y-$m-$d";
            $family->no_telp = str_replace("-", "", $request->no_telp);
            $file = $request->file("attachments");
            if(!empty($file)){
                $d = date("YmdHis");
                $newName = $d."_".Auth::id()."_family_".$file->getClientOriginalName();
                if($file->move($dir, $newName)){
                    $family->lampiran = "media/attachments/$newName";
                }
            }
            $family->save();
        } elseif($type == "license"){
            $d1 = substr($request->tgl_penerbitan, 0, 2);
            $m1 = substr($request->tgl_penerbitan, 3, 2);
            $y1 = substr($request->tgl_penerbitan, 6, 4);
            $tgl_kd = null;
            if(!empty($request->tgl_kadaluarsa)){
                $d2 = substr($request->tgl_kadaluarsa, 0, 2);
                $m2 = substr($request->tgl_kadaluarsa, 3, 2);
                $y2 = substr($request->tgl_kadaluarsa, 6, 4);
                $tgl_kd = "$y2-$m2-$d2";
            }
            $license = User_add_license::findOrNew($request->id);
            $license->user_id = Auth::id();
            $license->name = $request->nama;
            $license->organisasi = $request->organisasi;
            $license->tgl_penerbitan = "$y1-$m1-$d1";
            $license->tgl_kadaluarsa = $tgl_kd;
            $license->no_lisensi = $request->no_lisensi;
            $license->url = $request->url;
            $file = $request->file("attachments");
            if(!empty($file)){
                $d = date("YmdHis");
                $newName = $d."_".Auth::id()."_license_".$file->getClientOriginalName();
                if($file->move($dir, $newName)){
                    $license->lampiran = "media/attachments/$newName";
                }
            }
            $license->save();
        } elseif($type == "ktp"){
            $ktp = User_add_id_card::findOrNew($request->id);
            $ktp->user_id = Auth::id();
            $ktp->no_kartu = $request->no_kartu;
            $ktp->jenis_kartu = "ktp";
            $file = $request->file("attachments");
            if(!empty($file)){
                $d = date("YmdHis");
                $newName = $d."_".Auth::id()."_ktp_".$file->getClientOriginalName();
                if($file->move($dir, $newName)){
                    $ktp->lampiran = "media/attachments/$newName";
                }
            }
            $ktp->save();
        }

        return redirect()->back();
    }

    function addDelete($type, Request $request){
        if($type == "family"){
            $t = User_add_family::find($request->id);
        } elseif($type == "license"){
            $t = User_add_license::find($request->id);
        } elseif($type == "ktp"){
            $t = User_add_id_card::find($request->id);
        }
        if(!empty($t)){
            $t->delete();
        }

        return redirect()->back();
    }

    function setting_page_applicant(Request $request){
        $v = $request->v ?? "account_information";
        $user = User::find(Auth::id());
        $f = "setting";
        // dd($f);
        return view("users.setting", compact("v", "user", 'f'));
    }

    function setting_page_employer(Request $request){
        $v = $request->v ?? "account_information";

        $user = User::find(Auth::id());

        $company = Master_company::find(Auth::user()->comp_id);

        $industri = Master_industry::get();
        $industri_name = $industri->pluck("name", "id");

        $prov = Master_province::get();
        $prov_name = $prov->pluck("name", "id");

        $city = Master_city::get();
        $city_name = $city->pluck("name", "id");
        $city_prov = $city->pluck("prov_id", "id");

        $kec = Master_district::where('id', $company->kec_id ?? 0)->get();
        $kec_name = $kec->pluck("name", "id");

        $languages = Master_language::get();
        $languages_name = $languages->pluck("name", "id");

        $compImages = Kjk_company_photo::where('company_id', $company->id ?? null)->get();

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

        $compOwner = User::where("comp_id", $company->id ?? null)
            ->whereNull("dispatch_name")
            ->first();

        if($compOwner){
            $compOwner = $user;
        }

        $collaborators = User::where("comp_id", $company->id ?? null)
            ->where("dispatch_name", 'CB')
            ->get();

        $f = "setting_employer";
        return view("users.setting_employer", compact( "collaborators", "compOwner", "v", "user", 'f', 'industri', 'company', 'industri_name', 'prov', "city", "kec", 'prov_name', "city_name", "kec_name", "city_prov", "languages_name", "compImages"));
    }

    function account_activation(Request $request){
        $token = base64_decode($request->token);
        $_exp = explode("_", $token);
        $id = $_exp[0];
        $date = end($_exp);

        if(date("Y-m-d H:i:s") > $date){
            return redirect()->route("account.setting.uc_expired");
        }

        $user = User::findOrFail($id);

        return view("_crm.preferences.user.activation", compact("token", "user"));
    }

    function account_activation_post(Request $request){
        $id = base64_decode($request->id);

        $user = User::findOrFail($id);

        $user->password = Hash::make($request->password);
        $user->email_verified_at = date("Y-m-d H:i:s");

        $user->save();

        return redirect()->route("account.setting.uc_success");
    }

    function uc_verify(Request $request){
        $token = $request->token;
        $collabs = User_collaborator::where("token", $token)
            ->first();
        if(date("Y-m-d H:i:s") > $collabs->token_expired){
            return redirect()->route("account.setting.uc_expired");
        }

        return view("users.collabs", compact("token", "collabs"));
    }

    function uc_delete($type, Request $request){
        $user = User::find($request->id);
        if($type == "non"){
            $user->delete();
            $msg = "Kolaborator berhasil di non aktifkan";
        } else {
            $user->forceDelete();
            $msg = "Kolaborator berhasil dihapus";
        }

        return redirect()->back()->with(['msg' => $msg]);
    }

    function uc_expired(){
        return view("users.collabs_expired");
    }

    function uc_success(){
        return view("users.collabs_success");
    }

    function uc_update(Request $request){
        $rules = [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
        $request->validate($rules);

        $collabs = User_collaborator::where("token", $request->token)
            ->first();

        if(date("Y-m-d H:i:s") > $collabs->token_expired){
            return redirect()->route("account.setting.uc_expired");
        }

        $comp = Master_company::find($collabs->company_id);

        $_comp = ConfigCompany::find($collabs->comp_id);

        $collabsRole = [];
        $collabsRole['job_ads'] = $collabs->job_ads;
        $collabsRole['job_ads_applicant'] = $collabs->job_ads_applicant;
        $collabsRole['job_ads_report'] = $collabs->job_ads_report;
        $collabsRole['search_applicant'] = $collabs->search_applicant;
        $collabsRole['employer_account'] = $collabs->employer_account;
        $collabsRole['employer_purchasing'] = $collabs->employer_purchasing;
        $collabsRole['employer_profile'] = $collabs->employer_profile;

        $id_role = 45;
        $emailExp = explode("@", $collabs['email']);
        $username = $emailExp[0];
        $id_batch = $username."1";
        $user = User::firstOrNew(['email' => $collabs->email]);
        $user->name = $collabs->name;
        $user->email = $collabs->email;
        $user->username = $username;
        $user->password = Hash::make($request->password);
        $user->id_rms_roles_divisions = $id_role;
        $user->id_batch = $id_batch;
        $user->company_id = $collabs->comp_id;
        $user->company_hris_id = $_comp->hris_id;
        $user->comp_id = $comp->id;
        $user->position = "FP";
        $user->access = "EP";
        $user->dispatch_name = "CB";
        $user->email_verified_at = date("Y-m-d H:i:s");
        $user->complete_profile = 1;
        $user->do_code = $collabs->position;
        $user->dept = $collabs->departemen;
        $user->save();

        $collabs->verified_at = date("Y-m-d H:i:s");
        $collabs->save();

        $id_role = $user->id_rms_roles_divisions;

        $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
            ->where('id_rms_roles_divisions', $id_role)
            ->get();

        $moduleIdByName = Module::get()->pluck("name", "id");
        $actionNameById = Action::get()->pluck("name", "id");
        $_hasRole = [];
        foreach ($roleDivPriv as $key => $valDivPriv) {
            $aName = $actionNameById[$valDivPriv->id_rms_actions] ?? '';
            $mName = $moduleIdByName[$valDivPriv->id_rms_modules]?? '';
            $hasRole = true;
            if(in_array($mName, array_keys($collabsRole))){
                $_el = $collabsRole[$mName];
                if($_el != 1){
                    if(in_array($aName, ['approvediv1', 'approvediv2', 'approvedir', 'create', 'update', 'delete'])){
                        $hasRole = false;
                    }
                }
            }

            if($hasRole){
                $_hasRole[$mName][$aName] = $hasRole;
            }
        }


        return redirect()->route("account.setting.uc_success");
    }

    function uc_add(Request $request){
        $user = User::where("email", $request->email)->first();
        if(!empty($user)){
            return redirect()->back()->withErrors(["user" => "Email sudah terdaftar"]);
        }

        $hour = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $collabs = User_collaborator::where("email", $request->email)
            ->whereNull("verified_at")
            ->first();

        if(empty($collabs)){
            $collabs = new User_collaborator();
            $collabs->company_id = $request->id;
            $collabs->name = ucwords($request->name);
            $collabs->email = strtolower($request->email);
            foreach($request->all() as $key => $item){
                if(!in_array($key, ["_token", "id", "type", "name", "email"])){
                    $collabs->$key = $item;
                }
            }
        }

        $collabs->token_expired = $hour;
        $collabs->token = Str::random(32);
        $collabs->comp_id = Session::get("company_id");

        $collabs->save();

        $userFrom = User::find(Auth::id());
        $userTo = $collabs;

        try {
            Mail::to($collabs->email)->send(new MailConfig($userFrom, $userTo));

            return redirect()->back()->with(["msg" => "Undangan terkirim"]);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => "Undangan gagal terkirim"]);
        }
    }

    function setting_page(Request $request){
        if(Session::get("login_state") == "applicant"){
            return $this->setting_page_applicant($request);
        } else {
            return $this->setting_page_employer($request);
        }
    }

    function companyStore(Request $request){
        $comp = Master_company::findOrNew($request->id);
        if(empty($comp->owner_id)){
            $comp->owner_id = Auth::id();
        }
        if($request->type == "info"){
            $comp->company_name = $request->company_name;
            $comp->industry_id = $request->industri;
            $comp->npwp = $request->npwp;
            $comp->reg_num = $request->reg_num;
            $comp->descriptions = $request->overview;
        } elseif($request->type == "lokasi"){
            $comp->prov_id = $request->prov_id;
            $comp->city_id = $request->city_id;
            $comp->kec_id = $request->kec_id;
            $comp->address = $request->address;
            $comp->kode_pos = $request->kode_pos;
        } elseif($request->type == "additional"){
            $comp->company_year = $request->company_year;
            $comp->skala_usaha = $request->skala_usaha;
            $comp->dress_code = $request->dress_code;
            $comp->bahasa_id = $request->bahasa_id;
            $comp->link_company = $request->link_company;
            $comp->benefit = $request->benefit;
            $comp->jam_kerja = $request->jam_kerja;
        }
        $comp->save();
        if($request->type == "branding") {
            $comp->youtube_link = $request->youtube_link;

            $icon = $request->file("company_icon");
            if(!empty($icon)){
                $newName = "company_logo_$comp->id-".$icon->getClientOriginalName();
                $dir = $this->dir;
                if($icon->move($dir, $newName)){
                    $comp->icon = "media/attachments/$newName";
                    $comp->save();
                }
            }

            $banner = $request->file("company_banner");
            if(!empty($banner)){
                $newName = "company_banner_$comp->id-".$banner->getClientOriginalName();
                $dir = $this->dir;
                if($banner->move($dir, $newName)){
                    $comp->banner = "media/attachments/$newName";
                    $comp->save();
                }
            }

            $images = $request->file("company_images") ?? [];
            $removeImg = $request->remove_img ?? [];
            if(count($removeImg) > 0){
                Kjk_company_photo::whereIn("id", $removeImg)->where('company_id', $comp->id)->delete();
            }
            foreach($images as $i => $img){
                if(!empty($img) && $i > 0){
                    $newName = "company_images_$comp->id-".$img->getClientOriginalName();
                    $dir = $this->dir;
                    if($img->move($dir, $newName)){
                        $imgComp = new Kjk_company_photo();
                        $imgComp->company_id = $comp->id;
                        $imgComp->file_address = "media/attachments/$newName";
                        $imgComp->save();
                    }
                }
            }
        }

        $userComp = User::where("comp_id", $comp->id)->count();

        $user = User::find(Auth::id());
        $user->comp_id = $comp->id;
        $user->is_owner = $userComp == 0 ? 1 : null;
        $user->save();

        User_job_vacancy::where("user_id", $user->id)
            ->update([
                "company_id" => $comp->id
            ]);

        return redirect()->back();
    }

    function medsosStore(Request $request){
        $port = User_medsos::find($request->id);
        if(empty($port)){
            $port = new User_medsos();
            $port->user_id = Auth::id();
        }

        foreach($request->all() as $key => $item){
            if(!in_array($key, ["_token", "type", "id"])){
                if(!$request->hasFile($key)){
                    $port->$key = $item;
                }
            }
        }

        $port->save();

        return redirect()->back();
    }

    function portStore(Request $request){
        $port = User_portofolio::find($request->id);
        if(empty($port)){
            $port = new User_portofolio();
            $port->user_id = Auth::id();
        }

        foreach($request->all() as $key => $item){
            if(!in_array($key, ["_token", "type", "id"])){
                if(!$request->hasFile($key)){
                    $port->$key = $item;
                }
            }
        }

        $port->save();

        $classId = $port->id;
        $className = "User_portofolio";
        $classExp = explode("_", $className);
        $fNameArr = [];
        for ($i=1; $i < count($classExp); $i++) {
            $fNameArr[] = $classExp[$i];
        }

        $fName = implode("_", $fNameArr);

        $attachments = $request->file("attachments");
        if(!empty($attachments)){
            $newName = $fName."_$classId-".$attachments->getClientOriginalName();
            $dir = $this->dir;
            if($attachments->move($dir, $newName)){
                $att = new User_attachments();
                $att->user_id = Auth::id();
                $att->className = $className;
                $att->class_id = $classId;
                $att->file_address = "media/attachments/$newName";
                $att->file_name = $attachments->getClientOriginalName();
                $att->save();
            }
        }

        return redirect()->back();
    }

    function skillStore(Request $request){
        if($request->type == "skill"){
            $skill = User_skill::find($request->id);
            if(empty($skill)){
                $skill = new User_skill();
                $skill->user_id = Auth::id();
            }

            $skill->skill_name = $request->skill_name;
            $skill->proficiency = $request->proficiency;
            $skill->save();
        } else {
            $skill = User_language_skill::find($request->id);
            if(empty($skill)){
                $skill = new User_language_skill();
                $skill->user_id = Auth::id();
            }

            $skill->language = $request->language;
            $skill->reading = $request->reading;
            $skill->speaking = $request->speaking;
            $skill->writing = $request->writing;
            $skill->save();
        }

        return redirect()->back();
    }

    function skillDelete(Request $request){
        if($request->type == "skill"){
            $skill = User_skill::find($request->id)->delete();
        } else {
            $skill = User_language_skill::find($request->id)->delete();
        }

        return redirect()->back();
    }

    function expStore(Request $request){
        $exp = User_experience::find($request->id);
        if(empty($exp)){
            $exp = new User_experience();
            $exp->user_id = Auth::id();
        }

        foreach($request->all() as $key => $item){
            if(!in_array($key, ["_token", "id", "type", "start_month", "start_year", "end_month", "end_year", "still"])){
                if(!$request->hasFile($key)){
                    if($key == "salary"){
                        $sal = str_replace(".", "", $item);
                        $exp->$key = str_replace(",", ".", $sal);
                    } else {
                        $exp->$key = $item;
                    }
                }
            }
        }

        $exp->start_date = $request->start_year."-".$request->start_month;
        $still = $request->still;
        if(empty($still)){
            $exp->still = 0;
            $exp->end_date = $request->end_year."-".$request->end_month;
        } else {
            $exp->still = 1;
            $exp->end_date = null;
        }

        $exp->save();

        // $comp = Master_company::where('company_name', $exp->company)->first();
        // if(empty($comp)){
        //     $comp = new Master_company();
        //     $comp->company_name = $exp->company;
        //     $comp->location = $exp->location;
        //     $comp->save();
        // }

        $classId = $exp->id;
        $className = "User_experience";
        $classExp = explode("_", $className);
        $fNameArr = [];
        for ($i=1; $i < count($classExp); $i++) {
            $fNameArr[] = $classExp[$i];
        }

        $fName = implode("_", $fNameArr);

        $attachments = $request->file("attachments");
        if(!empty($attachments)){
            $newName = $fName."_$classId-".$attachments->getClientOriginalName();
            $dir = $this->dir;
            if($attachments->move($dir, $newName)){
                $att = new User_attachments();
                $att->user_id = Auth::id();
                $att->className = $className;
                $att->class_id = $classId;
                $att->file_address = "media/attachments/$newName";
                $att->file_name = $attachments->getClientOriginalName();
                $att->save();
            }
        }

        return redirect()->back();
    }

    function expDelete(Request $request){
        User_experience::find($request->id)->delete();

        return redirect()->back();
    }

    function eduDelete(Request $request){
        if($request->type == "formal-education"){
            User_formal_education::find($request->id)->delete();
        } else {
            User_informal_education::find($request->id)->delete();
        }

        return redirect()->back();
    }

    function eduStore(Request $request){
        if($request->type == "formal"){
            $edu = User_formal_education::find($request->id);
            if(empty($edu)){
                $edu = new User_formal_education();
                $edu->user_id = Auth::id();
            }
            $edu->degree = $request->degree;
            $edu->field_of_study = $request->field_of_study;
            $edu->school_name = $request->school_name;
            $edu->grade = $request->grade;
            $edu->start_date = $request->start_year."-".$request->start_month;
            $still = $request->still;
            if(empty($still)){
                $edu->still = 0;
                $edu->end_date = $request->end_year."-".$request->end_month;
            } else {
                $edu->still = 1;
                $edu->end_date = null;
            }
            $edu->descriptions = $request->descriptions;
            $edu->save();

            $className = "User_formal_education";
            $classId = $edu->id;
        } else {
            $edu = User_informal_education::find($request->id);
            if(empty($edu)){
                $edu = new User_informal_education();
                $edu->user_id = Auth::id();
            }
            $edu->course_name = $request->course_name;
            $edu->vendor = $request->vendor;
            $edu->start_date = $request->start_year."-".$request->start_month;
            $still = $request->still;
            if(empty($still)){
                $edu->still = 0;
                $edu->end_date = $request->end_year."-".$request->end_month;
            } else {
                $edu->still = 1;
                $edu->end_date = null;
            }
            $edu->descriptions = $request->descriptions;
            $edu->save();

            $className = "User_informal_education";
            $classId = $edu->id;
        }

        $classExp = explode("_", $className);
        $fNameArr = [];
        for ($i=1; $i < count($classExp); $i++) {
            $fNameArr[] = $classExp[$i];
        }

        $fName = implode("_", $fNameArr);

        $attachments = $request->file("attachments");
        if(!empty($attachments)){
            $newName = $fName."_$classId-".$attachments->getClientOriginalName();
            $dir = $this->dir;
            if($attachments->move($dir, $newName)){
                $att = new User_attachments();
                $att->user_id = Auth::id();
                $att->className = $className;
                $att->class_id = $classId;
                $att->file_address = "media/attachments/$newName";
                $att->file_name = $attachments->getClientOriginalName();
                $att->save();
            }
        }

        return redirect()->back();
    }

    function editAbout(Request $request){
        $user = User_profile::where("user_id", Auth::id())->first();
        if(empty($user)){
            $user = new User_profile();
            $user->user_id = Auth::id();
        }
        foreach($request->all() as $key => $item){
            if($key != "_token"){
                if($key == "salary_expect"){
                    $user->$key = str_replace(",", "", $item);
                } else {
                    $user->$key = $item;
                }
            }
        }
        $user->save();
        $user = User_profile::where("user_id", Auth::id())->first();
        Session::put("app_profile", $user);

        return redirect()->back();
    }

    function deleteAccount(Request $request){
        $user = User::find(Auth::id());

        $delete_schedule = date("Y-m-d", strtotime("+10 day"));
        $user->delete_schedule = $delete_schedule;
        $user->save();

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $redirect = "/";

        return redirect($redirect)->withErrors(['delete_account' => "Your account will be deleted permanently at ".date("d F Y", strtotime($delete_schedule))]);
    }

    function myApplicant(Request $request){
        $applied = User_job_applicant::where("user_id", Auth::id())->get();
        $bookmarked = Job_bookmark::where("user_id", Auth::id())->get();
        $job_list = User_job_vacancy::whereIn("id", $applied->pluck("job_id"))
            ->paginate(2);

        $companies = Master_company::whereIn("id", $job_list->pluck("company_id"))
            ->get();

        $province = Master_province::whereIn("id", $companies->pluck("prov_id"))->get();
        $city = Master_city::whereIn("id", $companies->pluck("city_id"))->get();

        $job_type = Master_job_type::whereIn("id", $job_list->pluck("job_type"))->get();

        if($request->a){
            if($request->a == "table"){
                $order = $request->sort_by ?? "asc";

                $filter = $request->filter ?? [];
                $myApplicant = User_job_applicant::where("user_id", Auth::id())
                    ->where(function($q) use($filter){
                        if(count($filter) > 0){
                            foreach($filter as $f){
                                if($f == 1){
                                    $q->orWhereIn("status", [1, 2]);
                                } else {
                                    $q->orWhere("status", $f);
                                }
                            }
                        }
                    })
                    ->orderBy("created_at", $order)
                    ->get();

                $job_list = User_job_vacancy::whereIn("id", $myApplicant->pluck("job_id"));

                $officer = User::pluck("name", "id")->toArray();

                $job_name = $job_list->pluck("position", "id")->toArray();
                $job_comp = $job_list->pluck("company_id", "id")->toArray();

                $companies = Master_company::whereIn("id", $job_list->pluck("company_id"));
                $comp_name = $companies->pluck("company_name", "id")->toArray();
                $comp_address = $companies->pluck("address", "id")->toArray();

                $myList = [];

                foreach($myApplicant as $item){
                    if(isset($job_name[$item->job_id])){

                        $statusLabel = "Terkirim";
                        $statusClass = "blue";
                        if($item->status == 1 || $item->status == 2){
                            $statusLabel = "Review";
                            $statusClass = "yellow";
                        } elseif($item->status == 3){
                            $statusLabel = "Interview";
                            $statusClass = "orange";
                        } elseif($item->status == 4){
                            $statusLabel = "Lolos";
                            $statusClass = "green";
                        } elseif($item->status == 5){
                            $statusLabel = "Gagal";
                            $statusClass = "red";
                        } elseif($item->status == -1){
                            $statusLabel = "Batal";
                            $statusClass = "secondary";
                        }

                        if(!empty($item->rejected_by_applicant)){
                            $statusLabel = "Batal";
                            $statusClass = "secondary";
                        }

                        $status = "<span class='badge badge-$statusClass'>$statusLabel</span>";

                        $showCancel = true;
                        if($item->status > 1){
                            $showCancel = false;
                        }

                        $int = $item->interview;

                        $col = [];
                        $jcid = $job_comp[$item->job_id];
                        $col['posisi'] = $job_name[$item->job_id] ?? "-";
                        $col['company'] = $comp_name[$jcid] ?? "-";
                        $col['address'] = $comp_address[$jcid] ?? "-";
                        $col['status'] = $status;
                        $col['officer'] = $officer[$int->int_officer ?? null] ?? "-";
                        $col['apply_date'] = date("d/M/Y", strtotime($item->created_at));
                        $actionButton = '<button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">';
                        $actionButton .= '<i class="fa fa-ellipsis-vertical text-dark"></i>';
                        $actionButton .= '</button>';
                        $actionButton .= '<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">';
                        $actionButton .= '<div class="menu-item px-3">
                                            <a href="'.route('account.my_applicant_detail', $item->id).'" class="menu-link px-3">
                                                Lihat detail lamaran
                                            </a>
                                        </div>';
                        if($showCancel){
                            $actionButton .= '<div class="menu-item px-3">
                                            <a href="'.route('account.my_applicant_cancel', $item->id).'" class="menu-link px-3">
                                                Batal Mengirim Lamaran
                                            </a>
                                        </div>';
                        }
                        $actionButton .= '</div>';
                        // $col['action'] = "<a href='".route('account.my_applicant_detail', $item->id)."' class='btn btn-sm btn-icon bg-hover-secondary'><i class='fa fa-ellipsis-v text-dark'></i></a>";
                        $col['action'] = $actionButton;
                        $myList[] = $col;
                    }
                }

                return DataTables::collection($myList)
                    ->rawColumns(['action', 'status', 'address'])
                    ->make(true);
            }
        }

        if($request->page){
            $order = $request->sort_by ?? "asc";

            $filter = $request->filter ?? [];
            $myApplicant = User_job_applicant::where("user_id", Auth::id())
                ->where(function($q) use($filter){
                    if(count($filter) > 0){
                        foreach($filter as $f){
                            if($f == 1){
                                $q->orWhereIn("status", [1, 2]);
                            } else {
                                $q->orWhere("status", $f);
                            }
                        }
                    }
                })
                ->orderBy("created_at", $order)
                ->paginate(2);

            $job_list = User_job_vacancy::whereIn("id", $myApplicant->pluck("job_id"));

            $officer = User::pluck("name", "id")->toArray();

            $job_name = $job_list->pluck("position", "id")->toArray();
            $job_comp = $job_list->pluck("company_id", "id")->toArray();

            $companies = Master_company::whereIn("id", $job_list->pluck("company_id"));
            $comp_name = $companies->pluck("company_name", "id")->toArray();
            $comp_image = $companies->pluck("icon", "id")->toArray();
            $comp_address = $companies->pluck("address", "id")->toArray();

            $myList = [];

            foreach($myApplicant as $item){
                if(isset($job_name[$item->job_id])){

                    $statusLabel = "Terkirim";
                    $statusClass = "blue";
                    if($item->status == 1 || $item->status == 2){
                        $statusLabel = "Review";
                        $statusClass = "yellow";
                    } elseif($item->status == 3){
                        $statusLabel = "Interview";
                        $statusClass = "orange";
                    } elseif($item->status == 4){
                        $statusLabel = "Lolos";
                        $statusClass = "green";
                    } elseif($item->status == 5){
                        $statusLabel = "Gagal";
                        $statusClass = "red";
                    } elseif($item->status == -1){
                        $statusLabel = "Batal";
                        $statusClass = "secondary";
                    }

                    if(!empty($item->rejected_by_applicant)){
                        $statusLabel = "Batal";
                        $statusClass = "secondary";
                    }

                    $status = "<span class='badge badge-$statusClass'>$statusLabel</span>";

                    $int = $item->interview;

                    $col = [];
                    $jcid = $job_comp[$item->job_id];
                    $col['posisi'] = $job_name[$item->job_id] ?? "-";
                    $col['company'] = $comp_name[$jcid] ?? "-";
                    $col['image'] = asset($comp_image[$jcid] ?? "theme/assets/media/avatars/blank.png");
                    $col['address'] = $comp_address[$jcid] ?? "-";
                    $col['job_type'] = $item->job_type;
                    $col['status'] = $status;
                    $col['id'] = $item->id;
                    $col['officer'] = $officer[$int->int_officer ?? null] ?? "-";
                    $col['apply_date'] = $item->created_at;
                    $col['action'] = route('account.my_applicant_detail', $item->id);
                    $myList[] = $col;
                }
            }

            $view = view("users._applicant_list", compact("myList"))->render();

            // dd($job_list);

            return json_encode([
                "data" => $view,
                "currentPage" => $myApplicant->currentPage(),
                "lastPage" => $myApplicant->lastPage(),
                "nextUrl" => $myApplicant->nextPageUrl(),
                "prevUrl" => $myApplicant->previousPageUrl(),
            ]);
        }

        return view("users.applicant", compact("job_list", "job_type", "province", "city", "companies", "applied", "bookmarked"));
    }

    function myApplicantCancel($id){
        $applicant = User_job_applicant::findOrFail($id);

        $applicant->delete();

        return redirect()->back();
    }

    function myApplicantDetail($id){
        $job_applicant = User_job_applicant::find($id);
        $job_ad = User_job_vacancy::find($job_applicant->job_id);
        // dd($job_ad);
        $mComp = Master_company::find($job_ad->company_id);

        $officer = User::find($job_applicant->interview_assign_to);

        $comp_prov = Master_province::find($mComp->prov_id ?? null);
        $comp_city = Master_city::find($mComp->city_id ?? null);

        $applicants = User_job_applicant::where("job_id", $job_applicant->job_id);

        $interviews = User_job_interview::where("job_app_id", $id)->orderBy("int_date", "asc")->orderBy("int_start", "asc")->get();

        $user_assign = User::whereIn("id", $interviews->pluck("int_officer"))->get();

        $statusLabel = "Terkirim";
        $statusClass = "blue";
        if($job_applicant->status == 1 || $job_applicant->status == 2){
            $statusLabel = "Review";
            $statusClass = "yellow";
        } elseif($job_applicant->status == 3){
            $statusLabel = "Interview";
            $statusClass = "orange";
        } elseif($job_applicant->status == 4){
            $statusLabel = "Lolos";
            $statusClass = "green";
        } elseif($job_applicant->status == 5){
            $statusLabel = "Gagal";
            $statusClass = "red";
        } elseif($job_applicant->status == -1){
            $statusLabel = "Batal";
            $statusClass = "secondary";
        }

        if(!empty($job_applicant->rejected_by_applicant)){
            $statusLabel = "Batal";
            $statusClass = "secondary";
        }

        $status = "<span class='badge badge-$statusClass'>$statusLabel</span>";

        return view("users.applicant_detail", compact("job_applicant", "officer", "job_ad", "mComp", "comp_prov", "comp_city", "applicants", "interviews", "user_assign", "status", "statusClass"));
    }

    function myApplicantReschedule(Request $request){
        $interview = User_job_interview::find($request->id);
        $interview->reschedule = 1;
        $interview->reschedule_reasons = $request->reason;
        $interview->save();

        $applicant = User_job_applicant::find($interview->job_app_id);
        $applicant->need_reschedule = 1;
        $applicant->save();

        $job = User_job_vacancy::find($applicant->job_id);

        $data = [];

        $data['users'][] = "$job->user_id";
        $collaborators = $job->collabotators ?? [];
        array_merge($data['users'], $collaborators);

        $data['text'] = Auth::user()->name." meminta untuk jadwal ulang interview untuk job ad $job->position";

        $data['url'] = route("calendar.index");

        $data['id'] = $applicant->id;

        \Notif_kjk::notifSave($data);

        return redirect()->route("account.my_applicant");
    }

    function myApplicantUpdate(Request $request){
        $int = User_job_interview::find($request->id);
        $int->accepted = $request->val == 1 ? 1 : null;
        $int->save();

        $applicant = User_job_applicant::find($int->job_app_id);

        $job = User_job_vacancy::find($applicant->job_id);

        if($request->val == -1){
            $applicant->rejected_by_applicant = 1;
            $applicant->status = 2;
            $applicant->backlog_at = date("Y-m-d H:i:s");
            $applicant->save();

            $int->delete();

            $data = [];

            $data['users'][] = "$job->user_id";
            $collaborators = $job->collabotators ?? [];
            array_merge($data['users'], $collaborators);

            $data['text'] = Auth::user()->name." menolak undangan interview untuk job ad $job->position";

            $data['url'] = route("calendar.index");

            $data['id'] = $applicant->id;

            \Notif_kjk::notifSave($data);

            return redirect()->route("account.my_applicant");
        }

        $data = [];

        $data['users'][] = "$job->user_id";
        $collaborators = $job->collabotators ?? [];
        array_merge($data['users'], $collaborators);

        $data['text'] = Auth::user()->name." menerima undangan interview untuk job ad $job->position";

        $data['url'] = route("calendar.index");

        $data['id'] = $applicant->id;

        \Notif_kjk::notifSave($data);

        return redirect()->back();
    }

    function myBookmark(){
        $bookmarked = Job_bookmark::where("user_id", Auth::id())->get();
        $job_list = User_job_vacancy::whereIn("id", $bookmarked->pluck("job_id"))
            ->get();

        $companies = Master_company::whereIn("id", $job_list->pluck("company_id"))
            ->get();

        $province = Master_province::whereIn("id", $companies->pluck("prov_id"))->get();
        $city = Master_city::whereIn("id", $companies->pluck("city_id"))->get();

        $job_type = Master_job_type::whereIn("id", $job_list->pluck("job_type"))->get();

        return view("users.bookmark", compact("job_list", "job_type", "province", "city", "companies", "bookmarked"));
    }

    function randomize(Request $request){
        $user = User::find($request->id);
        $code = random_int(100000, 999999);
        $codeExist = User::where("attend_code", $code)->first();
        while(!empty($codeExist)){
            $code = random_int(100000, 999999);
            $codeExist = User::where("attend_code", $code)->first();
        }
        $user->attend_code = $code;
        if($user->save()){
            $data =['success' => 1];
            return json_encode($data);
        } else {
            return json_encode("error");
        }
    }

    public function updatePasswordAccount(Request $request){
        $this->validate($request,[
            'password' => 'required'
        ]);

        $user = User::find($request['id']);

        User::where('username',$user->username)
            ->where('id_batch', $user->id_batch)
            ->update([
                'password' => Hash::make($request['password']),
            ]);

        Auth::logout();
        return redirect()->route('home');
    }

    public function updateAccountInfo(Request $request){
        $uploaddir = public_path('theme\\assets\\media\\users');

        $pictureInput = $request->file('user_img');
        if ($pictureInput!= null) {
            $picture = $request['id'] . "-profile." . $pictureInput->getClientOriginalExtension();


            $path = $uploaddir . '\\' . $picture;
            if (file_exists($path)) {
                @unlink($path);
            }
            $pictureInput->move($uploaddir, $picture);
            $emp_picture = $picture;
            User::where('id', $request['id'])
                ->update([
                    'user_img' => $emp_picture,
                ]);
        }
        return redirect()->route('account.info',['id'=>$request['id']]);
    }

    function user_exist($username, $company_id){
        $user = User::where('username', $username)
            ->where('company_id', $company_id)
            ->first();

        $exist = 0;

        if(!empty($user)){
            $exist = 1;
        }

        return $exist;
    }

    function add(Request $request){
         if (isset($request->export)){
            $user = User::where('id', $request->user_company)->first();

            if($this->user_exist($user->username, base64_decode($request->coid)) == 0){
                $userNew = new User();
                $userNew->id_batch = $user->id_batch;
                $userNew->name = $user->name;
                $userNew->password = $user->password;
                $userNew->username = $user->username;
                $user->complete_profile = 1;
                $userNew->email = $user->email;
                $userNew->company_id = base64_decode($request->coid);
                $userNew->id_rms_roles_divisions = $user->id_rms_roles_divisions;
                $userNew->save();

                $upriv = UserPrivilege::select("id_rms_modules", "id_rms_actions")
                    ->where('id_users', $user->id)
                    ->get();

                $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
                ->where('id_rms_roles_divisions', $userNew->id_rms_roles_divisions)
                ->get();
                if(!empty($upriv)){
                    foreach ($upriv as $key => $valDivPriv) {
                        $addUserRole = new UserPrivilege;
                        $addUserRole->id_users = $userNew->id;
                        $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
                        $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
                        $addUserRole->save();
                    }
                }
            } else {
                return redirect()->back()->with('msg', 'User Exist');
            }

        } else {
            $name = $request->name;
            $email = $request->email;
            $_email = explode("@", $request->email);
            $username = $_email[0];
            $password = Hash::make($request->password);
            // $position = $request->position;
            $id = base64_decode($request->coid);

            $usernameExist =  User::selectRaw("*, CAST(RIGHT(id_batch, (LENGTH(id_batch) - LENGTH(username))) as UNSIGNED) batch_num")->where("email", $email)
                ->orderBy('batch_num', 'desc')->get();

            $uacrole = \App\Models\Kjk_uac_role::where('company_id', $id)
                ->orderBy("id")->first();

            if (count($usernameExist) == 0) {

                $accounts = User::where("company_id", $id)->count();
                $isOwner = 0;
                if($accounts == 0){
                    $isOwner = 1;
                }

                $user = new User;
                $user->id_batch = $username."1";
                $user->emp_id = $request->empId;
                $user->name = $name;
                $user->email = $email;
                $user->username = $username;
                $user->password = $password;
                if(!empty($request->dispatch_name)){
                    $user->dispatch_name = $request->dispatch_name;
                }
                // $user->position = $position;
                $user->id_rms_roles_divisions = $request->userRoleAdd;
                $user->company_id = $id;
                $user->complete_profile = 1;
                $user->access = "EP";
                $user->is_owner = $isOwner == 1 ? 1 : null;
                $user->role_access = json_encode(["hris"]);
                $user->email_verified_at = date("Y-m-d H:i:s");
                $user->uac_role = $uacrole->id ?? null;
                $user->save();

                if($isOwner == 1){
                    $mComp = Master_company::where("company_id", $user->company_id)->first();
                    if(!empty($mComp)){
                        $mComp->owner_id = $user->id;
                        $mComp->save();
                    }
                }

                //Add user privilege based on position
                $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
                ->where('id_rms_roles_divisions', $user->id_rms_roles_divisions)
                ->get();
                foreach ($roleDivPriv as $key => $valDivPriv) {
                    $addUserRole = new UserPrivilege;
                    $addUserRole->id_users = $user->id;
                    $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
                    $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
                    $addUserRole->save();
                }
            } else {
                $userComp = $usernameExist->where("company_id", $id)->first();
                $userEdit = $usernameExist->whereNull("uac_role")->first();
                $_batch = $userEdit->id_batch;
                if($usernameExist->count() > 1){
                    $_batch = $userEdit->username.($usernameExist->last()->batch_num ?? 0) + 1;
                }
                if(empty($userComp)){
                    $userEdit->id_batch = $_batch;
                    $rAccess = json_decode($userEdit->role_access, true) ?? [];
                    $rAccess[] = "hris";
                    $userEdit->company_id = $id;
                    $userEdit->role_access = json_encode($rAccess);
                    $userEdit->uac_role = $uacrole->id ?? null;
                    $userEdit->save();
                } else {
                    return redirect()->back()->with([
                        "toast" => [
                            "message" => "Email sudah terdaftar",
                            "bg" => "bg-danger"
                        ]
                    ]);
                }
            }
        }

        return redirect()->back();
    }

    function edit(Request $request){
        $user = User::find($request->id_u);

        $user->username = $request->username;
        $user->email = $request->name;
        $user->email = $request->email;
        $user->emp_id = $request->empId;

        User::where('username', $user->username)
            ->where("id_batch", $user->id_batch)
            ->update([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email
            ]);
        if (!empty($request->password) || $request->password != "" || $request->password != null){
            // $user->password = Hash::make($request->password);
            // $userPass = User::where("username", $user->username);
            // $userPass->password = Hash::make($request->password);
            User::where('username', $user->username)
                ->where("id_batch", $user->id_batch)
                ->update([
                    'password' => Hash::make($request->password)
                ]);
        }
        if (!empty($request->do_code) || $request->do_code != "" || $request->do_code != null){
            $user->do_code = $request->do_code;
        } elseif (empty($request->do_code) && isset($request->delete_code) && $request->delete_code == 1) {
            $user->do_code = null;
        }
        // $user->position = $request->position;
        $user->save();

        // Change user position
        if($request->userRoleEdit != $request->userRoleEditOld)
        {
            $userRole = User::find($request->id_u);
            $userRole->id_rms_roles_divisions = $request->userRoleEdit;
            $userRole->save();

            //Delete existing user privilege
            UserPrivilege::where('id_users', $request->id_u)->forceDelete();

            //Edit user privilege based on new position
            $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
            ->where('id_rms_roles_divisions', $request->userRoleEdit)
            ->get();

            foreach ($roleDivPriv as $key => $valDivPriv)
            {
                $editUserRole = new UserPrivilege;
                $editUserRole->id_users = $request->id_u;
                $editUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
                $editUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
                $editUserRole->save();
            }
        }

        if(Auth::id() == $user->id){
            //RoleManagement
            $modules = Module::all()->pluck('name', 'id');
            $actions = Action::all()->pluck('name', 'id');
            $userPriv = UserPrivilege::where('id_users', $user->id)->get();
            $pr = [];
            foreach($userPriv as $priv){
                if(isset($modules[$priv->id_rms_modules])){
                    if(isset($actions[$priv->id_rms_actions])){
                        $pr[$modules[$priv->id_rms_modules]][$actions[$priv->id_rms_actions]] = 1;
                    }
                }
            }
            Session::put('company_user_rc', $pr);
        }

        return redirect()->back();
    }
    function delete($id){
        $rms = DB::table('rms_users_privileges')->where('id_users', $id)->get();
        $count_rms = count($rms);
        // dd($rms);
        if ($count_rms > 0) {
            DB::table('rms_users_privileges')->where('id_users', $id)->delete();
        }
        $user = User::find($id);

        if ($user->delete()){
            $data['del'] = 1;
        } else {
            $data['del'] = 0;
        }

        // return json_encode($data);
        return redirect()->back();
    }

    public function getUserPrivilege($id){
        $user = User::where('id',$id)->first();
        $companyId = base64_encode($user->company_id);
        $modules = Module::orderBy("name")->get();
        $moduleList = [];
        foreach($modules as $item){
            $moduleList[$item->id] = $item->desc ?? $item->name;
        }
        $actionList = Action::pluck('name', 'id');

        return view('users.privilege',compact('companyId','user','actionList','moduleList'));
    }

    public function updatePrivilege($id, Request $request){
        if($request->privilege)
        {
            UserPrivilege::where('id_users', $id)->forceDelete();
            foreach($request->privilege as $moduleId => $actionList)
            {
                foreach($actionList as $actionId => $value)
                {
                    $privilege = new UserPrivilege;
                    $privilege->id_users = $id;
                    $privilege->id_rms_modules = $moduleId;
                    $privilege->id_rms_actions = $actionId;
                    $privilege->save();
                }
            }
        }
        else
        {
            UserPrivilege::where('id_users', $id)->forceDelete();
        }

        if(Auth::id() == $id){
            //RoleManagement
            $modules = Module::all()->pluck('name', 'id');
            $actions = Action::all()->pluck('name', 'id');
            $userPriv = UserPrivilege::where('id_users', $id)->get();
            $pr = [];
            foreach($userPriv as $priv){
                if(isset($modules[$priv->id_rms_modules])){
                    if(isset($actions[$priv->id_rms_actions])){
                        $pr[$modules[$priv->id_rms_modules]][$actions[$priv->id_rms_actions]] = 1;
                    }
                }
            }
            Session::put('company_user_rc', $pr);
        }

        return redirect()->route('user.privilege', $id);
    }

    function signAdd(Request $request, $id){
        $user = User::find($id);

        $type = strtolower($request->type);

        if ($request->rb_sign == 1) {
            $folderPath = public_path("media/user/".$type."/");

            $image_parts = explode(";base64,", $request->imageData);

            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);

            $file_name = uniqid() . '.'.$image_type;

            $file = $folderPath . $file_name;
            $up = file_put_contents($file, $image_base64);

            // $image = $request['imageData'];
            // $image = str_replace('data:image/png;base64,', '', $image);
            // $image = str_replace(' ', '+', $image);

            // $file_name = "u-(".$user->id.")_" .$type . '.png';
            // $up = Storage::disk('sign_account')->put($file_name,base64_decode($image));
        } else {
            $file = $request->file('file_upload');
            $target = public_path("media/user/".$type."/");
            $file_name = "(".$user->id.")".$file->getClientOriginalName();
            $up = $file->move($target, $file_name);
        }

        if ($up) {
            $ftype = "file_$type";
            $user[$ftype] = $file_name;
            User::where('username', $user->username)
                ->where("id_batch", $user->id_batch)
                ->update([
                    "file_".$type => $file_name
                ]);
            // $user->save();
            $msg = "File uploaded";
            $success = true;
        } else {
            $msg = "Failed to upload";
            $success = false;
        }

        $result = array(
            "success" => $success,
            "message" => $msg
        );

        return json_encode($result);


    }

    public function inherit($id){
        $user = User::find($id);

        //Add user privilege based on position
        $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
            ->where('id_rms_roles_divisions', $user->id_rms_roles_divisions)
            ->get();
        UserPrivilege::where('id_users', $id)->forceDelete();
        foreach ($roleDivPriv as $key => $valDivPriv)
        {
            $addUserRole = new UserPrivilege;
            $addUserRole->id_users = $user->id;
            $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
            $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
            $addUserRole->save();
        }

        return redirect()->back();
    }

    public function userModule(Request $request){

        $user = User::find($request->id);
        $module = Module::find($request->module);
        $action = Action::all()->pluck('name', 'id');

        $otherUsers = User::where('username', $user->username)
            ->where("id_batch", $user->id_batch)
            ->get()->pluck('company_id', 'id');

        $companies = ConfigCompany::all()->pluck('company_name', 'id');

        return view('users._module_modal', [
            "user" => $user,
            "module" => $module,
            "actions" => $action,
            'companies' => $companies,
            'userComp' => $otherUsers
        ]);
    }

    public function userModuleSave(Request $request){
        $id_comp = [];
        foreach($request->company as $compKey => $item){
            $id_comp[] = $compKey;
        }

        $user = User::find($request->id_user);

        $users = User::where('username', $user->username)
            ->where("id_batch", $user->id_batch)
            ->whereIn('company_id', $id_comp)
            ->get();


        foreach ($users as $itemUser) {
            $priv = UserPrivilege::where('id_users', $itemUser->id)
                ->where('id_rms_modules', $request->id_module);

            if(!empty($priv->get())){
                $priv->forceDelete();
            }
            if(isset($request->privilege)){
                foreach ($request->privilege as $key => $value) {
                    $newPriv = new UserPrivilege();
                    $newPriv->id_users = $itemUser->id;
                    $newPriv->id_rms_modules = $request->id_module;
                    $newPriv->id_rms_actions = $key;
                    $newPriv->save();
                }
            }
        }

        return redirect()->back();
    }

    function my_zakat(){
        $emp = Hrd_employee::find(Auth::user()->emp_id);
        $balance = 0;
        $zakat = [];
        if(!empty($emp)){
            $zakat = Users_zakat::where('emp_id', $emp->id)
                ->orderBy('created_at', 'desc')
                ->get();
            foreach ($zakat as $key => $value) {
            $balance += $value->amount - $value->paid;
        }
        }


        return view('users.zakat', compact('emp', 'zakat', 'balance'));
    }

    function pay_zakat(Request $request){
        $amount = str_replace(",", "", $request->payment_amount);

        $zakat = new Users_zakat();
        $zakat->emp_id = Auth::user()->emp_id;
        $zakat->description = "Payment Zakat at ".date("F Y");
        $zakat->amount = $amount * -1;
        $zakat->company_id = Auth::user()->company_id;
        $zakat->save();
        return redirect()->back()->with('msg', 'success');
    }

    function toPdf($id, Request $request){
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

        $bookmarked = [];

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

        $wpt_test = Hrd_employee_test::where("category_id", 4)->first();
        $disc_test = Hrd_employee_test::where("category_id", 5)->first();
        $mbti_test = Hrd_employee_test::where("category_id", 2)->first();
        $papikostik_test = Hrd_employee_test::where("category_id", 3)->first();
        $user = User::find($id);

        $wpt_data = [];
        $disc_data = [];
        $mbti_data = [];
        $papikostik_data = [];

        $test_result = Hrd_employee_test_result::where('user_id', $applicant->id)
            ->whereNotNull("result_end")
            ->orderBy("id", "desc")->get();

        $wpt_result = $test_result->where("test_id", $wpt_test->id)->first();
        $disc_result = $test_result->where("test_id", $disc_test->id)->first();
        $mbti_result = $test_result->where("test_id", $mbti_test->id)->first();
        $papikostik_result = $test_result->where("test_id", $papikostik_test->id)->first();

        // $profile = User_profile::where("user_id", $applicant->user_id)->first();
        $umur = "-";
        if(!empty($profile)){
            $d1 = date_create($profile->birth_date);
            $d2 = date_create(date("Y-m-d"));
            $diff = date_diff($d1, $d2);
            $umur = $diff->format("%y");
        }

        if(!empty($wpt_result)){
            $wpt_data['jawaban'] = json_decode($wpt_result->result_detail, true);
            $wpt_data['point'] = Hrd_employee_question_point::whereIn("question_id", $wpt_test->questions->pluck("id"))->pluck("order_num", "id");
            $wpt_data['result'] = Kjk_wpt_result::where("test_result_id", $wpt_result->id)
                ->firstOrFail();
            $wpt_data['skor'] = json_decode($wpt_data['result']->test_result, true);
            $wpt_data['iq'] = Kjk_wpt_score_iq::where("score", $wpt_data['result']->score)->first();
            $wpt_data['interpretasi'] = Kjk_wpt_interpretasi::where("score", ">=", $wpt_data['result']->score)
                ->orderBy("score")
                ->first();
        }

        if(!empty($disc_result)){
            $disc_data['psikogram_data'] = Kjk_disc_psikogram::where("test_result_id", $disc_result->id)
                ->first();

            $disc_data['psikogram'] = json_decode($disc_data['psikogram_data']->psikogram, true);

            $line1 = json_decode($disc_data['psikogram_data']->line1, true);
            $line2 = json_decode($disc_data['psikogram_data']->line2, true);
            $line3 = json_decode($disc_data['psikogram_data']->line3, true);
            arsort($line1);
            arsort($line2);
            arsort($line3);

            $code_line1 = [];
            $code_line2 = [];
            $code_line3 = [];
            foreach($line1 as $key => $item){
                if($item > 0 && count($code_line1) < 3){
                    $code_line1[] = $key;
                }
            }

            foreach($line2 as $key => $item){
                if($item > 0 && count($code_line2) < 3){
                    $code_line2[] = $key;
                }
            }

            foreach($line3 as $key => $item){
                if($item > 0 && count($code_line3) < 3){
                    $code_line3[] = $key;
                }
            }

            $disc_data['desc_line'][1] = Kjk_disc_desc_line::line1()->where("code", implode("-", $code_line1))->first();
            $disc_data['desc_line'][2] = Kjk_disc_desc_line::line1()->where("code", implode("-", $code_line2))->first();
            $disc_data['desc_line'][3] = Kjk_disc_desc_line::line1()->where("code", implode("-", $code_line3))->first();

            $disc_data['desc_kepribadian'] = Kjk_disc_desc_line::line2()->where("code", implode("-", $code_line3))->first();
            $disc_data['desc_job'] = Kjk_disc_desc_line::line3()->where("code", implode("-", $code_line3))->first();

            $disc_data['disc'] = ["D", "I", "S", "C", "*"];

            if($request->a){
                if($request->a == "chart"){
                    $response = $disc_data['psikogram'][$request->l];
                    if(isset($response["*"])){
                        unset($response["*"]);
                    }
                    return json_encode($response);
                }
            }
        }

        if(!empty($mbti_result)){
            $mbti_data['result'] = Kjk_mbti_psikogram::where("test_result_id", $mbti_result->id)
                ->first();

            $mbti_data['identifier'] = Kjk_mbti_analysis_identifier::where("code", $mbti_data['result']->identifier)->first();

            $mbti_data['_desc'] = ["A. DESKRIPSI KEPRIBADIAN", "B. SARAN DAN PENGEMBANGAN", "C. SARAN PROFESI"];

            $mbti_data['analysis'][0] = Kjk_mbti_analysis::where("identifier", $mbti_data['identifier']->identifier ?? null)
                ->where("descriptions", "!=", "")
                ->where("code", "like", "d%")
                ->orderBy("code")
                ->get();
            $mbti_data['analysis'][1] = Kjk_mbti_analysis::where("identifier", $mbti_data['identifier']->identifier ?? null)
                ->where("descriptions", "!=", "")
                ->where("code", "like", "s%")
                ->orderBy("code")
                ->get();
            $mbti_data['analysis'][2] = Kjk_mbti_analysis::where("identifier", $mbti_data['identifier']->identifier ?? null)
                ->where("descriptions", "!=", "")
                ->where("code", "like", "p%")
                ->orderBy("code")
                ->get();

            $mbti_data['tag'] = Kjk_mbti_key::pluck("tag", "identifier");
        }

        if(!empty($papikostik_result)){
            $papikostik_data['param'] = Papikostik_parameter::where("company_id", $papikostik_test->company_id)->get();
            $papikostik_data['result'] = Papikostik_psikogram::whereIn("p_id", $papikostik_data['param']->pluck('id'))
                ->where("test_result_id", $papikostik_result->id)
                // ->where("user_id", $request->uid ?? Auth::id())
                ->get();
            $papikostik_data['psikogram'] = [];
            foreach($papikostik_data['result'] as $item){
                $papikostik_data['psikogram'][$item->p_id] = $item;
            }
            $papikostik_data['cat'] = [1=>"R", "K", "C", "B", "T"];
        }

        return view("_applicant.to_pdf", compact("data", 'wpt', "wpt_iq", 'umur', 'wpt_test', 'disc_test', 'mbti_test', 'papikostik_test', 'wpt_result', 'disc_result', 'mbti_result', 'papikostik_result', 'wpt_data', 'disc_data', 'mbti_data', 'papikostik_data', "wpt_interpretasi", "profile", "exp", "test_list", "test", "bookmarked", "applicant", "experience", "edu_formal", "edu_informal", "languages", "skills", "portofolio", "add_family", "add_id_card", "add_license"));
    }

    function resetMistake(){
        $experience = User_experience::whereRaw("length(resign_reason) = 1")
            ->where("resign_reason", "!=", "-")
            ->get();

        $completion = \App\Models\User_complete_profile::whereIn("user_id", $experience->pluck("user_id"))
            ->pluck("pengalaman_kerja", "user_id");

        foreach($experience as $item){
            $comp = json_decode($completion[$item->user_id] ?? "[]", true);
            if(!empty($comp)){
                $query = "update user_experience set resign_reason = '".$comp['resign_reason']."'";
                if(!empty($comp['ref_pos'])){
                    $query .= ", ref_pos = '".$comp['ref_pos']."'";
                }
                $query .= " where id = $item->id;#$item->ref_pos $item->resign_reason<br>";
                echo $query;
            }
        }
    }
}
