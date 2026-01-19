<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Kjk_uac_role;
use App\Models\Hrd_employee;
use App\Models\User;
use App\Models\Kjk_comp_departement;
use App\Models\ConfigCompany;
use App\Models\Asset_wh as Kjk_comp_location;

class KjkPreferenceUAC extends Controller
{

    private $dir, $uploadDir;

    public function __construct() {
        $this->dir = "_crm.preferences.uac";
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
        $this->uploadDir = str_replace("\\", "/", $dir);
    }

    function role_index(){
        $data = Kjk_uac_role::where("company_id", Session::get("company_id"))
            ->get();
        return view("$this->dir.role.index", compact('data'));
    }

    function role_permission($id, Request $request){
        $data = Kjk_uac_role::find($id);
        $depts = Kjk_comp_departement::where('company_id', Session::get("company_id"))->get();
        $locations = Kjk_comp_location::office()->where('company_id', Session::get("company_id"))->get();

        $dept_name = $depts->pluck("name", "id");
        $loc_name = $locations->pluck("name", "id");

        $perm = \Config::get("constants.uac_permissions");
        $actions = \Config::get("constants.uac_actions");

        if($request->a == "role"){
            if($request->type == "default"){
                // $permission = $data->permissions ?? [];
                // $col = $permission[$request->name] ?? [];
                // $col['type'] = $request->type;
                // $col['enable'] = $request->checked == "true" ? true : false;
                // if($request->checked == "true"){
                //     $col['actions'] = $actions;
                // } else {
                //     $col['actions'] = [];
                // }
                // $permission[$request->name] = $col;
                // $data->permissions = $permission;
                // $data->save();

                $act = [];
                $_perm = $perm[$request->name];
                foreach($_perm as $item){
                    $act[$item] = $actions;
                }

                $this->updatePermissions($data, $request->name, "default", $request->checked, $act);

                $permission = $data->permissions;

                return json_encode($permission);
            } else {

                if($request->checked == "true" || !empty($request->view)){
                    $perm_data = $perm[$request->name];
                    $key = $request->name;

                    $permissions = $data->permissions ?? [];
                    $rperm = $permissions[$key] ?? [];

                    $view = $request->view;

                    $v = view("$this->dir.role._modal_custom", compact("perm_data", "key", 'actions', 'view', 'rperm'))->render();

                    return json_encode([
                        "view" => $v,
                        'rperm' => $rperm
                    ]);
                } else {
                    $permission = $data->permissions ?? [];
                    $col = $permission[$request->name] ?? [];
                    if(!empty($col)){
                        $col['enable'] = false;
                    }

                    $permission[$request->name] = $col;
                    $data->permissions = $permission;
                    $data->save();

                    return json_encode($permission);
                }
            }
        }



        return view("$this->dir.role.permissions", compact('data', 'depts', 'locations', 'perm', "dept_name", "loc_name"));
    }

    function role_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){
            $validator = Validator::make($request->all(), [
                "role_name" => "required"
            ]);

            if($validator->fails()){
                return redirect()->back()->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                ])->withErrors($validator)->withInput($request->all());
            }

            $conflict = Kjk_uac_role::where("company_id", Session::get("company_id"))
                ->where("id", "!=", $request->id ?? null)
                ->where("name", $request->role_name)
                ->first();

            if(!empty($conflict)){
                return redirect()->back()->with([
                    "modal" => "modal_add"
                ])->withErrors([
                    "role_name" => ucwords($request->role_name)." is alread in use, enter another role name"
                ])->withInput($request->all());
            }

            $clone = Kjk_uac_role::find($request->clone ?? null);

            $role = Kjk_uac_role::findOrNew($request->id);
            $role->name = ucwords($request->role_name);
            $role->permissions = $clone->permissions ?? null;
            $role->company_id = Session::get("company_id");
            $role->created_by = Auth::id();
            $role->save();

            $toast = [
                "message" => empty($request->id) ? "Role has been added" : "Role has been updated",
                "bg" => "bg-success"
            ];
        } else {
            $role = Kjk_uac_role::find($request->id);

            $role->delete();
            $toast = [
                "message" => "Role has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function updatePermissions($data, $name, $type, $checked, $permissions){
        $permission = $data->permissions ?? [];
        $col = $permission[$name] ?? [];
        $col['type'] = $type;
        $col['enable'] = $checked == "true" ? true : false;
        if($checked == "true"){
            $col['permissions'] = $permissions;
        } else {
            $col['permissions'] = [];
        }
        $permission[$name] = $col;
        $data->permissions = $permission;
        $data->save();
    }

    function role_permission_update(Request $request){
        $type = $request->type;
        $role = Kjk_uac_role::find($request->id);
        if($type == "location"){
            $id_loc = json_decode($request->id_locs);
            $role->locations = $id_loc;
            $role->save();
            $message = "Locations has been added to $role->name";
        } elseif($type == "departements") {
            $id_dept = json_decode($request->id_dept);
            $role->departements = $id_dept;
            $role->save();

            $message = "Departements has been added to $role->name";
        } elseif($type == "permission"){
            $actions = [];
            foreach($request->permission as $key => $item){
                $actions[$key] = array_keys($item);
            }
            $this->updatePermissions($role, $request->name, "custom", "true", $actions);
            $message = "Permission updated";
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => $message,
                "bg" => "bg-success"
            ]
        ]);
    }

    function role_permission_remove($type, $id, $key){
        $role = Kjk_uac_role::find($id);

        $detail = $role[$type];
        unset($detail[$key]);
        $detail = array_values($detail);

        $role[$type] = $detail;
        $role->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => ucwords($type)." has been removed from $role->name",
                "bg" => "bg-success"
            ]
        ]);
    }

    function user_index(Request $request){
        $data = User::hris()->where("company_id", Session::get("company_id"))->orderBy("name")->get();

        $locations = Kjk_comp_location::office()->where("company_id", Session::get("company_id"))
            ->get();
        $departements = Kjk_comp_departement::where('company_id', Session::get("company_id"))->get();
        $roles = Kjk_uac_role::where("company_id", Session::get("company_id"))
            ->get();

        $sesComp = ConfigCompany::find(Session::get("company_id"));

        $parent = $sesComp->parentComp;

        $comp_id = [];

        while(!empty($parent)){
            $comp_id[] = $parent->id;
            $parent = $parent->parentComp;
        }

        $user_parent = User::hris()->whereIn("company_id", $comp_id)
            ->whereNotIn("id_batch", $data->pluck("id_batch"))
            ->orderBy("name")
            ->get();

        $resigned = \App\Models\Personel_resign::where("company_id", Session::get("company_id"))
            ->where("resign_date", "<=", date("Y-m-d"))->pluck("emp_id");

        $personel = \App\Models\Hrd_employee::where("company_id", Session::get("company_id"))
            ->whereNotIn("id", $resigned)
            ->whereNotIn("id", $data->whereNotNull("emp_id")->pluck("emp_id"))
            ->get();

        if($request->a == "edit"){
            $item = $data->where("id", $request->id)->first();

            $personel = \App\Models\Hrd_employee::where("company_id", Session::get("company_id"))
                ->whereNotIn("id", $resigned)
                ->where(function($q) use($item, $data){
                    $q->where("id", $item->emp_id);
                    $q->orWhereNotIn("id", $data->whereNotNull("emp_id")->pluck("emp_id"));
                })
                ->get();

            $view = view("$this->dir.user.edit", compact('item', 'locations', 'departements', 'roles', 'personel'))->render();

            return json_encode([
                "view" => $view
            ]);
        }

        return view("$this->dir.user.index", compact('data', 'locations', 'departements', 'roles', 'user_parent', 'personel'));
    }

    function user_post(Request $request){
        $store_type = $request->add_type;
        if($store_type == "import"){
            $user = User::find($request->import_id);
            $clone = $user->replicate();

            $clone->email = $clone->email."/".Session::get('company_id');
            $clone->created_by = Auth::id();
            $clone->created_at = date("Y-m-d H:i:s");
            $clone->api_token = null;
            $clone->company_id = Session::get("company_id");
            $clone->uac_role = $request->import_role;
            $clone->name = $request->import_name;
            $clone->emp_id = null;
            $clone->save();

            $toast = [
                "message" => empty($request->id) ? "User has been added" : "User change has been saved",
                "bg" => "bg-success"
            ];
        } else {
            $submit = $request->submit;
            if($submit == "store"){
                $field = [
                    // "user_id" => "required",
                    "name" => "required",
                    // "departement" => "required",
                    // "location" => "required",
                    "email" => "required",
                    "role" => "required",
                ];
                if(empty($request->id)){
                    $field['password'] = "required";
                }
                $validator = Validator::make($request->all(), $field);

                if($validator->fails()){
                    return redirect()->back()->with([
                        "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                    ])->withErrors($validator)->withInput($request->all());
                }

                // $emp = Hrd_employee::where("emp_id", $request->user_id)->first();
                // if(empty($emp)){
                //     $emp = new Hrd_employee();
                //     $emp->emp_id = $request->id;
                //     $emp->emp_name = $request->name;
                //     $emp->email = $request->email;
                //     $emp->company_id = Session::get("company_id");
                //     $emp->emp_type = 1;
                //     $emp->emp_level = 1;
                //     $emp->save();
                // }

                $username = explode("@", $request->email)[0];

                $user = User::findOrNew($request->id);
                $user->emp_id = $request->emp_id ?? null;
                $user->name = $request->name;
                $user->username = $username;
                $user->email = $request->email;
                $user->id_batch = explode("@", $request->email)[0]."1";
                $user->id_rms_roles_divisions = 45;
                $user->position = "FP";
                $user->access = "EP";
                $user->email_verified_at = date("Y-m-d H:i:s");
                $user->complete_profile = 1;
                $user->role_access = '["employe", "hris"]';
                $user->uac_status = $request->status ?? 1;
                $user->uac_location = $request->location;
                $user->uac_departement = $request->departement;
                $user->uac_role = $request->role;
                if(empty($request->id)){
                    $user->created_by = Auth::id();
                    $user->company_id = Session::get('company_id');
                }
                if(!empty($request->password)){
                    $user->uac_password = $request->password;
                    $user->password = Hash::make($request->password);
                }

                $file = $request->file("image");
                if(!empty($file)){
                    $d = date("Ymd");
                    $newName = $d."_".$file->getClientOriginalName();
                    $_dir = str_replace("/", "\\", public_path("media/attachments"));
                    $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
                    $dir = str_replace("\\", "/", $dir);
                    if($file->move($dir, $newName)){
                        $user->user_img = "media/attachments/$newName";
                    }
                }

                $user->save();

                $toast = [
                    "message" => empty($request->id) ? "User has been added" : "User change has been saved",
                    "bg" => "bg-success"
                ];
            } else {
                $user = User::find($request->id);

                $user->delete();

                $toast = [
                    "message" => "User has been deleted",
                    "bg" => "bg-danger"
                ];
            }
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }
}
