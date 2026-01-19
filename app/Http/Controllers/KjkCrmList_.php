<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee;
use App\Models\Kjk_crm_lead_update_history;
use App\Models\Kjk_crm_leads_contact;
use App\Models\Kjk_crm_tag;
use App\Models\Kjk_list_file;
use App\Models\Kjk_list_note;
use App\Models\Marketing_clients;
use App\Models\Marketing_lead_files;
use App\Models\Marketing_leads;
use App\Models\Master_city;
use App\Models\Master_province;
use App\Models\Master_var_emp;
use App\Models\Master_variables_model;
use App\Models\Master_religion;
use App\Models\User;
use App\Models\Kjk_crm_property;
use App\Models\Kjk_crm_property_value;
use App\Models\Kjk_crm_address;
use App\Models\Kjk_list_comment;
use App\Imports\MarketingClientsImport;
use App\Imports\KjkCrmLeadContacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class KjkCrmList extends Controller
{

    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    function index(Request $request){
        $products = [];
        $leads = Marketing_leads::where("company_id", Session::get("company_id"))->get();

        $files = Marketing_lead_files::where("company_id", Session::get("company_id"))->get();
        $contacts = Kjk_crm_leads_contact::where("company_id", Session::get('company_id'))->get();
        $company = Marketing_clients::where("company_id", Session::get("company_id"))->get();

        $uId = [];
        $uId = array_merge($uId, $files->pluck('created_by')->toArray(), $contacts->pluck("created_by")->toArray(), $company->pluck("created_by")->toArray());

        $users = User::whereIn("id", array_unique($uId))->get()->pluck("name", "id");
        $leadsPluck = $leads->pluck("leads_name", "id");
        $province = Master_province::get();
        $city = Master_city::get();
        $comPluck = $company->pluck("company_name", "id");
        $pPluck = $province->pluck("name", "id");
        $cPluck = $city->pluck("name", "id");

        $opportunity = Marketing_leads::where("company_id", Session::get("company_id"))->get();

        $opp = [];
        $op_file = [];
        foreach($opportunity as $item){
            $opp[$item->id_client][] = $item;
            $op_file[$item->id] = $item;
        }

        $cp = [];
        foreach($contacts as $item){
            $cp[$item->comp_id][] = $item;
        }

        if(!empty($request->a)){
            if($request->a == "contact"){
                $tags = Kjk_crm_tag::where("company_id", Session::get("company_id"))
                    ->where("category", "contact")
                    ->get();

                $arr = [
                    "tags" => $tags->pluck("label")->toArray()
                ];

                return json_encode($arr);
            }

            if($request->a == "get-company"){
                $companies = Marketing_clients::select("id", "company_name as name")->where("company_id", Session::get("company_id"))
                    ->where("company_name", "like", "%$request->term%")
                    ->get();
                $com = [];
                foreach($companies as $item){
                    $col = [];
                    $col['label'] = "";
                    $col['value'] = $item->name;
                    $col['id'] = $item->id;
                    $col['name'] = $item->name;
                    $col['false'] = true;
                    $com[] = $col;
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

            if($request->a == "get-contact"){
                $contact = Kjk_crm_leads_contact::select("id", "name")->where('company_id', Session::get('company_id'))
                    ->where("name", "like", "%$request->term%")
                    ->whereNull("comp_id")
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

                $count = count($com);

                $data = [
                    "count" => $count,
                    "data" => $com
                ];

                return json_encode($data);
            }

            if($request->a == "get-opportunity"){
                $opportunity = Marketing_leads::select("id", "leads_name")->where('company_id', Session::get('company_id'))
                    ->where("leads_name", "like", "%$request->term%")
                    ->where(function($q) use($request){
                        $tp = $request->type;
                        if($tp == "company"){
                            $q->where("id_client", '!=', $request->id);
                        } elseif($tp == "contacts"){
                            $q->where("contacts", "not like", "%\"$request->id\"%");
                        }
                        $q->orWhereNull("id_client");
                    })
                    ->get();
                $com = [];
                foreach($opportunity as $item){
                    $col = [];
                    $col['label'] = "";
                    $col['value'] = $item->leads_name;
                    $col['id'] = $item->id;
                    $col['name'] = $item->leads_name;
                    $com[] = $col;
                }

                $count = count($com);

                $data = [
                    "count" => $count,
                    "data" => $com
                ];

                return json_encode($data);
            }
        }

        $t = $request->t ?? "perusahaan";

        // foreach($company as $item){
        //     $
        // }

        $vars = Master_variables_model::whereIn("var_type", ['contact', "company"])
            ->where("company_id", Session::get("company_id"))
            ->get();

        return view("_crm.list.index", compact('opp','cp','op_file','products', "leads", "users", "files", "leadsPluck", "province", "city", "company", "contacts", "pPluck", "cPluck", "comPluck", "t", "vars"));
    }

    function checkUpdate($key, $id, $val1, $val2, $type){
        $v2 = is_array($val2) ? json_encode($val2) : $val2;
        $v1 = is_array($val1) ? json_encode($val1) : $val1;
        if($v1 != $v2){
            $history = new Kjk_crm_lead_update_history();
            $history->lead_id = $id;
            $history->target = $key;
            $history->before = $v1;
            $history->after = $v2;
            $history->type = $type;
            $history->created_by = Auth::id();
            $history->save();
        }
    }

    function add($type, Request $request){
        $iId = null;
        $lId = null;
        $iCom = null;
        if($type == "file"){
            $lFile = Marketing_lead_files::findOrNew($request->id);
            if(empty($request->id)){
                $lFile->id_lead = $request->lead;
                $lFile->company_id = Session::get('company_id');
                $lFile->created_by = Auth::id();
            }
            $file = $request->file("attachment");
            if(!empty($file)){
                $dir = str_replace("/", "\\", public_path("media/attacments"));
                $_dir = str_replace("prototype/public_html", "public_html/kerjaku/assets", $dir);
                $tdir = str_replace("\\", "/", $_dir);
                $name = date("YmdHis")."-".$lFile->id_leads."-".$file->getClientOriginalName();
                if($file->move($this->dir, $name)){
                    $lFile->file_name = $request->file_name ?? $file->getClientOriginalName();
                    $lFile->file_address = "media/attachments/$name";
                }
            }
            $lFile->id_lead = $request->opportunity_id;
            $lFile->file_url = $request->file_url ?? null;

            $lFile->save();
            $iId = $lFile->id;
            $r = route("crm.list.index")."?t=file";
        } elseif($type == "contact"){
            // dd($request->all());
            $_date = explode("/", $request->birth_date);
            $bdate = null;
            if(!empty($request->birth_date)){
                $bdate = $_date[2]."-".$_date[1]."-".$_date[0];
            }
            $con = Kjk_crm_leads_contact::findOrNew($request->id);
            $phone = $request->phone ?? [];
            $ptype = $request->phone_types;

            $arrPhone = [];

            foreach($phone as $i => $item){
                $col = [];
                $col['type'] = $ptype[$i] ?? "Work";
                $col['phone'] = $item;
                $arrPhone[] = $col;
            }
            foreach($request->all() as $keyR => $item){
                if(!in_array($keyR, ['_token', "id", "tag" ,"f", "property", "opp", "opp_id", "company_id", "phone_types", 'address', "title", "full_address", "country", "postal_code",
                "province", "city", "subdistrict", "property", "opportunity_id", "op_remove"])){
                    $_key = $keyR;
                    if($keyR == "sku"){
                        $_key = "name";
                    } elseif($keyR == "jabatan"){
                        $_key = "position";
                    } elseif($keyR == "alamat"){
                        $_key = "address";
                    } elseif($keyR == "province"){
                        $_key = "prov_id";
                    } elseif($keyR == "city"){
                        $_key = "city_id";
                    } elseif($keyR == "company"){
                        $_key = "comp_id";
                    } elseif($keyR == "tipe"){
                        $_key = "type";
                    } elseif($keyR == "agama"){
                        $_key = "religion_id";
                    } elseif($keyR == "birth_date"){
                        $_key = "birth_date";
                        $item = $bdate;
                    } elseif($keyR == "phone"){
                        $_key = "phones";
                        $item = $arrPhone;
                    } elseif($keyR == "email"){
                        $_key = "emails";
                    }
                    if(!empty($request->id)){
                        $this->checkUpdate($_key, $con->id, $con[$_key], is_array($item) ? json_encode($item) : $item, $type);
                    }
                }
            }
            $con->name = $request->sku;
            $con->tags = $request->tag;
            $con->position = $request->jabatan;
            // $con->email = $request->email;
            $con->address = $request->alamat;
            $con->prov_id = $request->province;
            $con->city_id = $request->city;
            $con->comp_id = $request->company;
            $con->company_id = Session::get("company_id");
            $con->created_by = Auth::id();
            $con->type = $request->tipe;
            // $con->no_telp = $request->no_telp;
            $con->kode_pos = $request->kode_pos;
            $con->role = $request->role;
            $con->religion_id = $request->agama;
            $con->birth_date = $bdate;

            $con->no_telp = count($arrPhone) > 0 ? $arrPhone[0]['phone'] : null;
            $con->email = !empty($request->email) ? $request->email[0] : null;
            $con->phones = $arrPhone;
            $con->emails = $request->email;

            $con->save();

            $this->add_address($request, $type, $con->id);

            $iId = $con->id;
            $iCom = $con->company_id;

            $op_remove = $request->op_remove ?? [];
            foreach($op_remove as $item){
                $el = Marketing_leads::find($item);
                $contact = json_decode($el->contacts ?? "[]", true);
                if(in_array($con->id, $contact)){
                    $key = array_search((string) $con->id, $contact);
                    unset($contact[$key]);
                    $c2 = array_values($contact);
                    $el->contacts = json_encode($c2);
                    $el->save();
                }
            }

            $opportunity = $request->opportunity_id ?? [];
            foreach($opportunity as $item){
                $el = Marketing_leads::find($item);
                $contact = json_decode($el->contacts ?? "[]", true);
                if(!in_array($con->id, $contact)){
                    $contact[] = (string) $con->id;
                    $el->contacts = json_encode($contact);
                    $el->save();
                }
            }

            $r = route("crm.list.index")."?t=kontak";
        } elseif($type == "company"){
            $harga = str_replace(".", "", $request->budget);
            $_harga = str_replace(",", ".", $harga);
            $com = Marketing_clients::findOrNew($request->id);
            $phone = $request->phone ?? [];
            $ptype = $request->phone_types;

            $arrPhone = [];

            foreach($phone as $i => $item){
                $col = [];
                $col['type'] = $ptype[$i] ?? "Work";
                $col['phone'] = $item;
                $arrPhone[] = $col;
            }

            foreach($request->all() as $keyR => $item){
                if(!in_array($keyR, ['_token', "id", "contact_id" ,"f", "var", "opp", "opp_id", "company_id", "phone_types", 'address', "title", "full_address", "country", "postal_code",
                    "province", "city", "subdistrict", "property", "opportunity_id", "op_remove"])){
                    $_key = $keyR;
                    if($keyR == "no_telp"){
                        $_key = "pic_number";
                    } elseif($keyR == "budget"){
                        $_key = "position";
                    } elseif($keyR == "alamat"){
                        $_key = "budget";
                        $item = $_harga;
                    } elseif($keyR == "province"){
                        $_key = "prov_id";
                    } elseif($keyR == "city"){
                        $_key = "city_id";
                    } elseif($keyR == "company"){
                        $_key = "comp_id";
                    } elseif($keyR == "tipe"){
                        $_key = "type";
                    } elseif($keyR == "phone"){
                        $_key = "phones";
                        $item = $arrPhone;
                    } elseif($keyR == "email"){
                        $_key = "emails";
                    }
                    if(!empty($request->id)){
                        $this->checkUpdate($_key, $com->id, $com[$_key], is_array($item) ? json_encode($item) : $item, $type);
                    }
                }
            }

            $com->company_name = $request->company_name;
            $com->tags = $request->tag;
            $com->pic_number = count($arrPhone) > 0 ? $arrPhone[0]['phone'] : null;
            $com->email = !empty($request->email) ? $request->email[0] : null;
            $com->type = $request->tipe ?? "Lead";
            $com->jumlah_karyawan = $request->jumlah_karyawan;
            $com->budget = $_harga;
            $com->address = $request->address[0] ?? null;
            $com->prov_id = $request->province;
            $com->city_id = $request->city;
            $com->kode_pos = $request->kode_pos;
            $com->latitude = $request->latitude;
            $com->longitude = $request->longitude;
            $com->radius = $request->radius;
            $com->phones = $arrPhone;
            $com->category = $request->jenis_bisnis;
            $com->emails = $request->email;
            $com->company_id = Session::get("company_id");
            $com->created_by = Auth::id();

            $com->save();

            $this->add_address($request, $type, $com->id);

            $iId = $com->id;
            $iCom = $com->company_id;

            $contacts = $request->contact_id ?? [];
            foreach($contacts as $item){
                $con = Kjk_crm_leads_contact::find($item);
                $con->comp_id = $com->id;
                $con->save();
            }

            $op_remove = $request->op_remove ?? [];
            Marketing_leads::whereIn("id", $op_remove)
                ->update([
                    "id_client" => null
                ]);

            $opportunity = $request->opportunity_id ?? [];
            Marketing_leads::whereIn("id", $opportunity)
                ->update([
                    "id_client" => $com->id
                ]);
            $r = route("crm.list.index")."?v=perusahaan";
        }

        $property = $request->property ?? [];
        foreach($property as $idPro => $proVal){
            if(!empty($proVal)){
                $prop = Kjk_crm_property_value::firstOrCreate([
                    "property_id" => $idPro,
                    "type" => $type,
                    "target_id" => $iId,
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

        $tags = $request->tag ?? "[]";

        foreach(json_decode($tags, true) as $tag){
            $t = Kjk_crm_tag::firstOrNew(['label' => $tag['value'], "category" => "contact"]);
            $t->label = $tag['value'];
            $t->category = "contact";
            $t->company_id = Session::get('company_id');
            $t->save();
        }

        $f = $request->f ?? null;

        if(!empty($f)){
            if($f == "lead"){
                return redirect()->back()->with(["leads_id" => $request->id_lead]);
            } elseif($f == "product"){
                return redirect()->back()->with(["list_id" => $request->id_lead]);
            }
            $_f = $f == "kontak" ? "contacts" : "company";
            return redirect()->to($r)->with(['list_type' => $_f, "list_id" => $request->id_list]);
        }

        return redirect()->to($r)->with(['list_type' => ($type == "contact" ? "contacts" : $type), "list_id" => $iId]);
    }

    public function import_kode_pos($kodepos){
        $file = file_get_contents("https://kodepos.vercel.app/search/?q=$kodepos");
        dd($file);
        $f = collect(json_decode($file, true));

        $find = $f->where("postal", $kodepos)->first();

        $data = [
            "success" => empty($find) ? false : true,
            "data" => $find ?? []
        ];

        return $data;
    }

    function import($type, Request $request){
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		$file = $request->file('file');

        // membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();

        $r = route("crm.list.index")."?t=". ($type == "company" ? "perusahaan" : "kontak");


		if($file->move("import",$nama_file)){
            try {
                if($type == "company"){
                    // import data
                    Excel::import(new MarketingClientsImport, "import/".$nama_file);
                } else {
                    Excel::import(new KjkCrmLeadContacts, "import/".$nama_file);
                }
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();

                return redirect()->to($r)->with([
                    "toast" => [
                        "message" => $failures[0]->errors()[0]." on row ".($failures[0]->row() - 1),
                        "bg" => "bg-danger"
                    ]
                ]);
            }

            // alihkan halaman kembali
            return redirect()->to($r)->with([
                "toast" => [
                    "message" => "Import $type successfully",
                    "bg" => "bg-success"
                ]
            ]);
        } else {
            return redirect()->to($r)->with([
                "toast" => [
                    "message" => "Upload file failed",
                    "bg" => "bg-danger"
                ]
            ]);
        }
    }

    function add_address($request, $type, $id){
        $address = $request->address ?? [];
        $title = $request->title;
        $full_address = $request->full_address;
        $postal_code = $request->postal_code;
        $country = $request->country;
        $province = $request->province;
        $city = $request->city;
        $subdistrict = $request->subdistrict;
        Kjk_crm_address::where("type", $type)
            ->where('target_id', $id)
            ->forceDelete();
        foreach($address as $key => $addr){
            $add = new Kjk_crm_address();
            $add->type = $type;
            $add->target_id = $id;
            $add->address = $addr;
            $add->title = $title[$key];
            $add->full_address = $full_address[$key];
            $add->postal_code = $postal_code[$key];
            $add->country = $country[$key];
            $add->province = $province[$key];
            $add->city = $city[$key];
            $add->subdistrict = $subdistrict[$key];
            $add->company_id = Session::get('company_id');
            $add->created_by = Auth::id();
            $add->save();
        }
    }

    function add_notes($type, Request $request){
        $notes = new Kjk_list_note();
        $notes->list_id = $request->id;
        $notes->type = $type;
        $notes->descriptions = $request->notes;
        $notes->persons = json_encode($request->persons ?? []);
        $file = $request->file("attachment_notes");
        $notes->company_id = Session::get('company_id');
        $notes->created_by = Auth::id();
        if(!empty($file)){
            $dir = str_replace("/", "\\", public_path("media/attachments"));
            $_dir = str_replace("prototype\public_html", "public_html\kerjaku\assets", $dir);
            $tdir = str_replace("\\", "/", $_dir);
            $name = date("YmdHis")."-$type-".$notes->list_id."-".$file->getClientOriginalName();
            if($file->move($this->dir, $name)){
                $notes->file_name = $file->getClientOriginalName();
                $notes->file_address = "media/attachments/$name";
            }
        }

        $notes->save();

        $tp = $type == "kontak" ? "contacts" : "company";

        return redirect()->back()->with([
            'list_type' => $tp, "list_id" => $request->id,
            "toast" => [
                "message" => "Notes successfully uploaded",
                "bg" => "bg-success"
            ]
        ]);
    }

    function add_files($type, Request $request){
        $lFile = new Kjk_list_file();
        $lFile->type = $type;
        $lFile->list_id = $request->id;
        $file = $request->file("attachment_notes");
        $lFile->company_id = Session::get('company_id');
        $lFile->created_by = Auth::id();
        if(!empty($file)){
            $dir = str_replace("/", "\\", public_path("media/attachments"));
            $_dir = str_replace("prototype\public_html", "public_html\kerjaku\assets", $dir);
            $tdir = str_replace("\\", "/", $_dir);

            $name = date("YmdHis")."-$type-".$lFile->list_id."-".$file->getClientOriginalName();
            if($file->move($this->dir, $name)){
                $lFile->file_name = $file->getClientOriginalName();
                $lFile->file_address = "media/attachments/$name";
            }
        }

        $lFile->save();

        $tp = $type == "kontak" ? "contacts" : "company";

        return redirect()->back()->with([
            'list_type' => $tp, "list_id" => $request->id,
            "toast" => [
                "message" => "File successfully uploaded",
                "bg" => "bg-success"
            ]
        ]);
    }

    function view($type, $id, Request $request){
        $v = "";
        $delRoute = "";
        $history = [];
        $show_detail = true;
        if($type == "company"){
            $vType = $type;
            $detail = Marketing_clients::withoutGlobalScopes()->find($id);
            $detail->label = $detail->company_name;
            $v = "perusahaan";
            $form = route('crm.list.add', ['type' => "company"]);
            $delRoute = route('crm.list.delete', ["type" => "company", "id" => $detail->id]);
            $updateHist = Kjk_crm_lead_update_history::company()->where("lead_id", $detail->id)->get();
            foreach($updateHist as $item){
                $col = [];
                $col['type'] = "update_$item->target";
                $col['item'] = $item->toArray();
                $history[date("Y-m-d", strtotime($item->created_at))][date("H:i:s", strtotime($item->created_at))][] = $col;
            }
        } elseif($type == "contacts"){
            $vType = "contact";
            $detail = Kjk_crm_leads_contact::withoutGlobalScopes()->find($id);
            $detail->label = $detail->name;
            $v = "kontak";
            $form = route('crm.list.add', ['type' => "contact"]);
            $delRoute = route('crm.list.delete', ["type" => "contact", "id" => $detail->id]);
            $updateHist = Kjk_crm_lead_update_history::contact()->where("lead_id", $detail->id)->get();
            foreach($updateHist as $item){
                $col = [];
                $col['type'] = "update_$item->target";
                $col['item'] = $item->toArray();
                $history[date("Y-m-d", strtotime($item->created_at))][date("H:i:s", strtotime($item->created_at))][] = $col;
            }
        } elseif($type == "file"){
            $vType = "file";
            $v = $type;
            $detail = Marketing_lead_files::find($id);
            $form = route('crm.list.add', ['type' => "file"]);
            $delRoute = route('crm.list.delete', ["type" => "file", "id" => $detail->id]);
            $show_detail = false;
        }

        $province = Master_province::get();
        $city = Master_city::get();
        $company = Marketing_clients::where("company_id", Session::get('company_id'))->get();

        if(!empty($detail)){
            $col = [];
            $col['type'] = "create";
            $col['item'] = $detail->toArray();
            $history[date("Y-m-d", strtotime($detail->created_at))][date("H:i:s", strtotime($detail->created_at))][] = $col;
        }

        $listNotes = Kjk_list_note::where("list_id", $detail->id ?? null)->where("type", $v)->get();

        foreach($listNotes as $item){
            $col = [];
            $col['type'] = "notes";
            $col['item'] = $item->toArray();
            $history[date("Y-m-d", strtotime($item->created_at))][date("H:i:s", strtotime($item->created_at))][] = $col;
        }

        $leadFiles = Kjk_list_file::where("list_id", $detail->id ?? null)->where("type", $v)->get();

        foreach($leadFiles as $item){
            $col = [];
            $col['type'] = "files";
            $col['item'] = $item->toArray();
            $history[date("Y-m-d", strtotime($item->created_at))][date("H:i:s", strtotime($item->created_at))][] = $col;
        }

        $activity = [];

        $user_activity = User::where("company_id", Session::get('company_id'))->pluck("name", "id");

        $emp = Hrd_employee::where("company_id", Session::get("company_id"))
            ->get();

        krsort($history);
        foreach($history as $date => $item){
            krsort($item);
            foreach($item as $val){
                foreach($val as $ival){
                    $_detail = $ival['item'];
                    $_user = "";
                    if(isset($user_activity[$_detail['created_by']])){
                        $_user = $user_activity[$_detail['created_by']];
                    }
                    $col = $ival;
                    $col['uid'] = $_detail['created_by'];
                    $col['date'] = $date;
                    $col['user'] = $_user;
                    $activity[] = $col;
                }
            }
        }

        if($request->a){
            if($request->a == "contact"){
                return json_encode($emp->pluck("emp_name")->toArray());
            }
        }

        $vars = Master_variables_model::where("var_type", $vType)
            ->where("company_id", Session::get("company_id"))
            ->get();

        $othVal = Master_var_emp::whereIn("id_var", $vars->pluck("id"))
            ->where("id_emp", $detail->id)
            ->get()->pluck("values", "id_var");

        $f = $request->f ?? null;

        $properties = Kjk_crm_property::where("type", $type == "contacts" ? "contact" : $type)
            ->whereNull("table_column")
            ->where("company_id", Session::get('company_id'))
            ->whereNull("hide")
            ->get();

        $propVal = Kjk_crm_property_value::whereIn("property_id", $properties->pluck("id"))
            ->where("target_id", $detail->id)
            ->where("type", $type == "contacts" ? "contact" : $type)
            ->get();
        $prop = [];
        foreach($propVal as $item){
            $prop[$item->property_id] = $item;
        }

        $contacts = Kjk_crm_leads_contact::where("company_id", Session::get('company_id'))->pluck("name", "id");
        $clients = Marketing_clients::where("company_id", Session::get('company_id'))->pluck("company_name", "id");

        $address = Kjk_crm_address::where("type", $vType)
            ->where("target_id", $detail->id)
            ->get();

        $user_hris = User::where("company_id", Session::get('company_id'))
            ->whereNotNull("emp_id")
            ->where("role_access", "like", '%"hris"%')->orderBy('name')->pluck("name", "id");

        $comments = Kjk_list_comment::where("list_id", $detail->id ?? null)
            ->where("list_type", $type)
            ->whereNull('comment_id')
            ->get();
        $comment_content = [];
        foreach($comments as $item){
            $comment_content[$item->content_type][$item->content_id][] = $item->toArray();
        }

        $user_hris = User::hris()->where('company_id', Session::get("company_id"))->pluck("name", "id");

        if($type == "company"){
            $opportunity = Marketing_leads::where("id_client", $detail->id)->get();
            $pic = Kjk_crm_leads_contact::where("comp_id", $detail->id)->get();
            $view = view("_crm.list._detail", compact("detail", "show_detail", 'user_hris', "comment_content", "address", "v", "f", "province", "city", "form", 'activity', "company", "delRoute", "vars", "othVal", "opportunity", "pic", "properties", "prop", "contacts", "clients", "user_hris"))->render();
        } elseif($type == "contacts"){
            $opportunity = Marketing_leads::where("contacts", "like", "%\"$detail->id\"%")->get();
            $agama = Master_religion::get();
            $clients = Marketing_clients::where('company_id', Session::get("company_id"))->pluck("company_name", "id");
            $view = view("_crm.list._detail", compact("detail", "show_detail", 'user_hris', "comment_content", "address", "v", "f", "province", "agama", "city", "form", 'activity', "company", "delRoute", "vars", "othVal", "opportunity", "properties", "prop", "contacts", "clients", "user_hris"))->render();
        } else {
            $opportunity = Marketing_leads::where("id", $detail->id_lead)->get();
            $view = view("_crm.list._detail", compact("detail", "show_detail", 'user_hris', "f", "v", "form", "opportunity"))->render();
        }

        return json_encode([
            "view" => $view
        ]);

        // return view("_crm.list.view", compact("detail", "v", "province", "city", "form", 'activity', "company", "delRoute", "vars", "othVal"));
    }

    function delete_detail($view, $type, $id){
        $list_id = null;
        if($type == "notes"){
            $f = Kjk_list_note::find($id);
        } else {
            $f = Kjk_list_file::find($id);
        }

        if(empty($f)){
            return redirect()->back()->with([
                // 'list_type' => $view, "list_id" => $list_id,
                "toast" => [
                    "message" => "Deleting $type fail",
                    "bg" => "bg-danger"
                ]
            ]);
        }

        $list_id = $f->list_id;
        $f->delete();

        return redirect()->back()->with([
            'list_type' => $view, "list_id" => $list_id,
            "toast" => [
                "message" => "$type successfully deleted",
                "bg" => "bg-success"
            ]
        ]);
    }

    function hierarchy($type, $id, Request $request){
        if($type == "company"){
            $parent = $request->parent;
            $data = [];
            if(empty($parent)){
                return view("_crm.list._hierarchy");
            } else {
                if($parent == "#"){
                    $comp = Marketing_clients::find($id);
                    if(empty($comp->parent)){
                        $data[] = array(
                            "id" => "$comp->id",
                            "text" => $comp->company_name,
                            // "icon" => "ki-outline ki-folder fs-1 text-dark",
                            "children" => $comp->childs->count() > 0 ? true : false,
                            "state" => [
                                "opened" => $comp->childs->count() > 0 ? true : false,
                                "selected" => $id == $comp->id ? true : false
                            ],
                            "type" => "root"
                        );
                    } else {
                        $comp_parent = $comp->parent;
                        while(!empty($comp_parent->parent)){
                            $comp_parent = $comp_parent->parent;
                        }

                        $data[] = array(
                            "id" => "$comp_parent->id",
                            "text" => $comp_parent->company_name,
                            // "icon" => "ki-outline ki-folder fs-1 text-dark",
                            "children" => $comp_parent->childs->count() > 0 ? true : false,
                            "state" => [
                                "opened" => $comp_parent->childs->count() > 0 ? true : false,
                                "selected" => $id == $comp_parent->id ? true : false
                            ],
                            "type" => "root"
                        );
                    }
                } else {
                    $comps = Marketing_clients::where("company_parent", $parent)->get();
                    foreach($comps as $item){
                        $data[] = array(
                            "id" => "$item->id",
                            "text" => $item->company_name,
                            // "icon" => "ki-outline ki-folder fs-1 text-dark",
                            "children" => count($item->childs) > 0 ? true : false,
                            "state" => [
                                "opened" => count($item->childs) > 0 ? true : false,
                                "selected" => $id == $item->id ? true : false
                            ]
                        );
                    }
                }

                return $data;
            }
        }
    }

    function delete($type, $id){
        if($type == "file"){
            $lFile = Marketing_lead_files::find($id);
            $t = "file";
            $lFile->delete();
        } elseif($type == "contact"){
            $con = Kjk_crm_leads_contact::find($id);
            $t = "kontak";
            $con->delete();
        } elseif($type == "company"){
            $con = Marketing_clients::find($id);
            $t = "perusahaan";
            $con->delete();
        }

        return redirect()->to(route("crm.list.index")."?t=$t");
    }

    function archive($type, $id){
        if($type == "file"){
            $detail = Marketing_lead_files::find($id);
            $t = "file";
        } elseif($type == "contact"){
            $detail = Kjk_crm_leads_contact::find($id);
            $t = "kontak";
        } elseif($type == "company"){
            $detail = Marketing_clients::find($id);
            $t = "perusahaan";
        }

        $detail->archive_at = date("Y-m-d H:i:s");
        $detail->archive_by = Auth::id();
        $detail->save();

        return redirect()->to(route("crm.list.index")."?t=$t");
    }

    function crmCommentAdd($view, Request $request){
        $comment = new Kjk_list_comment();
        $comment->list_type = $view;
        $comment->list_id = $request->list_id;
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
            $name = date("YmdHis")."-comment-".$comment->list_id."-".$file->getClientOriginalName();
            if($file->move($this->dir, $name)){
                $comment->file_name = $file->getClientOriginalName();
                $comment->file_address = "media/attachments/$name";
            }
        }

        $comment->save();

        $r = route("crm.list.index")."?t=" . ($view == "company" ? "perusahaan" : "kontak");

        return redirect()->to($r)->with(['list_type' => $view, "list_id" => $comment->list_id,]);
    }

    function crmCommentView($view, $type, $id, Request $request){
        $coid = $request->comment;
        $comment = Kjk_list_comment::where('content_type', $type)
            ->where("list_type", $view)
            ->whereNull("comment_id")
            ->where("content_id", $id)
            ->orderBy("created_at")
            ->get();
        if(!empty($coid)){
            $comment = Kjk_list_comment::where('content_type', $type)
                ->where("list_type", $view)
                ->where("comment_id", $coid)
                ->where("content_id", $id)
                ->orderBy("created_at")
                ->get();
        }

        $user = User::whereIn("id", $comment->pluck("user_id"))->get()->pluck("name", "id");

        $comments = Kjk_list_comment::whereIn('comment_id', $comment->pluck("id"))
            ->where("list_type", $view)
            ->orderBy("created_at")
            ->get();
        $child = [];
        foreach($comments as $item){
            $child[$item->comment_id][] = $item;
        }

        $v = view('_crm.list.comment', compact('comment', 'type', 'id', 'user', "child", "view"))->render();

        return json_encode([
            "view" => $v
        ]);
    }

    function crmCommentDelete($view,$id){
        $comment = Kjk_list_comment::find($id);
        $id_list = $comment->list_id;
        $view = $comment->list_type;
        $comment->delete();

        $r = route("crm.list.index")."?t=" . ($view == "company" ? "perusahaan" : "kontak");

        return redirect()->to($r)->with(['list_type' => $view, "list_id" => $id_list]);
    }
}
