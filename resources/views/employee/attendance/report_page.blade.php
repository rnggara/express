@extends('layouts.template')

@section('css')
<style>
    .select2-results__option[aria-disabled=true]
    {
        display: none;
    }
</style>
@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Attendance Report</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('attendance.report_result') }}" method="post" id="form-post" class="form">
                <div class="container">
                    <div class="row">
                        <div class="col-12 mb-5">
                            <div class="fv-row">
                                <label for="" class="col-form-label required">Template Name</label>
                                <input type="text" class="form-control" required name="template_name" value="{{ $template->name ?? "" }}" placeholder="Template Name">
                            </div>
                            <div class="fv-row">
                                <label for="" class="col-form-label">Default From</label>
                                <div class="d-flex">
                                    <div class="input-group me-3">
                                        <input type="text" class="form-control tempusDominus" name="from_date" id="from_date" placeholder="Default From Date" value="{{ empty($template) ? date("d/m/Y") : date("d/m/Y", strtotime($template->from_date)) }}">
                                    </div>
                                    <div class="input-group me-3">
                                        <input type="time" class="form-control" name="from_time" id="from_time" placeholder="Default From Time" value="{{ empty($template) ? date("H:i") : date("H:i", strtotime($template->from_date)) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row">
                                <label for="" class="col-form-label">Default To</label>
                                <div class="d-flex">
                                    <div class="input-group me-3">
                                        <input type="text" class="form-control tempusDominus" name="to_date" id="to_date" placeholder="Default To Date" value="{{ empty($template) ? date("d/m/Y") : date("d/m/Y", strtotime($template->to_date)) }}">
                                    </div>
                                    <div class="input-group me-3">
                                        <input type="time" class="form-control" name="to_time" id="to_time" placeholder="Default To Time" value="{{ empty($template) ? date("H:i") : date("H:i", strtotime($template->to_date)) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row">
                                <label for="" class="col-form-label required">Clock In/Out Type</label>
                                <select name="type" id="tp" class="form-select" required data-control="select2" data-hide-search="true" data-placeholder="Select Type">
                                    <option value=""></option>
                                    <option value="1" {{ !empty($template) && $template->type == "1" ? "SELECTED" : "" }}>Single Line</option>
                                    <option value="2" {{ !empty($template) && $template->type == "2" ? "SELECTED" : "" }}>Separate Line</option>
                                </select>
                            </div>
                            <div id="separate-form" style="display: none">
                                <div class="fv-row">
                                    <label for="" class="col-form-label required">Clock In</label>
                                    <div class="d-flex">
                                        <input type="text" name="cin_val" value="{{ $template->cin_val ?? "" }}" id="" class="form-control me-3" placeholder="Clock In Value">
                                        <input type="number" name="cin_order" value="{{ $template->cin_order ?? "1" }}" min="1" max="2" class="form-control" placeholder="Clock In Order">
                                    </div>
                                </div>
                                <div class="fv-row">
                                    <label for="" class="col-form-label required">Clock Out</label>
                                    <div class="d-flex">
                                        <input type="text" name="cout_val" value="{{ $template->cout_val ?? "" }}" id="" class="form-control me-3" placeholder="Clock Out Value">
                                        <input type="number" name="cout_order" value="{{ $template->cout_order ?? "2" }}" min="1" max="2" class="form-control" placeholder="Clock Out Order">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mx-auto">
                            <table class="table table-bordered table-responsive-xl w-100 display" id="table-kol" data-page-length="100">
                                <thead>
                                    <tr>
                                        <th class="text-center w-400px">Column</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($columns as $item)
                                        <tr>
                                            <td>
                                                <select name="columns[]" data-control="select2" class="form-select">
                                                    <option data-format="text" value="#" {{ $item['column'] == "#" ? "SELECTED" : "" }}>Nomor Urut</option>
                                                    <option data-format="text" value="id" {{ $item['column'] == "id" ? "SELECTED" : "" }}>ID</option>
                                                    <option data-format="text" value="employee_name" {{ $item['column'] == "employee_name" ? "SELECTED" : "" }}>Nama Lengkap</option>
                                                    <option data-format="text" value="company_id" {{ $item['column'] == "company_id" ? "SELECTED" : "" }}>ID Perusahaan</option>
                                                    <option data-format="text" value="company_name" {{ $item['column'] == "company_name" ? "SELECTED" : "" }}>Nama Perusahaan</option>
                                                    {{-- <option data-format="text" value="branch" {{ $item['column'] == "branch" ? "SELECTED" : "" }}>Branch</option> --}}
                                                    {{-- <option data-format="text" value="location_type" {{ $item['column'] == "location_type" ? "SELECTED" : "" }}>Lokasi</option> --}}
                                                    <optgroup class="opt-line opt-single-line">
                                                        <option data-format="datetime" value="check_in" {{ $item['column'] == "check_in" ? "SELECTED" : "" }}>Check in</option>
                                                        <option data-format="datetime" value="check_out" {{ $item['column'] == "check_out" ? "SELECTED" : "" }}>Check out</option>
                                                        <option data-format="datetime" value="break_in" {{ $item['column'] == "break_in" ? "SELECTED" : "" }}>Break in</option>
                                                        <option data-format="datetime" value="break_in" {{ $item['column'] == "break_in" ? "SELECTED" : "" }}>Break out</option>
                                                    </optgroup>
                                                    <optgroup class="opt-line opt-separate-line">
                                                        <option data-format="datetime" value="time" {{ $item['column'] == "time" ? "SELECTED" : "" }}>Time</option>
                                                        <option data-format="text" value="type" {{ $item['column'] == "type" ? "SELECTED" : "" }}>Type</option>
                                                    </optgroup>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="format-columns format-text">
                                                    <input type="text" name="format_selected[]" class="form-control" readonly value="text">
                                                </div>
                                                <div class="format-columns format-datetime" style="display: none">
                                                    <select name="formats[]" data-control="select2" class="form-select">
                                                        <option value="Y-m-d">Y-m-d ({{ date("Y-m-d") }})</option>
                                                        <option value="d-m-Y">d-m-Y ({{ date("d-m-Y") }})</option>
                                                        <option value="Y-m-d H:i:s">Y-m-d H:i:s ({{ date("Y-m-d H:i:s") }})</option>
                                                        <option value="d-m-Y H:i:s">d-m-Y H:i:s ({{ date("d-m-Y H:i:s") }})</option>
                                                        <option value="Y-m-d H:i">Y-m-d H:i ({{ date("Y-m-d H:i") }})</option>
                                                        <option value="d-m-Y H:i">d-m-Y H:i ({{ date("d-m-Y H:i") }})</option>
                                                        <option value="H:i:s">H:i:s ({{ date("H:i:s") }})</option>
                                                        <option value="H:i">H:i ({{ date("H:i") }})</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td align="center" class="text-nowrap">
                                                <button type="button" class="btn btn-icon btn-directions btn-sm btn-up btn-outline btn-outline-primary" onclick="change_order(this, 'prev')" style="display: none">
                                                    <i class="fa fa-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-icon btn-directions btn-sm btn-down btn-outline btn-outline-warning" onclick="change_order(this, 'next')" style="display: none">
                                                    <i class="fa fa-arrow-down"></i>
                                                </button>
                                                <button type="button" class="btn btn-icon btn-sm btn-outline btn-outline-danger" onclick="remove_row(this)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>                                    
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <button type="button" class="btn text-primary" onclick="add_row()">
                                                <i class="fa fa-plus-circle text-primary"></i>
                                                Tambah Row
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-9 mx-auto">
                            <div class="d-flex justify-content-end">
                                @csrf
                                <input type="hidden" name="template_id" value="{{ $template->id ?? null }}">
                                <button type="button" class="btn btn-primary" id="btn_report_submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('custom_script')
    <script>

        function btn_direction(){
            $(".btn-directions").hide()

            $("button.btn-up").each(function(index){
                var _length = $("button.btn-up").length
                if(index > 0){
                    $(this).show()
                }
            })

            $("button.btn-down").each(function(index){
                var _length = $("button.btn-down").length
                if(index < _length && _length > 1 && index < _length - 1){
                    $(this).show()
                }
            })
        }

        function change_order(btn, direction){
            var tr = $(btn).closest("tr")
            console.log(tr, direction)
            if(direction == "next"){
                var target = $(tr).next()
                tr.insertAfter(target)
            } else {
                var target = $(tr).prev()
                tr.insertBefore(target)
            }

            btn_direction()
        }

        function remove_row(btn){
            var tr = $(btn).closest("tr")
            tr.remove()

            btn_direction()
        }

        function add_row(){
            $.ajax({
                url : "{{ route("attendance.report.add") }}?a=row",
                type : "get",
            }).then(function(resp){
                $("#table-kol tbody").append(resp)
                $("select[data-control=select2]").select2()
                $("select[name='columns[]']").change(function(){
                    var opt = $(this).find("option:selected")
                    var tr = $(this).parents("tr")
                    $(tr).find(".format-columns input").attr("name", "formats[]")
                    $(tr).find(".format-columns select").attr("name", "formats[]")
                    $(tr).find(".format-columns").hide()
                    var _format = $(opt).data('format')
                    $(tr).find(".format-"+_format).show()
                    if(_format == "text"){
                        $(tr).find(".format-"+_format).find("input").attr('name', "format_selected[]")
                    } else {
                        $(tr).find(".format-"+_format).find("select").attr('name', "format_selected[]")
                    }
                })

                $(".opt-line").prop('disabled', true)

                var _line = $("#tp").val()
                if(_line == 1){
                    $(".opt-single-line").prop("disabled", false)
                } else if(_line == 2) {
                    $(".opt-separate-line").prop("disabled", false)
                }

                btn_direction()
            })
        }

        var validator = FormValidation.formValidation(document.querySelector("#form-post"),{
            fields: {
                'template_name': {
                    validators: {
                        notEmpty: {
                            message: 'Template name is required'
                        }
                    }
                },
                'type': {
                    validators: {
                        notEmpty: {
                            message: 'Clock in/out type is required'
                        }
                    }
                }
            },

            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row',
                    eleInvalidClass: '',
                    eleValidClass: ''
                })
            }
        });

        function separate_form(v){
            if(v == 2){
                $("#separate-form").show()
                $("#separate-form").find("input").prop("required", true)
                $("#separate-form").find("input").each(function(){
                    var _name = $(this).attr("name")
                    var pl = $(this).attr("placeholder")
                    validator.addField(_name, {
                        validators: {
                        notEmpty: {
                            message: pl + ' is required'
                        }
                    }
                    })
                })
            } else {
                $("#separate-form").hide()
                $("#separate-form").find("input").prop("required", false)
                const fields = validator.getFields()
                if (fields['cin_val'] !== undefined) {
                    $("#separate-form").find("input").each(function(){
                        var _name = $(this).attr("name")
                        validator.removeField(_name)
                    })   
                }
            }
        }

        $(document).ready(function(){
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
                            hours: false,
                            minutes: false,
                            seconds: false
                        }
                    },
                    localization: {
                        locale: "id",
                        startOfTheWeek: 1,
                        format: "dd/MM/yyyy"
                    }
                });
            })

            $("#btn_report_submit").click(function(){
                var tr = $("#table-kol tbody").find("tr")
                if(tr.length == 0){
                    return Swal.fire("Columns required", "Please add at least 1 column", "warning")
                }

                var form = $(this).closest("form")

                validator.validate().then(function(e){
                    if(e == "Valid"){
                        form.submit()
                    }
                })
            })

            separate_form($("#tp").val())

            $("#tp").on('change load', function(){
                var v = $(this).val()
                validator.revalidateField('type');
                $(".opt-line").prop('disabled', true)
                if(v == 1){
                    $(".opt-single-line").prop("disabled", false)
                } else if(v == 2) {
                    $(".opt-separate-line").prop("disabled", false)
                }
                separate_form(v)
            })

            $("select[name='columns[]']").each(function(){
                var opt = $(this).find("option:selected")
                var tr = $(this).parents("tr")
                $(tr).find(".format-columns input").attr("name", "formats[]")
                $(tr).find(".format-columns select").attr("name", "formats[]")
                $(tr).find(".format-columns").hide()
                var _format = $(opt).data('format')
                $(tr).find(".format-"+_format).show()
                if(_format == "text"){
                    $(tr).find(".format-"+_format).find("input").attr('name', "format_selected[]")
                } else {
                    $(tr).find(".format-"+_format).find("select").attr('name', "format_selected[]")
                }
            })

            btn_direction()

            var cin_order = $("input[name=cin_order]")
            var cout_order = $("input[name=cout_order]")

            cin_order.change(function(){
                var v = $(this).val()
                if(v >= 2){
                    $(this).val(2)
                    cout_order.val(1)
                } else {
                    $(this).val(1)
                    cout_order.val(2)
                }
            })

            cout_order.change(function(){
                var v = $(this).val()
                if(v >= 2){
                    $(this).val(2)
                    cin_order.val(1)
                } else {
                    $(this).val(1)
                    cin_order.val(2)
                }
            })
        })
    </script>
@endsection
