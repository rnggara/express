@extends('_attendance.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-50px me-3">
            <div class="symbol-label bg-primary">
                <i class="fi fi-sr-time-quarter-past text-white fs-1"></i>
            </div>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-3 fw-bold">Lembur Karyawan</span>
            <span class="text-muted">Manajemen pengisian lembur karyawan </span>
        </div>
    </div>
    <div class="d-flex flex-column">
        <div class="card bg-secondary-crm">
            <div class="card-body">
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
                                        <select name="ftime" class="form-select" data-control="select2" data-placeholder="Select Time" data-allow-clear="true">
                                            <option value=""></option>
                                        </select>
                                        <div class="mx-3"></div>
                                        <select name="fworkgroup" class="form-select" onchange="ftable(this,0)" data-control="select2" data-placeholder="Select Workgroup" data-allow-clear="true">
                                            <option value=""></option>
                                            @foreach ($workgroups as $item)
                                                <option value="{{ $item->workgroup_name }}">{{ $item->workgroup_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="mx-3"></div>
                                        <select name="fdepartement" class="form-select" onchange="ftable(this,5)" data-control="select2" data-placeholder="Select Departement" data-allow-clear="true">
                                            <option value=""></option>
                                            @foreach ($depts as $item)
                                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="mx-3"></div>
                                        <select name="ftype" class="form-select" onchange="ftable(this,2)" data-control="select2" data-placeholder="Select Overtime Type" data-allow-clear="true">
                                            <option value=""></option>
                                            <option value="in">Overtime In</option>
                                            <option value="out">Overtime Out</option>
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
                                    Name
                                </th>
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
                                    <td>{{ $item->dept->name ?? "-" }}</td>
                                    <td>
                                        @if (empty($item->approved_at) && empty($item->rejected_at))
                                            <span class="badge badge-outline badge-warning">Persetujuan</span>
                                        @else
                                            @if (!empty($item->approved_at))
                                                <span class="badge badge-outline badge-success">Approved</span>
                                            @else
                                                <span class="badge badge-outline badge-danger">Rejected</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-sm" onclick="show_detail({{ $item->id }}, '{{ empty($item->approved_at) && empty($item->rejected_at) ? 'approval' : '' }}')" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail">
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
                    <h3 class="me-2">Add Overtime</h3>
                    <span class="text-muted fs-base">Tambahkan data overtime untuk employee atau departement </span>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <div class="fv-row">
                <label class="col-form-label fw-bold">Employee</label>
                <select name="emp" class="form-select" data-control="select2" data-placeholder="Search Name Employee or ID Employee" data-dropdown-parent="#modal_add_overtime">
                    <option value=""></option>
                    @foreach ($emp as $item)
                        <option value="{{ $item->id }}" {{ (old("emp") ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->emp_id ." - ". $item->emp_name }}</option>
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
                    <label class="col-form-label fw-bold">Date</label>
                    <input type="date" name="overtime_date" value="{{ old("overtime_date") }}" class="form-control" {{ old("overtime_date") == null ? "disabled" : "" }} disabled placeholder="Input Data">
                    @error('overtime_date')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label fw-bold">Reason</label>
                    <input type="text" name="reason" value="{{ old("reason") }}" readonly class="form-control" placeholder="Automatic Fill">
                    <input type="hidden" name="reason_id" value="{{ old("reason_id") }}" readonly class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="fv-row col-4">
                    <label class="col-form-label fw-bold">Overtime Type</label>
                    <select name="overtime_type" class="form-select" data-control="select2" disabled data-placeholder="Select Overtime Type" data-dropdown-parent="#modal_add_overtime">
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
                <div class="fv-row col-4">
                    <label class="col-form-label fw-bold">Start Date</label>
                    <input type="time" name="start_date" value="{{ old("start_date") }}" class="form-control" id="add-start-date" disabled placeholder="Day - 00:00">
                    @error('start_date')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row col-4">
                    <label class="col-form-label fw-bold">End Date</label>
                    <input type="time" name="end_date" value="{{ old("end_date") }}" class="form-control" id="add-end-date" disabled placeholder="Day - 00:00">
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
                                <input class="form-check-input" disabled name="break_overtime" onclick="add_bs(this)" type="checkbox" value="1" />
                                Tambahkan Break Overtime
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
                                        <label for="" class="col-form-label">Break <span class="break-num"></span> Start</label>
                                        <input type="time" name="start" class="form-control" id="">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="fv-row">
                                        <label for="" class="col-form-label">Break <span class="break-num"></span> End</label>
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
                <div class="fv-row col-6">
                    <label class="col-form-label fw-bold">Paid By</label>
                    <select name="paid_type" class="form-select" data-control="select2" disabled data-placeholder="Select" data-dropdown-parent="#modal_add_overtime">
                        <option value=""></option>
                        <option value="money">Money</option>
                        <option value="days">Days</option>
                        <option value="no paid">No Paid</option>
                    </select>
                </div>
                <div class="fv-row col-4 d-none">
                    <label class="col-form-label fw-bold">&nbsp;</label>
                    <input type="number" name="day" class="form-control" min="1" disabled value="1">
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label fw-bold">Allocation Departement</label>
                    <select name="departement" class="form-select" data-control="select2" disabled data-placeholder="Select Departement" data-dropdown-parent="#modal_add_overtime">
                        <option value=""></option>
                        @foreach ($depts as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="fv-row">
                <label class="col-form-label fw-bold">Reference Number</label>
                <input type="text" name="reference" placeholder="OVT/thnblntgl/no urut" disabled class="form-control">
            </div>
            <div class="fv-row d-flex flex-column mt-5">
                <div class="d-flex align-items-center">
                    <label class="btn btn-secondary ">
                        <input type="file" data-required name="file" accept=".jpg,.png,.pdf" class="d-none">
                        Attachment
                        <i class="fi fi-rr-clip"></i>
                    </label>
                    <span class="text-primary ms-5" data-file></span>
                </div>
                <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
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
                        <input type="hidden" name="type" value="overtime">
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

@component('layouts.components.fab', [
        "fab" => [
            ["label" => "Add Overtime", "url" => "javascript:;", 'toggle' => 'data-bs-toggle="modal" data-bs-target="#modal_add_overtime"'],
        ]
    ])
    @endcomponent

@endsection

@section('view_script')
    <script src="{{ asset("theme/assets/plugins/custom/formrepeater/formrepeater.bundle.js") }}"></script>
    <script>

    KTDrawer.createInstances();
    var drawerElement = document.querySelector("#kt_drawer_detail");
    var drawer = KTDrawer.getInstance(drawerElement);
    var target = document.querySelector("#drawer-content");
    var blockUI = new KTBlockUI(target);

    function batalkan(id, type){
        $("#modalDelete input[name=id]").val(id)
        $("#modalDelete button[name=submit]").val(type)
        $("#modalDelete").modal("show")
    }

    function show_detail(id, tp = null){
        $(target).html("")
        blockUI.block();
        $.ajax({
            url : "{{ route('attendance.overtime.detail') }}/" + id,
            data : {
                act : tp
            },
            type : "get",
            dataType : "json"
        }).then(function(resp){
            blockUI.release();
            $(target).html(resp.view)
            var elements = Array.prototype.slice.call(document.querySelectorAll("[data-bs-stacked-modal]"));

            if (elements && elements.length > 0) {
                elements.forEach((element) => {
                    if (element.getAttribute("data-kt-initialized") === "1") {
                        return;
                    }

                    element.setAttribute("data-kt-initialized", "1");

                    element.addEventListener("click", function(e) {
                        e.preventDefault();

                        const modalEl = document.querySelector(this.getAttribute("data-bs-stacked-modal"));

                        if (modalEl) {
                            const modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        }
                    });
                });
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

        function ftable(me, index){
            $("#table-general").DataTable().row(index).search($(me).val()).draw()
        }

        function fnModal(target){
            $(target + " input[name=file]").change(function(){
                var val = $(this).val().split("\\")

                $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
            })

            $(target + " select[name=emp]").change(function(){
                $(target + " input[name=overtime_date]").prop("disabled", false).trigger("change")
                $(target + " select[name=overtime_type]").trigger("change")
            })

            $(target + " input[name=overtime_date]").change(function(){
                if($(this).val() != ""){
                    $.ajax({
                        url : "{{ route("attendance.overtime.index") }}?a=reason&id="+$(target + " select[name=emp]").val()+"&date=" + $(this).val(),
                        type : "get",
                        dataType : "json",
                    }).then(function(resp){
                        var data = resp.data
                        var day_name =data.day_name
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
                            id : $(target + " select[name=emp]").val(),
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

        $(document).ready(function(){

            repeater_form("#modal_add_overtime")
            fnModal("#modal_add_overtime")

            @if(\Session::has("modal"))
                $("{{ \Session::get("modal") }}").modal("show")
                $("#modal_add_overtime select[name=emp]").trigger("change")
            @endif

            $(".tempusDominus").each(function(){
                var _id = $(this).attr("id")
                var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                    display : {
                        viewMode: "calendar",
                        components: {
                            decades: true,
                            year: true,
                            month: true,
                            date: true,
                            hours: true,
                            minutes: true,
                            seconds: false
                        }
                    },
                    localization: {
                        locale: "id",
                        startOfTheWeek: 1,
                        format: "dd-MM-yyyy HH:mm"
                    }
                });
            })
        })

    </script>
@endsection
