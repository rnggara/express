<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\ConfigCompany;
use App\Models\Asset_wh;
use App\Models\Kjk_comp_job_level;
use App\Models\Kjk_comp_job_grade;
use App\Models\Kjk_comp_departement;
use App\Models\Master_industry as Kjk_comp_lob;
use App\Models\User as Pic;
use App\Models\Kjk_comp_position;
use App\Models\Master_industry;
use App\Models\Kjk_comp_resign;
use App\Models\Kjk_comp_offence;
use App\Models\Hrd_employee;
use App\Models\Kjk_comp_asset;
use App\Models\Kjk_comp_bank;
use App\Models\Kjk_comp_bank_type;
use App\Models\Kjk_comp_class;
use App\Models\Kjk_comp_country;
use App\Models\Kjk_comp_tax_status;

class KjkPreferenceCompany extends Controller
{

    private $dir, $uploadDir;

    public function __construct() {
        $this->dir = "_crm.preferences.company";
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", Config::get("constants.ASSET_DIR"), $_dir);
        $this->uploadDir = str_replace("\\", "/", $dir);
    }

    function validateRequest(Request $request, $validate){
        $validator = Validator::make($request->all(), $validate);
        return $validator;
    }

    function company_list_index(){

        $user = Pic::where("id_batch", Auth::user()->id_batch)->get();

        $data = ConfigCompany::where("id", Auth::user()->company_id)
            ->orWhere("id_parent", Auth::user()->company_id)
            ->get();

        return view("$this->dir.company_list.index", compact('data'));
    }

    function company_list_detail($id){
        $decodeId = base64_decode($id);

        $company = ConfigCompany::find($decodeId);

        $tp = [
            "company-detail" => [
                "label" => "Company Detail",
                "desc" => "",
                "url" => true,
            ],
            "line-of-business" => [
                "label" => "Line of Business",
                "desc" => "",
                "url" => true,
            ],
            "class" => [
                "label" => "Class",
                "desc" => "",
                "url" => true,
            ],
            "location" => [
                "label" => "Location",
                "desc" => "",
                "url" => true,
            ],
            "country" => [
                "label" => "Country",
                "desc" => "",
                "url" => true,
            ],
            "city" => [
                "label" => "City",
                "desc" => "",
                "url" => false,
            ],
            "asset" => [
                "label" => "Asset",
                "desc" => "",
                "url" => true,
            ],
            "offence" => [
                "label" => "Offence",
                "desc" => "",
                "url" => true,
            ],
            "resign" => [
                "label" => "Resign",
                "desc" => "",
                "url" => true,
            ],
            "tax_status" => [
                "label" => "Tax Status",
                "desc" => "",
                "url" => true,
            ],
            "bank" => [
                "label" => "Bank",
                "desc" => "",
                "url" => true,
            ],
        ];

        return view("$this->dir.company_list.detail", compact('company', 'tp'));
    }

    function company_list_structure($id){
        $decodeId = base64_decode($id);

        $company = ConfigCompany::find($decodeId);

        $tp = [
            "job-level" => [
                "label" => "Job Level",
                "desc" => "Tingkatan pekerjaan yang mengacu pada hierarki dalam sebuah organisasi atau perusahaan dan menunjukkan posisi seseorang dalam struktur yang ada.",
                "url" => route("crm.pref.company.job_level.index", $id),
            ],
            "job-grade" => [
                "label" => "Job Grade",
                "desc" => "Sistem klasifikasi untuk mengelompokkan pekerjaan berdasarkan tingkat tanggung jawab, keterampilan, kompleksitas, dan pengalaman.",
                "url" => route("crm.pref.company.job_grade.index", $id),
            ],
            "departement" => [
                "label" => "Departement",
                "desc" => "Unit organisasional dalam suatu perusahaan yang memiliki tanggung jawab spesifik dalam menjalankan fungsi-fungsi tertentu.",
                "url" => route("crm.pref.company.departement.index", $id),
            ],
            "position" => [
                "label" => "Position",
                "desc" => "Peran atau jabatan spesifik yang dipegang oleh seseorang dalam suatu organisasi atau perusahaan.",
                "url" => route("crm.pref.company.position.index", $id),
            ],
        ];

        return view("$this->dir.company_list.structure", compact('company', 'tp'));
    }

    function company_list_setting($type, $id){
        $decodeId = base64_decode($id);

        $company = ConfigCompany::find($decodeId);

        $compact = [
            "company" => $company,
            "type" => $type
        ];

        if($type == "company-detail"){
            $compact['industry'] = Master_industry::hris($decodeId)->get();
        } elseif($type == "line-of-business"){
            $compact['data'] = Kjk_comp_lob::hris($decodeId)->get();
        } elseif($type == "location"){
            $compact['data'] = Asset_wh::office()->where('company_id', $decodeId)->get();
        } elseif($type == "resign"){
            $compact['data'] = Kjk_comp_resign::where("company_id", $decodeId)->get();
        } elseif($type == "offence"){
            $compact['data'] = Kjk_comp_offence::where("company_id", $decodeId)->get();
        } elseif($type == "asset"){
            $compact['data'] = Kjk_comp_asset::where("company_id", $decodeId)->get();
        } elseif($type == "class"){
            $compact['data'] = Kjk_comp_class::where("company_id", $decodeId)->get();
        } elseif($type == "country"){
            $compact['data'] = Kjk_comp_country::get();
        } elseif($type == "bank"){
            $compact['data'] = Kjk_comp_bank::get();
            $compact['bank_type'] = Kjk_comp_bank_type::pluck("name", "id");
        } elseif($type == "tax_status"){
            $compact['data'] = Kjk_comp_tax_status::get();
        }

        // $tp = [
        //     "company_detail" => [
        //         "label" => "Company Detail",
        //         "desc" => "",
        //         "url" =>
        //     ]
        // ]

        return view("$this->dir.company_list._$type", $compact);
    }

    function company_list_post(Request $request){
        $submit = $request->submit;
        if($submit == "store"){
            $validator = $this->validateRequest($request, [
                "company_name" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)
                    ->with([
                        "modal" => empty($request->id) ? "modal_add" : "modal_edit_$request->id"
                    ])->withInput($request->all());
            } else {
                $data = ConfigCompany::findOrNew($request->id);
                $data->company_name = $request->company_name;
                $data->email = $request->email;
                $data->phone = $request->phone;
                $data->address = $request->address;
                $data->id_parent = $request->id_parent ?? null;
                if(empty($request->id)){
                    $data->created_by = Auth::id();
                }

                if(!empty($request->id)){
                    // $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Company has been added",
                    "bg" => "bg-success"
                ];
            }
        } else {
            $data = ConfigCompany::find($request->id);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Company has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function company_list_update_detail($type, $id, Request $request){
        $submit = $request->submit;
        $comp_id = base64_decode($id);
        if($type == "line-of-business"){
            if($submit == "store"){
                $validator = $this->validateRequest($request, [
                    "record_id" => "required",
                    "description" => "required",
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)
                        ->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                } else {
                    $conflict = Kjk_comp_lob::where("record_id", $request->record_id)
                        ->where("id", "!=", $request->id_detail ?? "")
                        ->hris($comp_id)
                        ->first();
                    if(!empty($conflict)){
                        return redirect()->back()->withErrors([
                            "record_id" => "record id sudah dipakai"
                        ])->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                    }
                    $data = Kjk_comp_lob::hris($comp_id)->findOrNew($request->id_detail);
                    $data->record_id = $request->record_id;
                    $data->name = $request->description;
                    if(empty($request->id_detail)){
                        $data->created_by = Auth::id();
                        $data->company_id = $comp_id;
                    }


                    if(!empty($request->id_detail)){
                        // $data->status = $request->status ?? 0;
                    }
                    $data->save();

                    $toast = [
                        "message" => empty($request->id_detail) ? "Line of Business has been added" : "Line of Business has been updated",
                        "bg" => "bg-success"
                    ];
                }
            } else {

                $data = Kjk_comp_lob::hris($comp_id)->find($request->id_detail);

                if(!empty($data)) $data->delete();

                // additional for later
                $toast = [
                    "message" => "Line of Business has been deleted",
                    "bg" => "bg-danger"
                ];
            }
        } elseif($type == "location"){
            if($submit == "store"){
                $validator = $this->validateRequest($request, [
                    "location_name" => "required",
                    "address" => "required",
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)
                        ->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                } else {
                    $data = Asset_wh::findOrNew($request->id_detail);
                    $data->name = $request->location_name;
                    $data->address = $request->address;
                    $data->longitude2 = $request->radius;
                    $data->status = $request->status ?? 0;
                    $data->longitude = $request->longitude;
                    $data->latitude = $request->latitude;
                    $data->office = 1;
                    if(empty($request->id_detail)){
                        $data->created_by = Auth::id();
                        $data->company_id = $comp_id;
                    }


                    if(!empty($request->id_detail)){
                        // $data->status = $request->status ?? 0;
                    }
                    $data->save();

                    $toast = [
                        "message" => empty($request->id_detail) ? "Location has been added" : "Location has been updated",
                        "bg" => "bg-success"
                    ];
                }
            } else {
                $data = Asset_wh::find($request->id_detail);

                // additional for later

                $data->delete();
                $toast = [
                    "message" => "Location has been deleted",
                    "bg" => "bg-danger"
                ];
            }
        } elseif($type == "company-detail"){
            $company = ConfigCompany::find($id);

            $company->company_name = $request->company_name;
            $company->website = $request->website;
            $company->npwp = $request->npwp;
            $company->phone = $request->phone;
            $company->email = $request->email;
            $company->industry = $request->industry;
            $company->zip_code = $request->zip_code;
            $company->country = $request->country;
            $company->province = $request->province;
            $company->city = $request->city;
            $company->bpjs_tk = $request->bpjs_tk;

            $image = $request->file("image");
            if(!empty($image)){
                $d = date("YmdHis");
                $newName = $d."_".$image->getClientOriginalName();
                if($image->move($this->uploadDir, $newName)){
                    $company->app_logo = "media/attachments/$newName";
                }
            }

            $signature = $request->file("signature");
            if(!empty($signature)){
                $d = date("YmdHis");
                $newName = $d."_".$signature->getClientOriginalName();
                if($signature->move($this->uploadDir, $newName)){
                    $company->signature = "media/attachments/$newName";
                }
            }

            $company->save();

            $toast = [
                "message" => "Company Detail change has been saved",
                "bg" => "bg-success"
            ];
        } elseif($type == "resign"){
            if($submit == "store"){
                $validator = $this->validateRequest($request, [
                    "record_id" => "required",
                    "description" => "required",
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)
                        ->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                } else {
                    $conflict = Kjk_comp_resign::where("record_id", $request->record_id)
                        ->where("id", "!=", $request->id_detail ?? "")
                        ->where("company_id", $comp_id)
                        ->first();
                    if(!empty($conflict)){
                        return redirect()->back()->withErrors([
                            "record_id" => "record id sudah dipakai"
                        ])->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                    }
                    $data = Kjk_comp_resign::findOrNew($request->id_detail);
                    $data->record_id = $request->record_id;
                    $data->name = $request->description;
                    if(empty($request->id_detail)){
                        $data->created_by = Auth::id();
                        $data->company_id = $comp_id;
                    }


                    if(!empty($request->id_detail)){
                        // $data->status = $request->status ?? 0;
                    }
                    $data->save();

                    $toast = [
                        "message" => empty($request->id_detail) ? "Resign has been added" : "Resign has been updated",
                        "bg" => "bg-success"
                    ];
                }
            } else {
                $data = Kjk_comp_resign::find($request->id_detail);

                // additional for later

                $data->delete();
                $toast = [
                    "message" => "Resign has been deleted",
                    "bg" => "bg-danger"
                ];
            }
        } elseif($type == "offence"){
            if($submit == "store"){
                $validator = $this->validateRequest($request, [
                    "record_id" => "required",
                    "description" => "required",
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)
                        ->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                } else {
                    $conflict = Kjk_comp_offence::where("record_id", $request->record_id)
                        ->where("id", "!=", $request->id_detail ?? "")
                        ->where("company_id", $comp_id)
                        ->first();
                    if(!empty($conflict)){
                        return redirect()->back()->withErrors([
                            "record_id" => "record id sudah dipakai"
                        ])->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                    }
                    $data = Kjk_comp_offence::findOrNew($request->id_detail);
                    $data->record_id = $request->record_id;
                    $data->name = $request->description;
                    if(empty($request->id_detail)){
                        $data->created_by = Auth::id();
                        $data->company_id = $comp_id;
                    }


                    if(!empty($request->id_detail)){
                        // $data->status = $request->status ?? 0;
                    }
                    $data->save();

                    $toast = [
                        "message" => empty($request->id_detail) ? "Offence has been added" : "Offence has been updated",
                        "bg" => "bg-success"
                    ];
                }
            } else {
                $data = Kjk_comp_offence::find($request->id_detail);

                // additional for later

                $data->delete();
                $toast = [
                    "message" => "Offence has been deleted",
                    "bg" => "bg-danger"
                ];
            }
        } elseif($type == "asset"){
            if($submit == "store"){
                $validator = $this->validateRequest($request, [
                    "record_id" => "required",
                    "description" => "required",
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)
                        ->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                } else {
                    $conflict = Kjk_comp_asset::where("record_id", $request->record_id)
                        ->where("id", "!=", $request->id_detail ?? "")
                        ->where("company_id", $comp_id)
                        ->first();
                    if(!empty($conflict)){
                        return redirect()->back()->withErrors([
                            "record_id" => "record id sudah dipakai"
                        ])->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                    }
                    $data = Kjk_comp_asset::findOrNew($request->id_detail);
                    $data->record_id = $request->record_id;
                    $data->name = $request->description;
                    if(empty($request->id_detail)){
                        $data->created_by = Auth::id();
                        $data->company_id = $comp_id;
                    }


                    if(!empty($request->id_detail)){
                        // $data->status = $request->status ?? 0;
                    }
                    $data->save();

                    $toast = [
                        "message" => empty($request->id_detail) ? "Asset has been added" : "Asset has been updated",
                        "bg" => "bg-success"
                    ];
                }
            } else {
                $data = Kjk_comp_asset::find($request->id_detail);

                // additional for later

                $data->delete();
                $toast = [
                    "message" => "Asset has been deleted",
                    "bg" => "bg-danger"
                ];
            }
        } elseif($type == "class"){
            if($submit == "store"){
                $validator = $this->validateRequest($request, [
                    "record_id" => "required",
                    "description" => "required",
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)
                        ->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                } else {
                    $conflict = Kjk_comp_class::where("record_id", $request->record_id)
                        ->where("id", "!=", $request->id_detail ?? "")
                        ->where("company_id", $comp_id)
                        ->first();
                    if(!empty($conflict)){
                        return redirect()->back()->withErrors([
                            "record_id" => "record id sudah dipakai"
                        ])->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                    }
                    $data = Kjk_comp_class::findOrNew($request->id_detail);
                    $data->record_id = $request->record_id;
                    $data->name = $request->description;
                    if(empty($request->id_detail)){
                        $data->created_by = Auth::id();
                        $data->company_id = $comp_id;
                    }


                    if(!empty($request->id_detail)){
                        // $data->status = $request->status ?? 0;
                    }
                    $data->save();

                    $toast = [
                        "message" => empty($request->id_detail) ? "Class has been added" : "Class has been updated",
                        "bg" => "bg-success"
                    ];
                }
            } else {
                $data = Kjk_comp_class::find($request->id_detail);

                // additional for later

                $data->delete();
                $toast = [
                    "message" => "Class has been deleted",
                    "bg" => "bg-danger"
                ];
            }
        } elseif($type == "country"){
            if($submit == "store"){
                $validator = $this->validateRequest($request, [
                    "iso3" => "required",
                    "name" => "required",
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)
                        ->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                } else {
                    $conflict = Kjk_comp_country::where("iso3", $request->iso3)
                        ->where("id", "!=", $request->id_detail ?? "")
                        ->first();
                    if(!empty($conflict)){
                        return redirect()->back()->withErrors([
                            "iso3" => "Record ID sudah dipakai",
                        ])->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                    }
                    $data = Kjk_comp_country::findOrNew($request->id_detail);
                    // $data->iso = $request->iso;
                    $data->iso3 = $request->iso3;
                    $data->name = $request->name;
                    $data->currency = $request->currency;
                    // $data->phonecode = $request->phonecode;
                    if(empty($request->id_detail)){
                        $data->company_id = $comp_id;
                    }


                    if(!empty($request->id_detail)){
                        // $data->status = $request->status ?? 0;
                    }
                    $data->save();

                    $toast = [
                        "message" => empty($request->id_detail) ? "Country has been added" : "Country has been updated",
                        "bg" => "bg-success"
                    ];
                }
            } else {
                $data = Kjk_comp_country::find($request->id_detail);

                // additional for later

                $data->delete();
                $toast = [
                    "message" => "Country has been deleted",
                    "bg" => "bg-danger"
                ];
            }
        } elseif($type == "bank"){
            if($submit == "store"){
                $validator = $this->validateRequest($request, [
                    "id" => "required",
                    "bank_name" => "required",
                    "bank_type" => "required",
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)
                        ->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                } else {
                    $conflict = Kjk_comp_bank::find($request->id_detail);
                    if(!empty($conflict)){
                        return redirect()->back()->withErrors([
                            "id" => "Bank ID sudah dipakai",
                        ])->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                    }
                    $data = Kjk_comp_bank::findOrNew($request->id_detail);
                    $data->bank_name = $request->bank_name;
                    $data->bank_type = $request->bank_type;
                    $data->address = $request->address;
                    if(empty($request->id_detail)){
                        $data->id = $request->id;
                        // $data->company_id = $comp_id;
                    }


                    if(!empty($request->id_detail)){
                        // $data->status = $request->status ?? 0;
                    }
                    $data->save();

                    $toast = [
                        "message" => empty($request->id_detail) ? "Bank has been added" : "Bank has been updated",
                        "bg" => "bg-success"
                    ];
                }
            } else {
                $data = Kjk_comp_bank::find($request->id_detail);

                // additional for later

                $data->delete();
                $toast = [
                    "message" => "Bank has been deleted",
                    "bg" => "bg-danger"
                ];
            }
        } elseif($type == "tax_status"){
            if($submit == "store"){
                $validator = $this->validateRequest($request, [
                    "record_id" => "required",
                    "description" => "required",
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)
                        ->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                } else {
                    $conflict = Kjk_comp_tax_status::where("code", $request->record_id)
                        ->where("id", "!=", $request->id_detail ?? "")
                        ->where("company_id", $comp_id)
                        ->first();
                    if(!empty($conflict)){
                        return redirect()->back()->withErrors([
                            "record_id" => "record id sudah dipakai"
                        ])->with([
                            "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                        ])->withInput($request->all());
                    }
                    $data = Kjk_comp_tax_status::findOrNew($request->id_detail);
                    $data->code = $request->record_id;
                    $data->name = $request->description;
                    if(empty($request->id_detail)){
                        $data->created_by = Auth::id();
                        $data->company_id = $comp_id;
                    }


                    if(!empty($request->id_detail)){
                        // $data->status = $request->status ?? 0;
                    }
                    $data->save();

                    $toast = [
                        "message" => empty($request->id_detail) ? "Tax Status has been added" : "Tax Status has been updated",
                        "bg" => "bg-success"
                    ];
                }
            } else {
                $data = Kjk_comp_tax_status::find($request->id_detail);

                // additional for later

                $data->delete();
                $toast = [
                    "message" => "Tax Status has been deleted",
                    "bg" => "bg-danger"
                ];
            }
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function job_level_index($id){
        $decodeId = base64_decode($id);
        $company = ConfigCompany::find($decodeId);
        $data = Kjk_comp_job_level::where('company_id', $decodeId)
            ->get();
        return view("$this->dir.job_level.index", compact("data", "company"));
    }

    function job_level_post($id, Request $request){
        $decodeId = base64_decode($id);
        $submit = $request->submit;
        if($submit == "store"){
            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "job_level" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)
                    ->with([
                        "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                    ])->withInput($request->all());
            } else {
                $conflict = Kjk_comp_job_level::where(function($q) use($request){
                        $q->where("record_id", $request->record_id);
                        $q->orWhere("name", $request->job_level);
                    })
                    ->where("id", "!=", $request->id_detail ?? "")
                    ->where("company_id", $decodeId)
                    ->first();
                if(!empty($conflict)){
                    $keyConflict = $conflict->record_id == $request->record_id ? "record_id" : "job_level";
                    return redirect()->back()->withErrors([
                        $keyConflict => str_replace("_", " ", $keyConflict)." sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                    ])->withInput($request->all());
                }

                $data = Kjk_comp_job_level::findOrNew($request->id_detail);
                $data->name = $request->job_level;
                $data->record_id = $request->record_id;
                if(empty($request->id_detail)){
                    $data->company_id = $decodeId;
                    $data->created_by = Auth::id();
                }

                $data->status = 1;

                if(!empty($request->id_detail)){
                    // $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Job Level has been added",
                    "bg" => "bg-success"
                ];
            }
        } else {
            $data = Kjk_comp_job_level::find($request->id_detail);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Job level has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function job_grade_index($id){
        $decodeId = base64_decode($id);
        $company = ConfigCompany::find($decodeId);
        $data = Kjk_comp_job_grade::where('company_id', $decodeId)
            ->get();
        return view("$this->dir.job_grade.index", compact("data", "company"));
    }

    function job_grade_post($id, Request $request){
        $decodeId = base64_decode($id);
        $submit = $request->submit;
        if($submit == "store"){
            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "description" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)
                    ->with([
                        "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                    ])->withInput($request->all());
            } else {
                $conflict = Kjk_comp_job_grade::where(function($q) use($request){
                        $q->where("record_id", $request->record_id);
                        $q->orWhere("name", $request->description);
                    })
                    ->where("id", "!=", $request->id_detail ?? "")
                    ->where("company_id", $decodeId)
                    ->first();
                if(!empty($conflict)){
                    $keyConflict = $conflict->record_id == $request->record_id ? "record_id" : "description";
                    return redirect()->back()->withErrors([
                        $keyConflict => str_replace("_", " ", $keyConflict)." sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                    ])->withInput($request->all());
                }

                $data = Kjk_comp_job_grade::findOrNew($request->id_detail);
                $data->name = $request->description;
                $data->record_id = $request->record_id;
                if(empty($request->id_detail)){
                    $data->company_id = $decodeId;
                    $data->created_by = Auth::id();
                }

                $data->status = 1;

                if(!empty($request->id_detail)){
                    // $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Job Grade has been added",
                    "bg" => "bg-success"
                ];
            }
        } else {
            $data = Kjk_comp_job_grade::find($request->id_detail);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Job Grade has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function departement_index($id, Request $request){
        $decodeId = base64_decode($id);
        $company = ConfigCompany::find($decodeId);
        $data = Kjk_comp_departement::where('company_id', $decodeId)
            ->orderBy("record_id")
            ->get();
        $pic = Pic::hris()->where('company_id', $decodeId)
            ->where("name", "!=", "")
            ->orderBy("name")
            ->get();

        if($request->a == "edit"){
            $item = $data->where("id", $request->id)->first();

            $view = view("$this->dir.departement._edit", compact('item', 'pic', 'company', 'data'))->render();

            return json_encode([
                "view" => $view
            ]);
        }
        return view("$this->dir.departement.index", compact("data", 'pic', "company"));
    }

    function departement_structure($id, Request $request){
        $decodeId = base64_decode($id);
        $company = ConfigCompany::find($decodeId);

        $depts = Kjk_comp_departement::where('company_id', $decodeId)->get();

        $maxLevel = $depts->max("sub_level");

        if($request->a == "tree"){
            $nodes = [];
            foreach($depts as $item){
                $col = [];
                $col['id'] = $item->id;
                $col['name'] = $item->name;
                if(!empty($item->parent_id)){
                    $col['pid'] = $item->parent_id;
                }
                $col['tags'] = ["subLevel".$item->sub_level];
                $nodes[] = $col;
            }

            return json_encode($nodes);
        }

        return view("$this->dir.departement.structure", compact("company", "maxLevel"));
    }

    function departement_post($id, Request $request){
        $decodeId = base64_decode($id);
        $submit = $request->submit;
        if($submit == "store"){
            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "departement" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)
                    ->with([
                        "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                    ])->withInput($request->all());
            } else {
                $conflict = Kjk_comp_departement::where(function($q) use($request){
                        $q->where("record_id", $request->record_id);
                        $q->orWhere("name", $request->departement);
                    })
                    ->where("id", "!=", $request->id_detail ?? "")
                    ->where("company_id", $decodeId)
                    ->first();
                if(!empty($conflict)){
                    $keyConflict = $conflict->record_id == $request->record_id ? "record_id" : "departement";
                    return redirect()->back()->withErrors([
                        $keyConflict => str_replace("_", " ", $keyConflict)." sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                    ])->withInput($request->all());
                }

                $data = Kjk_comp_departement::findOrNew($request->id_detail);
                $data->name = $request->departement;
                $data->record_id = $request->record_id;
                $data->parent_id = $request->parent_id;
                $data->sub_level = $request->sub_level;
                $data->pic = $request->pic;
                if(empty($request->id_detail)){
                    $data->company_id = $decodeId;
                    $data->created_by = Auth::id();
                }

                $data->status = 1;

                if(!empty($request->id_detail)){
                    // $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Departement has been ".(empty($request->id_detail) ? "added" : "updated"),
                    "bg" => "bg-success"
                ];
            }
        } else {
            $data = Kjk_comp_departement::find($request->id_detail);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Departement has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }

    function position_index($id){
        $decodeId = base64_decode($id);
        $company = ConfigCompany::find($decodeId);
        $data = Kjk_comp_position::where('company_id', $decodeId)
            ->orderBy("record_id")
            ->get();
        return view("$this->dir.position.index", compact("data", "company"));
    }

    function position_structure($id, Request $request){
        $decodeId = base64_decode($id);
        $company = ConfigCompany::find($decodeId);

        if($request->act == "load-chart"){
            $nodes = [];

            if($request->employee == "false"){
                $data = Kjk_comp_position::where('company_id', $decodeId)->get();

                foreach($data as $item){
                    $col = [];
                    $col['id'] = $item->id;
                    $col['name'] = $item->name;
                    if(!empty($item->parent_id)){
                        $col['pid'] = $item->parent_id;
                    }
                    $nodes[] = $col;
                }
            } else {
                $data = Kjk_comp_position::where('company_id', $decodeId)->get();

                $emp = Hrd_employee::whereIn("position_id", $data->pluck("id"))->get();
                $emp_pos = [];
                foreach($emp as $item){
                    $emp_pos[$item->position_id][] = $item;
                }

                foreach($data as $item){
                    $epos = $emp_pos[$item->id] ?? [];
                    if(count($epos) > 0){
                        if($item->user_count == 1){
                            $col = [];
                            $col['id'] = $item->id;
                            $col['name'] = $epos[0]->emp_name;
                            $col['subname'] = $item->name;
                            if(!empty($item->parent_id)){
                                $col['pid'] = $item->parent_id;
                            }

                            $image = $epos[0]->user->user_img ?? "";

                            $col['image'] = $image == "" ? "" : asset($image);
                            $nodes[] = $col;
                        } else {
                            foreach ($epos as $value) {
                                $col = [];
                                $col['id'] = $item->id."-".$value->id;
                                $col['name'] = $value->emp_name;
                                $col['subname'] = $item->name;
                                if(!empty($item->parent_id)){
                                    $col['pid'] = $item->parent_id;
                                }
                                $image = $value->user->user_img ?? "";

                                $col['image'] = $image == "" ? "" : asset($image);
                                $nodes[] = $col;
                            }
                        }
                    } else {
                        $col = [];
                        $col['id'] = $item->id;
                        $col['name'] = "-";
                        $col['subname'] = $item->name;
                        if(!empty($item->parent_id)){
                            $col['pid'] = $item->parent_id;
                        }
                        $nodes[] = $col;
                    }
                }
            }

            return json_encode([
                "nodes" => $nodes,
                "employee" => $request->employee == "false" ? false : true
            ]);
        }

        return view("$this->dir.position.structure", compact("company"));
    }

    function position_post($id, Request $request){
        $decodeId = base64_decode($id);
        $submit = $request->submit;
        if($submit == "store"){
            $validator = $this->validateRequest($request, [
                "record_id" => "required",
                "position" => "required",
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)
                    ->with([
                        "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                    ])->withInput($request->all());
            } else {
                $conflict = Kjk_comp_position::where(function($q) use($request){
                        $q->where("record_id", $request->record_id);
                        $q->orWhere("name", $request->position);
                    })
                    ->where("id", "!=", $request->id_detail ?? "")
                    ->where("company_id", $decodeId)
                    ->first();
                if(!empty($conflict)){
                    $keyConflict = $conflict->record_id == $request->record_id ? "record_id" : "position";
                    return redirect()->back()->withErrors([
                        $keyConflict => str_replace("_", " ", $keyConflict)." sudah dipakai"
                    ])->with([
                        "modal" => empty($request->id_detail) ? "modal_add" : "modal_edit_$request->id_detail"
                    ])->withInput($request->all());
                }

                $data = Kjk_comp_position::findOrNew($request->id_detail);
                $data->name = $request->position;
                $data->record_id = $request->record_id;
                $data->parent_id = $request->parent_id;
                $data->user_count = $request->user_count ?? 1;
                if(empty($request->id_detail)){
                    $data->company_id = $decodeId;
                    $data->created_by = Auth::id();
                }

                $data->status = 1;

                if(!empty($request->id_detail)){
                    // $data->status = $request->status ?? 0;
                }
                $data->save();

                $toast = [
                    "message" => "Position has been added",
                    "bg" => "bg-success"
                ];
            }
        } else {
            $data = Kjk_comp_position::find($request->id_detail);

            // additional for later

            $data->delete();
            $toast = [
                "message" => "Position has been deleted",
                "bg" => "bg-danger"
            ];
        }

        return redirect()->back()->with([
            "toast" => $toast
        ]);
    }
}
