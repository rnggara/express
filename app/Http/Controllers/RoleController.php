<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleController extends Controller
{
	public function store(Request $request)
	{
		$role = new Role;
		$role->id_company = base64_decode($request->coid);
		$role->name = $request->name;
		$role->desc = $request->desc;
        if(isset($request->no_probation)){
            $role->no_probation = 1;
        }
		$role->save();

		return redirect()->back();
	}

	public function update($id, Request $request)
	{
        $submit = $request->submit;
        $role = Role::find($id);
        if($submit == "career_path"){
            $cnum = 1;
            $nnum = Role::select("career_num")
                ->orderBy("career_num", "desc")
                ->first();
            if(!empty($nnum)){
                $cnum = $nnum->career_num + 1;
            }
            $show = $request->career;
            $role->show_career = $show ?? null;
            if(empty($role->career_num)){
                $role->career_num = $cnum;
            }
            if(empty($show)){
                $role->career_num = null;
            }
        } else {
            $role->id_company = base64_decode($request->coid);
            $role->name = $request->name;
            $role->desc = $request->desc;
            if(isset($request->no_probation)){
                $role->no_probation = 1;
            } else {
                $role->no_probation = 0;
            }
        }
		$role->save();

		return redirect()->back();
	}

	public function delete($id, Request $request)
	{
		Role::find($id)->delete();

		return redirect()->back();
	}
}
