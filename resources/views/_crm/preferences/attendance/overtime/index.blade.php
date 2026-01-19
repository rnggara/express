@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card shadow-none">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Pengaturan Lembur</h3>
                    <span>Atur preferensi anda tentang lembur perusahaan</span>
                </div>
            </div>
            <div class="card-header border-0 px-0">
                <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 pt-5">
                    <li class="nav-item">
                        <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_overtime_rules">
                            <span class="nav-text">Overtime Rules</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_overtime_index">
                            <span class="nav-text">Overtime Index</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_overtime_group">
                            <span class="nav-text">Overtime Group</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="myTabContent" style="padding: 0">
                <div class="tab-pane fade show active" id="tab_overtime_rules" role="tabpanel">
                    <div class="card-body rounded p-0">
                        <form action="{{ route("crm.pref.attendance.preferences.store") }}" method="post" id="form-ovt">
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex flex-column bg-secondary-crm p-10 rounded">
                                        <div class="form-check mb-10">
                                            <input class="form-check-input" onclick="show_rounded()" name="ovt_rounded_value" {{ ($prefs->ovt_rounded_value ?? "") == 1 ? "CHECKED" : "" }} type="checkbox" value="1" id="ckm3" />
                                            <label class="form-check-label text-dark" for="ckm3">
                                                Aktifkan Overtime Rounded Value
                                            </label>
                                        </div>
                                        <!--begin::Repeater-->
                                        <div id="kt_docs_repeater_basic">
                                            <div class="d-flex justify-content-between mb-10">
                                                <h3 class="text-primary">Rounded Time Value</h3>
                                                <button type="button" data-repeater-create class="btn text-primary">
                                                    <i class="fa fa-plus text-primary"></i>
                                                    Tambah
                                                </button>
                                            </div>
                                            <!--begin::Form group-->
                                            <div class="form-group">
                                                <div data-repeater-list="range_overtime">
                                                    @if ($rots->count() > 0)
                                                        @foreach ($rots as $item)
                                                            <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                                                <div class="fv-row me-3">
                                                                    <label class="col-form-label">Range Overtime</label>
                                                                    <input type="number" name="range_from" class="form-control" value="{{ $item->range_from }}">
                                                                </div>
                                                                <div class="fv-row me-3">
                                                                    <label class="col-form-label">&nbsp;</label>
                                                                    <input type="number" name="range_mid" class="form-control" value="{{ $item->range_mid }}">
                                                                </div>
                                                                <div class="fv-row me-3">
                                                                    <label class="col-form-label">Rounded To</label>
                                                                    <input type="number" name="range_to" class="form-control" value="{{ $item->range_to }}">
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">&nbsp;</label>
                                                                    <button type="button" data-repeater-delete class="btn bg-white">
                                                                        <i class="fi fi-rr-trash text-danger"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                    <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                                        <div class="fv-row me-3">
                                                            <label class="col-form-label">Range Overtime</label>
                                                            <input type="number" name="range_from" class="form-control" value="0">
                                                        </div>
                                                        <div class="fv-row me-3">
                                                            <label class="col-form-label">&nbsp;</label>
                                                            <input type="number" name="range_mid" class="form-control" value="15">
                                                        </div>
                                                        <div class="fv-row me-3">
                                                            <label class="col-form-label">Rounded To</label>
                                                            <input type="number" name="range_to" class="form-control" value="0">
                                                        </div>
                                                        <div class="fv-row">
                                                            <label class="col-form-label">&nbsp;</label>
                                                            <button type="button" data-repeater-delete class="btn bg-white">
                                                                <i class="fi fi-rr-trash text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!--end::Form group-->
                                        </div>
                                        <!--end::Repeater-->
                                        <div class="fv-row">
                                            <label class="col-form-label">Min Overtime In Minutes</label>
                                            <input type="number" name="ovt_in_minutes" value="{{ $prefs->ovt_in_minutes ?? "" }}" class="form-control" value="60">
                                        </div>
                                        <div class="fv-row">
                                            <label class="col-form-label">Min Overtime Out in Minutes</label>
                                            <input type="number" name="ovt_out_minutes" value="{{ $prefs->ovt_out_minutes ?? "" }}" class="form-control" value="60">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="d-flex flex-column bg-secondary-crm p-5 rounded">
                                        <div class="form-check mb-10">
                                            <input class="form-check-input" name="ovt_round" {{ ($prefs->ovt_round ?? "") == 1 ? "CHECKED" : "" }} type="checkbox" value="1" id="ckm{{ rand(1000,9999) }}" />
                                            <label class="form-check-label text-dark" for="ckm{{ rand(1000,9999) }}">
                                                Aktifkan Overtime Round (Overtime IN/Overtime OUT)
                                            </label>
                                        </div>
                                        <div class="form-check mb-10">
                                            <input class="form-check-input" name="ovt_join_round" {{ ($prefs->ovt_join_round ?? "") == 1 ? "CHECKED" : "" }} type="checkbox" value="1" id="ckm{{ rand(1000,9999) }}" />
                                            <label class="form-check-label text-dark" for="ckm{{ rand(1000,9999) }}">
                                                Aktifkan Join Rounded (Overtime IN+OUT)
                                            </label>
                                        </div>
                                        <div class="form-check mb-10">
                                            <input class="form-check-input" name="ovt_split_calculate" {{ ($prefs->ovt_split_calculate ?? "") == 1 ? "CHECKED" : "" }} type="checkbox" value="1" id="ckm{{ rand(1000,9999) }}" />
                                            <label class="form-check-label text-dark" for="ckm{{ rand(1000,9999) }}">
                                                Split Calculate Index
                                            </label>
                                        </div>
                                        <div class="form-check mb-10">
                                            <input class="form-check-input" name="ovt_late_in" {{ ($prefs->ovt_late_in ?? "") == 1 ? "CHECKED" : "" }} type="checkbox" value="1" id="ckm{{ rand(1000,9999) }}" />
                                            <label class="form-check-label text-dark" for="ckm{{ rand(1000,9999) }}">
                                                Late In & Early Out Reduce Automatic Overtime
                                            </label>
                                        </div>
                                        <div class="form-check mb-10">
                                            <input class="form-check-input" name="ovt_permission" {{ ($prefs->ovt_permission ?? "") == 1 ? "CHECKED" : "" }} type="checkbox" value="1" id="ckm{{ rand(1000,9999) }}" />
                                            <label class="form-check-label text-dark" for="ckm{{ rand(1000,9999) }}">
                                                Permission Decrease Automatic Overtime
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end d-none btn-toggle mt-10">
                                    @csrf
                                    <input type="hidden" name="type" value="overtime">
                                    <input type="hidden" name="id" value="{{ $prefs->id ?? "" }}">
                                    <button class="btn btn-sm" onclick="form_reset(this)" type="button">Reset</button>
                                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_overtime_index" role="tabpanel">
                    <div class="card-body rounded bg-secondary-crm">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_overtime_index">
                                    Tambah Overtime Index
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
                                                <input class="form-check-input" type="checkbox" value="" id="ck{{ rand(1000,9999) }}" />
                                                <label class="form-check-label" for="ck{{ rand(1000,9999) }}">
                                                </label>
                                            </div>
                                        </th>
                                        <th>Overtime Index ID</th>
                                        <th>Value Index</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($indexes as $item)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="ck{{ rand(1000,9999) }}" />
                                                </div>
                                            </td>
                                            <td>{{ $item->ovt_id }}</td>
                                            <td>{{ $item->ovt_index }}</td>
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
                                                        <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('index',{{$item->id}})" class="menu-link px-3">
                                                            Edit Data
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="javascript:;" @if($item->is_default == 0) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.overtime.delete', ['type' => "index", 'id' => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->ovt_id }}" @endif class="menu-link px-3 {{ $item->is_default == 1 ? "disabled text-muted" : "" }}">
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
                <div class="tab-pane fade" id="tab_overtime_group" role="tabpanel">
                    <div class="card-body rounded bg-secondary-crm">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_overtime_group">
                                    Tambah Overtime Group
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
                                                <input class="form-check-input" type="checkbox" value="" id="ck{{ rand(1000,9999) }}" />
                                                <label class="form-check-label" for="ck{{ rand(1000,9999) }}">
                                                </label>
                                            </div>
                                        </th>
                                        <th>Overtime Group ID</th>
                                        <th>Day Code</th>
                                        <th>Overtime Group</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groups as $item)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="ck{{ rand(1000,9999) }}" />
                                                </div>
                                            </td>
                                            <td>{{ $item->group_id }}</td>
                                            <td>{{ $item->dayCode->day_name ?? "-" }}</td>
                                            <td>{{ $item->group_name }}</td>
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
                                                        <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('group',{{$item->id}})" class="menu-link px-3">
                                                            Edit Data
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="javascript:;" @if($item->is_default == 0) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.overtime.delete', ['type' => "group", 'id' => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->group_id }}" @endif class="menu-link px-3 {{ $item->is_default == 1 ? "disabled text-muted" : "" }}">
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

    <form action="{{ route("crm.pref.attendance.overtime.store") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
        ])
            @slot('modalId')
                modal_add_overtime_index
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-alarm-clock text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Tambah New Overtime Index</h3>
                        <span class="text-muted fs-base">Create New Data & Manage it!</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Overtime Index ID</label>
                        <input type="text" class="form-control" name="ovt_id" placeholder="Input Data">
                        @error('ovt_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Index</label>
                        <input type="text" class="form-control" name="ovt_index" placeholder="Input Data">
                        @error('ovt_index')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="index">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Save</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("crm.pref.attendance.overtime.store") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
        ])
            @slot('modalId')
                modal_add_overtime_group
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-alarm-clock text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Tambah Overtime Group</h3>
                        <span class="text-muted fs-base">Create New Data & Manage it!</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="row">
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Overtime Group ID</label>
                        <input type="text" class="form-control" name="group_id" placeholder="OTG004">
                        @error('group_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Day Code</label>
                        <select name="day_code" class="form-select" data-control="select2" data-dropdown-parent="#modal_add_overtime_group" data-placeholder="Select Day Code" id="">
                            <option value=""></option>
                            @foreach ($day_codes as $item)
                                <option value="{{ $item->id }}">{{ $item->day_code." - ".$item->day_name }}</option>
                            @endforeach
                        </select>
                        @error('day_code')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="fv-row">
                    <label class="col-form-label fw-bold">Overtime Group Name</label>
                    <input type="text" class="form-control" name="group_name" placeholder="Input Data">
                    @error('group_name')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mt-5">
                    <div class="form-check">
                        <input class="form-check-input" onclick="show_calculation()" name="index_kemenaker" type="checkbox" value="1" id="ck{{ rand(1000,9999) }}" />
                        <label class="form-check-label" for="ck{{ rand(1000,9999) }}">
                            Ikuti Index Pengali yang ditentukan oleh kemenaker
                        </label>
                    </div>
                </div>
                <!--begin::Repeater-->
                <div id="index_kemenaker_form" class="d-none mt-10">
                    <div class="d-flex justify-content-between mb-10 align-items-center">
                        <h3 class="text-primary">Calculation</h3>
                        <div class="flex-fill border mx-5 separator"></div>
                        <button type="button" data-repeater-create class="btn text-primary px-0">
                            <i class="fa fa-plus text-primary"></i>
                            Tambah
                        </button>
                    </div>
                    <!--begin::Form group-->
                    <div class="form-group">
                        <div data-repeater-list="calculation">
                            <div data-repeater-item class="d-flex justify-content-between align-items-center">
                                <div class="fv-row me-3">
                                    <label class="col-form-label">Index Master</label>
                                    <select name="index" class="form-select w-100px" data-kt-repeater="select2" data-placeholder="Select Index" data-dropdown-parent="#modal_add_overtime_group">
                                        @foreach ($indexes as $item)
                                            <option value="{{ $item->id }}">{{ $item->ovt_index }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row me-3">
                                    <label class="col-form-label">Range</label>
                                    <input type="time" name="range_start" class="form-control">
                                </div>
                                <div class="fv-row me-3">
                                    <label class="col-form-label">&nbsp;</label>
                                    <input type="time" name="range_end" class="form-control">
                                </div>
                                <div class="fv-row">
                                    <label class="col-form-label w-100">&nbsp;</label>
                                    <button type="button" data-repeater-delete class="btn bg-white">
                                        <i class="fi fi-rr-trash text-danger"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Form group-->
                </div>
                <!--end::Repeater-->
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="group">
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
                url : "{{route("crm.pref.attendance.overtime.detail")}}/" + type + "/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)
                $(target).find("select[data-control=select2]").select2()

                if(type == "group"){
                    show_calculation_detail()

                    repeater_form("#index_kemenaker_form_detail", function() {
                        $(this).slideDown();

                        $(this).find('[data-kt-repeater="select2"]').select2();
                    })
                }
            })
        }

        function form_reset(me){
            var form = $(me).parents("form")
            $(form).find(".btn-toggle").addClass("d-none")
            $(form).find("input").each(function(){
                if($(this).attr("name") != "_token"){
                    $(this).val($(this).data("reset"))
                }
            })
        }

        function show_calculation_detail(){
            var ck = $("#kt_drawer_detail input[name=index_kemenaker]")
            var checked = $(ck).prop("checked")
            console.log(checked)
            if(checked){
                $("#index_kemenaker_form_detail").removeClass("d-none")
            } else {
                $("#index_kemenaker_form_detail").addClass("d-none")
            }
        }

        function show_calculation(){
            var ck = $("input[name=index_kemenaker]")
            var checked = $(ck).prop("checked")
            console.log(checked)
            if(checked){
                $("#index_kemenaker_form").removeClass("d-none")
            } else {
                $("#index_kemenaker_form").addClass("d-none")
            }
        }

        function show_rounded(){
            var ck = $("input[name=ovt_rounded_value]")
            var checked = $(ck).prop("checked")
            console.log(checked)
            if(checked){
                $("#kt_docs_repeater_basic").removeClass("d-none")
            } else {
                $("#kt_docs_repeater_basic").addClass("d-none")
            }
        }

        function repeater_form(target, show){
            $(target).repeater({
                initEmpty: false,

                defaultValues: {
                    'text-input': 'foo'
                },

                show: show,

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });
        }

        $(document).ready(function(){
            $("#form-ovt input").change(function(){
                $("#form-ovt .btn-toggle").removeClass("d-none")
            })

            repeater_form("#kt_docs_repeater_basic", function () {
                $(this).slideDown();

                $("#form-ovt input").change(function(){
                    $("#form-ovt .btn-toggle").removeClass("d-none")
                })
            })

            repeater_form("#index_kemenaker_form", function() {
                $(this).slideDown();

                $(this).find('[data-kt-repeater="select2"]').select2();
            })

            $('[data-kt-repeater="select2"]').select2()

            show_rounded()

            @if(Session::has("tab"))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ Session::get("tab") }}']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif

            @if(Session::has("modal"))
                $("#{{ Session::get("modal") }}").modal("show")
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
