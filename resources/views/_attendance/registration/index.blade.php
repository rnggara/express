@extends('_attendance.layout')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="d-flex align-items-center mb-5">
            <div class="symbol symbol-50px me-3">
                <div class="symbol-label bg-primary">
                    <i class="fi fi-rr-comment-user text-white fs-1"></i>
                </div>
            </div>
            <div class="d-flex flex-column">
                <span class="fs-3 fw-bold">Registrasi Absensi</span>
                <span class="text-muted">Manajemen & Assign Employe ke Workgroup </span>
            </div>
        </div>
        <div class="d-flex flex-column">
            <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                <li class="nav-item">
                    <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_general">
                        <span class="nav-text">General</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_history_change">
                        <span class="nav-text">History Change</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab_general" role="tabpanel">
                    <div class="card bg-secondary-crm">
                        <div class="card-body">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="position-relative me-5">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#kt_accordion_filter_body_1">
                                        <i class="fi fi-rr-filter"></i>
                                        Filter
                                    </button>
                                </div>
                                <div class="accordion mb-5" id="kt_accordion_filter">
                                    <div class="accordion-item bg-transparent border-0">
                                        <div id="kt_accordion_filter_body_1" class="accordion-collapse collapse" aria-labelledby="kt_accordion_filter_header_1" data-bs-parent="#kt_accordion_1">
                                            <div class="accordion-body px-0">
                                                <div class="d-flex align-items-center">
                                                    <select name="fworkgroup" class="form-select" onchange="ftable(this,2)" data-control="select2" data-placeholder="Select Workgroup" data-allow-clear="true" id="">
                                                        <option value=""></option>
                                                        @foreach ($workgroups as $item)
                                                            <option value="{{ $item->workgroup_name }}">{{ $item->workgroup_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="mx-3"></div>
                                                    <select name="fdepartement" class="form-select" onchange="ftable(this,1)" data-control="select2" data-placeholder="Select Departement" data-allow-clear="true" id="">
                                                        <option value=""></option>
                                                        @foreach ($dept as $item)
                                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-general">
                                    <thead>
                                        <tr>
                                            <th>
                                                Employee ID
                                            </th>
                                            <th>Employee</th>
                                            <th>Work Group</th>
                                            <th>Leave Group</th>
                                            <th>Start Date</th>
                                            <th>Detail Card</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($registrations as $item)
                                            @if (!empty($item->wg) && !empty($item->leave) && !empty($item->emp))
                                            <tr>
                                                <td>{{ $item->emp->emp_id ?? "-" }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <div class="symbol-label" style="background-image: url({{ asset($uImg[$item->emp_id] ?? "theme/assets/media/avatars/blank.png") }})"></div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a class="fw-bold text-dark" href="{{ route("attendance.registration.detail", $item->emp_id) }}">{{ $item->emp->emp_name ?? "-" }}</a>
                                                            <span class="text-muted">{{ $item->emp->dept->name ?? "" }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->wg->workgroup_name ?? "-" }}</td>
                                                <td>{{ $item->leave->leave_group_name ?? "-" }}</td>
                                                <td>@dateId($item->date1)</td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold mb-1">{{ $item->emp->emp_name ?? "-" }}</span>
                                                        <span class="text-muted">{{ $item->id_card }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-outline badge-{{ $item->status == 1 ? "success" : "danger" }}">{{ $item->status == 1 ? "Aktif" : "Nonaktif" }}</span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                    </button>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-emp_id="{{ $item->emp_id }}" data-id="{{ $item->id }}" onclick="editIDCard(this)" class="menu-link px-3">
                                                                Ganti ID Card
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-emp_id="{{ $item->emp_id }}" data-id="{{ $item->id }}" onclick="editEmpID(this)" class="menu-link px-3">
                                                                Ganti Employee ID
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-emp_id="{{ $item->emp_id }}" data-id="{{ $item->id }}" onclick="editWorkgroup(this)" class="menu-link px-3">
                                                                Pindah Group Kerja
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-emp_id="{{ $item->emp_id }}" data-id="{{ $item->id }}" onclick="editLeavegroup(this)" class="menu-link px-3">
                                                                Ganti Leave Group
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-name="{{ $item->emp->emp_name ?? "-" }}" data-id="{{ $item->id }}" onclick="editMobileAtt(this)" class="menu-link px-3">
                                                                Edit Mobile Attendance
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_history_change" role="tabpanel">
                    <div class="card bg-secondary-crm">
                        <div class="card-body">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="position-relative me-5">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                    <div>
                                        <select name="fhistory" onchange="fhistory()" class="form-select" data-control="select2">
                                            @foreach ($ff as $key => $item)
                                                <option value="{{ $key }}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" data-page-length="2" data-ordering="false" id="table-history-change">
                                    <thead>
                                        <tr>
                                            <th>
                                                Date
                                            </th>
                                            <th class="col-sec d-none" data-toggle="emp_id">ID Card</th>
                                            <th class="col-sec d-none" data-toggle="all">Employee ID</th>
                                            <th>Employee</th>
                                            <th>Start Date</th>
                                            <th class="col-sec">Type</th>
                                            @foreach ($ff as $key => $item)
                                                <th class="col-sec d-none" data-toggle="{{$key}}">New {{ $item }}</th>
                                                <th class="col-sec d-none" data-toggle="{{$key}}">Old {{ $item }}</th>
                                            @endforeach
                                            <th>Edit By</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($history_change as $item)
                                            @php
                                                $reg = $item->reg;
                                            @endphp
                                            @if (!empty($reg->emp))
                                            <tr>
                                                <td>{{ date("d F Y", strtotime($item->created_at)) }}</td>
                                                <td class="col-sec d-none" data-toggle="emp_id">{{$reg->id_card}}</td>
                                                <td class="col-sec d-none" data-toggle="all">{{ $reg->emp->emp_id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <div class="symbol-label" style="background-image: url({{ asset($uImg[$reg->emp_id] ?? "theme/assets/media/avatars/blank.png") }})"></div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ $reg->emp->emp_name }}</span>
                                                            <span class="text-muted">{{ $reg->emp->user->uacdepartement->name ?? "" }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ date("d F Y", strtotime($item->date)) }}</td>
                                                <td class="d-none">{{$item->type}}</td>
                                                @foreach ($ff as $key => $fitem)
                                                    <td class="col-sec d-none" data-toggle="{{$key}}">
                                                        @if ($item->type == "workgroup")
                                                            {{ $wg_name[$item->new_data] ?? "-" }}
                                                        @elseif($item->type == "leavegroup")
                                                            {{ $lname[$item->new_data] ?? "-" }}
                                                        @else
                                                            {{$item->new_data}}
                                                        @endif
                                                    </td>
                                                    <td class="col-sec d-none" data-toggle="{{$key}}">
                                                        @if ($item->type == "workgroup")
                                                            {{ $wg_name[$item->old_data] ?? "-" }}
                                                        @elseif($item->type == "leavegroup")
                                                            {{ $lname[$item->old_data] ?? "-" }}
                                                        @else
                                                            {{$item->old_data}}
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td>{{ $user_name[$item->created_by] ?? "-" }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                    </button>
                                                </td>
                                            </tr>
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

    <form action="{{ route("attendance.registration.store") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
            ])
            @slot('modalId')
                modal_new_registration
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-comment-user text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Add Registration Employee <span data-step>1/2</span></h3>
                        <span class="text-muted fs-base">Tambah baru & assign karyawan ke Workgroup </span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div data-step="head">
                    <div data-step="1">
                        <div class="fv-row">
                            <label class="col-form-label fw-bold">Employee Name</label>
                            <select name="emp" class="form-select" data-control="select2" data-placeholder="Input Employee by Name or ID" data-dropdown-parent="#modal_new_registration" id="">
                                <option value=""></option>
                                @foreach ($emp->whereNotIn("id", $registrations->pluck("emp_id")) as $item)
                                    @if ($item->emp_name != "")
                                    <option value="{{ $item->id }}" data-name="{{ $item->emp_name }}">{{ $item->emp_id ." - ". $item->emp_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('emp')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="fv-row col-6">
                                <label class="col-form-label fw-bold">ID Card</label>
                                <input type="text" name="id_card" class="form-control" placeholder="Input ID Card">
                                @error('id_card')
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label fw-bold">Date</label>
                                <input type="date" name="date1" class="form-control" placeholder="Input Data">
                                @error('date1')
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="fv-row col-6">
                                <label class="col-form-label fw-bold">Workgroup</label>
                                <select name="workgroup" class="form-select" data-control="select2" data-placeholder="Select Workgroup" data-dropdown-parent="#modal_new_registration" id="">
                                    <option value=""></option>
                                    @foreach ($workgroups as $item)
                                        <option value="{{ $item->id }}">{{ $item->workgroup_name }}</option>
                                    @endforeach
                                </select>
                                @error('workgroup')
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label fw-bold">Date</label>
                                <input type="date" name="date2" class="form-control" placeholder="Input Data">
                                @error('date2')
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="fv-row col-6">
                                <label class="col-form-label fw-bold">Leavegroup</label>
                                <select name="leavegroup" class="form-select" data-control="select2" data-placeholder="Select Leavegroup" data-dropdown-parent="#modal_new_registration" id="">
                                    <option value=""></option>
                                    @foreach ($leavegroups as $item)
                                        <option value="{{ $item->id }}">{{ $item->leave_group_name }}</option>
                                    @endforeach
                                </select>
                                @error('leavegroup')
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label fw-bold">Date</label>
                                <input type="date" name="date3" class="form-control" placeholder="Input Data">
                                @error('date3')
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div data-step="2" class="d-none">
                        <div class="fv-row">
                            <label class="col-form-label">Apakah <span data-name></span> bisa absen melalui mobile app?</label>
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-custom form-check-solid">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="mobile_att" value="1"/>
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <label class="form-check-label">
                                        <input class="form-check-input" checked type="radio" name="mobile_att" value="0"/>
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row d-none" data-mobile>
                            <label class="col-form-label">Apakah <span data-name></span> bisa "work from anywhere"?</label>
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-custom form-check-solid">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="wfa" value="1"/>
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <label class="form-check-label">
                                        <input class="form-check-input" checked type="radio" name="wfa" value="0"/>
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row d-none" data-mobile>
                            <label class="col-form-label">Pilih tempat <span data-name></span> bisa melakukan absensi online</label>
                            <div class="row">
                                @foreach ($loc as $item)
                                    <div class="col-6 mb-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" name="locations[]" value="{{ $item->id }}"/>
                                            {{ $item->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="button" class="btn btn-primary" data-step="next">Next</button>
                <button type="submit" class="btn btn-primary d-none">Save</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("attendance.registration.update") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
            ])
            @slot('modalId')
                modal_edit_id_card
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-comment-pen text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Edit ID Card</h3>
                        <span class="text-muted fs-base">Atur ulang ID Card pada data karyawan</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Employee Name</label>
                        <select name="emp" class="form-select" data-control="select2" data-placeholder="Input Employee by Name or ID" data-dropdown-parent="#modal_edit_id_card" id="">
                            <option value=""></option>
                            @foreach ($registrations as $item)
                                <option value="{{ $item->emp_id }}" {{ (old("emp") ?? null) == $item->emp_id ? "SELECTED" : "" }} data-id-card="{{ $item->id_card }}">{{ $item->emp->emp_name }}</option>
                            @endforeach
                        </select>
                        @error('emp')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Date</label>
                        <input type="date" name="date" value="{{ old("date") }}" class="form-control" placeholder="Input Data">
                        @error('date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Old ID Card</label>
                        <input type="text" name="old" value="{{ old("old") }}" class="form-control" readonly placeholder="Input ID Card">
                        @error('old')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">New ID Card</label>
                        <input type="text" name="new" value="{{ old("new") }}" class="form-control" placeholder="Input ID Card">
                        @error('new')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="id_card">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Save</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("attendance.registration.update") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
            ])
            @slot('modalId')
                modal_edit_employee_id
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-comment-pen text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Edit Employee ID</h3>
                        <span class="text-muted fs-base">Atur ulang Employee ID pada data karyawan</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Employee Name</label>
                        <select name="emp" class="form-select" data-control="select2" data-placeholder="Input Employee by Name or ID" data-dropdown-parent="#modal_edit_id_card" id="">
                            <option value=""></option>
                            @foreach ($registrations as $item)
                                @if (!empty($item->emp))
                                    <option value="{{ $item->emp_id }}" {{ (old("emp") ?? null) == $item->emp_id ? "SELECTED" : "" }} data-emp-id="{{ $item->emp->emp_id }}">{{ $item->emp->emp_name ?? "-" }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('emp')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Date</label>
                        <input type="date" name="date" value="{{ old("date") }}" class="form-control" placeholder="Input Data">
                        @error('date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Old Employee ID</label>
                        <input type="text" name="old" value="{{ old("old") }}" class="form-control" readonly placeholder="Input Employee ID">
                        @error('old')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">New Employee ID</label>
                        <input type="text" name="new" value="{{ old("new") }}" class="form-control" placeholder="Input Employee ID">
                        @error('new')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="emp_id">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Save</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("attendance.registration.update") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
            ])
            @slot('modalId')
                modal_edit_workgroup_id
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-comment-pen text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Edit Workgroup</h3>
                        <span class="text-muted fs-base">Atur ulang Workgroup pada data karyawan</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Employee Name</label>
                        <select name="emp" class="form-select" data-control="select2" data-placeholder="Input Employee by Name or ID" data-dropdown-parent="#modal_edit_workgroup_id">
                            <option value=""></option>
                            @foreach ($registrations as $item)
                                @if (!empty($item->emp))
                                <option value="{{ $item->emp_id }}" {{ (old("emp") ?? null) == $item->emp_id ? "SELECTED" : "" }} data-emp-id="{{ $item->workgroup }}">{{ $item->emp->emp_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('emp')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Date</label>
                        <input type="date" name="date" value="{{ old("date") }}" class="form-control" placeholder="Input Data">
                        @error('date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Old Workgroup</label>
                        <select name="old" class="form-select" data-control="select2" data-placeholder="Select Workgroup" data-dropdown-parent="#modal_edit_workgroup_id">
                            <option value=""></option>
                            @foreach ($workgroups as $item)
                                <option value="{{ $item->id }}" {{ (old('old') ?? null) == $item->id ? "SELECTED" : ""  }}>{{ $item->workgroup_name }}</option>
                            @endforeach
                        </select>
                        @error('old')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">New Workgroup</label>
                        <select name="new" class="form-select" data-control="select2" data-placeholder="Select Workgroup" data-dropdown-parent="#modal_edit_workgroup_id">
                            <option value=""></option>
                            @foreach ($workgroups as $item)
                                <option value="{{ $item->id }}" {{ (old('new') ?? null) == $item->id ? "SELECTED" : ""  }}>{{ $item->workgroup_name }}</option>
                            @endforeach
                        </select>
                        @error('new')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
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

    <form action="{{ route("attendance.registration.update") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
            ])
            @slot('modalId')
                modal_edit_leavegroup
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-comment-pen text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Edit Workgroup</h3>
                        <span class="text-muted fs-base">Atur ulang Workgroup pada data karyawan</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Employee Name</label>
                        <select name="emp" class="form-select" data-control="select2" data-placeholder="Input Employee by Name or ID" data-dropdown-parent="#modal_edit_leavegroup">
                            <option value=""></option>
                            @foreach ($registrations as $item)
                                @if (!empty($item->emp))
                                <option value="{{ $item->emp_id }}" {{ (old("emp") ?? null) == $item->emp_id ? "SELECTED" : "" }} data-emp-id="{{ $item->leavegroup }}">{{ $item->emp->emp_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('emp')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Date</label>
                        <input type="date" name="date" value="{{ old("date") }}" class="form-control" placeholder="Input Data">
                        @error('date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Old Leavegroup</label>
                        <select name="old" class="form-select" data-control="select2" data-placeholder="Select Leavegroup" data-dropdown-parent="#modal_edit_leavegroup" disabled>
                            <option value=""></option>
                            @foreach ($leavegroups as $item)
                                <option value="{{ $item->id }}" {{ (old('old') ?? null) == $item->id ? "SELECTED" : ""  }}>{{ $item->leave_group_name }}</option>
                            @endforeach
                        </select>
                        @error('old')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">New Leavegroup</label>
                        <select name="new" class="form-select" data-control="select2" data-placeholder="Select Leavegroup" data-dropdown-parent="#modal_edit_leavegroup">
                            <option value=""></option>
                            @foreach ($leavegroups as $item)
                                <option value="{{ $item->id }}" {{ (old('new') ?? null) == $item->id ? "SELECTED" : ""  }}>{{ $item->leave_group_name }}</option>
                            @endforeach
                        </select>
                        @error('new')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="leavegroup">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Save</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("attendance.registration.update") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
            ])
            @slot('modalId')
                modal_edit_mobile_att
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-comment-pen text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Edit Mobile Attendance</h3>
                        <span class="text-muted fs-base">Atur ulang Mobile Attendance pada data karyawan</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="fv-row">
                    <label class="col-form-label">Apakah <span data-name></span> bisa absen melalui mobile app?</label>
                    <div class="d-flex align-items-center">
                        <div class="form-check form-check-custom form-check-solid">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="mobile_att" value="1"/>
                                Ya
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <label class="form-check-label">
                                <input class="form-check-input" checked type="radio" name="mobile_att" value="0"/>
                                Tidak
                            </label>
                        </div>
                    </div>
                </div>
                <div class="fv-row d-none" data-mobile>
                    <label class="col-form-label">Apakah <span data-name></span> bisa "work from anywhere"?</label>
                    <div class="d-flex align-items-center">
                        <div class="form-check form-check-custom form-check-solid">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="wfa" value="1"/>
                                Ya
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <label class="form-check-label">
                                <input class="form-check-input" checked type="radio" name="wfa" value="0"/>
                                Tidak
                            </label>
                        </div>
                    </div>
                </div>
                <div class="fv-row d-none" data-mobile>
                    <label class="col-form-label">Pilih tempat <span data-name></span> bisa melakukan absensi online</label>
                    <div class="row">
                        @foreach ($loc as $item)
                            <div class="col-6 mb-5">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="locations[]" value="{{ $item->id }}"/>
                                    {{ $item->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="id">
                <input type="hidden" name="type" value="mobile_att">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Save</button>
            @endslot
        @endcomponent
    </form>

    @component('layouts.components.fab', [
        "fab" => [
            ["label" => "Add Overtime", 'url' => route("attendance.overtime.index")."?modal=modal_add_overtime"],
            ["label" => "Create Request Leave", "url" => route("attendance.leave.index")."?modal=modal_create_request_leave"],
            ["label" => "New Registration", "url" => "javascript:;", 'toggle' => 'data-bs-toggle="modal" data-bs-target="#modal_new_registration"'],
        ]
    ])
    @endcomponent
@endsection

@section('view_script')
    <script>

        function check_new_id(me, type){
            var old = $(me).parents('div.modal').find("[name=old]").val()
            var _new = $(me).val()
            $(me).parent().find("div.fv-plugins-message-container").remove()
            var modal = $(me).parents("div.modal")

            if(old == _new){
                $(modal).find("button[type=submit]").prop('disabled', true)
                $(me).addClass("border-danger")
                var message = "ID baru tidak boleh sama"
                if(type == "workgroup" || type == "leavegroup"){
                    message = `Perubahan ${type} tidak dapat dilakukan`
                }
                var el = `<div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">${message}</div>`
                $(me).parent().append(el)
            } else {
                $(modal).find("button[type=submit]").prop('disabled', false)
                if(type != "workgroup" || type != "leavegroup"){
                    $.ajax({
                        url : "{{ route("attendance.registration.index") }}?a=check&type="+type+"&id=" + _new,
                        type : "get",
                        dataType : "json"
                    }).then(function(resp){
                        if(resp.success){
                            $(me).removeClass("border-danger")
                            $(me).addClass("border-success")
                        } else {
                            $(modal).find("button[type=submit]").prop('disabled', true)
                            $(me).addClass("border-danger")
                            var el = `<div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">${resp.message}</div>`
                            $(me).parent().append(el)
                        }
                    })
                }
            }
        }

        function editIDCard(me){
            $("#modal_edit_id_card").modal('show')
            $("#modal_edit_id_card select[name=emp]").change(function(){
                var id_card = $(this).find("option:selected").data("id-card")
                $("#modal_edit_id_card input[name=old]").val(id_card)
            })
            $("#modal_edit_id_card select[name=emp]").val($(me).data("emp_id")).trigger("change")

            $("#modal_edit_id_card button[type=submit]").prop('disabled', true)

            $("#modal_edit_id_card input[name=new]").off("change")

            $("#modal_edit_id_card input[name=new]").change(function(){
                check_new_id(this, "id_card")
            })
        }

        function editEmpID(me){
            $("#modal_edit_employee_id").modal('show')
            $("#modal_edit_employee_id select[name=emp]").change(function(){
                var emp_id = $(this).find("option:selected").data("emp-id")
                $("#modal_edit_employee_id input[name=old]").val(emp_id)
            })
            $("#modal_edit_employee_id select[name=emp]").val($(me).data("emp_id")).trigger("change")

            $("#modal_edit_employee_id button[type=submit]").prop('disabled', true)

            $("#modal_edit_employee_id input[name=new]").off("change")

            $("#modal_edit_employee_id input[name=new]").change(function(){
                check_new_id(this, "employee_id")
            })
        }

        function editMobileAtt(me){
            $.ajax({
                url : "{{ route("attendance.registration.index") }}?a=m_att",
                type : "get",
                data : {
                    id : $(me).data("id")
                },
                dataType : "json"
            }).then(function(resp){
                $("#modal_edit_mobile_att [data-name]").text($(me).data("name"))
                $("#modal_edit_mobile_att input[name=mobile_att][value="+resp.mobile_att+"]").prop("checked", true)
                $("#modal_edit_mobile_att input[name=wfa][value="+resp.wfa+"]").prop("checked", true)

                var loc = resp.locations ?? []

                $("#modal_edit_mobile_att input[name='locations[]']").each(function(){
                    console.log($(this).val())
                    if(loc.includes(parseInt($(this).val()))){
                        $(this).prop("checked", true)
                    }
                })

                $("#modal_edit_mobile_att [data-mobile]").addClass("d-none")

                if(resp.mobile_att == 1){
                    $("#modal_edit_mobile_att [data-mobile]").removeClass("d-none")
                }

                $("#modal_edit_mobile_att input[name=mobile_att]").click(function(){
                    $("#modal_edit_mobile_att [data-mobile]").addClass("d-none")
                    if($(this).val() == "1"){
                        $("#modal_edit_mobile_att [data-mobile]").removeClass("d-none")
                    }
                })

                $("#modal_edit_mobile_att input[name=id]").val($(me).data("id"))

                $("#modal_edit_mobile_att").modal("show")
            })
        }

        function editWorkgroup(me){
            $("#modal_edit_workgroup_id").modal('show')
            $("#modal_edit_workgroup_id select[name=emp]").change(function(){
                var emp_id = $(this).find("option:selected").data("emp-id")
                $("#modal_edit_workgroup_id select[name=old]").val(emp_id).trigger('change')
            })
            $("#modal_edit_workgroup_id select[name=emp]").val($(me).data("emp_id")).trigger("change")

            $("#modal_edit_workgroup_id button[type=submit]").prop('disabled', true)

            // $("#modal_edit_workgroup_id select[name=new]").off("change")

            $("#modal_edit_workgroup_id select[name=new]").change(function(){
                check_new_id(this, "workgroup")
            })
        }

        function editLeavegroup(me){
            $("#modal_edit_leavegroup").modal('show')
            $("#modal_edit_leavegroup select[name=emp]").change(function(){
                var emp_id = $(this).find("option:selected").data("emp-id")
                $("#modal_edit_leavegroup select[name=old]").val(emp_id).trigger('change')
            })
            $("#modal_edit_leavegroup select[name=emp]").val($(me).data("emp_id")).trigger("change")

            $("#modal_edit_leavegroup button[type=submit]").prop('disabled', true)

            // $("#modal_edit_leavegroup select[name=new]").off("change")

            $("#modal_edit_leavegroup select[name=new]").change(function(){
                check_new_id(this, "leavegroup")
            })
        }

        function ftable(me, index){
            tb_list[0].column(index).search($(me).val()).draw()
        }

        function historyColumn(){
            var f = $("select[name=fhistory]").val()
            $("#table-history-change .col-sec").addClass("d-none")

            if(f == "emp_id"){
                $("#table-history-change [data-toggle=emp_id]").removeClass('d-none')
            } else {
                $("#table-history-change [data-toggle=all]").removeClass('d-none')
            }

            $("#table-history-change [data-toggle="+f+"]").removeClass('d-none')
        }

        function fhistory(){
            var f = $("select[name=fhistory]").val()
            tb_list[1].column(5).search(f).draw()

            historyColumn()
        }

        $(document).ready(function(){
            @if(Session::has("modal"))
                $("#{{ Session::get("modal") }}").modal("show")
            @endif

            fhistory()

            tb_list[1].on("init draw", function(){
                historyColumn()
            })

            $("#modal_new_registration select[name=emp]").change(function(){
                var opt = $(this).find("option:selected")
                var name = $(opt).data("name")

                $("#modal_new_registration .modal-body [data-name]").text(name)
            })

            $("#modal_new_registration button[data-step=next]").click(function(){
                var emp = $("#modal_new_registration select[name=emp]").val()
                if(emp == ""){
                    return Swal.fire("Employee kosong", "Mohon untuk memilih employee terlebih dahulu", "warning")
                }

                $("#modal_new_registration [data-step=1]").addClass("d-none")
                $("#modal_new_registration [data-step=2]").removeClass("d-none")
                $("#modal_new_registration button[type=submit]").removeClass("d-none")
                $("#modal_new_registration span[data-step]").text("2/2")
                $(this).addClass("d-none")
            })

            $("#modal_new_registration input[name=mobile_att]").click(function(){
                $("#modal_new_registration [data-mobile]").addClass("d-none")
                if($(this).val() == "1"){
                    $("#modal_new_registration [data-mobile]").removeClass("d-none")
                }
            })

            $("#modal_new_registration button[data-bs-dismiss]").click(function(){
                $("#modal_new_registration span[data-step]").text("1/2")
                $("#modal_new_registration [data-step=1]").removeClass("d-none")
                $("#modal_new_registration [data-step=2]").addClass("d-none")
                $("#modal_new_registration button[type=submit]").addClass("d-none")
                $("#modal_new_registration button[data-step=next]").removeClass("d-none")
            })

            @if(Session::has("drawer"))
                @if(Session::get("drawer") != null)
                    drawer.show()
                    show_detail({{Session::get("drawer")}})
                @endif
            @endif
        })
    </script>
@endsection
