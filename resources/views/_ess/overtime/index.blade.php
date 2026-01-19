@extends('_ess.layout')

@section('view_content')
<div class="card shadow-none card-p-0 h-100">
    <div class="card-body">
        <div class="d-flex flex-column gap-5 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-5">
                    <div class="symbol symbol-50px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-time-quarter-past text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold">Lembur</span>
                        <span>Anda dapat mengajukan lembur dan juga melihat riwayat lembur Anda</span>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_add_overtime">
                        <i class="fi fi-rr-plus"></i>
                        Pengajuan Lembur
                    </button>
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
                            <div>
                                <button type="button" class="btn btn-secondary border">
                                    <i class="fi fi-rr-filter"></i>
                                    Filter
                                </button>
                            </div>
                        </div>
                        <div class="scroll">
                            <table class="table table-display-2 bg-white" data-ordering="false" id="table-overtime">
                                <thead>
                                    <tr>
                                        <th>Nomor Referensi</th>
                                        <th>Tipe Lembur</th>
                                        <th>Dimulai</th>
                                        <th>Berakhir</th>
                                        <th>Jumlah Jam</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $dep_name = $depts->pluck("name", "id");
                                    @endphp
                                    @foreach ($overtimes as $item)
                                        @php
                                            $d1 = date_create($item->overtime_date." ".$item->overtime_start_time);
                                            $d2 = date_create($item->overtime_date." ".$item->overtime_end_time);
                                            $d3 = date_diff($d2, $d1);
                                            $h = $d3->format("%H");
                                        @endphp
                                        <tr>
                                            <td>{{ $item->ref_num ?? "-" }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">Lembur {{ $dcode[$item->reason_id] ?? "Workday" }}</span>
                                                    <span class="text-muted">Lembur {{ ucwords($item->overtime_type) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">{{ date("d-m-Y", strtotime($item->overtime_date)) }}</span>
                                                    <span class="text-muted">{{ date("H:i", strtotime($item->overtime_start_time)) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">{{ date("d-m-Y", strtotime($item->overtime_date)) }}</span>
                                                    <span class="text-muted">{{ date("H:i", strtotime($item->overtime_end_time)) }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $h }} h</td>
                                            <td>
                                                @if (empty($item->approved_at))
                                                    <span class="badge badge-secondary">Waiting</span>
                                                @else
                                                    <span class="badge badge-outline badge-success">Approved</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                    <i class="fi fi-rr-menu-dots-vertical"></i>
                                                </button>
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="javascript:;" onclick="show_detail({{$item->id}})" class="menu-link px-3">
                                                            Detail
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="javascript:;" @if(empty($item->approved_at)) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('ess.overtime.delete', ['id' => $item->id]) }}" data-id="{{ $item->id }}" @endif class="menu-link px-3 text-danger {{ !empty($item->approved_at) ? "disabled text-muted" : "" }}">
                                                            Delete
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
    </div>
</div>

<form action="{{ route("attendance.overtime.store") }}" method="post" enctype="multipart/form-data">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-lg"
        ])
        @slot('modalId')
            modal_add_overtime
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-time-quarter-past text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Pengajuan Lembur</h3>
                    <span class="text-muted fs-base"></span>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <input type="hidden" name="emp" value="{{ Auth::user()->emp_id }}">
            <div class="row">
                <div class="col-12">
                    <label class="col-form-label">Nomor Referensi : <span class="text-primary">{{ $last_ref }}</span></label>
                </div>
                <div class="fv-row col-12 col-md-6">
                    <label class="col-form-label fw-bold">Tanggal</label>
                    <input type="date" name="overtime_date" required value="{{ old("overtime_date") }}" required class="form-control" {{ old("overtime_date") == null ? "" : "" }}  placeholder="Input Data">
                    @error('overtime_date')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row col-12 col-md-6">
                    <label class="col-form-label fw-bold">Alasan</label>
                    <input type="text" name="reason" value="{{ old("reason") }}" readonly class="form-control" placeholder="Automatic Fill">
                    <input type="hidden" name="reason_id" value="{{ old("reason_id") }}" readonly class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="fv-row col-12 col-md-6">
                    <label class="col-form-label fw-bold">Jam Masuk Kerja</label>
                    <input type="time" name="sin" readonly class="form-control">
                </div>
                <div class="fv-row col-12 col-md-6">
                    <label class="col-form-label fw-bold">Jam Keluar Kerja</label>
                    <input type="time" name="sout" readonly class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="fv-row col-12 col-md-4">
                    <label class="col-form-label fw-bold">Tipe Lembur</label>
                    <select name="overtime_type" required class="form-select" data-control="select2"  data-placeholder="Pilih Tipe Lembur" data-dropdown-parent="#modal_add_overtime">
                        <option value=""></option>
                        @foreach (\Config::get("constants.overtime_type") as $key => $item)
                            <option value="{{ $key }}" {{ (old("overtime_type") ?? null) == $key ? "SELECTED" : "" }}>{{ $item }}</option>
                        @endforeach
                    </select>
                    @error('overtime_type')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row col-12 col-md-4">
                    <label class="col-form-label fw-bold">Dimulai</label>
                    <input type="time" name="start_date" required value="{{ old("start_date") }}" class="form-control" id="add-start-date"  placeholder="Day - 00:00">
                    @error('start_date')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row col-12 col-md-4">
                    <label class="col-form-label fw-bold">Berakhir</label>
                    <input type="time" name="end_date" required value="{{ old("end_date") }}" class="form-control" id="add-end-date"  placeholder="Day - 00:00">
                    @error('end_date')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="fv-row my-3">
                <div class="d-flex flex-column mb-5 repeater">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input"  name="break_overtime" onclick="add_bs(this)" type="checkbox" value="1" />
                                Tambahkan Break Lembur
                            </label>
                        </div>
                        <button type="button" class="btn text-primary d-none break-shift" data-repeater-create>
                            <i class="fa fa-plus text-primary"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="form-group d-none break-shift">
                        <div data-repeater-list="break_shift">
                            <div class="row" data-repeater-item>
                                <div class="col-5">
                                    <div class="fv-row">
                                        <label for="" class="col-form-label">Break <span class="break-num"></span></label>
                                        <input type="time" name="start" class="form-control" id="">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="fv-row">
                                        <label for="" class="col-form-label">Break <span class="break-num"></span> Berakhir</label>
                                        <input type="time" name="end" class="form-control" id="">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="fv-row">
                                        <label for="" class="col-form-label w-100">&nbsp;</label>
                                        <button data-repeater-delete class="bg-hover-light-danger bg-white btn btn-icon" type="button">
                                            <i class="fi fi-rr-trash text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="fv-row col-12 col-md-6">
                    <label class="col-form-label fw-bold">Paid By</label>
                    <select name="paid_type" class="form-select" required data-control="select2"  data-placeholder="Select" data-dropdown-parent="#modal_add_overtime">
                        <option value=""></option>
                        <option value="money">Money</option>
                        <option value="days">Days</option>
                        <option value="no paid">No Paid</option>
                    </select>
                </div>
                <div class="fv-row col-12 col-md-4 d-none">
                    <label class="col-form-label fw-bold">&nbsp;</label>
                    <input type="number" name="day" class="form-control" min="1"  value="1">
                </div>
                <div class="fv-row col-12 col-md-6">
                    <label class="col-form-label fw-bold">Departemen</label>
                    <select name="departement" class="form-select" required data-control="select2"  data-placeholder="Pilih Departemen" data-dropdown-parent="#modal_add_overtime">
                        <option value=""></option>
                        @foreach ($depts as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="fv-row">
                {{-- <label class="col-form-label fw-bold">Reference Number</label> --}}
                <input type="hidden" name="reference" value="{{ $last_ref }}" placeholder="OVT/thnblntgl/no urut"  class="form-control">
            </div>
            <div class="fv-row d-flex flex-column mt-5">
                <div class="d-flex align-items-center">
                    <label class="btn btn-secondary ">
                        <input type="file" data-required name="file" accept=".jpg,.png,.pdf" class="d-none">
                        Berkas Diunggah
                        <i class="fi fi-rr-clip"></i>
                    </label>
                    <span class="text-primary ms-5" data-file></span>
                </div>
                <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Kirim</button>
        @endslot
    @endcomponent
</form>

<div class="modal fade" tabindex="-1" id="modalDetail">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content px-10">

        </div>
    </div>
</div>


<div class="modal fade" tabindex="1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                    <span class="fw-bold fs-3 text-center">Apakah Anda yakin ingin membatalkan pengajuan Lembur ini?</span>
                    <span class="text-center">Melakukan ini dapat mempengaruhi data kehadiran dari employee</span>
                    <div class="d-flex align-items-center mt-5">
                        <a href="#" name="submit" value="cancel" id="delete-url" class="btn btn-white">Lanjutkan</a>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batalkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@component('layouts.components.fab', [
        "fab" => [
            ["label" => "Take attendance", "url" => "javascript:;", 'toggle' => 'onclick="take_attendance()"'],
            ["label" => "Pengajuan Cuti", "url" => route("ess.leave.index")."?modal=modal_add_leave"],
            ["label" => "Pengajuan Lembur", "url" => "javascript:;", 'toggle' => 'data-bs-toggle="modal" data-bs-target="#modal_add_overtime"'],
        ]
    ])
@endcomponent

@endsection

@section('view_script')
    <script src="{{ asset("theme/assets/plugins/custom/formrepeater/formrepeater.bundle.js") }}"></script>
    <script>

        function show_detail(id){
            $("#modalDetail .modal-content").html("")
            $.ajax({
                url : "{{ route("ess.overtime.detail") }}/" + id,
                type : "get",
                dataType : "json",
            }).then(function(resp){
                $("#modalDetail .modal-content").html(resp.view)
                $("#modalDetail").modal("show")
            })
        }

        function archiveItem(me){
            var url = $(me).data("url")
            $("#delete-url").attr("href", url)
            $("#modalDelete").modal("show")
        }

        function fnModal(target){
            $(target + " input[name=file]").change(function(){
                var val = $(this).val().split("\\")

                $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
            })

            $(target + " input[name=overtime_date]").change(function(){
                if($(this).val() != ""){
                    $.ajax({
                        url : "{{ route("attendance.overtime.index") }}?a=reason&id="+$(target + " input[name=emp]").val()+"&date=" + $(this).val(),
                        type : "get",
                        dataType : "json",
                    }).then(function(resp){
                        var data = resp.data
                        var day_name =data.day_name
                        $(target + " input[name=sin]").val(resp.work_start)
                        $(target + " input[name=sout]").val(resp.work_end)
                        $(target + " select[name=overtime_type]").find("option").each(function(){
                            var val = $(this).val()
                            $(this).prop("disabled", false)
                            if(day_name.toLowerCase() == "workday"){
                                if(val.includes("off")){
                                    $(this).prop("disabled", true)
                                }
                            } else {
                                if(!val.includes("off")){
                                    $(this).prop("disabled", true)
                                }
                            }
                        })
                        $(target + " input[name=reason]").val(data.day_name)
                        $(target + " input[name=reason_id]").val(data.id)
                        $(target + " select[name=overtime_type]").prop("disabled", false)
                    })
                }
            })

            $(target + " select[name=overtime_type]").change(function(){
                var me = $(this)
                if($(this).val() != ""){
                    $.ajax({
                        url : "{{ route("attendance.overtime.index") }}?a=shift_date",
                        type : "get",
                        data : {
                            id : $(target + " input[name=emp]").val(),
                            date : $(target + " input[name=overtime_date]").val()
                        },
                        dataType : "json",
                    }).then(function(resp){
                        var timin = resp.time_in
                        var timout = resp.time_out
                        $(target + " input[name=start_date]").val("")
                        $(target + " input[name=end_date]").val("")
                        $(target + " input[name=start_date]").prop("disabled", false)
                        $(target + " input[name=end_date]").prop("disabled", false)
                        $(target + " input[name=start_date]").prop("readonly", false)
                        $(target + " input[name=end_date]").prop("readonly", false)
                        if($(me).val() == "in"){
                            $(target + " input[name=end_date]").val(timin)
                            $(target + " input[name=end_date]").prop("readonly", true)
                        } else if($(me).val() == "out"){
                            $(target + " input[name=start_date]").val(timout)
                            $(target + " input[name=start_date]").prop("readonly", true)
                        }
                    })
                }
            })

            $(target + " input[name=start_date]").change(function(){
                $(target + " select[name=paid_type]").prop('disabled', false)
                $(target + " select[name=departement]").prop('disabled', false)
                $(target + " input[name=break_overtime]").prop('disabled', false)
            })
            $(target + " input[name=end_date]").change(function(){
                $(target + " select[name=paid_type]").prop('disabled', false)
                $(target + " select[name=departement]").prop('disabled', false)
                $(target + " input[name=break_overtime]").prop('disabled', false)
            })

            $(target + " select[name=paid_type]").change(function(){
                var row = $(this).parents("div.row")
                $(target + " input[name=reference]").prop("disabled", false)
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

        $(document).ready(function(){
            repeater_form("#modal_add_overtime")
            fnModal("#modal_add_overtime")
        })

    </script>
@endsection
