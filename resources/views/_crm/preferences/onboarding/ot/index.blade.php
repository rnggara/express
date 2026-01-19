@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Onboarding Template</h3>
                    <div class="d-flex align-items-center mb-5">
                        <span class="text-muted">Setting <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="text-muted">Onboarding <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="fw-semibold">Onboarding Template</span>
                    </div>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary" onclick="addForm()" data-bs-toggle="modal" data-bs-target="#modal_add">
                        Add Onboarding Template
                    </button>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <div class="position-relative">
                            <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                            <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                        </div>
                    </div>
                    <table class="table table-display-2 bg-white" data-ordering="false" id="table-list">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""/>
                                    </div>
                                </th>
                                <th>Onboarding Template</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""/>
                                        </div>
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $uname[$item->created_by] ?? "-" }}</td>
                                    <td>{{ date("d-m-Y", strtotime($item->created_at)) }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" {{ $item->status == 1 ? "checked" : "" }} disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                            <i class="fa fa-ellipsis-vertical text-dark"></i>
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $item->id }}" class="menu-link px-3 text-danger">
                                                    Delete Data
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" data-bs-toggle="modal" onclick="editModal('{{ json_encode($item->forms) }}', {{ $item->id }})" data-bs-target="#modal_edit_{{ $item->id }}" class="menu-link px-3">
                                                    Edit Data
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>

                                        <form action="{{ route("crm.pref.onboarding.ot.post") }}" method="post">
                                            <div class="modal fade" tabindex="-1" id="modal_delete_{{ $item->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                                                                <span class="fw-bold fs-3">Are you sure want to delete?</span>
                                                                <span class="text-center">Please becarefull when deleting, it will cause form database and onboarding template!</span>
                                                                <div class="d-flex align-items-center mt-5">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                                    <button type="submit" name="submit" value="delete" class="btn btn-white">Yes</button>
                                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <form action="{{ route("crm.pref.onboarding.ot.post") }}" method="post">
                                            @component('layouts._crm_modal', [
                                                "modalId" => "modal_edit_$item->id",
                                                "modalSize" => "modal-lg"
                                            ])
                                                @slot('modalTitle')
                                                    <div class="d-flex flex-column">
                                                        <span class="fs-1 fw-bold">Edit Onboarding Template</span>
                                                        <span class="fs-base fw-normal mt-2">&nbsp;&nbsp;</span>
                                                    </div>
                                                @endslot
                                                @slot('modalContent')
                                                    <div class="fv-row">
                                                        <label class="col-form-label required">Template Name</label>
                                                        <input type="text" name="template_name" value="{{ $item->name }}" required placeholder="Input Template Name" class="form-control">
                                                        @error('template_name')
                                                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="fv-row">
                                                        <label class="col-form-label required">Add Form</label>
                                                        <table class="table table-display-2 bg-white" id="form-sel-edit-{{ $item->id }}" data-ordering="false" data-paging="false">
                                                            <thead>
                                                                <tr>
                                                                    <th>Form Type</th>
                                                                    <th>Form Name</th>
                                                                    <th>Due Dates</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="3" class="text-center">
                                                                        <button type="button" class="btn btn-primary" data-bs-stacked-modal="#modal_form">
                                                                            Add Form
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                @endslot
                                                @slot('modalFooter')
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
                                                    <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
                                                @endslot
                                            @endcomponent
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route("crm.pref.onboarding.ot.post") }}" method="post" enctype="multipart/form-data">
        @component('layouts._crm_modal', [
            "modalId" => "modal_edit",
            "modalSize" => "modal-lg"
        ])
            @slot('modalTitle')
                <div class="d-flex flex-column">
                    <span class="fs-1 fw-bold">Edit Form</span>
                    <span class="fs-base fw-normal mt-2">&nbsp;&nbsp;</span>
                </div>
            @endslot
            @slot('modalContent')

            @endslot
            @slot('modalFooter')
                @csrf
                <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
                <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("crm.pref.onboarding.ot.post") }}" method="post">
        @component('layouts._crm_modal', [
            "modalId" => "modal_add",
            "modalSize" => "modal-lg"
        ])
            @slot('modalTitle')
                <div class="d-flex flex-column">
                    <span class="fs-1 fw-bold">Add Onboarding Template</span>
                    <span class="fs-base fw-normal mt-2">&nbsp;&nbsp;</span>
                </div>
            @endslot
            @slot('modalContent')
                <div class="fv-row">
                    <label class="col-form-label required">Template Name</label>
                    <input type="text" name="template_name" value="{{ old("template_name") }}" required placeholder="Input Template Name" class="form-control">
                    @error('template_name')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row">
                    <label class="col-form-label required">Add Form</label>
                    <table class="table table-display-2 bg-white" id="form-sel" data-ordering="false" data-paging="false">
                        <thead>
                            <tr>
                                <th>Form Type</th>
                                <th>Form Name</th>
                                <th>Due Dates</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-center">
                                    <button type="button" class="btn btn-primary" data-bs-stacked-modal="#modal_form">
                                        Add Form
                                    </button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
                <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("crm.pref.onboarding.ot.post") }}" id="form-form" method="post" enctype="multipart/form-data">
        @component('layouts._crm_modal', [
            "modalId" => "modal_form",
            "modalSize" => "modal-lg"
        ])
            @slot('modalTitle')
                <div class="d-flex flex-column">
                    <span class="fs-1 fw-bold">Add Form</span>
                    <span class="fs-base fw-normal mt-2">&nbsp;&nbsp;</span>
                </div>
            @endslot
            @slot('modalContent')
                <ul class="fs-6 mb-5 nav">
                    <li class="nav-item">
                        <div class="nav-link form-check form-check-custom active" data-bs-toggle="tab" href="#kt_tab_pane_0">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="form_type" checked value="from_database"/>
                                From Database
                            </label>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link form-check form-check-custom" data-bs-toggle="tab" href="#kt_tab_pane_1">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="form_type" value="upload_file"/>
                                Upload File
                            </label>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link form-check form-check-custom" data-bs-toggle="tab" href="#kt_tab_pane_2">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="form_type" value="download_file"/>
                                Download File
                            </label>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link form-check form-check-custom" data-bs-toggle="tab" href="#kt_tab_pane_3">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="form_type" value="task"/>
                                Task
                            </label>
                        </div>
                    </li>
                </ul>

                <div class="tab-content mt-5" id="myTabContent">
                    <div class="tab-pane fade show active" id="kt_tab_pane_0" role="tabpanel">
                        <div class="d-flex flex-column">
                            <span class="text-muted my-3">Chosing “From database” means you chose form that you set in the setting or database before.</span>
                            <span class="mt-5 fw-bold required">Form</span>
                            <table class="table bg-white" data-ordering="false" id="table-form">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""/>
                                            </div>
                                        </th>
                                        <th>Form Type</th>
                                        <th>Form Name</th>
                                        <th>Due Dates</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($forms as $item)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="{{ $item->id }}"/>
                                                </div>
                                            </td>
                                            <td>{{ ucwords(str_replace("_", " ", $item->form_type)) }}</td>
                                            <td>{{ $item->form_name }}</td>
                                            <td>{{ $item->due_date }} days</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_1" role="tabpanel">
                        <span class="text-muted my-3">Chosing “Upload file” means you ask for candidate to upload specific files you needed for hiring process</span>
                        <div class="fv-row">
                            <label class="required col-form-label">Document Name</label>
                            <input type="text" name="upload[document]" class="form-control" placeholder="Input Document Name" required>
                        </div>
                        <div class="fv-row">
                            <label class="col-form-label">Description</label>
                            <textarea name="upload[description]" class="form-control" cols="30" rows="5" placeholder="Input Description"></textarea>
                        </div>
                        <div class="fv-row">
                            <label class="col-form-label">File Format</label>
                            <select name="upload[file_format]" class="form-select" data-control="select2" data-dropdown-parent="#kt_tab_pane_1" data-placeholder="Select File Format">
                                <option value=""></option>
                                <option value=".pdf, .jpg, .jpeg, .png">PDF, JPG, JPEG, PNG</option>
                                <option value=".docx, .xlsx, .csv, .pptx">DOC, XLS, CSV, PPT</option>
                            </select>
                        </div>
                        <div class="fv-row">
                            <label class="col-form-label w-100">Add Example File</label>
                            <span class="text-muted">Upload example file for this document, so the user can use it for guidance to fill the document you asked. Or if you need a signature from the candidate, you can upload the document here.</span>
                            <div class="d-flex align-items-center">
                                <label class="btn btn-secondary">
                                    <input type="file" name="upload[attachment]" data-attachment class="d-none">
                                    Attachment
                                    <i class="fi fi-rr-clip"></i>
                                </label>
                                <div class="ms-5 text-primary" data-file>
                                </div>
                            </div>
                            <span class="text-muted mt-3">File format PDF, JPG, PNG, PPT, xls, doc, pptx File Format</span>
                        </div>
                        <div class="fv-row">
                            <label class="col-form-label">Due Date (days)</label>
                            <input type="number" name="upload[due_date]" class="form-control" placeholder="Input Day. Ex: 14">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                        <span class="text-muted my-3">Chosing “Downloadable file” means you ask for candidate to download files that you upload for the information on onboarding process.</span>
                        <div class="fv-row">
                            <label class="required col-form-label">Document Name</label>
                            <input type="text" name="download[document]" class="form-control" placeholder="Input Document Name">
                        </div>
                        <div class="fv-row">
                            <label class="col-form-label">Description</label>
                            <textarea name="download[description]" class="form-control" cols="30" rows="5" placeholder="Input Description"></textarea>
                        </div>
                        <div class="fv-row d-flex align-items-center mt-5">
                            <div class="nav-link form-check form-check-custom active">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="download[type]" checked value="file"/>
                                    File
                                </label>
                            </div>
                            <div class="nav-link form-check form-check-custom active">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="download[type]" value="link"/>
                                    Link
                                </label>
                            </div>
                        </div>
                        <div class="fv-row" data-download-type="file">
                            <label class="col-form-label w-100">File *</label>
                            <span class="text-muted">Upload example file for this document, so the user can use it for guidance to fill the document you asked. Or if you need a signature from the candidate, you can upload the document here.</span>
                            <div class="d-flex align-items-center">
                                <label class="btn btn-secondary">
                                    <input type="file" name="download[attachment]" data-attachment class="d-none">
                                    Attachment
                                    <i class="fi fi-rr-clip"></i>
                                </label>
                                <div class="ms-5 text-primary" data-file>
                                </div>
                            </div>
                            <span class="text-muted mt-3">File format PDF, JPG, PNG, PPT, xls, doc, pptx File Format</span>
                        </div>
                        <div class="fv-row d-none" data-download-type="link">
                            <label class="col-form-label w-100">Link *</label>
                            <input type="text" name="download[link]" class="form-control" placeholder="Input Link">
                        </div>
                        <div class="fv-row">
                            <label class="col-form-label">Due Date (days)</label>
                            <input type="number" name="download[due_date]" class="form-control" placeholder="Input Day. Ex: 14">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                        <span class="text-muted my-3">Chosing “Task” means you can give task to the employee or candidate.</span>
                        <div data-content>
                            <div class="fv-row">
                                <label class="required col-form-label">Task Name</label>
                                <input type="text" name="task[document][]" class="form-control" placeholder="Input Document Name">
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label">Description</label>
                                <textarea name="task[description][]" class="form-control" cols="30" rows="5" placeholder="Input Description"></textarea>
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label">PIC</label>
                                <select name="task[pic][]" class="form-select" data-control="select2" data-dropdown-parent="#kt_tab_pane_3" data-placeholder="Select PIC">
                                    <option value=""></option>
                                    @foreach ($pic as $item)
                                        @if ($item->name != "")
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label">Due Date (days)</label>
                                <input type="number" name="task[due_date][]" class="form-control" placeholder="Input Day. Ex: 14">
                            </div>
                            <div class="separator my-3"></div>
                        </div>
                        <div data-content-add></div>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn text-primary" onclick="cloneTask(this)">
                                <i class="fa fa-plus text-primary"></i>
                                Add Task
                            </button>
                        </div>
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
                <button type="button" name="submit" value="store" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>
@endsection

@section('view_script')
<script>

    var tb_loc

    function editModal(forms, id){
        var fname = @JSON($fname)

        var ftype = @JSON($ftype)

        var fdd = @JSON($fdue_date)

        tb_loc.rows().deselect()

        for (let i = 0; i < forms.length; i++) {
            const f = forms[i];
            if(fname[f] != undefined){
                var col = {}
                col['id'] = f
                col['type'] = ftype[f].replaceAll("_", " ")
                col['name'] = fname[f]
                col['due_date'] = fdd[f] + " days"
                form_sel.push(col)

                renderForms("#form-sel-edit-" + id)
            }
        }

        var rows = tb_loc.rows().data()
        for (let i = 0; i < rows.length; i++) {
            const element = rows[i];
            var dom_nodes = $($.parseHTML(element[0]));
            var ck = $(dom_nodes).find("input[type=checkbox]").val()
            if(forms.includes(ck)){
                tb_loc.row(i).select()
            }
        }

        tableFn("#table-form", tb_loc)

        modalForm("#form-sel-edit-"+id)


    }

    function editForm(id){
        $.ajax({
            url : "{{ route("crm.pref.onboarding.fd.index") }}?a=edit",
            type : "get",
            data : {
                id : id
            },
            dataType : "json"
        }).then(function(resp){
            $("#modal_edit .modal-body .card-body").html(resp.view)
            $("#modal_edit").modal("show")
            $("#modal_edit select[data-control=select2]").select2()

            $("#modal_edit input[name='download[type]']").click(function(){
                var form = $(this).parents("form")
                if($(this).val() == "file"){
                    $(form).find('[data-download-type="file"]').removeClass("d-none")
                    $(form).find('[data-download-type="link"]').addClass("d-none")
                } else {
                    $(form).find('[data-download-type="file"]').addClass("d-none")
                    $(form).find('[data-download-type="link"]').removeClass("d-none")
                }
            })

            $('#modal_edit [data-bs-toggle="tab"]').click(function(){
                var target = $(this).attr("href")
                var form = $(this).parents("form")
                $(form).find("input:required").prop("required", false)
                $(target).find("label.required").parents(".fv-row").find("input").prop("required", true)
            })

            $("#modal_edit select[name='upload[file_format]']").change(function(){
                var par = $(this).parents("div.tab-pane")
                $(par).find("input[name='upload[attachment]']").attr("accept", $(this).val())
            })

            $("#modal_edit input[data-attachment]").change(function(){
                var val = $(this).val().split("\\")

                $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
            })
        })
    }

    function cloneTask(me){
        var content = $(me).parents(".modal").find("[data-content]")
        var clone = $(content).clone()
        $(clone).find("input, select").val("").trigger("change")
        $(clone).find(".select2-container").remove()
        $(clone).find("select[data-control=select2]").select2()
        $("[data-content-add]").append(clone)
    }

    $("input[data-attachment]").change(function(){
        var val = $(this).val().split("\\")

        $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
    })

    function tableFn(id, table){
        $(`${id} tbody tr td:first-child`).off("click")
        $(`${id} tbody tr td:first-child`).click(function(){
            var tr = $(this).parents("tr")
            if($(tr).hasClass("selected")){
                table.row(tr).deselect()
                $(this).find(".form-check-input").prop('checked', false)
            } else {
                table.row(tr).select()
                $(this).find(".form-check-input").prop('checked', true)
            }

            var all_row = table.rows()[0].length
            var selected_row = table.rows({selected: true})[0].length
            if(selected_row != all_row){
                $(`${id} thead input[type=checkbox]`).prop("checked", false)
            }
        })

        $(`${id} thead input[type=checkbox]`).click(function(){
            var checked = this.checked
            if(checked){
                table.rows().select()
                $(`${id} tbody`).find(".form-check-input").prop('checked', true)
            } else {
                table.rows().deselect()
                $(`${id} tbody`).find(".form-check-input").prop('checked', false)
            }
        })

        $(`${id} tbody tr`).each(function(){
            if($(this).hasClass("selected")){
                $(this).find(".form-check-input").prop('checked', true)
            } else {
                $(this).find(".form-check-input").prop('checked', false)
            }
        })
    }

    var form_sel = []

    function renderForms(target){
        $(target).DataTable().clear()
        for (let i = 0; i < form_sel.length; i++) {
            const element = form_sel[i];

            var td = `<span>${element.type}</span>` +
                `<input type="hidden" name="id_forms[]" value="${element.id}">`

            $(target).DataTable().row.add([
                td,
                element.name,
                element.due_date
            ]).draw()

        }
    }

    function addForm(){
        modalForm("#form-sel")
    }

    function modalForm(target){
        $("#modal_form button[name=submit]").off("click")
        $("#modal_form button[name=submit]").click(function(){
            var ftype = $("#modal_form input[name=form_type]:checked").val()

            if(ftype == "from_database"){
                var selected = tb_loc.rows({selected: true})
                var data = selected.data()
                var count = data.length
                var forms = []
                var form = $(this).parents("form")
                for (let i = 0; i < data.length; i++) {
                    const element = data[i];
                    var dom_nodes = $($.parseHTML(element[0]));
                    var ck = $(dom_nodes).find("input[type=checkbox]").val()
                    var col = {}
                    col['id'] = ck
                    col['type'] = element[1]
                    col['name'] = element[2]
                    col['due_date'] = element[3]
                    forms.push(col)
                }

                form_sel = forms

                renderForms(target)

                $("#modal_form").modal("hide")
            } else {
                $.ajax({
                    url : "{{ route("crm.pref.onboarding.fd.post") }}",
                    type : "post",
                    dataType : "json",
                    data : new FormData($("#form-form")[0]),
                    contentType: false,
                    processData: false,
                }).then(function(resp){
                    var data = resp.data
                    for (let i = 0; i < data.length; i++) {
                        const element = data[i];
                        var col = {}
                        col['id'] = element.id
                        col['type'] = element.form_type.replaceAll("_", " ")
                        col['name'] = element.form_name
                        col['due_date'] = element.due_date + " days"
                        form_sel.push(col)

                        renderForms(target)

                        $("#modal_form").modal("hide")
                    }
                })
            }
        })
    }

    $(document).ready(function(){
        @if(Session::has("modal"))
            $("#{{ Session::get("modal") }}").modal("show")
        @endif

        tb_loc = initTable($("#table-form"))

        tb_loc.select();

        tableFn("#table-form", tb_loc)

        tb_loc.on("init draw", function(){
            tableFn("#table-dept", tb_dept)
        })

        $("input[name='download[type]']").click(function(){
            var form = $(this).parents("form")
            if($(this).val() == "file"){
                $(form).find('[data-download-type="file"]').removeClass("d-none")
                $(form).find('[data-download-type="link"]').addClass("d-none")
            } else {
                $(form).find('[data-download-type="file"]').addClass("d-none")
                $(form).find('[data-download-type="link"]').removeClass("d-none")
            }
        })

        $('[data-bs-toggle="tab"]').click(function(){
            var target = $(this).attr("href")
            var form = $(this).parents("form")
            $(form).find("input:required").prop("required", false)
            $(target).find("label.required").parents(".fv-row").find("input").prop("required", true)
        })

        $("select[name='upload[file_format]']").change(function(){
            var par = $(this).parents("div.tab-pane")
            $(par).find("input[name='upload[attachment]']").attr("accept", $(this).val())
        })
    })
</script>
@endsection
