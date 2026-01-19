@extends('_crm.preferences.index')

@section('view_content')
<div class="card shadow-none">
    <div class="card-header border-bottom-0 px-0">
        <div class="d-flex flex-column">
            <h3 class="card-title">Kolom Khusus</h3>
            <span>Buatlah kolom khusus untuk informasi onboarding</span>
        </div>
        <div class="card-toolbar">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_properties">
                <i class="la la-plus"></i>
                Tambah Kolom Khusus
            </button>
        </div>
    </div>
    <div class="card-body rounded bg-secondary-crm">
        <table class="table display bg-white" data-ordering="false" data-page-length="50" id="table-properties">
            <thead>
                <tr>
                    <th>Nama Kolom</th>
                    <th>Tipe Kolom</th>
                    <th>Created By</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($props as $item)
                    <tr class="{{ $item->hide == 1 ? 'text-muted' : "text-dark" }}">
                        <td>{{ $item->property_name }}</td>
                        <td>{{ $properties[$item->property_type] }}</td>
                        <td>{{ $users[$item->created_by] ?? \Config::get("constants.APP_NAME") }}</td>
                        <td>
                            <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 0px">
                                <i class="fa fa-ellipsis-vertical text-dark"></i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="{{ route('crm.pref.crm.properties.change_status', $item->id) }}" class="menu-link px-3">
                                        {{ $item->hide == 1 ? "Show" : "Hide" }}
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                @if (empty($item->table_column))
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="javascript:;" onclick="edit_property({{ $item->id }})" class="menu-link px-3">
                                        Edit
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="javascript:;" onclick="archive(this)" data-url="{{route('crm.pref.crm.properties.archive', $item->id)}}" data-label="{{ $item->property_name }}" class="menu-link px-3 text-danger">
                                        Archive
                                    </a>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="kt_modal_properties">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content px-10">
            <div class="modal-body">
                <div class="d-flex flex-column flex-md-row">
                    <div class="card w-100 w-md-50">
                        <div class="card-header border-0 px-0">
                            <div class="card-title">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-5">
                                            <div class="symbol-label bg-light-primary"><i class="fa fa-notes-medical teaxt-primary text-primary fs-2"></i></div>
                                        </div>
                                        <span class="fs-3 fw-bold">Tambah Kolom Khusus</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('crm.pref.crm.properties.store') }}" method="post">
                            <div class="card-body rounded bg-secondary-crm" id="property-sel-parent">
                                <div class="fv-row">
                                    <label for="property_type" class="col-form-label required">Tipe Kolom</label>
                                    <select name="property_type" id="property_type" class="form-select" required data-allow-clear="true" data-control="select2" data-dropdown-parent="#property-sel-parent" data-placeholder="Chose properties type">
                                        <option value=""></option>
                                        @foreach ($properties as $id => $item)
                                            <option value="{{ $id }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row">
                                    <label for="property_name" class="col-form-label required">Nama Kolom</label>
                                    <input type="text" name="property_name" id="property_name" onchange="change_preview('#kt_modal_properties')" class="form-control" required placeholder="Input property name">
                                </div>
                                <div class="fv-row" style="display: none"  data-section='currency' id="currency-sel-parent">
                                    <label for="currency" class="col-form-label">Currency</label>
                                    <select name="currency" id="currency" onchange="change_preview('#kt_modal_properties')" class="form-select" data-allow-clear="true" data-control="select2" data-dropdown-parent="#currency-sel-parent" data-placeholder="IDR">
                                        <option value=""></option>
                                        <option value="IDR">IDR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                                <div class="fv-row" style="display: none">
                                    <label for="input_placeholder" class="col-form-label">Input Placeholder</label>
                                    <input type="text" name="input_placeholder" id="input_placeholder" onchange="change_preview('#kt_modal_properties')" class="form-control" placeholder="Input placeholder">
                                </div>
                                <div class="fv-row" style="display: none">
                                    <label for="input_placeholder2" class="col-form-label">Input Placeholder 2</label>
                                    <input type="text" name="input_placeholder2" id="input_placeholder2" onchange="change_preview('#kt_modal_properties')" class="form-control" placeholder="Input placeholder 2">
                                </div>
                                <div data-section="properties-additional"></div>
                            </div>
                            <div class="card-footer border-0">
                                <div class="d-flex justify-content-end">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ $type ?? "opportunity" }}">
                                    <input type="hidden" name="layout_id" value="{{ $detail->id ?? null }}">
                                    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="border mx-5 mt-5 d-none d-md-flex"></div>
                    <div class="card w-100 w-md-50 h-100">
                        <div class="card-header border-0 px-0">
                            <h3 class="card-title">Preview</h3>
                        </div>
                        <div class="card-body rounded bg-secondary-crm" data-section="properties-preview">
                            <center>No Preview</center>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-0 modal-footer">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="kt_modal_edit">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content px-10">
            <div class="modal-body">
                <div class="d-flex flex-column flex-md-row">
                    <div class="card w-100 w-md-50">
                        <div class="card-header border-0 px-0">
                            <div class="card-title">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-5">
                                            <div class="symbol-label bg-light-primary"><i class="fa fa-notes-medical teaxt-primary text-primary fs-2"></i></div>
                                        </div>
                                        <span class="fs-3 fw-bold">Edit Property</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('crm.pref.crm.properties.store') }}" method="post">
                            <div class="card-body rounded bg-secondary-crm" data-section="property-sel-parent">

                            </div>
                            <div class="card-footer border-0">
                                <div class="d-flex justify-content-end">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ $type ?? "opportunity" }}">
                                    <input type="hidden" name="layout_id" value="{{ $detail->id ?? null }}">
                                    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="border mx-5 mt-5 d-none d-md-flex"></div>
                    <div class="card w-100 w-md-50 h-100">
                        <div class="card-header border-0 px-0">
                            <h3 class="card-title">Preview</h3>
                        </div>
                        <div class="card-body rounded bg-secondary-crm" data-section="properties-preview">
                            <center>No Preview</center>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-0 modal-footer">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                    <span class="fw-bold fs-3">Are you sure want to archive?</span>
                    <span class="text-center">Are you sure you want to archive <span id="archive-label"></span>?</span>
                    <div class="d-flex align-items-center mt-5">
                        <a href="" id="archive-url" class="btn btn-white">Yes</a>
                        <button class="btn btn-primary" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('view_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.min.js") }}"></script>
    <script>

        function archive(me){
            $("#modalDelete").modal("show")
            $("#modalDelete #archive-url").attr("href", $(me).data("url"))
            $("#modalDelete #archive-label").text($(me).data("label"))
        }

        load_js("#kt_modal_properties")

        function load_js(modal, isEdit = null){
            $(`${modal} select[name=property_type]`).change(function(){
                $(modal + " [data-section='properties-additional']").html("")

                if($(this).val() != ""){
                    $(modal + " input[name=input_placeholder]").parent().show()
                }

                $(modal + " [data-section='currency']").hide()

                if($(this).val() == 14){
                    $(modal + " [data-section='currency']").show()
                }

                $(modal + " input[name=input_placeholder2]").parent().hide()

                if($(this).val() == 18 || $(this).val() == 20){
                    $(modal + " input[name=input_placeholder2]").parent().show()
                }

                if(($(this).val() >= 3 && $(this).val() <= 5) || this.value == 12){
                    var tp = this.value == 12 ? "/scale" : ""

                    if(this.value == 12){
                        $(modal + " input[name=input_placeholder]").parent().hide()
                    }

                    var addUrl = "{{ route('crm.pref.crm.properties.additional') }}" + tp + "?modal=" + modal
                    console.log(addUrl)
                    if(isEdit != null){
                        addUrl += "&id=" + isEdit
                    }

                    addUrl = addUrl.replace("#", '%23')

                    $.ajax({
                        url : encodeURI(addUrl),
                        type : "get"
                    }).then(function(resp){
                        $(modal + " [data-section='properties-additional']").html(resp)
                        change_preview(modal)
                    })
                } else {
                    change_preview(modal)
                }
            })
        }

        function edit_property(id){
            $.ajax({
                url : "{{ route("crm.pref.crm.properties.detail") }}/" + id,
                type : "get",
            }).then(function(resp){
                $("#kt_modal_edit").modal("show")
                $("#kt_modal_edit [data-section='property-sel-parent']").html(resp)
                $("#kt_modal_edit select[data-control=select2]").select2()
                load_js("#kt_modal_edit", id)
                $("#kt_modal_edit select[name=property_type]").trigger("change")
            })
        }

        function add_additional(modal){
            var clone = $(modal + " .additional-option-items")

            var new_option = $(clone[0]).clone()
            $(new_option).find("input").val("")

            $( modal +  " [data-section=additional-option]").append(new_option)
            option_num(modal)
        }

        function remove_additional(me, modal){
            $(me).parents(".additional-option-items").remove()
            option_num(modal)
        }

        function option_num(modal){
            $(modal + " .additional-option-items").each(function(index){
                $(this).find("label").text("Option " + (index + 1))
                $(this).find("input").attr("placeholder", "Option " + (index + 1))
            })

            if($(modal + " .additional-option-items").length == 1){
                $(modal + " .additional-option-items").find("button").hide()
            } else {
                $(modal + " .additional-option-items").find("button").show()
            }
            change_preview(modal)
        }

        function change_preview(modal){
            $(modal + " [data-section='properties-preview']").html("<div class='d-flex justify-content-center'><div class='spinner-border'></div></div>")

            var type = $(modal + " [name=property_type]").val()
            var name = $(modal + " [name=property_name]").val()
            var placeholder = $(modal + " [name=input_placeholder]").val()
            var placeholder2 = $(modal + " [name=input_placeholder2]").val()
            var currency = $(modal + " [name=currency]").val()

            var additional = {}
            var colors = {}
            $(modal + " .additional-option-items").each(function(){
                var input = $(this).find("input[type=text]")
                var name = $(input).attr("name")
                var col = []
                if(additional['option'] !== undefined){
                    col = additional['option']
                }

                col.push($(input).val())

                additional['option'] = col

                var color = $(this).find("input[type=color]")
                var col = []
                if(additional['color'] !== undefined){
                    col = additional['color']
                }

                col.push($(color).val())

                additional['color'] = col
            })

            var _data = {
                type : type,
                name : name,
                placeholder : placeholder,
                placeholder2 : placeholder2,
                additional : additional,
                currency : currency
            }

            $.ajax({
                url : "{{ route("crm.pref.crm.properties.preview") }}",
                type : "get",
                dataType : "json",
                data : _data
            }).then(function(resp){
                $(modal + " [data-section='properties-preview']").html(resp.view)
                $(modal + " [data-section='properties-preview'] [data-control=select2]").each(function(){
                    var width = $(this).data("width") ?? "100%"
                    $(this).select2({
                        width : width
                    })
                })
                $(modal + " [data-section='properties-preview'] input.input-currency").number(true, 2).on("keyup", function(){
                    if($(this).val() != ""){
                        $(this).addClass("ps-13").next().show()
                    } else {
                        $(this).removeClass("ps-13").next().hide()
                    }
                })

                $(modal + " [data-section='properties-preview'] .tempusDominus").each(function(){
                    var _id = $(this).attr("id")
                    var viewMode = $(this).data("view")
                    var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                        display : {
                            inline: true,
                            viewMode: viewMode,
                            components: {
                                decades: viewMode == "calendar" ? true : false,
                                year: viewMode == "calendar" ? true : false,
                                month: viewMode == "calendar" ? true : false,
                                date: viewMode == "calendar" ? true : false,
                                hours: viewMode == "clock" ? true : false,
                                minutes: viewMode == "clock" ? true : false,
                                seconds: viewMode == "clock" ? true : false
                            }
                        },
                        localization: {
                            locale: "id",
                            startOfTheWeek: 1,
                            format: "dd/MM/yyyy"
                        }
                    });
                })
            })
        }

    </script>
@endsection
