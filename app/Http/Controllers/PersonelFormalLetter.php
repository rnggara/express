<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hrd_contract_template as FormalLetter;
use App\Models\Hrd_contract_fields as FormalLetterField;
use App\Models\Hrd_contract_employee as FLEmp;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class PersonelFormalLetter extends Controller
{

    private $dir, $column;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
        $this->column = [
            "emp_name" => "Nama Employee",
            "emp_id" => "NIK",
            "join_date" => "Join Date",
            "emp_lahir" => "Tanggal Lahir",
            "emp_tmpt_lahir" => "Tempat Lahir",
            "pos" => "Posisi",
            "lvl" => "Job Level",
            "grd" => "Job Grade",
            "emp_stat" => "Employee Status",
            "act_pos" => "Acting Position",
            "dep" => "Departement",
            "loc" => "Lokasi"
        ];
    }
    function index(){

        $template = FormalLetter::where("company_id", Session::get("company_id"))->get();

        $user_name = User::whereIn('id', $template->pluck("created_by"))->pluck("name", "id");

        $columns = $this->column;

        return view("_personel.formal_letter.index", compact("template", "user_name", 'columns'));
    }

    function ajaxField(Request $request){
        $sl = [];
        $sl["text"] = "Text";
        $sl["int"] = "Number";
        $sl["currency"] = "Currency";
        $sl["time"] = "Time";
        $sl["date"] = "Date";
        $sl["position"] = "Position";
        if($request->type == "add"){
            $fields = new FormalLetterField();
            $fields->name = str_replace(" ", "_", $request->f_name);
            $fields->type_data = $request->f_type;
            $fields->data_length = $request->f_length;
            $fields->created_by = Auth::id();
            $fields->description = $request->desc;
            $fields->field_emp = $request->emp_field;
            $fields->company_id = Session::get('company_id');

            if($fields->save()){
                return json_encode(array(
                    "success" => true
                ));
            } else {
                return json_encode(array(
                    "success" => false
                ));
            }
        }

        if($request->type == "edit"){
            $f = FormalLetterField::find($request->id);
            $f->name = str_replace(" ", "_", $request->f_name);
            $f->description = $request->desc;
            $f->data_length = $request->f_length;
            $f->field_emp = $request->emp_field;
            if($f->save()){
                return json_encode(array(
                    "success" => true
                ));
            } else {
                return json_encode(array(
                    "success" => false
                ));
            }
        }

        if($request->type == "table"){
            $fields = FormalLetterField::where("type_data", "!=", "image")
                ->where("company_id", Session::get('company_id'))->get();
            $row = [];
            foreach($fields as $i => $item){
                $opts = "";
                foreach($this->column as $key => $v){
                    $sel = $item->field_emp == $key ? "selected" : "";
                    $opts .= "<option value='$key' $sel>$v</option>";
                }
                $col = [];
                $col[] = $i+1;
                $col[] = "<input type='text' name='f_name' class='form-control' value='$item->name'>";;
                $col[] = "<input type='text' name='desc' class='form-control' value='$item->description'>";;
                $col[] = $sl[$item->type_data];
                $col[] = "<input type='number' name='f_length' class='form-control' value='$item->data_length'>";
                $col[] = '<div id="trId'.$item->id.'"><select name="emp_field" class="form-control" data-control="select2" data-dropdown-parent="#tbModal">
                <option value="">Free Text</option>
                '.$opts.'
            </select></div>';
                $btn = "<button type='button' onclick='editField(this)' class='btn btn-sm btn-icon btn-primary' data-id='$item->id'><i class='fa fa-edit'></i></button>";
                $btn .= "<button type='button' onclick='_delete($item->id)' class='btn btn-sm btn-icon btn-danger btn-delete' data-id='$item->id'><i class='fa fa-trash'></i></button>";
                $col[] = $btn;
                $row[] = $col;
            }

            $data = [
                "data" => $row
            ];

            return json_encode($data);
        }

        if($request->type == "delete"){
            $fields = FormalLetterField::find($request->id);
            if($fields->delete()){
                return json_encode(array(
                    "success" => true
                ));
            } else {
                return json_encode(array(
                    "success" => false
                ));
            }
        }

        if($request->type == "entry"){
            $emp = \App\Models\Hrd_employee::find($request->id);
            $tp = FormalLetter::find($request->template);

            $content = explode("((", $tp->content);
            $tag = [];
            for ($i=0; $i < count($content); $i++) {
                $end = explode("))", $content[$i]);
                if(isset($end[1])){
                    $tag[] = $end[0];
                }
            }

            $flds = FormalLetterField::where(function($query) use($tag){
                for ($i=0; $i < count($tag); $i++) {
                    $_name = $tag[$i];
                    $query->orWhere('name', $_name);
                }
            })->where('company_id', Session::get('company_id'))->where("type_data", "!=", "image")
            ->orderBy('name')->get();

            $emp->pos = $emp->position->name ?? "";
            $emp->lvl = $emp->job_level->name ?? "";
            $emp->grd = $emp->job_grade->name ?? "";
            $emp->dep = $emp->user->uacdepartement->name ?? "";
            $emp->loc = $emp->user->uaclocation->name ?? "";
            $emp->emp_stat = $emp->employee_status->label ?? "";
            $emp->act_pos = $emp->acting_position->name ?? "";

            $view = view("_personel.formal_letter.fields", compact("tp", "flds", 'emp'))->render();

            return json_encode(['view' => $view, "flds" => $flds]);
        }

        // if($request->type == "entry_edit"){
        //     $entry = Qhse_project_plan_entry::find($request->id);
        //     $tp = Qhse_project_plan::find($entry->plan_id);

        //     $val = json_decode($entry->contents ?? "[]", true);

        //     $content = explode("((", $tp->contents);
        //     $tag = [];
        //     for ($i=0; $i < count($content); $i++) {
        //         $end = explode("))", $content[$i]);
        //         if(isset($end[1])){
        //             $tag[] = $end[0];
        //         }
        //     }

        //     $flds = FormalLetterField::where(function($query) use($tag){
        //         for ($i=0; $i < count($tag); $i++) {
        //             $_name = str_replace("_", " ", $tag[$i]);
        //             $query->orWhere('name', $_name);
        //         }
        //     })->where('type', $request->field_type)->where('entry_id', $request->entry_id)->where("type_data", "!=", "image")
        //     ->orderBy('name')->get();

        //     $view = view("hse.plan.editEntry", compact("tp", "flds", 'val', 'entry'))->render();

        //     return json_encode(['view' => $view]);
        // }
    }

    function get_field(Request $request){
        $fields = FormalLetterField::where("company_id", Session::get('company_id'))->orderBy('name')
            ->get();
        $row = [];
        foreach($fields as $item){
            $col = [];
            $col['text'] = str_replace("\"", "'", $item->description);
            $col['value'] = "((".str_replace(" ", "_", strtolower($item->name))."))";
            if ($item->type_data == "image") {
                if($item->tp_id == $request->tp_id){
                    $row[] = $col;
                }
            } else {
                $row[] = $col;
            }
        }

        $col = [];
        $col['text'] = "Add a page break";
        $col['value'] = "<--new_break-->";
        $row[] = $col;

        return json_encode($row);
    }

    function delete($id){
        $plan = FormalLetter::find($id);

        if(!empty($plan)) $plan->delete();

        return redirect()->back();
    }

    function add(Request $request){

        $fields = FormalLetterField::where("company_id", Session::get('company_id'))
            ->where('type_data', "!=", "image")
            ->orderBy('name')
            ->get();
        $row = [];
        foreach($fields as $item){
            $col = [];
            $col['text'] = str_replace("\"", "'", $item->description);
            $col['value'] = "((".str_replace(" ", "_", strtolower($item->name))."))";
            $row[] = $col;
        }

        $tp = FormalLetter::find($request->tid);

        $images = FormalLetterField::where("company_id", Session::get('company_id'))
            ->where("tp_id", $tp->id ?? null)
            ->where('type_data', "image")
            ->orderBy('name')
            ->get();
        $row_image = [];
        foreach($images as $item){
            $col = [];
            $col['text'] = str_replace("\"", "'", $item->description);
            $col['value'] = "((".str_replace(" ", "_", strtolower($item->name))."))";
            $col['uri'] = asset($item->uri);
            $row_image[] = $col;
        }

        return view("_personel.formal_letter.add", compact("row", 'row_image', 'tp'));
    }

    function upload_attachment(Request $request){

        $file = $request->file("attachment");
        if(!empty($file)){
            $type = $request->tp;
            $eid = $request->eid;
            $ogi_name = str_replace(" ", "_", $file->getClientOriginalName());
            $name = $file->getClientOriginalName();
            $newName = date("YmdHis")."_".$type."_".$eid."_".$file->getClientOriginalName();

            if($file->move($this->dir, $newName)){
                $fields = new FormalLetterField();
                $fields->name = $ogi_name;
                $fields->type_data = "image";
                $fields->uri = "media/attachments/$newName";
                $fields->created_by = Auth::user()->username;
                $fields->description = $name;
                $fields->tp_id = $request->tp_id;
                $fields->company_id = Session::get('company_id');
                $fields->save();
            }
        }

        return redirect()->back();
    }

    function save(Request $request){
        $template = FormalLetter::find($request->id_tp);
        if(empty($template)){
            $template = new FormalLetter();
            $template->created_by = Auth::id();
            $template->company_id = Session::get('company_id');
        } else {
            $template->updated_by = Auth::id();
        }
        $template->name = $request->template_name;
        $template->content = $request->content;
        $template->save();

        return redirect()->route("personel.fl.index");
    }

    function print($id){
        $entry = FLEmp::find($id);

        $tp = FormalLetter::find($entry->template_id);

        $val = json_decode($entry->contents ?? "[]", true);

        $field = FormalLetterField::where("company_id", Session::get("company_id"))
            ->where("type_data", "!=", "image")
            ->get();

        $flds = [];
        foreach($field as $item){
            $flds[strtolower($item->name)] = $item->type_data;
        }

        $images = FormalLetterField::where("company_id", Session::get("company_id"))
            ->where("type_data", "image")
            ->get();

        $dimage = [];
        foreach($images as $item){
            $dimage[strtolower($item->name)] = $item->uri;
        }
        $ct = "";
        $content = explode("((", $tp->content ?? "");

        for ($i=0; $i < count($content); $i++) {
            if(stripos($content[$i], "&lt;--new_break--&gt;") !== false){
                $content[$i] = str_replace("&lt;--new_break--&gt;", "<div class='page-break'></div>", $content[$i]);
            }
            $end = explode("))", $content[$i]);

            if(isset($end[1])){
                // dd($end[0]);
                if($end[0] != "<--new_break-->"){
                    $txt = (isset($val[$end[0]])) ? $val[$end[0]] : "";
                    if (isset($flds[$end[0]]) && $flds[$end[0]] == "date") {
                        $ct .= date("d F Y", strtotime($txt));
                    } elseif(isset($dimage[$end[0]])){
                        $_src = asset($dimage[$end[0]]);
                        $ct .= "<image class='w-100' src='$_src'>";
                    } else {
                        $ct .= $txt;
                    }
                    $ct .= $end[1];
                } else {
                    $ct .= "<div class='page-break'></div>";
                }
            } else {
                $ct .= ucwords(str_replace("_", " ", $content[$i]));
            }
        }

        $view = view("_personel.formal_letter.print", compact("ct"));

        return $view;
    }

    function approve_request(Request $request){
        $submit = $request->submit ?? "approve";
        $req = \App\Models\Personel_employment_letter_request::find($request->req_id);

        $emp_id = $req->emp_id;

        if($submit == "approve"){
            $req->letter_id = $request->letter_id;
            $req->approved_by = Auth::id();
            $req->approved_at = date("Y-m-d H:i:s");
        } else {
            $req->letter_id = null;
            $req->approved_by = null;
            $req->approved_at = null;
        }

        $req->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Employment Letter Request has been $submit",
                "bg" => "bg-success"
            ],
            "fl" => $emp_id
        ]);
    }
}
