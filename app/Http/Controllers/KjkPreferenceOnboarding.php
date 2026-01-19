<?php

namespace App\Http\Controllers;

use App\Models\Kjk_onboarding_form;
use App\Models\Kjk_onboarding_template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class KjkPreferenceOnboarding extends Controller
{
    private $uploadDir;

    public function __construct() {
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
        $this->uploadDir = str_replace("\\", "/", $dir);
    }

    function fd_index(Request $request){
        $data = Kjk_onboarding_form::where("company_id", Session::get("company_id"))->get();

        $pic = User::hris()->where("company_id", Session::get('company_id'))
            ->orderBy("name")
            ->get();

        if($request->a == "edit"){
            $form = $data->where("id", $request->id)->first();

            $view = view("_crm.preferences.onboarding.fd._edit", compact("form", 'pic'))->render();

            return json_encode([
                "view" => $view
            ]);
        }
        return view("_crm.preferences.onboarding.fd.index", compact("data", 'pic'));
    }

    function fd_post(Request $request){

        if($request->submit == "delete"){
            $form = Kjk_onboarding_form::find($request->id);
            $fname = $form->form_name;
            $form->deleted_by = Auth::id();
            $form->save();
            $form->delete();

            return redirect()->back()->with([
                "toast" => [
                    "message" => "$fname has been deleted",
                    "bg" => "bg-danger"
                ]
            ]);
        }

        $form_type = $request->form_type;
        $json = [];
        if($form_type == "upload_file"){
            $data = $request->upload;
            $form = Kjk_onboarding_form::findOrNew($request->id);
            $form->form_type = $form_type;
            $form->form_name = $data['document'] ?? null;
            $form->descriptions = $data['description'] ?? null;
            $form->file_format = $data['file_format'] ?? null;
            $form->due_date = $data['due_date'] ?? null;
            $attachment = $data['attachment'] ?? null;
            if(!empty($attachment)){
                $newName = Session::get("company_id")."_".$form_type."_".$attachment->getClientOriginalName();
                if($attachment->move($this->uploadDir, $newName)){
                    $form->file_address = "media/attachments/$newName";
                    $form->file_name = $attachment->getClientOriginalName();
                }
            }
            if(empty($request->id)){
                $form->company_id = Session::get("company_id");
                $form->created_by = Auth::id();
            }

            $form->save();
            $json[] = $form;

            $message = "$form->form_name has been ".(empty($request->id) ? "added to form database" : "edited");
        } elseif($form_type == "download_file"){
            $data = $request->download;
            $form = Kjk_onboarding_form::findOrNew($request->id);
            $form->form_type = $form_type;
            $form->form_name = $data['document'] ?? null;
            $form->descriptions = $data['description'] ?? null;
            $form->file_format = $data['type'] ?? null;
            $form->due_date = $data['due_date'] ?? null;
            if($data['type'] == "file"){
                $attachment = $data['attachment'] ?? null;
                if(!empty($attachment)){
                    $newName = Session::get("company_id")."_".$form_type."_".$attachment->getClientOriginalName();
                    if($attachment->move($this->uploadDir, $newName)){
                        $form->file_address = "media/attachments/$newName";
                        $form->file_name = $attachment->getClientOriginalName();
                    }
                }
            } else {
                $form->file_address = $data['link'];
                $fname = explode("/", $data['link']);
                $form->file_name = end($fname) ?? null;
            }
            if(empty($request->id)){
                $form->company_id = Session::get("company_id");
                $form->created_by = Auth::id();
            }

            $form->save();
            $json[] = $form;

            $message = "$form->form_name has been ".(empty($request->id) ? "added to form database" : "edited");
        } elseif($form_type == "task"){
            $data = $request->task;
            $documents = $data['document'] ?? [];
            $description = $data['description'] ?? [];
            $pic = $data['pic'] ?? [];
            $due_date = $data['due_date'] ?? [];

            foreach($documents as $i => $value){
                $form = Kjk_onboarding_form::findOrNew($request->id);
                $form->form_type = $form_type;
                $form->form_name = $value ?? null;
                $form->descriptions = $description[$i] ?? null;
                $form->pic = $pic[$i] ?? null;
                $form->due_date = $due_date[$i] ?? null;
                if(empty($request->id)){
                    $form->company_id = Session::get("company_id");
                    $form->created_by = Auth::id();
                }

                $form->save();
                $json[] = $form;

                $message = "$form->form_name has been ".(empty($request->id) ? "added to form database" : "edited");
            }
        }

        if($request->wantsJson()){
            return json_encode([
                "data" => $json
            ]);
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => $message,
                "bg" => "bg-success"
            ]
        ]);
    }

    function ot_index(){
        $data = Kjk_onboarding_template::where("company_id", Session::get('company_id'))->get();
        $pic = User::hris()->where("company_id", Session::get('company_id'))
            ->orderBy("name")
            ->get();

        $forms = Kjk_onboarding_form::where("company_id", Session::get("company_id"))->get();

        $fname = $forms->pluck("form_name", "id");
        $ftype = $forms->pluck("form_type", "id");
        $fdue_date = $forms->pluck("due_date", "id");

        $uname = $pic->pluck("name", "id");

        return view("_crm.preferences.onboarding.ot.index", compact("data", "pic", 'forms', 'uname', 'fname', 'ftype', 'fdue_date'));
    }

    function ot_post(Request $request){

        if($request->submit == "delete"){
            $template = Kjk_onboarding_template::find($request->id);
            $template->deleted_by = Auth::id();
            $fname = $template->name;
            $template->save();

            $template->delete();

            return redirect()->back()->with([
                "toast" => [
                    "message" => "$fname has been deleted",
                    "bg" => "bg-danger"
                ]
            ]);
        } else {
            $template = Kjk_onboarding_template::findOrNew($request->id);
            if(empty($request->id)){
                $template->company_id = Session::get("company_id");
                $template->created_by = Auth::id();
            }
            $template->name = $request->template_name;
            $template->forms = $request->id_forms;
            $template->save();

            return redirect()->back()->with([
                "toast" => [
                    "message" => empty($request->id) ? "$template->name has been added to onboarding template." : "$template->name has been edited.",
                    "bg" => "bg-success"
                ]
            ]);
        }
    }
}
