@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Pengaturan Alasan</h3>
                    <span>Alasan yang digunakan untuk mengidentifikasi absensi</span>
                </div>
            </div>
            <div class="card-header border-bottom-0 px-0">
                <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                    <li class="nav-item">
                        <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_reason_name">
                            <span class="nav-text">Alasan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_reason_condition">
                            <span class="nav-text">Kondisi Alasan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_reason_grouping">
                            <span class="nav-text">Group Alasan</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="tab-content" id="myTabContent" style="padding: 0">
                        <div class="tab-pane fade show active" id="tab_reason_name" role="tabpanel">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_reason_name">
                                        Tambah Alasan
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
                                            <th>Color</th>
                                            <th>Reason Name ID</th>
                                            <th>Reason Name</th>
                                            <th>Status</th>
                                            <th>Dashboard</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reason_names as $item)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="h-30px rounded-4" style="background-color: {{ $item->color }}"></div>
                                                </td>
                                                <td>{{ $item->reason_id }}</td>
                                                <td>{{ $item->reason_name }}</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" {{ $item->status == 1 ? "checked" : "" }} disabled>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" {{ $item->status == 1 && $item->show_dashboard == 1 ? "checked" : "" }} disabled>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                    </button>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_periode" onclick="show_detail('name',{{$item->id}})" class="menu-link px-3">
                                                                Edit Data
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" @if($item->is_default == 0) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.reason_name.delete', ['type' => "name", 'id' => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->name }}" @endif class="menu-link px-3 {{ $item->is_default == 1 ? "disabled text-muted" : "" }}">
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
                        <div class="tab-pane fade" id="tab_reason_condition" role="tabpanel">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_reason_condition">
                                        Tambah Kondisi Alasan
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
                                            <th>ID</th>
                                            <th>Reason Type</th>
                                            <th>Reason Name</th>
                                            <th>Sequence</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rconditions as $item)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    </div>
                                                </td>
                                                <td>{{ sprintf("%03d", $item->id) }}</td>
                                                <td>{{ $item->reasonType->name }}</td>
                                                <td>{{ $item->reasonName->reason_name }}</td>
                                                <td>{{ $item->process_sequence }}</td>
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
                                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_periode" onclick="show_detail('condition',{{$item->id}})" class="menu-link px-3">
                                                                Edit Data
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" @if($item->is_default == 0) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.reason_name.delete', ['type' => "condition", 'id' => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->reasonName->reason_name." - ".$item->reasonType->name }}" @endif class="menu-link px-3 {{ $item->is_default == 1 ? "disabled text-muted" : "" }}">
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
                        <div class="tab-pane fade" id="tab_reason_grouping" role="tabpanel">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_group">
                                        Tambah Group Alasan
                                    </button>
                                </div>
                                <div class="d-flex scroll-x gap-5">
                                    @if ($rgroup->count() == 0)
                                        <div class="text-center">
                                            <span class="fs-3 fw-bold">No Group Data</span>
                                        </div>
                                    @else
                                        @foreach ($rgroup as $item)
                                            <div class="card min-w-300px  p-5 bg-white shadow-none">
                                                <div class="card-body px-2 py-3">
                                                    <div class="d-flex flex-column w-100 h-100 gap-3">
                                                        <div class="p-3 rounded d-flex justify-content-between align-items-center" style="background-color: {{ $item->color }}">
                                                            <span class="fw-bold fs-3 text-white">{{ $item->group_name }}</span>
                                                            <div>
                                                                <button type="button" onclick="addReasonGroup({{ $item->id }})" class="btn btn-icon text-white">
                                                                    <i class="fi fi-rr-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="flex-fill scroll-y mh-500px d-flex flex-column align-items-center gap-3">
                                                            @if (empty($item->reasons))
                                                                <span>No Data</span>
                                                            @else
                                                                @foreach ($item->reasons ?? [] as $r)
                                                                    @if (isset($rname[$r]))
                                                                        <div class="p-3 rounded d-flex bg-secondary-crm justify-content-between align-items-center w-100">
                                                                            <span class="">{{ $rname[$r] }}</span>
                                                                            <div>
                                                                                <button type="button" class="btn btn-icon" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                                                    <i class="fi fi-rr-menu-dots-vertical"></i>
                                                                                </button>
                                                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                                                    <!--begin::Menu item-->
                                                                                    <div class="menu-item px-3">
                                                                                        <a href="javascript:;" data-bs-toggle="modal" onclick="archiveGroup(this)" data-bs-target="#modalDelGroup" data-url="{{ route('crm.pref.attendance.reason_name.delete', ['type' => "group-non", 'id' => $r]) }}" data-name="Non Aktifkan {{ $rname[$r] }}" class="menu-link px-3">
                                                                                            Non Aktifkan
                                                                                        </a>
                                                                                    </div>
                                                                                    <!--end::Menu item-->
                                                                                    <!--begin::Menu item-->
                                                                                    <div class="menu-item px-3">
                                                                                        <a href="javascript:;" data-bs-toggle="modal" onclick="archiveGroup(this)" data-bs-target="#modalDelGroup" data-url="{{ route('crm.pref.attendance.reason_name.delete', ['type' => "group-del", 'id' => $item->id."_".$r]) }}" data-name="Remove {{ $rname[$r] }}" class="text-danger menu-link px-3">
                                                                                            Delete Data
                                                                                        </a>
                                                                                    </div>
                                                                                    <!--end::Menu item-->
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <form action="{{ route("crm.pref.attendance.reason_name.store") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
        ])
            @slot('modalId')
                modal_add_reason_name
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-settings text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Tambah Alasan</h3>
                        <span class="text-muted fs-base">Tambahkan reason kehadiran</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="row row-gap-5">
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">Reason Color</label>
                        <div class="position-relative" data-control="colorpicker">
                            <input type="text" name="reason_color" data-colorpicker-input class="form-control" placeholder="Select Shift Color" readonly>
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
                                                @foreach (\Config::get("constants.COLOR_PALLET") ?? [] as $item)
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
                        @error('reason_color')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">Reason Name ID</label>
                        <input type="text" name="reason_id" class="form-control" placeholder="Input Data">
                        @error('reason_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">Reason Name</label>
                        <input type="text" name="reason_name" class="form-control" placeholder="Input Data">
                        @error('reason_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="show_dashboard" value="1" />
                                Tampilkan Reason pada Dashboard Preview Today
                            </label>
                        </div>
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="name">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("crm.pref.attendance.reason_name.store") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
        ])
            @slot('modalId')
                modal_add_group_reason
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-sr-alarm-clock text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Group Alasan</h3>
                        <span class="text-muted fs-base">Tambahkan Alasan ke dalam grop untuk ditampilkan pada attendance track</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div data-group-content></div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="group_add">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("crm.pref.attendance.reason_name.store") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
        ])
            @slot('modalId')
                modal_add_group
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-settings text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Tambah Group Alasan</h3>
                        <span class="text-muted fs-base">Tambahkan Group Alasan</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="row row-gap-5">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Group Color</label>
                        <div class="position-relative" data-control="colorpicker">
                            <input type="text" name="color" data-colorpicker-input class="form-control" placeholder="Select Group Color" readonly>
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
                                                @foreach (\Config::get("constants.COLOR_PALLET") ?? [] as $item)
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
                        @error('color')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Group Name</label>
                        <input type="text" name="group_name" class="form-control" placeholder="Input Data">
                        @error('group_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="group">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("crm.pref.attendance.reason_name.store") }}" method="post">

        <div class="modal fade" tabindex="-1" id="modal_add_reason_condition">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content px-10">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12" data-form="main">
                                <div class="card">
                                    <div class="card-header border-0 px-0">
                                        <div class="card-title">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px me-5">
                                                    <div class="symbol-label bg-light-primary">
                                                        <span class="fi fi-rr-alarm-clock text-primary"></span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <h3 class="me-2">Tambah Kondisi Alasan</h3>
                                                    <span class="text-muted fs-base">Create New & Manage it!</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body rounded bg-secondary-crm">
                                        <div class="row">
                                            <div class="fv-row col-12 col-md-6" id="sel-reason-name">
                                                <label class="col-form-label fw-bold">Reason Name</label>
                                                <select name="reason_name_id" class="form-select" data-toggle="form" data-control="select2" data-dropdown-parent="#sel-reason-name" data-placeholder="Select Type">
                                                    <option value=""></option>
                                                    @foreach ($reason_names as $item)
                                                        <option value="{{ $item->id }}">{{ $item->reason_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('reason_name_id')
                                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="fv-row col-12 col-md-6" id="sel-reason-type">
                                                <label class="col-form-label fw-bold">Reason Type</label>
                                                <select name="reason_type" class="form-select" data-toggle="form" data-control="select2" data-dropdown-parent="#sel-reason-type" data-placeholder="Select Type">
                                                    <option value=""></option>
                                                    @foreach ($reason_types as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('reason_type')
                                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="fv-row col-12 col-md-4">
                                                <label class="col-form-label fw-bold">Process Sequence</label>
                                                <input type="number" name="process_sequence" class="form-control" data-toggle="form" placeholder="Input Data">
                                                @error('process_sequence')
                                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="fv-row col-12 col-md-4">
                                                <label class="col-form-label fw-bold">Reason Sequence</label>
                                                <input type="number" name="reason_sequence" class="form-control" data-toggle="form" placeholder="Input Data">
                                                @error('reason_sequence')
                                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="fv-row col-12 col-md-4">
                                                <label class="col-form-label fw-bold">Report Type</label>
                                                <select name="report_type" class="form-select" data-toggle="form" data-control="select2" data-dropdown-parent="#sel-reason-type" data-placeholder="Select Type">
                                                    <option value=""></option>
                                                    @foreach ($report_types as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('report_type')
                                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="fv-row mt-5">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" onclick="reasonLeave(this)" name="cut_leave" value="1" id="ckm1" />
                                                <label class="form-check-label" for="ckm1">
                                                    Reason memotong Kuota Cuti
                                                </label>
                                            </div>
                                            <div class="mb-5 d-none" data-reason-leave>
                                                <div class="row">
                                                    <div class="fv-row col-6">
                                                        <label class="col-form-label fw-bold">Leave Type</label>
                                                        <select name="leave_type" class="form-select" data-control="select2" data-dropdown-parent="#modal_add_reason_condition" data-placeholder="Select Type">
                                                            <option value=""></option>
                                                            @foreach ($leave_types as $item)
                                                                <option value="{{ $item->leave_used }}">{{ $item->leave_reason_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="col-form-label fw-bold">Total Day</label>
                                                        <input type="number" name="leave_days" class="form-control" min="0" step=".01" id="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="ess" value="1" id="ckm2" />
                                                <label class="form-check-label" for="ckm2">
                                                    Hubungkan dengan Aplikasi ESS
                                                </label>
                                            </div>
                                            <div class="fv-row d-none mb-5" data-form="additional">
                                                <label class="col-form-label fw-bold">Condition</label>
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" name="condition_formula" placeholder="Select Formula From Library Board" readonly>
                                                    <div class="position-absolute top-25 d-flex align-items-center mx-5">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" onclick="reasonPengganti(this)" name="reason_pengganti" value="1" id="ckm3" />
                                                <label class="form-check-label" for="ckm3">
                                                    Reason Pengganti
                                                </label>
                                            </div>
                                            <div class="mb-5 d-none" data-reason-pengganti id="sel-reason-pengganti">
                                                <div class="fv-row">
                                                    <select name="rp_detail[]" class="form-select" data-placeholder="Select Reason Pengganti" multiple data-control="select2" data-dropdown-parent="#sel-reason-pengganti" id="">
                                                        @foreach ($rconditions->where("status", 1) as $item)
                                                            <option value="{{ $item->id }}">{{ $item->reasonName->reason_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 d-none" data-form="additional">
                                <div class="card h-500px scroll scroll-pe">
                                    <div class="card-header border-0 px-0">
                                        <div class="card-title">
                                            <div class="d-flex flex-column">
                                                <h3 class="me-2">Custom Reason Board</h3>
                                                <span class="text-muted fs-base">Select & Input Board To Formula</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body rounded border mb-5">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Schedule</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[schedule]" onclick="switch_on(this)" data-color="primary" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <select name="schedule_id" class="form-select" data-control="select2" data-dropdown-parent="#modal_add_reason_condition" data-placeholder="Select Schedule">
                                                <option value=""></option>
                                                @foreach ($day_codes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->day_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-body rounded border mb-5">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Shift Code</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[shift_code]" onclick="switch_on(this)" data-color="info" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <select name="shift_code[]" class="form-select" multiple data-control="select2" data-dropdown-parent="#modal_add_reason_condition" data-placeholder="Select Sift">
                                                <option value=""></option>
                                                @foreach ($shifts as $item)
                                                    <option value="{{ $item->id }}">{{ $item->shift_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-body rounded border mb-5">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Time In</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[time_in]" onclick="switch_on(this)" data-color="warning" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <select name="time_in[condition]" class="form-select me-2" data-control="select2" data-dropdown-parent="#modal_add_reason_condition">
                                                @foreach (\Config::get("constants.reason_operator") ?? [] as $opt => $op)
                                                    <option value="{{ $opt }}">{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            <input type="time" name="time_in[time]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="card-body rounded border mb-5">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Time Out</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[time_out]" onclick="switch_on(this)" data-color="danger" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <select name="time_out[condition]" class="form-select me-2" data-control="select2" data-dropdown-parent="#modal_add_reason_condition">
                                                @foreach (\Config::get("constants.reason_operator") ?? [] as $opt => $op)
                                                    <option value="{{ $opt }}">{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            <input type="time" name="time_out[time]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="card-body rounded border mb-5">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Late In</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[late_in]" onclick="switch_on(this)" data-color="light-warning" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <select name="late_in[condition]" class="form-select me-2" data-control="select2" data-dropdown-parent="#modal_add_reason_condition">
                                                @foreach (\Config::get("constants.reason_operator") ?? [] as $opt => $op)
                                                    <option value="{{ $opt }}">{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            <input type="time" name="late_in[time]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="card-body rounded border mb-5">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Fast Out</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[fast_out]" onclick="switch_on(this)" data-color="light-danger" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <select name="fast_out[condition]" class="form-select me-2" data-control="select2" data-dropdown-parent="#modal_add_reason_condition">
                                                @foreach (\Config::get("constants.reason_operator") ?? [] as $opt => $op)
                                                    <option value="{{ $opt }}">{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            <input type="time" name="fast_out[time]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="card-body rounded border mb-5">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Overtime</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[overtime]" onclick="switch_on(this)" data-color="light-primary" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <select name="overtime[condition]" class="form-select me-2" data-control="select2" data-dropdown-parent="#modal_add_reason_condition">
                                                @foreach (\Config::get("constants.reason_operator") ?? [] as $opt => $op)
                                                    <option value="{{ $opt }}">{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            <input type="time" name="overtime[time]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-0 modal-footer">
                        @csrf
                        <input type="hidden" name="type" value="condition">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

<div
    id="kt_drawer_periode"

    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#kt_drawer_periode_button"
    data-kt-drawer-close="#kt_drawer_periode_close"
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
                    <span class="fw-bold fs-3 text-center">Are you sure want to delete?</span>
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

<div class="modal fade" tabindex="-1" id="modalDelGroup">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                    <span class="fw-bold fs-3 text-center">Are you sure want to <span data-lbl></span>?</span>
                    <div class="d-flex align-items-center mt-5">
                        <a href="" data-url class="btn btn-white">Yes</a>
                        <button class="btn btn-primary" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('view_script')
    <script>

        function addReasonGroup(id){
            $("#modal_add_group_reason [data-group-content]").html("")
            $.ajax({
                url : "{{ route("crm.pref.attendance.reason_name.index") }}?a=reason_group&id=" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#modal_add_group_reason [data-group-content]").html(resp.view)
                $("#modal_add_group_reason").modal("show")

                $("#modal_add_group_reason input[name=search_reason]").on("keyup", function(){
                    var s = $(this).val()
                    var content = $("#modal_add_group_reason [data-content]")
                    var items = $(content).find("[data-item]")
                    $(items).each(function(){
                        var spn = $(this).find("span").text()
                        $(this).removeClass("d-none")
                        if(!spn.toLowerCase().includes(s)){
                            $(this).addClass("d-none")
                        }
                    })
                })
            })
        }

        function archiveGroup(me){
            var _id = $(me).data("id")
            var uri = $(me).data("url")
            $("#modalDelGroup [data-lbl]").text($(me).data("name"))
            $("#modalDelGroup [data-url]").attr("href", uri)
        }

        function reasonLeave(me){
            var par = $(me).parents(".fv-row")
            var ck = $(me).prop("checked")
            $(par).find("[data-reason-leave]").addClass("d-none")
            if(ck){
                $(par).find("[data-reason-leave]").removeClass("d-none")
            }
        }

        function reasonPengganti(me){
            var par = $(me).parents(".fv-row")
            var ck = $(me).prop("checked")
            $(par).find("[data-reason-pengganti]").addClass("d-none")
            if(ck){
                $(par).find("[data-reason-pengganti]").removeClass("d-none")
            }
        }

        function archiveItem(me){
            var _id = $(me).data("id")
            var uri = $(me).data("url")
            console.log($(me).data('name'))
            $("#delete-label").text($(me).data("name"))
            $("#delete-url").attr("href", uri)
        }

        KTDrawer.createInstances();
        var drawerElement = document.querySelector("#kt_drawer_periode");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");
        var blockUI = new KTBlockUI(target);

        function show_detail(type, id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{route("crm.pref.attendance.reason_name.detail")}}/" + type + "/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)

                $("#kt_drawer_periode [data-control=select2]").select2()

                KTMenu.createInstances();

                $('#kt_drawer_periode [data-control="colorpicker"]').each(function(){
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

        function switch_on(me){
            var conditions = []
            $(me).parents("form").find('[data-toggle="switch"]').each(function(){
                var ck = $(this).prop("checked")
                if(ck){
                    var col = {}
                    col['label'] = $(this).parent().prev().text()
                    col['bg'] = $(this).data("color")
                    conditions.push(col)
                }
            })

            var target = $(me).parents("form").find("input[name=condition_formula]").next()
            var el = ``
            for (let i = 0; i < conditions.length; i++) {
                const element = conditions[i];
                el += `<span class="badge badge-${element['bg']} me-1">${element['label']}</span>`
            }

            $(me).parents("form").find("input[name=condition_formula]").attr("placeholder", "Select Formula From Library Board")

            if(el != ""){
                $(me).parents("form").find("input[name=condition_formula]").attr("placeholder", "")
            }

            $(target).html(el)
        }

        $(document).ready(function(){

            $("#modal_add_reason_condition [data-toggle='form']").change(function(){
                var cnt = 0
                $("#modal_add_reason_condition [data-toggle='form']").each(function(){
                    if($(this).val() != ""){
                        cnt++
                    }
                })

                $('#modal_add_reason_condition [data-form="main"]').addClass("col-12")
                $('#modal_add_reason_condition [data-form="additional"]').addClass("d-none")

                if(cnt == $("#modal_add_reason_condition [data-toggle='form']").length){
                    $('#modal_add_reason_condition [data-form="main"]').removeClass("col-12")
                    $('#modal_add_reason_condition [data-form="main"]').addClass("col-8")
                    $('#modal_add_reason_condition [data-form="additional"]').removeClass("d-none")
                }
            })

            $('[data-control="colorpicker"]').each(function(){
                var f = $(this)
                $(this).find('[data-colorpicker-toggle]').click(function(){
                    var val = $(this).data('color')

                    $(f).find('[data-colorpicker-label]').css("background-color", val)
                    $(f).find('[data-colorpicker-input]').val(val)

                    $(f).find('[data-kt-menu-trigger="click"]').trigger('click')
                })
            })

            // @if($errors->any())
            //     @if($errors->has("reason_id") || $errors->has("reason_name"))
            //         var triggerEl = $("a[data-bs-toggle='tab'][href='#tab_reason_name']")
            //         bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            //     @else
            //         var triggerEl = $("a[data-bs-toggle='tab'][href='#tab_reason_condition']")
            //         bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            //     @endif
            // @endif

            @if(Session::has("modal"))
                $("#{{ Session::get("modal") }}").modal("show")
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
