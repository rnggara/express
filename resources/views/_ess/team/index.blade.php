@extends('_ess.layout')

@section('view_content')
<div class="card shadow-none card-p-0 h-100">
    <div class="card-body">
        <div class="d-flex flex-column gap-5 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-5">
                    <div class="symbol symbol-50px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-users-alt text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold">Tim Saya</span>
                        <span>Lihat semua tim Anda</span>
                    </div>
                </div>
            </div>
            <div class="card shadow-none">
                <div class="card-body bg-secondary-crm p-5">
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
                                        Nama
                                    </th>
                                    <th>Posisi</th>
                                    <th>Tanggal mulai</th>
                                    <th>Group Kerja</th>
                                    <th>Grup Cuti</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registrations as $item)
                                    @if (!empty($item->wg) && !empty($item->leave))
                                        @php
                                            $_emp = $item->emp;
                                            $_user = $_emp->user ?? [];
                                        @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <div class="symbol-label" style="background-image: url('{{ asset($uImg[$item->emp_id] ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a class="fw-bold text-dark" href="{{ route("ess.team.detail", $_emp->id) }}">{{ $_emp->emp_name }}</a>
                                                    <span class="text-muted">{{ $_user->uacdepartement->name ?? "" }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $_emp->position->name ?? "-" }}</td>
                                        <td>{{ date("d-m-Y", strtotime($item->date1)) }}</td>
                                        <td>{{ $item->wg->workgroup_name ?? "-" }}</td>
                                        <td>{{ $item->leave->leave_group_name ?? "-" }}</td>
                                        <td>
                                            <span class="badge badge-outline badge-{{ $item->status == 1 ? "success" : "danger" }}">{{ $item->status == 1 ? "Aktif" : "Nonaktif" }}</span>
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
@endsection
