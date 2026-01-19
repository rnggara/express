@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Pengaturan Kelompok Kerja</h3>
                    <span>Pengaturan shift, pola kerja, dan kelompok kerja</span>
                </div>
            </div>
            <div class="card-header border-bottom-0 px-0">
                <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                    <li class="nav-item">
                        <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_shift">
                            <span class="nav-text">Shift</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_patern">
                            <span class="nav-text">Pattern</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_workgroup">
                            <span class="nav-text">Workgroup</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="tab-content" id="myTabContent" style="padding: 0">
                        <div class="tab-pane fade show active" id="tab_shift" role="tabpanel">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_shift">
                                        Tambah Shift
                                    </button>
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-reason-name">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    <label class="form-check-label" for="ck1">
                                                    </label>
                                                </div>
                                            </th>
                                            <th>Shift ID</th>
                                            <th>Color</th>
                                            <th>Shift Name</th>
                                            <th>In</th>
                                            <th>Out</th>
                                            <th>Break 1</th>
                                            <th>Break 2</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($shifts as $item)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    </div>
                                                </td>
                                                <td>{{ $item->shift_id }}</td>
                                                <td>
                                                    <div class="h-30px rounded-4" style="background-color: {{ $item->shift_color }}"></div>
                                                </td>
                                                <td>{{ $item->shift_name }}</td>
                                                <td>{{ $item->schedule_in ?? "-" }}</td>
                                                <td>{{ $item->schedule_out ?? "-" }}</td>
                                                <td>{{ $item->break_shifts[0]['start'] ?? "-" }}</td>
                                                <td>{{ $item->break_shifts[1]['start'] ?? "-" }}</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" {{ $item->status == 1 ? "checked" : "" }} disabled>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                    </button>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('shift',{{$item->id}})" class="menu-link px-3">
                                                                Edit Data
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" @if($item->is_default == 0) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.workgroup.delete', ['type' => "shift", 'id' => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->shift_id }}" @endif class="menu-link px-3 {{ $item->is_default == 1 ? "disabled text-muted" : "" }}">
                                                                Delete Data
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_patern" role="tabpanel">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <button type="button" class="btn btn-primary" onclick="generateId('patern', '#modal_add_patern input[name=patern_id]')" data-bs-toggle="modal" data-bs-target="#modal_add_patern">
                                        Tambah Pattern
                                    </button>
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-reason-condition">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    <label class="form-check-label" for="ck1">
                                                    </label>
                                                </div>
                                            </th>
                                            <th>Pattern ID</th>
                                            <th>Pattern Name</th>
                                            <th>Type</th>
                                            <th>Sequence</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paterns as $item)
                                            @php
                                                $sequence = 0;
                                                if(!empty($item->sequences) && count($item->sequences)){
                                                    foreach($item->sequences as $seq){
                                                        foreach($seq as $elseq){
                                                            if(!empty($elseq)){
                                                                $sequence++;
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    </div>
                                                </td>
                                                <td>{{$item->patern_id}}</td>
                                                <td>{{$item->patern_name}}</td>
                                                <td>{{ empty($item->patern_name) ? "-" : ($item->patern_name == 1 ? "Shifting" : "No Shifting") }}</td>
                                                <td>{{ $sequence == 0 ? "-" : $sequence }}</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" {{ $item->status == 1 ? "checked" : "" }} disabled>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                    </button>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('patern',{{$item->id}})" class="menu-link px-3">
                                                                Edit Data
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" @if($item->is_default == 0) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.workgroup.delete', ['type' => "patern", 'id' => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->patern_id }}" @endif class="menu-link px-3 {{ $item->is_default == 1 ? "disabled text-muted" : "" }}">
                                                                Delete Data
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_workgroup" role="tabpanel">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <button type="button" class="btn btn-primary" onclick="generateId('workgroup', '#modal_add_workgroup input[name=workgroup_id]')" data-bs-toggle="modal" data-bs-target="#modal_add_workgroup">
                                        Tambah Workgroup
                                    </button>
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-reason-condition">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    <label class="form-check-label" for="ck1">
                                                    </label>
                                                </div>
                                            </th>
                                            <th>Workgroup ID</th>
                                            <th>Workgroup Name</th>
                                            <th>Pattern</th>
                                            <th>Sequence</th>
                                            <th>Holiday Flag</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($workgroups as $item)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    </div>
                                                </td>
                                                <td>{{$item->workgroup_id}}</td>
                                                <td>{{$item->workgroup_name}}</td>
                                                <td>{{$item->paternData->patern_name}}</td>
                                                <td>{{$item->sequence ?? "-"}}</td>
                                                <td>
                                                    <span class='badge badge-{{$item->replace_holiday_flag == 1 ? "success" : "secondary"}}'>
                                                        {{$item->replace_holiday_flag == 1 ? "Replaced" : "Not Replaced"}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" {{ $item->status == 1 ? "checked" : "" }} disabled>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                    </button>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('workgroup',{{$item->id}})" class="menu-link px-3">
                                                                Edit Data
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" @if($item->is_default == 0) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.workgroup.delete', ['type' => "workgroup", 'id' => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->workgroup_id }}" @endif class="menu-link px-3 {{ $item->is_default == 1 ? "disabled text-muted" : "" }}">
                                                                Delete Data
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                    </div>
                                                </td>
                                            </tr>
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

    <form action="{{ route("crm.pref.attendance.workgroup.store") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
        ])
            @slot('modalId')
                modal_add_shift
            @endslot
            @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-alarm-clock text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Tambah New Shift</h3>
                    <span class="text-muted fs-base">Tambahkan reason kehadiran</span>
                </div>
            </div>
            @endslot
            @slot('modalContent')
                <div class="row">
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Shift ID</label>
                        <input type="text" name="shift_id" class="form-control" placeholder="Input Data">
                        @error('shift_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Shift Name</label>
                        <input type="text" name="shift_name" class="form-control" placeholder="Input Data">
                        @error('shift_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6" id="sel-day-code">
                        <label class="col-form-label fw-bold">Day Code</label>
                        <select name="day_code" class="form-select" data-control="select2" data-dropdown-parent="#sel-day-code" data-placeholder="Select Day Code" id="">
                            <option value=""></option>
                            @foreach ($day_codes as $item)
                                <option value="{{ $item->id }}">{{ $item->day_name }}</option>
                            @endforeach
                        </select>
                        @error('day_code')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6" id="sel-shift-color">
                        <label class="col-form-label fw-bold">Shift Color</label>
                        <div class="position-relative" data-control="colorpicker">
                            <input type="text" name="shift_color" data-colorpicker-input class="form-control" placeholder="Select Shift Color" readonly>
                            <div class="position-absolute top-0 w-100">
                                <div class="d-flex align-items-center">
                                    <span class="flex-fill h-20px ms-4 rounded-4" data-colorpicker-label></span>
                                    <div>
                                        <button type="button" class="btn btn-icon" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                        <!--begin::Menu sub-->
                                        <div class="menu-sub menu-sub-dropdown p-3 w-200px" data-kt-menu="true">
                                            <div class="row row-cols-3 pt-5">
                                                @foreach ($color_palletes as $item)
                                                    <div class="col mb-5">
                                                        <div class="symbol symbol-circle symbol-30px cursor-pointer" data-colorpicker-toggle data-color="{{ $item }}">
                                                            <div class="symbol-label" style="background-color: {{ $item }}">&nbsp;</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('shift_color')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Schedule In</label>
                        <input type="time" name="time_in" class="form-control" placeholder="Input Data">
                    </div>
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Schedule Out</label>
                        <input type="time" name="time_out" class="form-control" placeholder="Input Data">
                    </div>
                </div>
                <div class="fv-row mt-5">
                    <div class="d-flex flex-column mb-5 repeater">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" name="add_break_shift" onclick="add_bs(this)" type="checkbox" value="1" id="ckm1" />
                                <label class="form-check-label" for="ckm1">
                                    Tambahkan Break Shift
                                </label>
                            </div>
                            <button type="button" class="btn text-primary d-none break-shift" data-repeater-create>
                                <i class="fa fa-plus text-primary"></i>
                                Tambah
                            </button>
                        </div>
                        <div class="form-group d-none break-shift">
                            <div data-repeater-list="break_shift">
                                <div class="row" data-repeater-item>
                                    <div class="col-5">
                                        <div class="fv-row">
                                            <label for="" class="col-form-label">Break <span class="break-num"></span> Start</label>
                                            <input type="time" name="start" class="form-control" id="">
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="fv-row">
                                            <label for="" class="col-form-label">Break <span class="break-num"></span> End</label>
                                            <input type="time" name="end" class="form-control" id="">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="fv-row">
                                            <label for="" class="col-form-label w-100">&nbsp;</label>
                                            <button data-repeater-delete class="bg-hover-light-danger bg-white btn btn-icon" type="button">
                                                <i class="fi fi-rr-trash text-danger"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" name="automatic_overtime" type="checkbox" onclick="add_automatic_overtime(this)" value="1" id="ckm2" />
                        <label class="form-check-label" for="ckm2">
                            Tambahkan Automatic Overtime
                        </label>
                    </div>
                    <div class="d-none automatic-overtime">
                        <div class="row">
                            <div class="col-6">
                                <div class="fv-row">
                                    <label for="" class="col-form-label">Automatic Overtime In</label>
                                    <input type="time" name="overtime_in" class="form-control" id="">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="fv-row">
                                    <label for="" class="col-form-label">Automatic Overtime Out</label>
                                    <input type="time" name="overtime_out" class="form-control" id="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="shift">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Save</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("crm.pref.attendance.workgroup.store") }}" method="post">
        <div class="modal fade" tabindex="-1" id="modal_add_patern">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content px-10">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="card">
                                    <div class="card-header border-0 px-0">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px me-5">
                                                    <div class="symbol-label bg-light-primary">
                                                        <span class="fi fi-rr-calendar text-primary"></span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <h3 class="me-2">Tambah Pattern</h3>
                                                    <span class="text-muted fs-base">Easy create & custom patern</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body rounded bg-secondary-crm">
                                        <div class="row mb-5">
                                            <div class="fv-row col-12 col-md-4">
                                                <label class="col-form-label fw-bold">Pattern ID</label>
                                                <input type="text" name="patern_id" readonly class="form-control" placeholder="PP05">
                                            </div>
                                            <div class="fv-row col-12 col-md-4">
                                                <label class="col-form-label fw-bold">Pattern Name</label>
                                                <input type="text" name="patern_name" class="form-control" placeholder="Input Data">
                                                @error('patern_name')
                                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="fv-row col-12 col-md-4" id="sel-shifting-type">
                                                <label class="col-form-label fw-bold">Shifting Type</label>
                                                <select name="shifting_type" class="form-select" data-control="select2" data-dropdown-parent="#sel-shifting-type" data-placeholder="Select Type" id="">
                                                    <option value=""></option>
                                                    <option value="1">Shifting</option>
                                                    <option value="-1">No Shifting</option>
                                                </select>
                                                @error('shifting_type')
                                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="d-flex bg-white rounded p-2 justify-content-between mb-5">
                                            @php
                                                $day = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]
                                            @endphp
                                            @foreach ($day as $i => $item)
                                            <div class="d-flex flex-column h-40px justify-content-center w-40px">
                                                <span class="text-center">{{ $i + 1 }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="d-flex flex-column repeater">
                                            <div class="form-group">
                                                <div data-repeater-list="sequences">
                                                    <div class="row" data-repeater-item>
                                                        <div class="d-flex justify-content-between mb-5">
                                                            @foreach ($day as $i => $item)
                                                                <input type="hidden" name="cell[{{ $i }}]">
                                                                <button type="button" data-repeater-shift onclick="openModalShift(this, '#modal_add_patern')" class="bg-white border border-5 border-white btn btn-icon btn-lg rounded btn-lg">
                                                                    <i class="fi fi-rr-plus text-primary"></i>
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-white d-flex justify-content-center rounded">
                                                <button type="button" class="btn text-primary" data-repeater-create>
                                                    <i class="fa fa-plus text-primary"></i>
                                                    Tambah Sequence
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card">
                                    <div class="card-header border-0 px-0">
                                        <div class="card-title">
                                            <div class="d-flex flex-column">
                                                <h3 class="me-2">Template Pattern</h3>
                                                <span class="text-muted fs-base">Select Pattern to make easy</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body rounded border mb-5">
                                        <div class="d-flex justify-content-between">
                                            <span class="fs-3 fw-bold">Reguler Day</span>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" value="" id="ckck1" />
                                                <label class="form-check-label" for="ckck1">
                                                </label>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            @for($i = 0; $i < 4; $i++)
                                                <div class="col-12 d-flex justify-content-between mb-3">
                                                    @for ($j = 0; $j < 7; $j++)
                                                        <div class="w-20px h-20px bg-{{ $j < 5 ? "warning" : "secondary" }}"></div>
                                                    @endfor
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="card-body rounded border">
                                        <div class="d-flex justify-content-between">
                                            <span class="fs-3 fw-bold">Reguler 6-1 Day</span>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" value="" id="ckck1" />
                                                <label class="form-check-label" for="ckck1">
                                                </label>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            @for($i = 0; $i < 4; $i++)
                                                <div class="col-12 d-flex justify-content-between mb-3">
                                                    @for ($j = 0; $j < 7; $j++)
                                                        <div class="w-20px h-20px bg-{{ $j < 6 ? "warning" : "secondary" }}"></div>
                                                    @endfor
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-0 modal-footer">
                        @csrf
                        <input type="hidden" name="type" value="patern">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" tabindex="-1" id="modal_select_shift">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content px-10">
                <div class="modal-body">
                    <div class="fv-row">
                        <label for="" class="col-form-label">Shift</label>
                        <select name="shift" id="" class="form-select" data-control="select2" data-dropdown-parent="#modal_select_shift" data-placeholder="Select Shift">
                            <option value=""></option>
                            @foreach ($shifts as $item)
                                <option value="{{$item->id}}" data-id="{{ $item->shift_id }}" data-color="{{ $item->shift_color }}">{{ $item->shift_id." - ".$item->shift_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mt-5">
                        <input type="hidden" name="target">
                        <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" onclick="selectShift(this)" class="btn btn-primary btn-sm">Select Shift</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route("crm.pref.attendance.workgroup.store") }}" method="post">
        @component('layouts._crm_modal', [
                'modalSize' => "modal-lg"
            ])
            @slot('modalId')
                modal_add_workgroup
            @endslot
            @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-calendar text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Tambah Workgroup</h3>
                    <span class="text-muted fs-base">Create New Category & Manage it!</span>
                </div>
            </div>
            @endslot
            @slot('modalContent')
                <div class="row">
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Workgroup ID</label>
                        <input type="text" name="workgroup_id" readonly class="form-control" placeholder="Input Data">
                        @error('workgroup_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Workgroup Name</label>
                        <input type="text" name="workgroup_name" class="form-control" placeholder="Input Data">
                        @error('workgroup_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Start Date</label>
                        <input type="date" name='start_date' class="form-control" placeholder="Select Date">
                        @error('start_date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-4" id="sel-wg-type-patern">
                        <label class="col-form-label fw-bold">Type Pattern</label>
                        <select name="patern" class="form-select" data-control="select2" data-dropdown-parent="#sel-wg-type-patern" data-placeholder="Select Pattern" id="">
                            <option value=""></option>
                            @foreach($paterns as $item)
                                <option value="{{$item->id}}">{{$item->patern_name}}</option>
                            @endforeach
                        </select>
                        @error('patern')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Sequence</label>
                        <input type="number" name="sequence" class="form-control" value="0" placeholder="0">
                    </div>
                </div>
                <div class="fv-row mt-5">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" name="replace_holiday_flag" id="ckm123" />
                        <label class="form-check-label" for="ckm123">
                            Centang untuk me-non-aktifkan tabel Libur
                        </label>
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="workgroup">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Save</button>
            @endslot
        @endcomponent
    </form>

<div
    id="kt_drawer_detail"

    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#kt_drawer_detail_button"
    data-kt-drawer-close="#kt_drawer_detail_close"
    data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default : '50%', md: '50%', sm: '500px'}">
    <div class="card rounded-0 w-100" id="drawer-content">

    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                    <span class="fw-bold fs-3">Are you sure want to delete?</span>
                    <span class="text-center">Are you sure you want to delete <span id="delete-label"></span>? This action cannot be undone and will impact employee attendance settings.</span>
                    <div class="d-flex align-items-center mt-5">
                        <a href="" id="delete-url" class="btn btn-white">Yes</a>
                        <button class="btn btn-primary" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('view_script')
    <script src="{{ asset("theme/assets/plugins/custom/formrepeater/formrepeater.bundle.js") }}"></script>
    <script>

        function openModalShift(me, parent){
            $("#modal_select_shift").modal("show")
            var input = $(me).prev()
            var target = parent + " input[name='"+$(input).attr("name")+"']"
            $("#modal_select_shift input[name=target]").val(target)
        }

        function selectShift(me){
            var modal = $(me).parents(".modal")
            var target = $(modal).find("input[name=target]").val()
            var shift = $(modal).find("select[name=shift]")

            var selectedShift = $(shift).val()
            var sId = $(shift).find("option:selected").data("id")
            var scolor = $(shift).find("option:selected").data("color")

            $(`${target}`).val(selectedShift)
            var textColor = 'text-white'
            if(sId == "OFF"){
                textColor = 'text-dark'
            }
            $(`${target}`).next().html("<span class='"+textColor+"'>"+sId+"</span>")
            $(`${target}`).next().removeClass("bg-white")
            $(`${target}`).next().removeClass("btn-lg")
            $(`${target}`).next().css("background-color", scolor)

            $(shift).val("").trigger("change")

            $(modal).modal("hide")
        }

        function generateId(type, target){
            $.ajax({
                url : "{{ route("crm.pref.attendance.workgroup.index") }}?a=generate_id&t=" + type,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $(target).val(resp.id)
            })
        }

        function archiveItem(me){
            var _id = $(me).data("id")
            var uri = $(me).data("url")
            console.log($(me).data('name'))
            $("#delete-label").text($(me).data("name"))
            $("#delete-url").attr("href", uri)
        }

        KTDrawer.createInstances();
        var drawerElement = document.querySelector("#kt_drawer_detail");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");
        var blockUI = new KTBlockUI(target);

        function show_detail(type, id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{route("crm.pref.attendance.workgroup.detail")}}/" + type + "/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)

                $("#kt_drawer_detail [data-control=select2]").select2()

                repeater_form('#kt_drawer_detail')

                KTMenu.createInstances();

                $('#kt_drawer_detail [data-control="colorpicker"]').each(function(){
                    var f = $(this)
                    $(this).find('[data-colorpicker-toggle]').click(function(){
                        var val = $(this).data('color')

                        $(f).find('[data-colorpicker-label]').css("background-color", val)
                        $(f).find('[data-colorpicker-input]').val(val)

                        $(f).find('[data-kt-menu-trigger="click"]').trigger('click')
                    })
                })
            })
        }

        function add_bs(me){
            var ck = $(me).prop("checked")
            if(ck){
                $(me).parents("form").find(".break-shift").removeClass("d-none")
            } else {
                $(me).parents("form").find(".break-shift").addClass("d-none")
            }
        }

        function add_automatic_overtime(me){
            var ck = $(me).prop("checked")
            if(ck){
                $(me).parents("form").find(".automatic-overtime").removeClass("d-none")
            } else {
                $(me).parents("form").find(".automatic-overtime").addClass("d-none")
            }
        }

        function repeater_form(head = null){
            var target = ".repeater"
            if(head != null){
                target = head + " .repeater"
            }
            $(target).each(function(){
                var me = $(this)
                $(this).repeater({
                    initEmpty : false,
                    defaultValues : {
                        'text-input' : ""
                    },
                    show : function(){
                        $(this).slideDown();
                        $(this).find('[data-kt-repeater="select2"]').select2()
                        $(me).find('[data-repeater-item]').each(function(i){
                            $(this).find(".break-num").text(i + 1)
                        })
                        $(this).find('[data-repeater-shift]').last().each(function(){
                            var items = $(this).parents("[data-repeater-item]")
                            $(items).find('[data-repeater-shift]').each(function(){
                                $(this).html("<i class='fa fa-plus text-primary'></i>")
                                $(this).addClass("bg-white")
                                $(this).addClass("btn-lg")
                                $(this).css("backgroun-color", "")
                            })
                        })
                    },
                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                        $(me).find('[data-repeater-item]').each(function(i){
                            $(this).find(".break-num").text(i + 1)
                        })
                    },
                    ready: function(){
                        $(me).find('[data-kt-repeater="select2"]').select2()
                        $(me).find('[data-repeater-item]').each(function(i){
                            $(this).find(".break-num").text(i + 1)
                        })

                        $(me).find("[data-repeater-shift]").each(function(){

                        })
                    }
                })
            })
        }

        $(document).ready(function(){
            repeater_form()
            $('[data-control="colorpicker"]').each(function(){
                var f = $(this)
                $(this).find('[data-colorpicker-toggle]').click(function(){
                    var val = $(this).data('color')

                    $(f).find('[data-colorpicker-label]').css("background-color", val)
                    $(f).find('[data-colorpicker-input]').val(val)

                    $(f).find('[data-kt-menu-trigger="click"]').trigger('click')
                })
            })

            @if(Session::has("modal"))
                $("#{{ Session::get("modal") }}").modal("show")
                @if(Session::get("modal") == "modal_add_patern")
                    generateId("patern", "#modal_add_patern input[name=patern_id]")
                @elseif(Session::get("modal") == "modal_add_workgroup")
                    generateId("patern", "#modal_add_workgroup input[name=workgroup_id]")
                @endif
            @endif

            @if(Session::has("tab"))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ Session::get("tab") }}']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif

            @if(Session::has("drawer"))
                @if(Session::get("drawer") != null)
                    drawer.show()
                    show_detail('{{Session::get("type")}}', {{Session::get("drawer")}})
                @endif
            @endif
        })

    </script>
@endsection
