@extends('layouts.template', ["bgWrapper" => \Helper_function::isMobile() == 1 ? "" : "bg-white"])

@if(\Helper_function::isMobile() == 0)
    @section('content')
        <style>
            .fc-timegrid tbody tr {
                height: 100px;
            }
        </style>
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center w-100 justify-content-between mb-10">
                <div class="d-flex flex-column">
                    <span class="fw-bold">Jadwal Interview</span>
                    <div class="d-flex align-items-center">
                        <select name="job" id="job" class="form-select me-5" data-control="select2">
                            <option value="">Semua Job Ads</option>
                            @foreach ($job_ads as $item)
                                <option value="{{ $item->id }}" {{$job_sel == $item->id ? "SELECTED" : ""}} >{{ $item->position }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn text-primary text-nowrap">Lihat Semua Job Ads</button>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" data-toggle="tab" data-target="#calendar-view" class="btn active text-active-primary text-nowrap border-bottom border-2 border-dark rounded-0 border-active-primary">
                        <i class="fa fa-calendar text-dark active text-active-primary"></i>
                        Kalender Interview
                    </button>
                    <button type="button" data-toggle="tab" data-target="#list-view" class="btn text-active-primary text-nowrap border-bottom border-2 border-dark rounded-0 border-active-primary">
                        <i class="fa fa-users text-dark text-active-primary"></i>
                        Kandidat
                    </button>
                </div>
            </div>
            <div class="show" role="tab" id="calendar-view">
                <div class="d-flex">
                    <div class="card min-w-300px border">
                        <div class="card-body">
                            <div class="d-flex flex-column">
                                <button type="button" class="btn btn-outline-primary btn-outline mb-5">
                                    <i class="fa fa-plus-circle"></i>
                                    Tambah Kandidat
                                </button>
                                <!--begin::Accordion-->
                                <div class="accordion accordion-icon-toggle" id="kt_accordion_kandidat">
                                    <!--begin::Item-->
                                    <div class="mb-5">
                                        <!--begin::Header-->
                                        <div class="accordion-header py-3 d-flex justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_kandidat_item_1">
                                            <h3 class="fs-4 fw-semibold mb-0 me-4">Kandidat yang dipilih ({{ count($data_kandidat) }})</h3>
                                            <span class="accordion-icon">
                                                <i class="fa fa-caret-right fs-4"></i>
                                            </span>
                                        </div>
                                        <!--end::Header-->

                                        <!--begin::Body-->
                                        <div id="kt_accordion_kandidat_item_1" class="fs-6 collapse draggable-zone show" data-bs-parent="#kt_accordion_kandidat">
                                            @if (count($data_kandidat) == 0)
                                                <div class="border p-3 d-flex flex-column justify-content-center align-items-center rounded">
                                                    <span>Tidak ada kandidat yang dipilih</span>
                                                </div>
                                            @else
                                                @foreach ($data_kandidat as  $item)
                                                @php
                                                    $bgCard = "";
                                                    if($item['rejected_by_applicant']){
                                                        $bgCard = "bg-light-danger border-danger";
                                                    }
                                                @endphp
                                                <div class="mb-3 fc-event" data-id="{{ $item['id'] }}" data-type="{{ $item['type'] }}">
                                                    <div class="border p-3 d-flex align-items-center rounded fc-event-main {{ $bgCard }} {{ $item['rescheduled'] ? 'bg-light-warning border-warning' : "" }}">
                                                        <div class="symbol symbol-60px symbol-circle me-5">
                                                            @if ($item['type'] == "m")
                                                                <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset("theme/assets/media/logos/icon-sm.png") }}')"></div>
                                                            @else
                                                                <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset($item['img'] ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            @if($item['rescheduled'])
                                                            <span class="text-warning mb-5">Reschedule</span>
                                                            @endif
                                                            @if(!empty($item['rejected_by_applicant']))
                                                            <span class="text-danger mb-3">Ditolak Applicant</span>
                                                            @endif
                                                            <span class="fw-bold">{{ $item['name'] }}</span>
                                                            <span class="text-muted">{{ $item['pos'] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <!--end::Body-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Accordion-->
                                <!--begin::Accordion-->
                                <div class="accordion accordion-icon-toggle" id="kt_accordion_kandidat">
                                    <!--begin::Item-->
                                    <div class="mb-5">
                                        <!--begin::Header-->
                                        <div class="accordion-header py-3 d-flex justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_kandidat_item_1">
                                            <h3 class="fs-4 fw-semibold mb-0 me-4">Request Reschedule ({{ count($data_reschedule) }})</h3>
                                            <span class="accordion-icon">
                                                <i class="fa fa-caret-right fs-4"></i>
                                            </span>
                                        </div>
                                        <!--end::Header-->

                                        <!--begin::Body-->
                                        <div id="kt_accordion_kandidat_item_1" class="fs-6 collapse draggable-zone show" data-bs-parent="#kt_accordion_kandidat">
                                            @if (count($data_reschedule) == 0)
                                                <div class="border p-3 d-flex flex-column justify-content-center align-items-center rounded">
                                                    <span>Tidak ada kandidat yang dipilih</span>
                                                </div>
                                            @else
                                                @foreach ($data_reschedule as  $item)
                                                <div class="mb-3 fc-event" data-id="{{ $item['id'] }}" data-type="{{ $item['type'] }}">
                                                    <div class="border p-3 d-flex align-items-center rounded bg-light-warning border-warning">
                                                        <div class="symbol symbol-60px symbol-circle me-5">
                                                            @if ($item['type'] == "m")
                                                                <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset("theme/assets/media/logos/icon-sm.png") }}')"></div>
                                                            @else
                                                                <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset($item['img'] ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ $item['name'] }}</span>
                                                            <span class="text-muted mb-3">{{ $item['pos'] }}</span>
                                                            <button type="button" class="btn btn-primary btn-sm" onclick="modalReschedule({{$item['id']}})">Lihat detail</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <!--end::Body-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Accordion-->
                            </div>
                        </div>
                    </div>
                    <div class="card flex-fill border">
                        <div class="card-body">
                            <div class="d-flex flex-column">
                                {{-- <div class="d-flex justify-content-between align-items-center mb-5">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">@monthId(date("Y-m-d")) {{ date("Y") }}</span>
                                        <span class="text-muted">@dateId(date("Y-m-d"))</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <button type="button" class="btn btn-outline btn-outline-primary me-3">
                                            @monthId(date("Y-m-d")) {{ date("Y") }}
                                            <i class="fa fa-caret-down fs-2"></i>
                                        </button>
                                        <span class="me-3">Tampilan : </span>
                                        <button type="button" class="btn btn-outline btn-outline-primary">
                                            Week
                                            <i class="fa fa-caret-down fs-2"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon">
                                            <i class="fa fa-caret-left text-dark fs-2"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon">
                                            <i class="fa fa-caret-right text-dark fs-2"></i>
                                        </button>
                                    </div>
                                </div> --}}
                                <div id="kt_docs_fullcalendar_drag"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="" role="tab" id="list-view">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center mb-10">
                        <button type="button" class="btn btn-outline btn-outline-dark me-5">
                            <i class="fa fa-filter"></i>
                            Filter
                        </button>
                        <span class="me-3">Sort By :</span>
                        <div class="w-auto">
                            <select name="sort_by" id="sort_by" class="form-select" data-allow-clear="true" data-control="select2" data-placeholder="None">
                                <option value=""></option>
                                <option value="1" data-order="asc">Nama</option>
                                <option value="2" data-order="desc">Status Interview</option>
                                <option value="3" data-order="desc">Tipe Interview</option>
                                <option value="4" data-order="desc">Lokasi</option>
                                <option value="5" data-order="desc">Link Interview</option>
                                <option value="6" data-order="desc">Tanggal Interview</option>
                            </select>
                        </div>
                        <button type="button" data-toggle="tablist" data-target="#table-view" class="btn btn-active-primary active btn-icon mx-5 btn-outline">
                            <i class="fa fa-bars"></i>
                        </button>
                        <button type="button" data-toggle="tablist" data-target="#kanban-view" class="btn btn-active-primary btn-icon me-5 btn-outline">
                            <i class="fa fa-bars" style="rotate: 90deg"></i>
                        </button>
                    </div>
                    <div id="table-view" class="show" role="tablist">
                        <table class="table display" id="table-app">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Status Interview</th>
                                    <th>Tipe Interview</th>
                                    <th>Lokasi</th>
                                    <th>Link Interview</th>
                                    <th>Tanggal Interview</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_applicant as $i => $item)
                                    @php
                                        $status = "Backlog";
                                        $statusClass = "secondary";
                                        if($item->status == 3){
                                            $status = "Interview";
                                            $statusClass="yellow";
                                        } elseif($item->status > 3){
                                            $status = "Selesai Interview";
                                            $statusClass="blue";
                                        }
                                        $tp_int = [1=> "Online", "Offline"];
                                        $intv = $item->interview;
                                    @endphp
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <div class="symbol symbol-25px symbol-circle me-3">
                                                    <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset($user_img[$item->user_id] ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="{{route("calendar.applicant", $item->id)}}" class="text-dark mb-3 fw-bold">{{$user_name[$item->user_id] ?? "-"}}</a>
                                                    <span>Pengalaman 3 Tahun</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{$statusClass}}">{{ $status }}</span>
                                        </td>
                                        <td>{{ $tp_int[$intv->int_type ?? null] ?? "-" }}</td>
                                        <td>{{ $intv->int_location ?? "-" }}</td>
                                        <td>{{ $intv->int_link ?? "-" }}</td>
                                        <td>
                                            @if (!empty($intv))
                                                @dateId($intv->int_date)
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="kanban-view" role="tablist">
                        <div class="d-flex">
                            <div class="d-flex flex-column me-10">
                                <span class="fw-bold fs-3 mb-10">Backlog <span class="text-muted">({{ $all_applicant->where("status", 2)->count() }})</span></span>
                                <div class="d-flex flex-column">
                                    <a href="{{ empty($job_sel) ? route("job_report.index") : route("job_report.detail", $job_sel) }}" class="btn btn-outline btn-outline-primary rounded-0">
                                        <i class="fa fa-plus-circle"></i>
                                        Tambah Kandidat
                                    </a>
                                    <div class="draggable-zone d-flex flex-column min-h-100px" data-status="2" data-kt-draggable-level="restricted">
                                        @foreach ($all_applicant->where("status", 2) as $item)
                                            <div class="draggable">
                                                <div class="d-flex draggable-handle align-items-start border p-3" data-id="{{ $item->id }}">
                                                    <div class="symbol symbol-25px symbol-circle me-3">
                                                        <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset($user_img[$item->user_id] ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="badge badge-secondary mb-3">Backlog</span>
                                                        <span class="mb-3 fw-bold">{{$user_name[$item->user_id] ?? "-"}}</span>
                                                        <span>{{ $job_pos[$item->job_id] ?? "-" }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column me-10">
                                <span class="fw-bold fs-3 mb-10">Interview <span class="text-muted">({{$all_applicant->where("status", 3)->count()}})</span></span>
                                <div class="d-flex flex-column">
                                    <div class="draggable-zone d-flex flex-column min-h-100px" data-status="3" data-kt-draggable-level="restricted">
                                        @foreach ($all_applicant->where("status", 3) as $item)
                                            <div class="draggable">
                                                <div class="d-flex draggable-handle align-items-start border p-3" data-id="{{ $item->id }}">
                                                    <div class="symbol symbol-25px symbol-circle me-3">
                                                        <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset($user_img[$item->user_id] ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="badge badge-yellow mb-3">Interview</span>
                                                        <span class="mb-3 fw-bold">{{$user_name[$item->user_id] ?? "-"}}</span>
                                                        <span>{{ $job_pos[$item->job_id] ?? "-" }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column me-10">
                                <span class="fw-bold fs-3 mb-10">Lolos <span class="text-muted">({{$all_applicant->where("status", 4)->count()}})</span></span>
                                <div class="d-flex flex-column">
                                    <div class="draggable-zone d-flex flex-column min-h-100px" data-status="4">
                                        @foreach ($all_applicant->where("status", 4) as $item)
                                            <div class="draggable">
                                                <div class="d-flex draggable-handle align-items-start border p-3" data-id="{{ $item->id }}">
                                                    <div class="symbol symbol-25px symbol-circle me-3">
                                                        <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset($user_img[$item->user_id] ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="badge badge-green mb-3">Lolos</span>
                                                        <span class="mb-3 fw-bold">{{$user_name[$item->user_id] ?? "-"}}</span>
                                                        <span>{{ $job_pos[$item->job_id] ?? "-" }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column me-10">
                                <span class="fw-bold fs-3 mb-10">Gagal <span class="text-muted">({{$all_applicant->where("status", 5)->count()}})</span></span>
                                <div class="d-flex flex-column">
                                    <div class="draggable-zone d-flex flex-column min-h-100px" data-status="5">
                                        @foreach ($all_applicant->where("status", 5) as $item)
                                            <div class="draggable">
                                                <div class="d-flex draggable-handle align-items-start border p-3" data-id="{{ $item->id }}">
                                                    <div class="symbol symbol-25px symbol-circle me-3">
                                                        <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset($user_img[$item->user_id] ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="badge badge-red mb-3">Gagal</span>
                                                        <span class="mb-3 fw-bold">{{$user_name[$item->user_id] ?? "-"}}</span>
                                                        <span>{{ $job_pos[$item->job_id] ?? "-" }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route("calendar.reschedule_update") }}" method="post">
            <div class="modal fade" tabindex="-1" id="modalReschedule">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                    </div>
                </div>
            </div>
        </form>

        <form action="{{ route("calendar.add") }}" method="post">
            <div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="modalAddInterview">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">

                    </div>
                </div>
            </div>
        </form>

        <div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="modalEventGroup">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex flex-column align-items-center">
                            <span class="fw-bold mb-5">Pilih Kandidat</span>
                            <div class="d-flex flex-column align-items-center div-applicant"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('custom_script')
        <link href="{{ asset("theme/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css") }}" rel="stylesheet" type="text/css" />
        <script src="{{ asset("theme/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js") }}"></script>
        <script src="{{ asset("theme/assets/plugins/custom/draggable/draggable.bundle.js") }}"></script>
        <script>
            var containerEl = document.getElementById("kt_accordion_kandidat_item_1");
            new FullCalendar.Draggable(containerEl, {
                itemSelector: ".fc-event",
                eventData: function(eventEl) {
                    return {
                        title: eventEl.innerText.trim()
                    }
                }
            });

            function updateStartTime(ind){
                $("#modalAddInterview [name=end_time]").find("option").each(function(){
                    var $ind = $(this).data("index")
                    if($ind <= ind){
                        $(this).prop('disabled', true)
                    } else {
                        $(this).prop('disabled', false)
                    }
                })
            }

            function clickEvent(data){
                console.log(data)
            }

            function modalReschedule(id){
                $("#modalReschedule .modal-content").html("")
                $.ajax({
                    url : "{{ route('calendar.reschedule') }}/" + id,
                    type : "get",
                    dataType : "json",
                }).then(function(resp){
                    $("#modalReschedule").modal("show")
                    $("#modalReschedule .modal-content").html(resp.view)
                })
            }

            var routeEvent = "{{ route('calendar.event') }}"
            if($("#job").val() != ""){
                routeEvent += "?job="+$("#job").val()
            }

            // initialize the calendar -- for more info please visit the official site: https://fullcalendar.io/demos
            var calendarEl = document.getElementById("kt_docs_fullcalendar_drag");
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek"
                },
                locale: "id",
                initialView: "timeGridWeek",
                editable: false,
                droppable: true, // this allows things to be dropped onto the calendar
                drop: function(arg) {
                    // is the "remove after drop" checkbox checked?
                    // if (document.getElementById("drop-remove").checked) {
                    //     // if so, remove the element from the "Draggable Events" list
                    //     arg.draggedEl.parentNode.removeChild(arg.draggedEl);
                    // }
                    var date = new Date(arg.date)
                    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    var el = arg.draggedEl
                    var id = $(el).data("id")
                    var type = $(el).data("type")
                    $.ajax({
                        url : "{{ route("calendar.index")."?a=modal" }}",
                        type : "get",
                        dataType : "json",
                        data : {
                            id : id,
                            type : type,
                            date : date.toLocaleString("en-US")
                        }
                    }).then(function (resp) {
                        $("#modalAddInterview .modal-content").html(resp.view)
                        $("#modalAddInterview").modal("show")
                        $("#modalAddInterview [data-control=select2]").select2()
                        $("#modalAddInterview .ck-editor").each(function(){
                            var _id = $(this).attr("id")
                            ClassicEditor.create(this).then(function(editor){
                                _editor[_id] = editor
                            })
                        })

                        $("#modalAddInterview .tempusDominus").each(function(){
                            var _id = $(this).attr("id")
                            var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                                useCurrent: false,
                                display : {
                                    viewMode: "calendar",
                                    components: {
                                        decades: true,
                                        year: true,
                                        month: true,
                                        date: true,
                                        hours: false,
                                        minutes: false,
                                        seconds: false
                                    }
                                },
                                localization: {
                                    locale: "id",
                                    startOfTheWeek: 1,
                                    format: "dddd, d MMMM yyyy"
                                }
                            });
                        })

                        $("#modalAddInterview [name=date]").val(date.toLocaleString("id-ID", options))

                        $("#modalAddInterview [name=start_time]").on("change", function(){
                            var opt = $(this).find("option:selected")
                            var ind = $(opt).data("index")
                            updateStartTime(ind)
                        })

                        var opt = $("#modalAddInterview [name=start_time]").find("option:selected")
                        var ind = $(opt).data("index")
                        updateStartTime(ind)

                        $("#int_type").change(function(){
                            var val = $(this).val()

                            var int_link = $("#modalAddInterview [name=int_link]")
                            var int_lokasi = $("#modalAddInterview [name=int_lokasi]")

                            $(int_link).parents(".fv-row").hide()
                            $(int_lokasi).parents(".fv-row").hide()

                            if(val == 1){
                                $(int_link).parents(".fv-row").show()
                            } else {
                                $(int_lokasi).parents(".fv-row").show()
                            }
                        })
                    })
                },
                eventSources: [
                    {
                        url : routeEvent,
                        dataType : "json",
                    }
                ],
                selectMirror: true,
                selectable: true,
                select : function(arg){
                    console.log(arg)
                },
                allDaySlot: false,
                progressiveEventRendering: true,
                eventBackgroundColor : "#E1D7FA",
                eventTextColor : "#000",
                eventClick : function(arg){
                    var data = arg.event.extendedProps
                    if(data.id_int != undefined){
                        if(data.type == "n"){
                            location.href = "{{ route("calendar.applicant") }}/" + data.id_int
                        } else {
                            var applicants = data.applicants
                            var el = ""
                            for (let i = 0; i < applicants.length; i++) {
                                const element = applicants[i];
                                var route = "{{ route("calendar.applicant") }}/" + element.id
                                el += `<a href="${route}" class="d-flex align-items-center mb-5 text-dark text-hover-primary fw-bold">`
                                el += `<i class="fa fa-user text-dark me-3"></i>`
                                el += `<span>${element.name}</span>`
                                el += `</a>`
                            }

                            $("#modalEventGroup").modal("show")
                            $("#modalEventGroup .div-applicant").html(el)
                        }
                    }
                },
                eventContent : function(arg){
                    let divEl = document.createElement('div')
                    $(divEl).addClass("d-flex flex-column")
                    // divEl.addEventListener("click", clickEvent(arg))
                    var data = arg.event.extendedProps

                    // $(divEl).attr("data-id", )

                    var title = arg.event.title.split("\n")
                    var pos = title[1]
                    if(data.pos != undefined){
                        pos = data.pos
                    }
                    var el = `<span class='badge badge-yellow mb-2'>Interview</span>`
                    el += `<span class='mb-2 fw-bold'>${title[0]}</span>`
                    el += `<span class='mb-2'>${pos}</span>`
                    el += `<span class='mb-2'>${arg.timeText}</span>`
                    if(data.applicants != undefined){
                        var applicant = data.applicants
                        for (let i = 0; i < applicant.length; i++) {
                            const appl = applicant[i];
                            el += `<div class='d-flex align-items-center'>`
                            el += `<span class='fa fa-user me-3'></span>`
                            el += `<span>${appl['name']}</span>`
                            el += `</div>`

                        }
                    }
                    $(divEl).html(el)

                    let arrayOfDomNodes = [ divEl ]
                    return { domNodes: arrayOfDomNodes }
                },
            });

            $('#modalAddInterview').on('hidden.bs.modal', function () {
                var events = calendar.getEvents()
                var last = events[events.length - 1]
                if(last != undefined){
                    last.remove()
                }
            })

            $(".fc-timegrid-slots tr").each(function(){
                console.log($(this))
                $(this).attr("height", "50px")
            })

            calendar.render();

            @if ($errors->any())
                Swal.fire("", "{{ $errors->first() }}", "error")
            @endif

            @if (\Session::has("msg"))
                Swal.fire("", "{{ \Session::get('msg') }}", "success")
            @endif

            $("div[role=tab]").each(function(){
                $(this).hide()
                if($(this).hasClass("show")){
                    $(this).show()
                }
            })

            $("button[data-toggle=tab]").click(function(){
                $("button[data-toggle=tab]").removeClass("active")
                $("button[data-toggle=tab]").find("i").removeClass("active")
                $(this).addClass("active")
                var target = $(this).data("target")
                $("div[role=tab]").removeClass("show")
                $(target).addClass("show")
                $("div[role=tab]").each(function(){
                    $(this).hide()
                    if($(this).hasClass("show")){
                        $(this).show()
                    }
                })
            })


            var tb = $("#table-app").DataTable({
                columnDefs : [
                    {
                        targets : 0,
                        orderable : false
                    }
                ]
            })
            $("#sort_by").change(function(){
                var val = $(this).val()
                var order = $(this).find("option:selected").data("order")
                tb.order(val, order).draw()
            })

            var containers = document.querySelectorAll(".draggable-zone");
            const restrcitedWrapper = document.querySelector("[data-kt-draggable-level=restricted]");

            var swappable = new Droppable.default(containers, {
                draggable: ".draggable",
                dropzone: ".draggable-zone",
                handle: ".draggable .draggable-handle",
                mirror: {
                    //appendTo: selector,
                    appendTo: "body",
                    constrainDimensions: true
                }
            });

            // Define draggable element variable for permissions level
            let droppableOrigin;

            swappable.on("drag:stopped", (e) => {
                var newSource = e.data.originalSource
                var newFunnel = $(newSource).parents(".draggable-zone")
                var _row = $(newFunnel).find("div.draggable")
                var order = {}
                var ind = 1
                var oldSource = e.data.sourceContainer

                var newStatus = $(newFunnel).data("status")

                var clss = "green"
                var badgeText = "Lolos"
                if(newStatus == 5){
                    clss = "red"
                    badgeText = "Gagal"
                }

                var badge = $(newSource).find(".badge")
                $(badge).removeClass("badge-yellow").removeClass("badge-green").removeClass("badge-red").addClass(`badge-${clss}`)
                $(badge).text(badgeText)

                // removeClass("badge-yellow badge-green badge-red").addClass(`badge-${clss}`)

                var id = $(newSource).find(".draggable-handle").data("id")
                update_interview(id, newStatus).then(function(resp){
                    update_count()
                })
            });

            swappable.on("droppable:dropped", function(e){
                const isRestricted = e.dropzone.closest("[data-kt-draggable-level=restricted]");
                // Detect if drop container is restricted
                if (isRestricted) {
                    // Check if dragged element has permission level
                    e.cancel();
                }
            })

            $("div[role=tablist]").each(function(){
                console.log($(this))
                $(this).hide()
                if($(this).hasClass("show")){
                    $(this).show()
                }
            })

            $("button[data-toggle=tablist]").click(function(){
                $("button[data-toggle=tablist]").removeClass("active")
                $("button[data-toggle=tablist]").find("i").removeClass("active")
                $(this).addClass("active")
                var target = $(this).data("target")
                $("div[role=tablist]").removeClass("show")
                $(target).addClass("show")
                $("div[role=tablist]").each(function(){
                    $(this).hide()
                    if($(this).hasClass("show")){
                        $(this).show()
                    }
                })
            })

            $("#job").change(function(){
                var route = "{{ route("calendar.index") }}"
                if($(this).val() != ""){
                    route += "?job="  + $(this).val()
                }
                location.href = route
            })


            function update_interview(id, status){
                var route = "{{ route('calendar.applicant_update') }}/"+id+"?status="+status
                return $.ajax({
                    url : route,
                    type : "get",
                    dataType : "json"
                })
            }

            function update_count(){
                $(".draggable-zone").each(function(){
                    var draggable = $(this).find(".draggable")
                    var cnt = draggable.length
                    $(this).parent().prev().find("span.text-muted").text(`(${cnt})`)
                })
            }
        </script>
    @endsection
@else
    @section('content')
        @include('_error.desktop_only')
    @endsection
@endif
