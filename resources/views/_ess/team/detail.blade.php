@extends('_ess.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center">
        <div class="symbol symbol-50px me-3">
            <img src="{{ asset($emp->user->user_img ?? "theme/assets/media/avatars/blank.png") }}" class="w-100" alt="">
        </div>
        <div class="d-flex flex-column">
            <span class="fw-bold">{{ $emp->emp_name }}</span>
            <span class="text-muted">{{ $emp->emp_id }} - {{ $emp->user->uacdepartement->name ?? "-" }}</span>
        </div>
    </div>
    <div class="d-flex flex-column">
        <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
            <li class="nav-item">
                <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_general">
                    <span class="nav-text">Data</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_attendance">
                    <span class="nav-text">Kehadiran</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_overtime">
                    <span class="nav-text">Lembur</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_leave">
                    <span class="nav-text">Cuti</span>
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab_general" role="tabpanel">
                <div class="row">
                    <div class="col-3">
                        <div class="p-10 rounded bg-secondary-crm">
                            <div class="d-flex flex-column">
                                <div class="bg-white rounded row p-5 mb-5">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-40px me-5">
                                                <div class="symbol-label bg-light-primary">
                                                    <span class="fi fi-rr-user text-primary fs-3"></span>
                                                </div>
                                            </div>
                                            <span class="fw-bold fs-3">Data Umum</span>
                                        </div>
                                        <div class="separator separator-secondary my-5"></div>
                                        <div class="d-flex flex-column mb-5">
                                            <span class="text-muted">Departement</span>
                                            <span class="fw-bold">{{ $emp->user->uacdepartement->name ?? "-" }}</span>
                                        </div>
                                        <div class="d-flex flex-column mb-5">
                                            <span class="text-muted">Workgroup</span>
                                            <span class="fw-bold">{{ $reg->wg->workgroup_name }}</span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-muted">Leave Group</span>
                                            <span class="fw-bold">{{ $reg->leave->leave_group_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="p-10 rounded bg-secondary-crm">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-5">
                                            <div class="symbol-label bg-light-primary">
                                                <span class="fi fi-rr-calendar text-primary fs-3"></span>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold fs-3">Schedule</span>
                                            <span class="text-muted">Employee Schedule</span>
                                        </div>
                                    </div>
                                    <div class="bg-white px-5 py-3 rounded">
                                        <div class="d-flex align-items-center justify-content-around">
                                            <button class="btn btn-sm" onclick="loadSchedule('prev')" data-prev disabled>
                                                <i class="fi fi-rr-caret-left"></i>
                                            </button>
                                            <span class="fw-bold">{{ date("F Y") }}</span>
                                            <input type="hidden" name="sch_date" value="{{ date("Y-m") }}">
                                            <button class="btn btn-sm" onclick="loadSchedule('next')" data-next disabled>
                                                <i class="fi fi-rr-caret-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <table class="table bg-white" id="table-schedule">
                                        <thead>
                                            <tr>
                                                @foreach ($dayFull as $item)
                                                    <th class="text-center">{{ $item }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab_attendance" role="tabpanel">
                <div class="row bg-secondary-crm rounded p-5">
                    <div class="col-12">
                        <div class="card shadow-none">
                            <div class="card-header border-0">
                                <div class="card-title">Performa Karyawan <span class="text-muted">(Year to Date)</span></div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2">
                                        <div class="d-flex flex-column">
                                            <span class="fs-3 fw-bold text-muted mb-5">Attendance Perform</span>
                                            <span class="text-primary fs-3 fw-bold" data-att-perform>0%</span>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="d-flex flex-column">
                                            <span class="fs-3 fw-bold text-muted mb-5">Absence Rate</span>
                                            <span class="text-primary fs-3 fw-bold" data-att-absence>0%</span>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="d-flex flex-column">
                                            <span class="fs-3 fw-bold text-muted mb-5">Total Kehadiran</span>
                                            <span class="text-primary fs-3 fw-bold" data-att-sum>0</span>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="d-flex flex-column">
                                            <span class="fs-3 fw-bold text-muted mb-5">Total Mangkir</span>
                                            <span class="text-primary fs-3 fw-bold" data-att-mangkir>0</span>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="d-flex flex-column">
                                            <span class="fs-3 fw-bold text-muted mb-5">Total Cuti</span>
                                            <span class="text-primary fs-3 fw-bold" data-att-cuti>0</span>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="d-flex flex-column">
                                            <span class="fs-3 fw-bold text-muted mb-5">Total Sakit</span>
                                            <span class="text-primary fs-3 fw-bold" data-att-sakit>0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 p-5 bg-secondary-crm rounded">
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="px-5 py-3 rounded">
                                    <div class="d-flex align-items-center justify-content-around">
                                        <button class="btn btn-sm" onclick="loadAtt('prev')" data-prev disabled>
                                            <i class="fi fi-rr-caret-left"></i>
                                        </button>
                                        <span class="fw-bold">{{ date("F Y") }}</span>
                                        <input type="hidden" name="att_date" value="{{ date("Y-m") }}">
                                        <button class="btn btn-sm" onclick="loadAtt('next')" data-next disabled>
                                            <i class="fi fi-rr-caret-right"></i>
                                        </button>
                                    </div>
                                </div>
                                <table class="table border-0" id="table-attendance">
                                    <thead>
                                        <tr class="border-0">
                                            @foreach ($daySort as $item)
                                                <th class="text-center">{{ $item }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="{{ count($daySort) }}">
                                                <div class="d-flex justify-content-end">
                                                    <div class="d-flex align-items-center me-3">
                                                        <div class="me-3 symbol symbol-20px symbol-circle">
                                                            <div class="symbol-label bg-secondary"></div>
                                                        </div>
                                                        <span>Offday</span>
                                                    </div>
                                                    <div class="d-flex align-items-center me-3">
                                                        <div class="me-3 symbol symbol-20px symbol-circle">
                                                            <div class="symbol-label bg-success"></div>
                                                        </div>
                                                        <span>Hadir</span>
                                                    </div>
                                                    <div class="d-flex align-items-center me-3">
                                                        <div class="me-3 symbol symbol-20px symbol-circle">
                                                            <div class="symbol-label bg-warning"></div>
                                                        </div>
                                                        <span>Sakit</span>
                                                    </div>
                                                    <div class="d-flex align-items-center me-3">
                                                        <div class="me-3 symbol symbol-20px symbol-circle">
                                                            <div class="symbol-label bg-primary"></div>
                                                        </div>
                                                        <span>Cuti</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3 symbol symbol-20px symbol-circle">
                                                            <div class="symbol-label bg-danger"></div>
                                                        </div>
                                                        <span>Mangkir</span>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="d-flex align-items-center cursor-pointer">
                                    <div class="symbol symbol-40px me-5">
                                        <div class="symbol-label bg-light-primary">
                                            <span class="fi fi-rr-comment-user text-primary fs-3"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold fs-3">Koreksi Kehadiran</span>
                                        <span class="text-muted">Koreksi jam masuk & keluar</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="card shadow-none bg-transparent">
                            <div class="card-body" id="att-list">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab_overtime" role="tabpanel">
                <div class="row bg-secondary-crm rounded p-5">
                    <div class="col-4 p-5 bg-secondary-crm rounded">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="px-5 py-3 rounded">
                                    <div class="d-flex align-items-center justify-content-around">
                                        <button class="btn btn-sm" onclick="loadOvt('prev')" data-prev disabled>
                                            <i class="fi fi-rr-caret-left"></i>
                                        </button>
                                        <span class="fw-bold">{{ date("F Y") }}</span>
                                        <input type="hidden" name="ovt_date" value="{{ date("Y-m") }}">
                                        <button class="btn btn-sm" onclick="loadOvt('next')" data-next disabled>
                                            <i class="fi fi-rr-caret-right"></i>
                                        </button>
                                    </div>
                                </div>
                                <table class="table table-borderless border-0" id="table-overtime">
                                    <thead>
                                        <tr class="border-0">
                                            @foreach ($daySort as $item)
                                                <th class="text-center">{{ $item }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="{{ count($daySort) }}">
                                                <div class="d-flex justify-content-end">
                                                    <div class="d-flex align-items-center me-3">
                                                        <div class="me-3 symbol symbol-20px symbol-circle">
                                                            <div class="symbol-label bg-primary"></div>
                                                        </div>
                                                        <span>Ovt Holiday</span>
                                                    </div>
                                                    <div class="d-flex align-items-center me-3">
                                                        <div class="me-3 symbol symbol-20px symbol-circle">
                                                            <div class="symbol-label bg-success"></div>
                                                        </div>
                                                        <span>Ovt Workday</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3 symbol symbol-20px symbol-circle">
                                                            <div class="symbol-label bg-danger"></div>
                                                        </div>
                                                        <span>Ovt Offday</span>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="card shadow-none bg-transparent">
                            <div class="card-body">
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-ovt-list">
                                    <thead>
                                        <tr>
                                            <th>
                                                Name
                                            </th>
                                            <th>Overtime Type</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Paid By</th>
                                            <th>Allocation Departement</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overtime as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <div class="symbol-label" style="background-image: url({{ asset($uImg[$item->emp_id] ?? "theme/assets/media/avatars/blank.png") }})"></div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ $item->emp->emp_name }}</span>
                                                            <span class="text-muted">{{ $item->emp->user->uacdepartement->name ?? "-" }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold">Overtime {{ $item->reason->day_name }}</span>
                                                        <span>Overtime {{ ucwords($item->overtime_type) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column align-items-center">
                                                        <span class="fw-bold">{{ date("d F Y", strtotime($item->overtime_date)) }}</span>
                                                        <span>{{ date("H:i", strtotime($item->overtime_start_time)) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column align-items-center">
                                                        <span class="fw-bold">{{ date("d F Y", strtotime($item->overtime_date)) }}</span>
                                                        <span>{{ date("H:i", strtotime($item->overtime_end_time)) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ ucwords($item->paid) }}
                                                </td>
                                                <td>{{ $item->dept->name }}</td>
                                                <td>
                                                    <span class="badge badge-outline badge-success">Approved</span>
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
            <div class="tab-pane fade" id="tab_leave" role="tabpanel">
                <div class="row">
                    <div class="col-3">
                        <div class="p-10 rounded bg-secondary-crm">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold fs-3 me-2">Detail Cuti</span>
                                        <span class="text-muted">(This Year Period)</span>
                                    </div>
                                    <button type="button" class="btn btn-icon btn-sm" onclick="openDetail(this, 'drawer-detail-{{ $emp->id }}')">
                                        <i class="fi fi-rr-caret-down text-dark"></i>
                                    </button>
                                </div>
                                <div class="bg-white rounded row p-5" data-border>
                                    <div class="col-6">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted">Kuota Cuti</span>
                                            <span class="fs-3 fw-bold text-primary">{{ $leave['jatah'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted">Cuti Terpakai</span>
                                            <span class="fs-3 fw-bold">{{ $leave['used'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted">Sisa Cuti</span>
                                            <span class="fs-3 fw-bold text-primary">{{ $leave['jatah'] - $leave['used'] + $leave['reserve'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted">Reserve Cuti</span>
                                            <span class="fs-3 fw-bold">{{ $leave['reserve'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-none" data-toggle='drawer-detail-{{ $emp->id }}'>
                                    @foreach ($emp_leaves as $item)
                                        @php
                                            $jatah = $item->jatah ?? 0;
                                            $terpakai = $item->used - $item->anulir + $item->unrecorded;
                                            $reserved = $item->reserved ?? 0;
                                            $sold = $item->sold ?? 0;
                                            // $total_leaves = $item->leave["$item->type"."_total_leaves"] ?? [];
                                            // foreach ($total_leaves as $key => $vv) {
                                            //     $jatah += ($vv['total_leave'] ?? $vv['total_leaves']) ?? 0;
                                            // }
                                            $anulir = $item->anulir;
                                            $sisa = $jatah - $terpakai - $reserved - $sold;
                                        @endphp
                                        <div class="bg-white rounded row p-5 my-5 {{ date("Y-m-d") < $item->end_periode ? "" : "opacity-50" }}">
                                            <div class="col-12">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold fs-3 me-2">{{ ucwords($item->type) }} Leave</span>
                                                    <span class="text-muted">
                                                        @if (date("Y-m-d") < $item->end_periode)
                                                            Active until {{ date("d F Y", strtotime("$item->end_periode")) }}
                                                        @else
                                                            Expired {{ date("d F Y", strtotime("$item->end_periode")) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <hr>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex flex-column">
                                                    <span class="text-muted">Kuota Cuti</span>
                                                    <span class="fs-3 fw-bold text-primary">{{ $jatah }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex flex-column">
                                                    <span class="text-muted">Cuti Terpakai</span>
                                                    <span class="fs-3 fw-bold">{{ $terpakai + $sold }}</span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <hr>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex flex-column">
                                                    <span class="text-muted">Sisa Cuti</span>
                                                    <span class="fs-3 fw-bold text-primary">{{ $sisa}}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex flex-column">
                                                    <span class="text-muted">Reserve Cuti</span>
                                                    <span class="fs-3 fw-bold">{{ $reserved }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="p-5 rounded bg-secondary-crm">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-5">
                                            <div class="symbol-label bg-light-primary">
                                                <i class="fi fi-rr-calendar text-primary fs-2"></i>
                                            </div>
                                        </div>
                                        <h3>Riwayat Cuti</h3>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <select name="fleaveyear" class="form-select" data-control="select2">
                                            <option value="2023">2023</option>
                                        </select>
                                        <div class="mx-3"></div>
                                        <select name="fleavetype" class="form-select" data-placeholder="Select Type Leave" data-control="select2">
                                        </select>
                                        <div class="mx-3"></div>
                                        <select name="fleavestatus" class="form-select" data-placeholder="Select Status" data-control="select2">
                                            <option value=""></option>
                                            <option value="persetujuan">Persetujuan</option>
                                            <option value="approved">Approved</option>
                                            <option value="selesai">Selesai</option>
                                            <option value="reject">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-5">
                                    <table class="table table-display-2 bg-white" id="table-history-leave">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Request</th>
                                                <th>Type Leave</th>
                                                <th>Duration</th>
                                                <th>Note</th>
                                                <th style="width: 10%">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($request_leave as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ $hariId[date("N", strtotime($item->created_at))] }}</span>
                                                            <span class="text-muted">@dateId($item->created_at)</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ $item->rt->name }}</span>
                                                            <span class="text-muted">{{ ucwords($item->leave_used) }} Leave</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ $item->total_day }} Days</span>
                                                            <span class="text-muted">{{ date("d M Y", strtotime($item->start_date)) }} - {{ date("d M Y", strtotime($item->end_date)) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->notes }}</td>
                                                    <td style="width: 10%">
                                                        @if (empty($item->approved_at) && empty($item->rejected_at))
                                                            <label class="badge badge-outline badge-warning">Persetujuan</label>
                                                        @else
                                                            @if (!empty($item->approved_at))
                                                                <label class="badge badge-outline badge-success">Approved</label>
                                                            @else
                                                                <label class="badge badge-outline badge-danger">Rejected</label>
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
@endsection

@section('view_script')
<script src="{{ asset("theme/assets/plugins/custom/formrepeater/formrepeater.bundle.js") }}"></script>
<script>

    KTDrawer.createInstances();
    var drawerElement = document.querySelector("#kt_drawer_detail");
    var drawer = KTDrawer.getInstance(drawerElement);
    var target = document.querySelector("#drawer-content");
    var blockUI = new KTBlockUI(target);

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

    function openDetail(me, target){
        var par = $(`[data-toggle="${target}"]`).parent()
        if($(`[data-toggle="${target}"]`).hasClass("d-none")){
            $(`[data-toggle="${target}"]`).removeClass("d-none")
            $(par).find("[data-border]").addClass("border border-primary")
            $(me).find("i").removeClass("fi-rr-caret-down")
            $(me).find("i").addClass("fi-rr-caret-up")
        } else {
            $(`[data-toggle="${target}"]`).addClass("d-none")
            $(par).find("[data-border]").removeClass("border")
            $(me).find("i").addClass("fi-rr-caret-down")
            $(me).find("i").removeClass("fi-rr-caret-up")
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

    function add_bs(me){
        var ck = $(me).prop("checked")
        if(ck){
            $(me).parents("form").find(".break-shift").removeClass("d-none")
        } else {
            $(me).parents("form").find(".break-shift").addClass("d-none")
        }
    }

    function att_correction(date){
        $(target).html("")
        blockUI.block();
        $.ajax({
            url : "{{ route('attendance.correction.detail_edit', $reg->id) }}?date=" + date,
            type : "get",
            dataType : "json"
        }).then(function(resp){
            blockUI.release();
            $(target).html(resp.view)

            repeater_form("#drawer-content")

            $(target).find('select[data-control=select2]').select2()

            $(target).find("input[name=file]").change(function(){
                var val = $(this).val().split("\\")

                $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
            })

            $(target).find("select[name=overtime_type]").change(function(){
                $(target).find("input[name=start_date]").prop("disabled", false)
                $(target).find("input[name=end_date]").prop("disabled", false)

                $(target).find("input[name=start_date]").val("")
                $(target).find("input[name=end_date]").val("")
            })

            $(target).find("input[name=start_date]").change(function(){
                $(target).find("select[name=paid_type]").prop('disabled', false)
                $(target).find("select[name=departement]").prop('disabled', false)
                $(target).find("input[name=break_overtime]").prop('disabled', false)
            })
            $(target).find("input[name=end_date]").change(function(){
                $(target).find("select[name=paid_type]").prop('disabled', false)
                $(target).find("select[name=departement]").prop('disabled', false)
                $(target).find("input[name=break_overtime]").prop('disabled', false)
            })

            $(target).find("select[name=paid_type]").change(function(){
                var row = $(this).parents("div.row")
                $(target).find("input[name=reference]").prop("disabled", false)
                if($(this).val() == "money"){
                    $(row).find(".fv-row").removeClass("col-4")
                    $(row).find(".fv-row").addClass("col-6")
                    $(row).find("input[name=day]").prop("disabled", true).parents("div.fv-row").addClass("d-none")
                } else {
                    $(row).find(".fv-row").removeClass("col-6")
                    $(row).find(".fv-row").addClass("col-4")
                    $(row).find("input[name=day]").prop("disabled", false).parents("div.fv-row").removeClass("d-none")
                }
            })
        })
    }

    function loadAtt(act = null){
        var m = $("input[name=att_date]").val()

        $.ajax({
            url : "{{ route("attendance.registration.detail", $emp->id) }}?a=attendance&view=ess",
            type : "get",
            data : {
                month : m,
                act : act
            },
            dataType : "json"
        }).then(function(resp){
            $("#table-attendance tbody").html(resp.view)
            $("#att-list").html(resp.att_list)
            initTable($("#att-list table"))
            $("input[name=att_date]").val(resp.periode)
            $("input[name=att_date]").prev().text(resp.periode_label)
            var pr = $("input[name=att_date]").parent()
            $(pr).find("[data-prev]").prop("disabled", !resp.prev)
            $(pr).find("[data-next]").prop("disabled", !resp.next)

            $("[data-att-perform]").text(resp.total.att_perform.toFixed(2) + "%")
            $("[data-att-absence]").text(resp.total.absence_rate.toFixed(2) + "%")
            $("[data-att-sum]").text(resp.total.hadir)
            $("[data-att-mangkir]").text(resp.total.mangkir)
            $("[data-att-cuti]").text(resp.total.leave)
        })
    }

    function loadOvt(act = null){
        var m = $("input[name=ovt_date]").val()

        $.ajax({
            url : "{{ route("attendance.registration.detail", $emp->id) }}?a=overtime",
            type : "get",
            data : {
                month : m,
                act : act
            },
            dataType : "json"
        }).then(function(resp){
            $("#table-overtime tbody").html(resp.view)
            $("input[name=ovt_date]").val(resp.periode)
            $("input[name=ovt_date]").prev().text(resp.periode_label)
            var pr = $("input[name=ovt_date]").parent()
            $(pr).find("[data-prev]").prop("disabled", !resp.prev)
            $(pr).find("[data-next]").prop("disabled", !resp.next)
        })
    }

    function loadSchedule(act = null){
        var m = $("input[name=sch_date]").val()

        $.ajax({
            url : "{{ route("attendance.registration.detail", $emp->id) }}?a=schedule",
            type : "get",
            data : {
                month : m,
                act : act
            },
            dataType : "json"
        }).then(function(resp){
            $("#table-schedule tbody").html(resp.view)
            $("input[name=sch_date]").val(resp.periode)
            $("input[name=sch_date]").prev().text(resp.periode_label)
            var pr = $("input[name=sch_date]").parent()
            $(pr).find("[data-prev]").prop("disabled", !resp.prev)
            $(pr).find("[data-next]").prop("disabled", !resp.next)
        })
    }

    $(document).ready(function(){
        loadSchedule()
        loadOvt()
        loadAtt()
    })
</script>
@endsection
