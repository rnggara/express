<div class="accordion" id="kt_accordion_1_{{ $v }}">
    <div class="accordion-item border-0" style="background-color: var(--bs-page-bg)">
        <h2 class="accordion-header" id="kt_accordion_1_{{ $v }}_header_1" data-bs-toggle="collapse_title">
            <button class="accordion-button fw-semibold collapsed" style="background-color: var(--bs-page-bg)" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_{{ $v }}_body_1" aria-expanded="true" aria-controls="kt_accordion_1_{{ $v }}_body_1">
                <div class="d-flex flex-fill me-3 justify-content-around">
                    <div class="d-flex flex-column">
                        <span class="text-nowrap">Total Opportunity :</span>
                        <span class="fw-semibold">{{ $opportunity->count() }}</span>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-nowrap">Win :</span>
                        <span class="fw-semibold">{{ $opportunity->where("status_deal", 1)->count() }}</span>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-nowrap">Lose :</span>
                        <span class="fw-semibold">{{ $opportunity->where("status_deal", -1)->count() }}</span>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-nowrap">Win Rate :</span>
                        <span class="fw-semibold">{{ $opportunity->count() == 0 ? "0%" : (($opportunity->where('status_deal', 1)->count() / $opportunity->count()) * 100)."%" }}</span>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-nowrap">Avge. Sales Cylcle :</span>
                        <span class="fw-semibold text-nowrap">{{ $opportunity->count() == 0 ? "IDR 0" : "IDR ".number_format((($opportunity->sum("estimasi_profit") / $opportunity->count())), 0, ",", ".") }}</span>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="text-nowrap">Opportunity Worth :</span>
                        <span class="fw-semibold text-nowrap">{{ $opportunity->count() == 0 ? "IDR 0" : "IDR ".number_format(($opportunity->sum("estimasi_profit")), 0, ",", ".") }}</span>
                    </div>
                </div>
                <span class="fw-semibold fs-4" data-accordion="collapsed" style="display: none;">Company Detail Information</span>
            </button>
        </h2>
        <div id="kt_accordion_1_{{ $v }}_body_1" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_{{ $v }}_header_1" data-bs-parent="#kt_accordion_1_{{ $v }}">
            <div class="accordion-body">
                <div class="row">
                    <div class="fv-row col-6">
                        <label for="company_name" class="col-form-label required">Company Name</label>
                        <input type="text" name="company_name" id="company_name" value="{{ $detail->company_name }}" class="form-control" required placeholder="Input Company Name">
                    </div>
                    <div class="fv-row col-6">
                        <label for="company_name" class="col-form-label">Business Entity</label>
                        <select name="jenis_bisnis" class="form-select" data-control="select2" data-placeholder="PT">
                            <option value=""></option>
                            <option value="Persero" {{ $detail->category == "Persero" ? "SELECTED" : "" }}>Persero</option>
                            <option value="Perum" {{ $detail->category == "Perum" ? "SELECTED" : "" }}>Perum</option>
                            <option value="Perjan" {{ $detail->category == "Perjan" ? "SELECTED" : "" }}>Perjan</option>
                            <option value="PO" {{ $detail->category == "PO" ? "SELECTED" : "" }}>PO</option>
                            <option value="PT" {{ $detail->category == "PT" ? "SELECTED" : "" }}>PT</option>
                            <option value="CV" {{ $detail->category == "CV" ? "SELECTED" : "" }}>CV</option>
                            <option value="Firma" {{ $detail->category == "Firma" ? "SELECTED" : "" }}>Firma</option>
                            <option value="UD" {{ $detail->category == "UD" ? "SELECTED" : "" }}>UD</option>
                            <option value="Koperasi" {{ $detail->category == "Koperasi" ? "SELECTED" : "" }}>Koperasi</option>
                        </select>
                    </div>
                    <div class="fv-row col-6">
                        <label for="tipe" class="col-form-label">Type</label>
                        <select name="tipe" id="tipe-cont{{ $detail->id }}" class="form-select" data-control="select2" data-placeholder="Select Type">
                            <option value=""></option>
                            <option value="Competitor" {{ $detail->type == "Competitor" ? "SELECTED" : "" }}>Competitor</option>
                            <option value="Lead" {{ $detail->type == "Lead" ? "SELECTED" : "" }}>Lead</option>
                            <option value="Customer" {{ $detail->type == "Customer" ? "SELECTED" : "" }}>Customer</option>
                            <option value="Ex-customer" {{ $detail->type == "Ex-customer" ? "SELECTED" : "" }}>Ex-customer</option>
                            <option value="Partner" {{ $detail->type == "Partner" ? "SELECTED" : "" }}>Partner</option>
                            <option value="Other" {{ $detail->type == "Other" ? "SELECTED" : "" }}>Other</option>
                        </select>
                    </div>
                    <div class="fv-row col-6">
                        <label for="jumlah_karyawan" class="col-form-label">Number of Employees</label>
                        <select name="jumlah_karyawan"  class="form-select" data-control="select2" data-placeholder="1-50">
                            <option value=""></option>
                            <option value="1-50" {{ $detail->jumlah_karyawan == "1-50" ? "SELECTED" : "" }}>1-50</option>
                            <option value="51-100" {{ $detail->jumlah_karyawan == "51-100" ? "SELECTED" : "" }}>51-100</option>
                            <option value="101-200" {{ $detail->jumlah_karyawan == "101-200" ? "SELECTED" : "" }}>101-200</option>
                            <option value="201-500" {{ $detail->jumlah_karyawan == "201-500" ? "SELECTED" : "" }}>201-500</option>
                            <option value=">500" {{ $detail->jumlah_karyawan == ">500" ? "SELECTED" : "" }}>>500</option>
                        </select>
                        {{-- <input type="text" name="jumlah_karyawan" id="jumlah_karyawan" value="{{ $detail->jumlah_karyawan }}" class="form-control" placeholder="Masukan jumlah karyawan"> --}}
                    </div>
                </div>
                <hr>
                {{-- <div class="fv-row">
                    <label for="tag" class="col-form-label">Tag</label>
                    <input type="text" name="tag" id="tag-company-{{ $detail->id }}" class="form-control tag" value="{{ empty($detail->tags) ? "" : $detail->tags }}" placeholder="Masukan tag">
                </div> --}}
                <div class="row">
                    <div class="fv-row col-6" data-toggle="phone">
                        <label for="name" class="col-form-label">Phone Number</label>
                        <div class="row">
                            <div class="col-4">
                                <select name="phone_type" class="form-select" data-control="select2" data-placeholder="Work">
                                    <option value=""></option>
                                    <option value="Work">Work</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Home">Home</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-8">
                                <input type="text" name="phone_number" class="form-control phone-number" placeholder="Input Phone Number">
                            </div>
                            <div class="col-12 mt-3 text-end">
                                <button type="button" class="btn btn-primary btn-sm" data-button>
                                    Save
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 d-phone-user">
                            @if (count($detail->phones ?? []) > 0)
                                @foreach ($detail->phones as $item)
                                    <div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>
                                        <div class="d-flex align-items-center">
                                            <i class="la la-phone me-1 text-primary"></i>
                                            <span>{{ $item['type'] }} : {{ $item['phone'] }}</span>
                                        </div>
                                        <input type='hidden' name='phone[]' value="{{ $item['phone'] }}">
                                        <input type='hidden' name='phone_types[]' value="{{ $item['type'] }}">
                                        <button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>
                                    </div>
                                @endforeach
                            @endif
                            @if (empty($detail->phones) && !empty($detail->pic_number))
                                <div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>
                                    <div class="d-flex align-items-center">
                                        <i class="la la-phone me-1 text-primary"></i>
                                        <span>{{ "Work" }} : {{ $detail->pic_number }}</span>
                                    </div>
                                    <input type='hidden' name='phone[]' value="{{ $detail->pic_number }}">
                                    <input type='hidden' name='phone_types[]' value="{{ "Work" }}">
                                    <button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="fv-row col-6" data-toggle="email">
                        <label for="name" class="col-form-label">Email</label>
                        <div class="row">
                            <div class="col-12">
                                <input type="email" name="email_input" class="form-control" placeholder="Input Email">
                            </div>
                            <div class="col-12 mt-3 text-end">
                                <button type="button" class="btn btn-primary btn-sm" data-button>
                                    Save
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 d-email-user">
                            @if (count($detail->emails ?? []) > 0)
                                @foreach ($detail->emails as $item)
                                    <div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>
                                        <div class="d-flex align-items-center">
                                            <i class="la la-mail-bulk me-1 text-primary"></i>
                                            <span>{{ $item }}</span>
                                        </div>
                                        <input type='hidden' name='email[]' value="{{ $item }}">
                                        <button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>
                                    </div>
                                @endforeach
                            @endif
                            @if (empty($detail->emails) && !empty($detail->email))
                                <div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>
                                    <div class="d-flex align-items-center">
                                        <i class="la la-mail-bulk me-1 text-primary"></i>
                                        <span>{{ $detail->email }}</span>
                                    </div>
                                    <input type='hidden' name='email[]' value="{{ $detail->email }}">
                                    <button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>
                                </div>
                            @endif
                        </div>
                    </div>
                    {{-- <div class="fv-row col-6">
                        <label for="no_telp" class="col-form-label">Phone Number</label>
                        <input type="text" name="no_telp" id="no_telp" class="form-control" value="{{ $detail->pic_number }}" placeholder="Input phone number">
                    </div>
                    <div class="fv-row col-6">
                        <label for="email" class="col-form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $detail->email }}" placeholder="Input email">
                    </div> --}}
                </div>
                <hr>
                <div class="fv-row">
                    <label for="alamat" class="col-form-label">Address</label>
                    <div class="d-add-address">
                        @foreach ($address as $item)
                        <div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>
                                <div class="align-items-baseline align-items-center d-flex flex-column">
                                    <span class="fw-bold">{{ $item->title }}</span>
                                    <span>{{ $item->address }}</span>
                                </div>
                                <input type='hidden' name='address[]' value="{{ $item->address }}">
                                <input type='hidden' name='title[]' value="{{ $item->title }}">
                                <input type='hidden' name='full_address[]' value="{{$item->full_address}}">
                                <input type='hidden' name='postal_code[]' value="{{$item->postal_code}}">
                                <input type='hidden' name='country[]' value="{{$item->country}}">
                                <input type='hidden' name='province[]' value="{{$item->province}}">
                                <input type='hidden' name='city[]' value="{{$item->city}}">
                                <input type='hidden' name='subdistrict[]' value="{{$item->subdistrict}}">
                                <button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn text-primary p-0" onclick="modalAddress(this)">
                            <i class="fa fa-plus"></i>
                            Add Address
                        </button>
                    </div>
                </div>
                <hr>
                <div class="fv-row">
                    <label for="pic_names-dt" class="col-form-label ">Contact Person</label>
                    <div class="position-relative">
                        <input type="text" class="form-control find-contact pe-15" data-name='contact_id[]' placeholder="Select or Add Contact">
                        <div class="find-result"></div>
                        <div class="find-noresult"></div>
                        <div class="find-add">
                            @foreach ($pic as $item)
                                <span class="fw-bold cursor-pointer" onclick="show_detail('#drawer-advance', 'contacts', {{ $item->id }})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_list_extend">
                                    <input type="hidden" name="contact_id[]" value="{{ $item->id }}">
                                    {{ $item->name }},
                                </span>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-primary btn-sm position-absolute end-0 top-0 btn-icon mt-1 me-1" style="display: none" data-bs-toggle="tooltip" title="Add Contact">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="fv-row">
                    <label for="pic_names-dt" class="col-form-label ">Related Opportunity</label>
                    <div class="position-relative">
                        <input type="text" class="form-control find-opportunity pe-15" data-multiple="true" data-name='opportunity_id[]' placeholder="Select or Add Opportunity">
                        <div class="find-result"></div>
                        <div class="find-noresult"></div>
                        <div class="find-add">
                            @if ($opportunity->count() > 0)
                                    @foreach ($opportunity as $item)
                                    <div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5 opportunity-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fi fi-rr-building fw-bold text-primary me-3"></i>
                                            <span>{{ $item->leads_name }}</span>
                                            <input type="hidden" name="opportunity_id[]" value="{{ $item->id }}">
                                        </div>
                                        <button type="button" onclick="removeOpporunity(this, {{ $item->id }})" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                            <i class="fi fi-rr-trash"></i>
                                        </button>
                                    </div>
                                    @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-primary btn-sm position-absolute end-0 top-0 btn-icon mt-1 me-1" style="display: none" data-bs-toggle="tooltip" title="Add Opportunity">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="col-12 separator my-5"></div>
                @foreach ($properties as $item)
                    @php
                        $pName = "property[$item->id]";
                        if(in_array($item->property_type, [5,6,7,8,9,10,11])){
                            $pName = "property[$item->id][]";
                        } elseif(in_array($item->property_type, [18, 20])){
                            $pName = [
                                "property[$item->id][start]",
                                "property[$item->id][end]",
                            ];
                        }

                        $propVal = $prop[$item->id] ?? null;
                        $value = $propVal->property_value ?? "";
                        $propArr = json_decode($propVal->property_value ?? "");
                        if(is_array($propArr)){
                            $value = $propArr;
                        }

                        $additional = json_decode($item->additional ?? "[]");
                        if($item->property_type == 8){
                            $additional = $user_hris;
                            $value = json_decode($propVal->property_value ?? "[]");
                        }
                    @endphp
                    @component("_crm.preferences.properties.preview._$item->property_type", [
                        "name" => $item->property_name,
                        "placeholder" => $item->property_placeholder,
                        "readonly" => "",
                        "form_name" => $pName,
                        "test" => "hello",
                        "value" => $value,
                        "contacts" => $contacts,
                        "clients" => $clients,
                        "additional" => [
                            "option" => $additional
                        ]
                    ])
                    @endcomponent
                @endforeach
                <div class="fv-row mt-5">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_{{ $v }}_body_1" aria-expanded="true" aria-controls="kt_accordion_1_{{ $v }}_body_1">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
                {{-- <div class="fv-row">
                    <label for="budget" class="col-form-label">Budget</label>
                    <input type="text" name="budget" id="budget" class="form-control number" value="{{ number_format($detail->budget, 2, ",", ".") }}" placeholder="Masukan budget">
                </div>
                <div class="fv-row">
                    <label for="province" class="col-form-label required">Provinsi</label>
                    <select name="province" id="province-cont{{ $detail->id }}" class="form-select" required data-control="select2" data-placeholder="Pilih provinsi">
                        <option value=""></option>
                        @foreach ($province as $prov)
                            <option value="{{ $prov->id }}" {{ $detail->prov_id == $prov->id ? "SELECTED" : "" }}>{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row">
                    <label for="city" class="col-form-label required">Kota</label>
                    <select name="city" id="city-cont{{ $detail->id }}" class="form-select" required data-control="select2" data-placeholder="Pilih kota">
                        <option value=""></option>
                        @foreach ($city as $cit)
                            <option value="{{ $cit->id }}" {{ $detail->city_id == $cit->id ? "SELECTED" : "" }}>{{ $cit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row">
                    <label for="kode_pos" class="col-form-label required">Kode Pos</label>
                    <input type="text" name="kode_pos" id="kode_pos" class="form-control" value="{{ $detail->kode_pos }}" required placeholder="Masukan kode pos">
                </div>
                <div class="separator mt-5"></div>
                <div class="fv-row">
                    <label for="latitude" class="col-form-label ">Latitude</label>
                    <input type="text" name="latitude" value="{{ $detail->latitude ?? "" }}" id="latitude" class="form-control"  placeholder="Masukan Latitude">
                </div>
                <div class="fv-row">
                    <label for="longitude" class="col-form-label ">Longitude</label>
                    <input type="text" name="longitude" value="{{ $detail->longitude ?? "" }}" id="longitude" class="form-control"  placeholder="Masukan Longitude">
                </div>
                <div class="fv-row">
                    <label for="radius" class="col-form-label ">Radius (meters)</label>
                    <input type="number" name="radius" value="{{ $detail->radius ?? "500" }}" id="radius" class="form-control"  placeholder="Masukan Radius">
                </div> --}}
                {{-- @if ($vars->where("var_type", "company")->count() > 0)
                    <div class="separator separator-solid mt-5"></div>
                @endif
                @foreach ($vars->where("var_type", "company") as $item)
                    <div class="fv-row">
                        <label for="var{{ $item->id }}" class="col-form-label">{{ ucwords($item->parameter_name) }}</label>
                        <input type="text" name="var[{{ $item->id }}]" id="var{{ $item->id }}" class="form-control {{ $item->parameter_type == "Date" ? "tempusDominus" : "" }}" value="{{ $othVal[$item->id] ?? "" }}" placeholder="{{ ucwords($item->parameter_name) }}">
                    </div>
                @endforeach --}}
            </div>
        </div>
    </div>
</div>
