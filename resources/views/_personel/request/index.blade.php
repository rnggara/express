@extends('_personel.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-50px me-3">
            <div class="symbol-label bg-primary">
                <i class="fi fi-sr-time-check text-white fs-1"></i>
            </div>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-3 fw-bold">Permintaan</span>
            <span class="text-muted">Anda bisa melihat semua permintaan pada halaman ini</span>
        </div>
    </div>
    <div class="d-flex flex-column">
        <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
            <li class="nav-item">
                <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_all">
                    <span class="nav-text">Semua Permintaan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_approval">
                    <span class="nav-text">Butuh Persetujuan</span>
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab_all" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="position-relative me-5">
                                    <input type="text" class="form-control ps-12" placeholder="Cari" name="search_table">
                                    <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                </div>
                            </div>
                            <div class="scroll">
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-overtime">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check">
                                                    <label class="form-check-label" >
                                                        <input class="form-check-input" type="checkbox" value=""/>
                                                    </label>
                                                </div>
                                            </th>
                                            <th>Nama Pekerja</th>
                                            <th>Permintaan</th>
                                            <th>Disetujui Oleh</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reg as $item)
                                            @php
                                                $show = false;
                                                $el = [];
                                                if(isset($data_request[$item->id])){
                                                    $show = true;
                                                    $el = $data_request[$item->id];
                                                }
                                            @endphp
                                            @if ($show)
                                            @foreach ($el as $state => $data)
                                                @foreach ($data as $date => $val)
                                                    @php
                                                        $approvedBy = [];
                                                        $approvedById = [];
                                                        foreach ($val as $key => $value) {
                                                            if(!empty($value->approve->name) && !in_array($value->approve->name ?? "", $approvedBy)){
                                                                $approvedBy[] = $value->approve->name;
                                                                $approvedById[] = $value->approve->id;
                                                            }
                                                        }
                                                    @endphp
                                                    @if (in_array(\Auth::id(), $approvedById))
                                                        <tr>
                                                            <td style="width: 5%">
                                                                <div class="form-check">
                                                                    <label class="form-check-label" >
                                                                        <input class="form-check-input" type="checkbox" value=""/>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-start">
                                                                    <div class="symbol symbol-40px me-3">
                                                                        <div class="symbol-label" style="background-image: url({{ asset($item->user->user_img ?? "images/image_placeholder.png") }})"></div>
                                                                    </div>
                                                                    <div class="d-flex flex-column">
                                                                        <span class="fw-bold">{{ $item->emp_name }}</span>
                                                                        <span>{{ $item->emp_id }}</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex flex-column">
                                                                    @foreach ($val as $req)
                                                                        <div class="d-flex align-items-center">
                                                                            <span>{{ $req_data[$req->type ?? ''][$req->old] ?? ($req->old ?? "-") }}</span>
                                                                            <span class="fi fi-rr-arrow-alt-right mx-3"></span>
                                                                            <span>{{ $req_data[$req->type ?? ''][$req->new] ?? ($req->new ?? "-") }}</span>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </td>
                                                            <td>
                                                                {{ implode(", ", $approvedBy) }}
                                                            </td>
                                                            <td>
                                                                @if ($state == "approved")
                                                                    <span class="badge badge-success">Approved</span>
                                                                @elseif($state == "need_approval")
                                                                    <span class="badge badge-secondary">Waiting</span>
                                                                @else
                                                                    <span class="badge badge-warning">Process</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                                    <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                                </button>
                                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                                    <div class="menu-item px-3">
                                                                        <a href="javascript:;" onclick="show_detail({{ $item->id }}, '{{ $date }}', '')" class="menu-link px-3">
                                                                            Request Detail
                                                                        </a>
                                                                    </div>
                                                                    @if ($state == "need_approval")
                                                                    <div class="menu-item px-3">
                                                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $item->id }}_{{ $date }}" class="menu-link px-3 text-danger">
                                                                            Cancel Request
                                                                        </a>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                <form action="{{ route("personel.request.cancel_request", $item->id) }}" method="post">
                                                                    <div class="modal fade" tabindex="-1" id="modal_delete_{{ $item->id }}_{{ $date }}">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content">
                                                                                <div class="modal-body">
                                                                                    <div class="d-flex flex-column align-items-center">
                                                                                        <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                                                                                        <span class="fw-bold fs-3">Are you sure want to cancel request??</span>
                                                                                        <span class="text-center">Please becarefull when canceling, requests already made will be</span>
                                                                                        <span class="text-center">deleted, and cannot be restored. So you have to make a new request.</span>
                                                                                        <div class="d-flex align-items-center mt-5">
                                                                                            @csrf
                                                                                            <input type="hidden" name="date" value="{{ $date }}">
                                                                                            <button type="submit" name="submit" value="delete" class="btn btn-white">Yes</button>
                                                                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab_approval" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="position-relative me-5">
                                    <input type="text" class="form-control ps-12" placeholder="Cari" name="search_table">
                                    <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                </div>
                            </div>
                            <div class="scroll">
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-leave-request">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check">
                                                    <label class="form-check-label" >
                                                        <input class="form-check-input" type="checkbox" value=""/>
                                                    </label>
                                                </div>
                                            </th>
                                            <th>Nama Pekerja</th>
                                            <th>Permintaan</th>
                                            <th>Disetujui Oleh</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reg as $item)
                                            @php
                                                $show = false;
                                                $el = [];
                                                if(isset($data_request[$item->id])){
                                                    $show = true;
                                                    $el = $data_request[$item->id];
                                                }
                                            @endphp
                                            @if ($show)
                                            @foreach ($el as $state => $data)
                                                @foreach ($data as $date => $val)
                                                    @php
                                                        $approvedBy = [];
                                                        $approvedById = [];
                                                        $_approved = [];
                                                        $hasApproved = false;
                                                        foreach ($val as $key => $value) {
                                                            if(in_array($value->id, $myApproval)){
                                                                $_approved[] = true;
                                                            }
                                                            if(!empty($value->approve->name) && !in_array($value->approve->name ?? "", $approvedBy)){
                                                                $approvedBy[] = $value->approve->name;
                                                                $approvedById[] = $value->approve->id;
                                                            }
                                                        }

                                                        if(in_array(true, $_approved)){
                                                            $hasApproved = true;
                                                        }
                                                    @endphp
                                                    @if ($state != "approved" && in_array(\Auth::id(), $approvedById))
                                                    <tr>
                                                        <td style="width: 5%">
                                                            <div class="form-check">
                                                                <label class="form-check-label" >
                                                                    <input class="form-check-input" type="checkbox" value=""/>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-start">
                                                                <div class="symbol symbol-40px me-3">
                                                                    <div class="symbol-label" style="background-image: url({{ asset($item->user->user_img ?? "images/image_placeholder.png") }})"></div>
                                                                </div>
                                                                <div class="d-flex flex-column">
                                                                    <span class="fw-bold">{{ $item->emp_name }}</span>
                                                                    <span>{{ $item->emp_id }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                @foreach ($val as $req)
                                                                    <div class="d-flex align-items-center">
                                                                        <span>{{ $req_data[$req->type ?? ''][$req->old] ?? ($req->old ?? "-") }}</span>
                                                                        <span class="fi fi-rr-arrow-alt-right mx-3"></span>
                                                                        <span>{{ $req_data[$req->type ?? ''][$req->new] ?? ($req->new ?? "-") }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                        <td>
                                                            {{ implode(", ", $approvedBy) }}
                                                        </td>
                                                        <td>
                                                            @if (!$hasApproved)
                                                            <a href="javascript:;" onclick="show_detail({{ $item->id }}, '{{ $date }}', '{{ $state != 'approved' ? '1' : '' }}')">Click here to approve</a>
                                                            @else
                                                                <span class="text-warning">Process</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                                <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                            </button>
                                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                                <div class="menu-item px-3">
                                                                    <a href="javascript:;" onclick="show_detail({{ $item->id }}, '{{ $date }}', '')" class="menu-link px-3">
                                                                        Request Detail
                                                                    </a>
                                                                </div>
                                                                @if ($state == "need_approval")
                                                                <div class="menu-item px-3">
                                                                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $item->id }}_{{ $date }}" class="menu-link px-3 text-danger">
                                                                        Cancel Request
                                                                    </a>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <form action="{{ route("personel.request.cancel_request", $item->id) }}" method="post">
                                                                <div class="modal fade" tabindex="-1" id="modal_delete_{{ $item->id }}_{{ $date }}">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div class="modal-body">
                                                                                <div class="d-flex flex-column align-items-center">
                                                                                    <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                                                                                    <span class="fw-bold fs-3">Are you sure want to cancel request??</span>
                                                                                    <span class="text-center">Please becarefull when canceling, requests already made will be</span>
                                                                                    <span class="text-center">deleted, and cannot be restored. So you have to make a new request.</span>
                                                                                    <div class="d-flex align-items-center mt-5">
                                                                                        @csrf
                                                                                        <input type="hidden" name="date" value="{{ $date }}">
                                                                                        <button type="submit" name="submit" value="delete" class="btn btn-white">Yes</button>
                                                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route("personel.request.action") }}" method="post">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-lg"
        ])
        @slot('modalId')
        modal_action
        @endslot
        @slot('modalTitle')
        <div class="d-flex align-items-center">
            <div class="d-flex flex-column">
                <h3 class="me-2">Request Detail</h3>
            </div>
        </div>

        @endslot
        @slot('modalContent')
            <div data-content></div>
        @endslot
        @slot('modalFooter')
        @endslot
    @endcomponent
</form>
@endsection

@section('view_script')
    <script>
        KTDrawer.createInstances();
        var drawerElement = document.querySelector("#kt_drawer_detail");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#modal_action");
        var blockUI = new KTBlockUI(target);

        function show_detail(id, date, approval){
            $("#modal_action [data-content]").html("")
            // blockUI.block();
            $.ajax({
                url : "{{route("personel.request.index")}}?a=show&id=" + id + "&date=" + date + "&approval=" + approval,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#modal_action [data-content]").html(resp.view)
                $("#modal_action").modal("show")

                $("#modal_action [data-control=select2]").select2()
            })
        }
    </script>
@endsection
