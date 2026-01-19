<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kjk_custom_role;
use App\Models\Kjk_role_privilege as rPriv;
use App\Models\Kjk_user_role as uPriv;
use App\Models\Action;
use App\Models\Module;
use App\Models\User;

class KjkCustomRole extends Controller
{
    function add(Request $request){

        $role = Kjk_custom_role::findOrNew($request->id);

        $role->name = $request->name;
        $role->descriptions = $request->descriptions;
        $role->company_id = base64_decode($request->coid);
        $role->save();

        return redirect()->back();
    }

    function delete(Request $request){
        $role = Kjk_custom_role::find($request->id);
        if(!empty($role)){
            $role->delete();
        }

        return redirect()->back();
    }

    function privilege($id){
        $role = Kjk_custom_role::find($id);

        $actions = Action::pluck("name", "id");
        $modules = Module::pluck("name", "id");

        $priv = rPriv::where("role_id", $id)->get();

        $rolePriv = [];
        foreach($priv as $item){
            $rolePriv[$item->module_id][$item->action_id] = 1;
        }

        $companyId = $role->company_id;

        return view("company.role_priv", compact("role", "actions", "modules", "priv", "companyId", "rolePriv"));
    }

    function user($id){
        $user = User::find($id);

        $roles = Kjk_custom_role::pluck("name", "id");

        $priv = uPriv::where("user_id", $user->id)->pluck("role_id")->toArray();

        return view("company.role_user_priv", compact("user", 'roles', "priv"));
    }

    function user_privilege(Request $request){
        $uid = $request->id;
        $priv = uPriv::where("user_id", $uid)->count();
        if($priv > 0){
            uPriv::where("user_id", $uid)->delete();
        }

        $data = [];
        $roles = $request->roles ?? [];
        foreach($roles as $item){
            $row = [
                "user_id" => $uid,
                "role_id" => $item
            ];
            $data[] = $row;
        }

        uPriv::insert($data);

        return redirect()->back();
    }

    function update_privilege(Request $request){

        $roleId = $request->id;
        $priv = rPriv::where("role_id", $roleId)->count();
        if($priv > 0){
            rPriv::where("role_id", $roleId)->delete();
        }

        $privileges = $request->privilege ?? [];

        $insert = [];

        foreach($privileges as $idModule => $item){
            foreach($item as $idAction => $val){
                $row = [
                    "role_id" => $roleId,
                    "module_id" => $idModule,
                    "action_id" => $idAction
                ];

                $insert[] = $row;
            }
        }

        rPriv::insert($insert);

        return redirect()->back();
    }
}
