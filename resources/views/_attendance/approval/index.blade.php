@extends('_attendance.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-50px me-3">
            <div class="symbol-label bg-primary">
                <i class="fi fi-sr-clipboard-list-check text-white fs-1"></i>
            </div>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-3 fw-bold">Approval Request</span>
            <span class="text-muted">Managemen persetujuan pengajuan cuti, overtime & summary attendance</span>
        </div>
    </div>
    <div class="d-flex flex-column">
        <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
            <li class="nav-item">
                <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_overtime">
                    <span class="nav-text">Overtime</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_leave">
                    <span class="nav-text">Leave</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_attendance">
                    <span class="nav-text">Attendance Summary</span>
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab_overtime" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="position-relative me-5">
                                    <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                    <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                </div>
                            </div>
                            <div class="scroll">
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-overtime">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Overtime Type</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Paid By</th>
                                            <th>Allocation Departement</th>
                                            <th>Status</th>
                                            <th></th>
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
                                                            <span class="text-muted">{{ $item->emp->dept->name ?? "-" }}</span>
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
                                                        <span>{{ $item->overtime_start_time }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column align-items-center">
                                                        <span class="fw-bold">{{ date("d F Y", strtotime($item->overtime_date)) }}</span>
                                                        <span>{{ $item->overtime_end_time }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ ucwords($item->paid) }}
                                                </td>
                                                <td>{{ $item->dept->name ?? "-" }}</td>
                                                <td>
                                                    <span class="badge badge-{{ empty($item->approved_at) ? (empty($item->rejected_at) ? "warning" : "danger") : "success" }}">{{ empty($item->approved_at) ? (empty($item->rejected_at) ? "Persetujuan" : "Rejected") : "Approved" }}</span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-sm" onclick="show_overtime({{ $item->id }})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail">
                                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                    </button>
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
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="position-relative me-5">
                                    <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                    <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                </div>
                            </div>
                            <div class="scroll">
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-leave-request">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Departement</th>
                                            <th>Date Request</th>
                                            <th>Leave Used</th>
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
                                                <td>{{ $item->emp->dept->name }}</td>
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
            <div class="tab-pane fade" id="tab_attendance" role="tabpanel">
                <div class="alert alert-primary">
                    <span>Under development</span>
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
    <script>
        KTDrawer.createInstances();
        var drawerElement = document.querySelector("#kt_drawer_detail");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");
        var blockUI = new KTBlockUI(target);

        function show_overtime(id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{ route('attendance.overtime.detail') }}/" + id + "?act=approval",
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)
            })
        }

        function show_detail(type, id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{route("attendance.leave.detail")}}/" + type + "/" + id + "?act=approval",
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)

                $("#kt_drawer_detail [data-control=select2]").select2()
            })
        }
    </script>
@endsection