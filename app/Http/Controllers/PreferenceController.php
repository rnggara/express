<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Asset_wh;
use App\Models\Division;
use App\Models\Asset_item;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Preference_ppe;
use App\Models\Template_files;
use App\Models\Finance_br_config;
use App\Models\Preference_config;
use Illuminate\Support\Facades\DB;
use App\Models\Pref_activity_point;
use App\Models\Pref_email;
use App\Models\Pref_request_reject;
use App\Models\Pref_tax_config;
use App\Models\Pref_uom;
use Illuminate\Support\Facades\Auth;
use App\Models\Pref_work_environment;
use App\Models\Preference_bank_priority;
use App\Models\Master_banks;
use App\Models\Finance_coa;
use App\Models\Master_variables_model;
use App\Models\Pref_annual_holiday;
use App\Models\User;

class PreferenceController extends Controller
{
    public function index($id_company){
        $id = base64_decode($id_company);

        $isPref = Preference_config::where('id_company', $id)->first();
        $comp = ConfigCompany::find($id);
        if ($isPref == null){
            $newPref = new Preference_config();
            if ($comp->id_parent != null){
                $parentPref = Preference_config::where('id_company', $comp->id_parent)->first();
                $newPref->penalty_amt = $parentPref->penalty_amt;
                $newPref->penalty_period = $parentPref->penalty_period;
                $newPref->penalty_start = $parentPref->penalty_start;
                $newPref->penalty_stop = $parentPref->penalty_stop;
                $newPref->period_start = $parentPref->period_start;
                $newPref->period_end = $parentPref->period_end;
                $newPref->absence_deduction = $parentPref->absence_deduction;
                $newPref->bonus_period = $parentPref->bonus_period;
                $newPref->thr_period = $parentPref->thr_period;
                $newPref->odorate = $parentPref->odorate;
                $newPref->overtime_period = $parentPref->overtime_period;
                $newPref->overtime_start = $parentPref->overtime_start;
                $newPref->overtime_amt = $parentPref->overtime_amt;
                $newPref->performa_period = $parentPref->performa_period;
                $newPref->performa_start = $parentPref->performa_start;
                $newPref->performa_end = $parentPref->performa_end;
                $newPref->performa_amt1 = $parentPref->performa_amt1;
                $newPref->performa_amt2 = $parentPref->performa_amt2;
                $newPref->performa_amt3 = $parentPref->performa_amt3;
                $newPref->performa_amt4 = $parentPref->performa_amt4;
                $newPref->performa_amt5 = $parentPref->performa_amt5;
                $newPref->approval_start = $parentPref->approval_start;
                $newPref->btl_col = $parentPref->btl_col;
                $newPref->wo_signature = null;
                $newPref->po_signature = null;
                $newPref->to_signature = null;
                $newPref->id_company = $id;
                $newPref->save();
            }
        }

        $preferences = Preference_config::where('id_company', $id)->first();
        $template_files = Template_files::where('company_id', $id)->get();


       $br = Division::all();
       $br_config = Finance_br_config::all();
       $br_status = array();
       foreach ($br_config as $item){
           $br_status[$item->id_division] = $item;
       }

        $company = ConfigCompany::where('id', $id)->first();

        $label = DB::table('pref_activity_label')->get();
        $action = DB::table('pref_activity_action')->get();

        $pref_action_point = Pref_activity_point::where('company_id', $company->id)->get();
        $data_point = array();
        foreach ($pref_action_point as $item){
            $data_point[$item->id_modul][$item->action] = $item->point;
        }


        for ($m=1; $m<=12; $m++) {
            $month[$m] = date('F', mktime(0,0,0,$m, 1, date('Y')));
        }

        $we = Pref_work_environment::whereIn("company_id", [$company->id, $company->id_parent])->get();

        $uoms = Pref_uom::whereIn("company_id", [$company->id, $company->id_parent])->get();

        $ppe = Preference_ppe::all();
        foreach($ppe as $item){
            $js = json_decode($item->items, true);
            $row = [];
            for ($i=0; $i < count($js); $i++) {
                $_item = Asset_item::find($js[$i]);
                if(!empty($_item)){
                    $col = [];
                    $col['id'] = $js[$i];
                    $col['text'] = "[$_item->item_code] $_item->name";
                    $row[] = $col;
                }
            }
            $item->item_arr = $row;
        }

        $warehouses = Asset_wh::whereIn('company_id', [$company->id,$company->id_parent])->get();

        $taxes = Pref_tax_config::orderBy('tax_name', 'desc')->get();

        $email = Pref_email::where("company_id", $company->id)->get();

        $bank = Preference_bank_priority::orderBy('priority')->get();

        $list_banks = Master_banks::all();

        $tc = Finance_coa::get();

        $users = User::where("id", 1)
            ->orWhere('company_id', $company->id)->get();

        $variables = Master_variables_model::where("company_id", Session::get('company_id'))->get();

        return view('preference.index',[
            'company' => $company,
            'preferences' => $preferences,
            'template_files' => $template_files,
            'br_list' => $br,
            'months' => $month,
            'label' => $label,
            'action' => $action,
            'data_point' => $data_point,
            'we' => $we,
            'br_pref' => $br_status,
            "ppe" => $ppe,
            'wh' => $warehouses,
            'taxes' => $taxes,
            'email' => $email,
            'uoms' => $uoms,
            "bank" => $bank,
            'list_banks' => $list_banks,
            'tc' => $tc,
            'users' => $users,
            'variables' => $variables
        ]);
    }

    public function uom_store($id = null, Request $request){
        $uom = new Pref_uom();
        if(!empty($id)){
            $uom = Pref_uom::find($id);
        } else {
            $uom->company_id = $request->id_company;
        }

        $uom->name = $request->name;
        $uom->save();

        return redirect()->back();
    }

    public function uom_delete($id){
        $uom = Pref_uom::find($id);
        $uom->delete();

        return redirect()->back();
    }

    public function savePref(Request $request){
        $pref = Preference_config::where('id_company',$request['id_company'])->first();
        if ($pref === null){
            $pref = new Preference_config();
            $pref->id_company = $request['id_company'];
        }

        if (isset($request['saveAttendance'])){
            $pref->penalty_amt = $request['penalty_amt'];
            $pref->penalty_period = $request['penalty_period'];
            $pref->penalty_start = $request['penalty_start'];
            $pref->penalty_stop = $request['penalty_stop'];
            $pref->save();
        }
        if (isset($request['savePayrollPeriod'])){
            // $pref->period_start = $request['period_start'];
            // $pref->period_end = $request['period_end'];
            // $pref->period_archive = $request['period_archive'];
            // $pref->umr = str_replace(",", "", $request['umr']);
            // $pref->payroll_tc = $request['tc'];
            $pref->clock_in = $request['clock_in'];
            $pref->clock_out = $request['clock_out'];
            $pref->break_out = $request['break_out'];
            $pref->break_in = $request['break_in'];
            $pref->save();
        }
        if (isset($request['saveDeduction'])){
            $pref->absence_deduction = $request['absence_deduction'];
            $pref->save();
        }

        if(isset($request->addVariable)){
            $variables = Master_variables_model::findOrNew($request->id);
            if($request->type == "delete"){
                $variables->delete();
            } else {
                $variables->var_type = $request->type;
                $variables->parameter_name = $request->parameter_name;
                $variables->parameter_type = $request->parameter_type;
                $variables->company_id = $request['id_company'];
                $variables->save();
            }
        }


        return redirect()->route('preference',['id_company'=>base64_encode($request['id_company'])]);
    }

    function store_pr(Request $request){
        $pref = Preference_config::where('id_company', $request->id)->first();
        $pref->performa_period = $request->performa_period;
        $pref->performa_start = $request->performa_start;
        $pref->performa_end = $request->performa_end;
        $pref->performa_amt1 = $request->performa_amt1;
        $pref->performa_amt2 = $request->performa_amt2;
        $pref->performa_amt3 = $request->performa_amt3;
        $pref->performa_amt4 = $request->performa_amt4;
        $pref->performa_amt5 = $request->performa_amt5;

        Session::put('company_performa_period', $request->performa_period);
        Session::put('company_performa_start', $request->performa_start);
        Session::put('company_performa_end', $request->performa_end);
        Session::put('company_performa_amt1', $request->performa_amt1);
        Session::put('company_performa_amt2', $request->performa_amt2);
        Session::put('company_performa_amt3', $request->performa_amt3);
        Session::put('company_performa_amt4', $request->performa_amt4);
        Session::put('company_performa_amt5', $request->performa_amt5);

        $pref->save();

        return redirect()->route('preference', base64_encode($request->id));
    }

    function store_we(Request $request){
        $pref = new Pref_work_environment();
        $pref->name = $request->name;
        $pref->tag = $request->tag;
        $pref->formula = $request->formula;
        $pref->created_by = Auth::user()->username;
        $pref->company_id = Session::get('company_id');
        $pref->save();
        return redirect()->back();
    }

    function delete_we($id){
        Pref_work_environment::find($id)->delete();
        return redirect()->back();
    }

    function find_we($id){
        $we = Pref_work_environment::find($id);
        return view('preference.working_environment_edit', compact('we'));
    }

    function update_we(Request $request){
        $pref = Pref_work_environment::find($request->id_we);
        $pref->name = $request->name;
        $pref->tag = $request->tag;
        $pref->formula = $request->formula;
        $pref->updated_by = Auth::user()->username;
        $pref->save();
        return redirect()->back();
    }

    function store_ac(Request $request){
        $point = $request->point;
        foreach ($point as $key => $item){
            foreach ($item as $keyItem => $value){
                $iPoint = Pref_activity_point::where('company_id', Session::get('company_id'))
                    ->where('id_modul', $key)
                    ->where('action', $keyItem)
                    ->first();
                if (empty($iPoint)){
                    $nPoint = new Pref_activity_point();
                    $nPoint->id_modul = $key;
                    $nPoint->action = $keyItem;
                    $nPoint->point = $value;
                    $nPoint->created_by = Auth::user()->username;
                    $nPoint->company_id = Session::get('company_id');
                    $nPoint->save();
                } else {
                    $nPoint = Pref_activity_point::find($iPoint->id);
                    $nPoint->point = $value;
                    $nPoint->updated_by = Auth::user()->username;
                    $nPoint->save();
                }
            }
        }

        return redirect()->back();
    }

    function br_update($id){
        $iConf = Finance_br_config::where('id_division', $id)->first();
        if (!empty($iConf)){
            if ($iConf->unlocked == 1){
                $iConf->unlocked = 0;
            } else {
                $iConf->unlocked = 1;
            }
            $iConf->save();
        } else {
            $conf = new Finance_br_config();
            $conf->id_division = $id;
            $conf->unlocked = 1;
            $conf->save();
        }

        return redirect()->back();
    }

    function signatureSave(Request $request){
        $pref = Preference_config::find($request->id_company);
        if(isset($request->quotation)){
            $file = $request->file("image");
            $data = json_decode($pref->qo_signature, true);
            $_name = User::find($request->name);
            $data['id'] = $request->name;
            $data['name'] = $_name->name;
            $data['position'] = $request->position;
            if(!empty($file)){
                $upload_dir = "images/signature";
                $file_name = "SIGNATURE_QO_".str_replace(" ", "_", $file->getClientOriginalName());
                if ($file->move(public_path($upload_dir), $file_name)) {
                    $data['image'] = $file_name;
                }
            }
            $pref->qo_signature = json_encode($data);
            $pref->save();
            return redirect()->back();
        } else {
            $data = $request->data;
            if (is_object(json_decode($pref->po_signature)) && !empty($pref->po_signature)) {
                $jsPO = json_decode($pref->po_signature);
            } else {
                $jsPO = (object) [];
            }

            if (is_object(json_decode($pref->wo_signature)) && !empty($pref->wo_signature)) {
                $jsWO = json_decode($pref->wo_signature);
            } else {
                $jsWO = (object) [];
            }
            foreach ($data as $key => $powo) {
                if (isset($powo['min'])) {
                    foreach ($powo['min'] as $i => $value) {
                        if (!empty($value)) {
                            $min = str_replace(",", "", $value);
                        } else {
                            $min = "";
                        }

                        if ($key == "po") {
                            $jsPO->min[$i] = $min;
                        } else {
                            $jsWO->min[$i] = $min;
                        }
                    }
                }

                if (isset($powo['max'])) {
                    foreach ($powo['max'] as $i => $value) {
                        if (!empty($value)) {
                            $max = str_replace(",", "", $value);
                        } else {
                            $max = "";
                        }

                        if ($key == "po") {
                            $jsPO->max[$i] = $max;
                        } else {
                            $jsWO->max[$i] = $max;
                        }
                    }
                }

                if (isset($powo['bypass'])) {
                    foreach ($powo['bypass'] as $i => $value) {
                        if (!empty($value)) {
                            $bypass = 1;
                        } else {
                            $bypass = "";
                        }

                        if ($key == "po") {
                            $jsPO->bypass[$i] = $bypass;
                        } else {
                            $jsWO->bypass[$i] = $bypass;
                        }
                    }
                }

                if (isset($powo['img'])) {
                    foreach ($powo['img'] as $i => $value) {
                        $file = $value;
                        $upload_dir = "images/signature";
                        $file_name = "SIGNATURE_".str_replace(" ", "_", $file->getClientOriginalName());
                        if ($file->move(public_path($upload_dir), $file_name)) {
                            if ($key == "po") {
                                $jsPO->img[$i] = $file_name;
                            } else {
                                $jsWO->img[$i] = $file_name;
                            }
                        }
                    }
                }
            }

            $pref->po_signature = json_encode($jsPO);
            $pref->wo_signature = json_encode($jsWO);
        }

        if ($pref->save()) {
            return redirect()->back();
        }
    }

    function thr(Request $request){
        $pref = Preference_config::find($request->id);
        $pref->thr_period = $request->thr_period;
        $pref->save();

        if(Session::get('company_id') == $pref->id_company){
            Session::put('company_thr_period', $pref->thr_period);
        }

        return redirect()->back();
    }

    function test_save(Request $request){
        $pref = Preference_config::where("id_company", $request->id_comp)->first();
        $pref->delay_retake_test = $request->days;
        $pref->save();

        if($request->id_comp == Session::get("company_id")){
            Session::put("delay_retake_test", $pref->delay_retake_test);
        }

        return redirect()->back();
    }

    function accounting_save(Request $request){
        $pref = Preference_config::where("id_company", $request->id_comp)->first();
        $pref->transaction_name = $request->t_name;
        $pref->transaction_initial = $request->t_initial;
        $pref->save();

        Session::put('company_tc_name', $pref->transaction_name);
        Session::put('copmany_tc_initial', $pref->transaction_initial);

        return redirect()->back();
    }

    function asset_save(Request $request){
        $exclude = $request->exclude;
        $pref = Preference_config::where("id_company", $request->id_comp)->first();
        $pref->asset_exclude_from_parent = $exclude ?? 0;
        $pref->save();

        if(Session::get('company_id') == $request->id_comp){
            Session::put("company_asset_exclude", $pref->asset_exclude_from_parent);
        }

        return redirect()->back();
    }

    function ppe_add(Request $request){
        $ppe = Preference_ppe::find($request->id);
        if(empty($ppe)){
            $ppe = new Preference_ppe();
            $ppe->created_by = Auth::user()->username;
        } else {
            $ppe->updated_by = Auth::user()->username;
        }

        $ppe->description = $request->desc;
        $ppe->qty = $request->qty;
        $ppe->items = json_encode($request->items);

        $ppe->save();
        return redirect()->back();
    }

    function ppe_storage(Request $request){
        $pre = Preference_config::where("id_company", $request->id)->first();
        $pre->ppe_wh = $request->storage;
        $pre->save();
        return redirect()->back();
    }

    function request_view(Request $request){
        $pref = Pref_request_reject::select("*")->get();
        $row = [];
        foreach($pref as $item){
            $row[$item->modul][] = $item;
        }

        if($request->ajax()){
            if($request->a == "edit"){
                $pref = Pref_request_reject::where("modul", $request->mod)
                    ->where("approval", $request->app)
                    ->first();


                return view("preference._edit_request_pref", compact("pref"));
            }
        }

        return view("preference.request_view", compact("row"));
    }

    function request_post(Request $request){
        $pref = Pref_request_reject::find($request->id);
        $pref->yellow = $request->yellow;
        $pref->red = (empty($request->red)) ? NULL : $request->red;
        $pref->reject = (empty($request->reject)) ? NULL : $request->reject;
        $yellow_status = $request->yellow_status;
        $red_status = $request->red_status;
        $reject_status = $request->reject_status;
        if(!empty($yellow_status)){
            $pref->yellow_status = 1;
        } else {
            $pref->yellow_status = 0;
        }
        if(!empty($red_status)){
            $pref->red_status = 1;
        } else {
            $pref->red_status = 0;
        }
        if(!empty($reject_status)){
            $pref->reject_status = 1;
        } else {
            $pref->reject_status = 0;
        }

        $pref->save();

        return redirect()->back();
    }

    function submit_tax($act = null, Request $request){
        $tax = new Pref_tax_config();
        if($act == "edit"){
            $tax = Pref_tax_config::find($request->id);
        }
        if($act == "delete"){
            $tax = Pref_tax_config::find($request->id);
            $tax->delete();
            return redirect()->back();
        }
        $tax->tax_name = $request->tax_name;
        $tax->formula = $request->formula;
        if($request->wapu && $request->wapu == 1){
            $tax->is_wapu = 1;
        } else {
            $tax->is_wapu = 0;
        }

        if($request->print && $request->print == 1){
            $tax->is_print = 1;
        } else {
            $tax->is_print = 0;
        }

        $tax->conflict_with = (!empty($request->conflict)) ? json_encode($request->conflict) : NULL;
        $tax->company_id = 1;
        $tax->save();
        return redirect()->back();
    }

    function add_email(Request $request){
        $mail = new Pref_email();
        $mail->name = $request->name;
        $mail->email = $request->email;
        $mail->status = 1;
        $mail->email_type = json_encode($request->type);
        $mail->company_id = $request->id_comp;
        $mail->save();

        return redirect()->back();
    }

    function delete_email($id){
        Pref_email::find($id)->delete();

        return redirect()->back();
    }

    function update_email(Request $request){
        $mail = Pref_email::find($request->id);
        $mail->name = $request->name;
        $mail->email = $request->email;
        $status = $request->status;
        $mail->status = (!empty($status)) ? 1 : 0;
        $mail->email_type = json_encode($request->type);
        $mail->save();

        return redirect()->back();
    }

    function automatic_print($id){
        $pref = Preference_config::find($id);
        if($pref->is_refresh == 0) {
            $pref->is_refresh =1;
        } else {
            $pref->is_refresh =0;
        }

        $pref->save();

        return redirect()->back();
    }

    function view_bank($id){
        $company = ConfigCompany::find($id);
        $bank = Master_banks::all();

        return view("preference._bank_list", compact("bank", "company"));
    }

    function add_bank(Request $request){
        $priority = 1;

        $bank = new Master_banks();
        if(isset($request->id_bank)){
            $bank = Master_banks::find($request->id_bank);
        } else {
            $bank->company_id = $request->id_comp;
            // $_bank = Master_banks::where("company_id", $request->id_comp)->orderBy("priority", "desc")->first();
            // if(!empty($_bank)){
            //     $priority = $_bank->priority + 1;
            // }

            // $bank->priority = $priority;
        }
        $bank->bank_name = $request->bank_name;
        $bank->bank_code = $request->bank_code;
        $bank->save();

        return redirect()->back();
    }

    function delete_bank($id){
        $bank = Master_banks::find($id);
        $bank->delete();

        return redirect()->back();
    }

    function add_priority(Request $request){
        $priority = 1;
        $bank = new Preference_bank_priority();
        $_bank = Preference_bank_priority::orderBy("priority", "desc")->first();
        if(!empty($_bank)){
            $priority = $_bank->priority + 1;
        }
        $bank->bank_id = $request->bank_prior;
        $bank->priority = $priority;

        $bank->save();
        return redirect()->back();
    }

    function priority_bank($type, $id){
        $bank = Preference_bank_priority::find($id);

        if($type == "up"){
            $_bank = Preference_bank_priority::where("priority", "<", $bank->priority)
                ->orderBy("priority", "desc")
                ->first();
        } else {
            $_bank = Preference_bank_priority::where("priority", ">", $bank->priority)
                ->orderBy("priority", "asc")
                ->first();
        }

        $pr1 = $bank->priority;
        $pr2 = $_bank->priority;

        $bank->priority = $pr2;
        $_bank->priority = $pr1;

        $bank->save();
        $_bank->save();

        return redirect()->back();
    }

    function holiday_save(Request $request){
        $holiday = Pref_annual_holiday::find($request->id);
        if($request->act == "add"){
            $holiday = new Pref_annual_holiday();
            $holiday->company_id = $request->company_id;
        }
        $holiday->title = $request->title;
        $holiday->start_date = $request->start_date;
        $holiday->end_date = $request->end_date." 23:59:59";
        $holiday->save();

        return redirect()->back();
    }

    function holiday_event($id){
        $events = Pref_annual_holiday::where("company_id", $id)
            ->whereYear("start_date", date("Y"))
            ->get();

        $data = [];
        foreach($events as $item){
            $col = [];
            $col['id'] = $item->id;
            $col['title'] = $item->title;
            $col['start'] = $item->start_date;
            $col['end'] = $item->end_date;
            $col['className'] = "fc-event-solid-info fc-event-light";
            $col['description'] = $item->title;
            $data[] = $col;
        }

        return json_encode($data);
    }

    function holiday_delete($id){
        $holiday = Pref_annual_holiday::find($id);
        $holiday->delete();

        return redirect()->back();
    }
}
