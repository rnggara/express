<?php

namespace App\Http\Controllers;

use App\Models\Kjk_crm_lead_history;
use App\Models\Kjk_lead_layout;
use App\Models\Kjk_user_team;
use App\Models\Kjk_crm_lead_funnel;
use App\Models\Marketing_clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Marketing_leads;
use App\Models\Kjk_crm_product;
use App\Models\User;
use App\Models\Kjk_dashboard_pref;
use Illuminate\Support\Facades\Auth;

class KjkCrmDashboard extends Controller
{
    function index(Request $request){

        Session::put("session_state", "crm");
        Session::put("home_url", route("crm.index"));

        $f = $request->f ?? "week";

        $user_id = Auth::id();

        $user_team = Kjk_user_team::where(function($q) use($user_id) {
            $q->where("members", "like", '%"'.$user_id.'"%');
            $q->orWhere("members", '["_all"]');
        })->where("company_id", Session::get('company_id'))->get();

        $pipe_ids = [];
        foreach($user_team as $pid){
            $pIds = json_decode($pid->pipeline_id ?? "[]", true);
            $pipe_ids = array_merge($pipe_ids, $pIds);
        }

        $leadLayout = Kjk_lead_layout::where("company_id", Session::get('company_id'))
            ->whereIn("id", $pipe_ids)
            ->orderBy("row_order")
            ->get();

        $pId = [];
        if(!empty(Auth::user()->default_pipeline)){
            $dpId = Auth::user()->default_pipeline;
            if(in_array($dpId,$leadLayout->pluck("id")->toArray())){
                $pId[] = $dpId;
            } else {
                $pId[] = $leadLayout->first()->id ?? null;
            }
        } else {
            $pId[] = $leadLayout->first()->id ?? null;
        }

        $funnels = Kjk_crm_lead_funnel::where("company_id", Session::get('company_id'))
            ->whereIn('layout_id', $pId)
            ->orderBy("row_order")
            ->get();

        $leads_all = Marketing_leads::where("company_id", Session::get("company_id"))
            ->whereIn("funnel_id", $funnels->pluck("id"))
            ->get();

        $leads_today = Marketing_leads::where("company_id", Session::get("company_id"))
            ->whereIn("funnel_id", $funnels->pluck("id"))
            ->whereDate("created_at", date("Y-m-d"))
            ->get();

        $gagal = $leads_all->where('status_deal', 2);
        $berhasil = $leads_all->where('status_deal', 1);

        $annualEarning = Marketing_leads::where('company_id', Session::get("company_id"))
            ->whereIn("funnel_id", $funnels->pluck("id"))
            ->whereYear("deal_date", date("Y"))
            // ->where("status_deal", 1)
            ->get();

        $firstLastDate = "first day of last $f";
        $endLastDate = "last day of last $f";
        $firstThisDate = "first day of this $f";
        $endThisDate = "last day of this $f";
        $_f = $f;
        if($f == "year"){
            $_f = date("Y") - 1;
            $firstLastDate = "january 1st, $_f";
            $endLastDate = "december 31st, $_f";
            $firstThisDate = "january 1st, ".date("Y");
            $endThisDate = "december 31st, ".date("Y");
        }

        $mondayLast = date("Y-m-d 00:00:00", strtotime("$firstLastDate"));
        $sundayLast = date("Y-m-d 23:59:59", strtotime("$endLastDate"));

        $uId = Auth::id();

        $teams = Kjk_user_team::where('company_id', Session::get("company_id"))
            ->where(function($q) use($uId){
                $q->where("members", "like", '%"'.$uId.'"%');
                $q->orWhere("members", '["_all"]');
            })->get();

        $user = Auth::user();

        $perm = \App\Models\Kjk_crm_permission::find($user->crm_role);
        $role = [];
        if(!empty($perm)){
            $role = json_decode($perm->permission ?? "[]", true)['user'] ?? [];
        }

        if($request->a == "revenue"){

            $filter = $request->filter;

            $d1 = date("Y")."-01-01";
            if($filter == "qtd"){
                $d1 = date("Y-m-d", strtotime("-3 months"));
            } elseif($filter == "mtd"){
                $d1 = date("Y-m-")."01";
            } elseif($filter == "lytd"){
                $d1 = (date("Y", strtotime("last year")))."-01-01";
            } elseif($filter == "lqtd"){
                $d1 = date("Y-m-d", strtotime("-6 months"));
            } elseif($filter == "lmtd"){
                $d1 = date("Y-m-", strtotime("last month"))."01";
            }

            $dnow = date("Y-m-d", strtotime("+1 day"));
            $funnels = Kjk_crm_lead_funnel::where("company_id", Session::get('company_id'))
                ->where('layout_id', $request->pipeline_id)
                ->orderBy("row_order")
                ->get();

            $opportunity = Marketing_leads::where('layout_id', $request->pipeline_id)
                ->whereIn("funnel_id", $funnels->pluck("id"))
                ->where(function($q) use($uId, $role){
                    $_role = false;
                    if(!empty($role)){
                        $owner = $role['owner']['view'] ?? 0;
                        $collab = $role['collaborator']['view'] ?? 0;
                        if($owner == 1 || $collab == 1){
                            $_role = true;
                        }
                    }

                    if(!$_role){
                        $q->where("contributors", 'like', '%"'.$uId.'"%');
                        $q->orWhere("partner", $uId);
                        $q->orWhere("created_by", $uId);
                    }
                })
                ->whereBetween("created_at", [$d1, $dnow])
                ->get();

            $oppFunnel = [];
            foreach($opportunity as $item){
                $oppFunnel[$item->funnel_id][] = $item;
            }

            foreach($funnels as $i => $item){
                $cFunnel = collect($oppFunnel[$item['id']] ?? []);
                $item['revenue'] = $cFunnel->sum("nominal");
                $item['opportunity'] = $cFunnel;
                $funnels[$i] = $item;
            }

            $total_leads = $opportunity->sum("nominal");

            $data = [
                'funnels' => $funnels,
                "revenue" => number_format($total_leads, 0, ",", "."),
            ];

            return json_encode($data);
        }

        if($request->a == "load"){
            $theme = ["#7340E5", "#AA8CEF", "#D4C4F7", "#E1D7FA", "#f3effd", "#E45D6A"];
            $filter = $request->filter;
            $team = $request->team;

            $d1 = date("Y")."-01-01";
            if($filter == "qtd"){
                $d1 = date("Y-m-d", strtotime("-3 months"));
            } elseif($filter == "mtd"){
                $d1 = date("Y-m-")."01";
            } elseif($filter == "lytd"){
                $d1 = (date("Y", strtotime("last year")))."-01-01";
            } elseif($filter == "lqtd"){
                $d1 = date("Y-m-d", strtotime("-6 months"));
            } elseif($filter == "lmtd"){
                $d1 = date("Y-m-", strtotime("last month"))."01";
            }

            $dnow = date("Y-m-d", strtotime("+1 day"));

            $cteam = Kjk_user_team::find($team);
            $pipeline = Kjk_lead_layout::whereIn("id", json_decode($cteam->pipeline_id ?? "[]", true))->get();

            $oppClient = [];
            $oppFunnel = [];

            $uId = Auth::id();

            $funnels = Kjk_crm_lead_funnel::where("company_id", Session::get('company_id'))
                ->whereIn('layout_id', $pipeline->pluck("id") ?? [])
                ->orderBy("row_order")
                ->get();

            $funnel_win = $funnels->where("status_funnel", 1)->pluck("id")->toArray();
            $funnel_lose = $funnels->where("status_funnel", -1)->pluck("id")->toArray();


            $opportunity = Marketing_leads::whereIn('layout_id', $pipeline->pluck("id") ?? null)
                ->whereIn("funnel_id", $funnels->pluck("id"))
                ->where(function($q) use($uId, $role){
                    $_role = false;
                    if(!empty($role)){
                        $owner = $role['owner']['view'] ?? 0;
                        $collab = $role['collaborator']['view'] ?? 0;
                        if($owner == 1 || $collab == 1){
                            $_role = true;
                        }
                    }

                    if(!$_role){
                        $q->where("contributors", 'like', '%"'.$uId.'"%');
                        $q->orWhere("partner", $uId);
                        $q->orWhere("created_by", $uId);
                    }
                })
                ->whereBetween("created_at", [$d1, $dnow])
                ->get();

            $lastMove = Kjk_crm_lead_history::where("company_id", Session::get("company_id"))
                ->whereIn("lead_id", $opportunity->pluck("id"))
                ->whereBetween('created_at',[$d1, $dnow])
                ->get();

            $nowMove = Kjk_crm_lead_history::where("company_id", Session::get("company_id"))
                ->whereIn("lead_id", $opportunity->pluck("id"))
                ->whereBetween('created_at',[$d1, $dnow])
                ->get();

            $leadsMove = array_values(array_unique($nowMove->pluck("lead_id")->toArray()));

            $funnels = [];
            foreach($pipeline as $item){
                $funnels = array_merge($funnels, $item->funnel->sortBy("row_order")->toArray());
            }

            $funnels = collect($funnels);

            $total_leads = $opportunity->sum("nominal");

            $user = User::hris()->where("company_id", Session::get('company_id'))->get();
            $user_name = $user->pluck("name", "id");
            $user_img = $user->pluck("user_img", "id");

            $winrate = [];

            $opChart = [];
            $opWorth = [];
            $opWin = [];
            $opLose = [];

            $d2 = $d1;
            $dnum = 1;
            while($d2 <= $dnow){
                if($filter == "ytd" || $filter == "lytd"){
                    $d3 = date("Y-m-d", strtotime("$d2 +3 month"));
                    $opChart[] = date("Y", strtotime($d2)). " Q".$dnum++;
                    if(date("Y", strtotime($d3)) > date("Y", strtotime($d2))){
                        $dnum = 1;
                    }
                    $d2 = $d3;
                } elseif($filter == "qtd" || $filter == "lqtd"){
                    $opChart[] = date("F", strtotime($d2));
                    $d2 = date("Y-m-d", strtotime("$d2 +1 month"));
                } elseif($filter == "mtd" || $filter == "lmtd"){
                    $opChart[] = date("W", strtotime($d2));
                    $d2 = date("Y-m-d", strtotime("$d2 +1 week"));
                }
                $opWorth[] = 0;
                $opWin[] = 0;
                $opLose[] = 0;
            }

            $contriOp = [];
            $contriId = [];

            foreach($opportunity as $item){
                $oppClient[$item->id_client][] = $item;
                $oppFunnel[$item->funnel_id][] = $item;
                $contri = json_decode($item->contributors ?? "[]");
                foreach($contri as $cont){
                    if(isset($user_name[$cont])){
                        $col = isset($winrate[$cont]) ? $winrate[$cont] : [];
                        if(!isset($winrate[$cont])){
                            $col['name'] = $user_name[$cont];
                            $col['img'] = $user_img[$cont];
                        }
                        $col['all'][] = $item->id;
                        if(in_array($item->funnel_id, $funnel_win)){
                            $col['opportunity'][] = $item->id;
                        }
                        $contriOp[$item->funnel_id][str_replace(" ", "_", $user_name[$cont])][] = $item->id;
                        $winrate[$cont] = $col;
                    }
                }

                if($filter == "ytd" || $filter == "lytd"){
                    $m = date("n", strtotime($item->created_at));
                    $q = 5 - round(12 / $m);
                    $d = date("Y", strtotime($item->created_at))." Q$q";
                } elseif($filter == "qtd" || $filter == "lqtd"){
                    $d = date("F", strtotime($item->created_at));
                } else {
                    $d = date("W", strtotime($item->created_at));
                }
                $k = array_search($d, $opChart);
                $opWorth[$k] += $item->nominal;
                if(in_array($item->funnel_id, $funnel_win)){
                    $opWin[$k] += $item->nominal;
                }

                if(in_array($item->funnel_id, $funnel_lose)){
                    $opLose[$k] += $item->nominal;
                }
            }

            foreach($funnels as $i => $item){
                $cFunnel = collect($oppFunnel[$item['id']] ?? []);
                $item['revenue'] = $cFunnel->sum("nominal");
                $item['opportunity'] = $cFunnel;
                $funnels[$i] = $item;
            }

            $funnel_win = !empty($funnels) ? $funnels->where('status_funnel', 1) : [];
            $funnel_lose = !empty($funnels) ? $funnels->where('status_funnel', -1) : [];

            $winrateChart['label'] = [];
            $winrateChart['data'] = [];
            $winrateChart['pct'] = round($opportunity->count() == 0 ? 0 : ($opportunity->whereIn("funnel_id", $funnel_win->pluck('id') ?? [])->count() / $opportunity->count()) * 100, 2);
            foreach ($winrate as $key => $value) {
                $op = $value['opportunity'] ?? [];
                $pct = round((count($op) / count($value['all'])) * 100, 2);
                $winrateChart['label'][] = $value['name'];
                $winrateChart['img'][] = asset($value['img'] ?? "theme/assets/media/avatars/blank.png");
                $winrateChart['data'][] = $pct;
            }

            $opp_chart['labels'] = ["Available", "Win", "Lose"];
            $opp_chart['data'] = [
                $opportunity->whereNull("archive_at")->where("status_deal", 0)->count() + 1,
                $opportunity->whereNull("archive_at")->whereIn("funnel_id", $funnel_win->pluck('id') ?? [])->count() + 1,
                $opportunity->whereNull("archive_at")->where("funnel_id", $funnel_lose->pluck('id') ?? [])->count() + 1];

            $stacked['x'] = $winrateChart['label'];
            $stacked['y'] = [];
            $stacked['color'] = $theme;
            foreach($funnels as $fv){
                $col = [];
                $col['name'] = $fv['label'];
                $col['data'] = [];
                foreach($stacked['x'] as $item){
                    $_key = str_replace(" ", "_", $item);
                    $col['data'][] = isset($contriOp[$fv['id']]) ? count($contriOp[$fv['id']][$_key] ?? []) + 1 : 1;
                }
                $stacked['y'][] = $col;
            }

            $product_name = Kjk_crm_product::where("company_id", Session::get("company_id"))->pluck("label", "id");

            $comp = Marketing_clients::where("company_id", Session::get("company_id"))->get();
            $top_comp = [];
            foreach($comp as $item){
                $col = [];
                $pctg = 0;
                $cClient = collect($oppClient[$item->id] ?? []);
                if($cClient->count() > 0){
                    $pctg = ($cClient->whereIn("funnel_id", $funnel_win->pluck('id') ?? [])->count() / $cClient->count()) * 100;
                }
                $col['name'] = $item->company_name;
                $col['opportunity'] = $cClient->count();
                $col['value'] = $cClient->sum("nominal");
                $col['winrate'] = round($pctg, 2)."%";
                if($col['value'] > 0){
                    $top_comp[] = $col;
                }
            }

            usort($top_comp, function($a, $b) {
                return $b['value'] - $a['value'];
            });

            $top_comp = collect($top_comp);

            $top_prod = [];
            $prods = [];

            foreach($opportunity as $item){
                $prod = json_decode($item->products ?? "[]", true);
                if(is_array($prod)){
                    foreach($prod as $pId){
                        $prods[$pId]['nominal'][] = $item->nominal;
                        $_win = 0;
                        if(in_array($item->funnel_id, $funnel_win->pluck("id")->toArray())){
                            $_win = 1;
                        }
                        $prods[$pId]['win'][] = $_win;
                    }
                }
            }


            foreach($prods as $pId => $item){
                $col = [];
                if(isset($product_name[$pId])){
                    $pctg = 0;
                    if(count($item['nominal']) > 0){
                        $pctg = (array_sum($item['win']) / count($item['nominal'])) * 100;
                    }
                    $col['name'] = $product_name[$pId];
                    $col['opportunity'] = count($item['nominal']);
                    $col['value'] = array_sum($item['nominal']);
                    $col['winrate'] = $pctg."%";
                    $top_prod[] = $col;
                }
            }

            usort($top_prod, function($a, $b) {
                return $b['value'] - $a['value'];
            });

            $top_prod = collect($top_prod);

            $data = [
                "total_opportunity" => $opportunity->count(),
                "revenue" => number_format($total_leads, 0, ",", "."),
                'funnels' => $funnels,
                "opportunity_perf_chart" => $opp_chart,
                "winrate" => $winrateChart,
                'stacked' => $stacked,
                'top_comp' => $top_comp->take(3),
                "top_prod" => $top_prod->take(3),
                'opChart' => $opChart,
                'opWorth' => $opWorth,
                'opWin' => $opWin,
                'opLose' => $opLose,
                'opportunity_win' => $opportunity->whereIn("funnel_id", $funnel_win->pluck('id') ?? [])->count(),
                'opportunity_lose' => $opportunity->whereIn("funnel_id", $funnel_lose->pluck('id') ?? [])->count(),
                'sales_cycle' => $leadsMove,
                'pipelines' => $pipeline->pluck("label", "id")
            ];

            return json_encode($data);
        }

        $set = Kjk_dashboard_pref::where("company_id", Session::get('company_id'))->pluck("status", "dashboard_key");


        return view("_crm.index", compact("leads_all", "leads_today", "gagal", "berhasil", "annualEarning", 'f', "teams", "set"));
    }

    function test_drawer(Request $request){
        return redirect()->back()->with(["request" => $request->all()]);
    }
}
