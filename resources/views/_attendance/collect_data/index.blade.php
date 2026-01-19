@extends('_attendance.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-50px me-3">
            <div class="symbol-label bg-primary">
                <i class="fi fi-rr-layer-plus text-white fs-1"></i>
            </div>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-3 fw-bold">Pengambilan Data</span>
            <span class="text-muted">Managemen unggah data machine attendance</span>
        </div>
    </div>
    <div class="d-flex flex-column">
        <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
            <li class="nav-item">
                <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_collect">
                    <span class="nav-text">Collect & Daily Process</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_history">
                    <span class="nav-text">History</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tab_collect" role="tabpanel">
            <div class="row">
                <div class="col-3">
                    <div class="p-10 rounded bg-secondary-crm">
                        <form action="{{ route('attendance.collect_data.process') }}" method="post" id="form-generate" enctype="multipart/form-data">
                            <div class="rounded p-5 d-flex justify-content-between mb-5" style="background-color: #EBEBEB">
                                <div class="d-flex flex-column">
                                    <span>Ambil data terakhir</span>
                                    <span class="fw-bold">{{ $last_daily_process ?? "N/A" }}</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span>Proses terakhir</span>
                                    <span class="fw-bold">{{ $last_collect_data ?? "N/A" }}</span>
                                </div>
                            </div>
                            <div class="rounded bg-white p-5 mb-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="reproses" value="1" />
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">Reproses Collect Data</span>
                                            <span>Proses ini memungkinkan untuk menumpuk data yang sudah pernah diinput dengan data terbaru</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label">Machine Name</label>
                                <select name="machine_name" data-required class="form-select" data-control="select2" data-placeholder="Select Machine" id="">
                                    <option value=""></option>
                                    @foreach ($machines as $item)
                                        <option value="{{ $item->id }}" {{ old("machine_name") == $item->id ? "SELECTED" : "" }} data-format="{{ $item->program->program_type ?? "txt" }}">{{ $item->machine_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label text-muted">Start Date</label>
                                {{-- <input type="date" data-required name="start_date" value="{{ old("start_date") ?? date("Y-m-d") }}" min="{{ $last->end_date ?? "" }}" class="form-control"> --}}
                                <input type="date" data-required name="start_date" value="{{ old("start_date") ?? date("Y-m-d") }}" class="form-control">
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                            @error('start_date')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="fv-row">
                                <label class="col-form-label text-muted">End Date</label>
                                <input type="date" data-required name="end_date" value="{{ old("end_date") }}" min="{{ date("Y-m-d") }}" class="form-control">
                            </div>
                            <div class="col-12 fv-row d-flex flex-column mt-5">
                                <div class="d-flex align-items-center">
                                    <label class="btn btn-secondary">
                                        <input type="file" data-required name="file" class="d-none">
                                        Attachment
                                        <i class="fi fi-rr-clip"></i>
                                    </label>
                                    <span class="text-primary ms-5" data-file></span>
                                </div>
                                <span class="text-muted mt-3">File Format : <span data-file-format></span> Max 25 mb</span>
                            </div>
                            <div class="mt-5 d-flex justify-content-end">
                                @csrf
                                <input type="hidden" name="type">
                                <button type="submit" class="btn btn-secondary" form-input disabled>Process</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-9" id="show-schedule">
                    <div class="p-10 rounded bg-secondary-crm">
                        <div class="bg-white d-flex flex-column align-items-center p-10" data-list-empty>
                            <span class="fi fi-rr-document fs-1 text-muted"></span>
                            <span class="text-muted">No data available at the moment. Begin by adding your first data entry.</span>
                        </div>
                        <div class="d-flex flex-column d-none" data-list-exist>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="tab_history" role="tabpanel">
            <table class='table table-display-2' id='table-history'>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Machine</th>
                        <th>Range Process</th>
                        <th>Total Data</th>
                        <th>Collect By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $item)
                        @php
                            $total_data = 0;
                            $detail = $item->att_data;
                            foreach ($detail as $key => $value) {
                                foreach($value as $_val){
                                    $valid = collect($_val)->where("valid", 1)->count();
                                    $total_data += $valid >= 1 ? 1 : 0;
                                }
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ date("d F Y", strtotime($item->created_at)) }}</span>
                                    <span>{{ date("H:i", strtotime($item->created_at)) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('attendance.collect_data.history', $item->id) }}" class="fw-bold text-dark">{{ $item->machine->machine_name ?? "-" }}</a>
                                    <span>{{ $item->machine->program->program_name ?? "-" }}</span>
                                </div>
                            </td>
                            <td>
                                {{ date("d F Y", strtotime($item->start_date)) }} - {{ date("d F Y", strtotime($item->end_date)) }}
                            </td>
                            <td>
                                {{ $total_data }} Data
                            </td>
                            <td>
                                {{ $user_name[$item->created_by] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('view_script')
    <script>
        function otomatisPeriode(me){
            var form = $(me).parents("form")
            var ck = $(me).prop('checked')
            var selMonth = $(form).find("select[name=months]")

            $(selMonth).parents(".fv-row").addClass("d-none")

            if(ck){
                $(selMonth).parents(".fv-row").removeClass("d-none")
            }
        }

        var target = document.querySelector("#show-schedule");
        var blockUI = new KTBlockUI(target);

        function checkSchedule(){
            var workgroup = $("select[name=workgroup]")
            var periode = $("select[name=periode]")
            var from = $("input[name=from_date]")
            var from_label = $("input[name=from_date_label]")
            var to = $("input[name=to_date]")
            var to_label = $("input[name=to_date_label]")
            var type = $("input[name=type]")
            var btn = $(periode).parents("form").find("button[form-input]")
            $(periode).parent().find('[data-validator="notEmpty"]').text("")
            $(periode).parent().find(".select2-selection--single").removeClass("border-danger")
            btn.prop("disabled", true)
            btn.removeClass("btn-primary")
            btn.addClass("btn-secondary")

            if($(workgroup).val() != "" && $(periode).val() != ""){
                blockUI.block();
                $.ajax({
                    url : "{{ route("attendance.schedule.index") }}?a=schedule&w=" + $(workgroup).val() + "&p=" + $(periode).val(),
                    type : "get",
                    dataType : "json",
                }).then(function(resp){
                    blockUI.release();
                    if(!resp.success){
                        $(periode).parent().find('[data-validator="notEmpty"]').text(resp.message)
                        $(periode).parent().find(".select2-selection--single").addClass("border-danger")
                    } else {
                        var data = resp.data
                        $(from_label).val(data.from_label)
                        $(from).val(data.from)
                        $(to_label).val(data.to_label)
                        $(to).val(data.to)
                        btn.prop("disabled", false)
                        btn.removeClass("btn-secondary")
                        btn.addClass("btn-primary")
                        btn.text("Generate")

                        $("[data-list-title]").text(data.wg)
                        $("[data-list-subtitle]").text(data.pr)

                        $("[data-list-exist]").addClass("d-none")
                        $("[data-list-empty]").removeClass("d-none")
                        $(type).val("new")
                        var list = data.list
                        if(list.length > 0){
                            addRowTable(data.list)
                            btn.text("Re-Generate")
                            $("[data-list-empty]").addClass("d-none")
                            $("[data-list-exist]").removeClass("d-none")
                            $(type).val("regenerate")
                        }
                    }
                })
            }
        }

        function addRowTable(data){
            var el = ``;
            for (let i = 0; i < data.length; i++) {
                const element = data[i];
                var td = `<td>${element.day}</td>`
                    td += `<td>${element.date_label}</td>`
                    td += `<td>${element.time_in}</td>`
                    td += `<td>${element.time_out}</td>`
                    td += `<td>${element.break_1}</td>`
                    td += `<td>${element.break_2}</td>`
                var cls = element.day_off ? "bg-secondary" : ""
                var tr = `<tr class="${cls}">${td}</tr>`
                el += tr
            }

            $("#table-list tbody").html(el)
        }

        function generateSchedule(){
            var data = $("#form-generate").serialize()
            blockUI.block();
            $.ajax({
                url : "{{ route("attendance.schedule.generate") }}",
                type : "POST",
                dataType : "json",
                data : data
            }).then(function(resp){
                blockUI.release();
                if(!resp.success){
                    return Swal.fire("Error", resp.message, 'error')
                } else {
                    var item = resp.data
                    addRowTable(item)
                    var btn = $("button[form-input]")
                    $("[data-list-exist]").addClass("d-none")
                    btn.text("Re-Generate")
                    $("[data-list-empty]").addClass("d-none")
                    $("[data-list-exist]").removeClass("d-none")
                    showToast("Successfully Generate Schedule", "bg-success")
                }
            })
        }

        var reproses = false
        var dataExist = false

        function enable_process(form){
            var val = []
            $(form).find("select[data-required], input[data-required]").each(function(){
                if($(this).val() != "" && $(this).val() != undefined){
                    val.push($(this).val())
                }
            })

            var enabled = false
            if(val.length == $(form).find("select[data-required], input[data-required]").length){
                enabled = true
            }

            $(form).find("button[form-input]").addClass("btn-secondary")
            $(form).find("button[form-input]").removeClass("btn-primary")

            $("#form-generate input[name=start_date]").parent().find(".invalid-feedback").text("")

            // if(dataExist && !reproses){
            //     $("#form-generate").find("button[form-input]").prop("disabled", !reproses)
            //     if(!reproses){
            //         enabled = false
            //         $("#form-generate input[name=start_date]").parent().find(".invalid-feedback").text("Maaf, Mohon Perhatikan Tanggal Last Collect Data")
            //     }
            // } else {
            //     if(enabled){
            //         $(form).find("button[form-input]").removeClass("btn-secondary")
            //         $(form).find("button[form-input]").addClass("btn-primary")
            //     }
            // }

            if(enabled){
                $(form).find("button[form-input]").removeClass("btn-secondary")
                $(form).find("button[form-input]").addClass("btn-primary")
            }

            $(form).find("button[form-input]").prop("disabled", !enabled)

            return enabled
        }

        async function view_data(){
            var machine = $("#form-generate select[name=machine_name]").val()
            var start_date = $("#form-generate input[name=start_date]").val()
            var end_date = $("#form-generate input[name=end_date]").val()
            $("[data-list-exist]").removeClass("d-none")
            $("[data-list-empty]").removeClass("d-none")
            $("[data-list-exist]").addClass("d-none")
            $("[data-list-exist]").html("")
            return $.ajax({
                url : "{{ route("attendance.collect_data.index") }}",
                type : "get",
                data : {
                    a : "view",
                    machine : machine,
                    start_date : start_date,
                    end_date : end_date,
                    reproses : $("input[name=reproses]:checked").val()
                },
                dataType : "json"
            }).then(function(resp){
                if(resp.success){
                    dataExist = true
                    $("[data-list-exist]").addClass("d-none")
                    $("[data-list-empty]").addClass("d-none")
                    $("[data-list-exist]").removeClass("d-none")
                    $("[data-list-exist]").html(resp.view)
                    initTable($("#table-list"))
                    reproses = resp.reproses == 1 ? true : false
                } else {
                    dataExist = false
                    reproses = false
                }
            })
        }

        function form_collect(){
            $("#form-generate input[name=start_date]").change(async function(){
                $("#form-generate input[name=end_date]").attr("min", $(this).val())
                var end_date = $("#form-generate input[name=end_date]").val()
                if($(this).val() > end_date){
                    $("#form-generate input[name=end_date]").val($(this).val())
                }
                await view_data()
                enable_process("#form-generate")
            })

            $("#form-generate input[name=reproses]").click(async function(){
                var ck = $(this).prop("checked")
                var last_date = "{{ $last->end_date ?? "" }}"
                // $("#form-generate input[name=start_date]").attr("min", last_date)
                $("#form-generate input[name=start_date]").attr("max", "")
                $("#form-generate input[name=end_date]").attr("max", "")
                if(ck){
                    // $("#form-generate input[name=start_date]").attr("min", "")
                    $("#form-generate input[name=start_date]").attr("max", last_date)
                    $("#form-generate input[name=end_date]").attr("max", last_date)
                }
                await view_data()
                enable_process("#form-generate")
            })

            $("#form-generate input[name=end_date]").change(async function(){
                await view_data()
                enable_process("#form-generate")
            })

            $("#form-generate select[name=machine_name]").change(async function(){
                var opt = $(this).find("option:selected")

                var tp = $(opt).data('format')
                $("#form-generate [data-file-format]").text(tp.toUpperCase() + ",")
                $("#form-generate input[name=file]").attr("accept", "." + tp)
                $("#form-generate input[name=end_date]").prop("readonly", false)
                $("#form-generate input[name=end_date]").prev().removeClass("text-muted")
                await view_data()
                enable_process("#form-generate")
            })

            $("#form-generate input[name=file]").change(function(){
                var val = $(this).val().split("\\")

                $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
                enable_process("#form-generate")
            })

            @if(Session::has("trigger"))
                $("{{Session::get('trigger')}}").trigger("change")
                $("#form-generate input[name=end_date]").attr("min", $("#form-generate input[name=start_date]").val())
            @endif
        }

        $(document).ready(function(){
            form_collect()
            @if(Session::has("tab"))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ Session::get("tab") }}']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif
        })
    </script>
@endsection
