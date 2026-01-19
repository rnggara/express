@extends('_ess.layout', [
    "bgWrapper" => "",
    "contentHeight" => "h-100"
])

@section('view_content')
<div class="row g-8 h-100">
    <div class="col-12 col-lg-8">
        <div class="d-flex flex-column gap-8 h-100">
            @if ($boardTask->count() > 0)
                <div class="card shadow-none card-p-0">
                    <div class="card-body py-5 px-7">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="symbol symbol-40px">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="fi fi-sr-handshake text-primary fs-3"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fs-3 fw-bold">Tugas Orientasi</span>
                                    <span class="text-muted">Daftar pegawai orientasi yang perlu anda ketahui</span>
                                </div>
                                <!--begin::Radio group-->
                            </div>
                            <div>
                                <a href="{{ route('personel.onboarding.approve_data', Auth::id()) }}" class="btn btn-sm btn-primary">
                                    Klik disini untuk melihat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($brDetail->count() > 0)
                <div class="card shadow-none card-stretch card-p-0 h-200px">
                    <div class="card-body py-5 px-7">
                        <div class="d-flex flex-column gap-7">
                            <div class="d-flex align-items-center gap-3">
                                <div class="symbol symbol-40px">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="fi fi-sr-handshake text-primary fs-3"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fs-3 fw-bold">Orientasi</span>
                                    <span class="text-muted">Daftar orientasi untuk Anda</span>
                                </div>
                                <!--begin::Radio group-->
                            </div>
                            <div class="d-flex">
                                @php
                                    $uploadFile = $brDetail->where("type", "upload_file")->whereNotNull("action_at")->count();
                                    $downloadFile = $brDetail->where("type", "download_file")->whereNotNull("action_at")->count();
                                    $task = $brDetail->where("type", "task")->whereNotNull("action_at")->count();
                                    $uploadFileTotal = $brDetail->where("type", "upload_file")->count();
                                    $downloadFileTotal = $brDetail->where("type", "download_file")->count();
                                    $taskTotal = $brDetail->where("type", "task")->count();
                                @endphp
                                <ul class="nav border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
                                    <li class="nav-item w-md-200px me-0">
                                        <a class="nav-link text-active-primary text-dark active" data-bs-toggle="tab" href="#onboarding_upload">
                                            <i class="fi fi-rr-caret-right"></i>
                                            Unggah Dokumen <span class="text-danger">({{ "$uploadFile/$uploadFileTotal" }})</span>
                                        </a>
                                    </li>
                                    <li class="nav-item w-md-200px me-0">
                                        <a class="nav-link text-active-primary text-dark" data-bs-toggle="tab" href="#kt_vtab_pane_2">
                                            <i class="fi fi-rr-caret-right"></i>
                                            Unduh Dokumen <span class="text-danger">({{ "$downloadFile/$downloadFileTotal" }})</span>
                                        </a>
                                    </li>
                                    <li class="nav-item w-md-200px">
                                        <a class="nav-link text-active-primary text-dark" data-bs-toggle="tab" href="#onboarding_task">
                                            <i class="fi fi-rr-caret-right"></i>
                                            Tugas <span class="text-danger">({{ "$task/$taskTotal" }})</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content flex-fill" id="myTabContent">
                                    <div class="tab-pane fade show active" id="onboarding_upload" role="tabpanel">
                                        <div class="d-flex gap-5 w-100 justify-content-between">
                                            <div class="d-flex flex-column gap-2 bg-secondary-crm rounded p-5 h-100px scroll-y w-100">
                                                @foreach ($brDetail->where("type", "upload_file") as $item)
                                                    @php
                                                        $form = $item['detail'];
                                                    @endphp
                                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="symbol symbol-30px">
                                                                <div class="symbol-label bg-light-primary">
                                                                    @if ($item['action_at'] != null)
                                                                        <i class="fi fi-sr-check text-primary fs-3"></i>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <span>{{ $form['form_name'] }}</span>
                                                        </div>
                                                        <div class="ms-5 text-{{ empty($item['action_at']) ? "danger" : "success" }}">
                                                            @if (empty($item['action_at']))
                                                                Deadline on {{ date("d-m-Y", strtotime($item['due_date'])) }}
                                                            @else
                                                                <i class="fi fi-sr-check-circle text-success"></i>
                                                                Uploaded on {{ date("d-m-Y", strtotime($item['action_at'])) }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <a href="{{ route("personel.onboarding.upload", Auth::id()) }}?=onboarding_upload" class="btn btn-light-primary w-100 d-flex flex-column justify-content-center">
                                                <span class="fw-bold">Klik disini untuk melengkapi</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="kt_vtab_pane_2" role="tabpanel">
                                        <div class="d-flex flex-column gap-2 bg-secondary-crm rounded p-5 h-100px scroll-y w-100">
                                            @foreach ($brDetail->where("type", "download_file") as $item)
                                            @php
                                                $form = $item['detail'];
                                            @endphp
                                            <form action="" class="mb-3" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item['id'] }}">
                                                <input type="hidden" name="type" value="download_file">
                                                <div class="fv-row">
                                                    <label class="col-form-label w-100">{{ $form->form_name }}</label>
                                                    <div class="d-flex align-items-center">
                                                        <label class="btn btn-primary" onclick="onboardAction('download_file', '{{ $item['id'] }}', this)">
                                                            {{ $form->file_name }}
                                                            <i class="fi fi-rr-download"></i>
                                                        </label>
                                                        <div class="ms-5 text-{{ !empty($item['action_at']) ? 'primary' : "muted" }}">
                                                            @if (!empty($item['action_at']))
                                                                <i class="fi fi-sr-check-circle text-primary"></i> Downloaded by User
                                                            @else
                                                                Not downloaded
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="separator separator-solid"></div>
                                        @endforeach
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="onboarding_task" role="tabpanel">
                                        <div class="d-flex gap-5 w-100 justify-content-between">
                                            <div class="d-flex flex-column gap-2 bg-secondary-crm rounded p-5 h-100px scroll-y w-100">
                                                @foreach ($brDetail->where("type", "task") as $item)
                                                    @php
                                                        $form = $item['detail'];
                                                    @endphp
                                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="symbol symbol-30px">
                                                                <div class="symbol-label bg-light-primary">
                                                                    @if ($item['action_at'] != null)
                                                                        <i class="fi fi-sr-check text-primary fs-3"></i>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <span>{{ $form['form_name'] }}</span>
                                                        </div>
                                                        <ol class="breadcrumb breadcrumb-dot text-muted fs-6 fw-semibold my-3">
                                                            <li class="breadcrumb-item"><span class="fi fi-rr-user"></span></li>
                                                            <li class="breadcrumb-item"><span class="">{{ $form->picData->name ?? "" }}</span></li>
                                                            <li class="breadcrumb-item text-{{ empty($item['action_at']) ? "danger" : "success" }}">
                                                                @if (empty($item['action_at']))
                                                                    Deadline on {{ date("d-m-Y", strtotime($item['due_date'])) }}
                                                                @else
                                                                    <i class="fi fi-sr-check-circle text-success"></i>
                                                                    Uploaded on {{ date("d-m-Y", strtotime($item['action_at'])) }}
                                                                @endif
                                                            </li>
                                                        </ol>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <a href="{{ route("personel.onboarding.upload", Auth::id()) }}?t=onboarding_task" class="btn btn-light-primary w-100 d-flex flex-column justify-content-center">
                                                <span class="fw-bold">Klik disini untuk melengkapi</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card shadow-none card-stretch card-p-0 h-md-200px h-100">
                <div class="card-body py-5 px-7">
                    <div class="d-flex flex-column gap-7">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-3 fw-bold">Performa</span>
                            <!--begin::Radio group-->
                            <div class="btn-group" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                                <!--begin::Radio-->
                                <label class="btn btn-outline btn-sm btn-color-muted active bg-hover-white bg-active-white bg-secondary" data-kt-button="true">
                                    <!--begin::Input-->
                                    <input class="btn-check" onclick="getAttSummary()" checked="checked" type="radio" name="att_summary_tp" value="ytd"/>
                                    <!--end::Input-->
                                    YTD
                                </label>
                                <!--end::Radio-->
                                <label class="btn btn-outline btn-sm btn-color-muted bg-hover-white bg-active-white bg-secondary" data-kt-button="true">
                                    <!--begin::Input-->
                                    <input class="btn-check" onclick="getAttSummary()" type="radio" name="att_summary_tp" value="mtd"/>
                                    <!--end::Input-->
                                    MTD
                                </label>
                                <!--end::Radio-->
                            </div>
                        </div>
                        <div id="att-summary" class="rounded px-5"></div>
                    </div>
                </div>
            </div>
            @include("_ess.widgets.rside", [
                "class" => "d-md-none d-inline-flex w-100"
            ])
            <div class="card shadow-none card-stretch card-p-0 flex-fill">
                <div class="card-body pb-5 pt-5 pt-lg-0 px-7">
                    <div class="align-items-start d-flex flex-column flex-lg-row gap-10 h-100 w-100">
                        <div class="d-flex flex-column gap-3 h-100 w-100 flex-fill">
                            <div class="align-items-center d-flex flex-column flex-md-row h-100 justify-content-between w-100">
                                <span class="fs-3 fw-bold">Pelacak Kehadiran</span>
                                <div class="d-flex gap-3">
                                    <select name="att_month" onchange="loadAtt()" class="form-select min-w-100px" data-control="select2">
                                        @foreach ($idFullMonth as $i => $item)
                                            <option value="{{$i}}" {{ date("n") == $i ? "SELECTED" : "" }} >{{$item}}</option>
                                        @endforeach
                                    </select>
                                    <select name="att_year" onchange="loadAtt()" class="form-select min-w-100px" data-control="select2">
                                        @for ($i = date("Y") - 3; $i <= date("Y"); $i++)
                                            <option value="{{$i}}" {{ date("Y") == $i ? "SELECTED" : "" }} >{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="align-items-start d-flex flex-column flex-lg-row gap-10 h-100 w-100">
                                <div class="flex-fill overflow-auto overflow-lg-visible w-100">
                                    <table class="w-700px w-lg-100 table-sm border-0" id="table-attendance">
                                        <thead>
                                            <tr class="border-0">
                                                @foreach ($dayFull as $item)
                                                    <th class="text-center" class="w-100px">{{ $item }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="d-flex flex-column gap-3 h-100 d-none d-lg-inline">
                                    {{-- <div style="height: 49px"></div> --}}
                                    <div class="border border-gray-200 border-right-0 border-top-0 h-100"></div>
                                </div>
                                <div class="d-flex flex-column gap-3 w-lg-500px ps-5 mt-2">
                                    {{-- <div style="height: 49px"></div> --}}
                                    <span class="fw-bold">Alasan</span>
                                    <div class="d-flex flex-lg-column flex-row flex-wrap gap-5">
                                        @foreach ($reasonGroup as $item)
                                        <div class="d-flex align-items-center me-3">
                                            <div class="me-3 symbol symbol-20px symbol-circle">
                                                <div class="symbol-label" style="background-color: {{$item->color}}"></div>
                                            </div>
                                            <span style="font-size: 14px">{{$item->group_name}}</span>
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
    </div>
    <div class="col-12 col-lg-4">
        @include("_ess.widgets.rside", [
            "class" => "d-none d-lg-inline-flex w-100"
        ])
    </div>
</div>

@component('layouts.components.fab', [
        "fab" => [
            ["label" => "Take attendance", "url" => "javascript:;", 'toggle' => 'onclick="take_attendance()"'],
            ["label" => "Request Leave", "url" => route("ess.leave.index")."?modal=modal_add_leave"],
            ["label" => "Request Overtime", "url" => route("ess.overtime.index")."?modal=modal_add_overtime"],
        ]
    ])
@endcomponent

@endsection

@section('view_script')
    <script>

        var divPerformance = document.querySelector("#att-summary")


        var blockAttSummary = new KTBlockUI(divPerformance)

        function onboardAction(type, id, me){
            if(type == "download_file"){
                var form = $(me).parents("form")
                var fileuploaddata = new FormData($(form)[0])
                $.ajax({
                    url: "{{ route("personel.onboarding.update") }}",
                    type: "POST",
                    data: fileuploaddata,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(result) {
                        if(result.success){
                            var lbl = $(me).next()
                            $(lbl).html(`<i class="fi fi-sr-check-circle text-primary"></i> Downloaded by User`)
                            $(lbl).removeClass("text-muted")
                            $(lbl).addClass("text-primary")

                            var link = document.createElement("a");
                            link.download = result.message;
                            link.target = "_blank"
                            link.href = result.data;
                            link.click();
                            link.remove()
                        }
                    }
                })
            }
        }

        function getAttSummary(){
            $("#att-summary").html("")
            blockAttSummary.block()
            $.ajax({
                url : "{{ route("ess.index") }}?a=att_summary",
                type : "get",
                data : {
                    tp : $("input[name=att_summary_tp]:checked").val()
                },
                dataType : "json"
            }).then(function(resp){
                blockAttSummary.release()
                $("#att-summary").html(resp.view)
            })
        }

        function listRequest(me){
            var key = $(me).data("key")
            var target = $(me).data('target')
            var el = $('[data-id="' + target + '"]')
            $(el).html("")

            $.ajax({
                url : "{{ route("ess.index") }}",
                type : "get",
                data : {
                    a : "list",
                    k : key,
                    tg : target
                },
                dataType : "json"
            }).then(function(resp){
                $(el).html(resp.view)
            })
        }

        function loadAtt(act = null){
            var m = $("select[name=att_month]").val()
            var y = $("select[name=att_year]").val()

            var dt = y + "-" + String(m).padStart(2, "0")

            $.ajax({
                url : "{{ route("attendance.registration.detail", Auth::user()->emp_id) }}?a=attendance",
                type : "get",
                data : {
                    month : dt,
                    act : act,
                    s : "ess"
                },
                dataType : "json"
            }).then(function(resp){
                $("#table-attendance tbody").html(resp.view)
                $("#table-attendance tbody td").addClass("text-center")
                $("#table-attendance tbody td div.symbol").removeClass("symbol-40px").addClass("symbol-md-75px")
                $("#table-attendance tbody td").each(function(){
                    var smb = $(this).find("div.symbol")
                    var lbl = $(smb).find("div.symbol-label")
                    $(smb).css("background-color", $(lbl).css("background-color"))
                    $(smb).prop("class", "align-items-center d-flex h-50px justify-content-center rounded w-75px")
                    // $(this).css("background-color", $(smb).css("background-color"))
                    // $(this).addClass("rounded-4")
                    // $(this).addClass("w-100px")
                    // $(this).addClass("border")
                    // $(this).addClass("border-white")
                    // $(this).addClass("border-5")
                    // $(this).css("width", "100px!important")
                    // $(this).css("heigth", "100px!important")
                })
                $("#table-attendance tbody tr").each(function(){
                    var td = $(this).find("td").eq(0)
                    $(td).addClass("ps-0")
                })
                // $("#table-attendance").css("border-spacing", "16px")
                // $("#table-attendance").removeClass("gx-0")
                $("#table-attendance").removeClass("gs-7")
                $("#table-attendance").removeClass("gy-7")
            })
        }

        $(document).ready(function(){
            getAttSummary()
            loadAtt()

            $("[data-list-item].active").each(function(){
                listRequest(this)
            })

            $("[data-list-item]").click(function(){
                var par = $(this).parent()
                $(par).find("[data-list-item]").removeClass("active")
                $(this).addClass("active")
            })
        })

    </script>
@endsection
