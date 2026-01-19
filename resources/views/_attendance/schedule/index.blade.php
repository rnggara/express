@extends('_attendance.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-50px me-3">
            <div class="symbol-label bg-primary">
                <i class="fi fi-rr-calendar text-white fs-1"></i>
            </div>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-3 fw-bold">Pembuatan Jadwal</span>
            <span class="text-muted">Manajemen jadwal yang efisien secara manual & otomatis.</span>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="p-10 rounded bg-secondary-crm">
                <form action="" method="post" id="form-generate">
                    <div class="fv-row">
                        <label class="col-form-label">Workgroup</label>
                        <select name="workgroup" class="form-select" onchange="checkSchedule()" data-control="select2" data-placeholder="Select Workgroup">
                            <option value=""></option>
                            @foreach ($workgroups as $item)
                                <option value="{{ $item->id }}">{{ $item->workgroup_name }}</option>
                            @endforeach
                        </select>
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty"></div>
                        </div>
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Schedule Periode</label>
                        <select name="periode" class="form-select" onchange="checkSchedule()" data-control="select2" data-placeholder="Select Periode">
                            <option value=""></option>
                            @foreach ($periods as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            <div data-field="text_input" data-validator="notEmpty"></div>
                        </div>
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">From Date</label>
                        <input type="text" name="from_date_label" class="form-control" readonly placeholder="Automatic Fill">
                        <input type="hidden" name="from_date">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">To Date</label>
                        <input type="text" name="to_date_label" class="form-control" readonly placeholder="Automatic Fill">
                        <input type="hidden" name="to_date">
                    </div>
                    <div class="fv-row">
                        <div class="form-check col-form-label">
                            <label class="form-check-label">
                                <input class="form-check-input" name="auto" onclick="otomatisPeriode(this)" type="checkbox" value="1"/>
                                Otomatis Generate Schedule By Period
                            </label>
                        </div>
                    </div>
                    <div class="fv-row d-none">
                        <label class="col-form-label">Automatic Periode</label>
                        <select name="months" class="form-select" data-control="select2" data-placeholder="Select Periode">
                            <option value="2">2 Month</option>
                            <option value="3">3 Month</option>
                            <option value="6">6 Month</option>
                            <option value="9">9 Month</option>
                            <option value="12">12 Month</option>
                        </select>
                    </div>
                    <div class="mt-5 d-flex justify-content-end">
                        @csrf
                        <input type="hidden" name="type">
                        <button type="button" onclick="generateSchedule()" class="btn btn-secondary" form-input disabled>Generate</button>
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
                    <div class="d-flex flex-column mb-5">
                        <span class="fw-bold" data-list-title></span>
                        <span class="text-muted" data-list-subtitle></span>
                    </div>
                    <div class="scroll h-500px">
                        <table class="table table-row-bordered bg-white" id="table-list">
                            <thead>
                                <tr>
                                    <th>Days</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Break 1</th>
                                    <th>Break 2</th>
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
                var day = element.day
                if(day == "Jum'at"){
                    day = "Jumat"
                }
                var td = `<td>${day}</td>`
                    td += `<td>${element.date_label}</td>`
                    td += `<td>${element.time_in}</td>`
                    td += `<td>${element.time_out}</td>`
                    td += `<td>${element.break_1}</td>`
                    td += `<td>${element.break_2}</td>`
                var cls = element.day_off ? "bg-secondary" : ""
                var tr = `<tr class="${cls}">${td}</tr>`
                el += tr
            }

            console.log(el)

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

        $(document).ready(function(){
        })
    </script>
@endsection
