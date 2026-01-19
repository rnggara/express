@extends('_attendance.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-50px me-3">
            <div class="symbol-label bg-primary">
                <i class="fi fi-rr-calendar-clock text-white fs-1"></i>
            </div>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-3 fw-bold">Cuti Karyawan</span>
            <span class="text-muted">Managemen pengajuan, perpanjangan & penjualan cuti karyawan</span>
        </div>
    </div>
    <div class="d-flex flex-column">
        <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
            <li class="nav-item">
                <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_leave_balance">
                    <span class="nav-text">Leave Balance</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_leave_history">
                    <span class="nav-text">Leave History</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_leave_request">
                    <span class="nav-text">Leave Request</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_extend_leave">
                    <span class="nav-text">Extend Leave</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_sold_leave">
                    <span class="nav-text">Sold Leave</span>
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab_leave_balance" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="position-relative me-5">
                                    <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                    <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#kt_filter_balance_body_1">
                                    <i class="fi fi-rr-filter"></i>
                                    Filter
                                </button>
                            </div>
                            <div class="accordion mb-5" id="kt_filter_balance">
                                <div class="accordion-item bg-transparent border-0">
                                    <div id="kt_filter_balance_body_1" class="accordion-collapse collapse show" aria-labelledby="kt_filter_balance_header_1" data-bs-parent="#kt_filter_balance">
                                        <div class="accordion-body px-0">
                                            <div class="d-flex align-items-center">
                                                <select name="fleavegroup" onchange="loadTbl()" class="form-select" data-control="select2" data-placeholder="Select Leave Type" id="">
                                                    @foreach ($leaveCat as $item)
                                                        <option value="{{ $item->leave_used }}">{{ $item->leave_reason_name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fdepartement" onchange="loadTbl()" class="form-select" data-control="select2" data-placeholder="Select Departement" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="scroll min-h-250px w-100" id="leavebl">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab_leave_history" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="position-relative me-5">
                                    <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                    <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#kt_filter_leave_history_body_1">
                                    <i class="fi fi-rr-filter"></i>
                                    Filter
                                </button>
                            </div>
                            <div class="accordion mb-5" id="kt_filter_leave_history">
                                <div class="accordion-item bg-transparent border-0">
                                    <div id="kt_filter_leave_history_body_1" class="accordion-collapse collapse" aria-labelledby="kt_filter_leave_history_header_1" data-bs-parent="#kt_filter_leave_history">
                                        <div class="accordion-body px-0">
                                            <div class="d-flex align-items-center">
                                                <select name="freasontype" class="form-select" data-control="select2" data-placeholder="Select Reason Type" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                    @foreach ($reason_types as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Select Time" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Select Workgroup" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Select Departement" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="scroll">
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-leave-request">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Departement</th>
                                            <th>Date Request</th>
                                            <th>Reason</th>
                                            <th>Duration</th>
                                            <th>Note</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leave_history as $item)
                                            @php
                                                $d1 = date_create($item->start_date);
                                                $d2 = date_create($item->end_date);
                                                $diff = date_diff($d1, $d2);
                                                $days = $diff->format("%a");
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <img src="{{ asset($uImg[$item->emp_id] ?? "theme/assets/media/avatars/blank.png") }}" alt="">
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a class="fw-bold text-dark" href="{{ route("attendance.registration.detail", $item->emp->id) }}">{{ $item->emp->emp_name }}</a>
                                                            <span class="text-muted">{{ $item->emp->emp_id }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->emp->user->uacdepartement->name ?? "-" }}</td>
                                                <td>{{ date("d M Y", strtotime($item->created_at)) }}</td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold">{{ ucwords($item->leave_used) }} Leave</span>
                                                        <span class="text-muted">{{ date("F Y", strtotime($item->created_at)) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold">{{ $item->total_day }} Days</span>
                                                        <span class="text-muted">{{ date("d M Y", strtotime($item->start_date)) }} - {{ date("d M Y", strtotime($item->end_date)) }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $item->notes }}</td>
                                                <td>
                                                    @if (empty($item->approved_at) && empty($item->rejected_at))
                                                        <button type="button" class="btn btn-outline btn-outline-warning">Persetujuan</button>
                                                    @else
                                                        @if (!empty($item->approved_at))
                                                            <button type="button" class="btn btn-outline btn-outline-success">Approved</button>
                                                        @else
                                                            <button type="button" class="btn btn-outline btn-outline-danger">Rejected</button>
                                                        @endif
                                                    @endif
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
            <div class="tab-pane fade" id="tab_leave_request" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="position-relative me-5">
                                    <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                    <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#kt_filter_leave_request_body_1">
                                    <i class="fi fi-rr-filter"></i>
                                    Filter
                                </button>
                            </div>
                            <div class="accordion mb-5" id="kt_filter_leave_request">
                                <div class="accordion-item bg-transparent border-0">
                                    <div id="kt_filter_leave_request_body_1" class="accordion-collapse collapse" aria-labelledby="kt_filter_leave_request_header_1" data-bs-parent="#kt_filter_leave_request">
                                        <div class="accordion-body px-0">
                                            <div class="d-flex align-items-center">
                                                <select name="freasontype" class="form-select" data-control="select2" data-placeholder="Select Reason Type" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                    @foreach ($reason_types as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Select Time" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Select Workgroup" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Select Departement" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="scroll">
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-leave-request">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Departement</th>
                                            <th>Date Request</th>
                                            <th>Reason</th>
                                            <th>Duration</th>
                                            <th>Note</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($request_leave as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <img src="{{ asset($uImg[$item->emp_id] ?? "theme/assets/media/avatars/blank.png") }}" alt="">
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a class="fw-bold text-dark" href="{{ route("attendance.registration.detail", $item->emp->id) }}">{{ $item->emp->emp_name }}</a>
                                                            <span class="text-muted">{{ $item->emp->emp_id }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->emp->user->uacdepartement->name ?? "-" }}</td>
                                                <td>{{ date("d M Y", strtotime($item->created_at)) }}</td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold">{{ ucwords($item->leave_used) }} Leave</span>
                                                        <span class="text-muted">{{ date("F Y", strtotime($item->created_at)) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold">{{ $item->total_day }} Days</span>
                                                        <span class="text-muted">{{ date("d M Y", strtotime($item->start_date)) }} - {{ date("d M Y", strtotime($item->end_date)) }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $item->notes }}</td>
                                                <td>
                                                    @if (empty($item->approved_at) && empty($item->rejected_at))
                                                        <button type="button" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('request', {{ $item->id }})" class="btn btn-outline btn-outline-warning">Persetujuan</button>
                                                    @else
                                                        @if (!empty($item->approved_at))
                                                            <button type="button" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('request', {{ $item->id }})" class="btn btn-outline btn-outline-success">Approved</button>
                                                        @else
                                                            <button type="button" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('request', {{ $item->id }})" class="btn btn-outline btn-outline-danger">Rejected</button>
                                                        @endif
                                                    @endif
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
            <div class="tab-pane fade" id="tab_extend_leave" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="position-relative me-5">
                                    <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                    <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#kt_filter_sold_leave_body_1">
                                    <i class="fi fi-rr-filter"></i>
                                    Filter
                                </button>
                            </div>
                            <div class="accordion mb-5" id="kt_filter_sold_leave">
                                <div class="accordion-item bg-transparent border-0">
                                    <div id="kt_filter_sold_leave_body_1" class="accordion-collapse collapse" aria-labelledby="kt_filter_sold_leave_header_1" data-bs-parent="#kt_filter_sold_leave">
                                        <div class="accordion-body px-0">
                                            <div class="d-flex align-items-center">
                                                <select name="fstatus" class="form-select" multiple data-control="select2" data-placeholder="Select Status" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                    <option value="persetujuan">Persetujuan</option>
                                                    <option value="approved">Approved</option>
                                                    <option value="selesai">Selesai</option>
                                                    <option value="reject">Rejected</option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Select Departement" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="scroll">
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-leave-request">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Departement</th>
                                            <th>Date Request</th>
                                            <th>Sisa Cuti</th>
                                            <th>Old Expired Date</th>
                                            <th>New Expired Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leave_extend as $item)
                                            @php
                                                $sisa = 0;
                                                $lg = collect($epl[$item->emp_id])->first()[$item->type];
                                                $sisa = $lg->jatah ?? 0;
                                                $oldExpired = $lg->start_periode;
                                                $sisa -= $lg['used'] ?? 0;
                                                $sisa -= $lg['reserved'] ?? 0;
                                                $sisa -= $lg['sold'] ?? 0;
                                                $sisa += $lg['anulir'] ?? 0;
                                                $newExpired = date("Y-m-d", strtotime("$oldExpired +$item->months months"));
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <img src="{{ asset($uImg[$item->emp_id] ?? "theme/assets/media/avatars/blank.png") }}" alt="">
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a class="fw-bold text-dark" href="{{ route("attendance.registration.detail", $item->emp->id) }}">{{ $item->emp->emp_name }}</a>
                                                            <span class="text-muted">{{ $item->emp->emp_id }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->emp->user->uacdepartement->name ?? "-" }}</td>
                                                <td>{{ date("d M Y", strtotime($item->created_at)) }}</td>
                                                <td>{{ $sisa }}</td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold">{{ date("d M Y", strtotime($oldExpired)) }}</span>
                                                        <span class="text-muted">Periode Leave {{ date("Y", strtotime($oldExpired)) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ date("d M Y", strtotime($newExpired)) }}
                                                </td>
                                                <td>
                                                    @if (empty($item->approved_at) && empty($item->rejected_at))
                                                        <button type="button" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('extend', {{ $item->id }})" class="btn btn-outline btn-outline-warning">Persetujuan</button>
                                                    @else
                                                        @if (!empty($item->approved_at))
                                                            <button type="button" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('extend', {{ $item->id }})" class="btn btn-outline btn-outline-success">Approved</button>
                                                        @else
                                                            <button type="button" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('extend', {{ $item->id }})" class="btn btn-outline btn-outline-danger">Rejected</button>
                                                        @endif
                                                    @endif
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
            <div class="tab-pane fade" id="tab_sold_leave" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="position-relative me-5">
                                    <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                    <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#kt_filter_sold_leave_body_1">
                                    <i class="fi fi-rr-filter"></i>
                                    Filter
                                </button>
                            </div>
                            <div class="accordion mb-5" id="kt_filter_sold_leave">
                                <div class="accordion-item bg-transparent border-0">
                                    <div id="kt_filter_sold_leave_body_1" class="accordion-collapse collapse" aria-labelledby="kt_filter_sold_leave_header_1" data-bs-parent="#kt_filter_sold_leave">
                                        <div class="accordion-body px-0">
                                            <div class="d-flex align-items-center">
                                                <select name="fstatus" class="form-select" multiple data-control="select2" data-placeholder="Select Status" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                    <option value="persetujuan">Persetujuan</option>
                                                    <option value="approved">Approved</option>
                                                    <option value="selesai">Selesai</option>
                                                    <option value="reject">Rejected</option>
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Select Departement" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="scroll">
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-leave-request">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Departement</th>
                                            <th>Date Request</th>
                                            <th>Cuti Ditukarkan</th>
                                            <th>Pembayaran</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leave_sold as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <img src="{{ asset($uImg[$item->emp_id] ?? "theme/assets/media/avatars/blank.png") }}" alt="">
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a class="fw-bold text-dark" href="{{ route("attendance.registration.detail", $item->emp->id) }}">{{ $item->emp->emp_name }}</a>
                                                            <span class="text-muted">{{ $item->emp->emp_id }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->emp->user->uacdepartement->name ?? "-" }}</td>
                                                <td>{{ date("d M Y", strtotime($item->created_at)) }}</td>
                                                <td>{{ $item->days }}</td>
                                                <td>25 {{ date("F Y", strtotime($item->periode)) }}</td>
                                                <td>
                                                    @if (empty($item->approved_at) && empty($item->rejected_at))
                                                        <button type="button" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('sold', {{ $item->id }})" class="btn btn-outline btn-outline-warning">Persetujuan</button>
                                                    @else
                                                        @if (!empty($item->approved_at))
                                                            <button type="button" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('sold', {{ $item->id }})" class="btn btn-outline btn-outline-success">Approved</button>
                                                        @else
                                                            <button type="button" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('sold', {{ $item->id }})" class="btn btn-outline btn-outline-danger">Rejected</button>
                                                        @endif
                                                    @endif
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

<form action="{{ route("attendance.leave.request_leave") }}" method="post" enctype="multipart/form-data">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-lg"
        ])
        @slot('modalId')
            modal_create_request_leave
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-comment-pen text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Add Leave Request</h3>
                    <span class="text-muted fs-base">Tambahkan data pengajuan cuti untuk karyawan</span>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <div class="fv-row">
                <label class="col-form-label fw-bold">Employee Name</label>
                <select name="emp" class="form-select" data-control="select2" data-placeholder="Input Employee by Name or ID" data-dropdown-parent="#modal_create_request_leave" id="">
                    <option value=""></option>
                    @foreach ($emp as $item)
                        <option value="{{ $item->id }}" {{ (old("emp") ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->emp_name }}</option>
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
                    <label class="col-form-label fw-bold">Reason Type</label>
                    <select name="reason" class="form-select" data-control="select2" data-placeholder="Select Reason Type" data-dropdown-parent="#modal_create_request_leave" id="">
                        <option value=""></option>
                        @foreach ($reason_types as $item)
                            <option value="{{ $item->id }}" {{ (old("reason") ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('reason')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label fw-bold">Reason Used</label>
                    <select name="leave_used" class="form-select" data-control="select2" data-placeholder="Select Reason Used" data-dropdown-parent="#modal_create_request_leave" id="">
                        <option value=""></option>
                        @foreach ($rcon as $item)
                            <option value="{{ $item->id }}" data-tp="{{ $item->reason_type_id }}" {{ (old("leave_used") ?? null) == $item->id ? "SELECTED" : "" }} {{ (old("reason") ?? null) == $item->reason_type_id ? "" : "disabled" }}>{{ $item->reasonName->reason_name }}</option>
                        @endforeach
                        {{-- <option value="annual" {{ (old("leave_used") ?? null) == "annual" ? "SELECTED" : "" }}>Annual Leave</option>
                        <option value="long" {{ (old("leave_used") ?? null) == "long" ? "SELECTED" : "" }}>Long Leave</option>
                        <option value="special" {{ (old("leave_used") ?? null) == "special" ? "SELECTED" : "" }}>Special Leave</option> --}}
                    </select>
                    @error('leave_used')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="fv-row col-6">
                    <label class="col-form-label fw-bold">Date</label>
                    <input type="text" name="date" drpicker value="{{ old("date") }}" class="form-control">
                    @error('date')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label fw-bold">Reference Number</label>
                    <input type="text" name="ref_num" value="{{ old("ref_num") }}" class="form-control" placeholder="Input Data">
                    @error('ref_num')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="fv-row col-12">
                    <label class="col-form-label fw-bold">Detail Note</label>
                    <textarea name="notes" class="form-control" id="" cols="30" rows="10">{{ old("notes") }}</textarea>
                    @error('notes')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row col-12 mt-5">
                    <div class="d-flex align-items-center" data-toggle="file">
                        <label class="btn btn-secondary btn-sm me-5">
                            <span>Add File <i class="fi fi-rr-clip"></i></span>
                            <input type="file" name="attachment" data-toggle="file" accept=".jpg, .png, .pdf" class="d-none" id="">
                        </label>
                        <span class="text-primary" data-file-label></span>
                    </div>
                    <div class="d-flex flex-column mt-5 text-muted">
                        <span>File Format : JPG, PNG, PDF</span>
                        <span>Max 25 mb</span>
                    </div>
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

<form action="{{ route("attendance.leave.sold_leave") }}" method="post" enctype="multipart/form-data">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-lg"
        ])
        @slot('modalId')
            modal_create_sold_leave
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-calendar text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Sold Leave</h3>
                    <span class="text-muted fs-base">Tambahkan pengajuan cuti diuangkan untuk karyawan</span>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <div class="fv-row">
                <label class="col-form-label fw-bold">Employee Name</label>
                <select name="emp" class="form-select" data-control="select2" data-placeholder="Input Employee by Name or ID" data-dropdown-parent="#modal_create_sold_leave" id="">
                    <option value=""></option>
                    @foreach ($emp as $item)
                        <option value="{{ $item->id }}" {{ (old("emp") ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->emp_name }}</option>
                    @endforeach
                </select>
                @error('emp')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="row d-none mt-5" data-toggle="sold-detail">

            </div>
            <div class="row">
                <div class="fv-row col-4">
                    <label class="col-form-label fw-bold">Type Leave</label>
                    <select name="type_leave" class="form-select" data-control="select2" data-placeholder="Select Type Leave" data-dropdown-parent="#modal_create_sold_leave" id="">
                        <option value=""></option>
                        <option value="annual" {{ (old("type_leave") ?? null) == "annual" ? "SELECTED" : "" }}>Annual Leave</option>
                        <option value="long" {{ (old("type_leave") ?? null) == "long" ? "SELECTED" : "" }}>Long Leave</option>
                        <option value="special" {{ (old("type_leave") ?? null) == "special" ? "SELECTED" : "" }}>Special Leave</option>
                    </select>
                    @error('type_leave')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row col-4">
                    <label class="col-form-label fw-bold">Total Cuti Diuangkan</label>
                    <input type="number" name="days" class="form-control" value="{{ old("days") }}" min="1" id="">
                    @error('days')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row col-4">
                    <label class="col-form-label fw-bold">Periode Pembayaran</label>
                    <select name="periode" class="form-select" data-control="select2" data-placeholder="Select Periode Pembayaran" data-dropdown-parent="#modal_create_sold_leave" id="">
                        <option value=""></option>
                        @for ($i = 1; $i<=12; $i++)
                            <option value="{{ date("Y")."-".sprintf("%02d", $i) }}" {{ (old("periode") ?? null) == date("Y")."-".sprintf("%02d", $i) ? "SELECTED" : "" }}>{{ date("F Y", strtotime(date("Y")."-".sprintf("%02d", $i))) }}</option>
                        @endfor
                        @for ($i = 1; $i<=12; $i++)
                            <option value="{{ (date("Y") + 1)."-".sprintf("%02d", $i) }}" {{ (old("periode") ?? null) == (date("Y") + 1)."-".sprintf("%02d", $i) ? "SELECTED" : "" }}>{{ date("F Y", strtotime((date("Y") + 1)."-".sprintf("%02d", $i))) }}</option>
                        @endfor
                    </select>
                    @error('periode')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
            <button type="submit" class="btn btn-primary">Save</button>
        @endslot
    @endcomponent
</form>

<form action="{{ route("attendance.leave.extend_leave") }}" method="post" enctype="multipart/form-data">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-lg"
        ])
        @slot('modalId')
            modal_create_extend_leave
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-calendar text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Extend Expired Leave</h3>
                    <span class="text-muted fs-base">Tambahkan data perpanjangan cuti untuk karyawan</span>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <div class="fv-row">
                <label class="col-form-label fw-bold">Employee Name</label>
                <select name="emp" class="form-select" data-control="select2" data-placeholder="Input Employee by Name or ID" data-dropdown-parent="#modal_create_extend_leave" id="">
                    <option value=""></option>
                    @foreach ($emp as $item)
                        <option value="{{ $item->id }}" {{ (old("emp") ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->emp_name }}</option>
                    @endforeach
                </select>
                @error('emp')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="row d-none mt-5" data-toggle="sold-detail">

            </div>
            <div class="d-flex align-items-center">
                <div class="fv-row">
                    <label class="col-form-label fw-bold">Type Leave</label>
                    <select name="type_leave" class="form-select" data-control="select2" data-placeholder="Select Type Leave" data-dropdown-parent="#modal_create_extend_leave" id="">
                        <option value=""></option>
                        <option value="annual" {{ (old("type_leave") ?? null) == "annual" ? "SELECTED" : "" }}>Annual Leave</option>
                        <option value="long" {{ (old("type_leave") ?? null) == "long" ? "SELECTED" : "" }}>Long Leave</option>
                    </select>
                    @error('type_leave')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mx-3"></div>
                <div class="fv-row annual-leave d-none">
                    <label class="col-form-label fw-bold">Periode Leave</label>
                    <select name="periode" class="form-select" data-control="select2" data-placeholder="Select Periode Pembayaran" data-dropdown-parent="#modal_create_extend_leave" id="">

                    </select>
                    @error('periode')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mx-3"></div>
                <div class="fv-row">
                    <label class="col-form-label fw-bold">Total Bulan Extend</label>
                    <input type="number" name="months" class="form-control" value="{{ old("months") }}" min="1" id="">
                    @error('months')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
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

@component('layouts.components.fab', [
    "fab" => [
        ["label" => "Add Overtime", 'url' => route("attendance.overtime.index")."?modal=modal_add_overtime"],
        ["label" => "Create Request Leave", "url" => "javascript:;", "toggle" => 'data-bs-toggle="modal" data-bs-target="#modal_create_request_leave"'],
        ["label" => "Create Sold Leave", "url" => "javascript:;", "toggle" => 'data-bs-toggle="modal" data-bs-target="#modal_create_sold_leave"'],
        ["label" => "Create Extend Leave", "url" => "javascript:;", "toggle" => 'data-bs-toggle="modal" data-bs-target="#modal_create_extend_leave"'],
    ]
])
@endcomponent

<div class="modal fade" tabindex="1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                    <span class="fw-bold fs-3 text-center">Apakah Anda yakin ingin membatalkan pengajuan Overtime ini?</span>
                    <span class="text-center">Melakukan ini dapat mempengaruhi data kehadiran dari employee</span>
                    <form action="{{ route('attendance.approval.approve') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="type" value="leave">
                        <div class="d-flex align-items-center mt-5">
                            <button type="submit" name="submit" value="cancel" id="delete-url" class="btn btn-white">Lanjutkan</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batalkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('view_script')
    <script>
        $("input[data-toggle=file]").change(function(){
            var path = $(this).val().toString().split("\\")
            var fname = path[path.length - 1]

            var pr = $(this).parents("[data-toggle=file]")

            pr.find("[data-file-label]").text(fname)
        })

        function batalkan(id, type){
            $("#modalDelete input[name=id]").val(id)
            $("#modalDelete button[name=submit]").val(type)
            $("#modalDelete").modal("show")
        }

        function drpicker(header){
            $(`${header} input[drpicker]`).daterangepicker({
                locale: {
                    format: "DD/MM/YYYY"
                }
            });
        }

        function openDetail(me, target){
            var par = $(`[data-toggle="${target}"]`).parent()
            if($(`[data-toggle="${target}"]`).hasClass("d-none")){
                $(`[data-toggle="${target}"]`).removeClass("d-none")
                $(me).find("i").removeClass("fi-rr-caret-down")
                $(me).find("i").addClass("fi-rr-caret-up")
                $(par).find("[data-border]").addClass("border border-primary")
            } else {
                $(`[data-toggle="${target}"]`).addClass("d-none")
                $(par).find("[data-border]").removeClass("border")
                $(me).find("i").addClass("fi-rr-caret-down")
                $(me).find("i").removeClass("fi-rr-caret-up")
            }
        }

        KTDrawer.createInstances();
        var drawerElement = document.querySelector("#kt_drawer_detail");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");
        var blockUI = new KTBlockUI(target);

        var blockBl = new KTBlockUI(document.querySelector("#leavebl"))

        var tb = null

        function loadTbl(){
            var data = {
                leave_group : $("select[name=fleavegroup]").val(),
                dept : $("select[name=fdepartement]").val(),
                e : "balance"
            }

            blockBl.block()

            $("#leavebl").html("")

            $.ajax({
                url : "{{ route("attendance.leave.index") }}?a=table",
                type : "get",
                data : data,
                dataType : "json"
            }).then(function(resp){
                blockBl.release()
                $("#leavebl").html(resp.view)
                tb = initTable($("#table-leave-balance"))
            })
        }

        function editState(me){
            var edit = false
            if($(me).hasClass("btn-primary")){
                edit = false
                $(me).addClass("btn-icon")
                $(me).removeClass("btn-primary")
                $(me).find("i").removeClass("d-none")
                $(me).find("span").addClass("d-none")
            } else {
                edit = true
                $(me).removeClass("btn-icon")
                $(me).addClass("btn-primary")
                $(me).find("i").addClass("d-none")
                $(me).find("span").removeClass("d-none")
            }

            var data = tb.rows().data()
            for (let i = 0; i < data.length; i++) {
                const element = data[i];
                var n = element.length - 1
                var action = element[n]
                var det = $(action).eq(0)
                var ed = $(action).eq($(action).length - 1)
                if(edit){
                    $(det).addClass("d-none")
                    $(ed).removeClass("d-none")
                } else {
                    $(det).removeClass("d-none")
                    $(ed).addClass("d-none")
                }

                action = $(det).prop('outerHTML')
                action += $(ed).prop('outerHTML')
                element[n] = action
                tb.row(i).data(element).draw()
            }
        }

        function editLeave(id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{route("attendance.leave.detail")}}/edit-leave/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)

                $("#kt_drawer_detail [data-control=select2]").select2()

                $("#kt_drawer_detail [data-c]").on("keyup change", function(){
                    if($(this).val() == ""){
                        $(this).val(0)
                    }
                    var pr = $(this).parents("[data-cc]").eq(0)
                    var jatah = $(pr).find("[data-c=jatah]").val()
                    var used = $(pr).find("[data-c=used]").val()
                    var reserve = $(pr).find("[data-c=reserve]").val()
                    var sisa = $(pr).find("[data-c=sisa]")
                    console.log(jatah, used, reserve)

                    var _sisa = jatah - (parseFloat(used) + parseFloat(reserve))
                    $(sisa).val(_sisa)
                })
            })
        }

        function show_detail(type, id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{route("attendance.leave.detail")}}/" + type + "/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)

                $("#kt_drawer_detail [data-control=select2]").select2()
            })
        }


        $(document).ready(function(){

            loadTbl()

            drpicker("#modal_create_request_leave")
            @if(Session::has("modal"))
                $("#{{ Session::get("modal") }}").modal("show")
            @endif

            @if(Session::has("drawer"))
                @if(Session::get("drawer") != null)
                    drawer.show()
                    show_detail('{{ Session::get("type") }}',{{Session::get("drawer")}})
                @endif
            @endif

            @if(Session::has("el"))
                @if(Session::get("el") != null)
                    drawer.show()
                    editLeave({{Session::get("el")}})
                @endif
            @endif

            @if(Session::has("tab"))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ Session::get("tab") }}']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif

            $("#modal_create_sold_leave select[name=emp]").change(function(){
                var modal = $(this).parents(".modal")
                $.ajax({
                    url : "{{ route("attendance.leave.index") }}?a=table&e=sold&id=" + $(this).val(),
                    type : "get",
                    dataType : "json"
                }).then(function(resp){
                    console.log(resp)
                    $('#modal_create_sold_leave [data-toggle="sold-detail"]').removeClass("d-none")
                    $('#modal_create_sold_leave [data-toggle="sold-detail"]').html(resp.view)
                })
            })

            $("#modal_create_request_leave select[name=reason]").change(function(){
                var id = $(this).val()
                $("#modal_create_request_leave select[name=leave_used]").val("").trigger("change")
                $("#modal_create_request_leave select[name=leave_used] option[data-tp]").prop("disabled", true)
                $("#modal_create_request_leave select[name=leave_used] option[data-tp='"+id+"']").prop("disabled", false)
            })

            $("#modal_create_extend_leave select[name=type_leave]").change(function(){
                $("#modal_create_extend_leave .annual-leave").addClass('d-none')
                if($(this).val() == "annual"){
                    $("#modal_create_extend_leave .annual-leave").removeClass('d-none')
                }
            })

            $("#modal_create_extend_leave select[name=emp]").change(function(){
                var modal = $(this).parents(".modal")
                $.ajax({
                    url : "{{ route("attendance.leave.index") }}?a=table&e=extend&id=" + $(this).val(),
                    type : "get",
                    dataType : "json"
                }).then(function(resp){
                    console.log(resp)
                    $('#modal_create_extend_leave [data-toggle="sold-detail"]').removeClass("d-none")
                    $('#modal_create_extend_leave [data-toggle="sold-detail"]').html(resp.view)
                    var options = ``
                    var periode = resp.periode
                    for (let i = 0; i < periode.length; i++) {
                        const element = periode[i];
                        options += `<option value="${element['id']}">${element['text']}</option>`

                    }
                    $("#modal_create_extend_leave select[name=periode]").html(options)
                })
            })
        })
    </script>
@endsection
