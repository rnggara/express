@extends('_personel.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-50px me-3">
            <div class="symbol-label bg-primary">
                <i class="fi fi-rr-users-alt text-white fs-1"></i>
            </div>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-3 fw-bold">Pegawai</span>
            <span class="text-muted">Disini Anda bisa mengatur pegawai</span>
        </div>
    </div>
    <div class="d-flex flex-column">
        <div class="d-flex align-items-center justify-content-between">
            <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                <li class="nav-item">
                    <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_all_employee">
                        <span class="nav-text">Semua Pegawai</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_resign">
                        <span class="nav-text">Pegawai Berhenti</span>
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-5">
                <button type="button" class="btn btn-light-primary" id="btn-export-data">
                    <i class="fi fi-rr-file-csv"></i>
                    <span class="indicator-label">
                        Export
                    </span>
                    <span class="indicator-progress">
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
                <button type="button" class="btn btn-secondary" id="btn-batch-transfer">
                    <i class="fi fi-rr-folder-tree"></i>
                    Transfer Pegawai
                </button>
                <a href="{{ route("personel.add_employee") }}" class="btn btn-primary">
                    <i class="fi fi-rr-add-document"></i>
                    Tambah Pegawai
                </a>
            </div>
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab_all_employee" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="position-relative me-5">
                                    <input type="text" class="form-control ps-12" placeholder="Cari" name="search_table">
                                    <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#kt_all_employee_body_1">
                                    <i class="fi fi-rr-filter"></i>
                                    Filter
                                </button>
                            </div>
                            <div class="accordion mb-5" id="kt_all_employee">
                                <div class="accordion-item bg-transparent border-0">
                                    <div id="kt_all_employee_body_1" class="accordion-collapse collapse" aria-labelledby="kt_all_employee_header_1" data-bs-parent="#kt_all_employee">
                                        <div class="accordion-body px-0">
                                            <div class="d-flex align-items-center">
                                                <select name="fposition" class="form-select" data-control="select2" data-placeholder="Posisi" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Departemen" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fjoblevel" class="form-select" data-control="select2" data-placeholder="Tingkat Pegawai" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fempstatus" class="form-select" data-control="select2" data-placeholder="Status Karyawan" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="flocation" class="form-select" data-control="select2" data-placeholder="Lokasi" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="foffence" class="form-select" data-control="select2" data-placeholder="Pelanggaran" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="scroll">
                                <table class="table bg-white" data-ordering="false" id="table-leave-balance">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="select" type="checkbox" value="1" id="" />
                                                </div>
                                            </th>
                                            <th>Nama</th>
                                            <th>Posisi</th>
                                            <th>Departemen</th>
                                            <th>Tingkat Pegawai</th>
                                            <th>Status Karyawan</th>
                                            <th>Kelas</th>
                                            <th>Lokasi</th>
                                            <th>Pelanggaran</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($personel->whereNull("expire") as $item)
                                            @php
                                                $user = $item->user ?? [];
                                                $tf = $transfer[$item->id] ?? [];
                                                $tfD['position'] = collect($tf['position'] ?? [])->first();
                                                $tfD['departement'] = collect($tf['departement'] ?? [])->first();
                                                $tfD['job_level'] = collect($tf['job_level'] ?? [])->first();
                                                $tfD['employee_status'] = collect($tf['employee_status'] ?? [])->first();
                                                $tfD['class'] = collect($tf['class'] ?? [])->first();
                                                $tfD['location'] = collect($tf['location'] ?? [])->first();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="select" data-name="{{ $item->emp_name }}" data-check data-emp="{{ $item->id }}" type="checkbox" value="{{ $item->id }}" id="" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class='d-flex align-items-center'>
                                                        <div class='symbol symbol-40px me-5'><div class='symbol-label' style="background-image: url('{{ asset($item->user->user_img ?? "images/image_placeholder.png") }}')"></div></div>
                                                        <div class='d-flex flex-column'><span class='fw-bold'>{{ $item->emp_name }}</span><span>{{ $item->emp_id }}</span></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span>{{ $master['position'][$item->position_id] ?? "-" }}</span>
                                                        @if (!empty($tfD['position']))
                                                            <i class="fi fi-rr-arrow-right"></i>
                                                            {{ $master['position'][$tfD['position']->new] }}
                                                            <span class="fi fi-rr-info" data-bs-toggle="tooltip" title="{{ date("d/m/Y", strtotime($tfD['position']->start_date)) }}"></span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span>{{ $master['departement'][$user->uac_departement ?? null] ?? "-" }}</span>
                                                        @if (!empty($tfD['departement']))
                                                            <i class="fi fi-rr-arrow-right"></i>
                                                            {{ $master['departement'][$tfD['departement']->new] }}
                                                            <span class="fi fi-rr-info" data-bs-toggle="tooltip" title="{{ date("d/m/Y", strtotime($tfD['departement']->start_date)) }}"></span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span>{{ $master['job_level'][$item->job_level_id] ?? "-" }}</span>
                                                        @if (!empty($tfD['job_level']))
                                                            <i class="fi fi-rr-arrow-right"></i>
                                                            {{ $master['job_level'][$tfD['job_level']->new] }}
                                                            <span class="fi fi-rr-info" data-bs-toggle="tooltip" title="{{ date("d/m/Y", strtotime($tfD['job_level']->start_date)) }}"></span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span>{{ $master['employee_status'][$item->employee_status_id] ?? "-" }}</span>
                                                        @if (!empty($tfD['employee_status']))
                                                            <i class="fi fi-rr-arrow-right"></i>
                                                            {{ $master['employee_status'][$tfD['employee_status']->new] }}
                                                            <span class="fi fi-rr-info" data-bs-toggle="tooltip" title="{{ date("d/m/Y", strtotime($tfD['employee_status']->start_date)) }}"></span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span>{{ $master['class'][$item->class_id] ?? "-" }}</span>
                                                        @if (!empty($tfD['class']))
                                                            <i class="fi fi-rr-arrow-right"></i>
                                                            {{ $master['class'][$tfD['class']->new] }}
                                                            <span class="fi fi-rr-info" data-bs-toggle="tooltip" title="{{ date("d/m/Y", strtotime($tfD['class']->start_date)) }}"></span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span>{{ $master['location'][$user->uac_location ?? null] ?? "-" }}</span>
                                                        @if (!empty($tfD['location']))
                                                            <i class="fi fi-rr-arrow-right"></i>
                                                            {{ $master['location'][$tfD['location']->new] }}
                                                            <span class="fi fi-rr-info" data-bs-toggle="tooltip" title="{{ date("d/m/Y", strtotime($tfD['location']->start_date)) }}"></span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $last_offence = $item->offence->last() ?? [];
                                                    @endphp
                                                    {{ $last_offence->detail->name ?? "-" }}
                                                </td>
                                                <td>
                                                    <button type="button" onclick="view_offence({{ $item->id }})" class="btn btn-icon btn-sm">
                                                        <i class="fi fi-rr-info"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                    </button>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail({{ $item->id }})" class="menu-link px-3">
                                                                Lebih Detil
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" onclick="transfer_emp({{ $item->id }})" class="menu-link px-3">
                                                                Transfer
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" onclick="offence_emp({{ $item->id }})" class="menu-link px-3">
                                                                Pelanggaran
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="formal_letter({{ $item->id }})" class="menu-link px-3">
                                                                Surat Formal
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        @php
                                                            $_sr = collect($scheduled_resigned[$item->id] ?? [])->first();
                                                        @endphp
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" @if(empty($_sr)) onclick="resign_emp({{ $item->id }})" @endif class="menu-link px-3 {{ !empty($_sr) ? "disabled text-muted" : "text-danger" }}">
                                                                Berhenti {{ !empty($_sr) ? "($_sr->resign_date)" : "" }}
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
            <div class="tab-pane fade" id="tab_resign" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="position-relative me-5">
                                    <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                    <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                </div>
                                {{-- <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#kt_all_employee_body_1">
                                    <i class="fi fi-rr-filter"></i>
                                    Filter
                                </button> --}}
                            </div>
                            {{-- <div class="accordion mb-5" id="kt_all_employee">
                                <div class="accordion-item bg-transparent border-0">
                                    <div id="kt_all_employee_body_1" class="accordion-collapse collapse" aria-labelledby="kt_all_employee_header_1" data-bs-parent="#kt_all_employee">
                                        <div class="accordion-body px-0">
                                            <div class="d-flex align-items-center">
                                                <select name="fposition" class="form-select" data-control="select2" data-placeholder="Position" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Departemen" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fjoblevel" class="form-select" data-control="select2" data-placeholder="Job Level" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fempstatus" class="form-select" data-control="select2" data-placeholder="Employee Status" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="flocation" class="form-select" data-control="select2" data-placeholder="Location" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="foffence" class="form-select" data-control="select2" data-placeholder="Offence" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="scroll">
                                <table class="table bg-white" data-ordering="false" id="table-resign">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="select" type="checkbox" value="1" id="" />
                                                </div>
                                            </th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Departemen</th>
                                            <th>Job Level</th>
                                            <th>Employee Status</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Flag</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($personel_resigned as $item)
                                            @php
                                                $user = $item->user ?? [];
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="select" data-name="{{ $item->emp_name }}" data-check data-emp="{{ $item->id }}" type="checkbox" value="{{ $item->id }}" id="" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class='d-flex align-items-center'>
                                                        <div class='symbol symbol-40px me-5'><div class='symbol-label' style='background-image: url({{ asset($item->user->user_img ?? "images/image_placeholder.png") }})'></div></div>
                                                        <div class='d-flex flex-column'><span class='fw-bold'>{{ $item->emp_name }}</span><span>{{ $item->emp_id }}</span></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $master['position'][$item->position_id] ?? "-" }}
                                                </td>
                                                <td>
                                                    {{ $master['departement'][$user->uac_departement ?? null] ?? "-" }}
                                                </td>
                                                <td>
                                                    {{ $master['job_level'][$item->job_level_id] ?? "-" }}
                                                </td>
                                                <td>
                                                    {{ $master['employee_status'][$item->employee_status_id] ?? "-" }}
                                                </td>
                                                <td>
                                                    {{ $master['location'][$user->uac_location ?? null] ?? "-" }}
                                                </td>
                                                <td>
                                                    Resign
                                                </td>
                                                <td>
                                                    Resign
                                                </td>
                                                <td>
                                                    <i class="fi fi-rr-info"></i>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                    </button>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail({{ $item->id }})" class="menu-link px-3">
                                                                Employee Detail
                                                            </a>
                                                        </div>
                                                        {{-- <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" onclick="transfer_emp({{ $item->id }})" class="menu-link px-3">
                                                                Transfer
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $item->id }}" class="menu-link px-3">
                                                                Offence
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" onclick="resign_emp({{ $item->id }})" class="menu-link px-3 text-danger">
                                                                Resign
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item--> --}}
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
</div>

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

@component('layouts._crm_modal', [
    'modalSize' => "modal-lg"
    ])
    @slot('modalId')
        modal_employee_offence
    @endslot
    @slot('modalTitle')
        <div class="d-flex align-items-center">
            <div class="symbol symbol-50px me-5">
                <div class="symbol-label bg-light-primary">
                    <span class="fi fi-rr-add text-primary"></span>
                </div>
            </div>
            <div class="d-flex flex-column">
                <h3 class="me-2">Offence</h3>
            </div>
        </div>
    @endslot
    @slot('modalContent')
    <div data-content></div>
    @endslot
    @slot('modalFooter')
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
    @endslot
@endcomponent


<form action="{{ route("personel.employee_table.resign") }}" method="post" id="form-resign">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-lg"
        ])
        @slot('modalId')
            modal_resign
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-add text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Employee Termination/Resign</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
        <div id="resign-content"></div>
        @endslot
        @slot('modalFooter')
            @csrf
            <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
            <button type="button" name="submit" class="btn btn-primary">Submit</button>
            <button type="submit" class="d-none"></button>
        @endslot
    @endcomponent
</form>

<form action="{{ route("personel.employee_table.offence") }}" method="post" id="form-offence" enctype="multipart/form-data">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-lg"
        ])
        @slot('modalId')
            modal_offence
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-add text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Employee Offence</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
        <div id="offence-content"></div>
        @endslot
        @slot('modalFooter')
            @csrf
            <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
            <button type="button" name="submit" class="btn btn-primary">Submit</button>
            <button type="submit" class="d-none"></button>
        @endslot
    @endcomponent
</form>

<form action="{{ route("personel.employee_table.transfer") }}" method="post" id="form-transfer">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-lg"
        ])
        @slot('modalId')
            modal_transfer
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-add text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Transfer Pegawai</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
        <div id="transfer-content"></div>
        @endslot
        @slot('modalFooter')
            @csrf
            <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
            <button type="button" name="submit" class="btn btn-primary">Kirim</button>
            <button type="submit" class="d-none"></button>
        @endslot
    @endcomponent
</form>

<form action="{{ route("personel.employee_table.transfer") }}" method="post" id="form-transfer">
    @component('layouts._crm_modal', [
        'modalSize' => ""
        ])
        @slot('modalId')
            modal_transfer_form
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-add text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Transfer Pegawai</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
        <div id="transfer-form-content"></div>
        @endslot
        @slot('modalFooter')
            @csrf
            <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
            <button type="button" name="submit" class="btn btn-primary">Kirim</button>
        @endslot
    @endcomponent
</form>

@endsection

@section('view_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script>

        KTDrawer.createInstances();
        var drawerElement = document.querySelector("#kt_drawer_detail");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");
        var blockUI = new KTBlockUI(target);

        var transfer = {}

        function printmv(divname){
            window.frames[divname].focus();
            window.frames[divname].print();
        }

        function formal_letter(id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{ route('personel.employee_table.formal_letter') }}/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)

                $("#kt_drawer_detail select[data-control=select2]").select2()

                initTable($("#kt_drawer_detail #table-list"))

                $("#kt_drawer_detail select[name=template_id]").change(function(){
                    var form = $(this).parents("form").eq(0)
                    $.ajax({
                        url : "{{ route('personel.fl.ajaxField') }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            template : $(this).val(),
                            id : id,
                            type : "entry",
                        }
                    }).then(function(resp){
                        $("#kt_drawer_detail [data-content]").html(resp.view)
                        $(form).find("button[type=submit]").prop('disabled', false)
                        $("#kt_drawer_detail .flatpicker").each(function(){
                            $(this).flatpickr({
                                dateFormat: "Y-m-d",
                            });
                        })
                    })
                })
            })
        }

        function show_detail(id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{ route('personel.employee_table.detail') }}/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)

                $("#kt_drawer_detail .flatpicker").each(function(){
                    $(this).flatpickr({
                        dateFormat: "d/m/Y",
                    });
                })

                $("#kt_drawer_detail table.table-display-2").each(function(){
                    initTable($(this))
                })

                $("#kt_drawer_detail input[type=file][data-toggle=file]").change(function(){
                    var val = $(this).val().split("\\")

                    $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
                })

                $("#kt_drawer_detail select[data-control=select2]").select2()

                $('#kt_drawer_detail [data-toggle="form-add"]').click(function(){
                    var accordion = $(this).parents("div.accordion-body")
                    $(accordion).find('[data-form-add]').removeClass("d-none")
                })

                $('#kt_drawer_detail [data-toggle="form-cancel"]').click(function(){
                    var accordion = $(this).parents("div.accordion-body")
                    $(accordion).find('[data-form-add]').addClass("d-none")
                })

                $("#kt_drawer_detail input.number").number(true, 2)

                $('#kt_drawer_detail [data-toggle="still"]').click(function(){
                    var checked = this.checked
                    var form = $(this).parents("form")
                    if(checked){
                        $(form).find('[data-target="still"]').addClass("d-none")
                        $(form).find('[data-target="still"]').find("select").prop("required", false)
                    } else {
                        $(form).find('[data-target="still"]').removeClass("d-none")
                        $(form).find('[data-target="still"]').find("select").prop("required", true)
                    }
                })

                $("#kt_drawer_detail input[name=resident_identity]").click(function(){
                    var checked = this.checked

                    if(checked){
                        var address = $("#kt_drawer_detail [name='identity[address]']")
                        var zip_code = $("#kt_drawer_detail [name='identity[zip_code]']")
                        var country = $("#kt_drawer_detail [name='identity[country]']")
                        var city = $("#kt_drawer_detail [name='identity[city]']")
                        var province = $("#kt_drawer_detail [name='identity[province]']")

                        $("#kt_drawer_detail [name='resident[address]']").val($(address).val())
                        $("#kt_drawer_detail [name='resident[zip_code]']").val($(zip_code).val())
                        $("#kt_drawer_detail [name='resident[country]']").val($(country).val())
                        $("#kt_drawer_detail [name='resident[city]']").val($(city).val())
                        $("#kt_drawer_detail [name='resident[province]']").val($(province).val())
                    }
                })

                $("#kt_drawer_detail button[name=reactive]").click(function(){
                    var form = $(this).parents('form')

                    Swal.fire({
                        html: `<div class='alert alert-danger'><div class='d-flex flex-column'><div class='d-flex align-items-center mb-5'><i class='fi fi-rr-info text-danger me-2'></i><span class='text-dark'>Warning</span></div><p class='text-dark text-start'>Do you want to reactivate this employee? Reactivating an employee will automatically restore the functions that were previously assigned to them.</p></div></div>`,
                        icon: "question",
                        title : "Reactive Employee",
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        cancelButtonText: 'No',
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: 'btn text-primary'
                        }
                    }).then((resp) => {
                        if(resp.isConfirmed){
                            return $(form).find("button[type=submit]").click()
                        }
                    })
                })

                $("#kt_drawer_detail select[name=employee_status]").change(function () {
                    var opt = $(this).find("option:selected")
                    var end_date = $(opt).data("end-date")
                    var f = $(this).parents("form").eq(0)
                    var edate = $(f).find("input[name=emp_status_end_date]")
                    var fv = $(edate).parents("div.fv-row").eq(0)
                    $(fv).addClass("d-none")
                    console.log(end_date, fv, edate)
                    if(end_date == 1){
                        $(fv).removeClass("d-none")
                    }
                })
            })
        }

        function view_offence(id){
            $.ajax({
                url : "{{ route('personel.employee_table.index') }}?a=view-offence&id=" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#modal_employee_offence div[data-content]").html(resp.view)

                $("#modal_employee_offence").modal("show")

                initTable($("#modal_employee_offence table.table-display-2"))
            })
        }

        function offence_detail(id){
            $.ajax({
                url : "{{ route('personel.employee_table.index') }}?a=detail-offence&id=" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#offence-content").html(resp.view)

                $("#modal_offence input[type=file][data-toggle=file]").change(function(){
                    var val = $(this).val().split("\\")

                    $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
                })

                $("#modal_offence").modal("show")

                $("#modal_offence select[data-control=select2]").select2()

                $("#modal_offence button[name=submit]").prop("disabled", true)
            })
        }

        function offence_emp(id){
            $.ajax({
                url : "{{ route('personel.employee_table.index') }}?a=offence&id=" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#offence-content").html(resp.view)

                $("#modal_offence input[type=file][data-toggle=file]").change(function(){
                    var val = $(this).val().split("\\")

                    $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
                })

                $("#modal_offence button[name=submit]").prop("disabled", false)

                $("#modal_offence").modal("show")

                $("#modal_offence select[data-control=select2]").select2()

                $("#modal_offence button[name=submit]").click(async function(){
                    var form = $(this).parents("form")
                    Swal.fire({
                        html: `<div class='alert alert-danger'><div class='d-flex flex-column'><div class='d-flex align-items-center mb-5'><i class='fi fi-rr-info text-danger me-2'></i><span class='text-dark'>Warning</span></div><p class='text-dark text-start'>Are you sure you want to give this employee an offence? Giving an offence will affect the employee's track record.</p></div></div>`,
                        icon: "question",
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        cancelButtonText: 'No',
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: 'btn text-primary'
                        }
                    }).then((resp) => {
                        if(resp.isConfirmed){
                            return $(form).find("button[type=submit]").click()
                        }
                    })
                })
            })
        }

        function resign_emp(id){
            $.ajax({
                url : "{{ route('personel.employee_table.index') }}?a=resign&id=" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#resign-content").html(resp.view)

                $("#modal_resign").modal("show")

                $("#modal_resign select[data-control=select2]").select2()

                $("#modal_resign button[name=submit]").click(async function(){
                    var form = $(this).parents("form")
                    Swal.fire({
                        html: `<div class='alert alert-danger'><div class='d-flex flex-column'><div class='d-flex align-items-center mb-5'><i class='fi fi-rr-info text-danger me-2'></i><span class='text-dark'>Warning</span></div><p class='text-dark text-start'>Are you sure you want to accept this employee's resignation? Accepting an employee's resignation may affect their status, and they may not be able to perform the functions they were assigned to.</p></div></div>`,
                        icon: "question",
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: "Yes, resign",
                        cancelButtonText: 'No',
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: 'btn text-primary'
                        }
                    }).then((resp) => {
                        if(resp.isConfirmed){
                            return $(form).find("button[type=submit]").click()
                        }
                    })
                })
            })
        }

        function transfer_emp(id){
            $.ajax({
                url : "{{ route('personel.employee_table.index') }}?a=transfer&id=" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#transfer-content").html(resp.view)

                $("#modal_transfer [data-bs-toggle=tooltip]").tooltip()

                $("#modal_transfer").modal("show")

                $("#modal_transfer select[data-control=select2]").select2()

                $("#modal_transfer button[name=submit]").click(async function(){
                    var form = $(this).parents("form")
                    Swal.fire({
                        html: `<div class='alert alert-danger'><div class='d-flex flex-column'><div class='d-flex align-items-center mb-5'><i class='fi fi-rr-info text-danger me-2'></i><span class='text-dark'>Warning</span></div><p class='text-dark text-start'>Are you sure to change employee information? This change can result in changes to detailed employee information, and sending a request for approval to the person concerned.</p></div></div>`,
                        icon: "question",
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        cancelButtonText: 'No',
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: 'btn text-primary'
                        }
                    }).then((resp) => {
                        if(resp.isConfirmed){
                            return $(form).find("button[type=submit]").click()
                        }
                    })
                })
            })
        }

        function transfer_emp_batch(id){
            $.ajax({
                url : "{{ route('personel.employee_table.index') }}?a=transfer_batch",
                data : {
                    id : id
                },
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#transfer-content").html(resp.view)

                $("#modal_transfer").modal("show")

                $("#modal_transfer select[data-control=select2]").select2()

                var tb_batch = initTable($("#table-batch"))

                tb_batch.on('draw.dt, init.dt, order.dt search.dt', function () {
                    let i = 1;

                    tb_batch
                        .cells(null, 0, { search: 'applied', order: 'applied' })
                        .every(function (cell) {
                            this.data(i++);
                        });
                })
                .draw();

                $("#modal_transfer button[data-toggle='remove']").click(function(){
                    var tr = $(this).parents("tr")
                    var id = $(tr).find('input[name="emp_id[]"]').val()
                    $("#modal_transfer select[name=emp]").find("option[value="+id+"]").prop("disabled", false)
                    tb_batch.row( $(this).parents('tr') )
                        .remove()
                        .draw();
                })

                $("#modal_transfer select[name=emp]").change(function(){
                    if($(this).val() != ""){
                        var opt = $(this).find("option:selected")
                        var id = $(this).val()
                        var emp_id = $(opt).data("emp-id")
                        var name = $(opt).text()

                        tb_batch.row.add( [
                            "",
                            emp_id,
                            name,
                            `<input type="hidden" name="emp_id[]" value="${id}">
                            <button type="button" data-toggle="remove" class="btn btn-icon btn-sm">
                                <i class="fa fa-times text-danger"></i>
                            </button>`
                        ] ).draw();

                        $("#modal_transfer button[data-toggle='remove']").click(function(){
                            var tr = $(this).parents("tr")
                            tb_batch.row( $(this).parents('tr') )
                                .remove()
                                .draw();
                        })

                        $("#modal_transfer select[name=emp]").val("").trigger("change")
                    }
                })

                $("#modal_transfer button[name=submit]").click(async function(){
                    var form = $(this).parents("form")
                    Swal.fire({
                        html: `<div class='alert alert-danger'><div class='d-flex flex-column'><div class='d-flex align-items-center mb-5'><i class='fi fi-rr-info text-danger me-2'></i><span class='text-dark'>Warning</span></div><p class='text-dark text-start'>Are you sure to change employee information? This change can result in changes to detailed employee information, and sending a request for approval to the person concerned.</p></div></div>`,
                        icon: "question",
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        cancelButtonText: 'No',
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: 'btn text-primary'
                        }
                    }).then((resp) => {
                        if(resp.isConfirmed){
                            return $(form).find("button[type=submit]").click()
                        }
                    })
                })
            })
        }

        function render_transfer_form(id){
            $("#modal_transfer [data-transfer]").html('')
            console.log(transfer)
            for (const key in transfer) {
                var tp = key.toString()
                const item = transfer[key];

                elApprove = ""
                if(item['approved_by'] != undefined){
                    elApprove = `<div class='fv-row col-6'>` +
                            `<label class='col-form-label w-100'>Must Approve By</label>` +
                            `<label class='mt-1'>${item['approved_by']['label']}</label>` +
                            `<input type='hidden' name='update[${key}][approved_by]' value='${item['approved_by']['val']}'>` +
                            `<input type='hidden' name='update[${key}][reason]' value='${item['reason']['val']}'>` +
                            `<input type='hidden' name='update[${key}][reference]' value='${item['reference']['val']}'>` +
                        `</div>`
                } else {
                    elApprove = `<div class='fv-row col-6'>` +
                            `<input type='hidden' name='update[${key}][bypass_approve]' value='${item['bypass_approve']['val']}'>` +
                            `<input type='hidden' name='update[${key}][reason]' value='${item['reason']['val']}'>` +
                            `<input type='hidden' name='update[${key}][reference]' value='${item['reference']['val']}'>` +
                        `</div>`
                }

                    var el = `<div class='d-flex flex-column mt-5 data-item'>` +
                        `<span class='fw-bold mb-3'>Transfering Field (`+tp.replaceAll("_", " ")+`)</span>` +
                        `<div class="card">` +
                            `<div class="card-body">` +
                                `<div class="row">` +
                                    `<div class='fv-row col-6'>` +
                                        `<label class='col-form-label w-100'>Old (`+tp.replaceAll("_", " ")+`)</label>` +
                                        `<label class='mt-1'>${item['old']['label']}</label>` +
                                        `<input type='hidden' name='update[${key}][old]' value='${item['old']['label']}'>` +
                                    `</div>` +
                                    `<div class='fv-row col-6'>` +
                                        `<label class='col-form-label w-100'>New (`+tp.replaceAll("_", " ")+`)</label>` +
                                        `<label class='mt-1'>${item['new']['label']}</label>` +
                                        `<input type='hidden' name='update[${key}][new]' value='${item['new']['val']}'>` +
                                    `</div>` +
                                    `<div class='fv-row col-6'>` +
                                        `<label class='col-form-label w-100'>Start date</label>` +
                                        `<label class='mt-1'>${item['start_date']['label']}</label>` +
                                        `<input type='hidden' name='update[${key}][start_date]' value='${item['start_date']['val']}'>` +
                                    `</div>` +
                                    `<div class='fv-row col-6'>` +
                                        `<label class='col-form-label w-100'>End date</label>` +
                                        `<label class='mt-1'>${item['end_date']['label']}</label>` +
                                        `<input type='hidden' name='update[${key}][end_date]' value='${item['end_date']['val']}'>` +
                                    `</div>` +
                                    elApprove +
                                `</div>` +
                                `<div class="row mt-5">` +
                                    `<div class='fv-row col-6'>` +
                                        `<button type="button" onclick="remove_transfer_form(this, '${tp}')" class='btn btn-sm btn-danger col-12'>Cancel</button>` +
                                    `</div>` +
                                    `<div class='fv-row col-6'>` +
                                        `<button type="button" data-json='${JSON.stringify(item)}' onclick="show_transfer_form(${id}, '${tp}', this)" class='btn btn-sm btn-primary col-12'>Edit</button>` +
                                    `</div>` +
                                `</div>` +
                            `</div>` +
                        `</div>` +
                    `</div>`

                    $("#modal_transfer [data-transfer]").append(el)
            }
        }

        function remove_transfer_form(me, type){
            transfer[type] = {}
            $(me).parents("div.data-item").remove()
        }

        function show_transfer_form(id, type, _data = null){

            var data = null
            if(_data != null){
                data = $(_data).data("json")
            }

            $.ajax({
                url : "{{ route('personel.employee_table.index') }}?a=transfer_form&id=" + id + "&type=" + type,
                type : "get",
                data : {
                    edit : data
                },
                dataType : "json"
            }).then(function(resp){
                $("#transfer-form-content").html(resp.view)

                $("#modal_transfer_form").modal("show")

                $("#modal_transfer_form select[data-control=select2]").select2()

                $("#modal_transfer_form button[name=submit]").click(async function(){
                    var form = $(this).parents("form")
                    Swal.fire({
                        html: `<div class='alert alert-danger'><div class='d-flex flex-column'><div class='d-flex align-items-center mb-5'><i class='fi fi-rr-info text-danger me-2'></i><span class='text-dark'>Warning</span></div><p class='text-dark text-start'>Are you sure to change employee information? This change can result in changes to detailed employee information, and sending a request for approval to the person concerned.</p></div></div>`,
                        icon: "question",
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        cancelButtonText: 'No',
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: 'btn text-primary'
                        }
                    }).then((resp) => {
                        if(resp.isConfirmed){
                            return $(form).find("button[type=submit]").click()
                        }
                    })
                })

                $("#modal_transfer_form button[name=submit]").off("click")
                $("#modal_transfer_form button[name=submit]").click(function(){
                    var tp = transfer[type] ?? {}
                    var _col = {}
                    var next = 0
                    $("#modal_transfer_form input, #modal_transfer_form select, #modal_transfer_form textarea").each(function(){
                        var col = {}
                        var name = $(this).attr("name")
                        var label = $(this).val()
                        if(name == "new" || name == "approved_by"){
                            label = $(this).find('option:selected').text()
                        }

                        if($(this).prop('required')){
                            if($(this).val() != ""){
                                next++
                            }
                        }

                        col['val'] = $(this).val()
                        col['label'] = label
                        _col[name] = col
                    })

                    if(next != $("#modal_transfer_form [required]").length){
                        return Swal.fire("Required", "Required field need to be filled", "error")
                    } else {
                        tp = _col
                        transfer[type] = tp
                        render_transfer_form(id)
                        $("#modal_transfer_form").modal("hide")
                    }
                })
            })
        }

        function show_transfer_form_batch(id, type){
            $.ajax({
                url : "{{ route('personel.employee_table.index') }}?a=transfer_form_batch&id=" + id + "&type=" + type,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#transfer-form-content").html(resp.view)

                $("#modal_transfer_form").modal("show")

                $("#modal_transfer_form select[data-control=select2]").select2()

                $("#modal_transfer_form button[name=submit]").click(async function(){
                    var form = $(this).parents("form")
                    Swal.fire({
                        html: `<div class='alert alert-danger'><div class='d-flex flex-column'><div class='d-flex align-items-center mb-5'><i class='fi fi-rr-info text-danger me-2'></i><span class='text-dark'>Warning</span></div><p class='text-dark text-start'>Are you sure to change employee information? This change can result in changes to detailed employee information, and sending a request for approval to the person concerned.</p></div></div>`,
                        icon: "question",
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        cancelButtonText: 'No',
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: 'btn text-primary'
                        }
                    }).then((resp) => {
                        if(resp.isConfirmed){
                            return $(form).find("button[type=submit]").click()
                        }
                    })
                })

                $("#modal_transfer_form button[name=submit]").off("click")
                $("#modal_transfer_form button[name=submit]").click(function(){
                    var tp = transfer[type] ?? {}
                    var _col = {}
                    var next = 0
                    $("#modal_transfer_form input, #modal_transfer_form select, #modal_transfer_form textarea").each(function(){
                        var col = {}
                        var name = $(this).attr("name")
                        var label = $(this).val()
                        if(name == "new" || name == "approved_by"){
                            label = $(this).find('option:selected').text()
                        }

                        if($(this).prop('required')){
                            if($(this).val() != ""){
                                next++
                            }
                        }

                        col['val'] = $(this).val()
                        col['label'] = label
                        _col[name] = col
                    })

                    if(next != $("#modal_transfer_form [required]").length){
                        return Swal.fire("Required", "Required field need to be filled", "error")
                    } else {
                        tp = _col
                        transfer[type] = tp
                        render_transfer_form(id)
                        $("#modal_transfer_form").modal("hide")
                    }
                })
            })
        }

        function tableFn(id, table){
            $(`${id} tbody tr td:first-child`).off("click")
            $(`${id} tbody tr td:first-child`).click(function(){
                var tr = $(this).parents("tr")
                if($(tr).hasClass("selected")){
                    table.row(tr).deselect()
                    $(this).find(".form-check-input").prop('checked', false)
                } else {
                    table.row(tr).select()
                    $(this).find(".form-check-input").prop('checked', true)
                }

                var all_row = table.rows()[0].length
                var selected_row = table.rows({selected: true})[0].length
                if(selected_row != all_row){
                    $(`${id} thead input[type=checkbox]`).prop("checked", false)
                }
            })

            $(`${id} thead input[type=checkbox]`).click(function(){
                var checked = this.checked
                if(checked){
                    table.rows().select()
                    $(`${id} tbody`).find(".form-check-input").prop('checked', true)
                } else {
                    table.rows().deselect()
                    $(`${id} tbody`).find(".form-check-input").prop('checked', false)
                }
            })

            $(`${id} tbody tr`).each(function(){
                if($(this).hasClass("selected")){
                    $(this).find(".form-check-input").prop('checked', true)
                } else {
                    $(this).find(".form-check-input").prop('checked', false)
                }
            })
        }

        $(document).ready(function(){
            var tb = initTable($("#table-leave-balance"))
            $("input[name=search_table]").on("keyup", function(){
                tb.search($(this).val()).draw()
            })

            tb.select();

            tableFn("#table-leave-balance", tb)

            tb.on("init draw", function(){
                tableFn("#table-leave-balance", tb)
            })

            var tb_resign = initTable($("#table-resign"))

            $("#btn-batch-transfer").click(function(){
                var selected = tb.rows({selected: true})
                var data = selected.data()
                var count = data.length
                var locs = []
                var form = $(this).parents("form")
                for (let i = 0; i < data.length; i++) {
                    const element = data[i];
                    var dom_nodes = $($.parseHTML(element[0]));
                    var ck = $(dom_nodes).find("input[type=checkbox]").val()
                    locs.push(ck)
                }

                transfer_emp_batch(locs)
            })

            @if(Session::has("drawer"))
                @if(Session::get("drawer") != null)
                    drawer.show()
                    show_detail({{Session::get("drawer")}})
                @endif
            @endif

            @if(Session::has("fl"))
                @if(Session::get("fl") != null)
                    drawer.show()
                    formal_letter({{Session::get("fl")}})
                @endif
            @endif

            $("#btn-export-data").click(function(){
                // Activate indicator
                var button = this
                button.setAttribute("data-kt-indicator", "on");

                $.ajax({
                    url : "{{ route("personel.employee_table.export") }}",
                    data : {
                        _token : "{{ csrf_token() }}",
                    },
                    type : "post",
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success : function(result, status, xhr){
                        var disposition = xhr.getResponseHeader('content-disposition');
                        var matches = /"([^"]*)"/.exec(disposition);
                        var filename = "data_personel.xlsx";

                        // The actual download
                        var blob = new Blob([result], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;

                        document.body.appendChild(link);

                        link.click();
                        document.body.removeChild(link);
                        button.removeAttribute("data-kt-indicator");
                    }
                })
            })
        })
    </script>
@endsection
