<?php

namespace App\Http\Controllers;
use Mpdf\Mpdf;
use App\Models\Module;
use App\Models\Hrd_bonus;
use App\Models\Hrd_config;
use App\Models\Pref_email;
use App\Models\Users_zakat;
use Illuminate\Support\Str;
use App\Models\Hrd_employee;
use App\Models\Hrd_overtime;
use App\Models\Hrd_sanction;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Hrd_salary_share;
use App\Models\Hrd_bonus_payment;
use App\Models\Hrd_employee_loan;
use App\Models\Hrd_employee_type;
use App\Models\Preference_config;
use App\Models\Hrd_salary_archive_new;
use App\Models\Hrd_salary_remarks;
use App\Models\Finance_util_salary;
use App\Models\General_travel_order;
use App\Models\Hrd_employee_history;
use Illuminate\Support\Facades\Auth;
use App\Models\Hrd_employee_loan_payment;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Models\Preference_bank_priority;
use App\Models\Pref_annual_holiday;
use App\Models\Master_banks;
use App\Models\Hrd_employee_bank;
use DB;
use Illuminate\Support\Facades\Session;

class HrdNewPayrollController extends Controller
{
    function signName($loc){
        switch($loc){
            case "staff":
            case "konsultan":
            case "whbin":
            case "whcil":
                $ret[0] = "HRD Manager"; $ret[1] = "Finance Manager"; $ret[2] = "Operation Director";
                break;
            case "field":
                $ret[0] = "Operation Manager"; $ret[1] = "Operation Director"; $ret[2] = "President Director";
                break;
            case "manager":
                $ret[0] = "Finance Director"; $ret[1] = "Operation Director";
                break;
            default:
                $ret[0] = "Operation Manager"; $ret[1] = "Finance Director"; $ret[2] = "Operation Director";
                break;
        }
        return $ret;
    }

    public function tableSignature($arr){
        $var1 = "<table width='827' border='1' style='border-collapse:collapse'><tr>";
        if(count($arr) == 3) { $var2 = "<td colspan='2' align='center'>Approved By </td>";
            $var4 = "
            <td width='25%' align='center'><br /><br /><br />$arr[2]<br />Date: ____ </td>
            <td width='25%' align='center'><br /><br /><br />$arr[1]<br />Date: ____</td>";
        }
        if(count($arr) == 2) { $var2 = "<td align='center'>Approved By </td>";
            $var4 = "<td width='25%' align='center'><br /><br /><br />$arr[1]<br />Date: ____ </td>";
        }
        $var3 = "</tr><tr>";
        $var5 = "</tr></table>";
        $var6 = $var1.$var2.$var3.$var4.$var5;
        return $var6;
    }

    function index($code = null, Request $request){
        for ($m=1; $m<=12; $m++) {
            $data['month'][$m] = date('F', mktime(0,0,0,$m, 1, date('Y')));
        }
        $id_companies = [];
        $comp = ConfigCompany::find(Session::get('company_id'));
        if (empty($comp->id_parent)) {
            $childCompany = ConfigCompany::select("id")
                ->where('id_parent', $comp->id)
                ->get();
            foreach($childCompany as $ids){
                $id_companies[] = $ids->id;
            }
        } else {
            $id_companies[] = $comp->id_parent;
        }

        $id_companies[] = Session::get('company_id');

        $emp = Hrd_employee::where('company_id', Session::get('company_id'))
            ->whereNull('expel')
            ->get();

        $data['emp_type'] = [];
        foreach ($emp as $item){
            $data['emp_type'][$item->emp_type][] = $item->id;
        }

        $data['type'] = Hrd_employee_type::whereIn('company_id', $id_companies)
            ->where('company_exclude', 'not like', '%"'.$comp->id.'"%')
            ->orWhereNull("company_exclude")
            ->get();

        $startyear = date('Y', strtotime('-10 years'));
        for ($i = 0; $i < 20; $i++){
            $data['years'][$i] = $startyear;
            $startyear++;
        }

        $data['code'] = $code;
        $_month = "";
        $year = "";
        $_type = null;
        $type_name = "";

        if(!empty($code)){
            $share = Hrd_salary_share::where("code", $code)
                ->first();
            if(!empty($share)){
                $period = explode("-", $share->period);
                $_month = $period[0];
                $year = $period[1];
                $_type = $share->type;
                if($share->type != "all"){
                    $t = Hrd_employee_type::find($share->type);
                    $type_name = $t->name;
                }
                $accessed = (empty($share->accessed_by)) ? [] : json_decode($share->accessed_by, true);
                $user_name = Auth::user()->username;
                if(!in_array($user_name, $accessed)){
                    $accessed[] = $user_name;
                }

                $share->accessed_by = json_encode($accessed);
                $share->save();
            }
            if($share->company_id != Session::get("company_id")){
                return redirect()->route("forbidden")->with("msg", "You must access from the correct company");
            }
        }

        $data['_month'] = $_month;
        $data['year'] = $year;
        $data['_type'] = $_type;
        $data['type_name'] = $type_name;

        $data['mod'] = Module::all()->pluck('name', 'id');

        return view('payroll_new.index', $data);
    }

    function export(Request $request){
        return view('payroll_new.export', [
            'type' => $request->type,
            'month' => $request->month,
            'years' => $request->years,
        ]);
    }

    function get_icon($last, $now, $period){
        $icon = "";
        $prev_period = date("F Y", strtotime($period. "-1 month"));
        $tooltip = "$prev_period : Rp. ".number_format($last, 2);
        if($last > $now){
            $icon = "<button type='button' class='btn btn-icon btn-xs btn-primary print-hide mr-1' data-toggle='tooltip' data-trigger='focus' title='$tooltip'><i class='fa fa-arrow-down'></i></button>";
        } elseif($last < $now){
            $icon = "<button type='button' class='btn btn-icon btn-xs btn-danger print-hide mr-1' data-toggle='tooltip' data-trigger='focus' title='$tooltip'><i class='fa fa-arrow-up'></i></button>";
            // $icon = "<i class='fa fa-arrow-up print-hide cursor-pointer text-danger' data-toggle='tooltip' data-trigger='click' data-theme='dark' title='$tooltip'></i>";
        }

        return $icon;
    }

    function get_text_class($last, $now){
        $class = "dark";
        if($last > $now){
            $class = "primary";
        } elseif($last < $now){
            $class = "danger";
        }

        return $class;
    }

    function generate_payroll(){
        $m = date("n");
        $y = date("Y");
        $id_companies = Preference_config::where("is_refresh", 1)
            ->get();
        $companies = ConfigCompany::whereIn('id', $id_companies->pluck("id_company"))->get();
        $comp_name = $companies->pluck("company_name", "id");

        $emp = Hrd_employee::WhereNull("expel")
            ->get();
        $emp_n = $emp->pluck("emp_name", 'id');

        $peri = $y."-".sprintf("%02d", $m)."%";

        $emp_expel = Hrd_employee::WhereNull("expel")
            ->whereRaw("(expel like '$peri' or expel like '$peri')")
            ->get();
        $eexpel = [];
        foreach($emp_expel as $item){
            $col['name'] = $item->emp_name;
            if(!empty($item->expel) && empty($item->expel)){
                $col['expel'] = date("d F Y", strtotime($item->expel));
            } else if(!empty($item->expel)){
                $col['expel'] = date("d F Y", strtotime($item->expel));
            }
            $eexpel[$item->company_id][] = $col;
        }

        $emp_comp = [];
        foreach($emp as $em){
            if($em->emp_type != ""){
                $emp_comp[$em->company_id][$em->emp_type][] = $em->id;
            }
        }

        $emp_type = Hrd_employee_type::all();
        $emp_type_name = $emp_type->pluck("name", 'id');
        $type_print = [];
        foreach($emp_type as $item){
            $is_print = json_decode($item->is_print, true);
            if(!empty($is_print)){
                foreach($is_print as $compid => $va){
                    $type_print[$compid][] = $item->id;
                }
            }
        }

        $print = [];
        foreach($companies as $item){
            if(isset($type_print[$item->id])){
                $print[$item->id] = $type_print[$item->id];
            } else {
                if(isset($type_print[$item->id_parent])){
                    $print[$item->id] = $type_print[$item->id_parent];
                }
            }
        }

        foreach($print as $idcompany => $type){
            foreach($type as $item){
                $this->InsertToArchive($idcompany, $item, $m, $y);
            }
        }

        $emp_archive = [];
        $emp_archive_new = [];
        $archive = Hrd_salary_archive_new::where("archive_period", $m."-".$y)->get();
        $emp_proportional = $archive->pluck('proportional', 'emp_id');
        foreach($archive as $la){
            $lasal = base64_decode($la->salary) + $la->allowance_office;
            $lawages = base64_decode($la->wages);
            $lanonwages = base64_decode($la->non_wages);
            $la_total_sal = $lawages + $lanonwages + $la->ovt_nom + $la->field_nom + $la->wh_nom + $la->odo_nom + $la->odo_dgr + $la->odo_swt + $la->fld_dgr + $la->fld_swt + $la->performance_bonus + $la->allow_jkk + $la->allow_jkm + $la->allow_jht + $la->bonus + $la->allow_jp + $la->allow_bpjs_kes;
            $la_thp_total = $la_total_sal + $la->thr - $la->lateness - $la->deduction - $la->deduc_jkk - $la->deduc_jkm - $la->deduc_jht - $la->deduc_jht_tk - $la->deduc_jp - $la->deduc_jp_tk - $la->deduc->bpjs_kes - $la->deduc_bpjs_tk - $la->deduc_pph21 - $la->performance_bonus;
            $emp_archive[$la->emp_id] = $la_thp_total;
            if($la->proportional > 0){
                $emp_archive_new[$la->emp_id] = $la_thp_total;
            }
        }

        $report = [];
        foreach($emp_comp as $compid => $item){
            foreach($item as $type => $e){
                $total_type = 0;
                $total_type_new = [];
                $expel_emp = [];
                foreach($e as $val){
                    if(isset($emp_archive[$val])){
                        $total_type += $emp_archive[$val];
                    }

                    if(isset($emp_archive_new[$val])){
                        $col['name'] = $emp_n[$val];
                        $col['proportional'] = $emp_proportional[$val];
                    }
                }

                if(isset($eexpel[$compid])){
                    $expel_emp = $eexpel[$compid];
                }

                if(isset($emp_type_name[$type])){
                    $report[$compid]['summary'][$emp_type_name[$type]] = $total_type;
                    $report[$compid]['new'] = $total_type_new;
                    $report[$compid]['expel'] = $expel_emp;
                }
            }
        }

        foreach($report as $compid => $item){
            $cname = $comp_name[$compid];
            $view = "<html><body>";
            $view .= "<center><h1>Payroll Report $cname</h1></center>";
            $view .= "<center><h3>Periode April 2022</h3></center>";
            $view .= "<table>";
            $total_salary = 0;
            foreach($item['summary'] as $sumtype => $sum){
                $view .= "<tr><td>".ucwords($sumtype)."</td><td>:</td><td>Rp. ".number_format($sum, 2)."</td></tr>";
                $total_salary += $sum;
                $util = Finance_util_salary::where('position', ucwords($sumtype))
                            ->where('salary_date', 'like', $y."-".sprintf("%02d", $m)."%")
                            ->where('company_id', $compid)
                            ->where("isThr", 0)
                            ->first();
                if (empty($util)){
                    $_pref = Preference_config::where("id_company", Session::get('company_id'))->first();
                    $nUtil = new Finance_util_salary();
                    $sdate = $y."-".$m."-28";
                    $nUtil->salary_date = $sdate;
                    $nUtil->currency = "IDR"; //default
                    $nUtil->amount = $sum;
                    $nUtil->plan_date = $sdate;
                    $nUtil->status = "waiting";
                    $nUtil->position = ucwords($sumtype);
                    $nUtil->company_id = $compid;
                    $nUtil->created_by = Auth::user()->username;
                    $nUtil->isThr = 2;
                    $nUtil->tc = (empty($_pref->payroll_tc)) ? null : $_pref->payroll_tc;
                    $nUtil->save();
                } else {
                    $util->amount = $sum;
                    $util->save();
                }
            }
            $view .= "<tr><td>Total Salary</td><td>:</td><td>Rp. ".number_format($total_salary, 2)."</td></tr>";
            $view .= "</table>";

            $view .= "<table>";
            $view .= "<tr><td>New Employee</td><td></td><td></td></tr>";
            if(count($item['new']) > 0){
                foreach($item['new'] as $sum){
                    $view .= "<tr><td>".ucwords($sum['name'])."</td><td>:</td><td>Rp. ".number_format($sum['proportional'], 2)."</td></tr>";
                }
            } else {
                $view .= "<tr><td>-</td><td></td><td></td></tr>";
            }
            $view .= "</table>";

            $view .= "<table>";
            $view .= "<tr><td>Deleted Employee</td><td></td><td></td></tr>";
            if(count($item['expel']) > 0){
                foreach($item['expel'] as $sum){
                    $view .= "<tr><td>".ucwords($sum['name'])."</td><td>:</td><td>Rp. ".ucwords($sum['expel'])."</td></tr>";
                }
            } else {
                $view .= "<tr><td>-</td><td></td><td></td></tr>";
            }
            $view .= "</table>";

            $view .= "</body></html>";

            $subject = "Payroll Report of $cname";

            $receipent = Pref_email::where("company_id", $compid)
                ->where("status", 1)
                ->where("email_type", "like", '%"1"%')
                ->get();
            $sent_to = [];
            foreach($receipent as $item){
                $col['name'] = $item->name;
                $col['email'] = $item->email;
                $sent_to[] = $col;
            }

            $email = \Helper_function::instance()->send_mail($subject, $sent_to, $view);
        }
    }

    function InsertToArchive($company_id, $request_type, $request_month, $request_years){
        $config_company = Preference_config::where("id_company", $company_id)->first();
        $myComp = ConfigCompany::find($company_id);
        $id_companies = $company_id;
        $t = $request_type;
        $m = $request_month;
        $y = $request_years;

        $pref = Preference_config::where('id_company', $id_companies)->get();
        $prefCount = $pref->count();
        $now = date('Y-n-d');

        if ($prefCount >0){
            $period_end = $pref[0]->period_end;
            $period_start = $pref[0]->period_start;
        } else {
            if (session()->has('company_period_end') && session()->has('company_period_start')){
                $period_end = $config_company['period_end'];
                $period_start = $config_company['period_start'];
            } else {
                $period_end = 27;
                $period_start = 28;
            }
        }

        $thr_period = explode("\r\n",$config_company['thr_period']);
        if($t == "all"){
            $emp = Hrd_employee::where('expel', null)
                ->whereNull('freeze')
                ->where('company_id', $id_companies)
                ->orderBy('emp_name')
                ->get();
        } else {
            $emp = Hrd_employee::where('emp_type', $t)
                ->where('expel', null)
                ->whereNull('freeze')
                ->where('company_id', $id_companies)
                ->orderBy('emp_name')
                ->get();
        }

        $emp_name = [];
        $emp_pos = [];
        $emp_bank = [];
        $emp_type = [];
        $type_emp = [];
        $emp_comp = [];

        foreach ($emp as $key => $value) {
            $emp_name[$value->id] = $value->emp_name;
            $emp_pos[$value->id] = $value->emp_position;
            $emp_bank[$value->id] = $value->bank_acct;
            $emp_type[] = $value->id;
            $type_emp[$value->emp_type][] = $value->id;
            $emp_comp[$value->id] = $value->company_id;
        }

        $eType = Hrd_employee_type::all();
        $detType = [];
        foreach ($eType as $value){
            $detType[$value->id] = $value;
        }


        $emp_his = Hrd_employee_history::where('activity', 'in')
            ->where('company_id', $id_companies)
            ->get();

        foreach ($emp_his as $key => $value) {
            $act_date[$value->company_id][$value->emp_id] = $value->act_date;
        }

        $sign = $this->signName($t);

        $period_start_date = $y."-".sprintf('%02d', $m-1)."-".$period_start;
        $_y = $y;
        $_m = $m - 1;
        if($m-1 == 0){
            $period_start_date = ($y-1)."-12-".$period_start;
            $_y = $y-1;
            $_m = 12;
        }
        $period_end_date = $y."-".sprintf('%02d', $m)."-".$period_end;
        if($period_end == 31){
            $period_end_date = date("Y-m-t", strtotime("-1 month"));
        }
        $period_4 = $y."-".sprintf('%02d', $m)."-". ($period_end + 1);

        $last_archive = Hrd_salary_archive_new::where("archive_period", ($_m)."-$_y")
            ->where("company_id", $company_id)
            ->get();
        $emp_last_archive = [];
        foreach($last_archive as $item){
            $emp_last_archive[$item->emp_id] = $item;
        }

        $ovt = Hrd_overtime::where('company_id', $id_companies)
            ->whereBetween('ovt_date', [$period_start_date, $period_end_date])
            ->get();
        $ovt_date = [];
        foreach ($ovt as $key => $value) {
            $time_in[$value->emp_id][] = $value->time_in;
            $time_out[$value->emp_id][] = $value->time_out;
            $ovt_date[$value->emp_id][] = $value->ovt_date;
        }

        $period['start'] = $period_start_date;
        $period['end'] = $period_end_date;

        $to = General_travel_order::whereNotNull('action_time')
            ->where(function($query) use($period){
                $query->whereRaw("(departure_dt >= '".$period['start']."' and return_dt <= '".$period['end']."')");
                $query->orWhereRaw("(return_dt >= '".$period['start']."' and departure_dt <= '".$period['end']."')");
            })
            ->where('company_id', $company_id)
            ->where('status', 0)
            ->get();

        foreach ($to as $key => $value) {
            if ($value->departure_dt < $period_start_date){
                $d2 = date('Y-m-d', strtotime($period_start_date." -1 day"));
            } else {
                $d2 = $value->departure_dt;
            }

            if ($value->return_dt < $period_end_date){
                $d1 = $value->return_dt;
            } else {
                $d1 = $period_end_date;
            }
            // $d1 = ($value->return_dt >= $period_end_date) ? date("Y-m-d", strtotime($period_end_date." +1 day")) : $value->return_dt;
            // $d2 = ($value->departure_dt <= $period_start_date) ? date('Y-m-d', strtotime($period_start_date." -1 day")) : $value->departure_dt;

            $sum = date_diff(date_create($d1), date_create($d2));

            if ($value->travel_type == "reg") {
                if ($value->location_rate == "SWT") {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_swt[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                } elseif ($value->location_rate == "DGR") {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_dgr[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                } else {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_day[$value->employee_id][] = $sum->format("%a");
                            break;
                        case "wh" :
                            $wh_day[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                }
            } elseif ($value->travel_type = "odo") {
                if (empty($value->location_rate)) {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_day[$value->employee_id][] = $sum->format("%a");
                    }
                } elseif ($value->location_rate == "SWT") {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_swt[$value->employee_id][] = $sum->format("%a");
                    }
                } elseif ($value->location_rate == "DGR") {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_dgr[$value->employee_id][] = $sum->format("%a");
                    }
                }
            }
        }

        // dd($fld_swt, $fld_day);

        $whereLoan = $y."-".sprintf("%02d", $m);

        $loan = Hrd_employee_loan::whereNotNull("approved_at")->where("company_id", $company_id)->get();
        foreach($loan as $item){
            $loanEmp[$item->emp_id][] = $item->id;
        }


        $loan_det = Hrd_employee_loan_payment::where('date_of_payment', 'like', $whereLoan."%")->get();
        foreach($loan_det as $item){
            $loanDet[$item->loan_id][] = $item->amount;
        }

        $bonus = Hrd_bonus::all();
        $bonusEmp = [];
        foreach ($bonus as $value) {
            $bonusEmp[$value->emp_id][] = $value->id;
        }

        $bonus_pay = Hrd_bonus_payment::whereBetween('date_of_payment', [$period_start_date, $period_end_date])->get();
        foreach ($bonus_pay as $value) {
            $bonusPay[$value->bonus_id][] = $value->amount;
        }

        $foot['sum_salary'] = 0;
        $foot['sum_ovt'] = 0;
        $foot['sum_fld'] = 0;
        $foot['sum_wh'] = 0;
        $foot['sum_odo'] = 0;
        $foot['sum_tk'] = 0;
        $foot['sum_ks'] = 0;
        $foot['sum_jshk'] = 0;
        $foot['sum_tot_salary'] = 0;
        $foot['sum_sunction'] = 0;
        $foot['sum_absence'] = 0;
        $foot['sum_loan'] = 0;
        $foot['sum_ded_tk'] = 0;
        $foot['sum_ded_ks'] = 0;
        $foot['sum_ded_jshk'] = 0;
        $foot['sum_bonus'] = 0;
        $foot['sum_thr'] = 0;
        $foot['sum_pph21'] = 0;
        $foot['sum_prop'] = 0;
        $foot['sum_thp'] = 0;
        $foot['sum_voucher'] = 0;
        $foot['sum_ovt'] = 0;
        $foot['sum_sanction'] = 0;

        $rangeStart = $y."-".($m-1)."-".$period_start;
        $rangeEnd = $y."-".$m."-".$period_end;
        if($period_end == 31){
            $rangeEnd = date("Y-m-t", strtotime("-1 month"));
        }
        $pro_n_day = date("t", strtotime($rangeEnd));

        $empType = Hrd_employee_type::withTrashed()->get();
        $eType = [];
        foreach ($empType as $item){
            $eType[$item->id] = $item->name;
        }

        if (empty($config_company['period_archive'])) {
            $period_archive = "06";
        } else {
            $period_archive = $config_company['period_archive'];
        }

        $period_archive = "06";

        $p_archive = $request_years."-".$m."-".$period_archive;

        $btnArch = false;
        if(strtotime($now) > strtotime(date("Y")."-".date("n")."-".$period_archive)){
            if($m == date("n")){
                $btnArch = true;
            }
        }

        $_t = Hrd_employee_type::find($t);
        $empsalid = [];

        $archive = $archive = Hrd_salary_archive_new::where('archive_period', $m."-".$y)
            ->where('company_id', $id_companies)->get();
        $isArchive = [];
        foreach($archive as $item){
            $isArchive[$item->emp_id] = $item;
        }

        $holidays = Pref_annual_holiday::whereBetween("start_date", [$period_start_date, $period_end_date])
            ->get();

        $hd = 0;
        foreach($holidays as $item){
            if($item->start_date != $item->end_date){
                $d1 = date_create($item->start_date);
                $d2 = date_create($item->end_date);
                $diff = date_diff($d1, $d2);
                $a = $diff->format("%a");
                $hd += $a + 1;
            } else {
                $hd += 1;
            }
        }

        $nonwagesday = $pro_n_day - $hd;

        foreach ($emp as $key => $value) {
            if (!isset($isArchive[$value->id])){
                $empid = $value->id;

                $row = new Hrd_salary_archive_new();

                $salary_emp = base64_decode($value->n_basic_salary ?? "MA==");
                $sunction = 0;
                $absence_deduct = 0;
                $bonus_amt = 0;
                $ln_amt = 0;
                $hours = 0;

                $sanction = Hrd_sanction::where('emp_id', $value->id)
                    ->whereNotNull('approved_by')
                    ->whereBetween('sanction_date',[$period_start_date,$period_end_date])
                    ->get();
                foreach ($sanction as $key => $valSanc){
                    $sunction += floatval($valSanc->sanction_amount);
                }

                $allow_jkk = ($value->allow_bpjs_tk == "") ? 0 : $value->allow_bpjs_tk;
                $allow_bpjs_kes = ($value->allow_bpjs_kes == "") ? 0 : $value->allow_bpjs_kes;
                $allow_jp = ($value->allow_jp == "") ? 0 : $value->allow_jp;

                $foot['sum_tk'] += $allow_jkk;
                $foot['sum_ks'] += $allow_bpjs_kes;
                $foot['sum_jshk'] += $allow_jp;

                $deduc_bpjs_tk = ($value->deduc_bpjs_tk == "") ? 0 : $value->deduc_bpjs_tk;
                $deduc_bpjs_kes = ($value->deduc_bpjs_kes == "") ? 0 : $value->deduc_bpjs_kes;
                $deduc_jkk = ($value->deduc_jkk == "") ? 0 : $value->deduc_jkk;
                $deduc_pph21 = ($value->deduc_pph21 == "") ? 0 : $value->deduc_pph21;

                $foot['sum_ded_tk'] += $deduc_bpjs_tk;
                $foot['sum_ded_ks'] += $deduc_bpjs_kes;
                $foot['sum_ded_jshk'] += $deduc_jkk;


                $sal = $salary_emp + base64_decode($value->n_house_allow ?? "MA==") + base64_decode($value->n_health_allow ?? "MA==") + base64_decode($value->n_position_allow ?? "MA==");
                $transport_allow = base64_decode($value->n_transport_allow ?? "MA==") * $nonwagesday;
                $meal_allow = base64_decode($value->n_meal_allow ?? "MA==") * $nonwagesday;
                $nonwages = $transport_allow + $meal_allow;
                $pb = base64_decode($value->n_performance_bonus ?? "MA==");

                if (!empty($time_in[$value->id])) {
                    for ($i=0; $i < count($time_in[$value->id]); $i++) {
                        $otdate = date("N", strtotime($ovt_date[$value->id][$i]));
                        $jamout = $time_out[$value->id][$i];
                        if(!empty($jamout)){
                            if($jamout == "00:00:00"){
                                $jamout = "24:00:00";
                            }
                            if($otdate >= 6){
                                $diff = strtotime($jamout) - strtotime($time_in[$value->id][$i]);
                                $hours += $diff;
                            } else {
                                $ovtStartTime = $config_company['penalty_stop'];
                                if(!empty($ovtStartTime)){
                                    if(strtotime($time_in[$value->id][$i]) >= strtotime($ovtStartTime)){
                                        $diff = strtotime($jamout) - strtotime($time_in[$value->id][$i]);
                                    } else {
                                        $jammasuk = strtotime($config_company['penalty_start']);
                                        if(strtotime($time_in[$value->id][$i]) <= $jammasuk){
                                            $diff = strtotime($jamout) - strtotime($time_in[$value->id][$i]);;
                                        } else {
                                            $diff = strtotime($jamout) - strtotime($ovtStartTime);
                                        }
                                    }
                                    $hours += $diff;
                                }
                            }
                        }
                    }
                }

                $hours = $hours < 0 ? 0 : $hours;

                $ovt_total = $value->overtime * ceil(($hours / 3600));

                $foot['sum_ovt'] += $ovt_total;
                $whday = (empty($wh_day[$value->id])) ? "0" : array_sum($wh_day[$value->id]);
                $fldday = (empty($fld_day[$value->id])) ? "0" : array_sum($fld_day[$value->id]);
                $fldswtday = (empty($fld_swt[$value->id])) ? "0" : array_sum($fld_swt[$value->id]);
                $fldgrday = (empty($fld_dgr[$value->id])) ? "0" : array_sum($fld_dgr[$value->id]);

                $fld = $value->fld_bonus * $fldday;
                $flddgr = ($value->fld_bonus + 25000) * $fldgrday;
                $fldswt = ($value->fld_bonus + 50000) * $fldswtday;

                $foot['sum_fld'] += $fld + $flddgr + $fldswt;

                $wh = $value->wh_bonus * $whday;

                $foot['sum_wh'] += $wh;

                $ododay = (empty($odo_day[$value->id])) ? "0" : $odo_day[$value->id];
                $odoswtday = (empty($odo_swt[$value->id])) ? "0" : $odo_swt[$value->id];
                $odogrday = (empty($odo_dgr[$value->id])) ? "0" : $odo_dgr[$value->id];

                $odo = $value->odo_bonus * $ododay;
                $ododgr = ($value->odo_bonus + 25000) * $odogrday;
                $odoswt = ($value->odo_bonus + 50000) * $odoswtday;

                $foot['sum_odo'] += $odo + $ododgr + $odoswt;

                if(isset($loanEmp[$value->id])){
                    foreach($loanEmp[$value->id] as $lEmp){
                        if (isset($loanDet[$lEmp])){
                            $ln_amt += array_sum($loanDet[$lEmp]);
                        }
                    }
                }

                $foot['sum_loan'] += $ln_amt;

                if (isset($bonusEmp[$value->id])) {
                    foreach ($bonusEmp[$value->id] as $bEmp) {
                        if (isset($bonusPay[$bEmp])) {
                            $bonus_amt += array_sum($bonusPay[$bEmp]);
                        }
                    }
                }

                $yearly_bonus = $value->yearly_bonus * $salary_emp + $value->fx_yearly_bonus;
                $bonus_only = $value->yearly_bonus * $salary_emp;

                // Datatable
                $row->emp_id = $value->id;
                $row->archive_period = $m."-".$y;
                $row->wages = base64_encode($sal);
                $row->non_wages = base64_encode($nonwages);
                $row->ovt_rate = $value->overtime;
                $row->ovt_nom = $ovt_total;
                $row->field_rate = $value->fld_bonus;
                $row->field_nom = $fld;
                $row->wh_rate = $value->wh_bonus;
                $row->wh_nom = $wh;
                $row->odo_rate = $value->odo_bonus;
                $row->odo_nom = $odo;
                $row->performance_bonus = base64_encode($pb);
                $row->deduction = $ln_amt;
                $row->lateness = $sunction;
                $row->bonus = $bonus_amt;
                $isThr = sprintf("%02d", $m)."-".$y;

                $date_join = (isset($act_date[$value->company_id][$empid])) ? $act_date[$value->company_id][$empid] : "0000-00-00";
                $mntDiff = 0;
                $dIn = 0;
                if ($date_join != "0000-00-00") {
                    $dateIn = date_create($date_join);
                    $dateNow = date_create(date("Y-m-d", strtotime($period_end_date." +1 day")));
                    $diff = date_diff($dateIn, $dateNow);
                    $mntDiff = $diff->format("%y-%m");
                    $dIn = date('d', strtotime($date_join));
                }

                $wPeriod = explode("-", $mntDiff);

                $mnt = 0;
                if(count($wPeriod) > 0 && $wPeriod[0] >= 1){
                    $mnt = 12 * $wPeriod[0];
                }

                $mnt += end($wPeriod);

                if($dIn > $period_start){
                    // $mnt -= 1;
                }

                $prob = [];
                $vcr = [];
                $_thr = [];
                $_bonusArr = [];
                if(isset($detType[$value->emp_type])){
                    $prob = json_decode($detType[$value->emp_type]['no_probation'], true);
                    $vcr = json_decode($detType[$value->emp_type]['with_voucher'], true);
                    $_thr = json_decode($detType[$value->emp_type]['disable_thr'], true);
                    $_bonusArr = json_decode($detType[$value->emp_type]['with_bonus'], true);
                }

                $minThr = 3;
                $maxThr = 15;

                if(isset($prob[$value->company_id])){
                    if($prob[$value->company_id] == 1){
                        $minThr = 0;
                        $maxThr = 12;
                    }
                } elseif (isset($prob[$myComp->id_parent])){
                    if($prob[$myComp->id_parent] == 1){
                        $minThr = 0;
                        $maxThr = 12;
                    }
                }

                $with_voucher = 0;
                if(isset($vcr[$value->company_id])){
                    if($vcr[$value->company_id]){
                        $with_voucher = $value->voucher;
                    }
                } elseif (isset($vcr[$myComp->id_parent])){
                    if($vcr[$myComp->id_parent] == 1){
                        $with_voucher = $value->voucher;
                    }
                }

                $with_bonus = 0;
                if(isset($_bonusArr[$value->company_id])){
                    if($_bonusArr[$value->company_id]){
                        $with_bonus = $bonus_amt;
                    }
                } elseif (isset($_bonusArr[$myComp->id_parent])){
                    if($_bonusArr[$myComp->id_parent] == 1){
                        $with_bonus = $bonus_amt;
                    }
                }


                $disable_thr = 0;
                if(isset($_thr[$value->company_id])){
                    if($_thr[$value->company_id]){
                        $disable_thr = $_thr[$value->company_id];
                    }
                } elseif (isset($_thr[$myComp->id_parent])){
                    if($_thr[$myComp->id_parent] == 1){
                        $disable_thr = $_thr[$myComp->id_parent];
                    }
                }

                if($mnt <= $minThr){
                    $thr_num = 0;
                } elseif($mnt > $minThr && $mnt < $maxThr){
                    $thr_num = (($mnt - $minThr) / 12) * ($sal + $value->allowance_office + $with_voucher + $with_bonus);
                } elseif($mnt >= $maxThr) {
                    $thr_num = ($sal + $value->allowance_office + $with_voucher + $with_bonus);
                }

                if (in_array($isThr, $thr_period)){
                    $thr_total = ($disable_thr == 0) ? $thr_num : 0;
                } else {
                    $thr_total = 0;
                }
                $row->thr = $thr_total;
                $row->category = $value->emp_position;
                $row->fld_dgr = $flddgr;
                $row->fld_swt = $fldswt;
                $row->odo_dgr = $ododgr;
                $row->odo_swt = $odoswt;
                $row->allow_jkk = $allow_jkk;
                $row->allow_bpjs_kes = $allow_bpjs_kes;
                $row->allow_jp = $allow_jp;
                $row->deduc_bpjs_tk = $deduc_bpjs_tk;
                $row->deduc_bpjs_kes = $deduc_bpjs_kes;
                $row->deduc_jkk = $deduc_jkk;
                $row->deduc_pph21 = $deduc_pph21;

                $total_sal = $sal + $nonwages + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $pb + $value->allow_jkk + $value->allow_bpjs_kes + $value->allow_jp;

                $thp = $total_sal - $absence_deduct - $ln_amt - $value->deduc_bpjs_tk - $value->deduc_bpjs_kes - $value->deduc_jkk - $value->deduc_pph21 - $pb;
                $xthp = $thp - $fld - $wh - $odo - $ododgr - $odoswt - $fldswt - $flddgr;
                $date = (isset($act_date[$value->company_id][$empid])) ? $act_date[$value->company_id][$empid] : "0000-00-00";
                $pro_day = round((strtotime($date) - strtotime($rangeStart)) / 86400,0);
                $in_date = $date;
                $zero_day = (strtotime($rangeEnd) - strtotime($date)) / 86400;
                // if($empid == 1425){
                //     dd($pro_day, $pro_n_day, $xthp, $value->emp_name, $salary_emp);
                // }
                if($pro_day > 0 && $pro_day <= $pro_n_day)
                {
                    $pro_basis = $pro_n_day;
                    $pro_decrement = ($pro_day) / $pro_basis * $xthp;
                }
                //kalau hari masuk = start month gaji, pengurangan = gaji = ZERO gaji.
                elseif($pro_day == 0)
                {
                    // $pro_decrement = $xthp;
                    if(date('d',strtotime($in_date)) == 16)
                    {
                        $pro_decrement = 0;
                    }
                    else
                    {
                        $pro_decrement = $xthp;
                    }
                }
                //tidak ada pemotongan
                else
                {
                    $pro_decrement = 0;
                }

                //kalau tgl masuk baru lebih baru dari range2. ZERO gaji
                if($zero_day < 0)
                {
                    $pro_decrement = $xthp;
                }

                if($pro_day >= 0 && $pro_day <= 30) {
                    $total_decrement = $pro_decrement;
                } elseif($zero_day <= 0) {
                    $total_decrement = $pro_decrement;
                } else {
                    $total_decrement = 0;
                }

                $row->proportional = $pro_decrement; //Proportional
                $row->company_id = $value->company_id;

                $empsalid[$value->id] = $thp - $row->proportional;

                if($date_join <= $period_end_date && $date_join != "0000-00-00"){
                    $row->save();

                    // $amount_zakat = base64_decode($row->wages) + base64_decode($row->non_wages) + $row->ovt_nom + $row->field_nom + $row->wh_nom + $pb + $row->fld_dgr + $row->fld_swt +  $row->odo_dgr + $row->odo_swt + $row->allow_bpjs_kes + $row->allow_jkk + $row->allow_jp + $row->thr;
                    // $amount_zakat -= $row->deduction + $row->lateness + $row->deduc_bpjs_tk + $row->deduc_bpjs_kes + $row->deduc_jkk + $row->deduc_pph21 + $row->deduc_proportional;

                    // $zakat = new Users_zakat();
                    // $zakat->emp_id = $value->id;
                    // $zakat->company_id = $value->company_id;
                    // $zakat->description = "Zakat Mal ".date("F Y", strtotime($y."-".$m."-".$t));
                    // $zakat->salary_period = $y."-".sprintf("%02d", $m);
                    // $zakat->paid = 0;
                    // $zakat->amount = $amount_zakat * (2.5 / 100);
                    // $zakat->save();
                }
            }
        }
    }

    function show(Request $request){
        $id_companies = Session::get('company_id');
        $t = $request->type;
        $m = $request->month;
        $y = $request->years;

        $pref = Preference_config::where('id_company', $id_companies)->get();
        $myComp = ConfigCompany::find($id_companies);
        $prefCount = $pref->count();
        $now = date('Y-m-d');

        if ($prefCount >0){
            $period_end = $pref[0]->period_end;
            $period_start = $pref[0]->period_start;
        } else {
            if (session()->has('company_period_end') && session()->has('company_period_start')){
                $period_end = Session::get('company_period_end');
                $period_start = Session::get('company_period_start');
            } else {
                $period_end = 27;
                $period_start = 28;
            }
        }

        $thr_period_emp = explode("\n", Session::get('company_thr_period'));
        $thr_period = [];
        foreach($thr_period_emp as $item){
            $thr_period[] = str_replace("\r", "", $item);
        }
        if($t == "all"){
            $emp = Hrd_employee::where('expel', null)
                ->whereNull("freeze")
                ->where('company_id', $id_companies)
                ->orderBy('emp_name')
                ->get();
        } else {
            $emp = Hrd_employee::where('emp_type', $t)
                ->where('expel', null)
                ->whereNull("freeze")
                ->where('company_id', $id_companies)
                ->orderBy('emp_name')
                ->get();
        }

        $emp_name = [];
        $emp_pos = [];
        $emp_bank = [];
        $emp_type = [];
        $type_emp = [];
        $emp_comp = [];
        $temp = [];

        foreach ($emp as $key => $value) {
            $emp_name[$value->id] = $value->emp_name;
            $emp_pos[$value->id] = $value->emp_position;
            $emp_bank[$value->id] = $value->bank_acct;
            $emp_type[] = $value->id;
            $type_emp[$value->emp_type][] = $value->id;
            $emp_comp[$value->id] = $value->company_id;
            $temp[$value->id] = $value->emp_type;
        }

        $eType = Hrd_employee_type::all();
        $detType = [];
        foreach ($eType as $value){
            $detType[$value->id] = $value;
        }


        $emp_his = Hrd_employee_history::where('activity', 'in')
            ->where('company_id', $id_companies)
            ->get();

        foreach ($emp_his as $key => $value) {
            $act_date[$value->company_id][$value->emp_id] = $value->act_date;
        }

        $sign = $this->signName($t);

        $period_start_date = $y."-".sprintf('%02d', $m-1)."-".$period_start;
        $_y = $y;
        $_m = $m - 1;
        if($m-1 == 0){
            $period_start_date = ($y-1)."-12-".$period_start;
            $_y = $y-1;
            $_m = 12;
        }
        $period_end_date = $y."-".sprintf('%02d', $m)."-".$period_end;
        if($period_end == 31){
            $period_end_date = date("Y-m-t", strtotime("-1 month"));
        }
        $period_4 = $y."-".sprintf('%02d', $m)."-". ($period_end + 1);

        $last_per = $_y."-".sprintf("%02d", $_m)."-".$period_start_date;

        $last_archive = Hrd_salary_archive_new::where("archive_period", ($_m)."-$_y")
            ->where("company_id", Session::get("company_id"))
            ->get();
        $emp_last_archive = [];
        foreach($last_archive as $item){
            $emp_last_archive[$item->emp_id] = $item;
        }

        $ovt = Hrd_overtime::where('company_id', $id_companies)
            ->whereBetween('ovt_date', [$period_start_date, $period_end_date])
            ->get();
        $ovt_date = [];
        foreach ($ovt as $key => $value) {
            $time_in[$value->emp_id][] = $value->time_in;
            $time_out[$value->emp_id][] = $value->time_out;
            $ovt_date[$value->emp_id][] = $value->ovt_date;
        }

        $period['start'] = $period_start_date;
        $period['end'] = $period_end_date;

        $to = General_travel_order::whereNotNull('action_time')
            ->where(function($query) use($period){
                $query->whereRaw("(departure_dt >= '".$period['start']."' and return_dt <= '".$period['end']."')");
                $query->orWhereRaw("(return_dt >= '".$period['start']."' and departure_dt <= '".$period['end']."')");
            })
            ->where('company_id', Session::get('company_id'))
            ->where('status', 0)
            ->get();



        foreach ($to as $key => $value) {
            if ($value->departure_dt < $period_start_date){
                $d2 = date('Y-m-d', strtotime($period_start_date." -1 day"));
            } else {
                $d2 = $value->departure_dt;
            }

            if ($value->return_dt < $period_end_date){
                $d1 = $value->return_dt;
            } else {
                $d1 = $period_end_date;
            }
            // $d1 = ($value->return_dt >= $period_end_date) ? date("Y-m-d", strtotime($period_end_date." +1 day")) : $value->return_dt;
            // $d2 = ($value->departure_dt <= $period_start_date) ? date('Y-m-d', strtotime($period_start_date." -1 day")) : $value->departure_dt;

            $sum = date_diff(date_create($d1), date_create($d2));

            if ($value->travel_type == "reg") {
                if ($value->location_rate == "SWT") {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_swt[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                } elseif ($value->location_rate == "DGR") {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_dgr[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                } else {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_day[$value->employee_id][] = $sum->format("%a");
                            break;
                        case "wh" :
                            $wh_day[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                }
            } elseif ($value->travel_type = "odo") {
                if (empty($value->location_rate)) {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_day[$value->employee_id][] = $sum->format("%a");
                    }
                } elseif ($value->location_rate == "SWT") {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_swt[$value->employee_id][] = $sum->format("%a");
                    }
                } elseif ($value->location_rate == "DGR") {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_dgr[$value->employee_id][] = $sum->format("%a");
                    }
                }
            }
        }

        $whereLoan = $y."-".sprintf("%02d", $m);

        $loan = Hrd_employee_loan::whereNotNull("approved_at")->where("company_id", Session::get("company_id"))->get();
        foreach($loan as $item){
            $loanEmp[$item->emp_id][] = $item->id;
        }


        $loan_det = Hrd_employee_loan_payment::where('date_of_payment', 'like', $whereLoan."%")->get();
        foreach($loan_det as $item){
            $loanDet[$item->loan_id][] = $item->amount;
        }

        $bonus = Hrd_bonus::all();
        $bonusEmp = [];
        foreach ($bonus as $value) {
            $bonusEmp[$value->emp_id][] = $value->id;
        }

        $bonus_pay = Hrd_bonus_payment::whereBetween('date_of_payment', [$period_start_date, $period_end_date])->get();
        foreach ($bonus_pay as $value) {
            $bonusPay[$value->bonus_id][] = $value->amount;
        }

        $foot['sum_salary'] = 0;
        $foot['sum_nonwages'] = 0;
        $foot['sum_ovt'] = 0;
        $foot['sum_fld'] = 0;
        $foot['sum_wh'] = 0;
        $foot['sum_odo'] = 0;
        $foot['sum_jkk'] = 0;
        $foot['sum_jkm'] = 0;
        $foot['sum_jht'] = 0;
        $foot['sum_jp'] = 0;
        $foot['sum_bpjs_kes'] = 0;
        $foot['sum_perf_bonus'] = 0;
        $foot['sum_comp_bonus'] = 0;
        $foot['sum_tot_salary'] = 0;
        $foot['sum_sunction'] = 0;
        $foot['sum_absence'] = 0;
        $foot['sum_loan'] = 0;
        $foot['sum_ded_perf_bonus'] = 0;
        $foot['sum_ded_comp_bonus'] = 0;
        $foot['sum_ded_jkk'] = 0;
        $foot['sum_ded_jkm'] = 0;
        $foot['sum_ded_jht'] = 0;
        $foot['sum_ded_jht_tk'] = 0;
        $foot['sum_ded_jp'] = 0;
        $foot['sum_ded_jp_tk'] = 0;
        $foot['sum_ded_bpjs_kes'] = 0;
        $foot['sum_ded_bpjs_tk'] = 0;
        $foot['sum_pph21'] = 0;
        $foot['sum_bonus'] = 0;
        $foot['sum_thr'] = 0;
        $foot['sum_prop'] = 0;
        $foot['sum_thp'] = 0;
        $foot['sum_voucher'] = 0;
        $foot['sum_ovt'] = 0;
        $foot['sum_sanction'] = 0;

        $rangeStart = $y."-".($m-1)."-".$period_start;
        $rangeEnd = $y."-".$m."-".$period_end;
        $pro_n_day = date("t", strtotime($rangeEnd));

        $empType = Hrd_employee_type::withTrashed()->get();
        $eType = [];
        foreach ($empType as $item){
            $eType[$item->id] = $item->name;
        }

        if (empty(Session::get('company_period_archive'))) {
            $period_archive = "10";
        } else {
            $period_archive = Session::get('company_period_archive');
        }

        $p_archive = $request->years."-".sprintf("%02d", $m)."-".$period_archive;

        $btnArch = false;
        if(strtotime($now) > strtotime(date("Y")."-".date("n")."-".$period_archive)){
            if($request->month == date("n")){
                $btnArch = true;
            }
        }

        $config_company = ConfigCompany::find(Session::get('company_id'));

        $_t = Hrd_employee_type::find($t);

        $bank_acct = Hrd_employee_bank::whereIn("emp_id", $emp->pluck("id"))
            ->get();

        $bank_pref = Master_banks::all();

        $bank_prior = Preference_bank_priority::orderBy('priority')->get();

        if ($now >= $p_archive) {
            $this->InsertToArchive(Session::get('company_id'), $t, $m, $y);

            if($t == "all"){
                $allemp = Hrd_employee::whereRaw("(expel is null or expel > '$period_end_date')")->where("company_id", $id_companies)->get();
            } else {
                $allemp = Hrd_employee::where('emp_type', $t)
                    ->whereRaw("(expel is null or expel > '$period_end_date')")
                    ->where("company_id", $id_companies)
                    ->get();
            }

            $emp_type = $allemp->pluck('id');

            $emp_arc = Hrd_salary_archive_new::where('archive_period', $m."-".$y)
                ->where('company_id', $id_companies)
                ->whereIn('emp_id', $emp_type) //wherein
                // ->orWhere('category', 'like', "%$_t->name%")
                ->get();

            // dd(\DB::getQueryLog(), $period_start_date);

            $emp_name = $allemp->pluck("emp_name", 'id');
            $emp_pos = $allemp->pluck("emp_position", 'id');
            $emp_bank = $allemp->pluck("bank_acct", 'id');
            $temp = $allemp->pluck("emp_type", 'id');
            $emp_comp = $allemp->pluck("company_id", 'id');

            $iNum = 1;

            if (count($emp_arc) > 0) {
                foreach ($emp_arc as $key => $value) { //loop
                    if(isset($emp_name[$value->emp_id])){
                        $sunction = 0;
                        $sanction = Hrd_sanction::where('emp_id', $value->emp_id)
                            ->whereNotNull('approved_by')
                            ->whereBetween('sanction_date',[$rangeStart,$rangeEnd])
                            ->get();

                        foreach ($sanction as $key => $valSanc){
                            $sunction += floatval($valSanc->sanction_amount);
                        }

                        $row = [];
                        $salary_emp = base64_decode($value->wages);
                        $nonwages = base64_decode($value->non_wages);

                        $allow_jkk = ($value->allow_jkk == "") ? 0 : $value->allow_jkk;
                        $allow_bpjs_kes = ($value->allow_bpjs_kes == "") ? 0 : $value->allow_bpjs_kes;
                        $allow_jp = ($value->allow_jp == "") ? 0 : $value->allow_jp;

                        $foot['sum_jkk'] += $allow_jkk;
                        $foot['sum_bpjs_kes'] += $allow_bpjs_kes;
                        $foot['sum_jp'] += $allow_jp;

                        $deduc_bpjs_tk = ($value->deduc_bpjs_tk == "") ? 0 : $value->deduc_bpjs_tk;
                        $deduc_bpjs_kes = ($value->deduc_bpjs_kes == "") ? 0 : $value->deduc_bpjs_kes;
                        $deduc_jkk = ($value->deduc_jkk == "") ? 0 : $value->deduc_jkk;

                        $foot['sum_ded_bpjs_tk'] += $deduc_bpjs_tk;
                        $foot['sum_ded_bpjs_kes'] += $deduc_bpjs_kes;
                        $foot['sum_ded_jkk'] += $deduc_jkk;


                        $sal = base64_decode($value->wages);

                        $hours = 0;

                        if($value->ovt_nom > 0){
                            if($value->ovt_rate > 0){
                                $hours = $value->ovt_nom / $value->ovt_rate * 3600;
                            }
                        }

                        $whday = 0;
                        $fldday = 0;
                        $fldswtday = 0;
                        $fldgrday = 0;
                        $ododay = 0;
                        $odoswtday = 0;
                        $odogrday = 0;

                        $ovt_total = $value->ovt_rate * $hours;
                        if($value->field_nom > 0){
                            if($value->field_rate > 0){
                                $fldday = $value->field_nom / $value->field_rate;
                            }
                        }

                        if($value->fld_swt > 0){
                            $fldswtday = $value->fld_swt / ($value->field_rate + 50000);
                        }

                        if($value->fld_dgr > 0){
                            $fldgrday = $value->fld_dgr / ($value->field_rate + 25000);
                        }

                        if($value->wh_nom > 0){
                            if($value->wh_rate > 0){
                                $whday = $value->wh_nom / $value->wh_rate;
                            }
                        }

                        if($value->odo_nom > 0){
                            if($value->odo_rate > 0){
                                $ododay = $value->odo_nom / $value->odo_rate;
                            }
                        }

                        if($value->odo_swt > 0){
                            $odoswtday = $value->odo_swt / ($value->odo_rate + 25000);
                        }

                        if($value->odo_dgr > 0){
                            $odogrday = $value->odo_dgr / ($value->odo_rate + 50000);
                        }

                        $foot['sum_ovt'] += $value->ovt_nom;
                        // $whday = (empty($wh_day[$value->emp_id])) ? "0" : array_sum($wh_day[$value->emp_id]);
                        // $fldday = (empty($fld_day[$value->emp_id])) ? "0" : array_sum($fld_day[$value->emp_id]);
                        // $fldswtday = (empty($fld_swt[$value->emp_id])) ? "0" : array_sum($fld_swt[$value->emp_id]);
                        // $fldgrday = (empty($fld_dgr[$value->emp_id])) ? "0" : array_sum($fld_dgr[$value->emp_id]);

                        $fld = $value->field_rate * intval($fldday);
                        $_fld[$value->id]['day'] = $fldday;
                        $_fld[$value->id]['nom'] = $fld;
                        $_fld[$value->id]['rate'] = $value->field_rate;
                        $flddgr = ($value->field_rate + 25000) * $fldgrday;
                        $fldswt = ($value->field_rate + 50000) * $fldswtday;

                        $foot['sum_fld'] += $fld + $flddgr + $fldswt;

                        $wh = $value->wh_rate * $whday;

                        $foot['sum_wh'] += $wh;

                        // $ododay = (empty($odo_day[$value->emp_id])) ? "0" : $odo_day[$value->emp_id];
                        // $odoswtday = (empty($odo_swt[$value->emp_id])) ? "0" : $odo_swt[$value->emp_id];
                        // $odogrday = (empty($odo_dgr[$value->emp_id])) ? "0" : $odo_dgr[$value->emp_id];

                        $odo = $value->odo_rate * $ododay;
                        $ododgr = ($value->odo_rate + 25000) * $odogrday;
                        $odoswt = ($value->odo_rate + 50000) * $odoswtday;

                        $foot['sum_odo'] += $odo + $ododgr + $odoswt;

                        $ln_amt = $value->deduction;

                        $foot['sum_loan'] += $ln_amt;

                        $bonus_amt = $value->bonus;
                        $foot['sum_bonus'] += $bonus_amt;


                        // Datatable
                        $date = (isset($act_date[$value->company_id][$value->emp_id])) ? $act_date[$value->company_id][$value->emp_id] : "0000-00-00";

                        $_emp_bank = $bank_acct->where("emp_id", $value->emp_id);
                        $sel_bank = [];
                        if(count($bank_prior) > 0){
                            foreach($bank_prior as $bpref){
                                $bprefemp = $_emp_bank->where("bank_id", $bpref->bank_id)->first();
                                if(!empty($bprefemp)){
                                    $sel_bank = $bprefemp;
                                    break;
                                }
                            }
                            if(empty($sel_bank)){
                                $sel_bank = $_emp_bank->first();
                            }
                        } else {
                            $sel_bank = $_emp_bank->first();
                        }

                        $override = $_emp_bank->where('override', 1)->first();
                        if(!empty($override)){
                            $sel_bank = $override;
                        }

                        if(empty($sel_bank)){
                            $bnumber = "0";
                        } else {
                            $bnumber = $sel_bank->bank_number;
                        }

                        $isNew = (isset($emp_last_archive[$value->emp_id]) && $last_per >= $date) ? "" : "is-new";
                        $pro_day = round((strtotime($date) - strtotime($rangeStart)) / 86400,0);
                        $row[] = "<span class='$isNew'>".$iNum++."</span>";//
                        if (empty($emp_name) || $emp_name[$value->emp_id] == null){
                            $row[] = '';
                        } else {
                            $row[] = $emp_name[$value->emp_id]."<br>".$emp_pos[$value->emp_id]."<br><label style='font-style: italic;'>'".$bnumber."</label><a href='".route('payroll_new.slip.print', ['id' => $value->emp_id, 'period' => $y."-".$m])."' target='_blank' class='btn btn-xs btn-icon btn-secondary'><i class='fa fa-print'></i></a>";//
                        }


                        $lastSalary = "";
                        $classSalary = "";

                        $lastOvt = "";
                        $classOvt = "";

                        $lastOvtNum = "";
                        $classOvtNum = "";

                        $lastFldRate = "";
                        $classFld = "";

                        $lastWHRate = "";
                        $classWh = "";

                        $lastOdoRate = "";
                        $classOdo = "";

                        $lastAllowBpjskes = "";
                        $classAllowBpjskes = "";

                        $lastAllowBpjstk = "";
                        $classAllowBpjstk = "";

                        $lastAllowJshk = "";
                        $classAllowJshk = "";

                        $lastVoucher = "";
                        $classVoucher = "";

                        $lastTotalSal = "";
                        $classTotalSal = "";

                        $lastLoan = "";
                        $classLoan = "";

                        $lastDeducBpjskes = "";
                        $classDeducBpjskes = "";

                        $lastDeducBpjstk = "";
                        $classDeducBpjstk = "";

                        $lastDeducJshk = "";
                        $classDeducJshk = "";

                        $lastBonus = "";
                        $classBonus = "";

                        $lastSunction = "";
                        $classSunction = "";

                        $lastThr = "";
                        $classThr = "";

                        $lastProportional = "";
                        $classProportional = "";

                        $lastDeducPph21 = "";
                        $classDeducPph21 = "";

                        $lastThp = "";
                        $classThp = "";

                        $lastFldNom = "";
                        $classFldNom = "";

                        $lastFldDgr = "";
                        $classFldDgr = "";

                        $lastFldSwt = "";
                        $classFldSwt = "";

                        $lastOdoNom = "";
                        $classOdoNom = "";

                        $lastOdoDgr = "";
                        $classOdoDgr = "";

                        $lastOdoSwt = "";
                        $classOdoSwt = "";

                        $lastWhNom = "";
                        $classWhNom = "";

                        if(isset($emp_last_archive[$value->emp_id]) && $last_per >= $date){
                            $_per = $y."-".sprintf("%02d", $m)."-01";
                            $la = $emp_last_archive[$value->emp_id];
                            $lasal = base64_decode($la->wages);
                            $lastSalary = $this->get_icon($lasal, ($sal + $value->allowance_office), $_per);
                            $classSalary = $this->get_text_class($lasal, ($sal + $value->allowance_office));

                            $laovtrate = $la->ovt_rate;
                            $lastOvt = $this->get_icon($laovtrate, $value->ovt_rate, $_per);
                            $classOvt = $this->get_text_class($laovtrate, $value->ovt_rate);

                            $lastOvtNum = $this->get_icon($la->ovt_num, $value->ovt_num, $_per);
                            $classOvtNum = $this->get_text_class($la->ovt_num, $value->ovt_num);

                            $lafldrate = $la->field_rate;
                            $lastFldRate = $this->get_icon($lafldrate, $value->field_rate, $_per);
                            $classFld = $this->get_text_class($lafldrate, $value->field_rate);

                            $lawhrate = $la->wh_rate;
                            $lastWHRate = $this->get_icon($lawhrate, $value->wh_rate, $_per);
                            $classWh = $this->get_text_class($lawhrate, $value->wh_rate);

                            $lastOdoRate = $this->get_icon($la->odo_rate, $value->odo_rate, $_per);
                            $classOdo = $this->get_text_class($la->odo_rate, $value->odo_rate);

                            $lastAllowBpjskes = $this->get_icon($la->allow_bpjs_kes, $allow_bpjs_kes, $_per);
                            $classAllowBpjskes = $this->get_text_class($la->allow_bpjs_kes, $allow_bpjs_kes);

                            $lastAllowBpjstk = $this->get_icon($la->allow_jkk, $allow_jkk, $_per);
                            $classAllowBpjstk = $this->get_text_class($la->allow_jkk, $allow_jkk);

                            $lastAllowJshk = $this->get_icon($la->allow_jp, $allow_jp, $_per);
                            $classAllowJshk = $this->get_text_class($la->allow_jp, $allow_jp);

                            $lastVoucher = $this->get_icon($la->voucher, $value->voucher, $_per);
                            $classVoucher = $this->get_text_class($la->voucher, $value->voucher);

                            $now_total_sal = $sal + $value->allowance_office + $value->ovt_nom + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_jkk + $value->allow_bpjs_kes + $value->allow_jkk;
                            $la_total_sal = $lasal + $la->ovt_nom + $la->field_nom + $la->wh_nom + $la->odo_nom + $la->odo_dgr + $la->odo_swt + $la->fld_dgr + $la->fld_swt + $la->voucher + $la->allow_jkk + $la->allow_bpjs_kes + $la->allow_jkk;

                            $lastTotalSal = $this->get_icon($la_total_sal, $now_total_sal, $_per);
                            $classTotalSal = $this->get_text_class($la_total_sal, $now_total_sal);

                            $lastLoan = $this->get_icon($la->deduction, $value->deduction, $_per);
                            $classLoan = $this->get_text_class($la->deduction, $value->deduction);

                            $lastDeducBpjskes = $this->get_icon($la->deduc_bpjs_kes, $deduc_bpjs_kes, $_per);
                            $classDeducBpjskes = $this->get_text_class($la->deduc_bpjs_kes, $deduc_bpjs_kes);

                            $lastDeducBpjstk = $this->get_icon($la->deduc_bpjs_tk, $deduc_bpjs_tk, $_per);
                            $classDeducBpjstk = $this->get_text_class($la->deduc_bpjs_tk, $deduc_bpjs_tk);

                            $lastDeducJshk = $this->get_icon($la->deduc_jkk, $deduc_jkk, $_per);
                            $classDeducJshk = $this->get_text_class($la->deduc_jkk, $deduc_jkk);

                            $lastBonus = $this->get_icon($la->bonus, $value->bonus, $_per);
                            $classBonus = $this->get_text_class($la->bonus, $value->bonus);

                            $lastSunction = $this->get_icon($la->lateness, $value->lateness, $_per);
                            $classSunction = $this->get_text_class($la->lateness, $value->lateness);

                            $lastThr = $this->get_icon($la->thr, $value->thr, $_per);
                            $classThr = $this->get_text_class($la->thr, $value->thr);

                            $lastProportional = $this->get_icon($la->proportional, $value->proportional, $_per);
                            $classProportional = $this->get_text_class($la->proportional, $value->proportional);

                            $lastDeducPph21 = $this->get_icon($la->deduc_pph21, $value->deduc_pph21, $_per);
                            $classDeducPph21 = $this->get_text_class($la->deduc_pph21, $value->deduc_pph21);

                            $now_thp_total = $now_total_sal + $value->bonus + $value->thr - $value->lateness - $value->deduction - $value->deduc_jkk - $value->deduc_bpjs_tk - $value->deduc_pph21 - $value->deduc_bpjs_kes;
                            $la_thp_total = $la_total_sal + $la->bonus + $la->thr - $la->lateness - $la->deduction - $la->deduc_jkk - $la->deduc_bpjs_tk - $la->deduc_pph21 - $la->deduc_bpjs_kes;

                            $lastThp = $this->get_icon($la_thp_total, $now_thp_total, $_per);
                            $classThp = $this->get_text_class($la_thp_total, $now_thp_total);
                            if($classThp == "primary"){
                                $classThp .= " thp-down";
                            } elseif($classThp == "danger"){
                                $classThp .= " thp-up";
                            }

                            $cur_field_nom = $value->field_nom + $value->fld_dgr + $value->fld_swt;
                            $la_field_nom = $la->field_nom + $la->fld_dgr + $la->fld_swt;

                            $cur_odo_nom = $value->odo_nom + $value->odo_dgr + $value->odo_swt;
                            $la_odo_nom = $la->odo_nom + $la->odo_dgr + $la->odo_swt;

                            $lastFldNom = $this->get_icon($la_field_nom, $cur_field_nom, $_per);
                            $classFldNom = $this->get_text_class($la_field_nom, $cur_field_nom);

                            $lastOdoNom = $this->get_icon($la_odo_nom, $cur_odo_nom, $_per);
                            $classOdoNom = $this->get_text_class($la_odo_nom, $cur_odo_nom);

                            $lastWhNom = $this->get_icon($la->wh_nom, $value->wh_nom, $_per);
                            $classWhNom = $this->get_text_class($la->wh_nom, $value->wh_nom);

                        }

                        $value->voucher = base64_decode($value->performance_bonus);
                        $foot['sum_nonwages'] += $nonwages;

                        $row[] = $lastSalary."<span class='text-$classSalary'>".number_format($sal,2)."</span>";
                        $row[] = $lastSalary."<span class='text-$classSalary'>".number_format($nonwages,2)."</span>";
                        $row[] = $lastOvt."<span class='text-$classOvt'>".number_format($value->ovt_rate,2)."</span>";
                        $row[] = floor(($hours / 3600))." hour(s) ". round(($hours%3600) / 60)." minute(s)";
                        $row[] = $lastOvtNum."<span class='text-$classOvtNum'>".number_format($value->ovt_nom,2)."</span>";

                        $row[] = "<div class='d-flex align-items-center justify-content-between'>$lastFldRate<div class='d-flex flex-column'><span class='text-$classFld'>".number_format($value->field_rate,2)."</span><span class='text-$classFld'>". number_format(($value->field_rate + 50000),2) ."</span><span class='text-$classFld'>".number_format(($value->field_rate + 25000),2)."</span></div></div>";
                        $row[] = floor($fldday)."<br>".floor($fldswtday)."<br>".floor($fldgrday);
                        $row[] = "<div class='d-flex align-items-center justify-content-between'>$lastFldNom<div class='d-flex flex-column'><span class='text-$classFldNom'>".number_format($fld,2)."</span><span class='text-$classFldNom'>". number_format($fldswt,2) ."</span><span class='text-$classFldNom'>".number_format($flddgr,2)."</span></div></div>";

                        $row[] = $lastWHRate."<span class='text-$classWh'>".number_format($value->wh_rate,2)."</span>";
                        $row[] = $whday; // DAYS WH
                        $row[] = $lastWhNom."<span class='text-$classWhNom'>".number_format($wh,2)."</span>";

                        $row[] = "<div class='d-flex align-items-center justify-content-between'>$lastOdoRate<div class='d-flex flex-column'><span class='text-$classOdo'>".number_format($value->odo_rate,2)."</span><span class='text-$classOdo'>". number_format(($value->odo_rate + 50000),2) ."</span><span class='text-$classOdo'>".number_format(($value->odo_rate + 25000),2)."</span></div></div>";
                        $row[] = $ododay."<br>".$odoswtday."<br>".$odogrday; // DAYS ODO
                        $row[] = "<div class='d-flex align-items-center justify-content-between'>$lastOdoNom<div class='d-flex flex-column'><span class='text-$classOdoNom'>".number_format($odo,2)."</span><span class='text-$classOdoNom'>". number_format(($odoswt),2) ."</span><span class='text-$classOdoNom'>".number_format(($ododgr),2)."</span></div></div>";

                        $row[] = $lastAllowBpjstk."<span class='text-$classAllowBpjstk'>".number_format($allow_jkk,2)."</span>";
                        $row[] = number_format(0,2);
                        $row[] = number_format(0,2);
                        $row[] = number_format($allow_jp,2);
                        $row[] = $lastAllowBpjskes."<span class='text-$classAllowBpjskes'>".number_format($allow_bpjs_kes,2)."</span>";
                        $row[] = $lastVoucher."<span class='text-$classVoucher'>".number_format($value->voucher,2)."</span>";
                        $row[] = number_format(0,2);

                        $foot['sum_salary'] += $sal;
                        // $foot['sum_ovt'] += $ovt_total;
                        $foot['sum_voucher'] += $value->voucher;
                        $total_sal = $sal + $nonwages + $value->ovt_nom + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_jkk + $value->allow_bpjs_kes + $value->allow_jp;
                        $foot['sum_tot_salary'] += $total_sal;

                        $row[] = $lastTotalSal."<span class='text-$classTotalSal'>".number_format($total_sal,2)."</span>";
                        $row[] = $lastSunction."<span class='text-$classSunction'>".number_format($sunction,2)."</span>"; //SUNCTION
                        $row[] = 0; //ABSENCE
                        $row[] = $lastLoan."<span class='text-$classLoan'>".number_format($ln_amt,2)."</span>"; //LOAN
                        $row[] = $lastVoucher."<span class='text-$classVoucher'>".number_format($value->voucher,2)."</span>";
                        $row[] = number_format(0,2);
                        $row[] = $lastDeducJshk."<span class='text-$classDeducJshk'>".number_format($deduc_jkk,2)."</span>";
                        $row[] = number_format(0,2);
                        $row[] = number_format(0,2);
                        $row[] = number_format(0,2);
                        $row[] = number_format(0,2);
                        $row[] = number_format(0,2);
                        $row[] = $lastDeducBpjskes."<span class='text-$classDeducBpjskes'>".number_format($deduc_bpjs_kes,2)."</span>";
                        $row[] = $lastDeducBpjstk."<span class='text-$classDeducBpjstk'>".number_format($deduc_bpjs_tk,2)."</span>";
                        $deduc_pph21 = ($value->deduc_pph21 == "") ? 0 : $value->deduc_pph21;
                        $row[] = $lastDeducPph21."<span class='text-$classDeducPph21'>".number_format($deduc_pph21,2)."</span>"; //PPH21
                        $row[] = $lastBonus."<span class='text-$classBonus'>".number_format($bonus_amt,2)."</span>"; //Bonus

                        $prob = [];
                        $vcr = [];
                        $_thr = [];
                        $_temp = $temp[$value->emp_id];
                        if(isset($detType[$_temp])){
                            $prob = json_decode($detType[$_temp]['no_probation'], true);
                            $vcr = json_decode($detType[$_temp]['with_voucher'], true);
                            $_thr = json_decode($detType[$_temp]['disable_thr'], true);
                        }

                        $minThr = 3;
                        $maxThr = 15;

                        if(isset($prob[$value->company_id])){
                            if($prob[$value->company_id] == 1){
                                $minThr = 0;
                                $maxThr = 12;
                            }
                        } elseif (isset($prob[$myComp->id_parent])){
                            if($prob[$myComp->id_parent] == 1){
                                $minThr = 0;
                                $maxThr = 12;
                            }
                        }

                        $with_voucher = 0;
                        if(isset($vcr[$value->company_id])){
                            if($vcr[$value->company_id]){
                                $with_voucher = $value->voucher;
                            }
                        } elseif (isset($vcr[$myComp->id_parent])){
                            if($vcr[$myComp->id_parent] == 1){
                                $with_voucher = $value->voucher;
                            }
                        }

                        $disable_thr = 0;
                        if(isset($_thr[$value->company_id])){
                            if($_thr[$value->company_id]){
                                $disable_thr = (isset($_thr[$value->company_id])) ? $_thr[$value->company_id] : 0;
                            }
                        } elseif (isset($_thr[$myComp->id_parent])){
                            if($_thr[$myComp->id_parent] == 1){
                                $disable_thr = (isset($_thr[$myComp->id_parent])) ? $_thr[$myComp->id_parent] : 0;
                            }
                        }

                        $thr_total = 0;

                        $foot['sum_thr'] += $thr_total;
                        $join_date = "0000-00-00";
                        if(isset($act_date[$value->company_id])){
                            if(isset($act_date[$value->company_id][$value->emp_id])){
                                $join_date = date("d F Y", strtotime($act_date[$value->company_id][$value->emp_id]));
                            }
                        }
                        $thrprob = "";
                        $d1 = date_create(date("Y-m-d"));
                        $d2 = date_create($join_date);
                        $diff = date_diff($d1, $d2);
                        $dyear = $diff->format("%y");
                        $dmonth = $diff->format("%m");
                        if($dyear > 0){
                            $dmonth += $dyear * 12;
                        }
                        if($dmonth < $maxThr && $dmonth > $minThr){
                            if(($sal + $value->allowance_office + $with_voucher) > 0){
                                $calculation = round(($thr_total / ($sal + $value->allowance_office + $with_voucher)) * 12);
                                $_calc = number_format($sal + $value->allowance_office + $with_voucher, 2)." x $calculation / 12";
                                $thrprob = '<span>Months : '.($calculation).' months</span><span>Calculation :</span><span>'.$_calc.'</span>';
                            }
                        }

                        $titlethr = '<div class="d-flex flex-column"><span>Join Date : '.$join_date.'</span>'.$thrprob.'</div>';
                        $echothr = "<div class='d-flex flex-column'>";
                        $echothr .= "<div>".$lastThr."<span class='text-$classThr'>".number_format($thr_total,2)."</span></div>";
                        $echothr .= "<button type='button' class='btn btn-sm btn-info mt-3 print-hide' data-toggle='tooltip' data-trigger='focus' data-html='true' data-placement='bottom' title='$titlethr'><i class='fa fa-search'></i> Detail</button>";
                        $echothr .= "</div>";
                        $row[] = $echothr; //THR

                        $thp_total = $total_sal + $thr_total + $bonus_amt - $sunction - $ln_amt - $deduc_jkk - $deduc_bpjs_tk - $value->deduc_pph21 - $deduc_bpjs_kes - $value->voucher;

                        $thp_total -= $value->proportional;
                        $foot['sum_thp'] += $thp_total;

                        $foot['sum_pph21'] += $value->deduc_pph21;
                        $row[] = $lastProportional."<span class='text-$classProportional'>".number_format($value->proportional,2)."</span>"; //Proportional
                        $row[] = $lastThp."<span class='text-$classThp'>".number_format($thp_total,2)."</span>"; //Proportional
                        // $row[] = number_format($thp_total,2); //THP
                        $empsalid[$value->emp_id] = $thp_total;
                        $foot['sum_prop'] += $value->proportional;
                        $foot['sum_sanction'] += $sunction;
                        if($date <= $period_end_date){
                            $data[] = $row;
                        }
                        $source = "Archive";
                    }
                }
                foreach ($type_emp as $keyEmp => $valueEmp){
                    if (isset($eType[$keyEmp])) {
                        $util = Finance_util_salary::where('position', $eType[$keyEmp])
                            ->where('salary_date', 'like', $y."-".sprintf("%02d", $m)."%")
                            ->where("isThr", 0)
                            ->where('company_id', Session::get('company_id'))
                            ->first();
                        $sal_total = 0;
                        foreach ($valueEmp as $itemEmp){
                            if(isset($empsalid[$itemEmp])){
                                $sal_total += $empsalid[$itemEmp];
                            }
                        }
                        if (empty($util)){
                            $_pref = Preference_config::where("id_company", Session::get('company_id'))->first();
                            $nUtil = new Finance_util_salary();
                            $sdate = $y."-".$m."-28";
                            $nUtil->salary_date = $sdate;
                            $nUtil->currency = "IDR"; //default
                            $nUtil->amount = $sal_total;
                            $nUtil->plan_date = $sdate;
                            $nUtil->status = "waiting";
                            $nUtil->position = $eType[$keyEmp];
                            $nUtil->company_id = Session::get('company_id');
                            $nUtil->created_by = Auth::user()->username;
                            $nUtil->isThr = 2;
                            $nUtil->tc = (empty($_pref->payroll_tc)) ? null : $_pref->payroll_tc;
                            $nUtil->save();
                        } else {
                            $util->amount = $sal_total;
                            $util->save();
                        }
                    }
                }

                $error = 0;
            } else {
                $error = 1;
                $data = null;
                $source = null;
            }
        } else {
            if (count($emp) > 0) {
                foreach ($emp as $key => $value) {
                    $row = [];
                    $salary_emp = base64_decode($value->n_basic_salary ?? "MA==");
                    $sunction = 0;
                    $absence_deduct = 0;
                    $bonus_amt = 0;
                    $ln_amt = 0;
                    $hours = 0;
                    $sumSanc = 0;
                    $empid = $value->id;

                    $sanction = Hrd_sanction::where('emp_id', $value->id)
                        ->whereNotNull('approved_by')
                        ->whereBetween('sanction_date',[$rangeStart,$rangeEnd])
                        ->get();

                    foreach ($sanction as $key => $valSanc){
                        $sunction += intval($valSanc->sanction_amount);
                    }


                    $allow_jkk = ($value->allow_jkk == "") ? 0 : $value->allow_jkk;
                    $allow_bpjs_kes = ($value->allow_bpjs_kes == "") ? 0 : $value->allow_bpjs_kes;
                    $allow_jp = ($value->allow_jp == "") ? 0 : $value->allow_jp;

                    $foot['sum_sanction'] += $sunction;

                    $foot['sum_jkk'] += $allow_jkk;
                    $foot['sum_bpjs_kes'] += $allow_bpjs_kes;
                    $foot['sum_jp'] += $allow_jp;

                    $deduc_bpjs_tk = ($value->deduc_bpjs_tk == "") ? 0 : $value->deduc_bpjs_tk;
                    $deduc_bpjs_kes = ($value->deduc_bpjs_kes == "") ? 0 : $value->deduc_bpjs_kes;
                    $deduc_jkk = ($value->deduc_jkk == "") ? 0 : $value->deduc_jkk;

                    $foot['sum_ded_bpjs_tk'] += $deduc_bpjs_tk;
                    $foot['sum_ded_bpjs_kes'] += $deduc_bpjs_kes;
                    $foot['sum_ded_jkk'] += $deduc_jkk;

                    $sal = $salary_emp + base64_decode($value->n_house_allow ?? "MA==") + base64_decode($value->n_health_allow ?? "MA==") + base64_decode($value->n_position_allow ?? "MA==");
                    $nonwages = base64_decode($value->n_transport_allow ?? "MA==") + base64_decode($value->n_meal_allow ?? "MA==");
                    $pb = base64_decode($value->n_performance_bonus ?? "MA==");

                    if (!empty($time_in[$value->id])) {
                        for ($i=0; $i < count($time_in[$value->id]); $i++) {
                            $otdate = date("N", strtotime($ovt_date[$value->id][$i]));
                            if($otdate >= 6){
                                $diff = strtotime($time_out[$value->id][$i]) - strtotime($time_in[$value->id][$i]);
                                $hours += $diff;
                            } else {
                                $ovtStartTime = Session::get('company_penalty_stop');
                                if(!empty($ovtStartTime)){
                                    $diff = strtotime($time_out[$value->id][$i]) - strtotime($ovtStartTime);
                                    $hours += $diff;
                                }
                            }
                        }
                    }

                    $hours = $hours < 0 ? 0 : $hours;

                    $ovt_total = $value->overtime * ceil(($hours / 3600));

                    $foot['sum_ovt'] += $ovt_total;
                    $whday = (empty($wh_day[$value->id])) ? "0" : array_sum($wh_day[$value->id]);
                    $fldday = (empty($fld_day[$value->id])) ? "0" : array_sum($fld_day[$value->id]);
                    $fldswtday = (empty($fld_swt[$value->id])) ? "0" : array_sum($fld_swt[$value->id]);
                    $fldgrday = (empty($fld_dgr[$value->id])) ? "0" : array_sum($fld_dgr[$value->id]);

                    $fld = $value->fld_bonus * $fldday;
                    $flddgr = ($value->fld_bonus + 25000) * $fldgrday;
                    $fldswt = ($value->fld_bonus + 50000) * $fldswtday;

                    $foot['sum_fld'] += $fld + $flddgr + $fldswt;

                    $wh = $value->wh_bonus * $whday;

                    $foot['sum_wh'] += $wh;

                    $ododay = (empty($odo_day[$value->id])) ? "0" : $odo_day[$value->id];
                    $odoswtday = (empty($odo_swt[$value->id])) ? "0" : $odo_swt[$value->id];
                    $odogrday = (empty($odo_dgr[$value->id])) ? "0" : $odo_dgr[$value->id];

                    $odo = $value->odo_bonus * $ododay;
                    $ododgr = ($value->odo_bonus + 25000) * $odogrday;
                    $odoswt = ($value->odo_bonus + 50000) * $odoswtday;

                    $foot['sum_odo'] += $odo + $ododgr + $odoswt;

                    if(isset($loanEmp[$value->id])){
                        foreach($loanEmp[$value->id] as $lEmp){
                            if (isset($loanDet[$lEmp])){
                                $ln_amt += array_sum($loanDet[$lEmp]);
                            }
                        }
                    }

                    $foot['sum_loan'] += $ln_amt;

                    if (isset($bonusEmp[$value->id])) {
                        foreach ($bonusEmp[$value->id] as $bEmp) {
                            if (isset($bonusPay[$bEmp])) {
                                $bonus_amt += array_sum($bonusPay[$bEmp]);
                            }
                        }
                    }

                    $foot['sum_bonus'] += $bonus_amt;

                    $yearly_bonus = $value->yearly_bonus * $salary_emp + $value->fx_yearly_bonus;
                    $bonus_only = $value->yearly_bonus * $salary_emp;

                    $lastSalary = "";
                    $classSalary = "";

                    $lastOvt = "";
                    $classOvt = "";

                    $lastOvtNum = "";
                    $classOvtNum = "";

                    $lastFldRate = "";
                    $classFld = "";

                    $lastWHRate = "";
                    $classWh = "";

                    $lastOdoRate = "";
                    $classOdo = "";

                    $lastAllowBpjskes = "";
                    $classAllowBpjskes = "";

                    $lastAllowBpjstk = "";
                    $classAllowBpjstk = "";

                    $lastAllowJshk = "";
                    $classAllowJshk = "";

                    $lastVoucher = "";
                    $classVoucher = "";

                    $lastTotalSal = "";
                    $classTotalSal = "";

                    $lastLoan = "";
                    $classLoan = "";

                    $lastDeducBpjskes = "";
                    $classDeducBpjskes = "";

                    $lastDeducBpjstk = "";
                    $classDeducBpjstk = "";

                    $lastDeducJshk = "";
                    $classDeducJshk = "";

                    $lastBonus = "";
                    $classBonus = "";

                    $lastSunction = "";
                    $classSunction = "";

                    $lastThr = "";
                    $classThr = "";

                    $lastProportional = "";
                    $classProportional = "";

                    $lastDeducPph21 = "";
                    $classDeducPph21 = "";

                    $lastThp = "";
                    $classThp = "";

                    $lastFldNom = "";
                    $classFldNom = "";

                    $lastFldDgr = "";
                    $classFldDgr = "";

                    $lastFldSwt = "";
                    $classFldSwt = "";

                    $lastOdoNom = "";
                    $classOdoNom = "";

                    $lastOdoDgr = "";
                    $classOdoDgr = "";

                    $lastOdoSwt = "";
                    $classOdoSwt = "";

                    $lastWhNom = "";
                    $classWhNom = "";

                    if(isset($emp_last_archive[$value->id])){
                        $_per = $y."-".sprintf("%02d", $m)."-01";
                        $la = $emp_last_archive[$value->id];
                        $lasal = base64_decode($la->salary) + $la->allowance_office;
                        $lastSalary = $this->get_icon($lasal, ($sal + $value->allowance_office), $_per);
                        $classSalary = $this->get_text_class($lasal, ($sal + $value->allowance_office));

                        $laovtrate = $la->ovt_rate;
                        $lastOvt = $this->get_icon($laovtrate, $value->overtime, $_per);
                        $classOvt = $this->get_text_class($laovtrate, $value->overtime);

                        $lastOvtNum = $this->get_icon($la->ovt_num, $ovt_total, $_per);
                        $classOvtNum = $this->get_text_class($la->ovt_num, $ovt_total);

                        $lafldrate = $la->field_rate;
                        $lastFldRate = $this->get_icon($lafldrate, $value->fld_bonus, $_per);
                        $classFld = $this->get_text_class($lafldrate, $value->fld_bonus);

                        $lawhrate = $la->wh_rate;
                        $lastWHRate = $this->get_icon($lawhrate, $value->wh_bonus, $_per);
                        $classWh = $this->get_text_class($lawhrate, $value->wh_bonus);

                        $lastOdoRate = $this->get_icon($la->odo_rate, $value->odo_bonus, $_per);
                        $classOdo = $this->get_text_class($la->odo_rate, $value->odo_bonus);

                        $lastAllowBpjskes = $this->get_icon($la->allow_bpjs_kes, $allow_bpjs_kes, $_per);
                        $classAllowBpjskes = $this->get_text_class($la->allow_bpjs_kes, $allow_bpjs_kes);

                        $lastAllowBpjstk = $this->get_icon($la->allow_jkk, $allow_jkk, $_per);
                        $classAllowBpjstk = $this->get_text_class($la->allow_jkk, $allow_jkk);

                        $lastAllowJshk = $this->get_icon($la->allow_jkk, $allow_jkk, $_per);
                        $classAllowJshk = $this->get_text_class($la->allow_jkk, $allow_jkk);

                        $lastVoucher = $this->get_icon($la->voucher, $value->voucher, $_per);
                        $classVoucher = $this->get_text_class($la->voucher, $value->voucher);

                        $now_total_sal = $sal + $value->allowance_office + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_jkk + $value->allow_bpjs_kes + $value->allow_jkk + $bonus_amt;
                        $la_total_sal = $lasal + $la->ovt_nom + $la->field_nom + $la->wh_nom + $la->odo_nom + $la->odo_dgr + $la->odo_swt + $la->fld_dgr + $la->fld_swt + $la->voucher + $la->allow_jkk + $la->allow_bpjs_kes + $la->allow_jkk + $la->bonus;

                        $lastTotalSal = $this->get_icon($la_total_sal, $now_total_sal, $_per);
                        $classTotalSal = $this->get_text_class($la_total_sal, $now_total_sal);

                        $lastLoan = $this->get_icon($la->deduction, $ln_amt, $_per);
                        $classLoan = $this->get_text_class($la->deduction, $ln_amt);

                        $lastDeducBpjskes = $this->get_icon($la->deduc_bpjs_kes, $deduc_bpjs_kes, $_per);
                        $classDeducBpjskes = $this->get_text_class($la->deduc_bpjs_kes, $deduc_bpjs_kes);

                        $lastDeducBpjstk = $this->get_icon($la->deduc_bpjs_tk, $deduc_bpjs_tk, $_per);
                        $classDeducBpjstk = $this->get_text_class($la->deduc_bpjs_tk, $deduc_bpjs_tk);

                        $lastDeducJshk = $this->get_icon($la->deduc_jkk, $deduc_jkk, $_per);
                        $classDeducJshk = $this->get_text_class($la->deduc_jkk, $deduc_jkk);

                        $lastBonus = $this->get_icon($la->bonus, $bonus_amt, $_per);
                        $classBonus = $this->get_text_class($la->bonus, $bonus_amt);

                        $lastSunction = $this->get_icon($la->lateness, $sunction, $_per);
                        $classSunction = $this->get_text_class($la->lateness, $sunction);

                        $cur_field_nom = $fld + $flddgr + $fldswt;
                        $la_field_nom = $la->field_nom + $la->fld_dgr + $la->fld_swt;

                        $cur_odo_nom = $odo + $ododgr + $odoswt;
                        $la_odo_nom = $la->odo_nom + $la->odo_dgr + $la->odo_swt;

                        $lastFldNom = $this->get_icon($la_field_nom, $cur_field_nom, $_per);
                        $classFldNom = $this->get_text_class($la_field_nom, $cur_field_nom);

                        $lastOdoNom = $this->get_icon($la_odo_nom, $cur_odo_nom, $_per);
                        $classOdoNom = $this->get_text_class($la_odo_nom, $cur_odo_nom);

                        $lastWhNom = $this->get_icon($la->wh_nom, $wh, $_per);
                        $classWhNom = $this->get_text_class($la->wh_nom, $wh);

                    }

                    $_emp_bank = $bank_acct->where("emp_id", $value->id);
                    $sel_bank = [];
                    if(count($bank_prior) > 0){
                        foreach($bank_prior as $bpref){
                            $bprefemp = $_emp_bank->where("bank_id", $bpref->bank_id)->first();
                            if(!empty($bprefemp)){
                                $sel_bank = $bprefemp;
                                break;
                            }
                        }
                        if(empty($sel_bank)){
                            $sel_bank = $_emp_bank->first();
                        }
                    } else {
                        $sel_bank = $_emp_bank->first();
                    }

                    $override = $_emp_bank->where('override', 1)->first();
                    if(!empty($override)){
                        $sel_bank = $override;
                    }

                    if(empty($sel_bank)){
                        $bnumber = "0";
                    } else {
                        $bnumber = $sel_bank->bank_number;
                    }

                    // Datatable
                    $row[] = $key + 1;//
                    $row[] = $value->emp_name."<br>".$value->emp_position."<br><label style='font-style: italic;'>'".$bnumber."</label>";//
                    $row[] = $lastSalary."<span class='text-$classSalary'>".number_format($sal,2)."</span>";
                    $row[] = $lastSalary."<span class='text-$classSalary'>".number_format($nonwages,2)."</span>";
                    $row[] = $lastOvt."<span class='text-$classOvt'>".number_format($value->overtime,2)."</span>";
                    $row[] = floor(($hours / 3600))." hour(s) ". round(($hours%3600) / 60)." minute(s)";
                    $row[] = $lastOvtNum."<span class='text-$classOvtNum'>".number_format($ovt_total,2)."</span>";

                    // $row[] = number_format($value->fld_bonus,2)."<br>". number_format(($value->fld_bonus + 50000),2) ."<br>".number_format(($value->fld_bonus + 25000),2);
                    // $row[] = $fldday."<br>".$fldswtday."<br>".$fldgrday;
                    // $row[] = number_format($fld,2)."<br>". number_format(($fldswt),2) ."<br>".number_format(($flddgr),2);
                    $row[] = "<div class='d-flex align-items-center justify-content-between'>$lastFldRate<div class='d-flex flex-column'><span class='text-$classFld'>".number_format($value->fld_bonus,2)."</span><span class='text-$classFld'>". number_format(($value->fld_bonus + 50000),2) ."</span><span class='text-$classFld'>".number_format(($value->fld_bonus + 25000),2)."</span></div></div>";
                    $row[] = $fldday."<br>".$fldswtday."<br>".$fldgrday;
                    $row[] = "<div class='d-flex align-items-center justify-content-between'>$lastFldNom<div class='d-flex flex-column'><span class='text-$classFldNom'>".number_format($fld,2)."</span><span class='text-$classFldNom'>". number_format($fldswt,2) ."</span><span class='text-$classFldNom'>".number_format($flddgr,2)."</span></div></div>";

                    // $row[] = number_format($value->wh_bonus,2);
                    // $row[] = $whday; // DAYS WH
                    // $row[] = number_format($wh,2);
                    $row[] = $lastWHRate."<span class='text-$classWh'>".number_format($value->wh_bonus,2)."</span>";
                    $row[] = $whday; // DAYS WH
                    $row[] = $lastWhNom."<span class='text-$classWhNom'>".number_format($wh,2)."</span>";

                    // $row[] = number_format($value->odo_bonus,2)."<br>". number_format(($value->odo_bonus + 50000),2) ."<br>".number_format(($value->odo_bonus + 25000),2);
                    // $row[] = $ododay."<br>".$odoswtday."<br>".$odogrday; // DAYS ODO
                    // $row[] = number_format($odo,2)."<br>". number_format(($odoswt),2) ."<br>".number_format(($ododgr),2);
                    $row[] = "<div class='d-flex align-items-center justify-content-between'>$lastOdoRate<div class='d-flex flex-column'><span class='text-$classOdo'>".number_format($value->odo_bonus,2)."</span><span class='text-$classOdo'>". number_format(($value->odo_bonus + 50000),2) ."</span><span class='text-$classOdo'>".number_format(($value->odo_bonus + 25000),2)."</span></div></div>";
                    $row[] = $ododay."<br>".$odoswtday."<br>".$odogrday; // DAYS ODO
                    $row[] = "<div class='d-flex align-items-center justify-content-between'>$lastOdoNom<div class='d-flex flex-column'><span class='text-$classOdoNom'>".number_format($odo,2)."</span><span class='text-$classOdoNom'>". number_format(($odoswt),2) ."</span><span class='text-$classOdoNom'>".number_format(($ododgr),2)."</span></div></div>";

                    $row[] = $lastAllowBpjstk."<span class='text-$classAllowBpjstk'>".number_format($allow_jkk,2)."</span>";
                    $row[] = $lastAllowBpjskes."<span>".number_format(0,2)."</span>";
                    $row[] = $lastAllowJshk."<span class='text-$classAllowJshk'>".number_format($allow_jp,2)."</span>";
                    $row[] = $lastAllowBpjskes."<span>".number_format(0,2)."</span>";
                    $row[] = $lastAllowBpjskes."<span class='text-$classAllowBpjskes'>".number_format($allow_bpjs_kes,2)."</span>";
                    $row[] = $lastVoucher."<span class='text-$classVoucher'>".number_format($pb,2)."</span>";
                    $row[] = $lastVoucher."<span>".number_format(0,2)."</span>";

                    $foot['sum_salary'] += $sal;
                    $foot['sum_nonwages'] += $nonwages;
                    $foot['sum_ovt'] += $ovt_total;
                    $foot['sum_voucher'] += $pb;
                    $total_sal = $sal + $nonwages + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $pb + $value->allow_jkk + $value->allow_bpjs_kes + $value->allow_jp + $bonus_amt;
                    $foot['sum_tot_salary'] += $total_sal;

                    // $row[] = number_format($total_sal,2);
                    $row[] = $lastTotalSal."<span class='text-$classTotalSal'>".number_format($total_sal,2)."</span>";
                    $row[] = $lastSunction."<span class='text-$classSunction'>".number_format($sunction,2)."</span>"; //SUNCTION
                    $row[] = 0; //ABSENCE
                    $row[] = $lastLoan."<span class='text-$classLoan'>".number_format($ln_amt,2)."</span>"; //LOAN
                    $row[] = $lastVoucher."<span class='text-$classVoucher'>".number_format($pb,2)."</span>";
                    $row[] = $lastVoucher."<span>".number_format(0,2)."</span>";
                    $row[] = $lastVoucher."<span>".number_format(0,2)."</span>";
                    $row[] = $lastVoucher."<span>".number_format(0,2)."</span>";
                    $row[] = $lastVoucher."<span>".number_format(0,2)."</span>";
                    $row[] = $lastVoucher."<span>".number_format(0,2)."</span>";
                    $row[] = $lastVoucher."<span>".number_format(0,2)."</span>";
                    $row[] = $lastVoucher."<span>".number_format(0,2)."</span>";
                    $row[] = $lastDeducBpjskes."<span class='text-$classDeducBpjskes'>".number_format($deduc_bpjs_kes,2)."</span>";
                    $row[] = $lastDeducBpjstk."<span class='text-$classDeducBpjstk'>".number_format($deduc_bpjs_tk,2)."</span>";
                    // $row[] = $lastDeducJshk."<span class='text-$classDeducJshk'>".number_format($deduc_jkk,2)."</span>";
                    $row[] = $lastBonus."<span class='text-$classBonus'>".number_format($bonus_amt,2)."</span>"; //Bonus
                    $row[] = $lastDeducPph21."<span class='text-$classDeducPph21'>".number_format($value->deduc_pph21,2)."</span>"; //PPH21
                    $foot['sum_pph21'] += $value->deduc_pph21;

                    $isThr = sprintf("%02d", $m)."-".$y;
                    $date_join = (isset($act_date[$value->company_id][$empid])) ? $act_date[$value->company_id][$empid] : "0000-00-00";
                    $mntDiff = 0;
                    $dIn = 0;
                    if ($date_join != "0000-00-00") {
                        $dateIn = date_create($date_join);
                        $dateNow = date_create(date("Y-m-d", strtotime($rangeEnd." +1 day")));
                        $diff = date_diff($dateIn, $dateNow);
                        $mntDiff = $diff->format("%y-%m");
                        $dIn = date('d', strtotime($date_join));
                    }

                    $wPeriod = explode("-", $mntDiff);

                    $mnt = 0;
                    if($wPeriod[0] >= 1){
                        $mnt = 12 * $wPeriod[0];
                    }

                    $mnt += end($wPeriod);

                    if($dIn > $period_start){
                        // $mnt -= 1;
                    }

                    $prob = [];
                    $vcr = [];
                    $_thr = [];
                    if(isset($detType[$value->emp_type])){
                        $prob = json_decode($detType[$value->emp_type]['no_probation'], true);
                        $vcr = json_decode($detType[$value->emp_type]['with_voucher'], true);
                        $_thr = json_decode($detType[$value->emp_type]['disable_thr'], true);
                    }

                    $minThr = 3;
                    $maxThr = 15;

                    if(isset($prob[$value->company_id])){
                        if($prob[$value->company_id] == 1){
                            $minThr = 0;
                            $maxThr = 12;
                        }
                    } elseif (isset($prob[$config_company->id_parent])){
                        if($prob[$config_company->id_parent] == 1){
                            $minThr = 0;
                            $maxThr = 12;
                        }
                    }

                    $with_voucher = 0;
                    if(isset($vcr[$value->company_id])){
                        if($vcr[$value->company_id]){
                            $with_voucher = $value->voucher;
                        }
                    } elseif (isset($vcr[$config_company->id_parent])){
                        if($vcr[$config_company->id_parent] == 1){
                            $with_voucher = $value->voucher;
                        }
                    }

                    $disable_thr = 0;
                    if(isset($_thr[$value->company_id])){
                        if($_thr[$value->company_id]){
                            $disable_thr = (isset($_thr[$value->company_id])) ? $_thr[$value->company_id] : 0;
                        }
                    } elseif (isset($_thr[$config_company->id_parent])){
                        if($_thr[$config_company->id_parent] == 1){
                            $disable_thr = (isset($_thr[$value->company_id])) ? $_thr[$value->company_id] : 0;
                        }
                    }

                    if($mnt <= $minThr){
                        $thr_num = 0;
                    } elseif($mnt > $minThr && $mnt < $maxThr){
                        $thr_num = (($mnt - $minThr) / 12) * ($sal + $value->allowance_office + $with_voucher);
                    } elseif($mnt >= $maxThr) {
                        $thr_num = ($sal + $value->allowance_office + $with_voucher);
                    }

                    if (in_array($isThr, $thr_period)){
                        $thr_total = ($disable_thr == 0) ? $thr_num : 0;
                    } else {
                        $thr_total = 0;
                    }
                    $thr_total = 0;
                    $foot['sum_thr'] += $thr_total;

                    // $total_sal = $sal + $value->allowance_office + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_jkk + $value->allow_bpjs_kes + $value->allow_jkk;

                    $thp = $total_sal + $thr_total - $absence_deduct - $ln_amt - $value->deduc_bpjs_tk - $value->deduc_bpjs_kes - $value->deduc_jkk - $value->deduc_pph21 - $pb;
                    $xthp = $thp - $fld - $wh - $odo - $ododgr - $odoswt - $fldswt - $flddgr;
                    $_date = (isset($act_date[$value->company_id][$empid])) ? $act_date[$value->company_id][$empid] : "000-00-00";
                    $pro_day = round((strtotime($_date) - strtotime($rangeStart)) / 86400,0);
                    $in_date = (isset($act_date[$value->company_id][$empid])) ? $act_date[$value->company_id][$empid] : "000-00-00";
                    $zero_day = (strtotime($rangeEnd) - strtotime($_date)) / 86400;
                    if($pro_day > 0 && $pro_day <= $pro_n_day)
                    {
                        $pro_basis = $pro_n_day;
                        $pro_thp = $pro_day / $pro_basis * $xthp;
                        $pro_decrement = ($pro_day) / $pro_basis * $xthp;
                    }
                    //kalau hari masuk = start month gaji, pengurangan = gaji = ZERO gaji.
                    elseif($pro_day == 0)
                    {
                        // $pro_decrement = $xthp;
                        if(date('d',strtotime($in_date)) == 16)
                        {
                            $pro_decrement = 0;
                        }
                        else
                        {
                            $pro_decrement = $xthp;
                        }
                    }
                    //tidak ada pemotongan
                    else
                    {
                        $pro_thp = 0;
                        $pro_decrement = 0;
                    }

                    //kalau tgl masuk baru lebih baru dari range2. ZERO gaji
                    if($zero_day <= 0)
                    {
                        $pro_decrement = $xthp;
                    }

                    if($pro_day >= 0 && $pro_day <= 30) {
                        $total_decrement = $pro_decrement;
                        $thp_total = $thp - $pro_decrement;
                        $foot['sum_thp'] += $thp - $pro_decrement;
                    } elseif($zero_day <= 0) {
                        $total_decrement = $pro_decrement;
                        $thp_total = $thp - $pro_decrement;
                        $foot['sum_thp'] += $thp - $pro_decrement;
                    } else {
                        $foot['sum_thp'] += $thp;
                        $thp_total = $thp;
                        $total_decrement = 0;
                    }

                    if(isset($emp_last_archive[$value->id])){
                        $_per = $y."-".sprintf("%02d", $m)."-01";
                        $la = $emp_last_archive[$value->id];
                        $lastThr = $this->get_icon($la->thr, $thr_total, $_per);
                        $classThr = $this->get_text_class($la->thr, $thr_total);

                        $lastProportional = $this->get_icon($la->proportional, $total_decrement, $_per);
                        $classProportional = $this->get_text_class($la->proportional, $total_decrement);

                        $lastDeducPph21 = $this->get_icon($la->deduc_pph21, $value->deduc_pph21, $_per);
                        $classDeducPph21 = $this->get_text_class($la->deduc_pph21, $value->deduc_pph21);

                        $now_total_sal = $sal + $value->allowance_office + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_jkk + $value->allow_bpjs_kes + $value->allow_jkk + $bonus_amt;

                        $now_thp_total = $now_total_sal + $thr_total - $sunction - $ln_amt - $deduc_jkk - $deduc_bpjs_tk - $value->deduc_pph21 - $deduc_bpjs_kes;
                        $la_thp_total = $la_total_sal + $la->thr - $la->lateness - $la->deduction - $la->deduc_jkk - $la->deduc_bpjs_tk - $la->deduc_pph21 - $la->deduc_bpjs_kes;

                        $lastThp = $this->get_icon($la_thp_total, $now_thp_total, $_per);
                        $classThp = $this->get_text_class($la_thp_total, $now_thp_total);
                        if($classThp == "primary"){
                            $classThp .= " thp-down";
                        } elseif($classThp == "danger"){
                            $classThp .= " thp-up";
                        }
                    }

                    $thrprob = "";
                    $d1 = date_create(date("Y-m-d"));
                    $d2 = date_create($in_date);
                    $diff = date_diff($d1, $d2);
                    $dyear = $diff->format("%y");
                    $dmonth = $diff->format("%m");
                    if($dyear > 0){
                        $dmonth += $dyear * 12;
                    }
                    if($dmonth > $minThr && $dmonth < $maxThr){
                        if(($sal + $value->allowance_office + $with_voucher) > 0){
                            $calculation = round(($thr_total / ($sal + $value->allowance_office + $with_voucher)) * 12);
                            $_calc = number_format($sal + $value->allowance_office + $with_voucher, 2)." x $calculation / 12";
                            $thrprob = '<span>Months : '.($calculation).' months</span><span>Calculation :</span><span>'.$_calc.'</span>';
                        }
                    }
                    $titlethr = '<div class="d-flex flex-column"><span>Join Date : '.$in_date.'</span>'.$thrprob.'</div>';
                    $echothr = "<div class='d-flex flex-column'>";
                    $echothr .= "<div>".$lastThr."<span class='text-$classThr'>".number_format($thr_total,2)."</span></div>";
                    $echothr .= "<button type='button' class='btn btn-sm btn-info mt-3 print-hide' data-toggle='tooltip' data-trigger='focus' data-html='true' data-placement='bottom' title='$titlethr'><i class='fa fa-search'></i> Detail</button>";
                    $echothr .= "</div>";


                    $row[] = $echothr; //THR
                    $row[] = $lastProportional."<span class='text-$classProportional'>".number_format($total_decrement,2)."</span>"; //Proportional
                    $row[] = $lastThp."<span class='text-$classThp'>".number_format($thp_total,2)."</span>"; //Proportional

                    $foot['sum_prop'] += $total_decrement;

                    $data[] = $row;

                }
                $error = 0;
                $source = "EMPLOYEE";
            } else {
                $error = 1;
                $data = null;
                $source = null;
            }
        }

        if (isset($eType[$t])) {
            $pos = $eType[$t];
        } else {
            $pos = $t;
        }

        if (date('d') >= Session::get('period_start')) {
            if($request->month != date("n")){
                $refresh = 0;
            } else {
                $refresh = 1;
            }
        } else {
            $refresh = 0;
        }

        $val = array(
            'error' => $error,
            'data' => $data,
            'footer' => $foot,
            'table_signature' => $this->tableSignature($sign),
            'source' => $source,
            'periode' => date("F Y", strtotime($y."-".$m)),
            'position' => strtoupper($pos),
            'refresh' => $refresh,
            'btnArch' => $btnArch
        );

        return json_encode($val);
    }

    public function needsec(){
        return view('payroll_new.needsec');
    }
    public function submitNeedsec(Request $request){
        $this->validate($request,[
            'searchInput' => 'required'
        ]);
        if ($request['searchInput'] == 'koi999'){
            Session::put('seckey_payroll', 99);
            return redirect()->route('payroll_new.index');
        } else {
            return redirect()->back()->with('message_needsec_fail', 'Access Denied! Please enter the correct code');
        }
    }

    function update(Request $request){
        // dd($request);
        if ($request->type == "all"){
            $whereType = " 1";
            $wherePos = "";
        } else {
            $whereType = " emp_type = ".$request->type;
            $wherePos = " emp_position = ".$request->type;
        }
        $emp = Hrd_employee::whereRaw($whereType)
            ->whereNull('expel')
            ->where('company_id', Session::get('company_id'))
            ->get();

        $empId = [];
        foreach($emp as $item){
            $empId[] = $item->id;
        }

        $period = $request->month."-".$request->years;
        Hrd_salary_archive_new::whereRaw("archive_period='".$period."'")
            ->whereIn('emp_id', $empId)
            ->where('company_id', Session::get('company_id'))
            ->delete();

        $salary_period = $request->years."-".sprintf("%02d", $request->month);

        Users_zakat::whereRaw("salary_period='".$salary_period."'")
            ->whereIn('emp_id', $empId)
            ->where('company_id', Session::get('company_id'))
            ->forceDelete();

        $data['error'] = 0;

        return json_encode($data);
    }

    public function print_btl(Request $request){
        $id_companies = Session::get('company_id');

        $t = $request->t;
        $m = $request->m;
        $y = $request->y;

        $pref = Preference_config::where('id_company', $id_companies)->get();

        $prefCount = $pref->count();
        $now = date('Y-m-d');

        if ($prefCount >0){
            $period_end = $pref[0]->period_end;
            $period_start = $pref[0]->period_start;
        } else {
            if (session()->has('company_period_end') && session()->has('company_period_start')){
                $period_end = Session::get('company_period_end');
                $period_start = Session::get('company_period_start');
            } else {
                $period_end = 27;
                $period_start = 28;
            }
        }

        $thr_period_temp = explode("\n", Session::get('company_thr_period'));
        $thr_period = [];
        foreach($thr_period_temp as $item){
            $thr_period[] = str_replace("\r", "", $item);
        }
        if($t == "all"){
            $emp = Hrd_employee::where('expel', null)
                ->where('company_id', $id_companies)
                ->whereNull("freeze")
                ->orderBy('emp_name')
                ->get();
        } else {
            $emp = Hrd_employee::where('emp_type', $t)
                ->where('expel', null)
                ->where('company_id', $id_companies)
                ->whereNull("freeze")
                ->orderBy('emp_name')
                ->get();
        }

        $emp_name = [];
        $emp_pos = [];
        $emp_bank = [];
        $emp_type = [];
        $data_emp = [];

        foreach ($emp as $key => $value) {
            $emp_name[$value->id] = $value->emp_name;
            $emp_pos[$value->id] = $value->emp_position;
            $emp_bank[$value->id] = $value->bank_acct;
            $emp_type[] = $value->id;
            $data_emp[$value->id] = $value;
        }

        $emp_arc = Hrd_salary_archive_new::where('company_id',$id_companies)->get();

        $eType = Hrd_employee_type::all();
        $detType = [];
        foreach ($eType as $value){
            $detType[$value->id] = $value;
        }

        $emp_his = Hrd_employee_history::where('activity', 'in')->get();

        foreach ($emp_his as $key => $value) {
            $act_date[$value->company_id][$value->emp_id] = $value->act_date;
        }

        $sign = $this->signName($t);

        $period_start_date = $y."-".sprintf('%02d', $m-1)."-".$period_start;
        if($m-1 == 0){
            $period_start_date = ($y - 1)."-12-".$period_start;
        }
        $period_end_date = $y."-".sprintf('%02d', $m)."-".$period_end;

        $ovt = Hrd_overtime::where('company_id', $id_companies)
            ->whereBetween('ovt_date', [$period_start_date, $period_end_date])
            ->get();
        $ovt_date = [];
        foreach ($ovt as $key => $value) {
            $time_in[$value->emp_id][] = $value->time_in;
            $time_out[$value->emp_id][] = $value->time_out;
            $ovt_date[$value->emp_id][] = $value->ovt_date;
        }

        $to = General_travel_order::whereNotNull('action_time')
            ->whereRaw("(departure_dt >= '".$period_start_date."' and return_dt <= '".$period_end_date."')")
            ->orWhereRaw("(return_dt >= '".$period_start_date."' and departure_dt <= '".$period_end_date."')")
            ->where('company_id', Session::get('company_id'))
            ->where('status', 0)
            ->get();

        foreach ($to as $key => $value) {
            if ($value->departure_dt < $period_start_date){
                $d2 = date('Y-m-d', strtotime($period_start_date." -1 day"));
            } else {
                $d2 = $value->departure_dt;
            }

            if ($value->return_dt < $period_end_date){
                $d1 = $value->return_dt;
            } else {
                $d1 = $period_end_date;
            }
            // $d1 = ($value->return_dt >= $period_end_date) ? date("Y-m-d", strtotime($period_end_date." +1 day")) : $value->return_dt;
            // $d2 = ($value->departure_dt <= $period_start_date) ? date('Y-m-d', strtotime($period_start_date." -1 day")) : $value->departure_dt;

            $sum = date_diff(date_create($d1), date_create($d2));

            if ($value->travel_type == "reg") {
                if ($value->location_rate == "SWT") {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_swt[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                } elseif ($value->location_rate == "DGR") {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_dgr[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                } else {
                    switch ($value->dest_type) {
                        case "fld" :
                            $fld_day[$value->employee_id][] = $sum->format("%a");
                            break;
                        case "wh" :
                            $wh_day[$value->employee_id][] = $sum->format("%a");
                            break;
                    }
                }
            } elseif ($value->travel_type = "odo") {
                if (empty($value->location_rate)) {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_day[$value->employee_id][] = $sum->format("%a");
                    }
                } elseif ($value->location_rate == "SWT") {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_swt[$value->employee_id][] = $sum->format("%a");
                    }
                } elseif ($value->location_rate == "DGR") {
                    if ($value->dest_type == "fld_bonus") {
                        $odo_dgr[$value->employee_id][] = $sum->format("%a");
                    }
                }
            }
        }

        $whereLoan = $y."-".sprintf("%02d", $m);

        $loan = Hrd_employee_loan::whereNotNull("approved_at")->where("company_id", Session::get("company_id"))->get();
        foreach($loan as $item){
            $loanEmp[$item->emp_id][] = $item->id;
        }

        $loan_det = Hrd_employee_loan_payment::where('date_of_payment', 'like', $whereLoan."%")->get();
        foreach($loan_det as $item){
            $loanDet[$item->loan_id] = $item->amount;
        }

        $bonus_pay = Hrd_bonus_payment::where('date_of_payment', 'like', $whereLoan."%")->get();
        foreach ($bonus_pay as $value) {
            $bonusPay[$value->bonus_id][] = $value->amount;
        }

        $foot['sum_salary'] = 0;
        $foot['sum_ovt'] = 0;
        $foot['sum_fld'] = 0;
        $foot['sum_wh'] = 0;
        $foot['sum_odo'] = 0;
        $foot['sum_tk'] = 0;
        $foot['sum_ks'] = 0;
        $foot['sum_jshk'] = 0;
        $foot['sum_tot_salary'] = 0;
        $foot['sum_sunction'] = 0;
        $foot['sum_absence'] = 0;
        $foot['sum_loan'] = 0;
        $foot['sum_ded_tk'] = 0;
        $foot['sum_ded_ks'] = 0;
        $foot['sum_ded_jshk'] = 0;
        $foot['sum_bonus'] = 0;
        $foot['sum_thr'] = 0;
        $foot['sum_pph21'] = 0;
        $foot['sum_prop'] = 0;
        $foot['sum_thp'] = 0;
        $foot['sum_voucher'] = 0;
        $foot['sum_sanction'] = 0;

        $rangeStart = $y."-".($m-1)."-".$period_start;
        $rangeEnd = $y."-".$m."-".$period_end;
        $pro_n_day = date("t", strtotime($rangeEnd));

        $config_company = ConfigCompany::find(Session::get('company_id'));

        $bonus = Hrd_bonus::all();
        $bonusEmp = [];
        foreach ($bonus as $value) {
            $bonusEmp[$value->emp_id][] = $value->id;
        }

        $bank_acct = Hrd_employee_bank::whereIn("emp_id", $emp->pluck("id"))
            ->get();

        $bank_pref = Master_banks::all();

        $bank_prior = Preference_bank_priority::orderBy('priority')->get();

        if (empty(Session::get('company_period_archive'))) {
            $period_archive = "10";
        } else {
            $period_archive = Session::get('company_period_archive');
        }

        $p_archive = $request->y."-".sprintf("%02d", $m)."-".$period_archive;

        if ($now >= $p_archive){
            if($t == "all"){
                $allemp = Hrd_employee::whereRaw("(expel is null or expel > '$period_end_date')")
                    ->whereNull("freeze")
                    ->where("company_id", $id_companies)->get();
            } else {
                $allemp = Hrd_employee::where('emp_type', $t)
                    ->whereRaw("(expel is null or expel > '$period_end_date')")
                    ->whereNull("freeze")
                    ->where("company_id", $id_companies)
                    ->get();
            }

            $emp_type = $allemp->pluck('id');

            $emp_arc = Hrd_salary_archive_new::where('archive_period', intval($m)."-".$y)
                ->where('company_id', $id_companies)
                ->whereIn('emp_id', $emp_type) //wherein
                // ->orWhere('category', 'like', "%$_t->name%")
                ->get();

            // dd(\DB::getQueryLog(), $period_start_date);

            $emp_name = $allemp->pluck("emp_name", 'id');
            $emp_pos = $allemp->pluck("emp_position", 'id');
            $emp_bank = $allemp->pluck("bank_acct", 'id');
            $temp = $allemp->pluck("emp_type", 'id');
            $emp_comp = $allemp->pluck("company_id", 'id');

            if (count($emp_arc) > 0) {
                foreach ($emp_arc as $key => $value) {
                    $row = [];
                    $salary_emp = base64_decode($value->wages);
                    $nonwages = base64_decode($value->non_wages);
                    $value->voucher = base64_decode($value->performance_bonus);

                    $allow_jkk = ($value->allow_jkk == "") ? 0 : $value->allow_jkk;
                    $allow_bpjs_kes = ($value->allow_bpjs_kes == "") ? 0 : $value->allow_bpjs_kes;
                    $allow_jp = ($value->allow_jp == "") ? 0 : $value->allow_jp;

                    $foot['sum_tk'] += $allow_jkk;
                    $foot['sum_ks'] += $allow_bpjs_kes;
                    $foot['sum_jshk'] += $allow_jp;

                    $deduc_bpjs_tk = ($value->deduc_bpjs_tk == "") ? 0 : $value->deduc_bpjs_tk;
                    $deduc_bpjs_kes = ($value->deduc_bpjs_kes == "") ? 0 : $value->deduc_bpjs_kes;
                    $deduc_jkk = ($value->deduc_jkk == "") ? 0 : $value->deduc_jkk;

                    $foot['sum_ded_tk'] += $deduc_bpjs_tk;
                    $foot['sum_ded_ks'] += $deduc_bpjs_kes;
                    $foot['sum_ded_jshk'] += $deduc_jkk;

                    $sunction = 0;
                    $sanction = Hrd_sanction::where('emp_id', $value->emp_id)
                        ->whereNotNull('approved_by')
                        ->whereBetween('sanction_date',[$rangeStart,$rangeEnd])
                        ->get();
                    foreach ($sanction as $key => $valSanc){
                        $sunction += intval($valSanc->sanction_amount);
                    }


                    $sal = base64_decode($value->wages);

                    $hours = 0;

                    if($value->ovt_nom > 0){
                        if($value->ovt_rate > 0){
                            $hours = $value->ovt_nom / $value->ovt_rate * 3600;
                        }
                    }

                    $ovt_total = $value->ovt_nom;
                    $whday = 0;
                    $fldday = 0;
                    $fldswtday = 0;
                    $fldgrday = 0;
                    $ododay = 0;
                    $odoswtday = 0;
                    $odogrday = 0;

                    $foot['sum_ovt'] += $ovt_total;
                    if($value->field_nom > 0){
                        if($value->field_rate > 0){
                            $fldday = $value->field_nom / $value->field_rate;
                        }
                    }

                    if($value->fld_swt > 0){
                        $fldswtday = $value->fld_swt / ($value->field_rate + 50000);
                    }

                    if($value->fld_dgr > 0){
                        $fldgrday = $value->fld_dgr / ($value->field_rate + 25000);
                    }

                    if($value->wh_nom > 0){
                        if($value->wh_rate > 0){
                            $whday = $value->wh_nom / $value->wh_rate;
                        }
                    }

                    if($value->odo_nom > 0){
                        if($value->odo_rate > 0){
                            $ododay = $value->odo_nom / $value->odo_rate;
                        }
                    }

                    if($value->odo_swt > 0){
                        $odoswtday = $value->odo_swt / ($value->odo_rate + 25000);
                    }

                    if($value->odo_dgr > 0){
                        $odogrday = $value->odo_dgr / ($value->odo_rate + 50000);
                    }

                    $fld = $value->field_rate * intval($fldday);
                    $flddgr = ($value->field_rate + 25000) * $fldgrday;
                    $fldswt = ($value->field_rate + 50000) * $fldswtday;

                    $foot['sum_fld'] += $fld + $flddgr + $fldswt;

                    $wh = $value->wh_nom;

                    $foot['sum_wh'] += $wh;

                    $ododay = (empty($odo_day[$value->emp_id])) ? "0" : $odo_day[$value->emp_id];
                    $odoswtday = (empty($odo_swt[$value->emp_id])) ? "0" : $odo_swt[$value->emp_id];
                    $odogrday = (empty($odo_dgr[$value->emp_id])) ? "0" : $odo_dgr[$value->emp_id];

                    $odo = $value->odo_rate * $ododay;
                    $ododgr = ($value->odo_rate + 25000) * $odogrday;
                    $odoswt = ($value->odo_rate + 50000) * $odoswtday;

                    $foot['sum_odo'] += $odo + $ododgr + $odoswt;

                    $ln_amt = $value->deduction;

                    $foot['sum_loan'] += $ln_amt;

                    $bonus_amt = $value->bonus;

                    $_emp_bank = $bank_acct->where("emp_id", $value->emp_id);
                    $sel_bank = [];
                    if(count($bank_prior) > 0){
                        foreach($bank_prior as $bpref){
                            $bprefemp = $_emp_bank->where("bank_id", $bpref->bank_id)->first();
                            if(!empty($bprefemp)){
                                $sel_bank = $bprefemp;
                                break;
                            }
                        }
                        if(empty($sel_bank)){
                            $sel_bank = $_emp_bank->first();
                        }
                    } else {
                        $sel_bank = $_emp_bank->first();
                    }

                    $override = $_emp_bank->where('override', 1)->first();
                    if(!empty($override)){
                        $sel_bank = $override;
                    }

                    if(empty($sel_bank)){
                        $bnumber = "0";
                    } else {
                        $bnumber = $sel_bank->bank_number;
                    }

                    $row['bank_account'] = $bnumber;
                    $row['bank_code'] = $sel_bank->bank_id ?? null;
                    $row['emp_name'] = $data_emp[$value->emp_id]->emp_name;
                    $row['position'] = $data_emp[$value->emp_id]->emp_position;

                    $foot['sum_salary'] += $sal;
                    $foot['sum_ovt'] += $ovt_total;
                    $foot['sum_voucher'] += $value->voucher;
                    $total_sal = $sal + $nonwages + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->voucher + $value->allow_jp + $value->allow_bpjs_kes + $value->allow_jkk + $bonus_amt;
                    $foot['sum_tot_salary'] += $total_sal;


                    $thr_total = 0;

                    $thp_total = $total_sal + $thr_total - $sunction - $ln_amt - $deduc_jkk - $deduc_bpjs_tk - $value->deduc_pph21 - $deduc_bpjs_kes - $value->voucher;

                    $foot['sum_thr'] += $thr_total;

                    $thp_total -= $value->proportional;
                    $foot['sum_thp'] += $value->proportional;

                    $foot['sum_pph21'] += $value->deduc_pph21;
                    $row['thp'] = number_format($thp_total,2); //THP
                    $row['emp_id'] = $value->emp_id;
                    $row['company_id'] = $value->company_id;

                    $foot['sum_prop'] += $value->proportional;

                    $data[] = $row;
                    $source = "Archive";

                }
                $error = 0;
            } else {
                $error = 1;
                $data = null;
                $source = null;
            }
        } else {
            if (count($emp) > 0) {
                foreach ($emp as $key => $value) {
                    $row = [];
                    $salary_emp = base64_decode($value->n_basic_salary ?? "MA==");
                    $sunction = 0;
                    $absence_deduct = 0;
                    $bonus_amt = 0;
                    $ln_amt = 0;
                    $hours = 0;
                    $empId = $value->id;

                    $allow_jkk = ($value->allow_jkk == "") ? 0 : $value->allow_jkk;
                    $allow_bpjs_kes = ($value->allow_bpjs_kes == "") ? 0 : $value->allow_bpjs_kes;
                    $allow_jkk = ($value->allow_jkk == "") ? 0 : $value->allow_jkk;

                    $foot['sum_tk'] += $allow_jkk;
                    $foot['sum_ks'] += $allow_bpjs_kes;
                    $foot['sum_jshk'] += $allow_jkk;

                    $deduc_bpjs_tk = ($value->deduc_bpjs_tk == "") ? 0 : $value->deduc_bpjs_tk;
                    $deduc_bpjs_kes = ($value->deduc_bpjs_kes == "") ? 0 : $value->deduc_bpjs_kes;
                    $deduc_jkk = ($value->deduc_jkk == "") ? 0 : $value->deduc_jkk;

                    $foot['sum_ded_tk'] += $deduc_bpjs_tk;
                    $foot['sum_ded_ks'] += $deduc_bpjs_kes;
                    $foot['sum_ded_jshk'] += $deduc_jkk;


                    $sal = $salary_emp + base64_decode($value->n_house_allow ?? "MA==") + base64_decode($value->n_health_allow ?? "MA==") + base64_decode($value->n_position_allow ?? "MA==");
                    $nonwages = base64_decode($value->n_transport_allow ?? "MA==") + base64_decode($value->n_meal_allow ?? "MA==");
                    $pb = base64_decode($value->n_performance_bonus ?? "MA==");

                    if (!empty($time_in[$value->id])) {
                        for ($i=0; $i < count($time_in[$value->id]); $i++) {
                            $otdate = date("N", strtotime($ovt_date[$value->id][$i]));
                            if($otdate >= 6){
                                $diff = strtotime($time_out[$value->id][$i]) - strtotime($time_in[$value->id][$i]);
                                $hours += $diff;
                            } else {
                                $ovtStartTime = Session::get('company_penalty_stop');
                                if(!empty($ovtStartTime)){
                                    $diff = strtotime($time_out[$value->id][$i]) - strtotime($ovtStartTime);
                                    $hours += $diff;
                                }
                            }
                        }
                    }

                    $hours = $hours < 0 ? 0 : $hours;

                    $ovt_total = $value->overtime * ceil(($hours / 3600));

                    $foot['sum_ovt'] += $ovt_total;
                    $whday = (!isset($wh_day[$value->id])) ? "0" : $wh_day[$value->id];
                    $fldday = (!isset($fld_day[$value->id])) ? "0" : array_sum($fld_day[$value->id]);
                    $fldswtday = (!isset($fld_swt[$value->id])) ? "0" : array_sum($fld_swt[$value->id]);
                    $fldgrday = (!isset($fld_dgr[$value->id])) ? "0" : array_sum($fld_dgr[$value->id]);

                    $fld = intval($value->fld_bonus) * intval($fldday);
                    $flddgr = (intval($value->fld_bonus) + 25000) * $fldgrday;
                    $fldswt = (intval($value->fld_bonus) + 50000) * $fldswtday;

                    $foot['sum_fld'] += $fld + $flddgr + $fldswt;

                    $wh = $value->wh_bonus * $whday;

                    $foot['sum_wh'] += $wh;

                    $ododay = (empty($odo_day[$value->id])) ? "0" : $odo_day[$value->id];
                    $odoswtday = (empty($odo_swt[$value->id])) ? "0" : $odo_swt[$value->id];
                    $odogrday = (empty($odo_dgr[$value->id])) ? "0" : $odo_dgr[$value->id];

                    $odo = $value->odo_bonus * $ododay;
                    $ododgr = ($value->odo_bonus + 25000) * $odogrday;
                    $odoswt = ($value->odo_bonus + 50000) * $odoswtday;

                    $foot['sum_odo'] += $odo + $ododgr + $odoswt;

                    if(isset($loanEmp[$value->id])){
                        foreach($loanEmp[$value->id] as $lEmp){
                            if (isset($loanDet[$lEmp])){
                                $ln_amt += $loanDet[$lEmp];
                            }
                        }
                    }

                    $foot['sum_loan'] += $ln_amt;

                    foreach ($bonus as $keyBonus => $valueBonus) {
                        if ($value->id == $valueBonus->emp_id) {
                            foreach ($bonus_pay as $keyBonusPay => $valueBonusPay) {
                                if ($valueBonus->id == $valueBonusPay->bonus_id) {
                                    $bonus_amt += $valueBonusPay->amount;
                                }
                            }
                        }
                    }

                    $yearly_bonus = $value->yearly_bonus * $salary_emp + $value->fx_yearly_bonus;
                    $bonus_only = $value->yearly_bonus * $salary_emp;

                    $_emp_bank = $bank_acct->where("emp_id", $value->id);
                    $sel_bank = [];
                    if(count($bank_prior) > 0){
                        foreach($bank_prior as $bpref){
                            $bprefemp = $_emp_bank->where("bank_id", $bpref->bank_id)->first();
                            if(!empty($bprefemp)){
                                $sel_bank = $bprefemp;
                                break;
                            }
                        }
                        if(empty($sel_bank)){
                            $sel_bank = $_emp_bank->first();
                        }
                    } else {
                        $sel_bank = $_emp_bank->first();
                    }

                    $override = $_emp_bank->where('override', 1)->first();
                    if(!empty($override)){
                        $sel_bank = $override;
                    }

                    if(empty($sel_bank)){
                        $bnumber = "0";
                    } else {
                        $bnumber = $sel_bank->bank_number;
                    }

                    // Datatable
                    $row['bank_account'] = $bnumber;
                    $row['bank_code'] = $sel_bank->bank_id ?? null;
                    $row['emp_name'] = $value->emp_name;
                    $row['position'] = $value->emp_position;

                    $foot['sum_salary'] += $sal;
                    $foot['sum_ovt'] += $ovt_total;
                    $foot['sum_voucher'] += $value->voucher;
                    $total_sal = $sal + $ovt_total + $fld + $wh + $odo + $ododgr + $odoswt + $flddgr + $fldswt + $value->allow_jkk + $value->allow_bpjs_kes + $value->allow_jp + $nonwages + $pb;
                    $foot['sum_tot_salary'] += $total_sal;

                    $isThr = sprintf("%02d", $m)."-".$y;
                    $date_join = (isset($act_date[$value->company_id][$value->id])) ? $act_date[$value->company_id][$value->id] : "0000-00-00";
                    $mntDiff = 0;
                    $dIn = 0;
                    if ($date_join != "0000-00-00") {
                        $dateIn = date_create($date_join);
                        $dateNow = date_create(date("Y-m-d", strtotime($rangeEnd." +1 day")));
                        $diff = date_diff($dateIn, $dateNow);
                        $mntDiff = $diff->format("%y-%m");
                        $dIn = date('d', strtotime($date_join));
                    }

                    $wPeriod = explode("-", $mntDiff);

                    $mnt = 0;
                    if($wPeriod[0] >= 1){
                        $mnt = 12 * $wPeriod[0];
                    }

                    $mnt += end($wPeriod);

                    if($dIn > $period_start){
                        // $mnt -= 1;
                    }

                    $prob = [];
                    $vcr = [];
                    $_thr = [];
                    if(isset($detType[$value->emp_type])){
                        $prob = json_decode($detType[$value->emp_type]['no_probation'], true);
                        $vcr = json_decode($detType[$value->emp_type]['with_voucher'], true);
                        $_thr = json_decode($detType[$value->emp_type]['disable_thr'], true);
                    }

                    $minThr = 3;
                    $maxThr = 15;

                    if(isset($prob[$value->company_id])){
                        if($prob[$value->company_id] == 1){
                            $minThr = 0;
                            $maxThr = 12;
                        }
                    } elseif (isset($prob[$config_company->id_parent])){
                        if($prob[$config_company->id_parent] == 1){
                            $minThr = 0;
                            $maxThr = 12;
                        }
                    }

                    $with_voucher = 0;
                    if(isset($vcr[$value->company_id])){
                        if($vcr[$value->company_id]){
                            $with_voucher = $value->voucher;
                        }
                    } elseif (isset($vcr[$config_company->id_parent])){
                        if($vcr[$config_company->id_parent] == 1){
                            $with_voucher = $value->voucher;
                        }
                    }


                    $disable_thr = 0;
                    if(isset($_thr[$value->company_id])){
                        if($_thr[$value->company_id]){
                            $disable_thr = $_thr[$value->company_id];
                        }
                    } elseif (isset($_thr[$config_company->id_parent])){
                        if($_thr[$config_company->id_parent] == 1){
                            $disable_thr = $_thr[$value->company_id];
                        }
                    }

                    if($mnt <= $minThr){
                        $thr_num = 0;
                    } elseif($mnt > $minThr && $mnt < $maxThr){
                        $thr_num = (($mnt - $minThr) / 12) * ($sal + $value->allowance_office + $with_voucher);
                    } elseif($mnt >= $maxThr) {
                        $thr_num = ($sal + $value->allowance_office + $with_voucher);
                    }

                    if (in_array($isThr, $thr_period)){
                        $thr_total = ($disable_thr == 0) ? $thr_num : 0;
                    } else {
                        $thr_total = 0;
                    }

                    $thr_total = 0;

                    $foot['sum_thr'] += $thr_total;
                //    $row[] = number_format($thr_total,2); //THR

                    $thp = $total_sal - $sunction - $absence_deduct - $ln_amt - $value->deduc_bpjs_tk - $value->deduc_bpjs_kes - $value->deduc_jkk - $value->deduc_pph21 + $thr_total - $pb;
                    $xthp = $thp - $fld - $wh - $odo - $ododgr - $odoswt - $fldswt - $flddgr;
                    $pro_day = round((strtotime($act_date[$value->company_id][$empId]) - strtotime($rangeStart)) / 86400,0);
                    $in_date = $act_date[$value->company_id][$empId];
                    $zero_day = (strtotime($rangeEnd) - strtotime($act_date[$value->company_id][$empId])) / 86400;
                    if($pro_day > 0 && $pro_day <= $pro_n_day)
                    {
                        $pro_basis = $pro_n_day;
                        $pro_thp = $pro_day / $pro_basis * $xthp;
                        $pro_decrement = ($pro_day) / $pro_basis * $xthp;
                    }
                    //kalau hari masuk = start month gaji, pengurangan = gaji = ZERO gaji.
                    elseif($pro_day == 0)
                    {
                        // $pro_decrement = $xthp;
                        if(date('d',strtotime($in_date)) == 16)
                        {
                            $pro_decrement = 0;
                        }
                        else
                        {
                            $pro_decrement = $xthp;
                        }
                    }
                    //tidak ada pemotongan
                    else
                    {
                        $pro_thp = 0;
                        $pro_decrement = 0;
                    }

                    //kalau tgl masuk baru lebih baru dari range2. ZERO gaji
                    if($zero_day <= 0)
                    {
                        $pro_decrement = $xthp;
                    }

                    if($pro_day >= 0 && $pro_day <= 30) {
                        $total_decrement = $pro_decrement;
                        $thp_total = $thp - $pro_decrement;
                        $foot['sum_thp'] += $thp - $pro_decrement;
                    } elseif($zero_day <= 0) {
                        $total_decrement = $pro_decrement;
                        $thp_total = $thp - $pro_decrement;
                        $foot['sum_thp'] += $thp - $pro_decrement;
                    } else {
                        $foot['sum_thp'] += $thp;
                        $thp_total = $thp;
                        $total_decrement = 0;
                    }

                    $foot['sum_pph21'] += $value->deduc_pph21;
                    $row['thp'] = number_format($thp_total,2); //THP
                    $row['emp_id'] = $value->id;
                    $row['company_id'] = $value->company_id;

                    $foot['sum_prop'] += $total_decrement;

                    $data[] = $row;

                }
                $error = 0;
                $source = "EMP";
            } else {
                $error = 1;
                $data = null;
                $source = null;
            }
        }


        // $rep_bank_code = array("002" => "BRI","008" => "MANDIRI","009" => "BNI","120" => "SUMSEL","014" => "BCA");

        $rep_bank_code = FacadesDB::table('master_banks')->pluck('bank_name', 'id');

        $empType = Hrd_employee_type::withTrashed()->get();
        $typeEmp = [];
        foreach($empType as $item){
            $typeEmp[$item->id] = $item->name;
        }

        $val = array(
            'error' => $error,
            'data' => $data,
            't' => $t,
            'periode' => date('F Y', strtotime($y."-".$m)),
            'bank_code' => $rep_bank_code,
            'source' => $source,
            'emp_type' => $typeEmp
        );

        if ($request->act == 'remarks'){
            $view = "payroll_new.btl_remarks";
        } else {
            $view = "payroll_new.btl";
        }

        $data_remarks = Hrd_salary_remarks::where('periode',  $y."-".$m)
            ->where('company_id', Session::get('company_id'))
            ->get();
        $remarks = array();
        foreach ($data_remarks as $item){
            $remarks[$item->emp_id] = $item;
        }

        return view($view, [
            'data' => $val,
            'remarks' => $remarks
        ]);
    }

    function save_remarks(Request $request){
        $thp = $request->thp;
        $thp_old = $request->thp_old;
        $remarks = $request->remarks;

        foreach ($thp as $thpKey => $thpValue){
            if ($thpValue != $thp_old[$thpKey]){
                $find = Hrd_salary_remarks::where('periode', $request->periode)
                    ->where('emp_id', $thpKey)
                    ->where('company_id', Session::get('company_id'))
                    ->first();
                if (empty($find)){
                    $hrd_remarks = new Hrd_salary_remarks();
                    $hrd_remarks->emp_id = $thpKey;
                    $hrd_remarks->company_id = Session::get('company_id');
                    $hrd_remarks->periode = $request->periode;
                    $hrd_remarks->thp = $thpValue;
                    $hrd_remarks->remarks = $remarks[$thpKey];
                    $hrd_remarks->save();
                } else {
                    $hrd_remarks = Hrd_salary_remarks::find($find->id);
                    $hrd_remarks->thp = $thpValue;
                    $hrd_remarks->remarks = $remarks[$thpKey];
                    $hrd_remarks->save();
                }
            }
        }

        return redirect()->back();
    }

    function print_slip($id, $period){
        $emp = Hrd_employee::find($id);
        $xperiod = explode("-", $period);
        $archive = Hrd_salary_archive_new::where('emp_id', $id)
            ->where('archive_period', $xperiod[1]."-".$xperiod[0])
            ->firstOrFail();
        $pref = Preference_config::where('id_company', $emp->company_id)->first();

        $total_sal = base64_decode($archive->salary) + $archive->voucher + $archive->allow_jkk + $archive->allow_bpjs_kes + $archive->allow_jkk;

        $yearly_bonus = 0;
        if ($xperiod[1] == $pref->bonus_period) {
            $yearly_bonus = $emp->yearly_bonus * $total_sal;
        }

        $comp = ConfigCompany::find($emp->company_id);

        $arr_thr_period = explode('\n', $pref->thr_period);
            $arr_thr_period[0] = strip_tags($arr_thr_period[0]);
            $thr_period = str_pad($xperiod[1], 2, '0', STR_PAD_LEFT).'-'.$xperiod[0];

        $total_deduc = $archive->deduction + $archive->lateness + $archive->deduc_pph21 + $archive->deduc_jkk + $archive->deduc_bpjs_tk + $archive->deduc_bpjs_kes;

        $salary_total = $total_sal + $archive->wh_nom + $archive->odo_nom + $archive->field_nom + $archive->bonus + $archive->ovt_nom + $archive->thr;

        $view = view('payroll_new.slip', [
            'emp' => $emp,
            'archive' => $archive,
            'period' => $period,
            'total_sal' => $salary_total,
            'total_deduc' => $total_deduc,
            'yearly_bonus' => $yearly_bonus,
            'pref' => $comp
        ]);

        // return $view;

        $mpdf = new Mpdf([
            'default_font_size' => 11,
            'default_font' => 'arial',
            'tempDir'=>storage_path('tempdir')
        ]);
        $mpdf->SetAuthor('PT. ');
        $mpdf->SetTitle('Payroll Total Archive');
        $mpdf->SetSubject('total archive');
        $mpdf->SetKeywords('archive, PDF');

        $mpdf->SetDisplayMode('fullpage');
        // $stylesheet = file_get_contents('mpdf-style-payslip.css');
        // $mpdf->WriteHTML($stylesheet,1);
        $mpdf->writeHtml($view);
        // $mpdf->RestartDocTemplate();
        $mpdf->Output('Payroll Payslip.pdf','I');
    }

    function print_new_slip($id, $period){
        $emp = Hrd_employee::find($id);
        $xperiod = explode("-", $period);
        $archive = Hrd_salary_archive_new::where('emp_id', $id)
            ->where('archive_period', $xperiod[1]."-".$xperiod[0])
            ->firstOrFail();
        $pref = Preference_config::where('id_company', $emp->company_id)->first();

        $archive->voucher = base64_decode($archive->performance_bonus);

        $total_sal = base64_decode($archive->wages) + base64_decode($archive->non_wages) + $archive->voucher + $archive->allow_jkk + $archive->allow_bpjs_kes + $archive->allow_jp;

        // $total_sal = base64_decode($emp->n_basic_salary) + base64_decode($emp->n_house_allow) + base64_decode($emp->n_health_allow) + base64_decode($emp->n_transport_allow) + base64_decode($emp->n_meal_allow) + base64_decode($emp->n_position_allow) + $archive->allow_jkk + $archive->allow_bpjs_kes + $archive->allow_jkk;

        $yearly_bonus = 0;
        if ($xperiod[1] == $pref->bonus_period) {
            $yearly_bonus = $emp->yearly_bonus * $total_sal;
        }

        $comp = ConfigCompany::find($emp->company_id);

        $arr_thr_period = explode('\n', $pref->thr_period);
            $arr_thr_period[0] = strip_tags($arr_thr_period[0]);
            $thr_period = str_pad($xperiod[1], 2, '0', STR_PAD_LEFT).'-'.$xperiod[0];

        $total_deduc = $archive->deduction + $archive->lateness + $archive->deduc_pph21 + $archive->deduc_jkk + $archive->deduc_bpjs_tk + $archive->deduc_bpjs_kes + $archive->voucher;

        $salary_total = $total_sal + $archive->wh_nom + $archive->odo_nom + $archive->field_nom + $archive->bonus + $archive->ovt_nom + $archive->thr;

        $grade = \App\Models\Career_path::find($emp->grade);
        $tp = \App\Models\Hrd_employee_type::find($emp->emp_type);
        $div = \App\Models\Division::find($emp->division);

        $view = view('payroll_new.new_slip', [
            'emp' => $emp,
            'archive' => $archive,
            'period' => $period,
            'total_sal' => $salary_total,
            'total_deduc' => $total_deduc,
            'yearly_bonus' => $yearly_bonus,
            'pref' => $comp,
            'grade' => $grade,
            'tp' => $tp,
            'div' => $div,
        ]);

        // return $view;

        $mpdf = new Mpdf([
            'default_font_size' => 11,
            'default_font' => 'arial',
            'tempDir'=>storage_path('tempdir')
        ]);
        $mpdf->SetAuthor('PT. ');
        $mpdf->SetTitle('Payroll Total Archive');
        $mpdf->SetSubject('total archive');
        $mpdf->SetKeywords('archive, PDF');

        $mpdf->SetDisplayMode('fullpage');
        // $stylesheet = file_get_contents('mpdf-style-payslip.css');
        // $mpdf->WriteHTML($stylesheet,1);
        $mpdf->writeHtml($view);
        // $mpdf->RestartDocTemplate();
        $mpdf->Output('Payroll Payslip.pdf','I');
    }

    function get_total_payroll(Request $request){
        $configCompany = ConfigCompany::find(Session::get('company_id'));
        $id_companies = [];
        if(!empty($configCompany->id_parent)){
            $id_companies[] = $configCompany->id_parent;
        } else {
            $companyChild = ConfigCompany::where('id_parent', $configCompany->id_parent)->get();
            foreach($companyChild as $item){
                $id_companies[] = $item->id;
            }
        }

        $data['type'] = Hrd_employee_type::whereIn('company_id', $id_companies)
            ->where('company_exclude', 'not like', '%"'.$configCompany->id.'"%')
            ->orWhereNull("company_exclude")
            ->get();

        $archive = Hrd_salary_archive_new::where('archive_period', $request->period)
            ->where('company_id', Session::get('company_id'))
            ->get();

        $row = [];

        if(count($archive) > 0){
            foreach($archive as $item){

            }
        } else {

        }
    }

    function share_payroll(Request $request){
        $type = $request->type;
        $month = $request->month;
        $year = $request->year;

        $pyshare = Hrd_salary_share::where("company_id", Session::get('company_id'))
            ->where("period", "$month-$year")
            ->where("type", $type)
            ->first();
        if(empty($pyshare)){
            $code = Str::random(8);
            $pyshare = Hrd_salary_share::where("code", $code)->first();
            while(!empty($pyshare)){
                $code = Str::random(8);
                $pyshare = Hrd_salary_share::where("code", $code)->first();
            }

            $pyshare = new Hrd_salary_share();
            $pyshare->code = $code;
            $pyshare->period = "$month-$year";
            $pyshare->issued_by = Auth::user()->username;
            $pyshare->type = $type;
            $pyshare->company_id = Session::get("company_id");
            $pyshare->save();
        }

        $data = [
            "success" => true,
            "url" => route("payroll_new.index", $pyshare->code)
        ];

        return json_encode($data);
    }
}
