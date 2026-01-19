@extends('_attendance.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-50px me-3">
            <div class="symbol-label bg-primary">
                <i class="fi fi-rr-calendar-lines-pen text-white fs-1"></i>
            </div>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-3 fw-bold">Koreksi Jadwal</span>
            <span class="text-muted">Koreksi jadwal masing-masing karyawan dan rangkuman kehadiran</span>
        </div>
    </div>
    <div class="d-flex flex-column">
        <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
            <li class="nav-item">
                <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_employee">
                    <span class="nav-text">Employee Schedule</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_workgroup">
                    <span class="nav-text">Workgroup Schedule</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_attendance">
                    <span class="nav-text">Attendance Correction</span>
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab_employee" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-5">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#kt_filter_balance_body_1">
                                        <i class="fi fi-rr-filter"></i>
                                        Filter
                                    </button>
                                    <div>
                                        <button type="button" class="btn btn-icon border ms-5 bg-hover-secondary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 10px">
                                            <i class="fi fi-sr-book-alt text-primary"></i>
                                        </button>
                                        <!--begin::Menu sub-->
                                        <div class="menu-sub menu-sub-dropdown p-3 w-200px" data-kt-menu="true">
                                            <div class="d-flex flex-column pt-3">
                                                @foreach ($shifts as $item)
                                                    <div class="d-flex align-items-center p-2 bg-hover-light-primary rounded">
                                                        <span class="me-5">{{ $item->shift_id }}</span>
                                                        @if ($dayCode[$item->day_code] == "Workday")
                                                            <span>{{ date("H:i", strtotime($item->schedule_in)) }} - {{ date("H:i", strtotime($item->schedule_out)) }}</span>
                                                        @else
                                                            <span class="fw-bold">{{ $dayCode[$item->day_code] ?? "Offday" }}</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary" data-export>
                                    <i class="fi fi-rr-folder-download"></i>
                                    Export
                                </button>
                                <div class="d-flex align-items-center d-none" data-edit>
                                    <button type="button" onclick="cancelEdit()" class="btn btn-secondary">
                                        Cancel
                                    </button>
                                    <div class="mx-2"></div>
                                    <button type="button" class="btn btn-primary" data-save-correction>
                                        Save All
                                    </button>
                                </div>
                            </div>
                            <div class="accordion mb-5" id="kt_filter_balance">
                                <div class="accordion-item bg-transparent border-0">
                                    <div id="kt_filter_balance_body_1" class="accordion-collapse collapse" aria-labelledby="kt_filter_balance_header_1" data-bs-parent="#kt_filter_balance">
                                        <div class="accordion-body px-0">
                                            <div class="d-flex align-items-center">
                                                <select name="fperiode" onchange="loadTable()" class="form-select" data-control="select2" data-placeholder="Select Periode" id="">
                                                    @foreach ($periodes as $item)
                                                        <option value="{{ $item->id }}" {{ date("Y-m-d") >= $item->start_date && date("Y-m-d") <= $item->end_date ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="mx-3"></div>
                                                <select name="fworkgroup" class="form-select" data-control="select2" data-placeholder="Select Workgroup" data-allow-clear="true" id="">
                                                    <option value=""></option>
                                                    @foreach ($workgroups as $item)
                                                        <option value="{{ $item->workgroup_name }}">{{ $item->workgroup_name }}</option>
                                                    @endforeach
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
                            <div class="scroll" id="content-emp">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab_workgroup" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-5 min-w-250px">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                    <select name="fwgperiode" data-allow-clear="true" onchange="loadTableWorkgroup()" class="form-select min-w-250px" data-control="select2" data-placeholder="Select Periode" id="">
                                        <option value=""></option>
                                        @foreach ($periodes as $item)
                                            <option value="{{ $item->id }}" {{ date("Y-m-d") >= $item->start_date && date("Y-m-d") <= $item->end_date ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div>
                                        <button type="button" class="btn btn-icon border ms-5 bg-hover-secondary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 10px">
                                            <i class="fi fi-sr-book-alt text-primary"></i>
                                        </button>
                                        <!--begin::Menu sub-->
                                        <div class="menu-sub menu-sub-dropdown p-3 w-200px" data-kt-menu="true">
                                            <div class="d-flex flex-column pt-3">
                                                @foreach ($shifts as $item)
                                                    <div class="d-flex align-items-center p-2 bg-hover-light-primary rounded">
                                                        <span class="me-5">{{ $item->shift_id }}</span>
                                                        @if ($dayCode[$item->day_code] == "Workday")
                                                            <span>{{ date("H:i", strtotime($item->schedule_in)) }} - {{ date("H:i", strtotime($item->schedule_out)) }}</span>
                                                        @else
                                                            <span class="fw-bold">{{ $dayCode[$item->day_code] ?? "Offday" }}</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary">
                                    <i class="fi fi-rr-folder-download"></i>
                                    Export
                                </button>
                            </div>
                            <div class="scroll" id="content-workgroup">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab_attendance" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-5 min-w-250px">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                    <select name="fperiodeattendance" onchange="loadTableAttendance()" class="form-select min-w-250px" data-control="select2" data-placeholder="Select Periode" id="">
                                        @foreach ($periodes as $item)
                                            <option value="{{ $item->id }}" {{ date("Y-m-d") >= $item->start_date && date("Y-m-d") <= $item->end_date ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mx-3"></div>
                                    <select name="fdepartementattendance" onchange="loadTableAttendance()" data-allow-clear="true" class="form-select min-w-250px" data-control="select2" data-placeholder="Select Departement" id="">
                                        <option value=""></option>
                                        @foreach ($departements as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <div>
                                        <button type="button" class="btn btn-icon border ms-5 bg-hover-secondary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 10px">
                                            <i class="fi fi-sr-book-alt text-primary"></i>
                                        </button>
                                        <!--begin::Menu sub-->
                                        <div class="menu-sub menu-sub-dropdown p-3 w-200px" data-kt-menu="true">
                                            <div class="d-flex flex-column pt-3">
                                                @foreach ($shifts as $item)
                                                    <div class="d-flex align-items-center p-2 bg-hover-light-primary rounded">
                                                        <span class="me-5">{{ $item->shift_id }}</span>
                                                        @if ($dayCode[$item->day_code] == "Workday")
                                                            <span>{{ date("H:i", strtotime($item->schedule_in)) }} - {{ date("H:i", strtotime($item->schedule_out)) }}</span>
                                                        @else
                                                            <span class="fw-bold">{{ $dayCode[$item->day_code] ?? "Offday" }}</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-icon border ms-5 bg-hover-secondary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 10px">
                                            <i class="fi fi-sr-calendar-lines text-primary"></i>
                                        </button>
                                        <!--begin::Menu sub-->
                                        <div class="menu-sub menu-sub-dropdown p-3 w-200px" data-kt-menu="true">
                                            <div class="d-flex flex-column pt-3">
                                                @foreach ($shifts as $item)
                                                    <div class="d-flex align-items-center p-2 bg-hover-light-primary rounded">
                                                        <span class="me-5">{{ $item->shift_id }}</span>
                                                        @if ($dayCode[$item->day_code] == "Workday")
                                                            <span>{{ date("H:i", strtotime($item->schedule_in)) }} - {{ date("H:i", strtotime($item->schedule_out)) }}</span>
                                                        @else
                                                            <span class="fw-bold">{{ $dayCode[$item->day_code] ?? "Offday" }}</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="scroll" id="content-attendance">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('attendance.correction.employee') }}" method="post">
    <div class="modal fade" tabindex="-1" id="modal-save">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content px-10">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body rounded">
                            <div class="d-flex flex-column align-items-center">
                                <span class="fs-1 fw-bold text-center">Apakah Anda yakin melakukan perubahan?</span>
                                <div class="my-1"></div>
                                <div data-desc></div>
                                <div class="my-3"></div>
                                <div class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="data">
                                    <button type="button" class="btn text-dark" data-bs-dismiss="modal">Back</button>
                                    <div class="mx-2"></div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{ route('attendance.correction.workgroup') }}" method="post">
    <div class="modal fade" tabindex="-1" id="modal-save-workgroup">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content px-10">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body rounded">
                            <div class="d-flex flex-column align-items-center">
                                <span class="fs-1 fw-bold text-center">Apakah Anda yakin melakukan perubahan?</span>
                                <div class="my-1"></div>
                                <span class="text-center" data-desc></span>
                                <div class="my-3"></div>
                                <div class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="data">
                                    <button type="button" class="btn text-dark" data-bs-dismiss="modal">Back</button>
                                    <div class="mx-2"></div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


@component('layouts.components.fab', [
    "fab" => [
        ["label" => "Add Overtime", 'url' => route("attendance.overtime.index")."?modal=modal_add_overtime"],
        ["label" => "Create Request Leave", "url" => route("attendance.leave.index")."?modal=modal_create_request_leave"],
        ["label" => "Employee Presence", "url" => "javascript:;"],
        ["label" => "Edit Schedule", "url" => "javascript:;", 'toggle' => 'onclick="editSchedule()"'],
    ]
])
@endcomponent

@endsection

@section('view_script')
    <script>

        var edit_schedule = {}
        var isEditSchedule = false

        function cancelEdit(){
            edit_schedule = []
            if(isEditSchedule){
                loadTable()
            }
            isEditSchedule = false
            $("#tab_employee [data-edit]").addClass("d-none")
            $("#tab_employee [data-export]").removeClass("d-none")
            $("#tab_employee [data-shift-toggle]").off("click")
        }

        function editSchedule(){
            $("#tab_employee [data-edit]").removeClass("d-none")
            $("#tab_employee [data-export]").addClass("d-none")
            isEditSchedule = true
            $("#tab_employee [data-check]").click(function(){
                var id_emp = $(this).data("emp")
                var data_emp = edit_schedule[id_emp] ?? {}
                if(data_emp != null){
                    var ck = $(this).prop("checked")
                    data_emp['checked'] = ck
                    console.log(edit_schedule)
                }
            })
            $("#tab_employee [data-shift-toggle]").click(function(){
                var shiftId = $(this).data("shift-id")
                var shiftColor = $(this).data("shift-color")
                var shiftLabel = $(this).data("shift-label")
                var span = $(this).parents("td").find('[data-kt-menu-trigger="click"]')
                $(span).text(shiftLabel)
                $(span).css("background-color", shiftColor)
                $(span).removeClass("text-white")
                $(span).removeClass("text-dark")
                if(shiftLabel != "OFF"){
                    $(span).addClass("text-white")
                } else {
                    $(span).addClass("text-dark")
                }

                var id_emp = $(this).data("emp")
                var _date = $(this).data("date")
                var ck = $(this).parents("tr").find("input[name=select]").prop("checked")
                var emp = $(this).parents("tr").find("input[name=select]").data("name")

                var _data_emp = edit_schedule[id_emp] ?? {}

                var data_emp = _data_emp['data'] ?? []

                var exist = data_emp.find(x => x.date == _date)
                if(exist === undefined){
                    var col = {}
                    col['date'] = $(this).data("date")
                    col['shift'] = shiftId
                    col['old'] = $(this).data("shift-old")
                    col['color'] = $(this).data("shift-color")
                    col['old_color'] = $(this).data("shift-old-color")
                    col['lbl'] = $(this).data("lbl")
                    col['label'] = shiftLabel
                    data_emp.push(col)
                } else {
                    var col = data_emp.filter(x => x.date == _date)[0];
                    var ind = data_emp.findIndex(x => x.date == _date);
                    col['shift'] = shiftId
                    col['label'] = shiftLabel
                    col['color'] = $(this).data("shift-color")
                    col['old_color'] = $(this).data("shift-old-color")
                    col['lbl'] = $(this).data("lbl")
                    data_emp[ind] = col
                }

                _data_emp['checked'] = ck
                _data_emp['name'] = emp
                _data_emp['data'] = data_emp

                edit_schedule[id_emp] = _data_emp

                $(this).parents("td").find('[data-shift-toggle]').removeClass("bg-primary text-white")
                $(this).addClass("bg-primary text-white")
                $(span).trigger("click")
            })

            $("#tab_employee [data-save-correction]").click(function(){
                var cnt = 0
                for (const key in edit_schedule) {
                    if (Object.hasOwnProperty.call(edit_schedule, key)) {
                        const element = edit_schedule[key];
                        if(element.checked){
                            cnt++
                        }
                    }
                }
                $("#modal-save [data-desc]").text("")
                if(cnt == 0){
                    return Swal.fire("Data kosong", "Anda belum memilih data yang akan dirubah", 'error')
                }

                var desc = `<table class="table table-layout-fixed" id='table-save' data-ordering="false" data-paging="false" data-bInfo="false">`
                    desc += "<thead>"
                    desc += "<tr class='bg-light-primary'>"
                    desc += "<th>Nama</th>"
                    desc += "<th>Tanggal</th>"
                    desc += "<th>Last</th>"
                    desc += "<th>Update</th>"
                    desc += "</tr>"
                    desc += "</thead>"
                    desc += "<tbody>"

                for (const key in edit_schedule) {
                    if (Object.hasOwnProperty.call(edit_schedule, key)) {
                        const element = edit_schedule[key];
                        if(element.checked){
                            var data = element.data
                            var txtClsOld = data[0].old == "OFF" ? "text-dark" : "text-white"
                            var txtCls = data[0].label == "OFF" ? "text-dark" : "text-white"
                            desc += `<tr>` +
                                    `<td>${element.name}</td>` +
                                    `<td>${data[0].lbl}</td>` +
                                    `<td><span class="badge badge-lg ${txtClsOld}" style="background-color: ${data[0].old_color}">${data[0].old}</span></td>` +
                                    `<td><span class="badge badge-lg ${txtCls}" style="background-color: ${data[0].color}">${data[0].label}</span></td>` +
                                    `</tr>`

                            if(data.length > 1){
                                for (let i = 1; i < data.length; i++) {
                                    const el = data[i];
                                    var txtClsOld = el.old == "OFF" ? "text-dark" : "text-white"
                                    var txtCls = el.label == "OFF" ? "text-dark" : "text-white"
                                    desc += `<tr>` +
                                        `<td></td>` +
                                        `<td>${el.lbl}</td>` +
                                        `<td><span class="badge badge-lg ${txtClsOld}" style="background-color: ${el.old_color}">${el.old}</span></td>` +
                                        `<td><span class="badge badge-lg ${txtCls}" style="background-color: ${el.color}">${el.label}</span></td>` +
                                        `</tr>`
                                }
                            }

                            // desc += `<div class='d-flex align-items-baseline justify-content-between mb-3'>`
                            // desc += `<span class="me-3">${element.name}</span>`
                            // desc += `<span class="mx-2">:</span>`
                            // desc += `<div class='d-flex flex-column'>`
                            // desc += `</div>`
                            // desc += `</div>`
                        }
                    }
                }
                desc += "</tbody>"
                desc += `</table>`


                $("#modal-save [data-desc]").html(desc)

                initTable($("#table-save"))

                $("#modal-save [data-desc] div.dataTable-length-info-label").hide()

                $("#modal-save").modal("show")
                $("#modal-save input[name=data]").val(JSON.stringify(edit_schedule))
            })
        }

        function loadTable(){
            $.ajax({
                url : "{{ route("attendance.correction.index") }}?a=table-employee",
                type : "get",
                data : {
                    periode : $("select[name=fperiode]").val()
                },
                dataType : "json"
            }).then(function(resp){
                $("#content-emp").html(resp.view)

                var seq = 0

                var tb = initTable("#table-employee")

                $("#table-employee button[data-prev]").prop("disabled", true)

                $("#table-employee button[data-next]").click(function(){
                    $("#table-employee .sequence").addClass("d-none")
                    seq++
                    $(`#table-employee [data-sequence="${seq}"]`).removeClass("d-none")
                    $("#table-employee button[data-prev]").prop("disabled", false)
                    if(seq == resp.total_sequence){
                        $("#table-employee button[data-next]").prop("disabled", true)
                    }
                })

                $("#table-employee button[data-prev]").click(function(){
                    $("#table-employee .sequence").addClass("d-none")
                    seq--
                    $(`#table-employee [data-sequence="${seq}"]`).removeClass("d-none")
                    $("#table-employee button[data-next]").prop("disabled", false)
                    if(seq == 0){
                        $("#table-employee button[data-prev]").prop("disabled", true)
                    }
                })

                $("select[name=fworkgroup]").change(function(){
                    console.log($(this).val())
                    tb.column(2).search($(this).val()).draw()
                })

                $('#table-employee').parents("div.card").find("input[name=search_table]").on("keyup", function(){
                    tb.search($(this).val()).draw()
                })

                tb.on("init draw", function(){
                    if(isEditSchedule){
                        editSchedule()
                    } else {
                        cancelEdit()
                    }
                })
            })
        }

        function editWorkgroup(){
            $("#table-workgroup [data-shift-toggle]").off("click")
            $("#table-workgroup [data-shift-toggle]").click(function(){
                console.log("hello")
                var date = $(this).data("date")
                var lbl = $(this).data("lbl")
                var shift_id = $(this).data("shift-id")
                var shift_old = $(this).data("shift-old")
                var shift_label = $(this).data("shift-label")
                var shift_color = $(this).data("shift-color")
                var name = $(this).data("name")
                var span = $(this).parents("td").find('[data-kt-menu-trigger="click"]')
                var col = {}
                col['date'] = $(this).data("date")
                col['shift'] = shift_id
                col['old'] = $(this).data("shift-old")
                col['label'] = shift_label
                col['id'] = $(this).data("id")

                var desc = `Pada tanggal ${lbl}, ${name} mengalami perubahan jadwal kerja, mereka beralih dari ${shift_old} ke ${shift_label}`
                $("#modal-save-workgroup [data-desc]").html(desc)
                $("#modal-save-workgroup").modal("show")
                $("#modal-save-workgroup input[name=data]").val(JSON.stringify(col))
                $(span).trigger("click")
            })
        }

        function loadTableAttendance(){
            $.ajax({
                url : "{{ route("attendance.correction.index") }}?a=table-attendance",
                type : "get",
                data : {
                    periode : $("select[name=fperiodeattendance]").val(),
                    dept : $("select[name=fdepartementattendance]").val(),

                },
                dataType : "json"
            }).then(function(resp){
                $("#content-attendance").html(resp.view)

                var tb = initTable("#table-attendance")
            })
        }

        function loadTableWorkgroup(){
            $.ajax({
                url : "{{ route("attendance.correction.index") }}?a=table-workgroup",
                type : "get",
                data : {
                    periode : $("select[name=fwgperiode]").val()
                },
                dataType : "json"
            }).then(function(resp){
                $("#content-workgroup").html(resp.view)

                var seq = 0

                var tb = initTable("#table-workgroup")

                $("#table-workgroup button[data-prev]").prop("disabled", true)

                $("#table-workgroup button[data-next]").click(function(){
                    $("#table-workgroup .sequence").addClass("d-none")
                    seq++
                    $(`#table-workgroup [data-sequence="${seq}"]`).removeClass("d-none")
                    $("#table-workgroup button[data-prev]").prop("disabled", false)
                    if(seq == resp.total_sequence){
                        $("#table-workgroup button[data-next]").prop("disabled", true)
                    }
                })

                $("#table-workgroup button[data-prev]").click(function(){
                    $("#table-workgroup .sequence").addClass("d-none")
                    seq--
                    $(`#table-workgroup [data-sequence="${seq}"]`).removeClass("d-none")
                    $("#table-workgroup button[data-next]").prop("disabled", false)
                    if(seq == 0){
                        $("#table-workgroup button[data-prev]").prop("disabled", true)
                    }
                })

                editWorkgroup()


                tb.on("init draw", function(){
                    editWorkgroup()
                })
            })
        }

        $(document).ready(function(){
            loadTable()
            loadTableWorkgroup()
            loadTableAttendance()
            @if(Session::has("tab"))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ Session::get("tab") }}']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif
        })
    </script>
@endsection
