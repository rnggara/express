<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Personel_history;

class PersonelRequest extends Controller
{
    function index(Request $request){

        $histories = Personel_history::where("company_id", Session::get('company_id'))
            // ->where("must_approved_by", Auth::id())
            ->where("type", "!=", "reactive")
            ->orderBy("id", "desc")
            ->get();

        $req_data['location'] = \App\Models\Asset_wh::office()->where("company_id", Session::get('company_id'))->pluck("name", "id");
        $req_data['job_grade'] = \App\Models\Kjk_comp_job_grade::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $req_data['job_level'] = \App\Models\Kjk_comp_job_level::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $req_data['employee_status'] = \App\Models\Personel_employee_status::where("company_id", Session::get("company_id"))->pluck("label", "id");
        $req_data['workgroup'] = \App\Models\Att_workgroup::where("company_id", Session::get("company_id"))->pluck("workgroup_name", "id");
        $req_data['position'] = \App\Models\Kjk_comp_position::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $req_data['departement'] = \App\Models\Kjk_comp_departement::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $req_data['acting_position'] = \App\Models\Kjk_comp_position::pluck("name", "id");


        $data_request = [];

        $list = [];

        $myApproval = $histories->where("must_approved_by", Auth::id())->whereNotNull("approved_at")->pluck("id")->toArray();

        foreach($histories as $item){
            $list[$item->personel_id][strtotime($item->created_at)][] = $item;
        }

        $items = [];

        foreach($list as $pId => $item){
            foreach($item as $d => $val){
                $approved = collect($val)->whereNotNull("approved_at");

                $status = $approved->count() == count($val) ? "approved" : ($approved->count() == 0 ? "need_approval" : "approved_by_some");
                $data_request[$pId][$status][$d] = $val;
            }
        }

        // foreach($histories as $item){
        //     if(empty($item->approved_at)){
        //         $data_request[$item->personel_id]['need_approval'][strtotime($item->created_at)][] = $item;
        //     } else {
        //         $data_request[$item->personel_id]['approved'][strtotime($item->created_at)][] = $item;
        //     }
        // }


        $reg = \App\Models\Hrd_employee::whereIn("id", array_keys($data_request))
            ->get();

        if($request->a == "show"){
            $req = Personel_history::where("personel_id", $request->id)
                ->where('created_at', date("Y-m-d H:i:s", $request->date))
                ->get();

            $approval = $request->approval ?? "";

            $emp_id = $request->id;
            $date = $request->date;

            $view = view("_personel.request.detail", compact('req', 'req_data', 'approval', 'emp_id', 'date'));

            return json_encode([
                "view" => $view->render()
            ]);
        }

        return view("_personel.request.index", compact('histories', 'data_request', 'reg', 'req_data', 'myApproval'));
    }

    function cancel_request($id, Request $request){
        $date = date("Y-m-d H:i:s", $request->date);
        $reg = \App\Models\Att_employee_registration::find($id);
        $req = Personel_history::where("personel_id", $reg->emp_id)
            ->where("created_at", $date)
            ->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Request has been deleted",
                "bg" => "bg-danger"
            ]
        ]);
    }

    function action(Request $request){
        $date = date("Y-m-d H:i:s", $request->date);
        $req = Personel_history::where("personel_id", $request->emp_id)
            ->where("must_approved_by", Auth::id())
            ->where("created_at", $date)
            ->get();

        foreach($req as $item){
            $item->approved_at = date("Y-m-d H:i:s");
            $item->approved_by = Auth::id();
            $item->save();
        }

        $this->implement($req);

        return redirect()->back()->with([
            "toast" => [
                "message" => "Request has been approved",
                "bg" => "bg-success"
            ]
        ]);
    }

    function cronPersonel(){
        $reqData = Personel_history::whereNull("implement_at")->get();
        $req = [];
        foreach($reqData as $item){
            $req[$item->personel_id][date("YmdHis", strtotime($item->created_at))][] = $item;
        }

        foreach($req as $item){
            foreach($item as $_data){
                $data = collect($_data);
                $approved = $data->whereNotNull("approved_at");
                if($data->count() == $approved->count()){
                    $this->implement($_data);
                }
            }
        }
    }

    function implement($request){
        foreach($request as $item){
            if(date("Y-m-d") >= $item->start_date){
                $item->implement_at = date("Y-m-d H:i:s");

                $key = $item->type;

                $pr = $item->emp;

                if($key == "job_type"){
                    $pr->job_type_id = $item->new;
                    $pr->job_type_mutation_date = $item->start_date;
                    $pr->save();
                } elseif($key == "job_level"){
                    $pr->job_level_id = $item->new;
                    $pr->job_level_mutation_date = $item->start_date;
                    $pr->save();
                } elseif($key == "job_grade"){
                    $pr->job_grade_id = $item->new;
                    $pr->job_grade_mutation_date = $item->start_date;
                    $pr->save();
                } elseif($key == "employee_status"){
                    $pr->employee_status_id = $item->new;
                    $pr->employee_status_mutation_start = $item->start_date;
                    $pr->employee_status_mutation_end = $item->end_date;
                    $pr->save();
                } elseif($key == "position"){
                    $pr->position_id = $item->new;
                    $pr->position_mutation_date = $item->start_date;
                    $pr->save();
                } elseif($key == "workgroup"){
                    $pr->reg->workgroup ?? $item->new;
                    $pr->save();
                } elseif($key == "departement"){
                    $user = $pr->user ?? [];
                    if(!empty($user)){
                        $user->uac_departement = $item->new;
                        $user->uac_departement_mutation_date = $item->start_date;
                        $user->save();
                    }
                } elseif($key == "location"){
                    $user = $pr->user ?? [];
                    if(!empty($user)){
                        $user->uac_location = $item->new;
                        $user->uac_location_mutation_date = $item->start_date;
                        $user->save();
                    }
                } elseif($key == "acting_position"){
                    $pr->acting_position_id = $item->new;
                    $pr->save();
                }

                $item->implement_by = Auth::id() ?? "cron";
                $item->save();
            }
        }
    }
}
