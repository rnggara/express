<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personel_blood_type;
use App\Models\Personel_employee_status;
use App\Models\Personel_identity;
use App\Models\Personel_license;
use App\Models\Personel_major;
use App\Models\Master_religion;
use App\Models\Master_educations;
use App\Models\Master_marital_status;
use App\Models\Master_language;
use App\Models\Master_gender;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class KjkPreferencePersonel extends Controller
{
    private $sesId, $authId;

    public function __construct() {
        $this->sesId = Session::get("company_id");
        $this->authId = Auth::id();
    }

    function generateRecordId(Request $request){

    }

    function validateRequest(Request $request, $validate){
        $validator = Validator::make($request->all(), $validate);
        return $validator;
    }

    function employee_status(){
        $data = Personel_employee_status::where('company_id', Session::get("company_id"))->get();
        return view("_crm.preferences.personel.employee_status.index", compact("data"));
    }

    function employee_status_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){

            $validator = $this->validateRequest($request, [
                "description" => "required"
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                ]);
            } else {
                $data = Personel_employee_status::findOrNew($request->id);
                $data->label = $request->description;
                $data->end_date = $request->end_date ?? 0;
                if(empty($request->id)){
                    $data->company_id = Session::get('company_id');
                    $data->created_by = Auth::id();
                }

                if(!empty($request->id)){
                    $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Employee Status has been added",
                    "bg" => "bg-success"
                ];
            }
        } elseif($submit == "delete"){
            $data = Personel_employee_status::find($request->id);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Employee Status has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function identity(){
        $data = Personel_identity::where('company_id', Session::get("company_id"))->get();
        return view("_crm.preferences.personel.identity.index", compact('data'));
    }

    function identity_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){

            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "description" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                ]);
            } else {
                $conflict = Personel_identity::where("record_id", $request->record_id)
                    ->where("company_id", Session::get('company_id'))
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "record_id" => "record id sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                    ])->withInput($request->all());
                }

                $data = Personel_identity::findOrNew($request->id);
                $data->label = $request->description;
                $data->record_id = $request->record_id;
                if(empty($request->id)){
                    $data->company_id = Session::get('company_id');
                    $data->created_by = Auth::id();
                }

                if(!empty($request->id)){
                    $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Card/Identity has been added",
                    "bg" => "bg-success"
                ];
            }
        } elseif($submit == "delete"){
            $data = Personel_identity::find($request->id);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Card/Identity has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function education(){
        $data = Master_educations::where(function($q){
            $q->where('company_id', Session::get("company_id"));
            $q->orWhereNull('company_id');
        })->get();
        return view("_crm.preferences.personel.education.index", compact('data'));
    }

    function education_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){

            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "description" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                ]);
            } else {
                $conflict = Master_educations::where("record_id", $request->record_id)
                    ->where(function($q){
                        $q->where('company_id', Session::get("company_id"));
                        $q->orWhereNull('company_id');
                    })
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "record_id" => "record id sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                    ])->withInput($request->all());
                }

                $data = Master_educations::findOrNew($request->id);
                $data->name = $request->description;
                $data->record_id = $request->record_id;
                if(empty($request->id)){
                    $data->company_id = Session::get('company_id');
                    $data->created_by = Auth::id();
                }

                if(!empty($request->id)){
                    $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Education has been added",
                    "bg" => "bg-success"
                ];
            }
        } elseif($submit == "delete"){
            $data = Master_educations::find($request->id);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Education has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function major(){
        $data = Personel_major::where('company_id', Session::get("company_id"))->get();
        return view("_crm.preferences.personel.major.index", compact('data'));
    }

    function major_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){

            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "description" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                ]);
            } else {
                $conflict = Personel_major::where("record_id", $request->record_id)
                    ->where("company_id", Session::get('company_id'))
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "record_id" => "record id sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                    ])->withInput($request->all());
                }

                $data = Personel_major::findOrNew($request->id);
                $data->label = $request->description;
                $data->record_id = $request->record_id;
                if(empty($request->id)){
                    $data->company_id = Session::get('company_id');
                    $data->created_by = Auth::id();
                }

                if(!empty($request->id)){
                    $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Major has been added",
                    "bg" => "bg-success"
                ];
            }
        } elseif($submit == "delete"){
            $data = Personel_major::find($request->id);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Major has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }
    function language(){
        $data = Master_language::where(function($q){
            $q->where('company_id', Session::get("company_id"));
            $q->orWhereNull('company_id');
        })->get();
        return view("_crm.preferences.personel.language.index", compact('data'));
    }

    function language_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){

            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "description" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                ]);
            } else {
                $conflict = Master_language::where("record_id", $request->record_id)
                    ->where(function($q){
                        $q->where('company_id', Session::get("company_id"));
                        $q->orWhereNull('company_id');
                    })
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "record_id" => "record id sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                    ])->withInput($request->all());
                }

                $data = Master_language::findOrNew($request->id);
                $data->name = $request->description;
                $data->record_id = $request->record_id;
                if(empty($request->id)){
                    $data->company_id = Session::get('company_id');
                    $data->created_by = Auth::id();
                }

                if(!empty($request->id)){
                    $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Language has been added",
                    "bg" => "bg-success"
                ];
            }
        } elseif($submit == "delete"){
            $data = Master_language::find($request->id);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Language has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function religion(){
        $data = Master_religion::where(function($q){
            $q->where('company_id', Session::get("company_id"));
            $q->orWhereNull('company_id');
        })->get();
        return view("_crm.preferences.personel.religion.index", compact('data'));
    }

    function religion_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){

            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "description" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                ]);
            } else {
                $conflict = Master_religion::where("record_id", $request->record_id)
                    ->where(function($q){
                        $q->where('company_id', Session::get("company_id"));
                        $q->orWhereNull('company_id');
                    })
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "record_id" => "record id sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                    ])->withInput($request->all());
                }

                $data = Master_religion::findOrNew($request->id);
                $data->name = $request->description;
                $data->record_id = $request->record_id;
                if(empty($request->id)){
                    $data->company_id = Session::get('company_id');
                    $data->created_by = Auth::id();
                }

                if(!empty($request->id)){
                    $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Religion has been added",
                    "bg" => "bg-success"
                ];
            }
        } elseif($submit == "delete"){
            $data = Master_religion::find($request->id);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Religion has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function marital_status(){
        $data = Master_marital_status::where(function($q){
            $q->where('company_id', Session::get("company_id"));
            $q->orWhereNull('company_id');
        })->get();
        return view("_crm.preferences.personel.marital_status.index", compact('data'));
    }

    function marital_status_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){

            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "description" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                ]);
            } else {
                $conflict = Master_marital_status::where("record_id", $request->record_id)
                    ->where(function($q){
                        $q->where('company_id', Session::get("company_id"));
                        $q->orWhereNull('company_id');
                    })
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "record_id" => "record id sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                    ])->withInput($request->all());
                }

                $data = Master_marital_status::findOrNew($request->id);
                $data->name = $request->description;
                $data->record_id = $request->record_id;
                if(empty($request->id)){
                    $data->company_id = Session::get('company_id');
                    $data->created_by = Auth::id();
                }

                if(!empty($request->id)){
                    $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Marital Status has been added",
                    "bg" => "bg-success"
                ];
            }
        } elseif($submit == "delete"){
            $data = Master_marital_status::find($request->id);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Marital Status has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function licenses(){
        $data = Personel_license::where('company_id', Session::get("company_id"))->get();
        return view("_crm.preferences.personel.licenses.index", compact('data'));
    }

    function licenses_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){

            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "description" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                ]);
            } else {
                $conflict = Personel_license::where("record_id", $request->record_id)
                    ->where("company_id", Session::get('company_id'))
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "record_id" => "record id sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                    ])->withInput($request->all());
                }

                $data = Personel_license::findOrNew($request->id);
                $data->label = $request->description;
                $data->record_id = $request->record_id;
                if(empty($request->id)){
                    $data->company_id = Session::get('company_id');
                    $data->created_by = Auth::id();
                }

                if(!empty($request->id)){
                    $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "License has been added",
                    "bg" => "bg-success"
                ];
            }
        } elseif($submit == "delete"){
            $data = Personel_license::find($request->id);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "License has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function blood_type(){
        $data = Personel_blood_type::where('company_id', Session::get("company_id"))->get();
        return view("_crm.preferences.personel.blood_type.index", compact('data'));
    }

    function blood_type_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){

            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "description" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                ]);
            } else {
                $conflict = Personel_blood_type::where("record_id", $request->record_id)
                    ->where("company_id", Session::get('company_id'))
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "record_id" => "record id sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                    ])->withInput($request->all());
                }

                $data = Personel_blood_type::findOrNew($request->id);
                $data->label = $request->description;
                $data->record_id = $request->record_id;
                if(empty($request->id)){
                    $data->company_id = Session::get('company_id');
                    $data->created_by = Auth::id();
                }

                if(!empty($request->id)){
                    $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Blood Type has been added",
                    "bg" => "bg-success"
                ];
            }
        } elseif($submit == "delete"){
            $data = Personel_blood_type::find($request->id);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Blood Type has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function gender(){
        $data = Master_gender::where(function($q){
            $q->where('company_id', Session::get("company_id"));
            $q->orWhereNull('company_id');
        })->get();
        return view("_crm.preferences.personel.gender.index", compact('data'));
    }

    function gender_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){

            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "description" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->with([
                    "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                ]);
            } else {
                $conflict = Master_gender::where("record_id", $request->record_id)
                    ->where(function($q){
                        $q->where('company_id', Session::get("company_id"));
                        $q->orWhereNull('company_id');
                    })
                    ->first();
                if(!empty($conflict)){
                    return redirect()->back()->withErrors([
                        "record_id" => "record id sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                    ])->withInput($request->all());
                }

                $data = Master_gender::findOrNew($request->id);
                $data->name = $request->description;
                $data->record_id = $request->record_id;
                if(empty($request->id)){
                    $data->company_id = Session::get('company_id');
                    $data->created_by = Auth::id();
                }

                if(!empty($request->id)){
                    $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Gender has been added",
                    "bg" => "bg-success"
                ];
            }
        } elseif($submit == "delete"){
            $data = Master_gender::find($request->id);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Gender has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function custom_properties(Request $request){
        $type = "personel";
        $detail = [];
        if(!empty($id)){
            if(empty($type)){
                $detail = \App\Models\Kjk_lead_layout::find($id);
            }
        }

        $props = \App\Models\Kjk_crm_property::where(function($q){
            $q->where("company_id", Session::get("company_id"));
            $q->orWhereNotNull("table_column");
        })
            ->where("type", $type ?? "opportunity")
            ->where(function($q) use($detail){
                if(!empty($detail)){
                    $q->whereNull("layout_id");
                    $q->orWhere("layout_id", $detail->id);
                }
            })
            ->get();

        $properties = \Illuminate\Support\Facades\Config::get("constants.CRM_PROPERTIES_TYPE");

        $users = \App\Models\User::hris()->where("company_id", Session::get("company_id"))->pluck("name", "id");

        return view("_crm.preferences.personel.custom_properties.index", compact("type", "detail", "properties", "props", "users"));
    }

}
