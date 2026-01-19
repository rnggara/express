<?php

namespace App\Http\Controllers;

use App\Models\Career_path;
use App\Models\ConfigCompany;
use App\Models\Role;
use Illuminate\Http\Request;

class CareerPathController extends Controller
{
    function index($id){
        $company = ConfigCompany::find($id);

        $role = Role::where("show_career", 1)
            ->orderBy("career_num")
            ->get();

        $career_path = Career_path::orderBy("order_num")
            ->get();
        return view("company.cp.index", compact("company", "role", "career_path"));
    }

    function add(Request $request){
        $orderNum = 1;
        $last = Career_path::orderBy("order_num", "desc")
            ->first();
        if(!empty($last)){
            $orderNum = $last->order_num + 1;
        }

        $cp = new Career_path();
        $cp->order_num = $orderNum;
        $cp->role_id = $request->level;
        $cp->grade = $request->grade;
        $cp->min_edu = ucwords($request->min_edu);
        $cp->yos = $request->yos;
        $cp->salary = str_replace(",", "", $request->salary);
        $cp->house = str_replace(",", "", $request->house);
        $cp->health = str_replace(",", "", $request->health);
        $cp->position = str_replace(",", "", $request->position);
        $cp->transport = str_replace(",", "", $request->transport);
        $cp->meal = str_replace(",", "", $request->meal);
        $cp->performance_bonus = str_replace(",", "", $request->performance_bonus);
        if($request->performance_bonus == "-" || $request->performance_bonus == ""){
            $cp->performance_bonus = -1;
        }
        $cp->company_id = $request->company_id;
        $cp->save();
        return redirect()->back();
    }

    function edit($id){
        $cp = Career_path::find($id);

        $company = ConfigCompany::find($cp->company_id);

        $role = Role::where("show_career", 1)
            ->orderBy("career_num")
            ->get();

        $career_path = Career_path::orderBy("order_num")
            ->get();
        return view("company.cp._edit", compact("company", "role", "career_path", "cp"));
    }

    function get($id){
        $cp = Career_path::find($id);

        return json_encode($cp);
    }

    function update(Request $request){
        $cp = Career_path::find($request->cp_id);
        $cp->role_id = $request->level;
        $cp->grade = $request->grade;
        $cp->min_edu = ucwords($request->min_edu);
        $cp->yos = $request->yos;
        $cp->salary = str_replace(",", "", $request->salary);
        $cp->house = str_replace(",", "", $request->house);
        $cp->health = str_replace(",", "", $request->health);
        $cp->position = str_replace(",", "", $request->position);
        $cp->transport = str_replace(",", "", $request->transport);
        $cp->meal = str_replace(",", "", $request->meal);
        $cp->performance_bonus = str_replace(",", "", $request->performance_bonus);
        if($request->performance_bonus == "-" || $request->performance_bonus == ""){
            $cp->performance_bonus = -1;
        }
        $cp->company_id = $request->company_id;
        $cp->save();
        return redirect()->back();
    }

    function delete($id){
        $cp = Career_path::find($id);
        $cnum = $cp->order_num;

        $next = Career_path::where("order_num", ">", $cnum)
            ->get();
        if(count($next) > 0){
            foreach($next as $item){
                $item->order_num = $cnum++;
                $item->save();
            }
        }

        $cp->delete();

        return redirect()->back();
    }

    function order($type, $id){
        $cp = Career_path::find($id);
        if($type == "up"){
            $onum = $cp->order_num - 1;
        } else {
            $onum = $cp->order_num + 1;
        }
        $target = Career_path::where("order_num", $onum)->first();
        $target->order_num = $cp->order_num;
        $target->save();

        $cp->order_num = $onum;
        $cp->save();

        return redirect()->back();
    }
}
