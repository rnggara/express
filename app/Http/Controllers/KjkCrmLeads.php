<?php

namespace App\Http\Controllers;

use App\Exports\BOExport;
use App\Exports\BUExport;
use App\Models\Hrd_employee;
use App\Models\Kjk_crm_lead_funnel;
use App\Models\Kjk_crm_lead_history;
use App\Models\Kjk_crm_lead_update_history;
use App\Models\Kjk_crm_leads_contact;
use App\Models\Kjk_crm_product;
use App\Models\Kjk_crm_tag;
use App\Models\Kjk_lead_comment;
use App\Models\Kjk_lead_layout;
use App\Models\Marketing_clients;
use App\Models\Marketing_lead_files;
use App\Models\Marketing_lead_notes;
use App\Models\Marketing_lead_tasks;
use App\Models\Marketing_leads;
use App\Models\Kjk_crm_property;
use App\Models\User;
use App\Models\Kjk_user_team;
use App\Models\Kjk_crm_property_value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DateTime\DateTime;
use DatePeriod\DatePeriod;
use DateInterval\DateInterval;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class KjkCrmLeads extends Controller
{

    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    function daysUntil() {
        $startDate = Carbon::parse("2023-01-01");
        $endDate = Carbon::parse(date("Y-m-d"));

        // $days = $startDate->diffInDays($endDate);
        $holidays = [
            // Carbon::parse("2023-01-26"),
            // Carbon::parse("2023-08-15")
        ];

        $days = $startDate->diffInDaysFiltered(function (Carbon $date) use ($holidays) {
            return $date->isWeekday() && !in_array($date, $holidays);
        }, $endDate);

        // dd($diffInDays);

        return $days;
    }

    function crmLead_d(Request $request){

        if($request->a){
            if($request->a == "table"){

                $firstDate = "first day of this month";
                $lastDate = "today";
                if($request->f == "2"){
                    $firstDate = "January 1st, ".date("Y");
                }

                $_d1 = date("Y-m-d 00:00:00", strtotime($firstDate));
                $_d2 = date("Y-m-d 23:59:59", strtotime($lastDate));

                $company_id = Session::get("company_id");
                $ml = Marketing_leads::where('company_id', $company_id)
                    ->get();
                $rml = [];
                $search = array('"', '[', ']');
                $replace = array('', '', '');
                foreach($ml as $k => $v){
                    foreach (json_decode($v->contributors) as $contr){
                        if (!in_array($contr, $rml))
                        {
                            $rml[] = $contr;
                        }
                    }
                }
                $u_data = [];
                $r3 = Hrd_employee::where('company_id', $company_id)->get()->toArray();

                $uid = User::get()->pluck("id", "emp_id");
                for($i=0;$i<count($rml); $i++){
                    foreach($r3 as $k){
                        $u_name = "";
                        if($k['id'] == $rml[$i]){
                            $u_name = $k['emp_name'];
                            break;
                        }

                    }
                    $u_leads = Marketing_leads::Where('company_id', $company_id)->Where('contributors', 'LIKE', '%"'.$rml[$i].'"%')
                        ->whereBetween('created_at', [$_d1, $_d2])
                        ->get();
                    $a_company = [];
                    $u_nominal = 0;
                    $u_company = 0;
                    $u_profit = 0;
                    $sales = 0;

                    $u_id = $uid[$rml[$i]] ?? 0;


                    $nowMove = Kjk_crm_lead_history::whereIn("lead_id", $u_leads->pluck('id'))
                        ->where("created_by", "$u_id")
                        ->whereBetween('created_at',[$_d1, $_d2])
                        ->get();
                    // if($nowMove->count() > 0) {
                    //     dd($nowMove, $u_leads->pluck("leads_name"));
                    // }

                    $leadsMove = array_unique($nowMove->pluck("lead_id")->toArray());

                    $leadDeals = 0;

                    foreach($u_leads as $k => $v){
                        if (!in_array($v->id_client, $a_company))
                        {
                            $a_company[] = $v->id_client;
                        }
                        $u_nominal += $v->nominal;
                        $u_profit += $v->estimasi_profit;

                        if(!empty($v->deal_date) && $v->status_deal == 1){
                            $d1 = date_create($v->created_at);
                            $d2 = date_create($v->deal_date);
                            $diff = date_diff($d1, $d2);
                            $sales += $diff->format("%a");
                            $leadDeals++;
                        }
                    }
                    // dd($u_leads);

                    $salesDay = ($sales / ($leadDeals == 0 ? 1 : $leadDeals));

                    $u_data[] = array(
                        "ID" => $rml[$i],
                        "UNAME" => $u_name,
                        "DAYS" => $this->daysUntil(),
                        "COMPANY" => count($a_company),
                        "LEADS" => count($u_leads),
                        "NOMINAL" => $u_nominal,
                        "PROFIT" => $u_profit,
                        "LK" => floatval($this->daysUntil() / ( count($u_leads) == 0 ? 1 : count($u_leads)) ),
                        "LA" => floatval(count($u_leads) / (count($a_company) == 0 ? 1 : count($a_company))),
                        "PCTG" => floatval((count($leadsMove) / (count($u_leads) == 0 ? 1 : count($u_leads))) * 100),
                        "SALES" => $salesDay,
                    );

                }

                $view = view("_crm.leads._group_table", compact('u_data'));

                $arr = [
                    "view" => $view->render(),
                    "firstDate" => $_d1,
                    "lastDate" => $_d2,
                ];

                return json_encode($arr);
            }
        }
        return view('_crm.leads.group');
    }

    function crmCommentAdd(Request $request){
        $comment = new Kjk_lead_comment();
        $comment->lead_id = $request->lead_id;
        $comment->comment = $request->comment;
        $comment->user_id = Auth::id();
        $comment->content_id = $request->content_id;
        $comment->content_type = $request->content_type;
        $comment->comment_id = $request->comment_id;

        $file = $request->file("attachments");
        $comment->company_id = Session::get('company_id');
        $comment->created_by = Auth::id();
        if(!empty($file)){
            $dir = str_replace("/", "\\", public_path("media/attachments"));
            $_dir = str_replace("prototype\public_html", "public_html\kerjaku\assets", $dir);
            $tdir = str_replace("\\", "/", $_dir);
            $name = date("YmdHis")."-comment-".$comment->id_leads."-".$file->getClientOriginalName();
            if($file->move($this->dir, $name)){
                $comment->file_name = $file->getClientOriginalName();
                $comment->file_address = "media/attachments/$name";
            }
        }

        $comment->save();

        return redirect()->back()->with(['leads_id' => $comment->lead_id]);
    }

    function crmCommentView($type, $id, Request $request){
        $coid = $request->comment;
        $comment = Kjk_lead_comment::where('content_type', $type)
            ->whereNull("comment_id")
            ->where("content_id", $id)
            ->orderBy("created_at")
            ->get();
        if(!empty($coid)){
            $comment = Kjk_lead_comment::where('content_type', $type)
                ->where("comment_id", $coid)
                ->where("content_id", $id)
                ->orderBy("created_at")
                ->get();
        }

        $user = User::whereIn("id", $comment->pluck("user_id"))->get()->pluck("name", "id");

        $comments = Kjk_lead_comment::whereIn('comment_id', $comment->pluck("id"))
            ->orderBy("created_at")
            ->get();
        $child = [];
        foreach($comments as $item){
            $child[$item->comment_id][] = $item;
        }

        $v = view('_crm.leads.comment', compact('comment', 'type', 'id', 'user', "child"))->render();

        return json_encode([
            "view" => $v
        ]);
    }

    function crmCommentDelete($id){
        $comment = Kjk_lead_comment::find($id);
        $id_lead = $comment->lead_id;
        $comment->delete();

        return redirect()->back()->with(['leads_id' => $id_lead]);
    }

    function crmLayoutAdd(Request $request){
        $lastRow = Kjk_lead_layout::where("company_id", Session::get('company_id'))
            ->orderBy('row_order', "desc")
            ->first();
        $num = empty($lastRow) ? 1 : $lastRow->row_order + 1;
        $layout = new Kjk_lead_layout();
        $layout->label = $request->label;
        $layout->company_id = Session::get("company_id");
        $layout->row_order = $num;
        $layout->save();

        return redirect()->back();
    }

    function crmLayoutDelete(Request $request){
        $layout = Kjk_lead_layout::find($request->lid);

        $layout->delete();

        Marketing_leads::where("layout_id", $request->lid)->delete();
        Kjk_crm_lead_funnel::where("layout_id", $request->lid)->delete();

        return redirect()->route('crm.lead.index');
    }

    function crmLayoutEdit($type, $id){
        $l = Kjk_lead_layout::find($id);
        $n = Kjk_lead_layout::where('company_id', $l->company_id)
            ->where("row_order", $type == "up" ? "<" : ">", $l->row_order)
            ->orderBy('row_order', $type == "up" ? "desc" : "asc")
            ->first();

        $before = $l->row_order;

        $l->row_order = $n->row_order;
        $l->save();
        $n->row_order = $before;
        $n->save();

        return redirect()->back();
    }

    function crmLeadIndex($layoutid = null, Request $request){

        $user_id = Auth::id();

        $user = Auth::user();

        $perm = \App\Models\Kjk_crm_permission::find($user->crm_role);
        $role = [];
        if(!empty($perm)){
            $role = json_decode($perm->permission ?? "[]", true)['user'] ?? [];
        }

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

        $pId = null;
        if(!empty(Auth::user()->default_pipeline)){
            $dpId = Auth::user()->default_pipeline;
            if(in_array($dpId,$leadLayout->pluck("id")->toArray())){
                $pId = $dpId;
            } else {
                $pId = $leadLayout->first()->id ?? null;
            }
        } else {
            $pId = $leadLayout->first()->id ?? null;
        }

        $layoutSelected = $layoutid ?? $pId;

        $m_tag = Kjk_crm_tag::where('company_id', Session::get("company_id"))->get()->toArray();
        $m_contact = Kjk_crm_leads_contact::where('company_id', Session::get("company_id"))->get()->toArray();
        $m_product = Kjk_crm_product::where('company_id', Session::get("company_id"))->get()->toArray();
        $m_funnel = Kjk_crm_lead_funnel::where('company_id', Session::get("company_id"))->get()->toArray();
        // dd()
        $m_menu = array(
            "TAG" => $m_tag,
            "CONTACT" => $m_contact,
            "PRODUCT" => $m_product,
            "FUNNEL" => $m_funnel
        );
        $funnels = Kjk_crm_lead_funnel::where("company_id", Session::get('company_id'))
            ->where('layout_id', $layoutSelected)
            ->orderBy("row_order")
            ->get();

        $leads = Marketing_leads::where("company_id", Session::get('company_id'))
            ->whereIn("funnel_id", $funnels->pluck("id"))
            ->where('layout_id', $layoutSelected)
            ->orderBy("row_order")
            ->get();

        $comps = Marketing_clients::where('company_id', Session::get('company_id'))->pluck("company_name", "id");

        $lead_funnel = [];
        foreach($leads as $item){
            // $lead_funnel[$item->funnel_id][] = $item;
        }

        $contacts = Kjk_crm_leads_contact::where('company_id', Session::get("company_id"))->pluck("name", "id");

        $uId = [];
        if($leads->count() > 0){
            $uId = array_merge($uId, $leads->pluck('created_by')->toArray(), $leads->pluck('partner')->toArray());
        }

        $users = Hrd_employee::where('company_id', Session::get("company_id"))->get()->pluck("emp_name", "id");

        $user = User::where("company_id", Session::get("company_id"))->get();
        $user_name = $user->pluck("name", "id");
        $user_email = $user->pluck("email", "id");
        $user_phone = $user->pluck("phone", "id");
        $user_img = $user->pluck("user_img", "id");

        $products = Kjk_crm_product::where("company_id", Session::get('company_id'))->get();

        $fdetail = [];
        foreach($funnels as $item){
            $fdetail[$item->id] = $item;
        }

        // dd($role);

        if($request->a == "table"){
            $tags = [];
            $funnelsx = [];
            $contactsx = [];
            $products = [];
            if(count(collect($request->tags)) > 0){
                $tags = collect($request->tags);
            }
            if(count(collect($request->funnels)) > 0){
                $funnelsx = collect($request->funnels);
            }

            if(count(collect($request->contacts )) > 0){
                $contactsx  = collect($request->contacts);
            }

            if(count(collect($request->products)) > 0){
                $contactsx  = collect($request->products);
            }

            $filter = $request->filter;

            $sortBy = "id";
            $sortType = "desc";

            if(!empty($filter) && $filter['sort'] != ""){
                $_sort = explode("-", $filter['sort']);
                $sortBy = $_sort[0];
                $sortType = $_sort[1];
            }

            $uId = Auth::id();

            $_funnel = $funnels->pluck("label", "id");
            $fstatus = $request->status;
            $table_leads = Marketing_leads::where("company_id", Session::get("company_id"))
                ->where('layout_id', $layoutSelected)
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
                    // if(!empty($fstatus) && $fstatus != ""){
                    //     $q->whereNotNull("archive_at");
                    //     $q->where("status_deal", $fstatus);
                    // } else {
                    //     $q->whereNull("archive_at");
                    // }
                })
                ->where(function($q) use($tags, $contactsx, $products, $funnelsx){
                    // if(count(collect($tags)) > 0){
                    //     for($i=0;$i<count($tags); $i++){
                    //         if($i == 0){
                    //             $q->Where('tags', "LIKE", "%".$tags[$i]."%");
                    //         } else {
                    //             $q->OrWhere('tags', "LIKE", "%".$tags[$i]."%");
                    //         }
                    //     }
                    // }
                    // if(count(collect($contactsx)) > 0){
                    //     for($i=0;$i<count($contactsx); $i++){
                    //         if($i == 0){
                    //             $q->Where('contacts', "LIKE", "%".$contactsx[$i]."%");
                    //         } else {
                    //             $q->OrWhere('contacts', "LIKE", "%".$contactsx[$i]."%");
                    //         }
                    //     }
                    // }
                    // if(count(collect($products)) > 0){
                    //     for($i=0;$i<count($products); $i++){
                    //         if($i == 0){
                    //             $q->Where('products', "LIKE", "%".$products[$i]."%");
                    //         } else {
                    //             $q->OrWhere('products', "LIKE", "%".$products[$i]."%");
                    //         }
                    //     }
                    // }
                    // if(count(collect($funnelsx)) > 0){
                    //     $q->whereIn("funnel_id", $funnelsx);
                    // }
                })
                ->where(function($q) use($filter){
                    if(isset($filter['sales_confidence'])){
                        $q->where("sales_confidence", $filter['sales_confidence']);
                    }

                    if(isset($filter['priority'])){
                        $q->where("level_priority", $filter['priority']);
                    }

                    if(isset($filter['owner'])){
                        $q->where("partner", $filter['owner']);
                    }

                    if(isset($filter['company'])){
                        $q->where("id_client", $filter['company']);
                    }
                })
                ->orderBy($sortBy, $sortType)
                ->get();

            $leadH = Kjk_crm_lead_history::whereIn("lead_id", $table_leads->pluck("id"))
                ->orderBy("created_at", "desc")
                ->get();

            $lh = [];
            foreach($leadH as $item){
                $lh[$item->lead_id][] = $item->created_at;
            }

            $table = [];
            foreach($table_leads as $item){
                $lead_funnel[$item->funnel_id][] = $item;
                $row = [];
                $_comLeads =$comps[$item->id_client] ?? "-";
                $tag = "-";
                $_tags = [];
                $js_tag = json_decode($item->tags, true);
                if(is_array($js_tag)){
                    $tag = "";
                    $_tags = $js_tag;
                } else {
                    $_tags = [];
                    if($item->tags != "null"){
                        $col = [];
                        $col['value'] = $item->tags;
                        $_tags[] = $col;
                        $tag = "";
                    }
                }

                foreach ($_tags as $tg){
                    $tag .= "<span class='badge badge-tag badge-outline me-3 mb-3'>".$tg['value']."</span>";
                }

                $conf = "Nice to";
                $confClass = "secondary";
                if($item['sales_confidence'] == 1){
                    $confClass = "secondary";
                    $conf = "Nice to";
                } elseif($item['sales_confidence'] == 2){
                    $confClass = "success";
                    $conf = "Run through";
                } elseif($item['sales_confidence'] == 3){
                    $confClass = "warning";
                    $conf = "Best case";
                } elseif($item['sales_confidence'] == 4){
                    $confClass = "danger";
                    $conf = "Commit";
                }

                $prty = "Low";
                $ptyClass = "success";
                if($item['level_priority'] == 1){
                    $ptyClass = "success";
                    $prty = "Low";
                } elseif($item['level_priority'] == 2){
                    $ptyClass = "warning";
                    $prty = "Medium";
                } elseif($item['level_priority'] == 3){
                    $ptyClass = "danger";
                    $prty = "High";
                }

                // $conf = "-";
                // $confClass = "";
                // if($item['sales_confidence'] == 4){
                //     $confClass = "danger";
                //     $conf = "Commit";
                // } elseif($item['sales_confidence'] == 4){
                //     $confClass = "warning";
                //     $conf = "Best case";
                // } elseif($item['sales_confidence'] == 2){
                //     $confClass = "success";
                //     $conf = "Run through";
                // } elseif($item['sales_confidence'] == 1){
                //     $confClass = "secondary";
                //     $conf = "Nice to";
                // }

                // $prty = "-";
                // $ptyClass = "";
                // if($item['level_priority'] == 3){
                //     $ptyClass = "danger";
                //     $prty = "Tinggi";
                // } elseif($item['level_priority'] == 2){
                //     $ptyClass = "warning";
                //     $prty = "Sedang";
                // } elseif($item['level_priority'] == 1){
                //     $ptyClass = "success";
                //     $prty = "Rendah";
                // }

                $cons = "<div class='symbol-group symbol-hover'>";
                if(!empty($item->contributors) && $item->contributors != "null"){
                    $con = json_decode($item->contributors ?? "[]", true);
                    foreach($con as $ic => $cc){
                        if(isset($users[$cc])){
                            $tooltip = "<div class='d-flex flex-column align-items-center'><span>".($user_name[$cc] ?? "-")."</span>";
                            $tooltip .= "<span class='text-primary'>".Session::get("company_name_parent")."</span>";
                            $tooltip .= "<span>".($user_phone[$cc] ?? "-")."</span>";
                            $tooltip .= "<span>".($user_email[$cc] ?? "-")."</span>";
                            $tooltip .= "</div>";
                            $cons .= "<div class='symbol symbol-40px symbol-circle' data-bs-toggle='tooltip' data-bs-html='true' title=\"$tooltip\"><div class='symbol-label' style=\"background-image:url('".asset($user_img[$cc] ?? "theme/assets/media/avatars/blank.png.")."')\"></div></div>";
                        }
                    }
                }
                $cons .= "</div>";

                $url = route("crm.lead.add", ["layoutid" => $layoutSelected, "fid" => $item->funnel_id, "rowid" => $item->row_order]);
                if(!empty($item->archive_at)){
                    $url .= "?archive=1";
                }

                $updClass = "secondary";

                $_fd = $fdetail[$item->funnel_id] ?? [];
                $idleState = $_fd->idle_state ?? null;
                $warningState = $_fd->warning_state ?? null;

                $lupdate = $lh[$item->id] ?? [];

                $lupdate_date = $item->updated_at;

                if(!empty($lupdate)){
                    $lupdate_date = $lupdate[0];
                }

                $d1 = date_create(date("Y-m-d"));
                $d2 = date_create(date("Y-m-d", strtotime($lupdate_date)));
                $d3 = date_diff($d2, $d1);
                $days = $d3->format("%r%a");

                if(!empty($idleState)){
                    if($days > $idleState){
                        $updClass = "warning";
                    }
                }

                if(!empty($warningState)){
                    if($days > $warningState){
                        $updClass = "danger";
                    }
                }

                $row[] = "<div class=\"d-flex flex-column\"><span class=\"mb-3 text-dark fw-bold\">$item->leads_name</span></div>";
                $row[] = $_funnel[$item->funnel_id] ?? "-";
                $row[] = "<span class=\"badge badge-$ptyClass\">$prty</span>";
                $row[] = "<span class='badge badge-$confClass'>$conf</span>";
                $tooltip = "<div class='d-flex flex-column align-items-center'><span>".($user_name[$item->partner] ?? "-")."</span>";
                $tooltip .= "<span class='text-primary'>".Session::get("company_name_parent")."</span>";
                $tooltip .= "<span>".($user_phone[$item->partner] ?? "-")."</span>";
                $tooltip .= "<span>".($user_email[$item->partner] ?? "-")."</span>";
                $tooltip .= "</div>";
                $row[] = "<div class='symbol symbol-40px symbol-circle' data-bs-toggle='tooltip' data-bs-html='true' title=\"$tooltip\"><div class='symbol-label' style=\"background-image:url('".asset($user_img[$item->partner] ?? "theme/assets/media/avatars/blank.png")."')\"></div></div>";
                $row[] = $cons;
                $row[] = "<span class='badge badge-$updClass'>".date("d/m/Y", strtotime($lupdate_date))."</span>";
                $button = '<button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 0px">
                <i class="fa fa-ellipsis-vertical text-dark"></i>
                </button>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="javascript:;" data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="'.route('crm.lead.archive', $item->id).'" data-id="'.$item->id.'" data-name="'.$item->leads_name.'" class="menu-link px-3 text-danger">
                            Archive
                        </a>
                    </div>
                    <!--end::Menu item-->
                </div>';
                $row[] = $button;
                $table[] = $row;
            }

            $ltop = \App\Models\Kjk_crm_lead_top::where("user_id",Auth::id())
                ->whereIn("lead_id", $table_leads->pluck("id"))
                ->where("top", 1)
                ->pluck("lead_id")->toArray();

            $kanban = view("_crm.leads._funnels", compact("funnels", "ltop", "fdetail", "lh", "products", "lead_funnel", "comps", "contacts", "users", "m_menu", "leads", "leadLayout", "layoutSelected", "user_name", "user_img"))->render();

            $resp = [
                "table" => $table,
                "kanban" => $kanban
            ];

            return json_encode($resp);
        }

        if($request->a == "find"){
            $com = [];
            if($request->b == "contact"){
                $comp = $request->c;
                $t = $request->t;
                $contact = Kjk_crm_leads_contact::select("id", "name", 'comp_id')->where('company_id', Session::get('company_id'))
                        ->where("name", "like", "%$request->term%")
                        ->where(function($q) use($comp, $t){
                            if(!empty($comp)){
                                if(!empty($t)){
                                    $q->where("comp_id", null);
                                } else {
                                    $q->where("comp_id", $comp);
                                }
                            }
                        })
                        ->get();
                $companies = Marketing_clients::whereIn('id', $contact->pluck("comp_id"))->pluck("company_name", "id");
                foreach($contact as $item){
                    $col = [];
                    $col['label'] = "";
                    $col['value'] = $item->name;
                    $col['id'] = $item->id;
                    $col['name'] = $item->name;
                    $col['comp_id'] = $item->comp_id;
                    $col['company_name'] = $companies[$item->comp_id] ?? "";
                    $col['false'] = true;
                    $com[] = $col;
                }
            }

            if($request->b == "company"){
                $companies = Marketing_clients::select("id", "company_name as name")->where("company_id", Session::get("company_id"))
                    ->where("company_name", "like", "%$request->term%")
                    ->get();
                foreach($companies as $item){
                    $col = [];
                    $col['label'] = "";
                    $col['value'] = $item->name;
                    $col['id'] = $item->id;
                    $col['name'] = $item->name;
                    $col['false'] = true;
                    $com[] = $col;
                }
            }

            $count = count($com);

            // if($count == 0){
            //     $col = [];
            //     $col['value'] = "$request->term tidak ditemukan.";
            //     $col['id'] = null;
            //     $col['disabled'] = true;
            //     $com[] = $col;
            // }

            $data = [
                "count" => $count,
                "data" => $com
            ];

            return json_encode($data);
        }

        if($request->a == "top"){
            $ltop = \App\Models\Kjk_crm_lead_top::firstOrNew([
                "user_id" => Auth::id(),
                "lead_id" => $request->id
            ]);

            $ltop->top = $ltop->top == 1 ? 0 : 1;
            $ltop->save();

            return json_encode([
                "top" => $ltop->top,
                "ltop" => $ltop
            ]);
        }

        return view("_crm.leads.kanban", compact("funnels", "products", "lead_funnel", "comps", "contacts", "users", "m_menu", "leads", "leadLayout", "layoutSelected", "user_name", "user_img"));
    }

    function crmLeadUpdateFunnel(Request $request){
        $leads = Marketing_leads::find($request->id);

        $from = $leads->funnel_id;
        $to = $request->funnel;

        $leads->funnel_id = $request->funnel;
        $leads->funnel_update = date("Y-m-d H:i:s");
        $leads->save();

        $his = new Kjk_crm_lead_history();
        $his->lead_id = $leads->id;
        $his->from = $from;
        $his->to = $to;
        $his->created_by = Auth::id();
        $his->company_id = $leads->company_id;
        $his->save();

        $fLeads = Marketing_leads::where("company_id", Session::get('company_id'))->get();
        $leadsFunnel = [];
        foreach($fLeads as $item){
            $leadsFunnel[$item->funnel_id][] = $item;
        }

        $orders = $request->orders;
        foreach($orders as $f => $row){
            foreach($row as $id => $ld){
                $fl = $fLeads->where("id", $id)->first();
                if(!empty($fl)){
                    $fl->row_order = $ld;
                    $fl->save();
                }
            }
        }

        return json_encode($leads);
    }

    function crmFunnelUpdateOrder(Request $request){
        $funnel = Kjk_crm_lead_funnel::where("company_id", Session::get('company_id'))
            ->orderBy("row_order")
            ->get();

        $orders = $request->orders;
        foreach($orders as $id => $row){
            $fl = $funnel->where("id", $id)->first();
            if(!empty($fl)){
                $fl->row_order = $row;
                $fl->save();
            }
        }

        return json_encode($funnel);
    }

    function crmLeadDelete($id){
        $lead = Marketing_leads::find($id);
        $funnel_id = $lead->funnel_id;

        $lead->delete();

        $num = 1;
        $fLeads = Marketing_leads::where("funnel_id", $funnel_id)
            ->orderBy("funnel_id", "asc")
            ->get();
        foreach($fLeads as $item){
            $item->row_order = $num;
            $item->save();
            $num++;
        }

        return redirect()->route("crm.lead.index");
    }

    function crmLeadDeleteDetail($type, $id){
        $detail = [];
        $id_lead = null;
        if($type == "files"){
            $detail = Marketing_lead_files::find($id);
            $id_lead = $detail->id_lead;
        } elseif($type == "notes") {
            $detail = Marketing_lead_notes::find($id);
            $id_lead = $detail->id_leads;
        } elseif($type == "task"){
            $detail = Marketing_lead_tasks::find($id);
            $id_lead = $detail->id_lead;
        }

        $detail->delete();

        return redirect()->back()->with(['leads_id' => $id_lead, "toast" => [
            "message" => ucwords($type)." has been deleted successfully",
            "bg" => "bg-danger"
        ]]);
    }

    function crmLeadArchive($id){
        $leads = Marketing_leads::find($id);
        $leads->archive_at = date("Y-m-d H:i:s");
        $leads->archive_by = Auth::id();
        $leads->save();

        return redirect()->route("crm.lead.index");
    }

    function crmLeadAdd($layoutid, $fid, $rowid, Request $request){
        $funnels = Kjk_crm_lead_funnel::where("company_id", Session::get('company_id'))
            ->where("layout_id", $layoutid)
            ->orderBy("row_order")
            ->get();

        $emp = Hrd_employee::where("company_id", Session::get("company_id"))
            ->get();

        if(!empty($request->a)){
            if($request->a == "tags"){
                $tags = Kjk_crm_tag::where("company_id", Session::get("company_id"))
                    ->where("category", "lead")
                    ->get();

                $arr = [
                    "tags" => $tags->pluck("label")->toArray()
                ];

                return json_encode($arr);
            }

            if($request->a == "comp"){
                $comps = Marketing_clients::select("id", "company_name")->where('company_id', Session::get('company_id'))
                    ->where("company_name", "like", "%$request->term%")
                    ->get();
                $com = [];
                foreach($comps as $item){
                    $col = [];
                    $col['label'] = $item->company_name;
                    $col['value'] = $item->company_name;
                    $col['id'] = $item->id;
                    $com[] = $col;
                }


                return json_encode($com);
            }

            if($request->a == "contact"){
                $contact = Kjk_crm_leads_contact::select("id", "name")->where('company_id', Session::get('company_id'))
                    ->where("name", "like", "%$request->term%")
                    ->get();
                $com = [];
                foreach($contact as $item){
                    $col = [];
                    $col['label'] = "";
                    $col['value'] = $item->name;
                    $col['id'] = $item->id;
                    $col['name'] = $item->name;
                    $com[] = $col;
                }

                if($request->e == "tag"){
                    return json_encode($emp->pluck("emp_name")->toArray());
                }

                return json_encode($com);
            }
        }

        $products = Kjk_crm_product::where('company_id', Session::get("company_id"))
            ->get();

        $isArchive = $request->archive;

        $leads = Marketing_leads::where('funnel_id', $fid)
            ->where("layout_id", $layoutid)
            ->where("row_order", $rowid)
            ->where(function($q) use($isArchive){
                if($isArchive){
                    $q->whereNotNull("archive_at");
                } else {
                    $q->whereNull("archive_at");
                }
            })
            ->first();

        $leadComp = Marketing_clients::find($leads->id_client ?? null);
        $contacts = Kjk_crm_leads_contact::whereIn("id", json_decode($leads->contacts ?? "[]"))->get();
        $leadProducts = Kjk_crm_product::whereIn("id", json_decode($leads->products ?? "[]"))->get();

        $history = [];
        if(!empty($leads)){
            $col = [];
            $col['type'] = "create";
            $col['item'] = $leads->toArray();
            $history[date("Y-m-d", strtotime($leads->created_at))][date("H:i:s", strtotime($leads->created_at))] = $col;
        }

        $leadFiles = Marketing_lead_files::where("id_lead", $leads->id ?? null)->get();

        foreach($leadFiles as $item){
            $col = [];
            $col['type'] = "files";
            $col['item'] = $item->toArray();
            $history[date("Y-m-d", strtotime($item->created_at))][date("H:i:s", strtotime($item->created_at))] = $col;
        }

        $leadNotes = Marketing_lead_notes::where("id_leads", $leads->id ?? null)->get();

        foreach($leadNotes as $item){
            $col = [];
            $col['type'] = "notes";
            $col['item'] = $item->toArray();
            $history[date("Y-m-d", strtotime($item->created_at))][date("H:i:s", strtotime($item->created_at))] = $col;
        }

        $leadTask = Marketing_lead_tasks::where("id_lead", $leads->id ?? null)->get();

        foreach($leadTask as $item){
            $col = [];
            $col['type'] = "task";
            $col['item'] = $item->toArray();
            $history[date("Y-m-d", strtotime($item->created_at))][date("H:i:s", strtotime($item->created_at))] = $col;
        }

        $uId = [];
        if(!empty($leads)){
            $uId = array_merge($uId, $leads->pluck("created_by")->toArray());
        }
        if(count($leadFiles) > 0){
            $uId = array_merge($uId, $leadFiles->pluck("created_by")->toArray());
        }
        if(count($leadNotes) > 0){
            $uId = array_merge($uId, $leadNotes->pluck("created_by")->toArray());
        }
        if(count($leadTask) > 0){
            $uId = array_merge($uId, $leadTask->pluck("created_by")->toArray());
        }

        $user_activity = User::whereIn("id", $uId)->get()->pluck("name", "id");

        $activity = [];

        krsort($history);
        foreach($history as $date => $item){
            krsort($item);
            foreach($item as $val){
                $detail = $val['item'];
                $_user = "";
                if(isset($user_activity[$detail['created_by']])){
                    $_user = $user_activity[$detail['created_by']];
                }
                $col = $val;
                $col['date'] = $date;
                $col['user'] = $_user;
                $activity[] = $col;
            }
        }

        $comments = Kjk_lead_comment::where("lead_id", $leads->id ?? null)
            ->whereNull('comment_id')
            ->get();
        $comment_content = [];
        foreach($comments as $item){
            $comment_content[$item->content_type][$item->content_id][] = $item->toArray();
        }

        return view("_crm.leads.add", compact("funnels", "emp", "fid", "rowid", "products", "leads", "leadComp", "contacts", 'leadProducts', "activity", "layoutid", "comment_content"));
    }

    function crmLeadNotesAdd(Request $request){
        $notes = new Marketing_lead_notes();
        $notes->id_leads = $request->lead_id;
        $notes->notes = $request->notes;
        $notes->persons = json_encode($request->persons);
        $file = $request->file("attachment_notes");
        $notes->company_id = Session::get('company_id');
        $notes->created_by = Auth::id();
        if(!empty($file)){
            $dir = str_replace("/", "\\", public_path("media/attachments"));
            $_dir = str_replace("prototype\public_html", "public_html\kerjaku\assets", $dir);
            $tdir = str_replace("\\", "/", $_dir);
            $name = date("YmdHis")."-".$notes->id_leads."-".$file->getClientOriginalName();
            if($file->move($this->dir, $name)){
                $notes->file_name = $file->getClientOriginalName();
                $notes->file_address = "media/attachments/$name";
            }
        }

        $notes->save();

        return redirect()->back()->with([
            'leads_id' => $notes->id_leads,
            "toast" => [
                "message" => "Notes is sucessfully added to opportunity",
                "bg" => "bg-success"
            ]
        ]);
    }

    function crmLeadFileAdd(Request $request){
        $lFile = new Marketing_lead_files();
        $lFile->id_lead = $request->lead_id;
        $lFile->file_url = $request->file_url;
        $file = $request->file("attachment_notes");
        $lFile->company_id = Session::get('company_id');
        $lFile->created_by = Auth::id();
        if(!empty($file)){
            $dir = str_replace("/", "\\", public_path("media/attachments"));
            $_dir = str_replace("prototype\public_html", "public_html\kerjaku\assets", $dir);
            $tdir = str_replace("\\", "/", $_dir);

            $name = date("YmdHis")."-".$lFile->id_lead."-".$file->getClientOriginalName();
            if($file->move($this->dir, $name)){
                $lFile->file_name = $request->file_name == "" ?  $file->getClientOriginalName() : $request->file_name;
                $lFile->file_address = "media/attachments/$name";
            }
        }

        $lFile->save();

        return redirect()->back()->with(['leads_id' => $lFile->id_lead, "toast" => [
            "message" => "File is sucessfully added to opportunity",
            "bg" => "bg-success"
        ]]);
    }

    function crmLeadTaskAdd(Request $request){
        $task = new Marketing_lead_tasks();
        $task->id_lead = $request->lead_id;
        $task->title = $request->task;
        $task->notes = $request->notes;
        $task->persons = json_encode($request->persons ?? []);
        $task->created_by = Auth::id();
        $task->company_id = Session::get('company_id');

        $sdate = explode("/", $request->tanggal_tenggat);
        krsort($sdate);
        $due_date = implode("-", $sdate)." ".$request->waktu_tenggat.":00";
        $task->due_date = $due_date;
        $task->reminders = $request->reminders;
        $task->priority = $request->prioritas;
        $task->status = $request->status;
        $file = $request->file("attachment_task");
        if(!empty($file)){
            $dir = str_replace("/", "\\", public_path("media/attachments"));
            $_dir = str_replace("prototype\public_html", "public_html\kerjaku\assets", $dir);
            $tdir = str_replace("\\", "/", $_dir);
            $name = date("YmdHis")."-".$task->id_leads."-".$file->getClientOriginalName();
            if($file->move($this->dir, $name)){
                $task->file_name = $file->getClientOriginalName();
                $task->file_address = "media/attachments/$name";
            }
        }

        $task->save();

        return redirect()->back()->with(['leads_id' => $task->id_lead, "toast" => [
            "message" => "Task is sucessfully added to opportunity",
            "bg" => "bg-success"
        ]]);
    }

    function crmLeadDetail($id){
        $leads = Marketing_leads::withoutGlobalScopes()->find($id);

        $teams = Kjk_user_team::where("pipeline_id", "like", '%"'.$leads->layout_id.'"%')
            ->get();
        $members = [];
        foreach($teams as $item){
            $_m = $item->members ?? [];
            $members = array_merge($members, $_m);
        }

        $clients = Marketing_clients::where('company_id', Session::get('company_id'))->pluck("company_name", "id");
        $products = Kjk_crm_product::where("company_id", Session::get('company_id'))->pluck("label", "id");
        $users = User::where("company_id", Session::get("company_id"))->hris()->pluck("name", "id");
        $users_collab = User::where("company_id", Session::get("company_id"))->whereIn("id", $members)->hris()->get();
        $contacts = Kjk_crm_leads_contact::where("company_id", Session::get("company_id"))->pluck("name", "id");

        $history = [];
        if(!empty($leads)){
            $col = [];
            $col['type'] = "create";
            $col['item'] = $leads->toArray();
            $history[date("Y-m-d", strtotime($leads->created_at))][date("H:i:s", strtotime($leads->created_at))][] = $col;
        }

        $leadFiles = Marketing_lead_files::where("id_lead", $leads->id ?? null)->get();

        foreach($leadFiles as $item){
            $col = [];
            $col['type'] = "files";
            $col['item'] = $item->toArray();
            $history[date("Y-m-d", strtotime($item->created_at))][date("H:i:s", strtotime($item->created_at))][] = $col;
        }

        $leadNotes = Marketing_lead_notes::where("id_leads", $leads->id ?? null)->get();

        foreach($leadNotes as $item){
            $col = [];
            $col['type'] = "notes";
            $col['item'] = $item->toArray();
            $history[date("Y-m-d", strtotime($item->created_at))][date("H:i:s", strtotime($item->created_at))][] = $col;
        }

        $leadTask = Marketing_lead_tasks::where("id_lead", $leads->id ?? null)->get();

        foreach($leadTask as $item){
            $col = [];
            $col['type'] = "task";
            $col['item'] = $item->toArray();
            $history[date("Y-m-d", strtotime($item->created_at))][date("H:i:s", strtotime($item->created_at))][] = $col;
        }

        $updateHist = Kjk_crm_lead_update_history::opportunity()->where("lead_id", $leads->id)->get();
        foreach($updateHist as $item){
            $col = [];
            $col['type'] = "update_$item->target";
            $col['item'] = $item->toArray();
            $history[date("Y-m-d", strtotime($item->created_at))][date("H:i:s", strtotime($item->created_at))][] = $col;
        }

        $activity = [];

        krsort($history);
        foreach($history as $date => $item){
            krsort($item);
            foreach($item as $val){
                foreach($val as $ival){
                    $detail = $ival['item'];
                    $_user = "";
                    if(isset($users[$detail['created_by']])){
                        $_user = $users[$detail['created_by']];
                    }
                    $col = $ival;
                    $col['date'] = $date;
                    $col['user'] = $_user;
                    $col['uid'] = $detail['created_by'];
                    $activity[] = $col;
                }
            }
        }

        $comments = Kjk_lead_comment::where("lead_id", $leads->id ?? null)
            ->whereNull('comment_id')
            ->get();
        $comment_content = [];
        foreach($comments as $item){
            $comment_content[$item->content_type][$item->content_id][] = $item->toArray();
        }

        $funnel = Kjk_crm_lead_funnel::find($leads->funnel_id);

        $properties = Kjk_crm_property::where("type", "opportunity")
            ->where("layout_id", $leads->layout_id)
            ->whereNull("table_column")
            ->get();

        $propVal = Kjk_crm_property_value::whereIn("property_id", $properties->pluck("id"))
            ->where("target_id", $leads->id)
            ->where("type", "opportunity")
            ->get();
        $prop = [];
        foreach($propVal as $item){
            $prop[$item->property_id] = $item;
        }

        $view = view("_crm.leads.detail", compact("funnel", "leads", "clients", "contacts", 'products', "activity", "comment_content", "users", "properties", "prop", "users_collab"))->render();

        $collaborators = [];

        foreach($users_collab->whereIn('id', json_decode($leads->contributors, true)) as $item){
            $col = [];
            $col['id'] = $item->id;
            $col['name'] = $item->name;
            $col['phone'] = $item['phone'] ?? "-";
            $col['email'] = $item['email'] ?? "-";
            $col['company'] = Session::get('company_name_parent');
            $collaborators[] = $col;
        }

        $arr = [
            "view" => $view,
            'members' => $collaborators
        ];

        return json_encode($arr);
    }

    function crmLeadStore(Request $request){
        $order = 1;
        $last_order = Marketing_leads::where("funnel_id", $request->funnel)
            ->orderBy("row_order", "desc")
            ->first();
        if(!empty($last_order)){
            $order = $last_order->row_order + 1;
        }

        $funnel = Kjk_crm_lead_funnel::where("layout_id", $request->pipeline)->orderBy("row_order")->first();

        $funnels = Kjk_crm_lead_funnel::where('company_id', Session::get("company_id"))->orderBy("row_order")->first();

        $leads = Marketing_leads::findOrNew($request->lead_id);
        if(empty($request->lead_id)){
            $leads->company_id = Session::get("company_id");
            $leads->created_by = Auth::id();
        }
        $leads->layout_id = $request->pipeline ?? $funnels->layout_id;
        $leads->funnel_id = $funnel->id ?? $funnels->id;
        $leads->leads_name = $request->leads_name;
        $leads->id_client = $request->id_client;
        $leads->contacts = json_encode($request->contact_id ?? []);
        $leads->products = json_encode($request->product ?? []);
        $leads->partner = Auth::id();
        $leads->sumber = $request->source;
        $leads->row_order = $order;
        $leads->save();

        $contacts = Kjk_crm_leads_contact::whereIn("id", $request->contact_id ?? [])->get();
        foreach($contacts as $item){
            if(!empty($leads->id_client)){
                $item->comp_id = $leads->id_client;
                $item->save();
            }
        }

        if($request->ajax()){
            $resp = [
                "data" => $leads
            ];
            return json_encode($resp);
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Opportunity has been added",
                "bg" => "bg-success"
            ]
        ]);
    }

    function checkUpdate($key, $id, $val1, $val2){
        if($val1 != $val2){
            $history = new Kjk_crm_lead_update_history();
            $history->lead_id = $id;
            $history->target = $key;
            $history->before = $val1;
            $history->after = $val2;
            $history->type = "opportunity";
            $history->created_by = Auth::id();
            $history->save();
        }
    }

    function crmLeadUpdate(Request $request){

        $leads = Marketing_leads::findOrFail($request->id);

        $nom = str_replace(".", "", $request->nominal ?? "0");
        $request['nominal'] = str_replace(",", ".", $nom);

        $prof = str_replace(".", "", $request->estimasi_profit ?? "0");
        $request['estimasi_profit'] = str_replace(",", ".", $prof);

        $end_date = explode("/", $request->end_date);
        krsort($end_date);
        $request['end_date'] = implode("-", $end_date);

        foreach($request->all() as $key => $val){
            if(!in_array($key, ['_token', "id", "prod_sel" ,"collab_sel", "property"])){
                $_key = $key;
                if($key == "collaborators"){
                    $_key = "contributors";
                } elseif($key == "contact_id"){
                    $_key = "contacts";
                } elseif($key == "product_id"){
                    $_key = "products";
                } elseif($key == "source"){
                    $_key = "sumber";
                }
                $this->checkUpdate($_key, $leads->id, $leads[$_key], is_array($val) ? json_encode($val) : $val);
                $leads[$_key] = is_array($val) ? json_encode($val) : $val;
            }

            if($key == "property"){
                foreach($val as $idPro => $proVal){
                    if(!empty($proVal)){
                        $prop = Kjk_crm_property_value::firstOrCreate([
                            "property_id" => $idPro,
                            "type" => "opportunity",
                            "target_id" => $leads->id,
                            'company_id' => Session::get('company_id')
                        ]);

                        if(is_array($proVal)){
                            $prop->property_value = json_encode($proVal);
                            $prop->format = "array";
                        } else {
                            $prop->property_value = $proVal;
                            $prop->format = "text";
                        }
                        $prop->save();
                    }
                }
            }
        }

        $leads->save();

        $contacts = Kjk_crm_leads_contact::whereIn("id", $request->contact_id ?? [])->get();
        foreach($contacts as $item){
            if(!empty($leads->id_client) && empty($item->comp_id)){
                $item->comp_id = $leads->id_client;
            }
        }

        return redirect()->back()->with(['leads_id' => $leads->id, "toast" => [
            "message" => "Changes has been saved successfully",
            "bg" => "bg-success"
        ]]);
    }

    function crmFunnelAdd(Request $request){
        $num = 1;
        $last_funnel = Kjk_crm_lead_funnel::where("company_id", Session::get("company_id"))
            ->orderBy("row_order", "desc")
            ->first();
        if(!empty($last_funnel)){
            $num = $last_funnel->row_order + 1;
        }
        $funnel = new Kjk_crm_lead_funnel();
        $funnel->label = $request->funnel_label;
        $funnel->company_id = Session::get("company_id");
        $funnel->created_by = Auth::id();
        $funnel->row_order = $num;
        $funnel->layout_id = $request->layout_id;
        $funnel->save();

        return redirect()->back();
    }

    function crmFunnelDelete(Request $request){

        $fun = Kjk_crm_lead_funnel::find($request->fid);

        Marketing_leads::where('funnel_id', $fun->id)
            ->update([
                "funnel_id" => $request->funnel
            ]);

        $fun->delete();

        return redirect()->back();
    }

    function crmCompanyAdd(Request $request){
        $comp = Marketing_clients::findOrNew($request->comp_id);
        $comp->company_name = $request->name;
        $comp->company_id = Session::get('company_id');
        $comp->created_by = Auth::id();

        $comp->save();

        if($request->ajax()){
            $data = [
                "data" => $comp,
            ];
            return json_encode($data);
        }
    }

    function crmContactAdd(Request $request){
        $contact = Kjk_crm_leads_contact::findOrNew($request->con_id);;
        $contact->name = $request->name;
        $contact->company_id = Session::get('company_id');
        $contact->created_by = Auth::id();

        $contact->save();

        if($request->ajax()){
            $data = [
                "data" => $contact,
            ];
            return json_encode($data);
        }
    }

    function exportopp($type, Request $request){
        $pipeline = $request->pipeline;

        $user = Auth::user();

        $perm = \App\Models\Kjk_crm_permission::find($user->crm_role);
        $role = [];
        if(!empty($perm)){
            $role = json_decode($perm->permission ?? "[]", true)['user'] ?? [];
        }

        $uId = Auth::id();

        $leads = Marketing_leads::where("layout_id", $pipeline)
            ->where(function($q) use($role, $uId){
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
            ->get();

        $funnels = Kjk_crm_lead_funnel::whereIn("id", $leads->pluck("funnel_id"))->get();

        $company_name = Marketing_clients::whereIn("id", $leads->pluck('id_client'))->pluck("company_name", "id");
        $cp = Kjk_crm_leads_contact::where("company_id", Session::get("company_id"))->pluck("name", "id");

        $funnel_name = $funnels->pluck("label", "id");

        $users = User::hris()->where("company_id", Session::get("company_id"))->pluck("name", "id");

        if($type == "business"){
            $data['leads'] = $leads;
            $data['company_name'] = $company_name;
            $data['cp'] = $cp;
            $data['funnel_name'] = $funnel_name;
            $data['users'] = $users;
            $data['products'] = Kjk_crm_product::where("company_id", Session::get("company_id"))->pluck("label", "id");
            return Excel::download(new BUExport($data, "Business Unit"), 'business-unit.xlsx');
        } else {

            $data['mat']['company_name'] =  Marketing_clients::where("company_id", Session::get("company_id"))->get();
            $accPop = Kjk_crm_property::where("type", "company")->where("property_name", "Account Manager")->first();
            $data['mat']['account_manager'] = Kjk_crm_property_value::where("property_id", $accPop->id)
                ->whereIn('target_id', $data['mat']['company_name']->pluck('id'))
                ->where('type', "company")
                ->get();

            $data['fun']['funnels'] = $funnels;
            $_l = Marketing_leads::whereIn("funnel_id", $funnels->pluck('id'))
                ->where(function($q) use($role, $uId){
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
                ->get();
            $lf = $_l->pluck("funnel_id", "id");

            $fcount = [];
            foreach($_l as $item){
                $i = date("n", strtotime($item->created_at));
                $fcount[$item->funnel_id][$i][] = $item->id;
            }

            $_lh = Kjk_crm_lead_history::whereIn("lead_id", $_l->pluck("id"))->get();
            foreach($_lh as $item){
                $ff = $lf[$item->lead_id];
                $i = date("n", strtotime($item->created_at));
                $fcount[$ff][$i][] = $item->lead_id;
            }
            $data['fun']['fcount'] = $fcount;

            $ltop = \App\Models\Kjk_crm_lead_top::where("user_id",Auth::id())
                ->where("top", 1)
                ->pluck("lead_id")->toArray();
            $leads_top = Marketing_leads::whereIn("id", $ltop)
                ->where("layout_id", $pipeline)
                ->get();

            $data['top']['leads'] = $leads_top;
            $data['top']['company_name'] = Marketing_clients::whereIn("id", $leads_top->pluck('id_client'))->pluck("company_name", "id");;

            $data['users'] = $users;

            return Excel::download(new BOExport($data), "business-outlook.xlsx");
        }


    }
}
