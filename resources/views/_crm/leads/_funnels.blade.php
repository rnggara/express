<div class="d-flex hover-scroll-x scroll-x h-100">
    @if ($funnels->count() == 0)
        <div class="w-100 text-center fs-3">
            <span>No opportunity available. Please make sure you have assigned into a team</span>
        </div>
    @else
        <div class="d-flex draggable-zone" id="dzone">
            @foreach ($funnels as $i => $item)
                @php
                    $row_id = isset($lead_funnel[$item->id]) ? count($lead_funnel[$item->id]) + 1 : 1;
                    $prj_count = isset($lead_funnel[$item->id]) ? count($lead_funnel[$item->id]) : 0;
                @endphp
                <div class="draggable" style="min-width: 300px" data-id="{{ $item->id }}" id="funnel-{{ $item->id }}">
                    <div class="card px-5 border" style="margin-bottom: 8px">
                        <div class="card-header border-0">
                            {{-- <div class="card-title">
                                <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary draggable-handle">
                                    <i class="ki-duotone ki-abstract-14 fs-2x"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                            </div> --}}
                            <h3 class="card-title draggable-handle">{{ $item->label }}</h3>
                            <div class="card-toolbar">
                                <div class="d-flex">
                                    <span class="badge badge-success badge-circle me-5" data-target="networth" style="border-radius: 13px!important">
                                        <i class="fa fa-dollar-sign text-white"></i>
                                    </span>
                                    <span class="badge badge-primary badge-circle" style="border-radius: 13px!important">{{ $prj_count }}</span>
                                    {{-- <button type="button" class="btn btn-icon btn-sm" data-id="{{ $item->id }}" data-label="{{ $item->label }}" data-bs-toggle="modal" data-bs-target="#modalDeleteFunnel" onclick="dfunel(this)">
                                        <i class="fa fa-trash text-danger"></i>
                                    </button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card border-0 w-100 mb-5">
                        <div class="card-body border border-dashed rounded">
                            <a href="{{ route("crm.lead.add", ["layoutid" => $layoutSelected,"fid" => $item->id, "rowid" => $row_id]) }}" class="d-flex justify-content-center align-items-center text-dark cursor-pointer text-hover-primary">
                                <span class="fa fa-plus me-3"></span>
                                <span>Tambah Leads</span>
                            </a>
                        </div>
                    </div> --}}
                    <div class="draggable-zone-leads h-750px scroll" data-funnel="{{ $item->id }}">
                        @if (isset($lead_funnel[$item->id]))
                            @foreach ($lead_funnel[$item->id] as $leads)
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

                                    $updClass = "muted";

                                    $_fd = $fdetail[$leads->funnel_id] ?? [];
                                    $idleState = $_fd->idle_state ?? null;
                                    $warningState = $_fd->warning_state ?? null;

                                    $lupdate = $lh[$leads->id] ?? [];

                                    $lupdate_date = $leads->updated_at;

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

                                @endphp
                                <div class="draggable-leads" data-id="{{ $leads->id }}" data-networth="{{ $leads->nominal ?? 0 }}" id="leads-{{ $leads->id }}">
                                    <div class="card border p-5" style="margin-bottom: 8px">
                                        {{-- <div class="card-header border-bottom-0 rounded">
                                            <a href="{{ route("crm.lead.add", ["layoutid" => $layoutSelected,'fid' => $item->id, "rowid" => $leads->row_order]) }}" class="card-title fs-3 text-dark link-leads">{{ $leads->leads_name }}</a>
                                            <div class="card-toolbar">
                                                <a href="#" class="btn btn-icon btn-hover-light-primary draggable-handle-leads">
                                                    <i class="ki-duotone ki-abstract-14 fs-2x"><span class="path1"></span><span class="path2"></span></i>
                                                </a>
                                            </div>
                                        </div> --}}
                                        <div class="card-body">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between mb-3">
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold fs-4 mb-2 cursor-pointer" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_leads" onclick="lead_detail({{ $leads->id }})" @if(strlen($leads->leads_name) > 100) data-bs-toggle="tooltip" title="{{ $leads->leads_name }}" @endif>{{ strlen($leads->leads_name) > 100 ? substr($leads->leads_name, 0, 97)."..."  : $leads->leads_name }}</span>
                                                        <span class="fw-semibold mb-1" @if(strlen($comps[$leads->id_client] ?? "-") > 67) data-bs-toggle="tooltip" title="{{ $comps[$leads->id_client] ?? "-" }}" @endif>{{ strlen($comps[$leads->id_client] ?? "-") > 67 ? substr($comps[$leads->id_client] ?? "-", 0, 64)."..."  : $comps[$leads->id_client] ?? "-" }}</span>
                                                        <span class="text-muted mb-1">Created at : {{ date("d-m-Y", strtotime($leads->created_at)) }}</span>
                                                        <span class="text-{{ $updClass }}">Updated at : {{ date("d-m-Y", strtotime($lupdate_date)) }}</span>
                                                    </div>
                                                    <a href="javascript:;" class="btn btn-icon btn-hover-light-primary draggable-handle-leads">
                                                        <i class="fa fa-grip-vertical fs-2"></i>
                                                    </a>
                                                </div>
                                                <div class="d-flex flex-column flex-md-row align-items-md-center">
                                                    <span class="badge me-3 badge-{{ $ptyClass }}">{{ $prty }}</span>
                                                    <span class="badge me-3 badge-{{ $confClass }}">{{ $conf }}</span>
                                                </div>
                                                <div class="mt-3">
                                                    <a href="javascript:;" onclick="topLead({{ $leads->id }}, this)">
                                                        <i class="fi fi-sr-star text-hover-warning text-active-warning text-secondary {{ in_array($leads->id, $ltop) ? "active" : "" }}"></i>
                                                    </a>
                                                </div>
                                                <hr>
                                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-circle symbol-25px me-3">
                                                            <div class="symbol-label" style="background-image:url('{{ asset($user_img[$leads->partner] ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                                        </div>
                                                    </div>
                                                    <div class="symbol-group symbol-hover">
                                                        @php
                                                            $contributors = json_decode($leads->contributors ?? "[]", true);
                                                        @endphp

                                                        @foreach ($contributors as $contri)
                                                            <div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip" title="{{ $user_name[$contri] ?? "-" }}">
                                                                <div class="symbol-label" style="background-image:url('{{ asset($user_img[$contri] ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <span class="fa fa-building me-3"></span>
                                                        <a href="{{ route("crm.list.view", ["type" => "company", "id" => $leads->id_client]) }}" target="_blank" class="fw-bold">{{ $comps[$leads->id_client] ?? "-" }}</a>

                                                    </div>
                                                    <span>{{ date("d/m/Y", strtotime($leads->created_at)) }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="fa fa-user me-3"></span>
                                                    @if($leads->contacts != "null")
                                                    @php
                                                        $con = json_decode($leads->contacts ?? "[]", true);
                                                    @endphp
                                                    @foreach ($con as $ic => $cc)
                                                        <span>{{ $contacts[$cc] ?? "-" }}{{ $ic < count($con)-1 ? "," : "" }}</span>
                                                    @endforeach
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </div>
                                                <div class="separator separator-solid my-3"></div>
                                                @php

                                                    $d1 = date_create(date("Y-m-d"));
                                                    $d2 = date_create($leads->updated_at);
                                                    $diff = date_diff($d1, $d2);
                                                    $a = $diff->format("%a");
                                                    if($a <= 7){
                                                        $lastUpdate = "green";
                                                    } elseif($a > 7 && $a <= 14){
                                                        $lastUpdate = "yellow";
                                                    } elseif($a > 14){
                                                        $lastUpdate = "red";
                                                    }

                                                @endphp
                                                <div class="row row-cols-2">
                                                    <div class="cols mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="fa fa-chart-bar me-3"></span>
                                                            <span class="badge badge-outline badge-{{ $ptyClass }}">{{ $prty }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="cols mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="fa fa-star me-3"></span>
                                                        </div>
                                                    </div>
                                                    <div class="cols mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="fa fa-user me-3"></span>
                                                            <span>{{ $users[$leads->partner] ?? "-" }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="cols mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="fa fa-clock me-3"></span>
                                                            <span class="badge badge-outline badge-{{ $lastUpdate }}">{{ date("d/m/Y", strtotime($leads->updated_at)) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @if ($i < count($funnels) - 1)
                <div class="border border-dashed" style=" margin-left: 12px; margin-right: 12px;"></div>
                @endif
            @endforeach
        </div>
    @endif
</div>
