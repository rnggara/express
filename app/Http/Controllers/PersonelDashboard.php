<?php

namespace App\Http\Controllers;

use App\Models\Asset_wh;
use App\Models\Hrd_employee;
use App\Models\Kjk_comp_departement;
use App\Models\Kjk_comp_job_level;
use App\Models\Master_gender;
use App\Models\Personel_employee_status;
use App\Models\Personel_history;
use App\Models\Personel_onboarding;
use App\Models\Personel_onboarding_detail;
use App\Models\Personel_profile;
use App\Models\Personel_resign;
use App\Models\User;
use App\Models\User_formal_education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PersonelDashboard extends Controller
{
    function index(Request $request){
        $state = "personel";
        Session::put("session_state", $state);
        Session::put("home_url", route("personel.index"));

        $data = [];

        $resigned = Personel_resign::where("company_id", Session::get("company_id"))
            ->where("resign_date", "<=", date("Y-m-d"))
            ->get();

        $emp = Hrd_employee::where("company_id", Session::get("company_id"))
            ->get();
        $emp_year = $emp->whereNotIn("id", $resigned->where("resign_date", ">=", date("Y")."-01-01")->pluck("emp_id"))->count();
        $emp_last_year = $emp->where("join_date", "<", date("Y")."01-01")->whereNotIn("id", $resigned->where("resign_date", "<", date("Y")."-01-01")->pluck("emp_id"));
        $new_emp = $emp->where("join_date", ">=", date("Y-m")."-01");
        $lmonth = date("Y-m", strtotime("-1 month"));
        $last_new_emp = $emp->where("join_date", ">=", $lmonth."-01")->where("join_date", "<", date("Y-m")."-01")->count();
        $turnover = $emp->whereNotIn("id", $resigned->where("resign_date", ">=", date("Y-m")."-01")->pluck("emp_id"))->count();

        $data['total_employee'] = $emp_year;
        $data['total_employee_pctg'] = $emp_last_year->count() == 0 ? 0 : (($emp_year - $emp_last_year->count()) / $emp_last_year->count() * 100);
        $data['new_employee'] = $new_emp->count();
        $data['new_employee_pctg'] = $last_new_emp == 0 ? 0 : (($new_emp->count() - $last_new_emp) / $last_new_emp * 100);
        $data['turnover'] = [];

        $master['locations'] = Asset_wh::office()->where("company_id", Session::get("company_id"))->get();
        $master['departements'] = Kjk_comp_departement::where("company_id", Session::get("company_id"))->get();
        $master['levels'] = Kjk_comp_job_level::where("company_id", Session::get("company_id"))->get();
        $master['ecat'] = ["onboarding" => "Orientasi", "promotion" => "Promosi", "expired_contract" => "Kontrak Kadaluwarsa", "resign" => "Keluar", "update_data" => "Perubahan Data", "document" => "Dokumen"];

        $emp_birth = Hrd_employee::selectRaw("*, month(emp_lahir) as month_lahir, DAY(emp_lahir) as day_lahir")->where("company_id", Session::get("company_id"))
            ->whereNotIn("id", $resigned->pluck("emp_id"))
            ->whereNotNull("emp_lahir")
            ->whereMonth("emp_lahir", date("n"))
            ->orderBy("month_lahir")
            ->orderBy("day_lahir")->get();
        $user_img = User::hris()->where("company_id", Session::get("company_id"))->pluck("user_img", "emp_id");
        if($request->a == "chart"){
            if($request->t == "employee_trend"){
                $chart['new_employee'] = [];
                $chart['resign'] = [];
                $xseries = [1=>"Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

                $y = $request->year ?? date("Y");

                $udept = [];
                if(!empty($request->dep)){
                    $udept = User::where("company_id", Session::get("company_id"))
                        ->where("uac_departement", $request->dep)
                        ->pluck("emp_id")->toArray();
                }

                $uloc = [];
                if(!empty($request->loc)){
                    $uloc = User::where("company_id", Session::get("company_id"))
                        ->where("uac_location", $request->loc)
                        ->pluck("emp_id")->toArray();
                }

                $emp = Hrd_employee::where("join_date", ">=", $y."-01-01")
                    ->where("company_id", Session::get("company_id"))
                    ->where(function($q) use($udept, $uloc, $request){
                        if(!empty($request->dep)){
                            $q->whereIn("id", $udept);
                        }

                        if(!empty($request->loc)){
                            $q->whereIn("id", $uloc);
                        }
                    })
                    ->get();

                $resigned = Personel_resign::where("company_id", Session::get("company_id"))
                    ->where("resign_date", ">=", $y."-01-01")
                    ->get();

                $_resEmp = [];

                foreach($resigned as $item){
                    $m = date("n", strtotime($item->resign_date));
                    $_resEmp[$m][] = $item->id;
                }

                foreach($xseries as $i => $m){
                    $col = [];
                    $col['x'] = $m;
                    $col['y'] = count($_resEmp[$i] ?? []);
                    $chart['resign'][] = $col;
                }



                $_newEmp = [];

                foreach($emp as $item){
                    $m = date("n", strtotime($item->join_date ?? $item->created_at));
                    $_newEmp[$m][] = $item->id;
                }

                foreach($xseries as $i => $m){
                    $col = [];
                    $col['x'] = $m;
                    $col['y'] = count($_newEmp[$i] ?? []);
                    $chart['new_employee'][] = $col;
                }

                $series = [];
                foreach($chart as $_key => $item){
                    $col = [];
                    $col['name'] = ucwords(str_replace("_", " ", $_key));
                    $col['data'] = $item;
                    $series[] = $col;
                }

                return json_encode([
                    "chart" => $series
                ]);
            }

            if($request->t == "time_service"){
                $lbl = ["<6 month", "6-12 month", "1-2 years", "2-4 years", "4-6 years", ">6 years"];
                $x = [0, 0, 0, 0, 0, 0];

                $udept = [];
                if(!empty($request->dep)){
                    $udept = User::where("company_id", Session::get("company_id"))
                        ->where("uac_departement", $request->dep)
                        ->pluck("emp_id")->toArray();
                }

                $resigned = Personel_resign::where("company_id", Session::get("company_id"))
                    ->where("resign_date", "<=", date("Y-m-d"))
                    ->get();

                $emp = Hrd_employee::where("company_id", Session::get("company_id"))
                    ->whereNotIn("id", $resigned->pluck("emp_id"))
                    ->where(function($q) use($request, $udept){
                        if(!empty($request->dep)){
                            $q->whereIn("id", $udept);
                        }
                    })
                    ->get();

                foreach($emp as $item){
                    $jdate = $item->join_date ?? $item->created_at;
                    $d1 = date_create($jdate);
                    $d2 = date_create(date("Y-m-d"));
                    $d3 = date_diff($d2, $d1);

                    $y = $d3->format("%y");
                    $m = $d3->format("%m");
                    $tm = $m + ($y * 12);
                    if($tm < 6){
                        $x[0] += 1;
                    } elseif($tm >= 6 && $tm < 12){
                        $x[1] += 1;
                    } elseif($tm >= 12 && $tm < 24){
                        $x[2] += 1;
                    } elseif($tm >= 24 && $tm < 48){
                        $x[3] += 1;
                    } elseif($tm >= 48 && $tm < 72){
                        $x[4] += 1;
                    } elseif($tm >= 72){
                        $x[5] += 1;
                    }
                }

                $chart = [
                    "label" => $lbl,
                    "data" => $x
                ];

                return json_encode($chart);
            }

            if($request->t == "ratio_employee"){
                $udept = [];
                if(!empty($request->dep)){
                    $udept = User::where("company_id", Session::get("company_id"))
                        ->where("uac_departement", $request->dep)
                        ->pluck("emp_id")->toArray();
                }

                $resigned = Personel_resign::where("company_id", Session::get("company_id"))
                    ->where("resign_date", "<=", date("Y-m-d"))
                    ->get();

                $emp = Hrd_employee::where("company_id", Session::get("company_id"))
                    ->where(function($q) use($request, $udept){
                        if(!empty($request->dep)){
                            $q->whereIn("id", $udept);
                        }
                    })->whereNotIn("id", $resigned->pluck("emp_id"))
                    ->get();

                $emp_list = [];

                $lists = [];
                $x = [];
                $clr = [];
                $emp_data = [];
                $edata = [];
                if($request->type == "education"){
                    $edu = User_formal_education::where("personel", 1)
                        ->whereIn("user_id", $emp->pluck("id"))
                        ->get();

                    $lists = $edu->pluck("degree")->toArray();
                    $emp_degree = $edu->pluck("degree", "user_id");

                    $emp = $emp->whereIn("id", $edu->pluck("user_id"));

                    foreach($edu as $item){
                        $emp_data[$item->user_id][] = $item;
                    }

                    $udeg = [];
                    $emp_list = $emp;
                    foreach($emp as $item){
                        $_deg = $emp_degree[$item->id];
                        $udeg[$_deg][] = $item->id;
                    }

                    $x = [];

                    $clr = [];

                    foreach($lists as $item){
                        $x[] = count($udeg[$item] ?? []);
                        $clr[] = $this->random_color();
                    }
                }

                if($request->type == "level"){
                    $level = Kjk_comp_job_level::where("company_id", Session::get('company_id'))
                        ->get();

                    $lname = $level->pluck("name", "id");

                    $edata = $lname;

                    $emp_ch = $emp->whereIn("job_level_id", $level->pluck("id"));

                    $udeg = [];
                    foreach($emp as $item){
                        if(isset($lname[$item->job_level_id])){
                            $udeg[$item->job_level_id][] = $item->id;
                            $emp_list[] = $item;
                        }
                    }

                    $lists = $level->whereIn("id", array_keys($udeg))->pluck("name")->toArray();

                    $x = [];

                    foreach($level->whereIn("id", array_keys($udeg)) as $item){
                        $x[] = count($udeg[$item->id] ?? []);
                        $clr[] = $this->random_color();
                    }
                }

                if($request->type == "division"){
                    $level = Kjk_comp_departement::where("company_id", Session::get('company_id'))
                        ->get();

                    $lname = $level->pluck("name", "id");

                    $edata = $lname;

                    // $emp_ch = $emp->whereIn("job_level_id", $level->pluck("id"));

                    $udeg = [];
                    foreach($emp as $item){
                        $dep_id = $item->user->uac_departement ?? null;
                        if(isset($lname[$dep_id])){
                            $udeg[$dep_id][] = $item->id;
                            $emp_list[] = $item;
                        }
                    }

                    $lists = $level->whereIn("id", array_keys($udeg))->pluck("name")->toArray();

                    $x = [];

                    foreach($level->whereIn("id", array_keys($udeg)) as $item){
                        $x[] = count($udeg[$item->id] ?? []);
                        $clr[] = $this->random_color();
                    }
                }

                if($request->type == "contract"){
                    $level = Personel_employee_status::where("company_id", Session::get('company_id'))
                        ->get();

                    $lname = $level->pluck("label", "id");

                    $edata = $lname;

                    // $emp_ch = $emp->whereIn("job_level_id", $level->pluck("id"));

                    $udeg = [];
                    foreach($emp as $item){
                        if(isset($lname[$item->employee_status_id])){
                            $udeg[$item->employee_status_id][] = $item->id;
                            $emp_list[] = $item;
                        }
                    }

                    $lists = $level->whereIn("id", array_keys($udeg))->pluck("label")->toArray();

                    $x = [];

                    foreach($level->whereIn("id", array_keys($udeg)) as $item){
                        $x[] = count($udeg[$item->id] ?? []);
                        $clr[] = $this->random_color();
                    }
                }

                if($request->type == "gender"){
                    $level = Master_gender::get();

                    $profiles = Personel_profile::whereIn("user_id", $emp->pluck("id"))
                        ->whereNotNull("gender")
                        ->get();
                    $_egen = $profiles->pluck("gender", "user_id");

                    $edata = $level->pluck("name", "id");

                    $emp_ch = $emp;

                    $udeg = [];
                    foreach($emp as $item){
                        $_gender = $_egen[$item->id] ?? [];
                        if(!empty($_gender)){
                            $udeg[$_gender][] = $item->id;
                            $emp_list[] = $item;
                        }
                    }

                    $lists = $level->whereIn("id", array_keys($udeg))->pluck("name")->toArray();

                    $x = [];

                    foreach($level->whereIn("id", array_keys($udeg)) as $item){
                        $x[] = count($udeg[$item->id] ?? []);
                        $clr[] = $this->random_color();
                    }
                }

                if($request->type == "location"){
                    $level = Asset_wh::office()->where('company_id', Session::get("company_id"))->get();

                    $uLoc = User::whereIn("uac_location", $level->pluck("id"))->get();

                    $_ul = $uLoc->pluck("uac_location", "emp_id");

                    $_egen = $level->pluck("name", "id");

                    $edata = $_egen;

                    $emp_ch = $emp->whereIn("id", $uLoc->pluck("emp_id"));

                    $udeg = [];
                    foreach($emp as $item){
                        $uid = $_ul[$item->id] ?? null;
                        $_l = $_egen[$uid] ?? null;
                        if(!empty($_l)){
                            $udeg[$uid][] = $item->id;
                            $emp_list[] = $item;
                        }
                    }

                    $lists = $level->whereIn("id", array_keys($udeg))->pluck("name")->toArray();

                    $x = [];

                    foreach($level->whereIn("id", array_keys($udeg)) as $item){
                        $x[] = count($udeg[$item->id] ?? []);
                        $clr[] = $this->random_color();
                        $emp_data[$item->id] = $item;
                    }
                }

                $content = [];
                if($request->modal == "true"){
                    $tp = $request->type;
                    $content = [
                        "tab" => view("_personel.widgets.modal.ratio_content", compact("lists", "x", "clr"))->render(),
                        "table" => view("_personel.widgets.modal.ratio_table", compact("lists", "x", "clr", 'emp_list', 'master', 'tp', 'emp_data', 'edata'))->render(),
                    ];
                }

                return json_encode([
                    "labels" => $lists,
                    "x" => $x,
                    'colors' => $clr,
                    "content" => $content
                ]);
            }

            if($request->t == "turnover_per_quartal"){
                $q1['start'] = date("Y")."-01-01";
                $q1['end'] = date("Y")."-03-".date("t", strtotime(date("Y")."-03"));
                $q2['start'] = date("Y")."-04-01";
                $q2['end'] = date("Y")."-06-".date("t", strtotime(date("Y")."-06"));
                $q3['start'] = date("Y")."-07-01";
                $q3['end'] = date("Y")."-09-".date("t", strtotime(date("Y")."-09"));
                $q4['start'] = date("Y")."-10-01";
                $q4['end'] = date("Y")."-12-31";

                $rQ['1'] = Personel_resign::where("company_id", Session::get("company_id"))
                    ->where(function($q) use($q1){
                        $q->where("resign_date", ">=", $q1['start']);
                        $q->where("resign_date", "<=", $q1['end']);
                    })->get();
                $rQ['2'] = Personel_resign::where("company_id", Session::get("company_id"))
                    ->where(function($q) use($q2){
                        $q->where("resign_date", ">=", $q2['start']);
                        $q->where("resign_date", "<=", $q2['end']);
                    })->get();
                $rQ['3'] = Personel_resign::where("company_id", Session::get("company_id"))
                    ->where(function($q) use($q3){
                        $q->where("resign_date", ">=", $q3['start']);
                        $q->where("resign_date", "<=", $q3['end']);
                    })->get();
                $rQ['4'] = Personel_resign::where("company_id", Session::get("company_id"))
                    ->where(function($q) use($q4){
                        $q->where("resign_date", ">=", $q4['start']);
                        $q->where("resign_date", "<=", $q4['end']);
                    })->get();

                $chart = [];

                $col = [];
                $col['name'] = "Turnover Per Quartal";
                $col['data'] = [];
                foreach($rQ as $i => $item){
                    $c = [];
                    $c['x'] = "C$i";
                    $c['y'] = $item->count();
                    $col['data'][] = $c;
                }

                $chart[] = $col;

                return json_encode(['chart' => $chart]);
            }

            if($request->t == "turnover_pctg"){
                $_date = date("Y")."-01-01";

                $to = Personel_resign::where("company_id", Session::get("company_id"))
                    ->whereBetween("resign_date", [$_date, date("Y-m-d")])
                    ->get();

                $_resP = Personel_resign::where("company_id", Session::get("company_id"))
                    ->where("resign_date", "<", $_date)
                    ->get();

                $emp = Hrd_employee::where("company_id", Session::get('company_id'))
                    ->whereNotIn("id", $_resP->pluck("emp_id"))
                    ->get();

                $_data = [];
                $_res = [];

                foreach($emp as $item){
                    $t = $request->type == "level" ? $item->job_level_id : ($item->user->uac_departement ?? null);
                    if(!empty($t)){
                        $_data[$t][] = $item->id;
                        if(in_array($item->id, $to->pluck("id")->toArray())){
                            $_res[$t][] = $item->id;
                        }
                    }
                }

                $_master = [];
                if($request->type == "level"){
                    $_master = \App\Models\Kjk_comp_job_level::where("company_id", Session::get('company_id'))->pluck("name", "id");
                } else {
                    $_master = \App\Models\Kjk_comp_departement::where("company_id", Session::get('company_id'))->pluck("name", "id");
                }

                $labels = [];
                $data = [];

                foreach($_res as $t => $val){
                    if(isset($_master[$t])){
                        $_total = $_data[$t];
                        $avg = (count($val) / count($_total)) * 100;
                        $labels[] = $_master[$t];
                        $data[] = $avg;
                    }
                }

                $chart = [
                    "label" => $labels,
                    "data" => $data
                ];

                return json_encode($chart);
            }

            if($request->t == "turnover_type"){
                $_date = date("Y")."-01-01";

                $to = Personel_resign::where("company_id", Session::get("company_id"))
                    ->whereBetween("resign_date", [$_date, date("Y-m-d")])
                    ->get();

                $labels = ["PHK", "Non PHK"];
                $data = [1, 1];

                if($to->count() > 0){
                    $data[0] = $to->where("resign_type", 0)->count();
                    $data[1] = $to->where("resign_type", 1)->count();
                }

                $chart = [
                    "labels" => $labels,
                    "x" => $data
                ];

                return json_encode($chart);
            }
        }

        if($request->a == "list"){
            if($request->t == "det"){
                $k = $request->key;

                $emp_id = [];

                $dm = [];

                $detail = [];
                $emp_id = [];

                if($k == "onboarding"){
                    $detail = Personel_onboarding::where("company_id", Session::get('company_id'))
                        ->get();
                    $emp_id = User::whereIn("id", $detail->pluck("user_id"))->pluck("emp_id");
                }

                if($k == "promotion"){
                    $detail = Personel_history::where("company_id", Session::get('company_id'))
                        ->where("type", "position")
                        ->get();

                    $dm = \App\Models\Kjk_comp_position::where("company_id", Session::get("company_id"))
                        ->pluck("name", "id");

                    $emp_id = $detail->pluck("personel_id");
                }

                if($k == "update_data"){
                    $detail = Personel_history::where("company_id", Session::get('company_id'))
                        ->where("type", "!=", "position")
                        ->get();

                    $emp_id = $detail->pluck("personel_id");
                }

                if($k == "resign"){
                    $detail = Personel_resign::where("company_id", Session::get('company_id'))
                        ->get();

                    $emp_id = $detail->pluck("emp_id");
                }

                if($k == "expired_contract"){
                    $mExp = date("Y-m-d", strtotime("+1 month"));
                    $emp_id = Hrd_employee::where('company_id', Session::get("company_id"))
                        ->whereNotNull("employee_status_mutation_end")
                        ->where("employee_status_mutation_end", "<=", $mExp)
                        ->pluck("id");
                }

                if($k == "document"){
                    $mExp = date("Y-m-d", strtotime("+1 month"));
                    $detail = \App\Models\Hrd_cv_u::where('company_id', Session::get("company_id"))
                        ->where("expiry_date", "<=", $mExp)
                        ->orderBy("expiry_date")
                        ->get();

                    $emp_id = $detail->pluck("user_id");
                }

                $emp = Hrd_employee::whereIn("id", $emp_id)->get();

                $user_img = User::whereIn("emp_id", $emp_id)->pluck("user_img", "emp_id");

                $v = view("_personel.widgets.$k", compact("emp", "detail", "user_img", 'dm'))->render();

                return json_encode(['view' => $v]);
            }
        }

        if($request->a == "modal"){
            if($request->t == "birthday"){
                $emp_birth = Hrd_employee::selectRaw("*, month(emp_lahir) as month_lahir, DAY(emp_lahir) as day_lahir")->where("company_id", Session::get("company_id"))
                    ->whereNotIn("id", $resigned->pluck("emp_id"))
                    ->whereNotNull("emp_lahir")
                    ->orderBy("month_lahir")
                    ->orderBy("day_lahir")->get();

                $months = [1=> "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                $user_img = User::hris()->where("company_id", Session::get("company_id"))->pluck("user_img", "emp_id");

                $view = view("_personel.widgets.modal.birthday", compact("emp_birth", "months", 'user_img'));

                return json_encode([
                    "view" => $view->render()
                ]);
            }

            if($request->t == "list"){

                $detail['onboarding'] = Personel_onboarding::where("company_id", Session::get('company_id'))
                    ->get();

                $donboard = Personel_onboarding_detail::whereIn('onboard_id', $detail['onboarding']->pluck("id"))->get();
                $do = [];
                foreach($donboard as $item){
                    $do[$item->onboard_id][] = $item;
                }

                $detail['promotion'] = Personel_history::where("company_id", Session::get('company_id'))
                    ->where("type", "position")
                    ->get();

                $detail['update_data'] = Personel_history::where("company_id", Session::get('company_id'))
                    ->where("type", "!=", "position")
                    ->get();

                $detail['resign'] = Personel_resign::where("company_id", Session::get('company_id'))
                    ->get();

                $mExp = date("Y-m-d", strtotime("+1 month"));

                $detail['document'] = \App\Models\Hrd_cv_u::where('company_id', Session::get("company_id"))
                        ->where("expiry_date", "<=", $mExp)
                        ->orderBy("expiry_date")
                        ->get();

                $dm = \App\Models\Kjk_comp_position::where("company_id", Session::get("company_id"))
                    ->pluck("name", "id");

                $emp_id = User::whereIn("id", $detail['onboarding'])->pluck("id", "emp_id")->toArray();

                $user_img = User::whereIn("emp_id", $emp->pluck("id"))->pluck("user_img", "emp_id");

                $res_reason = \App\Models\Kjk_comp_resign::where("company_id", Session::get('company_id'))->pluck("name", "id");
                $rtype = ["Non PHK", "PHK"];
                $req_data['location'] = \App\Models\Asset_wh::office()->where("company_id", Session::get('company_id'))->pluck("name", "id");
                $req_data['job_grade'] = \App\Models\Kjk_comp_job_grade::where("company_id", Session::get("company_id"))->pluck("name", "id");
                $req_data['job_level'] = \App\Models\Kjk_comp_job_level::where("company_id", Session::get("company_id"))->pluck("name", "id");
                $req_data['employee_status'] = \App\Models\Personel_employee_status::where("company_id", Session::get("company_id"))->pluck("label", "id");
                $req_data['workgroup'] = \App\Models\Att_workgroup::where("company_id", Session::get("company_id"))->pluck("workgroup_name", "id");
                $req_data['position'] = \App\Models\Kjk_comp_position::where("company_id", Session::get("company_id"))->pluck("name", "id");
                $req_data['departement'] = \App\Models\Kjk_comp_departement::where("company_id", Session::get("company_id"))->pluck("name", "id");
                $req_data['acting_position'] = \App\Models\Kjk_uac_role::pluck("name", "id");

                $emp_expired = $emp->whereNotNull("employee_status_mutation_end")
                ->where("employee_status_mutation_end", "<=", $mExp);

                $view = view("_personel.widgets.modal.list", compact("emp", "detail", "emp_id", "dm", 'user_img', 'do', 'res_reason', 'rtype', 'req_data', 'emp_expired'));

                return json_encode([
                    "view" => $view->render()
                ]);
            }

            if($request->t == "turnover"){

                $_date = date("Y")."-01-01";

                $to = Personel_resign::where("company_id", Session::get("company_id"))
                    ->whereBetween("resign_date", [$_date, date("Y-m-d")])
                    ->get();

                $_resP = Personel_resign::where("company_id", Session::get("company_id"))
                    ->where("resign_date", "<", $_date)
                    ->get();

                $empThisYear = Hrd_employee::where("company_id", Session::get("company_id"))
                    ->whereNotIn("id", $_resP->pluck("emp_id"))
                    ->get();
                $totalEmpThisYear = $empThisYear->count();

                $toRate = $totalEmpThisYear > 0 ? ($to->count() / $totalEmpThisYear) * 100 : 0;

                $empResigned = Hrd_employee::where("company_id", Session::get("company_id"))
                    ->whereIn("id", $to->pluck("emp_id"))
                    ->get();

                $avg['year'] = 0;
                $avg['month'] = 0;
                $avg['day'] = 0;

                $_avg = 0;

                foreach($empResigned as $item){
                    $d1 = date_create($item->join_date ?? $item->created_at);
                    $d2 = date_create(date("Y-m-d"));
                    $d3 = date_diff($d2, $d1);
                    $a = $d3->format("%a");
                    $_avg += $a;
                }

                if($_avg > 0){
                    $nAvg = $_avg / $empResigned->count();

                    $y = floor($nAvg / 365);
                    $m = floor(($nAvg - ($y * 365)) / 30);
                    $d = floor($nAvg - ($y * 365) - ($m * 30));
                    $avg['year'] = $y;
                    $avg['month'] = $m;
                    $avg['day'] = $d;
                }

                $toType['phk'] = $to->where("resign_type", 0);
                $toType['non_phk'] = $to->where("resign_type", 1);

                $rname = \App\Models\Kjk_comp_resign::where('company_id', Session::get('company_id'))->pluck("name", "id");

                $toReason['phk'] = [];
                $toReason['non_phk'] = [];

                foreach($toType['phk'] as $val){
                    if(isset($rname[$val->resign_reason])){
                        $toReason["phk"][$val->resign_reason][] = $val->id;
                    }
                }

                foreach($toType['non_phk'] as $val){
                    if(isset($rname[$val->resign_reason])){
                        $toReason["non_phk"][$val->resign_reason][] = $val->id;
                    }
                }

                // dd($toReason);

                $view = view("_personel.widgets.modal.turnover", compact("to", "toRate", 'avg', 'toType', 'rname', 'toReason'));

                return json_encode([
                    "view" => $view->render()
                ]);
            }

            if($request->t == "employement"){
                $view = view("_personel.widgets.modal.employement", compact("master"));

                return json_encode([
                    "view" => $view->render()
                ]);
            }

            if($request->t == "ratio"){
                $view = view("_personel.widgets.modal.ratio", compact("master"));

                return json_encode([
                    "view" => $view->render()
                ]);
            }
        }

        $retensi = 0;
        $rcptg = 0;
        $ty = date("Y")."01-01";
        $rthisyear = Personel_resign::where("company_id", Session::get("company_id"))
            ->where("resign_date", "<=", $ty)
            ->get();
        $all_emp = Hrd_employee::where("company_id", Session::get("company_id"))
            ->whereNotIn("id", $rthisyear->pluck("emp_id"))
            ->get();
        if($all_emp->count() > 0){
            $retensi = 100;
            $ryear = Personel_resign::where("company_id", Session::get("company_id"))
                ->whereBetween("resign_date", [$ty, date("Y-m-d", strtotime("+1 day"))])
                ->get();
            $ret = Hrd_employee::where("company_id", Session::get("company_id"))
                ->whereIn("id", $ryear->pluck("emp_id"))
                ->get();

            if($ret->count() > 0){
                $cnt = $all_emp->count() - $ret->count();
                $retensi = ($cnt / $all_emp->count()) * 100;
            }

            $ryearY = Personel_resign::where("company_id", Session::get("company_id"))
                ->whereBetween("resign_date", [$ty, date("Y-m-d")])
                ->get();
            $retY = Hrd_employee::where("company_id", Session::get("company_id"))
                ->whereIn("id", $ryear->pluck("emp_id"))
                ->get();
            if($retY->count() > 0){
                $s = $ret->count() - $retY->count();
                $rcptg = ($s / $retY->count()) * 100;
            }
        }

        return view("_personel.index", compact('data', 'master', 'emp_birth', 'user_img', 'retensi', 'rcptg'));
    }

    function random_color_part() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    function random_color() {
        return "#".$this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }
}
