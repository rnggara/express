<?php

namespace App\Http\Controllers;

use App\Mail\EmailNotification;
use App\Models\ConfigCompany;
use App\Models\Hrd_employee;
use App\Models\Hrd_employee_history;
use App\Models\Kjk_crm_job_title;
use App\Models\Kjk_crm_lead_funnel;
use App\Models\Kjk_crm_permission;
use App\Models\Kjk_crm_property;
use App\Models\Kjk_crm_user_role;
use App\Models\Kjk_lead_layout;
use App\Models\Kjk_user_role;
use App\Models\Kjk_user_team;
use App\Models\Master_company;
use App\Models\RolePrivilege;
use App\Models\User;
use App\Models\UserPrivilege;
use App\Models\Kjk_dashboard_pref;
use App\Models\Kjk_crm_address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class KjkPreferences extends Controller
{
    protected $dirView = "_crm.preferences";

    private $crmAdminModules;
    private $crmAdminAction;
    private $crmUserModules;
    private $crmUserAction;
    private $crmUserPermission;
    private $dir;

    public function __construct() {
        $this->crmAdminModules = Config::get("constants.CRM_ADMIN_MODULES");
        $this->crmAdminAction = Config::get("constants.CRM_ADMIN_ACTION");
        $this->crmUserModules = Config::get("constants.CRM_USER_MODULES");
        $this->crmUserAction = Config::get("constants.CRM_USER_ACTION");
        $this->crmUserPermission = Config::get("constants.CRM_USER_PERMISSION");
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    public function dirView(){
        return $this->dirView;
    }

    function dashboard_index(){
        $list = Config::get("constants.DASHBOARD_CRM");
        $set = Kjk_dashboard_pref::where("company_id", Session::get("company_id"))->pluck("status", "dashboard_key");
        return view("$this->dirView.dashboard.index", compact("list", "set"));
    }

    function dashboard_update($key, $status){
        $set = Kjk_dashboard_pref::firstOrCreate([
            "dashboard_key" => $key,
            "company_id" => Session::get("company_id")
        ]);
        $set->status = $status;
        $set->save();

        return $set;
    }

    function index(Request $request){
        $state = Session::get("session_state") ?? ($request->state ?? "intranet");
        Session::put("session_state", $state);

        if($state == "intranet"){
            Session::put("home_url", route("home"));
            return redirect()->route("crm.pref.general.basic_information.index");
        }

        if($state == "personel"){
            return redirect()->route("crm.pref.personel.employee_status.index");
        }

        if($state == "crm"){
            return redirect()->route("crm.pref.crm.dashboard.index");
        }

        if($state == "attendance"){
            return redirect()->route("crm.pref.attendance.reason_name.index");
        }

        // return view("$this->dirView.index");
    }

    // permission
    function permission_index(){
        $crmAdminModules = $this->crmAdminModules;
        $crmAdminAction = $this->crmAdminAction;
        $crmUserModules = $this->crmUserModules;
        $crmUserAction = $this->crmUserAction;
        $crmUserPermission = $this->crmUserPermission;

        $roles = Kjk_crm_permission::where('company_id', Session::get('company_id'))->get();
        $pipeline = Kjk_lead_layout::where('company_id', Session::get('company_id'))->get();
        return view("$this->dirView.permission.index", compact("roles", "crmAdminModules", "crmAdminAction", "crmUserModules", "crmUserAction", "crmUserPermission", "pipeline"));
    }

    function permission_store(Request $request){
        $dataRole = [];

        if($request->type == "data"){
            $id = $request->id ?? null;
            if(empty($id)){
                $exist = Kjk_crm_permission::where("name", $request->role_name ?? $dataRole['name'])
                    ->where("company_id", Session::get('company_id'))
                    ->first();
                if(!empty($exist)){
                    $resp = [
                        "success" => false,
                        "message" => "Duplicate Role"
                    ];

                    return json_encode($resp);
                }
            }

            $admin_edit = Kjk_crm_permission::find($id);

            $dataRole = [
                "name" => $request->role_name,
                "is_admin" => $request->is_admin ?? 1,
                "edit" => $admin_edit,
            ];

            Session::put("crm_role_temp", $dataRole);

            $is_admin = $request->is_admin ?? 1;

            $resp = [
                "success" => true,
                "message" => "",
                "is_admin" => $is_admin == 1 ? true : false,
                'edit' => $admin_edit
            ];

            return json_encode($resp);
        }

        if($request->type == "admin"){
            $dataRole = Session::get("crm_role_temp");
            $id = $request->id ?? ($dataRole['edit']['id'] ?? null);
            if(empty($id)){
                $exist = Kjk_crm_permission::where("name", $request->role_name ?? $dataRole['name'])
                    ->where("company_id", Session::get('company_id'))
                    ->first();
                if(!empty($exist)){
                    $resp = [
                        "success" => false,
                        "message" => "Duplicate Role"
                    ];

                    return json_encode($resp);
                }
            }

            $dataRole['permissions'] = [];
            $dataRole['permissions']['admin'] = $request->ck;
            Session::put("crm_role_temp", $dataRole);
            $resp = [
                "success" => true,
                "message" => "",
                'edit' => $dataRole['edit']
            ];

            return json_encode($resp);
        }

        if($request->type == "user"){
            $dataRole = Session::get("crm_role_temp");
            $id = $request->id ?? ($dataRole['edit']['id'] ?? null);
            if(empty($id)){
                $exist = Kjk_crm_permission::where("name", $request->role_name ?? $dataRole['name'])
                    ->where("company_id", Session::get('company_id'))
                    ->first();
                if(!empty($exist)){
                    $resp = [
                        "success" => false,
                        "message" => "Duplicate Role"
                    ];

                    return json_encode($resp);
                }
            }

            $dataRole['permissions'] = $dataRole['permissions'] ?? [];
            $dataRole['permissions']['user'] = $request->ck;
            Session::put("crm_role_temp", $dataRole);

            $role = Kjk_crm_permission::findOrNew($id);
            $role->name = $dataRole['name'];
            $role->is_admin = $dataRole['is_admin'];
            $role->permission = json_encode($dataRole['permissions']);
            if(empty($id)){
                $role->company_id = Session::get("company_id");
                $role->created_by = Auth::id();
            }
            $role->save();

            $resp = [
                "success" => true,
                "message" => "",
                'edit' => $dataRole['edit']
            ];

            Session::put("crm_role_temp", null);

            return json_encode($resp);
        }
    }

    // opportunity
    function opportunity_index(){
        $pipelines = Kjk_lead_layout::where("company_id", Session::get("company_id"))->get();
        return view("$this->dirView.opportunity.index", compact("pipelines"));
    }

    function opportunity_detail($id = null){
        $pipeline = Kjk_lead_layout::find($id);

        return view("$this->dirView.opportunity.detail", compact("pipeline"));
    }

    function opportunity_add_funnel(){
        return view("$this->dirView.opportunity._add_funnel");
    }

    function opportunity_store(Request $request){
        $last = Kjk_lead_layout::where("company_id", Session::get("company_id"))->orderby('row_order', "desc")->first();

        $num = empty($last) ? 1 : $last->row_order + 1;

        $pipeline = Kjk_lead_layout::findOrNew($request->id);
        $pipeline->label = $request->pipe_label;

        if(empty($request->id)){
            $pipeline->company_id = Session::get('company_id');
            $pipeline->created_by = Auth::id();
            $pipeline->row_order = $num;
        }

        $pipeline->updated_by = Auth::id();

        $pipeline->save();

        $funnel_name = $request->funnel_name ?? [];
        $status_funnel = $request->status_funnel ?? [];
        $idle_state = $request->idle_state ?? [];
        $warning_state = $request->warning_state ?? [];
        $funnel_id = $request->funnel_id ?? [];
        $funnel_delete = json_decode($request->funnel_delete ?? "[]", true);

        foreach($funnel_name as $i => $item){
            $fid = $funnel_id[$i] ?? null;
            if(empty($fdel)){
                $funnel = Kjk_crm_lead_funnel::findOrNew($fid);

                $funnel->label = $item;
                $funnel->layout_id = $pipeline->id;
                $funnel->status_funnel = $status_funnel[$i] ?? -1;
                $funnel->idle_state = $idle_state[$i] ?? 0;
                $funnel->warning_state = $warning_state[$i] ?? 0;

                if(empty($fid)){
                    $funnel->company_id = $pipeline->company_id;
                    $funnel->created_by = Auth::id();
                    $funnel->row_order = $i+1;
                }

                $funnel->updated_by = Auth::id();
                $funnel->save();
            }
        }

        foreach($funnel_delete as $item){
            $funnel = Kjk_crm_lead_funnel::find($item);
            $funnel->delete();
        }

        $msg = "New pipeline has been sucessfully added";
        if(!empty($request->id)){
            $msg = "Pipeline $pipeline->label has been successfully updated";
        }

        return redirect()->route("crm.pref.crm.opportunity.index")->with(["toast" => ["status" => "bg-success", "message" => $msg]]);
    }

    // user
    function user_index(){
        $teams = Kjk_user_team::where("company_id", Session::get("company_id"))->get();
        $pipelines = Kjk_lead_layout::where("company_id", Session::get("company_id"))->get();

        $userTeam = [];

        foreach($teams as $item){
            foreach($item->members as $member){
                $userTeam[$member][] = $item;
            }
        }

        $users = User::where("company_id", Session::get("company_id"))
            ->hris()
            ->get();

        $pipe_name = $pipelines->pluck("label", "id");

        $emp = Hrd_employee::where("company_id", Session::get("company_id"))
            ->where(function($q) use($users){
                $q->whereNotIn("id", $users->pluck("emp_id"));
            })
            ->get();
        return view("$this->dirView.user.index", compact("teams", 'pipelines', "users", "emp", "userTeam", "pipe_name"));
    }

    function user_detail($id){
        $user = User::find($id);
        $teams = Kjk_user_team::where("company_id", Session::get("company_id"))->get();
        // $pipelines = Kjk_lead_layout::where("company_id", Session::get("company_id"))->get();
        $job_title = Kjk_crm_job_title::where("company_id", Session::get("company_id"))->get();
        $roles = Kjk_crm_permission::where("company_id", Session::get("company_id"))->get();

        $myTeamId = [];
        $pId = [];
        foreach($teams as $item){
            if(in_array($user->id ?? null, $item->members)){
                $myTeamId[] = $item->id;
            }

            $pis = json_decode($item->pipeline_id ?? "[]", true);
            $pId = array_merge($pis, $pId);
        }

        $pipelines = Kjk_lead_layout::where("company_id", Session::get("company_id"))
            ->whereIn("id", $pId)->get();

        $user_comp = ConfigCompany::find($user->company_id ?? Session::get("company_id"));

        $address = Kjk_crm_address::where("type", "user")
            ->where("target_id", $user->id ?? null)
            ->get();

        $view = view("$this->dirView.user.detail", compact("teams", "address", "user", "job_title", "roles", "user_comp", "myTeamId", "pipelines"));

        return json_encode([
            "view" => $view->render()
        ]);
    }

    function add_address($request, $type, $id){
        $address = $request->address ?? [];
        $title = $request->title;
        $full_address = $request->full_address;
        $postal_code = $request->postal_code;
        $country = $request->country;
        $province = $request->province;
        $city = $request->city;
        $subdistrict = $request->subdistrict;
        Kjk_crm_address::where("type", $type)
            ->where('target_id', $id)
            ->forceDelete();
        foreach($address as $key => $addr){
            $add = new Kjk_crm_address();
            $add->type = $type;
            $add->target_id = $id;
            $add->address = $addr;
            $add->title = $title[$key];
            $add->full_address = $full_address[$key];
            $add->postal_code = $postal_code[$key];
            $add->country = $country[$key];
            $add->province = $province[$key];
            $add->city = $city[$key];
            $add->subdistrict = $subdistrict[$key];
            $add->company_id = Session::get('company_id');
            $add->created_by = Auth::id();
            $add->save();
        }
    }

    function user_delete_job_title($id){
        $job_title = Kjk_crm_job_title::find($id)->delete();

        return "true";
    }

    function user_edit_job_title(Request $request){
        $job_title = Kjk_crm_job_title::find($request->id);
        if($request->type == "name"){
            $job_title->name = $request->text;
        }

        if($request->type == "parent"){
            $job_title->parent_id = $request->parent == "#" ? null : $request->parent;
        }
        $job_title->save();

        return json_encode(["success" => true]);
    }

    function user_get_job_title($type, $parent_id = null, Request $request){
        $job_title = Kjk_crm_job_title::select("*", "name as text")
            ->where('company_id', Session::get("company_id"))
            ->where(function($q) use($parent_id, $request){
                if(!empty($parent_id)){
                    $q->where("parent_id", $parent_id);
                }

                if(!empty($request->term)){
                    $q->where("name", "like", "%$request->term%");
                }
            })->get();

        if($type == "select"){
            return json_encode([
                "results" => $job_title
            ]);
        } elseif($type == "tree"){
            $parent = $request->parent;
            $data = [];
            if ($parent == "#") {
                foreach($job_title->whereNull("parent_id") as $item){
                    $hasChildren = count($item->children) > 0 ? true : false;
                    $data[] = array(
                        "id" => "$item->id",
                        "text" => $item->text,
                        // "icon" => "ki-outline ki-folder fs-1 text-dark",
                        "children" => $hasChildren,
                        "state" => [
                            "opened" => $hasChildren
                        ],
                        "type" => "root"
                    );
                }
            } else {
                $_parent = explode("_", $parent);
                $parent_id = end($_parent);
                foreach($job_title->where("parent_id", $parent_id) as $item){
                    $hasChildren = count($item->children) > 0 ? true : false;
                    $data[] = array(
                        "id" => "$item->id",
                        "text" => $item->text,
                        // "icon" => "ki-outline ki-folder fs-1 text-dark",
                        "children" => count($item->children) > 0 ? true : false,
                        "state" => [
                            "opened" => $hasChildren
                        ]
                    );
                }
                // if (rand(1, 5) === 3) {
                //     $data[] = array(
                //         "id" => "node_" . time() . rand(1, 100000),
                //         "icon" => "ki-outline ki-file text-muted",
                //         "text" => "No children ",
                //         "state" => array("disabled" => true),
                //         "children" => false
                //     );
                // } else {
                //     for($i = 1; $i < rand(2, 4); $i++) {
                //         $data[] = array(
                //             "id" => "node_" . time() . rand(1, 100000),
                //             "icon" => ( rand(0, 3) == 2 ? "ki-outline ki-file fs-1" : "ki-outline ki-folder fs-1")." text-dark",
                //             "text" => "Node " . time(),
                //             "children" => ( rand(0, 3) == 2 ? false : true)
                //         );
                //     }
                // }
            }

            return $data;
        }
    }

    function user_store($type, Request $request){
        if($type == "team"){
            return $this->user_store_team($request);
        }

        if($type == "job-title"){
            return $this->user_store_job_title($request);
        }

        if($type == "user"){
            return $this->user_store_user($request);
        }

        if($type == "user-update"){
            return $this->user_update($request);
        }

        if($type == "user-add"){
            return $this->user_add($request);
        }
    }

    function user_archive($type, $id){
        if($type == "team"){
            $d = Kjk_user_team::findOrFail($id);
        }

        if($type == "job-title"){
            // return $this->user_store_job_title($request);
        }

        if($type == "user"){
            // return $this->user_store_user($request);
        }

        if($type == "user-update"){
            // return $this->user_update($request);
        }

        $d->delete();

        return redirect()->back();
    }

    function user_update(Request $request){
        $user = User::findOrFail($request->id);

        $user->name = $request->name;
        $user->crm_job_title = $request->job_title;
        $user->crm_role = $request->role;
        $user->address = $request->address[0] ?? null;
        $user->emails = $request->email;

        $phone = $request->phone ?? [];
        $ptype = $request->phone_types;
        $user->default_pipeline = $request->default_pipeline;

        $arrPhone = [];

        foreach($phone as $i => $item){
            $col = [];
            $col['type'] = $ptype[$i] ?? "Work";
            $col['phone'] = $item;
            $arrPhone[] = $col;
        }

        $user->phones = $arrPhone;

        $file = $request->file("user_img");
        if(!empty($file)){
            $d = date("YmdHis");
            $newName = $d."_".$user->id."_profile_".$file->getClientOriginalName();
            if($file->move($this->dir, $newName)){
                $user->user_img = "media/attachments/$newName";
            }
        }


        $user->save();

        $this->add_address($request, "user", $user->id);

        $team = Kjk_user_team::whereIn("id",$request->teams)->get();

        if($team->count() > 0){
            foreach($team as $tm){
                if(!in_array($user->id, $tm->members)){
                    $members = $tm->members;
                    $members[] = $user->id;
                    $tm->members = $members;
                }

                $tm->save();
            }
        }

        return redirect()->back();
    }

    function user_add(Request $request){
        $user = User::where("email", $request->email)->first();
        if(!empty($user)){
            return redirect()->back()->with([
                "toast" => [
                    "message" => "Email duplicate",
                    "bg" => "bg-danger"
                ]
            ]);
        } else {
            $mComp = Master_company::where("company_id", Session::get('company_id'))->first();
            $email = $request->email;
            $_email = explode("@", $email);
            $username = $_email[0];
            $user = new User();
            $user->id_batch = $username."1";
            $user->name = $request->name;
            $user->email = $email;
            $user->username = $username;
            $user->crm_role = $request->role;
            $user->address = $request->address;
            $user->id_rms_roles_divisions = 45;
            $user->email_verified_at = date("Y-m-d H:i:s");
            $user->company_id = Session::get('company_id');
            $user->comp_id = $mComp->id ?? null;
            $user->role_access = json_encode(["hris", "employer"]);
            $user->complete_profile = 1;
            $user->access = "EP";

            $phone = $request->phone ?? [];
            $ptype = $request->phone_types;

            $arrPhone = [];

            foreach($phone as $i => $item){
                $col = [];
                $col['type'] = $ptype[$i] ?? "Work";
                $col['phone'] = $item;
                $arrPhone[] = $col;
            }

            $user->phones = json_encode($arrPhone);

            $file = $request->file("user_img");
            if(!empty($file)){
                $d = date("YmdHis");
                $newName = $d."_".$user->id."_profile_".$file->getClientOriginalName();
                if($file->move($this->dir, $newName)){
                    $user->user_img = "media/attachments/$newName";
                }
            }
            $user->save();

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

            $employee = new Hrd_employee();
            $employee_history = new Hrd_employee_history();

            // $initials = $this->initials($request->full_name, $request->edu);

            $employee->emp_name = stripslashes($request->full_name);
            $employee->company_id = Session::get('company_id');
            $employee->address = $request->address;
            $employee->email = $request->email;
            $employee->created_by = Auth::user()->username;
            $employee->save();

            $employee_history->emp_id        = $employee->id;
            $employee_history->activity      = "in";
            $employee_history->act_date      = date("Y-m-d");
            $employee_history->act_by        = Auth::user()->username;
            $employee_history->company_id    = Session::get('company_id');

            $employee_history->save();

            $user->emp_id = $employee->id;
            $user->save();
            $team = Kjk_user_team::find($request->teams);

            if(!empty($team)){
                if(!in_array($user->id, $team->members)){
                    $members = $team->members;
                    $members[] = $user->id;
                    $team->members = $members;
                }

                $team->save();
            }

            $_email = $user->email;

            $token = $user->id."_".date("Y-m-d H:i:s", strtotime("+2 hour"));

            $data = [
                "name" => $user->name,
                "username" => $username,
                "company" => $mComp->company_name,
                "link" => route("account.setting.activation")."?token=".base64_encode($token)
            ];

            Mail::to($_email)->send(new EmailNotification("User Activation", "_crm.preferences.user.email", $data));
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "User successfully added.",
                "bg" => "bg-success"
            ]
        ]);
    }

    function user_store_user(Request $request){
        $type = $request->team_user_sel;

        if($type == "_all"){
            $users = User::where("company_id", Session::get("company_id"))
                ->where("role_access", "like", "%hris%")
                ->get();

            $emp = Hrd_employee::where("company_id", Session::get("company_id"))
                ->where(function($q) use($users){
                    $q->whereNotIn("id", $users->pluck("emp_id"));
                })->whereNotNull("email")->get();
        } else {
            $emp = Hrd_employee::where("company_id", Session::get("company_id"))
                ->whereIn("id", $request->emp)
                ->whereNotNull("email")->get();
        }

        foreach($emp as $item){

            $userEmp = User::where("email", $item->email)->first();
            if(empty($userEmp)){

                $mComp = Master_company::where("company_id", $item->company_id)->first();

                $email = $item->email;
                $name = $item->emp_name;
                $_email = explode("@", $email);
                $username = $_email[0];
                $user = new User();
                $user->id_batch = $username."1";
                $user->emp_id = $item->id;
                $user->name = $name;
                $user->email = $email;
                $user->username = $username;
                $user->id_rms_roles_divisions = 45;
                $user->company_id = $item->company_id;
                $user->comp_id = $mComp->id ?? null;
                $user->role_access = ["hris"];
                // $user->email_verified_at = date("Y-m-d H:i:s");
                $user->role_access = json_encode(["hris", "employer"]);
                $user->complete_profile = 1;
                $user->access = "EP";
                $user->save();

                //Add user privilege based on position
                $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
                ->where('id_rms_roles_divisions', $user->id_rms_roles_divisions)
                ->get();
                foreach ($roleDivPriv as $key => $valDivPriv) {
                    $addUserRole = new UserPrivilege();
                    $addUserRole->id_users = $user->id;
                    $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
                    $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
                    $addUserRole->save();
                }

                $_email = $item->email;

                $token = $user->id."_".date("Y-m-d H:i:s", strtotime("+2 hour"));

                $data = [
                    "name" => $item->emp_name,
                    "username" => $username,
                    "company" => $mComp->company_name,
                    "link" => route("account.setting.activation")."?token=".base64_encode($token)
                ];

                Mail::to($_email)->send(new EmailNotification("User Activation", "_crm.preferences.user.email", $data));
            }
        }

        return redirect()->back();
    }

    function user_store_job_title(Request $request){
        $job_title = Kjk_crm_job_title::findOrNew($request->id);

        $job_title->name = $request->name;
        $job_title->parent_id = $request->parent_id ?? null;

        if(empty($request->id)){
            $job_title->company_id = Session::get("company_id");
            $job_title->created_by = Auth::user()->username;
        }

        $job_title->save();

        return json_encode([
            "success" => true,
            "data" => $job_title
        ]);
    }

    function user_edit($type, $id){
        if($type == "team"){
            $team = Kjk_user_team::find($id);
            $pipelines = Kjk_lead_layout::where("company_id", Session::get("company_id"))->get();
            $users = User::where("company_id", Session::get("company_id"))
                ->where("role_access", "like", "%hris%")
                ->get();
            $view = view("$this->dirView.user._edit_team", compact("team", 'pipelines', "users"));
            $members = User::select("id", "name", "phone","email")->whereIn("id", $team->members)
                ->where("company_id", $team->company_id)
                ->hris()
                ->get()->toArray();
            foreach($members as $i => $item){
                $item['phone'] = $item['phone'] ?? "-";
                $item['email'] = $item['email'] ?? "-";
                $item['company'] = Session::get('company_name_parent');
                $members[$i] = $item;
            }
            return json_encode([
                "view" => $view->render(),
                "member" => $members
            ]);
        }
    }

    function user_store_team(Request $request){

        $team_member = $request->team_member;

        $member_sel = $request->team_member_sel;

        $team = Kjk_user_team::findOrNew($request->id);

        $team->name = $request->team_name;
        $team->pipeline_id = json_encode($request->pipeline_id ?? "[]");
        if(empty($request->id)){
            $team->company_id = Session::get("company_id");
            $team->created_by = Auth::user()->username;
        }
        $team->members = !empty($member_sel) ? ["_all"] : $team_member;
        $team->save();

        $msg = "New team has been sucessfully added";
        if(!empty($request->id)){
            $msg = "Team $team->name has been successfully updated";
        }

        return redirect()->back()->with(["toast" => ["status" => "bg-success", "message" => $msg]]);
    }

    function properties_view($id = null, $type = null, Request $request){
        $detail = [];
        if(!empty($id)){
            if(empty($type)){
                $detail = Kjk_lead_layout::find($id);
            }
        }

        $props = Kjk_crm_property::where(function($q){
            $q->where("company_id", Session::get("company_id"));
            $q->orWhereNotNull("table_column");
        })
            ->where("type", $type ?? "opportunity")
            ->where(function($q) use($detail){
                if(!empty($detail)){
                    $q->whereNull("layout_id");
                    $q->orWhere("layout_id", $detail->id);
                }
            })
            ->get();

        $properties = Config::get("constants.CRM_PROPERTIES_TYPE");

        $users = User::hris()->where("company_id", Session::get("company_id"))->pluck("name", "id");

        return view("_crm.preferences.properties.index", compact("type", "detail", "properties", "props", "users"));
    }

    function opportunity_change_status($id){
        $detail = Kjk_lead_layout::findOrFail($id);
        $detail->status = $detail->status == 1 ? 0 : 1;
        $detail->save();

        return redirect()->back();
    }

    function opportunity_archive($id){
        $detail = Kjk_lead_layout::findOrFail($id);
        $detail->delete();

        return redirect()->back();
    }

    function properties_preview(Request $request){
        $view = "_".($request->type ?? 0);

        $name = $request->name;
        $placeholder = $request->placeholder;
        $placeholder2 = $request->placeholder2;
        $additional = $request->additional;
        $currency = $request->currency;

        $v = view("_crm.preferences.properties.preview.$view", compact("name", "placeholder", "placeholder2", "additional", "currency"));

        return json_encode([
            "view" => $v->render()
        ]);
    }

    function properties_additional($type = null, Request $request){
        $modal = $request->modal;
        $view = $type != null ? "_crm.preferences.properties._$type" : "_crm.preferences.properties._option";
        $prop = Kjk_crm_property::find($request->id);
        $additional = json_decode($prop->additional ?? "[]", "true");
        return view($view, compact("modal", 'additional'));
    }

    function properties_store(Request $request){
        $prop = Kjk_crm_property::findOrNew($request->id);

        $prop->type = $request->type;
        $prop->property_type = $request->property_type;
        $prop->property_name = $request->property_name;
        $prop->property_placeholder = $request->input_placeholder;
        $prop->currency = $request->property_type != 14 ? null : $request->currency;
        $prop->additional = json_encode($request->option ?? []);
        $prop->layout_id = $request->layout_id ?? null;
        if($request->property_type == 12){
            $additional = [
                'value' => $request->option,
                "color" => $request->color
            ];

            $prop->additional = json_encode($additional);
        }

        if(empty($request->id)){
            $prop->company_id = Session::get("company_id");
            $prop->created_by = Auth::id();
        }

        $prop->save();

        return redirect()->back();
    }

    function properties_detail($id){
        $prop = Kjk_crm_property::findOrFail($id);

        $properties = Config::get("constants.CRM_PROPERTIES_TYPE");

        $users = User::hris()->where("company_id", Session::get("company_id"))->pluck("name", "id");

        return view("_crm.preferences.properties._edit", compact("prop", "properties", "users"));
    }

    function properties_change_status($id){
        $detail = Kjk_crm_property::findOrFail($id);
        $detail->hide = $detail->hide == 1 ? null : 1;
        $detail->save();

        return redirect()->back();
    }

    function properties_archive($id){
        $detail = Kjk_crm_property::findOrFail($id);
        $detail->delete();

        return redirect()->back();
    }

    function company_index(Request $request){
        return $this->properties_view(null, "company", $request);
    }

    function contact_index(Request $request){
        return $this->properties_view(null, "contact", $request);
    }

    function file_index(Request $request){
        return $this->properties_view(null, "file", $request);
    }

    function product_index(Request $request){
        return $this->properties_view(null, "product", $request);
    }
}
