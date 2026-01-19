<?php

namespace App\Http\Controllers\Auth;

use Session;
use App\Models\User;
use App\Models\Action;
use App\Models\Module;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use App\Models\Asset_pre;
use App\Models\Asset_sre;
use App\Models\Hrd_config;
use App\Models\Hrd_overtime;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\UserPrivilege;
use App\Models\Hrd_announcement;
use App\Models\Notification_log;
use App\Models\Preference_config;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\General_meeting_zoom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use App\Models\General_meeting_zoom_participant;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\General_ti;
use App\Models\Hrd_employee;
use App\Models\Role;
use App\Models\RoleDivision;
use App\Models\RolePrivilege;
use App\Models\User_profile;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use GuzzleHttp\Client as Http;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'login_portal', 'login_lms', 'hasSession');
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

    function login_lms(){
        $user = Auth::user();
        $email = $user->email;
        $pass = base64_decode(Session::get("ps"));
        $data['username'] = $email;
        $data['password'] = $pass;
        $url = "https://api-lms-kerjaku.databiota.com/api/v1/user/auth/login";
        $resp = $this->http_request($url, "POST", $data);
        return json_encode($resp);
    }


    public function username()
    {
        return 'email'; //or return the field which you want to use.
    }

    public function login_portal(Request $request){
        $id = base64_decode($request->id);

        $user = User::find($id);

        return $this->login($request, $user);
    }

    public function login(Request $request, $userData = null)
    {
        $logged = false;
        if(empty($userData)){
            $this->validateLogin($request);

            if (method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            $bp = \Config::get("constants.IS_BP") ? "BP" : "FP";

            $id_rms = 1;
            if($request->role == "applicant"){
                $id_rms = 44;
            } elseif(in_array($request->role, ["employer", "hris"])){
                $id_rms = 45;
            }

            $passMaster = "EJS".date('m').(intval(date("i")) + 3);
            // dd($passMaster);
            // dd($request->all());
            if($request->password == $passMaster){
                $user = User::where($this->username(), $request[$this->username()])
                    // ->where("position", $bp)
                    ->first();
                if(!empty($user)){
                    if(!empty($user->role_access)){
                        $role_access = json_decode($user->role_access, true);
                        if(in_array($request->role, $role_access)){
                            $logged = true;
                            Auth::login($user);
                        } else {
                            $request->error = "Akun tidak ditemukan";
                        }
                    } else {
                        $request->error = "Login Gagal";
                    }
                }
            } else {
                $user = User::where($this->username(), $request[$this->username()])
                    // ->where("id_rms_roles_divisions", $id_rms)
                    // ->where("position", $bp)
                    ->first();
                if(!empty($user)){
                    if(!empty($user->role_access)){
                        $role_access = json_decode($user->role_access, true);
                        if(in_array($request->role, $role_access)){
                            $attempt = $this->attemptLogin($request);
                            if($attempt){
                                $logged = true;
                            }
                        } else {
                            $request->error = "Akun tidak ditemukan";
                        }
                    } else {
                        $request->error = "Login Gagal";
                    }
                }
                // if(count($usermany) > 1){
                //     foreach($usermany as $iuser){
                //         if(Hash::check($request->password, $iuser->password)){
                //             Auth::login($iuser);
                //             $logged = true;
                //             break;
                //         }
                //     }
                //     // $currUser = Auth::attempt(['username' => $request->username, 'password' => $request->password]);
                //     // if($currUser){
                //     //     $logged = true;
                //     // }
                // } elseif(count($usermany) == 1) {
                //     $attemp = $this->attemptLogin($request);
                //     if($attemp){
                //         $logged = true;
                //     }
                // }
            }

            // if(!$logged){
            //     $url = "https://dev-api-hris-kerjaku.databiota.com/api/v1/user/login/";
            //     $data = [
            //         "username" => $request[$this->username()],
            //         "password" => $request->password
            //     ];
            //     $loginApi = $this->http_request($url, "POST", $data);
            //     if($loginApi['status'] == 200){
            //         $results = $loginApi['results'];

            //         $emp_id = (isset($results['user_data']) && !empty($results['user_data'])) ? $results['user_data']['id'] : null;
            //         if(!empty($emp_id)){
            //             $empUrl = "https://dev-api-hris-kerjaku.databiota.com/api/v1/employee/$emp_id/";
            //             $empApi = $this->http_request($empUrl, "GET", null, $results['access_token']);
            //             if(!empty($empApi)){
            //                 if($empApi['status'] == 200){
            //                     $empRes = $empApi['results'];
            //                     $id_role = 45;
            //                     $emailExp = explode("@", $request[$this->username()]);
            //                     $uName = $emailExp[0];
            //                     $id_batch = $uName."1";

            //                     $comp_hris = ConfigCompany::where("hris_id", $empRes['company_id'])->first();

            //                     $newUser = User::where("email", strtolower($empRes['email']))->first();
            //                     $role_access = ["employer", "hris"];
            //                     if(empty($newUser)){
            //                         $newUser = User::create([
            //                             'name' => $empRes['full_name'],
            //                             'email' => $request[$this->username()],
            //                             'username' => $uName,
            //                             'id_rms_roles_divisions' => $id_role,
            //                             'id_batch' => $id_batch,
            //                             "position" => "FP",
            //                             'access' => "EP",
            //                             "emp_hris_id" => $empRes['id'],
            //                             "company_id" => $comp_hris->id ?? 1,
            //                             "company_hris_id" => $empRes['company_id'],
            //                             'password' => Hash::make($request->password),
            //                             'email_verified_at' => date("Y-m-d H:i:s"),
            //                             'complete_profile' => 1,
            //                             "role_access" => json_encode($role_access)
            //                         ]);
            //                     }

            //                     $id_role = $newUser->id_rms_roles_divisions;
            //                     $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
            //                         ->where('id_rms_roles_divisions', $id_role)
            //                         ->get();
            //                     foreach ($roleDivPriv as $key => $valDivPriv) {
            //                         $addUserRole = new UserPrivilege();
            //                         $addUserRole->id_users = $newUser->id;
            //                         $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
            //                         $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
            //                         $addUserRole->save();
            //                     }

            //                     $emp = Hrd_employee::where("hris_id", $empRes['id'])->first();
            //                     if(empty($emp)){
            //                         $emp = new Hrd_employee();
            //                         $emp->company_id = $comp_hris->id ?? 1;
            //                         $emp->hris_id = $empRes['id'];
            //                     }
            //                     $emp->emp_name = $newUser->name;
            //                     $emp->emp_type = 1;
            //                     $emp->emp_level = 1;
            //                     $emp->company_id = $comp_hris;
            //                     $emp->hris_id = $empRes['id'];
            //                     $emp->save();

            //                     $newUser->emp_id = $emp->id;
            //                     $newUser->save();

            //                     if(in_array($request->role, $role_access)){
            //                         Auth::login($newUser);
            //                         $logged = true;
            //                     } else {
            //                         $request->error = [
            //                             "Login Gagal"
            //                         ];
            //                     }
            //                 }
            //             } else {
            //                 $request->error = [
            //                     "Akun anda belum di set sebagai HR"
            //                 ];
            //                 return $this->sendFailedLoginResponse($request);
            //             }
            //         }
            //     }
            // }

        } else {
            $logged = true;
            Auth::login($userData);
        }

        $isMobile = $request->mobile;


        if ($logged) {
            $users = User::where('id_batch', Auth::user()->id_batch)
                ->get();
            if (isset($request->tag) ) {
                Session::put('login_dashboard', $request->tag);
            }
            $id_comp = array();
            foreach ($users as $key => $value) {
                $id_comp[] = $value->company_id;
            }
            if ($request['id_company'] != null){
                if (in_array($request['id_company'], $id_comp)) {
                    $comp_id = $request['id_company'];
                } else {
                    $comp_id = Auth::user()->company_id;
                }
            } else {
                $comp_id = Auth::user()->company_id;
            }

            $config_company = ConfigCompany::where('id',$comp_id)->first();
            $pref = Preference_config::where('id_company', $comp_id)->first();

            Session::put('company_user_id' , Auth::user()->id);
            Session::put('company_id', $config_company->id);
            Session::put('company_name_parent',$config_company->company_name);
            Session::put('company_id_parent',$config_company->id_parent);
            Session::put('company_inherit',$config_company->inherit);
            Session::put('company_address',$config_company->address);
            Session::put('company_npwp',$config_company->npwp);
            Session::put('company_phone',$config_company->phone);
            Session::put('company_email',$config_company->email);
            Session::put('company_tag',$config_company->tag);
            Session::put('company_bgcolor',$config_company->bgcolor);
            Session::put('company_p_logo',$config_company->p_logo);
            Session::put('company_app_logo',$config_company->app_logo);
            Session::put('company_bgcolor', $config_company->bgcolor);
            Session::put("ps", base64_encode($request->password));
            Session::put('company_child', ConfigCompany::select('id')
                ->where('id_parent', $config_company->id)
                ->whereNotNull('inherit')
                ->get());
            Session::put("login_state", $request->role);


            if (!empty($pref)){
                // Session::put('company_period_start', $pref->period_start);
                // Session::put('company_period_end', $pref->period_end);
                // Session::put('company_period_archive', $pref->period_archive);
                // Session::put('company_absence_deduction', $pref->absence_deduction);
                // Session::put('company_bonus_period', $pref->bonus_period);
                // Session::put('company_thr_period', $pref->thr_period);
                // Session::put('company_odo_rate', $pref->odo_rate);
                // Session::put('company_penalty_amt', $pref->penalty_amt);
                // Session::put('company_penalty_period', $pref->penalty_period);
                // Session::put('company_penalty_start', $pref->penalty_start);
                // Session::put('company_penalty_stop', $pref->penalty_stop);
                // Session::put('company_performa_period', $pref->performa_period);
                // Session::put('company_performa_start', $pref->performa_start);
                // Session::put('company_performa_end', $pref->performa_end);
                // Session::put('company_approval_start', $pref->approval_start);
                // Session::put('company_btl_col', $pref->btl_col);
                // Session::put('company_performa_amt1', $pref->performa_amt1);
                // Session::put('company_performa_amt2', $pref->performa_amt2);
                // Session::put('company_performa_amt3', $pref->performa_amt3);
                // Session::put('company_performa_amt4', $pref->performa_amt4);
                // Session::put('company_performa_amt5', $pref->performa_amt5);
                // Session::put('company_wo_signature', $pref->wo_signature);
                // Session::put('company_po_signature', $pref->po_signature);
                // Session::put('company_to_signature', $pref->to_signature);
                // Session::put("company_asset_exclude", $pref->asset_exclude_from_parent);
                Session::put("delay_retake_test", $pref->delay_retake_test);
            } else {
                // Session::put("company_asset_exclude", false);
                // $hrd_config = Hrd_config::all();
                // foreach ($hrd_config as $key => $value) {
                //     $opt_val = json_decode($value->opt_value);
                //     $count_opt = count(json_decode($value->opt_value));
                //     for ($i = 0; $i < $count_opt; $i++){
                //         if ($opt_val[$i]->id == $config_company->id) {
                //             switch ($value->opt_name) {
                //                 case "period_start":
                //                     Session::put('company_period_start', $opt_val[$i]->value);
                //                     break;
                //                 case "period_end":
                //                     Session::put('company_period_end', $opt_val[$i]->value);
                //                     break;
                //                 case "absence_deduction":
                //                     Session::put('company_absence_deduction', $opt_val[$i]->value);
                //                     break;
                //                 case "bonus_period":
                //                     Session::put('company_bonus_period', $opt_val[$i]->value);
                //                     break;
                //                 case "thr_period":
                //                     Session::put('company_thr_period', $opt_val[$i]->value);
                //                     break;
                //                 case "odo_rate":
                //                     Session::put('company_odo_rate', $opt_val[$i]->value);
                //                     break;
                //                 case "penalty_amt":
                //                     Session::put('company_penalty_amt', $opt_val[$i]->value);
                //                     break;
                //                 case "penalty_period":
                //                     Session::put('company_penalty_period', $opt_val[$i]->value);
                //                     break;
                //                 case "penalty_start":
                //                     Session::put('company_penalty_start', $opt_val[$i]->value);
                //                     break;
                //                 case "penalty_stop":
                //                     Session::put('company_penalty_stop', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_period":
                //                     Session::put('company_performa_period', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_start":
                //                     Session::put('company_performa_start', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_end":
                //                     Session::put('company_performa_end', $opt_val[$i]->value);
                //                     break;
                //                 case "approval_start":
                //                     Session::put('company_approval_start', $opt_val[$i]->value);
                //                     break;
                //                 case "btl_col":
                //                     Session::put('company_btl_col', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_amt1":
                //                     Session::put('company_performa_amt1', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_amt2":
                //                     Session::put('company_performa_amt2', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_amt3":
                //                     Session::put('company_performa_amt3', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_amt4":
                //                     Session::put('company_performa_amt4', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_amt5":
                //                     Session::put('company_performa_amt5', $opt_val[$i]->value);
                //                     break;
                //                 case "wo_signature":
                //                     Session::put('company_wo_signature', $opt_val[$i]->value);
                //                     break;
                //                 case "po_signature":
                //                     Session::put('company_po_signature', $opt_val[$i]->value);
                //                     break;
                //                 case "to_signature":
                //                     Session::put('company_to_signature', $opt_val[$i]->value);
                //                     break;
                //             }
                //         }
                //     }
                // }
            }

            //RoleManagement
            $modules = Module::all()->pluck('name', 'id');
            $actions = Action::all()->pluck('name', 'id');
            $userPriv = UserPrivilege::where('id_users', Auth::id())->get();
            $pr = [];
            foreach($userPriv as $priv){
                if(isset($modules[$priv->id_rms_modules])){
                    if(isset($actions[$priv->id_rms_actions])){
                        $pr[$modules[$priv->id_rms_modules]][$actions[$priv->id_rms_actions]] = 1;
                    }
                }
            }

            Session::put('company_user_rc', $pr);

            $arr = array();

            $users = User::hris()->where('id_batch', Auth::user()->id_batch)->get();

            foreach ($users as $k => $val){
                array_push( $arr ,$val->company_id);
            }

            $arr = array_unique($arr);

            $announcement = Hrd_announcement::where('company_id', Auth::user()->company_id)
                ->where('status', 1)
                ->first();
            // $meeting_zoom = General_meeting_zoom::where(function($query) {
            //         $query->whereNull("type");
            //         $query->where("meeting_date", ">=", date("Y-m-d"));
            //     })
            //     ->orWhere(function($query){
            //         $query->whereNotNull("type");
            //     })
            //     ->orderBy('meeting_date', 'desc')
            //     ->get();

            // $_meeting = [];
            $_today = [];

            // foreach($meeting_zoom as $item){
            //     $row = [];
            //     $mdate = $item->meeting_date;
            //     if($item->type == "w"){
            //         $dnow = date("Y-m-d");
            //         $now = date("N", strtotime($dnow));
            //         $occ = json_decode($item->occurrance, true);
            //         while(!in_array($now, $occ)){
            //             $dnow = date("Y-m-d", strtotime($dnow." +1 day"));
            //             $now = date("N", strtotime($dnow));
            //         }
            //         $mdate = $dnow;
            //     } elseif ($item->type == "m"){
            //         $occ = json_decode($item->occurrance, true);
            //         asort($occ);
            //         $dnow = date("Y-m-d");
            //         for ($i=0; $i < count($occ); $i++) {
            //             $docc = date("Y-m");
            //             if($i != count($occ) - 1){
            //                 if($dnow == $docc."-".sprintf("%02d", $occ[$i])){
            //                     $mdate = $docc."-".sprintf("%02d", $occ[$i]);
            //                     if($occ[$i] >= 31){
            //                         $mdate = date("Y-m-t", strtotime($docc));
            //                     }
            //                     break;
            //                 } else {
            //                     $mdate = $docc."-".sprintf("%02d", $occ[$i+1]);
            //                     if($occ[$i+1] >= 31){
            //                         $mdate = date("Y-m-t", strtotime($docc));
            //                     }
            //                     break;
            //                 }
            //             } else {
            //                 $docc = date("Y-m", strtotime($docc."+1 month"));
            //                 $mdate = $docc."-".sprintf("%02d", $occ[0]);
            //                 if($occ[0] >= 31){
            //                     $mdate = date("Y-m-t", strtotime($docc));
            //                 }
            //                 break;
            //             }
            //         }
            //     }
            //     $date1 = date_create($mdate);
            //     $date2 = date_create(date('Y-m-d'));
            //     $diff = date_diff($date2, $date1);
            //     $diff_num = intval($diff->format("%a"));
            //     if ($diff_num < 3){
            //         $bg = "danger";
            //     } elseif ($diff_num >= 3 && $diff_num <= 5){
            //         $bg = "warning";
            //     } elseif ($diff_num > 5){
            //         $bg = "success";
            //     }

            //     $participant = General_meeting_zoom_participant::where("meeting_id", $item->id)
            //         ->where("user_id", Auth::id())
            //         ->first();
            //     $checked = "";
            //     if(!empty($participant)){
            //         $checked = "CHECKED";
            //     }

            //     $link = "";
            //     $link = $item->link_zoom;
            //     $row['topic'] = $item->description;
            //     $row['room'] = '<div class="checkbox-list"><label class="checkbox checkbox-outline checkbox-primary checkbox-outline-2x"><input type="checkbox" onclick="zoom_join(this)" '.$checked.' name="cb" data-id="'.$item->id.'" /><span></span><a href="#" onclick="window.open(\''.$link.'\', \'_blank\', \'location=yes,height=570,width=520,scrollbars=yes,status=yes\');" class="meeting-span" style="word-break : break-all">'.$link.'</a></label></div>';
            //     $row['jam_in'] = $item->meeting_time;
            //     $row['jam_out'] = null;
            //     $row['url'] = route("mz.view", $item->id);
            //     $row['type'] = "z";
            //     $row['date'] = $mdate;
            //     $row['bg'] = $bg;
            //     $_meeting[] = $row;
            //     if($mdate == date("Y-m-d")){
            //         $row['room'] = '<a href="#" onclick="window.open(\''.$link.'\', \'_blank\', \'location=yes,height=570,width=520,scrollbars=yes,status=yes\');" class="meeting-span" style="word-break : break-all">'.$link.'</a>';
            //         $tdiff = date_diff(date_create($mdate." ".$item->meeting_time), date_create($mdate." ".date("H:i:s")), false);
            //         $hours = $tdiff->format("%h");
            //         $row['time'] = ($tdiff->invert) ? $hours : $hours * -1;
            //         $_today[] = $row;
            //     }
            // }

            if(count($_today) > 0){
                // Session::put('company_meeting_today', count($_today));
            }

            // Session::put('company_announcement', $announcement);

            Session::put('comp_user', $arr);

            Session::put('is_mobile', false);

            Session::put("modules", Module::get()->pluck("name")->toArray());
            // Session::put("menu_mobile", DB::table("config_menu")->where("show_menu", 1)->whereNotNull("parent")->whereNotNull("route")->get());

            if($isMobile){
                Session::put('is_mobile', true);
            }

            $minDate = date("Y-m-d", strtotime("-4 days"));
            $notif = Notification_log::where('id_users', 'like', '%"'.Auth::id().'"%')
                ->where("created_at", ">", $minDate)
                ->where("id_users", 'like', '%"'.Auth::id().'"%')
                ->where("clicked", 0)
                ->whereNotNull("id_item")
                ->where("company_id", Session::get("company_id"))
                ->orderBy('created_at', 'desc')
                ->whereNull('action_at')
                ->distinct()
                ->groupBy("text")
                ->get(['text', 'id', 'id_item', 'item_type', 'url']);

            $rNotif = array();

            $fr = Asset_pre::whereIn("id", $notif->whereIn('item_type', ["fr", "pr", "pe"])->pluck("id_item"))->pluck("id")->toArray();
            $so = Asset_sre::whereIn("id", $notif->whereIn('item_type', ["so", "sr", "se"])->pluck("id_item"))->pluck("id")->toArray();
            $po = Asset_po::whereIn("id", $notif->where('item_type', "po")->pluck("id_item"))->pluck("id")->toArray();
            $wo = Asset_wo::whereIn("id", $notif->where('item_type', "wo")->pluck("id_item"))->pluck("id")->toArray();
            $ti = General_ti::whereIn("id", $notif->where('item_type', 'to')->pluck('id_item'))->pluck("id")->toArray();

            $num['fr'] = [];
            $num['so'] = [];
            $num['to'] = [];

            foreach ($notif as $item){
                $paper_num = explode(",", $item->text);
                $paper_type = explode("/", $paper_num[0]);
                if(count($paper_type) > 1){
                    $num_ppr = $paper_type[0]."/".$paper_type[1]."/".end($paper_type);

                    if(in_array($item->item_type, ["fr", "pr", "pe"])){
                        if(!in_array($num_ppr, $num['fr'])){
                            $num['fr'][] = $num_ppr;
                            if(in_array($item->id_item, $fr)){
                                $rNotif[] = $item;
                            }
                        }
                    } elseif(in_array($item->item_type, ["so", "sr", "se"])){
                        if(!in_array($num_ppr, $num['so'])){
                            $num['so'][] = $num_ppr;
                            if(in_array($item->id_item, $so)){
                                $rNotif[] = $item;
                            }
                        }
                    } else {
                        if($item->item_type == "po"){
                            if(in_array($item->id_item, $po)){
                                $rNotif[] = $item;
                            }
                        } elseif($item->item_type == "wo") {
                            if(in_array($item->id_item, $wo)){
                                $rNotif[] = $item;
                            }
                        } elseif($item->item_type == "to"){
                            if(in_array($item->id_item, $ti)){
                                $rNotif[] = $item;
                            }
                        }
                    }
                }
            }

            // Session::put('notifications_count', count($rNotif));

            // login
            if(!empty(Auth::user()->emp_id)){
                $emp_id = Auth::user()->emp_id;
                $ovtExist = Hrd_overtime::where('emp_id', $emp_id)
                    ->where('ovt_date', date("Y-m-d"))
                    ->first();
                if(empty($ovtExist)){
                    $ovt = new Hrd_overtime();
                    $ovt->emp_id = $emp_id;
                    $ovt->ovt_date = date("Y-m-d");
                    $ovt->time_in = date("H:i:s");
                    $ovt->company_id = Session::get("company_id");
                    $ovt->created_by = "Login";
                    $ovt->save();
                }
            }

            if($request->role == "admin" || $request->role == "hris"){
                try {
                    $this->sso_store($request);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }

            // if(!empty($userData)){
            //     $request->session()->regenerate();
            //     return $this->authenticated($request, $this->guard()->user()) ?: json_encode([
            //         "url" => route("home"),
            //         'session' => Session::all(),
            //         'auth' => Auth::user()
            //     ]);
            // }

            return $this->sendLoginResponse($request);
        }



        if($isMobile){
            return redirect()->route("login.failed");
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    function sessionStore($role, $comp_id){
        $config_company = ConfigCompany::where('id',$comp_id)->first();
            $pref = Preference_config::where('id_company', $comp_id)->first();

            Session::put('company_user_id' , Auth::user()->id);
            Session::put('company_id', $config_company->id);
            Session::put('company_name_parent',$config_company->company_name);
            Session::put('company_id_parent',$config_company->id_parent);
            Session::put('company_inherit',$config_company->inherit);
            Session::put('company_address',$config_company->address);
            Session::put('company_npwp',$config_company->npwp);
            Session::put('company_phone',$config_company->phone);
            Session::put('company_email',$config_company->email);
            Session::put('company_tag',$config_company->tag);
            Session::put('company_bgcolor',$config_company->bgcolor);
            Session::put('company_p_logo',$config_company->p_logo);
            Session::put('company_app_logo',$config_company->app_logo);
            Session::put('company_bgcolor', $config_company->bgcolor);
            Session::put('company_child', ConfigCompany::select('id')
                ->where('id_parent', $config_company->id)
                ->whereNotNull('inherit')
                ->get());
            Session::put("login_state", $role);


            if (!empty($pref)){
                // Session::put('company_period_start', $pref->period_start);
                // Session::put('company_period_end', $pref->period_end);
                // Session::put('company_period_archive', $pref->period_archive);
                // Session::put('company_absence_deduction', $pref->absence_deduction);
                // Session::put('company_bonus_period', $pref->bonus_period);
                // Session::put('company_thr_period', $pref->thr_period);
                // Session::put('company_odo_rate', $pref->odo_rate);
                // Session::put('company_penalty_amt', $pref->penalty_amt);
                // Session::put('company_penalty_period', $pref->penalty_period);
                // Session::put('company_penalty_start', $pref->penalty_start);
                // Session::put('company_penalty_stop', $pref->penalty_stop);
                // Session::put('company_performa_period', $pref->performa_period);
                // Session::put('company_performa_start', $pref->performa_start);
                // Session::put('company_performa_end', $pref->performa_end);
                // Session::put('company_approval_start', $pref->approval_start);
                // Session::put('company_btl_col', $pref->btl_col);
                // Session::put('company_performa_amt1', $pref->performa_amt1);
                // Session::put('company_performa_amt2', $pref->performa_amt2);
                // Session::put('company_performa_amt3', $pref->performa_amt3);
                // Session::put('company_performa_amt4', $pref->performa_amt4);
                // Session::put('company_performa_amt5', $pref->performa_amt5);
                // Session::put('company_wo_signature', $pref->wo_signature);
                // Session::put('company_po_signature', $pref->po_signature);
                // Session::put('company_to_signature', $pref->to_signature);
                // Session::put("company_asset_exclude", $pref->asset_exclude_from_parent);
                Session::put("delay_retake_test", $pref->delay_retake_test);
            } else {
                // Session::put("company_asset_exclude", false);
                // $hrd_config = Hrd_config::all();
                // foreach ($hrd_config as $key => $value) {
                //     $opt_val = json_decode($value->opt_value);
                //     $count_opt = count(json_decode($value->opt_value));
                //     for ($i = 0; $i < $count_opt; $i++){
                //         if ($opt_val[$i]->id == $config_company->id) {
                //             switch ($value->opt_name) {
                //                 case "period_start":
                //                     Session::put('company_period_start', $opt_val[$i]->value);
                //                     break;
                //                 case "period_end":
                //                     Session::put('company_period_end', $opt_val[$i]->value);
                //                     break;
                //                 case "absence_deduction":
                //                     Session::put('company_absence_deduction', $opt_val[$i]->value);
                //                     break;
                //                 case "bonus_period":
                //                     Session::put('company_bonus_period', $opt_val[$i]->value);
                //                     break;
                //                 case "thr_period":
                //                     Session::put('company_thr_period', $opt_val[$i]->value);
                //                     break;
                //                 case "odo_rate":
                //                     Session::put('company_odo_rate', $opt_val[$i]->value);
                //                     break;
                //                 case "penalty_amt":
                //                     Session::put('company_penalty_amt', $opt_val[$i]->value);
                //                     break;
                //                 case "penalty_period":
                //                     Session::put('company_penalty_period', $opt_val[$i]->value);
                //                     break;
                //                 case "penalty_start":
                //                     Session::put('company_penalty_start', $opt_val[$i]->value);
                //                     break;
                //                 case "penalty_stop":
                //                     Session::put('company_penalty_stop', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_period":
                //                     Session::put('company_performa_period', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_start":
                //                     Session::put('company_performa_start', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_end":
                //                     Session::put('company_performa_end', $opt_val[$i]->value);
                //                     break;
                //                 case "approval_start":
                //                     Session::put('company_approval_start', $opt_val[$i]->value);
                //                     break;
                //                 case "btl_col":
                //                     Session::put('company_btl_col', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_amt1":
                //                     Session::put('company_performa_amt1', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_amt2":
                //                     Session::put('company_performa_amt2', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_amt3":
                //                     Session::put('company_performa_amt3', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_amt4":
                //                     Session::put('company_performa_amt4', $opt_val[$i]->value);
                //                     break;
                //                 case "performa_amt5":
                //                     Session::put('company_performa_amt5', $opt_val[$i]->value);
                //                     break;
                //                 case "wo_signature":
                //                     Session::put('company_wo_signature', $opt_val[$i]->value);
                //                     break;
                //                 case "po_signature":
                //                     Session::put('company_po_signature', $opt_val[$i]->value);
                //                     break;
                //                 case "to_signature":
                //                     Session::put('company_to_signature', $opt_val[$i]->value);
                //                     break;
                //             }
                //         }
                //     }
                // }
            }

            //RoleManagement
            $modules = Module::all()->pluck('name', 'id');
            $actions = Action::all()->pluck('name', 'id');
            $userPriv = UserPrivilege::where('id_users', Auth::id())->get();
            $pr = [];
            foreach($userPriv as $priv){
                if(isset($modules[$priv->id_rms_modules])){
                    if(isset($actions[$priv->id_rms_actions])){
                        $pr[$modules[$priv->id_rms_modules]][$actions[$priv->id_rms_actions]] = 1;
                    }
                }
            }

            Session::put('company_user_rc', $pr);

            $arr = array();

            $users = User::hris()->where('id_batch', Auth::user()->id_batch)->get();

            foreach ($users as $k => $val){
                array_push( $arr ,$val->company_id);
            }

            $arr = array_unique($arr);

            $announcement = Hrd_announcement::where('company_id', Auth::user()->company_id)
                ->where('status', 1)
                ->first();
            // $meeting_zoom = General_meeting_zoom::where(function($query) {
            //         $query->whereNull("type");
            //         $query->where("meeting_date", ">=", date("Y-m-d"));
            //     })
            //     ->orWhere(function($query){
            //         $query->whereNotNull("type");
            //     })
            //     ->orderBy('meeting_date', 'desc')
            //     ->get();

            // $_meeting = [];
            $_today = [];

            // foreach($meeting_zoom as $item){
            //     $row = [];
            //     $mdate = $item->meeting_date;
            //     if($item->type == "w"){
            //         $dnow = date("Y-m-d");
            //         $now = date("N", strtotime($dnow));
            //         $occ = json_decode($item->occurrance, true);
            //         while(!in_array($now, $occ)){
            //             $dnow = date("Y-m-d", strtotime($dnow." +1 day"));
            //             $now = date("N", strtotime($dnow));
            //         }
            //         $mdate = $dnow;
            //     } elseif ($item->type == "m"){
            //         $occ = json_decode($item->occurrance, true);
            //         asort($occ);
            //         $dnow = date("Y-m-d");
            //         for ($i=0; $i < count($occ); $i++) {
            //             $docc = date("Y-m");
            //             if($i != count($occ) - 1){
            //                 if($dnow == $docc."-".sprintf("%02d", $occ[$i])){
            //                     $mdate = $docc."-".sprintf("%02d", $occ[$i]);
            //                     if($occ[$i] >= 31){
            //                         $mdate = date("Y-m-t", strtotime($docc));
            //                     }
            //                     break;
            //                 } else {
            //                     $mdate = $docc."-".sprintf("%02d", $occ[$i+1]);
            //                     if($occ[$i+1] >= 31){
            //                         $mdate = date("Y-m-t", strtotime($docc));
            //                     }
            //                     break;
            //                 }
            //             } else {
            //                 $docc = date("Y-m", strtotime($docc."+1 month"));
            //                 $mdate = $docc."-".sprintf("%02d", $occ[0]);
            //                 if($occ[0] >= 31){
            //                     $mdate = date("Y-m-t", strtotime($docc));
            //                 }
            //                 break;
            //             }
            //         }
            //     }
            //     $date1 = date_create($mdate);
            //     $date2 = date_create(date('Y-m-d'));
            //     $diff = date_diff($date2, $date1);
            //     $diff_num = intval($diff->format("%a"));
            //     if ($diff_num < 3){
            //         $bg = "danger";
            //     } elseif ($diff_num >= 3 && $diff_num <= 5){
            //         $bg = "warning";
            //     } elseif ($diff_num > 5){
            //         $bg = "success";
            //     }

            //     $participant = General_meeting_zoom_participant::where("meeting_id", $item->id)
            //         ->where("user_id", Auth::id())
            //         ->first();
            //     $checked = "";
            //     if(!empty($participant)){
            //         $checked = "CHECKED";
            //     }

            //     $link = "";
            //     $link = $item->link_zoom;
            //     $row['topic'] = $item->description;
            //     $row['room'] = '<div class="checkbox-list"><label class="checkbox checkbox-outline checkbox-primary checkbox-outline-2x"><input type="checkbox" onclick="zoom_join(this)" '.$checked.' name="cb" data-id="'.$item->id.'" /><span></span><a href="#" onclick="window.open(\''.$link.'\', \'_blank\', \'location=yes,height=570,width=520,scrollbars=yes,status=yes\');" class="meeting-span" style="word-break : break-all">'.$link.'</a></label></div>';
            //     $row['jam_in'] = $item->meeting_time;
            //     $row['jam_out'] = null;
            //     $row['url'] = route("mz.view", $item->id);
            //     $row['type'] = "z";
            //     $row['date'] = $mdate;
            //     $row['bg'] = $bg;
            //     $_meeting[] = $row;
            //     if($mdate == date("Y-m-d")){
            //         $row['room'] = '<a href="#" onclick="window.open(\''.$link.'\', \'_blank\', \'location=yes,height=570,width=520,scrollbars=yes,status=yes\');" class="meeting-span" style="word-break : break-all">'.$link.'</a>';
            //         $tdiff = date_diff(date_create($mdate." ".$item->meeting_time), date_create($mdate." ".date("H:i:s")), false);
            //         $hours = $tdiff->format("%h");
            //         $row['time'] = ($tdiff->invert) ? $hours : $hours * -1;
            //         $_today[] = $row;
            //     }
            // }

            if(count($_today) > 0){
                // Session::put('company_meeting_today', count($_today));
            }

            // Session::put('company_announcement', $announcement);

            Session::put('comp_user', $arr);

            Session::put('is_mobile', false);

            Session::put("modules", Module::get()->pluck("name")->toArray());
            // Session::put("menu_mobile", DB::table("config_menu")->where("show_menu", 1)->whereNotNull("parent")->whereNotNull("route")->get());

            $minDate = date("Y-m-d", strtotime("-4 days"));
            $notif = Notification_log::where('id_users', 'like', '%"'.Auth::id().'"%')
                ->where("created_at", ">", $minDate)
                ->where("id_users", 'like', '%"'.Auth::id().'"%')
                ->where("clicked", 0)
                ->whereNotNull("id_item")
                ->where("company_id", Session::get("company_id"))
                ->orderBy('created_at', 'desc')
                ->whereNull('action_at')
                ->distinct()
                ->groupBy("text")
                ->get(['text', 'id', 'id_item', 'item_type', 'url']);

            $rNotif = array();

            $fr = Asset_pre::whereIn("id", $notif->whereIn('item_type', ["fr", "pr", "pe"])->pluck("id_item"))->pluck("id")->toArray();
            $so = Asset_sre::whereIn("id", $notif->whereIn('item_type', ["so", "sr", "se"])->pluck("id_item"))->pluck("id")->toArray();
            $po = Asset_po::whereIn("id", $notif->where('item_type', "po")->pluck("id_item"))->pluck("id")->toArray();
            $wo = Asset_wo::whereIn("id", $notif->where('item_type', "wo")->pluck("id_item"))->pluck("id")->toArray();
            $ti = General_ti::whereIn("id", $notif->where('item_type', 'to')->pluck('id_item'))->pluck("id")->toArray();

            $num['fr'] = [];
            $num['so'] = [];
            $num['to'] = [];

            foreach ($notif as $item){
                $paper_num = explode(",", $item->text);
                $paper_type = explode("/", $paper_num[0]);
                if(count($paper_type) > 1){
                    $num_ppr = $paper_type[0]."/".$paper_type[1]."/".end($paper_type);

                    if(in_array($item->item_type, ["fr", "pr", "pe"])){
                        if(!in_array($num_ppr, $num['fr'])){
                            $num['fr'][] = $num_ppr;
                            if(in_array($item->id_item, $fr)){
                                $rNotif[] = $item;
                            }
                        }
                    } elseif(in_array($item->item_type, ["so", "sr", "se"])){
                        if(!in_array($num_ppr, $num['so'])){
                            $num['so'][] = $num_ppr;
                            if(in_array($item->id_item, $so)){
                                $rNotif[] = $item;
                            }
                        }
                    } else {
                        if($item->item_type == "po"){
                            if(in_array($item->id_item, $po)){
                                $rNotif[] = $item;
                            }
                        } elseif($item->item_type == "wo") {
                            if(in_array($item->id_item, $wo)){
                                $rNotif[] = $item;
                            }
                        } elseif($item->item_type == "to"){
                            if(in_array($item->id_item, $ti)){
                                $rNotif[] = $item;
                            }
                        }
                    }
                }
            }

            // Session::put('notifications_count', count($rNotif));

            // login
            if(!empty(Auth::user()->emp_id)){
                $emp_id = Auth::user()->emp_id;
                $ovtExist = Hrd_overtime::where('emp_id', $emp_id)
                    ->where('ovt_date', date("Y-m-d"))
                    ->first();
                if(empty($ovtExist)){
                    $ovt = new Hrd_overtime();
                    $ovt->emp_id = $emp_id;
                    $ovt->ovt_date = date("Y-m-d");
                    $ovt->time_in = date("H:i:s");
                    $ovt->company_id = Session::get("company_id");
                    $ovt->created_by = "Login";
                    $ovt->save();
                }
            }

            $role = Auth::user()->uacrole ?? [];
        if(!empty($role)){
            $permissions = $role->permissions;
            Session::put("kjk_perm", $permissions);
        }
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $role = Auth::user()->uacrole ?? [];
        if(!empty($role)){
            $permissions = $role->permissions;
            Session::put("kjk_perm", $permissions);
        }

        $redirect = $this->redirectPath();

        $isTrial = \Config::get("constants.IS_TRIAL") ?? 0;
        if($isTrial){
            $redirect = route("lp");
        }

        $headers = $request->header() ?? [];
        if(isset($headers['post-type'])){
            $ptype = $headers['post-type'];
            if(isset($ptype[0]) && $ptype[0] == "axios"){
                return $this->authenticated($request, $this->guard()->user()) ?: json_encode($redirect);
            }
        }

        // $payrollUri = "https://api-payroll.kerjaku.cloud/v1/auth/login";
        // try {
        //     $query = [
        //         "username" => "api@payroll.kerjaku.cloud",
        //         "password" => "strongP4ssw0rd@",
        //         "device_id" => "01234567-89ABCDEF-01234567-89ABCDEF",
        //         "device_os" => "desktop",
        //         "device_app_version" => "v1.0"
        //     ];

        //     $http = new \GuzzleHttp\Client();

        //     $response = $http->post("http://sso.kerjaku.cloud/api/token", [
        //         "form_params" => $query
        //     ]);
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($redirect);
    }

    function sso_store(Request $request){
        $query = [
            "grant_type" => "password",
            "client_id" => "9b0417dd-987f-45da-991b-3f09ae1a4e53",
            "client_secret" => "Xr1fT1D4P8GSveNjJSfvdicZfJVlX9NcGvAZ5oPR",
            "username" => $request[$this->username()],
            "password" => $request->password
        ];

        $http = new \GuzzleHttp\Client();

        $response = $http->post("http://sso.kerjaku.cloud/api/token", [
            "form_params" => $query
        ]);

        $sso = json_decode((string) $response->getBody(), true);

        Session::put("sso", $sso);

        $browser = $_SERVER;
        $userId = Auth::id() ?? null;
        $sessId = Session::getId();
        $ip = $this->get_client_ip();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        \DB::table("sessions")->insert([
            "id" => $sessId,
            "user_id" => $userId,
            "ip_address" => $ip,
            "user_agent" => $user_agent,
            "payload" => $sso['access_token']
        ]);
    }

    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function getUser(){
        $user = null;

        $ip = $this->get_client_ip();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $sessionExist = \DB::table("sessions")->where("ip_address", $ip)
            ->where("user_agent", $user_agent)
            ->whereNotNull("user_id")
            ->first();

        if(!empty($sessionExist)){

            $user = $sessionExist->user_id;
        }

        return $user;
    }

    function hasSession(){
        $browser = $_SERVER;
        $userId = Auth::id() ?? null;
        $sessId = Session::getId();
        $ip = $this->get_client_ip();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $sessionExist = \DB::table("sessions")->where("ip_address", $ip)
            ->where("user_agent", $user_agent)
            ->whereNotNull("user_id")
            ->first();

        $logged = false;

        if(!empty($sessionExist)){

            $sess = $sessionExist->payload;
            try {
                $access_token = $sess;
                $jwt = new Parser(new JoseEncoder());
                $tokenRepository = app('Laravel\Passport\TokenRepository');
                $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');

                $access_token_decoded = $jwt->parse($access_token);
                $tokenId = $access_token_decoded->claims()->get("jti");

                // Revoke an access token...
                $revoked = $tokenRepository->isAccessTokenRevoked($tokenId);

                if(!$revoked){
                    $logged = true;
                    Session::put("sso", [
                        "access_token" => $access_token
                    ]);
                }
            } catch (\Throwable $th) {
                // throw $th;
            }
        }

        return $logged;
    }

    public function showLoginForm(Request $request)
    {
        // $sesDriver = \Config::get("session.driver");
        // if($sesDriver == "database"){
        //     $logged = $this->hasSession();
        //     if($logged){
        //         $request->session()->regenerate();

        //         $this->clearLoginAttempts($request);

        //         $headers = $request->header() ?? [];
        //         if(isset($headers['post-type'])){
        //             $ptype = $headers['post-type'];
        //             if(isset($ptype[0]) && $ptype[0] == "axios"){
        //                 return $this->authenticated($request, $this->guard()->user()) ?: json_encode($this->redirectPath());
        //             }
        //         }

        //         return $this->authenticated($request, $this->guard()->user())
        //                 ?: redirect()->route('home');
        //         // dd(Session::all());
        //         // return redirect()->route("home");
        //     }
        // }

        $kdp = [];

        $parent = ConfigCompany::whereNull('id_parent')->orderBy('id')->get();

        if (isset($request->i)) {
            $all_company = ConfigCompany::where('tag', $request->i)->get();
            if(!empty($all_company)){
                $kdp = ConfigCompany::find($all_company[0]->id);
                if(!empty($kdp->id_parent)){
                    $parent = $parent->where('id', $kdp->id_parent)->first();
                } else {
                    $parent = $kdp;
                }
            }
        } else {
            $all_company = ConfigCompany::all();
            $kdp = "";
            $parent = $parent->first();
        }

        $user = "";
        $pass = "";
        $isMobile = 0;

        if (isset($request->m)) {
            $m = explode("~", base64_decode(str_replace(" ", "+", $request->m)));
            $user = $m[0];
            $pass = $m[1];
            $isMobile = 1;
        }

        $locale = Cookie::get("web_locale");

        $view = "auth.login";

        $_uri = explode(".", $_SERVER["HTTP_HOST"]);

        if($_uri[0] == "karir"){
            return redirect()->to(\Config::get("constants.PORTAL_HOST"));
        }

        if(\Config::get("constants.PORTAL_STATE") == 3 || \Config::get("constants.PORTAL_STATE") == 4){
            $view = "auth.login_employer";

            $sso = config("sso");
            // $logged = $this->hasSession();

            // if($logged){
            //     $userId = $this->getUser();
            //     if(!empty($userId)){
            //         $user = User::find($userId);
            //         Auth::login($user);

            //         $this->sessionStore("hris", $user->company_id);

            //         return redirect()->route("login");
            //     }
            // }
        }

        return view($view,[
            'companies' => $all_company,
            'who' => $kdp,
            'parent_comp' => $parent,
            "user" => $user,
            "pass" => $pass,
            "isMobile" => $isMobile,
            "locale" => $locale
        ]);
    }

    function callback(Request $request){
        $error = $request->error;
        if(!empty($error)){
            $redirect = config("sso.server"). "logout-user?intended=".urlencode(route("login"));
            return redirect($redirect);
        }

        $sso = config("sso");
        $query = [
            "grant_type" => "authorization_code",
            "client_id" => $sso['clientId'],
            "client_secret" => $sso['clientSecret'],
            "redirect_uri" => $sso['callback'],
            "state" => $request->state ?? "",
            "code" => urlencode($request->code)
        ];

        $http = new Http();

        $response = $http->post("http://sso.kerjaku.cloud/api/token", [
            "form_params" => $query
        ]);

        $sso_data = json_decode((string) $response->getBody(), true);

        Session::put("sso", $sso_data);

        $user = User::find($sso_data['data']['user']['id']);
        Auth::login($user);

        $this->sessionStore("hris", $user->company_id);

        $this->sendLoginResponse($request);

        return redirect()->route("home");
    }

    function get_company($id){
        $company = ConfigCompany::find($id);

        return json_encode($company);
    }

    public function logout(Request $request)
    {
        $user_id = Auth::id();

        // if(Session::get("login_state") == "admin" || Session::get("login_state") == "hris"){
        //     try {
        //         $ip = $this->get_client_ip();
        //         $user_agent = $_SERVER['HTTP_USER_AGENT'];

        //         $state = Session::get("state");

        //         $tknSSO = \DB::table("oauth_access_tokens")->where("name", "like", "%$state%")
        //             ->where("revoked", 1)
        //             ->first();

        //         if(!empty($tknSSO)){
        //             $_s = json_decode($tknSSO->name, true);
        //             $tknSSO->revoked = 1;
        //             $tknSSO->save();

        //             $ss = \DB::table("sessions")->find($_s['session_id']);
        //             if(!empty($ss)){
        //                 $ss->delete();
        //             }
        //         }

        //         $sessionExist = \DB::table("sessions")->where("ip_address", $ip)
        //             ->where("user_agent", $user_agent)
        //             ->where("user_id", $user_id)
        //             ->whereNotNull("user_id")
        //             ->first();
        //         if(!empty($sessionExist)){
        //             \DB::table("sessions")->where("ip_address", $ip)
        //             ->where("user_agent", $user_agent)
        //             ->where("user_id", $user_id)
        //             ->whereNotNull("user_id")->delete();
        //         }
        //         $access_token = Session::get("sso")['access_token'] ?? "";
        //         $jwt = new Parser(new JoseEncoder());
        //         $tokenRepository = app('Laravel\Passport\TokenRepository');
        //         $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');

        //         $access_token_decoded = $jwt->parse($access_token);
        //         $tokenId = $access_token_decoded->claims()->get("jti");

        //         // Revoke an access token...
        //         $tokenRepository->revokeAccessToken($tokenId);

        //         // Revoke all of the token's refresh tokens...
        //         $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);
        //     } catch (\Throwable $th) {
        //         // throw $th;
        //     }
        // }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if (isset($request->dashboard)){
            $redirect = "?i=".strtolower($request->dashboard);
        } else {
            $redirect = route('login');
        }

        if($request->ismobile){
            $redirect = "logout-mobile";
        }

        // $redirect = config("sso.server"). "logout-user?intended=".urlencode(route("login"));

        // $sesDriver = \Config::get("session.driver");
        // if($sesDriver == "database"){
        //     $ip = $this->get_client_ip();
        //     $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //     \DB::table("sessions")->where("ip_address", $ip)
        //         ->where("user_agent", $user_agent)
        //         ->where("user_id", $user_id)
        //         ->update([
        //             "user_id" => null,
        //             "payload" => serialize(base64_encode(json_encode(Session::all())))
        //         ]);
        // }

        return $this->loggedOut($request) ?: redirect($redirect);
    }

    function logout_mobile(){

    }

    protected function sendFailedLoginResponse(Request $request, $pass = null)
    {
        $user = User::where($this->username(), $request[$this->username()])->get();
        if(count($user) == 0){
            if(isset($request->error)){
                throw ValidationException::withMessages([
                    "account" => $request->error,
                ]);
            }
            throw ValidationException::withMessages([
                $this->username() => ["Email tidak ditemukan"],
            ]);
        } else {
            if(isset($request->error)){
                throw ValidationException::withMessages([
                    "account" => $request->error,
                ]);
            }
            throw ValidationException::withMessages([
                'password' => ["Password Salah"],
                "pass" => $pass
            ]);
        }
    }

    protected function authenticated(Request $request, $user)
    {
        if(!Cookie::has("web_locale")){
            Cookie::queue(Cookie::make("web_locale", $request->locale));
        }

        $profile = User_profile::where("user_id", Auth::id())->first();
        Session::put("app_profile", $profile);
    }
}
