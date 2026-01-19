<?php

namespace App\Http\Controllers;

use App\Models\Kjk_onboarding_form;
use App\Models\Kjk_onboarding_template;
use App\Models\Personel_onboarding;
use App\Models\Personel_onboarding_detail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class PersonelOnboarding extends Controller
{
    private $uploadDir;

    public function __construct() {
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
        $this->uploadDir = str_replace("\\", "/", $dir);
    }

    function index(Request $request){

        $users = User::hris()->where("company_id", Session::get("company_id"))
            ->get();

        $templates = Kjk_onboarding_template::where("company_id", Session::get("company_id"))
            ->get();

        $forms = Kjk_onboarding_form::where("company_id", Session::get("company_id"))
            ->get();

        $onboard = Personel_onboarding::where("company_id", Session::get("company_id"))
            ->get();

        $pic = $users;

        $udetail = [];
        foreach($users as $item){
            $udetail[$item->id] = $item;
        }

        $tname = $templates->pluck("name", "id");

        $ftype = $forms->pluck("form_type", "id");

        $details = Personel_onboarding_detail::whereIn("onboard_id", $onboard->pluck("id"))->get();

        $odetail = [];
        foreach($details as $item){
            $tt = $ftype[$item->form_id] ?? null;
            if(!empty($tt)){
                $odetail[$item->onboard_id][$tt][] = $item;
            }
        }

        if($request->a == "template"){
            $template = $templates->where("id", $request->id)->first();

            $list_forms = Kjk_onboarding_form::whereIn("id", $template->forms)->get();

            $row = [];
            foreach($list_forms as $item){
                $col = [];
                $col['id'] = $item->id;
                $col['name'] = $item->form_name;
                $col['type'] = ucwords(str_replace("_", " ", $item->form_type));
                $col['due_date'] = $item->due_date." days";
                $row[] = $col;
            }

            return json_encode([
                "data" => $row
            ]);
        }

        if($request->a == "detail"){
            $board = Personel_onboarding::find($request->id);
            $user = $users->find($board->user_id);

            $board_detail = Personel_onboarding_detail::where("onboard_id", $board->id)->get();

            $brDetail = [];

            $fdetail = [];
            foreach($forms as $item){
                $fdetail[$item->id] = $item;
            }

            foreach($board_detail as $item){
                $tt = $ftype[$item->form_id] ?? null;
                if(!empty($tt)){
                    $col = [];
                    $col['id'] = $item->id;
                    $col['form_id'] = $item->form_id;
                    $col['due_date'] = $item->due_date;
                    $col['file_name'] = $item->file_name;
                    $col['file_address'] = $item->file_address;
                    $col['action_at'] = $item->action_at;
                    $col['type'] = $tt;
                    $col['approved_at'] = $item->approved_at;
                    $col['detail'] = $fdetail[$item->form_id];
                    $brDetail[] = $col;
                }
            }

            $brDetail = collect($brDetail);

            $pic_name = $users->pluck("name", "id");

            $tp = $request->tp ?? null;

            $view = view("_personel.onboarding._detail", compact("user", "board", 'brDetail', 'pic_name', 'tp'))->render();

            return json_encode([
                "view" => $view,
                'br' => $brDetail
            ]);
        }

        return view("_personel.onboarding.index", compact('users', 'templates', 'pic', 'forms', 'tname', 'onboard', 'udetail', 'odetail'));
    }

    function update(Request $request){
        $board = Personel_onboarding_detail::find($request->id);

        $success = false;
        $message = "";
        $data = null;

        if($request->type == "upload_file"){
            $attachment = $request->file("attachment");
            if(!empty($attachment)){
                $newName = $request->type."_".$board->onboard_id."_".$board->id."_".$attachment->getClientOriginalName();
                try {
                    $attachment->move($this->uploadDir, $newName);
                    $board->file_address = "media/attachments/$newName";
                    $board->file_name = $attachment->getClientOriginalName();
                    $board->action_at = date("Y-m-d H:i:s");
                    $board->action_by = Auth::id();
                    $board->save();

                    $data = date("d-m-Y", strtotime($board->action_at));

                    $success = true;
                } catch (\Throwable $th) {
                    $message = $th->getMessage();
                }
            }
        } elseif($request->type == "download_file"){
            $board->action_at = date("Y-m-d H:i:s");
            $board->action_by = Auth::id();
            $board->save();

            $form = Kjk_onboarding_form::find($board->form_id);

            $data = $form->file_format == "link" ? $form->file_address : asset($form->file_address);
            $message = $form->file_name;
            $success = true;
        } elseif($request->type == "task"){
            $attachment = $request->file("attachment");
            if(!empty($attachment)){
                $newName = $request->type."_".$board->onboard_id."_".$board->id."_".$attachment->getClientOriginalName();
                try {
                    $attachment->move($this->uploadDir, $newName);
                    $board->file_address = "media/attachments/$newName";
                    $board->file_name = $attachment->getClientOriginalName();
                    $board->action_at = date("Y-m-d H:i:s");
                    $board->action_by = Auth::id();
                    $board->save();

                    $data = date("d-m-Y", strtotime($board->action_at));

                    $success = true;
                } catch (\Throwable $th) {
                    $message = $th->getMessage();
                }
            }
        } elseif($request->type == "task_appr"){
            $board->approved_at = $request->checked == "true" ? date("Y-m-d H:i:s") : null;
            $board->approved_by = $request->checked == "true" ? Auth::id() : null;
            $board->save();
            $success = true;
        } elseif($request->type == "notif"){
            $board = Personel_onboarding::find($request->id);
            $board->last_notify = date("Y-m-d");
            $board->save();

            $success = true;
        }

        return json_encode([
            "success" => $success,
            "data" => $data,
            'message' => $message
        ]);
    }

    function detail_update(Request $request){

        $user = User::find($request->id);

        $board = Personel_onboarding::where("user_id", $request->id)->first();

        $forms = $request->id_forms ?? [];

        $f = Kjk_onboarding_form::whereIn("id", $forms)->get();
        $f_due = $f->pluck("due_date", "id");

        foreach($forms as $item){
            $_f = $f_due[$item] ?? 7;
            $due_date = date("Y-m-d", strtotime("+$_f days"));
            $detail = new Personel_onboarding_detail();
            $detail->onboard_id = $board->id;
            $detail->form_id = $item;
            $detail->due_date = $due_date;
            $detail->company_id = $board->company_id;
            $detail->created_by = Auth::id();
            $detail->save();
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Form has been added to $user->name onboarding.",
                "bg" => "bg-success"
            ]
        ]);
    }

    function store(Request $request){

        $user = User::find($request->user_id);

        $onboard = new Personel_onboarding();
        $onboard->user_id = $request->user_id;
        $onboard->type = $request->template;
        $onboard->template_id = $request->template == "template" ? $request->obtemplate : null;
        $onboard->company_id = Session::get("company_id");
        $onboard->created_by = Auth::id();
        $onboard->save();

        $forms = $request->id_forms ?? [];

        $f = Kjk_onboarding_form::whereIn("id", $forms)->get();
        $f_due = $f->pluck("due_date", "id");

        foreach($forms as $item){
            $_f = $f_due[$item] ?? 7;
            $due_date = date("Y-m-d", strtotime("+$_f days"));
            $detail = new Personel_onboarding_detail();
            $detail->onboard_id = $onboard->id;
            $detail->form_id = $item;
            $detail->due_date = $due_date;
            $detail->company_id = $onboard->company_id;
            $detail->created_by = Auth::id();
            $detail->save();
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "$user->name has been added to onboarding.",
                "bg" => "bg-success"
            ]
        ]);
    }

    function approve(Request $request){
        $tasks = Personel_onboarding_detail::whereIn("id", $request->task_id)->get();

        foreach($tasks as $item){
            $item->approved_at = date("Y-m-d H:i:s");
            $item->approved_by = Auth::id();
            $item->save();
        }

        return redirect()->back();
    }

    function upload_data($id = null, $returnData = null){
        $user = User::find($id ?? Auth::id());

        if(empty($user)){
            return abort("404");
        }

        $board = Personel_onboarding::where("user_id", $user->id ?? null)->first();

        $board_detail = Personel_onboarding_detail::where("onboard_id", $board->id ?? null)->get();

        $brDetail = [];

        $forms = Kjk_onboarding_form::where("company_id", Session::get("company_id"))
            ->get();

        $ftype = $forms->pluck("form_type", "id");

        $fdetail = [];
        foreach($forms as $item){
            $fdetail[$item->id] = $item;
        }

        foreach($board_detail as $item){
            $tt = $ftype[$item->form_id] ?? null;
            if(!empty($tt)){
                $col = [];
                $col['id'] = $item->id;
                $col['form_id'] = $item->form_id;
                $col['due_date'] = $item->due_date;
                $col['file_name'] = $item->file_name;
                $col['action_at'] = $item->action_at;
                $col['type'] = $tt;
                $col['approved_at'] = $item->approved_at;
                $col['detail'] = $fdetail[$item->form_id];
                $brDetail[] = $col;
            }
        }

        $brDetail = collect($brDetail);

        $users = User::hris()->where("company_id", Session::get("company_id"))
            ->get();

        $pic_name = $users->pluck("name", "id");

        if($returnData){
            return $brDetail;
        }

        if($user->id != Auth::id()){
            return abort("404");
        }

        return view("_personel.onboarding.upload", compact("user", "board", 'brDetail', 'pic_name'));
    }

    function approve_data($id = null, $returnData = null){
        $user = User::find($id ?? Auth::id());

        if(empty($user)){
            return abort("404");
        }

        $forms = \App\Models\Kjk_onboarding_form::where("pic", $user->id)
            ->get();
        $ff = [];
        foreach($forms as $item){
            $ff[$item->id] = $item;
        }

        $onboarding_detail = \App\Models\Personel_onboarding_detail::whereIn("form_id", $forms->pluck("id"))
            ->get();

        $onboarding = \App\Models\Personel_onboarding::whereIn("id", $onboarding_detail->pluck("onboard_id"))
            ->get();

        $obdetail = [];
        foreach($onboarding_detail as $item){
            $_f = $ff[$item->form_id] ?? [];
            $col = [];
            $col['id'] = $item->id;
            $col['form_type'] = $_f->form_type;
            $col['form_name'] = $_f->form_name;
            $col['form_descriptions'] = $_f->descriptions;
            $col['form_file_format'] = $_f->file_format;
            $col['form_file_address'] = $_f->file_address;
            $col['form_file_name'] = $_f->file_name;
            $col['due_date'] = $item->due_date;
            $col['file_address'] = empty($item->file_address) ? NULL : asset($item->file_address);
            $col['file_name'] = $item->file_name;
            $col['upload_file_at'] = $item->file_at;
            $col['action_at'] = $item->action_at;
            $col['approved_at'] = $item->approved_at;
            $col['approved_by'] = $item->approved_by;
            $obdetail[$item->onboard_id][] = $col;
        }

        $users = User::whereIn("id", $onboarding->pluck('user_id'))
            ->get();

        $resigned = \App\Models\Personel_resign::where("company_id", $user->company_id)
            ->where("resign_date", "<=", date("Y-m-d"))->get();

        $dataemp = \App\Models\Hrd_employee::where("company_id", $user->company_id)
            ->whereNotIn("id", $resigned->pluck("emp_id"))
            ->whereIn("id", $users->pluck("emp_id"))
            ->get();

        $emp = [];
        foreach($dataemp as $item){
            $emp[$item->id] = $item;
        }

        $row = [];

        foreach($users as $item){
            $_emp = $emp[$item->emp_id] ?? [];
            if(!empty($_emp)){
                $ob = $onboarding->where("user_id", $item->id)->first();

                $obd = collect($obdetail[$ob->id] ?? []);
                $col = [];
                $task = $obd;
                $filled_data = $obd->whereNotNull("approved_at");
                $col['id'] = $item->id;
                $col['emp_id'] = $_emp->emp_id;
                $col['name'] = $_emp->emp_name;
                $col['task'] = $task;
                $col['redirect_url'] = "https://hris.kerjaku.cloud/personel/onboarding/approve-data/$item->id";
                if(count($task) != count($filled_data)){
                    $row[] = $col;
                }
            }
        }

        if($returnData){
            return $row;
        }

        if($user->id != Auth::id()){
            return abort("404");
        }


        return view("_personel.onboarding.approve", compact("row"));
    }
}
