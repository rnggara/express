@extends('_attendance.layout', ["bgWrapper" => ""])

{{-- @section('fixaside')
    @include('_crm.leads._aside')
@endsection --}}

@section('view_content')
<div class="d-flex flex-column">
    <div class="row" style="margin-bottom: 32px">
        <div class="col-8">
            <div class="card bg-transparent shadow-none card-stretch">
                <div class="card-body p-0">
                    <div class="d-flex flex-column">
                        <div class="card shadow-none">
                            <div class="card-header border-0 pb-0">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px me-3">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="fi fi-sr-calendar fs-1 text-primary"></i>
                                        </div>
                                    </div>
                                    <span class="fs-3 fw-bold">Hari ini</span>
                                </div>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-icon" onclick="accrd(this)">
                                        <i class="fi fi-rr-caret-down" data-accr="expand"></i>
                                        <i class="fi fi-rr-caret-up d-none" data-accr="collapse"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex gap-3 scroll-x mb-5" data-accr-hide>
                                    @foreach ($reason_names as $item)
                                        @php
                                            $_tdy = count($_today[$item->id] ?? []);
                                            $_yst = count($_yesterday[$item->id] ?? []);
                                            $_sel = $_tdy - $_yst;
                                            $pctg = 0;
                                            if($_yst == 0){
                                                $pctg = $_sel * 100;
                                            } else {
                                                $pctg = number_format(($_sel/$_yst) * 100, 0, ".", "");
                                            }

                                            $cls = "secondary";
                                            $icon = "";
                                            if($item->reason_name == "Hadir"){
                                                if($pctg > 0){
                                                    $cls = "badge badge-outline badge-success";
                                                    $icon = "fi fi-sr-arrow-trend-up";
                                                } else {
                                                    $cls = "badge badge-outline badge-danger";
                                                    $icon = "fi fi-sr-arrow-trend-down";
                                                }
                                            } else {
                                                if($pctg < 0){
                                                    $cls = "badge badge-outline badge-success";
                                                    $icon = "fi fi-sr-arrow-trend-down";
                                                } else {
                                                    $cls = "badge badge-outline badge-danger";
                                                    $icon = "fi fi-sr-arrow-trend-up";
                                                }
                                            }

                                            if($pctg == 0){
                                                $cls = "badge badge-secondary";
                                                $icon = "";
                                            }
                                        @endphp
                                        <div class="min-w-275px">
                                            <div class="card cursor-pointer {{ $item->reason_name == "Hadir" ? 'bg-primary active' : "" }}">
                                                <div class="card-body border rounded">
                                                    <div class="d-flex flex-column">
                                                        <div class="d-flex align-items-center">
                                                            @if ($item->reason_name == "Hadir")
                                                                <div class="symbol symbol-30px me-3">
                                                                    <div class="symbol-label bg-light-warning">
                                                                        <i class="fi fi-rr-users text-warning"></i>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <span class="{{ $item->reason_name == "Hadir" ? 'text-white' : "" }} fs-3 fw-bold">{{ $item->reason_name }}</span>
                                                        </div>
                                                        <div class="my-3"></div>
                                                        <div class="d-flex align-items-center">
                                                            <span class="{{ $item->reason_name == "Hadir" ? 'text-white' : "" }} fs-3 me-3">{{ $_tdy }}</span>
                                                            <div class="{{ $cls }}"><i class="{{ $icon }} me-1"></i> {{ $pctg >= 0 ? "+" : "-" }}{{ abs($pctg) }}%</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex flex-column d-none" data-accr>
                                    <div class="d-flex gap-3 scroll-x mb-5">
                                        @foreach ($reason_names as $item)
                                            @php
                                                $_tdy = count($_today[$item->id] ?? []);
                                                $_yst = count($_yesterday[$item->id] ?? []);
                                                $_sel = $_tdy - $_yst;
                                                $pctg = 0;
                                                if($_yst == 0){
                                                    $pctg = $_sel * 100;
                                                } else {
                                                    $pctg = number_format(($_sel/$_yst) * 100, 0, ".", "");
                                                }

                                                $cls = "secondary";
                                                $icon = "";
                                                if($item->reason_name == "Hadir"){
                                                    if($pctg > 0){
                                                        $cls = "badge badge-outline badge-success";
                                                        $icon = "fi fi-sr-arrow-trend-up";
                                                    } else {
                                                        $cls = "badge badge-outline badge-danger";
                                                        $icon = "fi fi-sr-arrow-trend-down";
                                                    }
                                                } else {
                                                    if($pctg < 0){
                                                        $cls = "badge badge-outline badge-success";
                                                        $icon = "fi fi-sr-arrow-trend-down";
                                                    } else {
                                                        $cls = "badge badge-outline badge-danger";
                                                        $icon = "fi fi-sr-arrow-trend-up";
                                                    }
                                                }

                                                if($pctg == 0){
                                                    $cls = "badge badge-secondary";
                                                    $icon = "";
                                                }
                                            @endphp
                                            <div class="min-w-275px">
                                                <div class="card cursor-pointer {{ $item->reason_name == "Hadir" ? 'bg-light-info active' : "" }}" onclick="loadTable(this)" data-div-reason data-id="{{ $item->id }}">
                                                    <div class="card-body border rounded">
                                                        <div class="d-flex flex-column">
                                                            <div class="d-flex align-items-center">
                                                                <span class="{{ $item->reason_name == "Hadir" ? 'text-white' : "" }} fs-3 fw-bold">{{ $item->reason_name }}</span>
                                                            </div>
                                                            <div class="my-3"></div>
                                                            <div class="d-flex align-items-center">
                                                                <span class="{{ $item->reason_name == "Hadir" ? 'text-white' : "" }} me-3 fs-3">{{ count($_today[$item->id] ?? []) }}</span>
                                                                <div class="{{ $cls }}"><i class="{{ $icon }} me-1"></i> {{ $pctg >= 0 ? "+" : "-" }}{{ abs($pctg) }}%</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="w-50">
                                            <div class="position-relative">
                                                <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                                <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                            </div>
                                        </div>
                                        <div class="mx-3"></div>
                                        <div class="flex-fill">
                                            <select name="tdworkgroup" onchange="getDataTable()" class="form-select" data-control="select2" data-placeholder="Select Workgroup" data-allow-clear="true">
                                                <option value=""></option>
                                                @foreach ($workgroups as $item)
                                                    <option value="{{ $item->id }}">{{ $item->workgroup_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mx-3"></div>
                                        <div class="flex-fill">
                                            <select name="tddepartement" onchange="getDataTable()" class="form-select" data-control="select2" data-placeholder="Select Departement" data-allow-clear="true">
                                                <option value=""></option>
                                                @foreach ($departements as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <table class="table table-display-2" id="tbl-today">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Work Group</th>
                                                <th>Clock In</th>
                                                <th>Clock Out</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                {{-- <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                                    <span class="fi fi-rr-document fs-3tx text-muted"></span>
                                    <span class="text-muted">No Data Available</span>
                                </div> --}}
                            </div>
                        </div>
                        <div style="margin-top: 24px"></div>
                        <div class="card shadow-none">
                            <div class="card-header border-0">
                                <h3 class="card-title">Riwayat Kehadiran</h3>
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-event">
                                        <div class="d-flex align-items-center">
                                            <select name="att_periode" class="form-select min-w-200px" onchange="loadAttTrack()" data-control="select2" data-placeholder="January" id="">
                                                <option value=""></option>
                                                @foreach ($periodes as $item)
                                                    <option value="{{ $item->id }}" {{ $now >= $item->start_date && $now <= $item->end_date ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mx-3"></div>
                                        <div class="d-flex align-items-center">
                                            <select name="att_type" class="form-select min-w-200px" onchange="loadAttTrack()" data-control="select2" data-placeholder="Present" id="">
                                                @foreach ($reasons as $item)
                                                    <option value="{{ $item->reason_name_id }}">{{ $item->reasonName->reason_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mx-3"></div>
                                        <div class="d-flex align-items-center">
                                            <select name="att_div" class="form-select min-w-200px" onchange="loadAttTrack()" data-allow-clear="true" data-control="select2" data-placeholder="All" id="">
                                                <option value=""></option>
                                                @foreach ($departements as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-0">
                                <table class="table border-0" id="table-att-track">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach ($hariId as $item)
                                                <th class="text-center">{{ $item }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                {{-- <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                                    <span class="fi fi-rr-document fs-3tx text-muted"></span>
                                    <span class="text-muted">No Data Available</span>
                                </div> --}}
                            </div>
                        </div>
                        <div style="margin-top: 24px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card bg-transparent shadow-none card-stretch">
                <div class="card-body p-0">
                    <div class="d-flex flex-column">
                        <div class="card shadow-none min-h-300px">
                            <div class="card-header pb-0">
                                <span class="card-title">Permintaan Cuti</span>
                                <div class="card-toolbar">
                                    <div class="d-flex align-items-center">
                                        <select name="leave_request_sort" onchange="loadLeaveRequest()" class="form-select" data-control="select2" data-placeholder="Request" id="">
                                            <option value="created_at">By Request</option>
                                            <option value="start_date">By Date Leave</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" id="leave-request-content">

                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 24px"></div>
                    @include('_attendance.widgets.empty', ['key' => 1])
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row gx-5">
                <div class="col-4">
                    @include('_attendance.widgets.empty', ['key' => 2])
                </div>
                <div class="col-4">
                    @include('_attendance.widgets.empty', ['key' => 3])
                </div>
                <div class="col-4">
                    @include('_attendance.widgets.empty', ['key' => 4])
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-none">
        <div class="card-header border-0">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-40px me-3">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-rr-users fs-1 text-primary"></i>
                    </div>
                </div>
                <span class="fs-3 fw-bold">Data Attendance</span>
            </div>
        </div>
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
                        <div id="kt_filter_balance_body_1" class="accordion-collapse collapse" aria-labelledby="kt_filter_balance_header_1" data-bs-parent="#kt_filter_balance">
                            <div class="accordion-body px-0">
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control flatpicker" onchange="loadAttData()" id="fattdate" name="fattdate" placeholder="Today">
                                    {{-- <select name="ftoday" class="form-select" data-control="select2" data-placeholder="Today" data-allow-clear="true" id="">
                                        <option value=""></option>
                                    </select> --}}
                                    <div class="mx-3"></div>
                                    <select name="fattworkgroup" class="form-select" onchange="loadAttData()" data-control="select2" data-placeholder="Select Leavegroup" data-allow-clear="true" id="">
                                        <option value=""></option>
                                        @foreach ($workgroups as $item)
                                            <option value="{{ $item->id }}">{{ $item->workgroup_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mx-3"></div>
                                    <select name="fattreason" class="form-select" data-control="select2" onchange="loadAttData()" data-placeholder="Select Reason" data-allow-clear="true" id="">
                                        <option value=""></option>
                                        @foreach ($reasons as $item)
                                            <option value="{{ $item->reason_name_id }}">{{ $item->reasonName->reason_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-display-2 bg-white" data-ordering="false" id="table-att-data">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Work Group</th>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Overtime Hours</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalWidget">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <div class="modal-body bg-secondary-crm rounded">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center mb-5">
                        <div class="symbol symbol-40px me-3">
                            <div class="symbol-label bg-primary">
                                <i class="fi fi-sr-chart-pie-alt text-white fs-1"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-3">Customize Dashboard</span>
                            <span>Sesuaikan tampilan dashboard sesuai preferensi dan kebutuhan anda</span>
                        </div>
                    </div>
                    <div class="row g-3">
                        @foreach ($widgets as $key => $item)
                            @php
                                $checked = "";
                                if(in_array($key, $widget_dashboard->pluck("widget_key")->toArray())){
                                    $checked = "checked";
                                }
                            @endphp
                            <div class="col-4 mb-3">
                                <div class="card shadow-none card-stretch">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">{{ $item['label'] }}</h3>
                                        <div class="card-toolbar">
                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" {{$checked}} onclick="updateWidget(this, '{{ $key }}')" type="checkbox" value="1"/>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="d-flex flex-column h-100">
                                            <img src="{{ asset("images/logos/".$item['images']) }}" alt="" class="w-100">
                                            <div class="my-3 border"></div>
                                            <div class="rounded bg-light-primary p-3 flex-fill">
                                                {!! $item['descriptions'] !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <input type="hidden" name="position">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('view_script')
    <script>

        function accrd(me){
            var crd = $(me).parents("div.card")
            var target = $(crd).find("div[data-accr]")
            if($(target).hasClass("d-none")){
                $(crd).find("div[data-accr-hide]").addClass("d-none")
                $(target).removeClass("d-none")
                $(me).find('i[data-accr="expand"]').addClass("d-none")
                $(me).find('i[data-accr="collapse"]').removeClass("d-none")
                $("div[data-div-reason]").addClass('bg-hover-light-info')
                $("div[data-div-reason].active").removeClass("bg-primary").addClass("bg-light-info").find("span, h3").removeClass("text-white")
            } else {
                $(crd).find("div[data-accr-hide]").removeClass("d-none")
                $(target).addClass("d-none")
                $(me).find('i[data-accr="expand"]').removeClass("d-none")
                $(me).find('i[data-accr="collapse"]').addClass("d-none")
                $("div[data-div-reason]").removeClass('bg-hover-light-info')
                $("div[data-div-reason].active").addClass("bg-primary").removeClass("bg-light-info").removeClass('bg-hover-light-info').find("span, h3").addClass("text-white")
            }
        }

        function getDataTable(){
            var reason = $("div[data-div-reason].active").data("id")
            $.ajax({
                url : "{{ route("attendance.index") }}?a=table_today",
                type : "get",
                dataType : "json",
                data : {
                    reason : reason,
                    workgroup : $("select[name=tdworkgroup]").val(),
                    departement : $("select[name=tddepartement]").val()
                }
            }).then(function(resp){
                $("#tbl-today").DataTable().clear().draw()
                $("#tbl-today").DataTable().rows.add(resp).draw()
            })
        }

        function loadTable(me = null){
            var accr = $("div[data-accr]")

            if(me == null){
                getDataTable()
            } else {
                if(!$(accr).hasClass("d-none")){
                    $("div[data-div-reason]").removeClass("active bg-light-info")
                    $(me).addClass("active bg-light-info")

                    getDataTable()
                }
            }
        }

        function loadLeaveRequest(){
            var data = {}
            data['sort_by'] = $("select[name=leave_request_sort]").val()
            $("#leave-request-content").html("")

            $.ajax({
                url : "{{ route("attendance.index") }}?type=leave_request",
                type : "get",
                data : data,
                dataType : "json"
            }).then(function (resp) {
                $("#leave-request-content").html(resp.view)
            })
        }

        function openModalWidget(pos){
            $("#modalWidget input[name=position]").val(pos)
            $("#modalWidget").modal("show")
        }

        function updateWidget(me, key){
            var pos = $("#modalWidget input[name=position]").val()
            var ck = $(me).prop("checked")
            window.location = "{{ route("attendance.update_widget") }}/" + key + "?checked=" + ck + "&position=" + pos
        }

        function loadAttTrack(){
            var periode = $("select[name=att_periode]").val()

            $("#table-att-track").removeClass("gy-7 gs-7 border table-rounded")

            $("#table-att-track thead tr").removeClass("fw-semibold fs-6 text-gray-800 border-bottom border-gray-200")

            $.ajax({
                url : "{{ route("attendance.index") }}?type=att_track",
                type : "get",
                data : {
                    periode : periode,
                    reason : $("select[name=att_type]").val(),
                    div : $("select[name=att_div]").val(),
                },
                dataType : "json"
            }).then(function (resp) {

                var data = resp.data;
                var rcount = resp.rcount
                var days = @json($hariId)

                var el = ""
                for (const key in data) {
                    const element = data[key];
                    var tr = "<tr>" +
                        "<td>Minggu " + key + "</td>"
                    for (const i in days) {
                        const _el = days[i];
                        var td = "<td>"
                        if (element[i] != undefined) {
                            var cnt = 0
                            var cls = "bg-light-primary"
                            var cls_ = "opacity-50 text-primary"
                            var _cls = "text-primary"
                            if(rcount[element[i]] != undefined){
                                cnt = rcount[element[i]].length
                                cls = "bg-primary"
                                _cls = "text-white"
                                cls_ = "opacity-75"
                            }

                            td = "<td class='"+cls+" border border-white border-5'>"
                            td += "<div data-bs-toggle='tooltip' title='"+element[i]+"' class='"+_cls+" d-flex justify-content-center text-center fw-bold'>" + cnt + "<span class='"+cls_+"'>/" + resp.reg + "<span></div>"
                        }
                        td += "</td>"
                        tr += td
                    }
                    tr += "</tr>"
                    el += tr
                }

                $("#table-att-track tbody").html(el)
            })
        }

        function loadAttData(){
            var filter = {}

            filter['date'] = $("input[name=fattdate]").val()
            filter['workgroup'] = $("select[name=fattworkgroup]").val()
            filter['reason'] = $("select[name=fattreason]").val()

            $("#table-att-data").DataTable().clear()

            $.ajax({
                url : "{{ route("attendance.index") }}?type=att_data",
                type : "get",
                data : {
                    filter : filter
                },
                dataType : "json"
            }).then(function(resp){
                $("#table-att-data").DataTable().rows.add(resp).draw()
            })
        }

        function barChart(data, element, color = null){
            var options = {
                series: [{
                    data: data
                }],
                chart: {
                    type: 'bar',
                    height: 150
                },
                colors: [color ?? "#7340E5"],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                yaxis: {
                    type: 'numeric'
                },
                tooltip: {
                    y: {
                        title: {
                            formatter: (seriesName) => "",
                        },
                    },
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        type : "vertical",
                        shadeIntensity: 0,
                        opacityFrom: 0.9,
                        opacityTo: 0.7,
                        stops: [0, 90, 100]
                    }
                }
            };

            $(element).html("")

            var el = document.querySelector(element)
            console.log(element)
            if(!el){
                return;
            }

            var chart = new ApexCharts(document.querySelector(element), options);
            chart.render();
        }

        function verticalChart(data, element, lbl){
            var options = {
                series: [{
                    data: data
                }],
                chart: {
                    type: 'bar',
                    height: $(element).data("height") ?? 150
                },
                colors: ["#7340E5"],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: lbl
                },
                grid : {
                    show : false
                },
                xaxis : {
                    show : false,
                    labels : {
                        show : false,
                    }
                },
                yaxis: {
                    type: 'numeric'
                },
                tooltip: {
                    y: {
                        formatter: lbl,
                        title: {
                            formatter: (seriesName) => "",
                        },
                    },
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        type : "horizontal",
                        shadeIntensity: 0,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100]
                    }
                }
            };

            $(element).html("")

            var el = document.querySelector(element)
            console.log(element)
            if(!el){
                return;
            }

            var chart = new ApexCharts(document.querySelector(element), options);
            chart.render();
        }

        function doughnutChart(element, labels, x){
            $("#"+element).html("")
            // Chart data
            var bgColor = ["#5837FF", "#6235C5", "#E1D7FA", "#FEB3B2", "#FCD9C4"]
            var data = {
                labels: labels.reverse(),
                datasets: [{
                    data : x.reverse(),
                    backgroundColor: bgColor.reverse(),
                    borderJoinStyle : "round",
                    rotation : 270
                    // borderRadius: {
                    //     outerEnd : 100,
                    //     innerEnd : 100
                    // },
                    // spacing : -100,
                    // clip: 5,
                }]
            };

            // Chart config
            var config = {
                type: 'doughnut',
                data: data,
                options: {
                    events: [],
                    plugins: {
                        title: {
                            display: false,
                        },
                        legend : {
                            reverse : true,
                            position : "right",
                            onClick : null,
                            labels : {
                                generateLabels: (chart) => {
                                    const datasets = chart.data.datasets;
                                    return datasets[0].data.map((data, i) => ({
                                        text: `${chart.data.labels[i]}(${data})`,
                                        fillStyle: datasets[0].backgroundColor[i],
                                        strokeStyle: datasets[0].backgroundColor[i],
                                        index: i,
                                        borderRadius : 4,
                                        lineCap : "round"
                                    }))
                                }
                            }
                        },
                        tooltip : {
                            enabled : false,
                        }
                    },
                    responsive: true,
                },
                plugins : [{
                    afterUpdate: function (chart) {
                            var a=chart.config.data.datasets.length -1;
                            for (let i in chart.config.data.datasets) {
                                for(var j = chart.config.data.datasets[i].data.length - 1; j>= 0;--j) {
                                    var arc = chart.getDatasetMeta(i).data[j];
                                    arc.round = {
                                        x: (chart.chartArea.left + chart.chartArea.right) / 2,
                                        y: (chart.chartArea.top + chart.chartArea.bottom) / 2,
                                        radius: (arc.outerRadius + arc.innerRadius) / 2,
                                        thickness: (arc.outerRadius - arc.innerRadius) / 2 - 1,
                                        value : x[j],
                                        // thickness: chart.radiusLength / 2 - 1,
                                        backgroundColor: arc.options.backgroundColor
                                    }
                                }
                                a--;
                            }
                    },
                    afterDraw: function (chart) {
                            var ctx = chart.ctx;
                            for (let i in chart.config.data.datasets) {
                                for(var j = chart.config.data.datasets[i].data.length - 1; j>= 0;--j) {
                                    var arc = chart.getDatasetMeta(i).data[j];
                                    // console.log(arc)
                                    var startAngle = Math.PI / 2 - arc.startAngle;
                                    var endAngle = Math.PI / 2 - arc.endAngle;

                                    ctx.save();
                                    ctx.translate(arc.round.x, arc.round.y);
                                    ctx.fillStyle = arc.round.backgroundColor;
                                    ctx.beginPath();
                                    if(j == chart.config.data.datasets[i].data.length - 1) ctx.arc(arc.round.radius * Math.sin(startAngle), arc.round.radius * Math.cos(startAngle), arc.round.thickness, 0, 2 * Math.PI);
                                    if(j != 1) ctx.arc(arc.round.radius * Math.sin(endAngle), arc.round.radius * Math.cos(endAngle), arc.round.thickness, 0, 2 * Math.PI);
                                    ctx.closePath();
                                    ctx.fill();
                                    ctx.restore();
                                }
                            }
                    },
                }]
            };

            var ctx = document.getElementById(element).getContext("2d")

            // Init ChartJS -- for more info, please visit: https://www.chartjs.org/docs/latest/
            var myChart = new Chart(ctx, config);
        }


        function loadChart(key){

            var data = {}
            if(key == "reason_trend"){
                data['reason'] = $(`select[name="${key}_reason"]`).val()
                data['year'] = $(`select[name="${key}_year"]`).val()
            } else if(key == "overtime_trend"){
                data['departement'] = $(`select[name="${key}_departement"]`).val()
                data['year'] = $(`select[name="${key}_year"]`).val()
            } else if(key == "absence_rate"){
                data['reason'] = $(`select[name="${key}_reason"]`).val()
            }

            $.ajax({
                url : "{{ route("attendance.chart_widget") }}/" + key,
                type : "get",
                data : data,
                dataType : "json"
            }).then(function(resp){
                if(key == "reason_trend"){
                    barChart(resp, "#chart_"+key, "#FEB3B2")
                } else if(key == "overtime_trend"){
                    barChart(resp, "#chart_"+key)
                } else if(key == "overtime_hours"){
                    var m = resp.avg
                    var h = Math.floor(m/60)
                    var _m = m - (h * 60)
                    var lbl = ""
                    if(h > 0){
                        lbl += h +"h "
                    }
                    lbl += _m +"m"
                    $(`.${key}_avg`).text(lbl)

                    verticalChart(resp.chart, "#chart_"+key, function (val, opts) {
                        var m = val
                        var h = Math.floor(m/60)
                        var _m = m - (h * 60)
                        var lbl = ""
                        if(h > 0){
                            lbl += h +"h "
                        }
                        lbl += _m +"m"
                        return lbl
                    })
                } else if(key == "absence_rate"){
                    $(`.${key}_avg`).text(resp.avg + "%")
                    verticalChart(resp.chart, "#chart_"+key, function (val, opts) {
                        return val + " times"
                    })
                } else {
                    doughnutChart("chart_"+key, resp.labels, resp.chart)
                }
            })
        }

        $(document).ready(function(){
            loadAttTrack()
            loadAttData()
            loadLeaveRequest()
            loadTable()

            var widgets = @json($widgets)

            console.log(widgets)
            for (const key in widgets) {
                loadChart(key)
            }
        })
    </script>
@endsection
