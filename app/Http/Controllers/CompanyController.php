<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Role;
use App\Models\User;
use App\Models\Action;
use App\Models\Module;
use App\Models\Division;
use phpseclib\Crypt\Hash;
use App\Models\Hrd_config;
use App\Models\Finance_coa;
use App\Models\Hrd_employee;
use App\Models\RoleDivision;
use Illuminate\Http\Request;
use League\Flysystem\Config;
use App\Models\ConfigCompany;
use App\Models\RolePrivilege;
use App\Models\UserPrivilege;
use App\Helpers\FileManagement;
use App\Models\Hrd_announcement;
use App\Models\Hrd_employee_type;
use App\Models\Preference_config;
use App\Models\Asset_new_category;
use App\Models\General_meeting_zoom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config as Cfg;
use Illuminate\Support\Facades\Redirect;
use App\Models\General_meeting_zoom_participant;
use App\Models\Notification_log;
use App\Models\Asset_pre;
use App\Models\Asset_sre;
use App\Models\Asset_po;
use App\Models\Asset_wo;
use App\Models\Master_company;
use App\Models\Kjk_custom_role;
use App\Models\Kjk_uac_role;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash as FacadesHash;

class CompanyController extends Controller
{

    private $dir, $uploadDir;

    public function __construct() {
        $this->dir = "_crm.preferences.company";
        $_dir = str_replace("/", "\\", public_path("images"));
        $dir = str_replace("prototype\public_html", Cfg::get("constants.ASSET_DIR"), $_dir);
        $this->uploadDir = str_replace("\\", "/", $dir);
    }

    private function setEnv($key, $value)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . \Config::get("constants.$key"),
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }


    function app_edit(Request $request){
        $this->setEnv("APP_LABEL", $request->app_name);

        $avatar = $request->file("avatar");
        if(!empty($avatar)){
            $_dir = str_replace("/", "\\", public_path("theme/assets/media/logos"));
            $dir = str_replace("prototype\public_html", "public_html\kerjaku\assets", $_dir);
            $dir = str_replace("\\", "/", $dir);
            if($avatar->move($dir, $avatar->getClientOriginalName())){
                $this->setEnv("APP_ICON", "theme/assets/media/logos/".$avatar->getClientOriginalName());
            }
        }

        return redirect()->back();
    }

    function index(){
        $data = ConfigCompany::all();
        $level = array();
        foreach ($data as $value){
            $hasParent[$value->id] = $value->id_parent;
            if ($value->id_parent == null){
                $level[$value->id]= 1;
            } elseif ($value->id_parent != null){
                if ($hasParent[$value->id_parent] ?? [] == null){
                    $level[$value->id]= 2;
                } else {
                    $level[$value->id]= 3;
                }
            }
        }

        return view('company.index', [
            'company' => $data,
            'level' => $level
        ]);
    }

    function generate_code(Request $request) {
        $input = $request->input;
        $code = substr(md5($input), 0, 6);
        return $code;
    }

    function add(Request $request){

        $company = new ConfigCompany();

        $plogo = $request->file('p_logo');
        $applogo = $request->file('app_logo');

        $_dir = str_replace("\\", "/", public_path("images"));
        $tujuan_upload = str_replace("prototype/public_html", "public_html/assets", $_dir);

        if (!empty($plogo)) {
             $p_logo   = "p_logo_".$request->company_tag.".".$plogo->getClientOriginalExtension();
             $plogo->move($this->uploadDir,$p_logo);

            $company->app_logo = $p_logo;
        } else {
            $p_logo  = "";
        }

        if (!empty($applogo)) {
            $app_logo = "app_logo_".$request->company_tag.".".$applogo->getClientOriginalExtension();
            $applogo->move($this->uploadDir,$app_logo);

            $company->app_logo = $app_logo;
        } else {
            $app_logo = "";
        }

        $cname = str_replace("pt", "", $request->company_name);
        $cname = str_replace(".", "", $cname);

        $company->id_parent = $request->parent;
        $company->company_name = $request->company_name;
        $company->tag = substr(str_shuffle(str_replace(" ", "", $cname)), 1, 3);
        $company->npwp = $request->npwp;
        $company->address = $request->address;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->created_at = date('Y-m-d H:i:s');
        $company->created_by = "admin";
        $company->bgcolor = $request->bgcolor;
        $company->p_logo = $p_logo;
        $company->app_logo = $app_logo;

        $company->save();

        $mComp = new Master_company();
        $mComp->company_name = $company->company_name;
        $mComp->email = $company->email;
        $mComp->phone = $company->phone;
        $mComp->address = $company->address;
        $mComp->company_id = $company->id;
        $mComp->package_id = 1;
        $mComp->save();

        $id_company = $company->id;

        $category = Asset_new_category::where('standard', 1)->get();
        foreach ($category as $key => $value){
            $_idcomp = json_decode($value->company_id);
            array_push($_idcomp,"".$id_company."");

            Asset_new_category::where('id',$value->id)
                ->update([
                    'company_id' => json_encode($_idcomp)
                ]);
        }

        $hrd_conf = Hrd_config::all();
        foreach ($hrd_conf as $key => $value){
            $json_data = json_decode($value->opt_value);
            switch ($value->opt_name) {
                case "period_start":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_period_start');
                    array_push($json_data, $data);
                    break;
                case "period_end":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_period_end');
                    array_push($json_data, $data);
                    break;
                case "absence_deduction":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_absence_deduction');
                    array_push($json_data, $data);
                    break;
                case "bonus_period":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_bonus_period');
                    array_push($json_data, $data);
                    break;
                case "thr_period":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_thr_period');
                    array_push($json_data, $data);
                    break;
                case "odo_rate":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_odo_rate');
                    array_push($json_data, $data);
                    break;
                case "penalty_amt":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_penalty_amt');
                    array_push($json_data, $data);
                    break;
                case "penalty_period":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_penalty_period');
                    array_push($json_data, $data);
                    break;
                case "penalty_start":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_penalty_start');
                    array_push($json_data, $data);
                    break;
                case "penalty_stop":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_penalty_stop');
                    array_push($json_data, $data);
                    break;
                case "performa_period":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_performa_period');
                    array_push($json_data, $data);
                    break;
                case "performa_start":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_performa_start');
                    array_push($json_data, $data);
                    break;
                case "performa_end":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_performa_end');
                    array_push($json_data, $data);
                    break;
                case "approval_start":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_approval_start');
                    array_push($json_data, $data);
                    break;
                case "btl_col":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_btl_col');
                    array_push($json_data, $data);
                    break;
                case "performa_amt1":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_performa_amt1');
                    array_push($json_data, $data);
                    break;
                case "performa_amt2":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_performa_amt2');
                    array_push($json_data, $data);
                    break;
                case "performa_amt3":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_performa_amt3');
                    array_push($json_data, $data);
                    break;
                case "performa_amt4":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_performa_amt4');
                    array_push($json_data, $data);
                    break;
                case "performa_amt5":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_performa_amt5');
                    array_push($json_data, $data);
                    break;
                case "wo_signature":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_wo_signature');
                    array_push($json_data, $data);
                    break;
                case "po_signature":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_po_signature');
                    array_push($json_data, $data);
                    break;
                case "to_signature":
                    $data['id'] = $company->id;
                    $data['value'] = Session::get('company_to_signature');
                    array_push($json_data, $data);
                    break;
            }
            Hrd_config::where('opt_name', $value->opt_name)
                ->update([
                    'opt_value' => json_encode($json_data)
                ]);
        }

        $perm = \Config::get("constants.uac_permissions");
        $actions = \Config::get("constants.uac_actions");

        $role = new Kjk_uac_role();
        $role->name = "Administrator";
        $role->departements = ["_all"];
        $role->locations = ["_all"];
        $role->permissions = ["_all"];

        $permissions = [];
        foreach($perm as $key => $item){
            $col = [];
            $col['type'] = "default";
            $col['enable'] = true;
            $col['permissions'] = [];
            foreach($item as $val){
                $col['permissions'][$val] = $actions;
            }
            $permissions[$key] = $col;
        }

        $role->permissions = $permissions;
        $role->company_id = $company->id;
        $role->save();

        return redirect()->route('company.index');
    }

    public function edit(Request $request){
        $_dir = str_replace("\\", "/", public_path("images"));
        $tujuan_upload = str_replace("prototype/public_html", "public_html/assets", $_dir);

        $company = ConfigCompany::find($request['id']);

        $mComp = Master_company::where("company_id", $company->id)->first();

        if ($request->hasFile('p_logo')){
            $plogo = $request->file('p_logo');
            $p_logo   = "p_logo_".$request->company_tag.".".$plogo->getClientOriginalExtension();
            // upload file
            $plogo->move($this->uploadDir,$p_logo);

            $company->app_logo = $p_logo;
        }
        if ($request->hasFile('app_logo')){
            $applogo = $request->file('app_logo');
            $app_logo = "app_logo_".$request->company_tag.".".$applogo->getClientOriginalExtension();
            // upload file
            $applogo->move($this->uploadDir,$app_logo);
            $company->app_logo = $app_logo;
            if(!empty($mComp)){
                $mComp->icon = "images/$app_logo";
                $mComp->save();
            }
        }

        if ($request->hasFile('p_logo_white')){
            $plogowhite = $request->file('p_logo_white');
            $p_logo_white = "p_logo_white_".$request->company_tag.".".$plogowhite->getClientOriginalExtension();
            // upload file
            $plogowhite->move($this->uploadDir,$p_logo_white);
            $company->p_logo_white = $p_logo_white;
        }

        if ($request->hasFile('p_background')){
            $pbg = $request->file('p_background');
            $p_bg = "p_bg_".$request->company_tag.".".$pbg->getClientOriginalExtension();
            // upload file
            $pbg->move($this->uploadDir,$p_bg);
            $company->p_bg = $p_bg;
        }

        $company->id_parent = $request->parent;
        $company->company_name = $request->company_name;
        $company->tag = $request->company_tag;
        // $company->tag = substr(str_shuffle(str_replace(" ", "", $request->company_name)), 1, 3);
        $company->npwp = $request->npwp;
        $company->address = $request->address;
        $company->city = $request->city;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->p_title = $request->p_title;
        $company->p_subtitle = $request->p_sub_title;
        $company->p_bg_width = $request->p_bg_width;
        $company->updated_at = date('Y-m-d H:i:s');
        $company->updated_by = Auth::user()->username;
        $company->bgcolor =$request->bgcolor;
        if (isset($request->inherit)){
            $company->inherit = $request->inherit;
            Session::put('company_child', ConfigCompany::select('id')
                ->where('id_parent', Session::get('company_id'))
                ->whereNotNull('inherit')
                ->get());
        } else {
            $company->inherit = null;
            Session::put('company_child', ConfigCompany::select('id')
                ->where('id_parent', Session::get('company_id'))
                ->whereNotNull('inherit')
                ->get());
        }
        $company->save();

        return redirect()->route('company.detail',['id' => base64_encode($request['id'])]);
    }

    function switch(Request $request){
        $config_company = ConfigCompany::where('id',$request->id)->first();
        if(Auth::id() == 1){
            $_id_user = 1;
            Session::put('company_user_id', 1);
        } else {
            $user = User::where('username' , Auth::user()->username)
                ->where("id_batch", Auth::user()->id_batch)
                ->where('company_id', $config_company->id)
                ->first();
            if(!empty($user)){
                $_id_user = $user->id;
                Session::put('company_user_id', $user->id);

                $role = $user->uacrole ?? [];
                if(!empty($role)){
                    $permissions = $role->permissions;
                    Session::put("kjk_perm", $permissions);
                }
            }
        }

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

        $announcement = Hrd_announcement::where('company_id', $config_company->id)
                ->where('status', 1)
                ->first();

        Session::put('company_announcement', $announcement);

        Session::put('company_child', ConfigCompany::select('id')
            ->where('id_parent', $config_company->id)
            ->whereNotNull('inherit')
            ->get());

        $pref = Preference_config::where('id_company', $config_company->id)->first();
        // if (!empty($pref)){
        //     Session::put('company_period_start', $pref->period_start);
        //     Session::put('company_period_end', $pref->period_end);
        //     Session::put('company_absence_deduction', $pref->absence_deduction);
        //     Session::put('company_bonus_period', $pref->bonus_period);
        //     Session::put('company_thr_period', $pref->thr_period);
        //     Session::put('company_odo_rate', $pref->odo_rate);
        //     Session::put('company_penalty_amt', $pref->penalty_amt);
        //     Session::put('company_penalty_period', $pref->penalty_period);
        //     Session::put('company_penalty_start', $pref->penalty_start);
        //     Session::put('company_penalty_stop', $pref->penalty_stop);
        //     Session::put('company_performa_period', $pref->performa_period);
        //     Session::put('company_performa_start', $pref->performa_start);
        //     Session::put('company_performa_end', $pref->performa_end);
        //     Session::put('company_approval_start', $pref->approval_start);
        //     Session::put('company_btl_col', $pref->btl_col);
        //     Session::put('company_performa_amt1', $pref->performa_amt1);
        //     Session::put('company_performa_amt2', $pref->performa_amt2);
        //     Session::put('company_performa_amt3', $pref->performa_amt3);
        //     Session::put('company_performa_amt4', $pref->performa_amt4);
        //     Session::put('company_performa_amt5', $pref->performa_amt5);
        //     Session::put('company_wo_signature', $pref->wo_signature);
        //     Session::put('company_po_signature', $pref->po_signature);
        //     Session::put('company_to_signature', $pref->to_signature);
        //     Session::put("company_asset_exclude", $pref->asset_exclude_from_parent);
        // } else {
        //     Session::put("company_asset_exclude", false);
        //     $hrd_config = Hrd_config::all();
        //     foreach ($hrd_config as $key => $value) {
        //         $opt_val = json_decode($value->opt_value);
        //         $count_opt = count(json_decode($value->opt_value));
        //         for ($i = 0; $i < $count_opt; $i++) {
        //             if ($opt_val[$i]->id == $config_company->id) {
        //                 switch ($value->opt_name) {
        //                     case "period_start":
        //                         Session::put('company_period_start', $opt_val[$i]->value);
        //                         break;
        //                     case "period_end":
        //                         Session::put('company_period_end', $opt_val[$i]->value);
        //                         break;
        //                     case "absence_deduction":
        //                         Session::put('company_absence_deduction', $opt_val[$i]->value);
        //                         break;
        //                     case "bonus_period":
        //                         Session::put('company_bonus_period', $opt_val[$i]->value);
        //                         break;
        //                     case "thr_period":
        //                         Session::put('company_thr_period', $opt_val[$i]->value);
        //                         break;
        //                     case "odo_rate":
        //                         Session::put('company_odo_rate', $opt_val[$i]->value);
        //                         break;
        //                     case "penalty_amt":
        //                         Session::put('company_penalty_amt', $opt_val[$i]->value);
        //                         break;
        //                     case "penalty_period":
        //                         Session::put('company_penalty_period', $opt_val[$i]->value);
        //                         break;
        //                     case "penalty_start":
        //                         Session::put('company_penalty_start', $opt_val[$i]->value);
        //                         break;
        //                     case "penalty_stop":
        //                         Session::put('company_penalty_stop', $opt_val[$i]->value);
        //                         break;
        //                     case "performa_period":
        //                         Session::put('company_performa_period', $opt_val[$i]->value);
        //                         break;
        //                     case "performa_start":
        //                         Session::put('company_performa_start', $opt_val[$i]->value);
        //                         break;
        //                     case "performa_end":
        //                         Session::put('company_performa_end', $opt_val[$i]->value);
        //                         break;
        //                     case "approval_start":
        //                         Session::put('company_approval_start', $opt_val[$i]->value);
        //                         break;
        //                     case "btl_col":
        //                         Session::put('company_btl_col', $opt_val[$i]->value);
        //                         break;
        //                     case "performa_amt1":
        //                         Session::put('company_performa_amt1', $opt_val[$i]->value);
        //                         break;
        //                     case "performa_amt2":
        //                         Session::put('company_performa_amt2', $opt_val[$i]->value);
        //                         break;
        //                     case "performa_amt3":
        //                         Session::put('company_performa_amt3', $opt_val[$i]->value);
        //                         break;
        //                     case "performa_amt4":
        //                         Session::put('company_performa_amt4', $opt_val[$i]->value);
        //                         break;
        //                     case "performa_amt5":
        //                         Session::put('company_performa_amt5', $opt_val[$i]->value);
        //                         break;
        //                     case "wo_signature":
        //                         Session::put('company_wo_signature', $opt_val[$i]->value);
        //                         break;
        //                     case "po_signature":
        //                         Session::put('company_po_signature', $opt_val[$i]->value);
        //                         break;
        //                     case "to_signature":
        //                         Session::put('company_to_signature', $opt_val[$i]->value);
        //                         break;
        //                 }
        //             }
        //         }
        //     }
        // }

        // $meeting_zoom = General_meeting_zoom::where(function($query) {
        //         $query->whereNull("type");
        //         $query->where("meeting_date", date("Y-m-d"));
        //     })
        //     ->orWhere(function($query){
        //         $query->whereNotNull("type");
        //     })
        //     ->orderBy('meeting_date', 'desc')
        //     ->get();

        // $_meeting = [];
        // $_today = [];

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
        //         $occ = empty($item->occurrance) ? [] : json_decode($item->occurrance, true);
        //         asort($occ);
        //         $dnow = date("Y-m-d");
        //         if(count($occ) > 0){
        //             for ($i=0; $i < count($occ); $i++) {
        //                 $docc = date("Y-m");
        //                 if($i != count($occ) - 1){
        //                     if($dnow == $docc."-".sprintf("%02d", $occ[$i])){
        //                         $mdate = $docc."-".sprintf("%02d", $occ[$i]);
        //                         if($occ[$i] >= 31){
        //                             $mdate = date("Y-m-t", strtotime($docc));
        //                         }
        //                         break;
        //                     } else {
        //                         $mdate = $docc."-".sprintf("%02d", $occ[$i+1]);
        //                         if($occ[$i+1] >= 31){
        //                             $mdate = date("Y-m-t", strtotime($docc));
        //                         }
        //                         break;
        //                     }
        //                 } else {
        //                     $docc = date("Y-m", strtotime($docc."+1 month"));
        //                     $mdate = $docc."-".sprintf("%02d", $occ[0]);
        //                     if($occ[0] >= 31){
        //                         $mdate = date("Y-m-t", strtotime($docc));
        //                     }
        //                     break;
        //                 }
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
        //     if(!empty($participant)){

        //     }
        // }

        // if(count($_today) > 0){
        //     Session::put('company_meeting_today', count($_today));
        // }

        //RoleManagement
        $modules = Module::all()->pluck('name', 'id');
        $actions = Action::all()->pluck('name', 'id');
        $userPriv = UserPrivilege::where('id_users', $_id_user)->get();
        $pr = [];
        foreach($userPriv as $priv){
            if(isset($modules[$priv->id_rms_modules])){
                if(isset($actions[$priv->id_rms_actions])){
                    $pr[$modules[$priv->id_rms_modules]][$actions[$priv->id_rms_actions]] = 1;
                }
            }
        }
        Session::put('company_user_rc', $pr);

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

        $fr = Asset_pre::whereIn("id", $notif->whereIn('item_type', ["fr", "pr", "pe"])->pluck("id_item"))->get()->pluck("id")->toArray();
        $so = Asset_sre::whereIn("id", $notif->whereIn('item_type', ["so", "sr", "se"])->pluck("id_item"))->get()->pluck("id")->toArray();
        $po = Asset_po::whereIn("id", $notif->where('item_type', "po")->pluck("id_item"))->get()->pluck("id")->toArray();
        $wo = Asset_wo::whereIn("id", $notif->where('item_type', "wo")->pluck("id_item"))->get()->pluck("id")->toArray();

        $num['fr'] = [];
        $num['so'] = [];

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
                    } else {
                        if(in_array($item->id_item, $wo)){
                            $rNotif[] = $item;
                        }
                    }
                }
            }
        }

        Session::put('notifications_count', count($rNotif));

        $arr = array();

        $users = User::where('username', Auth::user()->username)
            ->where('id_batch', Auth::user()->id_batch)->get();

        foreach ($users as $k => $val){
            array_push( $arr ,$val->company_id);
        }

        $arr = array_unique($arr);

        Session::put('comp_user', $arr);

        Session::put('is_mobile', false);

        return redirect()->route('home');
    }
    function detail($id){
        $id = base64_decode($id);
        $company = ConfigCompany::where('id', $id)->first();
        $companies = ConfigCompany::all();

        return view('company.detail', compact('company', 'companies'));
    }

    function has_child($pos, $parent){
        $childs = [];
        foreach($pos->where("parent_id", $parent) as $ch){
            $child = $this->has_child($pos, $ch->id);
            $ch->children = [];
            $ch->name = str_replace(" ", "<br>", $ch->name);
            $ch->class = "bg-info";
            if(count($child) > 0){
                $ch->children = $child;
            }
            $childs[] = $ch->toArray();
        }

        return $childs;
    }

    function st($id, Request $request){
        if($request->source){
            $pos = RoleDivision::select('id', 'id_rms_roles_divisions_parent as parent_id', 'name')
                ->where('rms_roles_divisions.id_company', $id)
                ->get();
            $data = [];
            foreach($pos->whereNull('parent_id') as $item){
                $child = $this->has_child($pos, $item->id);
                $item->children = [];
                $item->name = str_replace(" ", "<br>", $item->name);
                $item->class = "bg-primary";
                if(count($child) > 0){
                    $item->children = $child;
                }
                $data[] = $item->toArray();
            }
            return json_encode($data);
        }

        return view("company.rc.st", compact("id"));
    }

    function role_controll($id, Request $request){
        $v = $request->v;
        $id = base64_decode($id);
        $company = ConfigCompany::where('id', $id)->first();

        $roleHasPriv = [];

        $numberRole = 1;

        $numberDivision = 1;

        $numberModule = 1;


        $numberAction = 1;
        $preferences = Preference_config::where('id_company', $id)->first();

        $roleDivsPriv = [];
        $users = [];
        $roleDivsList = [];
        $roleList = [];
        $divList = [];
        $divList = [];
        $parentLists = [];
        $parentPosition = [];
        $level_role = [];
        $numberPosition = 1;
        $roles = [];
        $divisions = [];
        $modules = [];
        $actions = [];

        $emp_type = [];
        $coa = [];
        $coa_detail = [];

        $user_devices = [];
        $uids = [];
        $custom_roles = [];

        if($v == "position-management"){
            $users = User::select('users.*', 'users.id_rms_roles_divisions AS userRoleDivId', 'rms_roles_divisions.id_rms_roles AS userRoleId', 'rms_roles_divisions.id_rms_divisions AS userDivId', 'rms_roles.name AS roleName', 'rms_divisions.name AS divName')
                ->leftJoin('rms_roles_divisions', 'rms_roles_divisions.id', '=', 'users.id_rms_roles_divisions')
                ->leftJoin('rms_roles', 'rms_roles.id', '=', 'rms_roles_divisions.id_rms_roles')
                ->leftJoin('rms_divisions', 'rms_divisions.id', '=', 'rms_roles_divisions.id_rms_divisions')
                ->where('company_id', $id)
                ->orderBy('users.username', 'ASC')
                ->get();
            $roleDivsPriv = RolePrivilege::select('id_rms_roles_divisions')->groupBy('id_rms_roles_divisions')->get();
            foreach ($roleDivsPriv as $value) {
                $roleHasPriv[] = $value->id_rms_roles_divisions;
            }

            $roleList = Role::where('id_company', $id)->pluck('name', 'id');
            $divList = Division::where('id_company', $id)->pluck('name', 'id');
            $parentLists = RoleDivision::where('id_company', $id)->get();
            $roleDivsList = RoleDivision::select('rms_roles.name AS roleName', 'rms_divisions.name AS divName', 'rms_roles.id AS roleId', 'rms_divisions.id AS divId', 'rms_roles_divisions.*')
                ->leftJoin('rms_roles', 'rms_roles.id', '=', 'rms_roles_divisions.id_rms_roles')
                ->leftJoin('rms_divisions', 'rms_divisions.id', '=', 'rms_roles_divisions.id_rms_divisions')
                ->where('rms_roles_divisions.id_company', $id)
                ->get();

            foreach ($roleDivsList as $roleDivList) {
                $hasParent[$roleDivList->id] = $roleDivList->id_rms_roles_divisions_parent;
            }

            foreach ($roleDivsList as $roleDivList) {
                $parentPosition[$roleDivList->id] = RoleDivision::find($roleDivList->id_rms_roles_divisions_parent);
                if ($roleDivList->id_rms_roles_divisions_parent == null) {
                    $level_role[$roleDivList->id] = 1;
                } elseif ($roleDivList->id_rms_roles_divisions_parent != null) {
                    if (!isset($hasParent[$roleDivList->id_rms_roles_divisions_parent]) || $hasParent[$roleDivList->id_rms_roles_divisions_parent] == null) {
                        $level_role[$roleDivList->id] = 2;
                    } else {
                        $level_role[$roleDivList->id] = 3;
                    }
                }
            }
        } elseif($v == "role-management"){
            $roles = Role::where('id_company', $id)->get();
        } elseif($v == "division-management"){
            $divisions = Division::where('id_company', $id)->get();
        } elseif($v == "module-management"){
            $modules = Module::all();
        } elseif($v == "employee-type-management"){
            $id_companies = [];
            $id_companies[] = $company->id;
            if (empty($company->id_parent)) {
                $childCompany = ConfigCompany::select("id")
                    ->where('id_parent', $company->id)
                    ->get();
                foreach ($childCompany as $ids) {
                    $id_companies[] = $ids;
                }
            } else {
                $id_companies[] = $company->id_parent;
            }
            $emp_type = Hrd_employee_type::whereIn('company_id', $id_companies)
                ->where('company_exclude', 'not like', '%"' . $company->id . '"%')
                ->orWhereNull("company_exclude")
                ->get();

            $coa = Finance_coa::all();
            $coa_detail['code'] = $coa->pluck('code', 'id');
            $coa_detail['name'] = $coa->pluck('name', 'id');
        } elseif($v == "action-management"){
            $actions = Action::all();
        } elseif($v == "custom-roles"){
            $custom_roles = Kjk_custom_role::get();
        }

        return view('company.role_controll', compact('company', 'custom_roles', 'users', 'user_devices', 'uids', 'roleDivsList', 'level_role', 'numberPosition', 'roleDivsList', 'roleList', 'divList', 'parentLists', 'parentPosition', 'numberRole', 'roles', 'numberDivision', 'divisions', 'numberModule', 'modules', 'numberAction', 'actions', 'preferences', 'emp_type', 'coa', 'coa_detail'));
    }

    function delete(Request $request){
        $company = ConfigCompany::find($request->id);

        Master_company::where("company_id", $company->id)->delete();

        if ($company->delete()){
            $data['del'] = 1;
        } else {
            $data['del'] = 0;
        }

        return json_encode($data);
    }

    function comp_user($id){
        $comp_id = base64_decode($id);
        $company = ConfigCompany::find($comp_id);

        $data_emp = Hrd_employee::where('company_id', $comp_id)
            ->orderBy('emp_name')
            ->get();

        $emp = $data_emp->pluck("emp_name", "id");
        $emp_type = $data_emp->pluck("emp_type", "id");

        $etype = Hrd_employee_type::pluck("name", "id");

        $roleHasPriv = [];

        $roleDivsPriv = RolePrivilege::select('id_rms_roles_divisions')->groupBy('id_rms_roles_divisions')->get();
        foreach ($roleDivsPriv as $value) {
            $roleHasPriv[] = $value->id_rms_roles_divisions;
        }

        //Only select roles_divisions that have privilege
        $roleDivsList = RoleDivision::select('rms_roles.name AS roleName', 'rms_divisions.name AS divName', 'rms_roles_divisions.id AS id', 'rms_roles.id AS roleId', 'rms_divisions.id AS divId')
        ->leftJoin('rms_roles', 'rms_roles.id', '=', 'rms_roles_divisions.id_rms_roles')
        ->leftJoin('rms_divisions', 'rms_divisions.id', '=', 'rms_roles_divisions.id_rms_divisions')
        ->whereIn('rms_roles_divisions.id', $roleHasPriv)
        ->orderBy('rms_roles.name', 'ASC')
        ->get();

        $users = User::select('users.*', 'users.id_rms_roles_divisions AS userRoleDivId', 'rms_roles_divisions.id_rms_roles AS userRoleId', 'rms_roles_divisions.id_rms_divisions AS userDivId', 'rms_roles.name AS roleName', 'rms_divisions.name AS divName')
            ->leftJoin('rms_roles_divisions', 'rms_roles_divisions.id', '=', 'users.id_rms_roles_divisions')
            ->leftJoin('rms_roles', 'rms_roles.id', '=', 'rms_roles_divisions.id_rms_roles')
            ->leftJoin('rms_divisions', 'rms_divisions.id', '=', 'rms_roles_divisions.id_rms_divisions')
            ->where('company_id', $comp_id)
            ->where("users.role_access", "like", '%"hris"%')
            ->orderBy('users.username', 'ASC')
            ->get();

        return view('company.users', compact('company', 'emp', 'roleDivsList', 'users', 'emp_type', 'etype'));
    }

}
