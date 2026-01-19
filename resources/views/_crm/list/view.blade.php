@extends('layouts.templateCrm', ['withoutFooter' => true])

@section('content')
    <div class="d-flex flex-column mb-8">
        <div class="d-flex align-items-center justify-content-between p-5 bg-white">
            <div class="d-flex align-items-center">
                <a href="{{ route("crm.list.index")."?t=$v" }}" class="btn btn-link">
                    <i class="fa fa-chevron-left fw-bold fs-3 me-5"></i>
                </a>
                {{ $detail->label }}
            </div>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-primary me-5" id="btn-simpan">Simpan</button>
                @if (!empty($detail))
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete">Hapus</button>
                @else
                <button type="button" class="btn btn-danger" disabled>Hapus</button>
                @endif
            </div>
        </div>
        <div class="d-flex">
            <div class="min-w-500px bg-white">
                <form class="form p-3" method="post" action="{{ $form }}" id="form-lead">
                    @csrf
                    @if (!empty($detail))
                        <input type="hidden" name="id" value="{{ $detail->id }}">
                    @endif
                    @if ($v == "perusahaan")
                        @include('_crm.list._edit_perusahaan')
                    @else
                        @include('_crm.list._edit_kontak')
                    @endif
                </form>
            </div>
            <div class="flex-fill">
                <div class="p-10" style="background-color: var(--bs-page-bg)">
                    <div class="row d-flex flex-column bg-white {{ empty($detail) ? 'blockui' : '' }}" id="div-activity">
                        <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab_notes">
                                    <span class="nav-icon">
                                        <i class="fa fa-note-sticky"></i>
                                    </span>
                                    <span class="nav-text">Notes</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab_files">
                                    <span class="nav-icon">
                                        <i class="fa fa-file"></i>
                                    </span>
                                    <span class="nav-text">Files</span>
                                </a>
                            </li>
                        </ul>
                        <div class="separator separator-solid"></div>

                        <div class="tab-content" id="myTabContent" style="padding: 0">
                            <div class="tab-pane fade show active" id="tab_notes" role="tabpanel">
                                <form action="{{ route('crm.list.add_notes', $v) }}" method="post" enctype="multipart/form-data">
                                    <div class="border">
                                        <div class="fv-row">
                                            <input type="text" placeholder="Add person" name="persons"
                                                id="notes_persons" class="form-control border-0">
                                        </div>
                                        <div class="separator separator-solid"></div>
                                        <div class="fv-row">
                                            <textarea name="notes" id="notes" class="form-controll ck-editor" placeholder="Masukan notes disini"
                                                cols="30" rows="10"></textarea>
                                        </div>
                                        <div class="separator separator-solid"></div>
                                        <div class="fv-row p-5 upload-file">
                                            <label for="notes-file" data-toggle="upload_file"
                                                class="btn btn-outline btn-outline-primary btn-sm">
                                                <i class="fa fa-file"></i>
                                                Add File
                                            </label>
                                            <span class="upload-file-label">Max 25 mb</span>
                                            <input id="notes-file" style="display: none" data-toggle="upload_file"
                                                name="attachment_notes" accept=".jpg, .png, .pdf" type="file" />
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end pt-5" style="background-color: var(--bs-page-bg)">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="tab_files" role="tabpanel">
                                <form action="{{ route('crm.list.add_files', $v) }}" method="post" enctype="multipart/form-data">
                                    <div class="border">
                                        <div class="fv-row p-5 upload-file">
                                            <label for="lead-file" data-toggle="upload_file"
                                                class="btn btn-outline btn-outline-primary btn-sm">
                                                <i class="fa fa-file"></i>
                                                Add File
                                            </label>
                                            <span class="upload-file-label">Max 25 mb</span>
                                            <input id="lead-file" style="display: none" data-toggle="upload_file"
                                                name="attachment_notes" accept=".jpg, .png, .pdf" type="file" />
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end pt-5" style="background-color: var(--bs-page-bg)">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty($detail)
                            <div class="blockui-overlay " style="z-index: 1;"></div>
                        @endempty
                        @if (!empty($detail))
                        <div class="py-5" style="background-color: var(--bs-page-bg)">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="timeline-3">
                                        @foreach ($activity as $item)
                                            <li>
                                                <div class="d-flex flex-column">
                                                    <span class="mb-5">
                                                        <span class="fw-bold">{{ $item['user'] }}</span>
                                                        @if ($item['type'] == 'create')
                                                            membuat {{ $v == "kontak" ? "kontak" : "" }} <span class="fw-bold">{{ $detail->label }}</span>
                                                        @elseif($item['type'] == 'task')
                                                            membuat task di <span class="fw-bold">{{ $detail->label }}</span>
                                                        @elseif($item['type'] == 'notes')
                                                            membuat note di <span class="fw-bold">{{ $detail->label }}</span>
                                                        @elseif($item['type'] == 'files')
                                                            mengupload file di <span
                                                                class="fw-bold">{{ $detail->label }}</span>
                                                        @endif
                                                    </span>
                                                    <ol class="breadcrumb breadcrumb-dot text-muted fs-6 fw-semibold">
                                                        <li class="breadcrumb-item"><span>@dayId($item['date']), {{ date('d/m/Y H:i', strtotime($item['item']['created_at'])) }}</span></li>
                                                        @if (in_array($item['type'], ["notes", "task"]))
                                                            @if (!empty($item['item']['persons']))
                                                            <li class="breadcrumb-item">
                                                                @foreach (json_decode($item['item']['persons'], true) as $i => $pr)
                                                                    {{ $pr['value'] }}@if ($i < count(json_decode($item['item']['persons'], true)) - 1),@endif
                                                                @endforeach
                                                            </li>
                                                            @endif
                                                            @if ($item['type'] == "task")
                                                            <li class="breadcrumb-item">
                                                                <span class="text-danger">Deadline: {{ date("d/m/Y", strtotime($item['item']['due_date'])) }} | {{ date("H:i", strtotime($item['item']['due_date'])) }}</span>
                                                            </li>
                                                            @endif
                                                        @endif
                                                    </ol>
                                                        @if ($item['type'] != "create")
                                                        <div class="border bg-white rounded p-3">
                                                            @if ($item['type'] != "files")
                                                            <div class="d-flex flex-column">
                                                                <span>{!! $item['item']['descriptions'] !!}</span>
                                                            </div>
                                                            @endif
                                                            @if (!empty($item['item']['file_name']))
                                                                @if ($item['type'] != "files")
                                                                <div class="separator separator-solid mb-3"></div>
                                                                @endif
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <i class="fa fa-file-pdf me-3 text-primary fs-2"></i>
                                                                    <a href="{{ asset($item['item']['file_address']) }}" class="btn btn-link text-primary">{{ $item['item']['file_name'] }}</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" tabindex="-1" id="modalDelete">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-info-circle text-danger fs-2 me-3"></i>
                                                <span class="fw-bold fs-2">Hapus {{ $v }}</span>
                                            </div>
                                            <span>Apakah kamu yakin akan menghapus {{ $v }} {{ $detail->label }}?</span>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <a href="{{ $delRoute }}" class="btn btn-danger">Hapus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <link href="{{ asset('theme/jquery-ui/jquery-ui.css') }}" rel="Stylesheet">
    <script src="{{ asset('theme/jquery-ui/jquery-ui.js') }}"></script>
    <script>
        function init_tag(tag, tags) {
            var tagify = new Tagify(document.querySelector(`#${tag}`), {
                whitelist: tags,
                dropdown: {
                    maxItems: 20, // <- mixumum allowed rendered suggestions
                    classname: "", // <- custom classname for this dropdown, so it could be targeted
                    enabled: 0, // <- show suggestions on focus
                    closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
                },
                callbacks: {
                    "change": function(e) {}
                }
            })
        }

        function init_contacts(id) {
            $.ajax({
                url: encodeURI("{{ route('crm.list.view', ["type" => $v, "id" => $detail->id]) }}?a=contact"),
                type: "get",
                dataType: "json"
            }).then(function(resp) {
                var tagify = new Tagify(document.querySelector(id), {
                    whitelist: resp,
                    enforceWhitelist: true,
                    dropdown: {
                        maxItems: 20, // <- mixumum allowed rendered suggestions
                        classname: "", // <- custom classname for this dropdown, so it could be targeted
                        enabled: 0, // <- show suggestions on focus
                        closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
                    },
                    callbacks: {
                        "change": function(e) {}
                    }
                })
            })
        }

        function init_comp() {
            $("#company_name").autocomplete({
                source: encodeURI("{{ route('crm.list.index') }}?a=contact"),
                minLength: 1,
                appendTo: "#autocomplete-div",
                response: function(event, ui) {
                    console.log(event)
                },
                select: function(event, ui) {
                    $("#comp_id").val(ui.item.id)
                    $("#btn-add-company").show()
                }
            });
        }

        function init_contact() {
            $("#pic_names").autocomplete({
                source: encodeURI("{{ route('crm.list.index') }}?a=contact"),
                minLength: 1,
                appendTo: "#autocomplete-div-contact",
                select: function(event, ui) {
                    $("#con_id").val(ui.item.id)
                }
            });
        }

        function add_contact(item) {
            var el = `<div class="d-flex flex-column mb-3">
                <span>${item.name}</span>
                <span class="text-danger cursor-pointer" onclick="remove_instruct(this)">Hapus</span>
                <input type="hidden" name="contacts[]" value="${item.id}">
            </div>`

            $("#div-contact").append(el)
            $("#pic_names").val("")
        }

        function remove_instruct(me) {
            $(me).parent().remove()
            var inp = $("#div-contact").find("input[name='contacts[]']")
            if (inp.length == 0) {
                $("#pic_names").prop("required", true)
            }

            var ins = $("#div-instruct").find("input[name='instruct[]']")
            if (ins.length == 0) {
                $("#instruct_to").prop("required", true)
            }
        }

        function validate_form(form) {
            var f = document.getElementById(form)
            var requireds = $(f).find(":required")
            var fields = {}
            requireds.each(function() {
                var _name = $(this).attr("name")
                var _row = $(this).parents("div.fv-row")
                var _label = $(_row).find('label')
                var attr = {
                    validators: {
                        notEmpty: {
                            message: `${_label.text()} harus diisi`
                        }
                    }
                }
                fields[_name] = attr
            })

            var validator = FormValidation.formValidation(f, {
                fields: fields,
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            })

            $(f).find("[data-control=select2]").on("change", function() {
                // Revalidate the field when an option is chosen
                if ($(this).attr("id") == "instruct_to") {
                    var ins = $("#div-instruct").find("input[name='instruct[]']")
                    console.log(ins)
                    if (ins.length > 0) {
                        var _row = $(this).parents("div.fv-row")
                        var _fv = $(_row).find("div.fv-plugins-message-container")
                        _fv.html("")
                    }
                } else if ($(this).attr("id") == "products") {
                    var ins = $("#div-products").find("input[name='products_id[]']")
                    console.log(ins)
                    if (ins.length > 0) {
                        var _row = $(this).parents("div.fv-row")
                        var _fv = $(_row).find("div.fv-plugins-message-container")
                        _fv.html("")
                    }
                } else {
                    validator.revalidateField($(this).attr("name"));
                }
            });

            validator.validate().then(function(resp) {
                if (resp == "Valid") {
                    $(`#${form}`).submit()
                }
            })
        }

        function getTag(){
            return $.ajax({
                url: encodeURI("{{ route('crm.list.index') }}?a=contact"),
                type: "get",
                dataType: "json"
            })
        }

        $(document).ready(function() {
            getTag().then(function(resp){
                $(".tag").each(function(){
                    var _id = $(this).attr("id")
                    init_tag(_id, resp.tags)
                })
            })
            init_comp()
            init_contact()

            $("#company_name").on("change keyup", function() {
                if ($(this).val() != "") {
                    $("#btn-add-company").show()
                } else {
                    $("#btn-add-company").hide()
                }
            })

            $("#pic_names").on("keyup change", function() {
                if ($(this).val() != "") {
                    $("#btn-add-contact").show()
                } else {
                    $("#btn-add-contact").hide()
                }
            })

            $("#btn-add-company").click(function() {
                $.ajax({
                    url: "{{ route('crm.company.add') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        company_name: $("#company_name").val(),
                        comp_id: $("#comp_id").val()
                    }
                }).then(function(resp) {
                    $("#btn-add-company").hide()
                    $("#comp_id").val(resp.id)
                    $("#company_name").prop("required", false)
                })
            })

            $("#btn-add-contact").click(function() {
                $.ajax({
                    url: "{{ route('crm.contact.add') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: $("#pic_names").val(),
                        con_id: $("#con_id").val()
                    }
                }).then(function(resp) {
                    add_contact(resp)
                    $("#btn-add-contact").hide()
                    $("#pic_names").prop("required", false)
                })
            })

            $("#instruct_to").change(function() {
                if ($(this).val() != "") {
                    var opt = $("#instruct_to option:selected")
                    var el = `<div class="d-flex flex-column mb-3">
                            <span>${opt.text()}</span>
                            <span class="text-danger cursor-pointer" onclick="remove_instruct(this)">Hapus</span>
                            <input type="hidden" name="instruct[]" value="${$(this).val()}">
                        </div>`

                    $("#div-instruct").append(el)
                    $("#instruct_to").val("").trigger("change")
                    $("#instruct_to").prop("required", false)
                }
            })

            $("#products").change(function() {
                if ($(this).val() != "") {
                    var opt = $("#products option:selected")
                    var el = `<div class="d-flex border rounded p-3 mb-3 justify-content-between align-items-center">
                            <div class="d-flex">
                                <i class="ki-duotone ki-cube-3 fs-2 me-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                <span>${opt.text()}</span>
                            </div>
                            <span class="text-danger cursor-pointer" onclick="remove_instruct(this)">Hapus</span>
                            <input type="hidden" name="products_id[]" value="${$(this).val()}">
                        </div>`

                    $("#div-products").append(el)
                    $("#products").val("").trigger("change")
                    $("#products").prop("required", false)
                }
            })

            $("#btn-simpan").click(function() {
                validate_form("form-lead")
            })

            init_contacts("#notes_persons")
            init_contacts("#task_persons")
        })
    </script>
@endsection
