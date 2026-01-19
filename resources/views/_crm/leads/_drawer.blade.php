@php
    $conf = "Nice to";
    $confClass = "secondary";
    if($leads['sales_confidence'] == 1){
        $confClass = "secondary";
        $conf = "Nice to";
    } elseif($leads['sales_confidence'] == 2){
        $confClass = "success";
        $conf = "Run through";
    } elseif($leads['sales_confidence'] == 3){
        $confClass = "warning";
        $conf = "Best case";
    } elseif($leads['sales_confidence'] == 4){
        $confClass = "danger";
        $conf = "Commit";
    }

    $prty = "Low";
    $ptyClass = "success";
    if($leads['level_priority'] == 1){
        $ptyClass = "success";
        $prty = "Low";
    } elseif($leads['level_priority'] == 2){
        $ptyClass = "warning";
        $prty = "Medium";
    } elseif($leads['level_priority'] == 3){
        $ptyClass = "danger";
        $prty = "High";
    }
@endphp
<div class="accordion" id="kt_lead_drawer_acc">
    <div class="accordion-item border-0" style="background-color: var(--bs-page-bg)">
        <div class="align-items-center d-flex justify-content-between ps-5 py-5">
            <div class="d-flex flex-column w-100">
                <span class="fw-bold fs-3 mb-5 cursor-pointer" onclick="show_detail('company', {{ $leads->id_client }}, {{ $leads->id }})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_leads_extend">{{ $clients[$leads->id_client] ?? "-" }}</span>
                <div class="d-flex justify-content-between">
                    <span>Estimated Revenue (IDR) <span class="fw-bold">{{ number_format($leads->nominal, 0, ",", ".") }}</span></span>
                    <div class="d-flex flex-column flex-md-row align-items-md-center">
                        <span class="badge me-3 badge-secondary">{{ $funnel->label ?? "-" }}</span>
                        <span class="badge me-3 badge-{{ $confClass }}">{{ $conf }}</span>
                        <span class="badge me-3 badge-{{ $ptyClass }}">{{ $prty }}</span>
                    </div>
                </div>
            </div>
            <span class="fw-semibold fs-4" data-accordion="collapsed" style="display: none;">Opportunity detail information</span>
            <h2 class="accordion-header" id="kt_lead_drawer_acc_header_1">
                <button class="accordion-button fw-semibold collapsed" style="background-color: var(--bs-page-bg)" type="button" data-bs-toggle="collapse" data-bs-target="#kt_lead_drawer_acc_body_1" aria-expanded="true" aria-controls="kt_lead_drawer_acc_body_1">

                </button>
            </h2>
        </div>
        <div id="kt_lead_drawer_acc_body_1" class="accordion-collapse collapse" aria-labelledby="kt_lead_drawer_acc_header_1" data-bs-parent="#kt_lead_drawer_acc">
            <div class="accordion-body">
                <div class="row">
                    <div class="fv-row col-12">
                        <label class="col-form-label">Opportunity Name</label>
                        <input type="text" name="leads_name" class="form-control" placeholder="Input Opportunity Name" value="{{ $leads->leads_name }}">
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label">Estimated Revenue (IDR)</label>
                        <div class="position-relative">
                            <input type="text" class="form-control input-currency" placeholder="IDR 0" name="nominal" value="{{ number_format($leads->nominal, 0, ",", ".") }}">
                            <span class="mt-4 position-absolute ps-4 top-0" style="display: none">IDR</span>
                        </div>
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label">Estimated Gross Profit (IDR)</label>
                        <div class="position-relative">
                            <input type="text" class="form-control input-currency" placeholder="IDR 0" name="estimasi_profit" value="{{ number_format($leads->estimasi_profit, 0, ",", ".") }}">
                            <span class="mt-4 position-absolute ps-4 top-0" style="display: none">IDR</span>
                        </div>
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label">Opportunity Owner</label>
                        <input type="text" readonly class="form-control" value="{{ $users[$leads->partner] ?? "-" }}">
                    </div>
                    <div class="fv-row col-6" id="opp-collab">
                        @php
                            $collaborators = json_decode($leads->contributors ?? "[]", true);
                        @endphp
                        <label class="col-form-label">Collaborators</label>
                        <select name="collab_sel" class="form-control" id="collab_sel" data-dropdown-parent="#opp-collab" data-placeholder="Add Collaborator">
                            <option value=""></option>
                            @foreach ($users_collab as $idUser => $item)
                                @if ($item->id != $leads->partner)
                                    <option value="{{ $item->id }}" 
                                    data-email="{{ $item->email }}"
                                    data-company="{{ $item->company->company_name }}"
                                    data-phone="{{ $item->phone ?? "-" }}"
                                    data-job="{{ $item->crmRole->name ?? "-" }}">{{ ucwords($item->name) }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="collaborator-list d-flex flex-column" id="collab_list">
                            @foreach ($collaborators as $item)
                                @if (isset($users[$item]))
                                    <span class="fw-bold cursor-pointer">
                                        <input type="hidden" name="collaborators[]" value="{{ $item }}">
                                        {{$users[$item]  }},
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="fv-row col-6" id="opp-source-detail">
                        <label for="" class="col-form-label">Opportunity Source</label>
                        <select name="source" class="form-select" data-control="select2" data-dropdown-parent="#opp-source-detail" data-placeholder="Canvasing">
                            <option value=""></option>
                            <option value="Canvasing" {{ $leads->sumber == "Canvasing" ? "SELECTED" : "" }}>Canvasing</option>
                            <option value="Web" {{ $leads->sumber == "Web" ? "SELECTED" : "" }}>Web</option>
                            <option value="Phone Inquiry" {{ $leads->sumber == "Phone Inquiry" ? "SELECTED" : "" }}>Phone Inquiry</option>
                            <option value="Partner Referral" {{ $leads->sumber == "Partner Referral" ? "SELECTED" : "" }}>Partner Referral</option>
                            <option value="External Partner" {{ $leads->sumber == "External Partner" ? "SELECTED" : "" }}>External Partner</option>
                            <option value="Partner" {{ $leads->sumber == "Partner" ? "SELECTED" : "" }}>Partner</option>
                            <option value="Public Relations" {{ $leads->sumber == "Public Relations" ? "SELECTED" : "" }}>Public Relations</option>
                            <option value="Trade Show" {{ $leads->sumber == "Trade Show" ? "SELECTED" : "" }}>Trade Show</option>
                            <option value="Word of mouth" {{ $leads->sumber == "Word of mouth" ? "SELECTED" : "" }}>Word of mouth</option>
                            <option value="Employee Referral" {{ $leads->sumber == "Employee Referral" ? "SELECTED" : "" }}>Employee Referral</option>
                            <option value="Purchased List" {{ $leads->sumber == "Purchased List" ? "SELECTED" : "" }}>Purchased List</option>
                            <option value="Other" {{ $leads->sumber == "Other" ? "SELECTED" : "" }}>Other</option>
                        </select>
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label">Estimated Closing Date</label>
                        <input type="text" name="end_date" id="opp-end_date" value="{{ !empty($leads->end_date) ? date("d/m/Y", strtotime($leads->end_date)) : "" }}" data-view="calendar" data-mask="99/99/9999" class="form-control tempusDominus" placeholder="{{ date("d/m/Y") }}">
                    </div>
                    <div class="col-12 separator my-5"></div>
                    <div class="fv-row col-6">
                        <label class="col-form-label">Sales Confidence</label>
                        <div class="form-floating">
                            <div class="row row-cols-4 bg-secondary-crm rounded border h-45px scale mx-0">
                                @php
                                    $scaleColor = ['secondary', 'success', 'warning', 'danger'];
                                    $scaleTitle = ['Nice To', "Run Through", "Best Case", "Commit"];
                                @endphp
                                @for ($j = 0; $j < 4; $j ++)
                                    @php
                                        $_val = "#EBEBEB";
                                    @endphp
                                    <label data-color="{{ $scaleColor[$j] }}" data-title="{{ $scaleTitle[$j] }}" for="ckScale{{ $j }}" class="border scale-item border-0 col cursor-pointer {{ $j == 0 ? "rounded-start" : (($j == 3) ? "rounded-end " : "") }}" style="background-color: {{ $_val }}">
                                        <input type="radio" name="sales_confidence"  {{ (empty($leads) && $j == 0) || ($leads->sales_confidence ?? 1) == ($j + 1) ? "checked" : "" }} id="ckScale{{ $j }}" value="{{ $j+1 }}" class="d-none">
                                    </label>
                                @endfor
                            </div>
                            <label class="col-form-label"></label>
                        </div>
                        {{-- <select name="sales_confidence" class="form-control">
                            <option value="1" {{ $leads->sales_confidence == "1" ? "SELECTED" : "" }}>Nice To</option>
                            <option value="2" {{ $leads->sales_confidence == "2" ? "SELECTED" : "" }}>Run Through</option>
                            <option value="3" {{ $leads->sales_confidence == "3" ? "SELECTED" : "" }}>Best Case</option>
                            <option value="4" {{ $leads->sales_confidence == "4" ? "SELECTED" : "" }}>Commit</option>
                        </select> --}}
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label">Priority</label>
                        <div class="form-floating">
                            @php
                                $scaleColor = ['success', 'warning', 'danger'];
                                $scaleTitle = ['Low', "Medium", "High"];
                            @endphp
                            <div class="row row-cols-{{ count($scaleTitle) }} bg-secondary-crm rounded border h-45px scale mx-0">
                                @for ($j = 0; $j < count($scaleTitle); $j ++)
                                    @php
                                        $_val = "#EBEBEB";
                                    @endphp
                                    <label data-color="{{ $scaleColor[$j] }}" data-title="{{ $scaleTitle[$j] }}" for="ckPrior{{ $j }}" class="border scale-item border-0 col cursor-pointer {{ $j == 0 ? "rounded-start" : (($j == count($scaleTitle) - 1) ? "rounded-end " : "") }}" style="background-color: {{ $_val }}">
                                        <input type="radio" name="level_priority"  {{ (empty($leads) && $j == 0) || ($leads->level_priority ?? 1) == ($j + 1) ? "checked" : "" }} id="ckPrior{{ $j }}" value="{{ $j+1 }}" class="d-none">
                                    </label>
                                @endfor
                            </div>
                            <label class="col-form-label"></label>
                        </div>
                        {{-- <select name="level_priority" class="form-control">
                            <option value="1" {{ $leads->level_priority == "1" ? "SELECTED" : "" }}>Low</option>
                            <option value="2" {{ $leads->level_priority == "2" ? "SELECTED" : "" }}>Medium</option>
                            <option value="3" {{ $leads->level_priority == "3" ? "SELECTED" : "" }}>High</option>
                        </select> --}}
                    </div>
                    <div class="col-12 separator my-5"></div>
                    {{-- <div class="dv-row col-12">
                        <label class="col-form-label w-100">Status</label>
                        <!--begin::Radio group-->
                        <div class="btn-group w-100" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                            <!--begin::Radio-->
                            <label class="btn btn-outline btn-color-muted btn-active-secondary {{ $leads->status_deal == 0 ? 'active' : "" }}" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="status_deal" {{ $leads->status_deal == 0 ? 'checked="checked"' : "" }} value="0"/>
                                <!--end::Input-->
                                On going
                            </label>
                            <!--end::Radio-->

                            <!--begin::Radio-->
                            <label class="btn btn-outline btn-color-muted btn-active-danger {{ $leads->status_deal == -1 ? 'active' : "" }}" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="status_deal" {{ $leads->status_deal == -1 ? 'checked="checked"' : "" }} value="1"/>
                                <!--end::Input-->
                                Lose
                            </label>
                            <!--end::Radio-->

                            <!--begin::Radio-->
                            <label class="btn btn-outline btn-color-muted btn-active-success {{ $leads->status_deal == 1 ? 'active' : "" }}" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="status_deal" {{ $leads->status_deal == 1 ? 'checked="checked"' : "" }} value="1" />
                                <!--end::Input-->
                                Win
                            </label>
                            <!--end::Radio-->
                        </div>
                        <!--end::Radio group-->
                    </div> --}}
                    <div class="col-12 separator my-5"></div>
                    <div class="fv-row col-12">
                        <label class="col-form-label">Company Name</label>
                        <div class="position-relative">
                            <input type="text" class="form-control find-company pe-15" data-name='id_client' data-multiple="false" {{ !empty($leads->id_client) ? "disabled" : "" }} placeholder="Select or add company">
                            <div class="find-result"></div>
                            <div class="find-noresult"></div>
                            <div class="find-add">
                                @if (!empty($leads->id_client))
                                    <div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5 company-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fi fi-rr-building fw-bold text-primary me-3"></i>
                                            <span class='cursor-pointer' onclick="show_detail('company', {{ $leads->id_client }}, {{ $leads->id }})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_leads_extend">{{ $clients[$leads->id_client] ?? "-" }}</span>
                                            <input type="hidden" name="id_client" value="{{ $leads->id_client }}">
                                        </div>
                                        <button type="button" onclick="removeCompany(this)" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                            <i class="fi fi-rr-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-primary btn-sm position-absolute end-0 top-0 btn-icon mt-1 me-1" style="display: none" data-bs-toggle="tooltip" title="Add Contact">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 separator my-5"></div>
                    <div class="fv-row col-12">
                        @php
                            $leadContact = json_decode($leads->contacts ?? "[]");
                        @endphp
                        <label for="" class="col-form-label">Contact Person</label>
                        <div class="position-relative">
                            <input type="text" class="form-control find-contact pe-15" data-name='contact_id[]' placeholder="Select or Add Contact">
                            <div class="find-result"></div>
                            <div class="find-noresult"></div>
                            <div class="find-add">
                                @foreach ($leadContact as $item)
                                    @if (isset($contacts[$item]))
                                        <span class="fw-bold cursor-pointer" onclick="show_detail('contacts', {{ $item }}, {{ $leads->id }})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_leads_extend">
                                            <input type="hidden" name="contact_id[]" value="{{ $item }}">
                                            {{$contacts[$item]  }},
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-primary btn-sm position-absolute end-0 top-0 btn-icon mt-1 me-1" style="display: none" data-bs-toggle="tooltip" title="Add Contact">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 separator my-5"></div>
                    <div class="fv-row col-12" id="opp-prod-drawer">
                        @php
                            $leadProd = json_decode($leads->products ?? "[]");
                        @endphp
                        <label class="col-form-label">Product or Solutions</label>
                        <select name="prod_sel" id="" class="form-select" data-control="select2" data-dropdown-parent="#opp-prod-drawer" data-placeholder="Select Product or Solutions">
                            <option value=""></option>
                            @foreach ($products as $idProd => $item)
                            <option value="{{ $idProd }}" {{ in_array($idProd, $leadProd) ? "disabled" : "" }}>{{ $item }}</option>
                            @endforeach
                        </select>
                        <div class="product-list">
                            @foreach ($leadProd as $item)
                                @if (isset($products[$item]))
                                    <div class="d-flex product-item justify-content-between border bg-white rounded p-3 align-items-center mt-5">
                                        <div class="d-flex align-items-center">
                                            <i class="fi fi-rr-cube fw-bold text-primary me-3"></i>
                                            <span>{{ $products[$item] }}</span>
                                            <input type="hidden" name="product_id[]" value="{{ $item }}">
                                        </div>
                                        <button type="button" onclick="removeProd(this)" data-id="{{ $item }}" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                            <i class="fi fi-rr-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
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
                                $additional = $users_collab;
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
                    <div class="fv-row col-12 mt-5">
                        <button type="button" class="btn" id="kt_drawer_example_basic_close">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        @if (in_array(Auth::user()->id, [$leads->created_by, $leads->partner]))
                            {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
