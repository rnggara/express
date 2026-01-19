@extends('_personel.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center mb-5">
            <div class="symbol symbol-50px me-3">
                <div class="symbol-label bg-primary">
                    <i class="fi fi-sr-handshake text-white fs-1"></i>
                </div>
            </div>
            <div class="d-flex flex-column">
                <span class="fs-3 fw-bold">Pendaftaran Orientasi</span>
                <span class="text-muted">&nbsp;&nbsp;</span>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-primary" onclick="addForm()" data-bs-toggle="modal" data-bs-target="#modal_add">
                <i class="fa fa-plus"></i>
                Tambah Pengguna
            </button>
        </div>
    </div>
    <div class="d-flex flex-column">
        <div class="card bg-secondary-crm">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center mb-5">
                        <div class="position-relative me-5">
                            <input type="text" class="form-control ps-12" placeholder="Cari" name="search_table">
                            <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                        </div>
                    </div>
                    <div class="scroll">
                        <table class="table table-display-2 bg-white" data-ordering="false" id="table-overtime">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Format Orientasi</th>
                                    <th>Unggal Berkas</th>
                                    <th>Unduh Berkas</th>
                                    <th>Tugas</th>
                                    <th>Aksi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($onboard as $item)
                                    @php
                                        $u = $udetail[$item->user_id] ?? [];
                                    @endphp
                                    @if (!empty($u))
                                        @php
                                            $upload_file = collect($odetail[$item->id]['upload_file'] ?? []);
                                            $download_file = collect($odetail[$item->id]['download_file'] ?? []);
                                            $task = collect($odetail[$item->id]['task'] ?? []);

                                            $upload_count = $upload_file->count();
                                            $download_count = $download_file->count();
                                            $task_count = $task->count();

                                            $upload_done = $upload_file->whereNotNull("action_at")->count();
                                            $download_done = $download_file->whereNotNull("action_at")->count();
                                            $task_done = $task->whereNotNull("action_at")->count();
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-40px me-3">
                                                        <div class="symbol-label" style="background-image: url('{{ asset($u->user_img ?? "images/image_placeholder.png") }}')"></div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold">{{ $u->name }}</span>
                                                        <span>{{ $u->emp->emp_id ?? "-" }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $tname[$item->template_id] ?? "Custom" }}
                                            </td>
                                            <td>
                                                <span class="text-{{ $upload_done == $upload_count ? "success" : "danger" }}">{{ $upload_done }}/{{ $upload_count }}</span>
                                            </td>
                                            <td>
                                                <span class="text-{{ $download_done == $download_count ? "success" : "danger" }}">{{ $download_done }}/{{ $download_count }}</span>
                                            </td>
                                            <td>
                                                <span class="text-{{ $task_done == $task_count ? "success" : "danger" }}">{{ $task_done }}/{{ $task_count }}</span>
                                            </td>
                                            <td>
                                                @if (empty($item->last_notify) || $item->last_notify != date("Y-m-d"))
                                                    <a href="javascript:;" onclick="onboardAction('notif', '{{ $item->id }}', this)"><u>Notifikasi Pengguna</u></a>
                                                @else
                                                    <a href="javascript:;" class="text-secondary"><u>Notifikasi Pengguna</u></a>
                                                @endif
                                                <a href="javascript:;" class="text-dark {{ empty($item->last_notify) || $item->last_notify != date("Y-m-d") ? 'd-none' : "" }}" data-bs-toggle="tooltip" title="Anda bisa mengirim notifikasi sekali setiap hari. Fungsi ini akan aktif lagi besok"><i class="fi fi-rr-info"></i></a>
                                            </td>
                                            <td>
                                                <button type="button" onclick="editDetail({{ $item->id }})" class="btn btn-icon btn-sm p-0">
                                                    <i class="fa fa-ellipsis-v text-dark"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="form-detail" method="post">
</div>

<form action="{{ route("personel.onboarding.store") }}" method="post">
    @component('layouts._crm_modal', [
        "modalId" => "modal_add",
        "modalSize" => "modal-lg"
    ])
        @slot('modalTitle')
            <div class="d-flex align-items-center mb-5">
                <div class="symbol symbol-50px me-3">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-sr-add text-primary fs-1"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <span class="fs-3 fw-bold">Tambah Orientasi</span>
                    <span class="text-muted">&nbsp;&nbsp;</span>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <div class="fv-row">
                <label class="col-form-label required">Nama Pegawai</label>
                <select name="user_id" class="form-select" data-control="select2" data-dropdown-parent="#modal_add" data-placeholder="Pilih Pegawai" required id="">
                    <option value=""></option>
                    @foreach ($users->whereNotIn("id", $onboard->pluck("user_id")) as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fv-row d-flex align-items-center mt-5">
                <div class="nav-link form-check form-check-custom active">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="template" checked value="template"/>
                        Onboarding Template
                    </label>
                </div>
                <div class="nav-link form-check form-check-custom active">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="template" value="custom"/>
                        Custom Form
                    </label>
                </div>
            </div>
            <div class="fv-row">
                <label class="col-form-label required">Template orientasi yang akan digunakan</label>
                <select name="obtemplate" class="form-select" data-control="select2" data-dropdown-parent="#modal_add" data-placeholder="Pilih Template Orientasi" id="">
                    <option value=""></option>
                    @foreach ($templates as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fv-row">
                <label class="col-form-label required">Tambah Formulir</label>
                <table class="table table-display-2 bg-white" id="form-sel" data-ordering="false" data-paging="false">
                    <thead>
                        <tr>
                            <th>Tipe Form</th>
                            <th>Nama Form</th>
                            <th>Tanggal Akhir</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-center">
                                <button type="button" class="btn btn-primary" data-bs-stacked-modal="#modal_form">
                                    Tambah Form
                                </button>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="submit" value="store" class="btn btn-primary">Kirim</button>
        @endslot
    @endcomponent
</form>

<form action="{{ route("personel.onboarding.store") }}" id="form-form" method="post" enctype="multipart/form-data">
    @component('layouts._crm_modal', [
        "modalId" => "modal_form",
        "modalSize" => "modal-lg"
    ])
        @slot('modalTitle')
            <div class="d-flex flex-column">
                <span class="fs-1 fw-bold">Tambah Formulir</span>
                <span class="fs-base fw-normal mt-2">&nbsp;&nbsp;</span>
            </div>
        @endslot
        @slot('modalContent')
            <ul class="fs-6 mb-5 nav">
                <li class="nav-item">
                    <div class="nav-link form-check form-check-custom active" data-bs-toggle="tab" href="#kt_tab_pane_0">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="form_type" checked value="from_database"/>
                            Dari Basis Data
                        </label>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="nav-link form-check form-check-custom" data-bs-toggle="tab" href="#kt_tab_pane_1">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="form_type" value="upload_file"/>
                            Unggah Berkas
                        </label>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="nav-link form-check form-check-custom" data-bs-toggle="tab" href="#kt_tab_pane_2">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="form_type" value="download_file"/>
                            Unduh Berkas
                        </label>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="nav-link form-check form-check-custom" data-bs-toggle="tab" href="#kt_tab_pane_3">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="form_type" value="task"/>
                            Tugas
                        </label>
                    </div>
                </li>
            </ul>

            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_tab_pane_0" role="tabpanel">
                    <div class="d-flex flex-column">
                        <span class="text-muted my-3">Jika Anda pilih “Dari basis data”, anda akan memakai formular yang telah anda atur sebelumnya.</span>
                        <span class="mt-5 fw-bold required">Form</span>
                        <table class="table bg-white" data-ordering="false" id="table-form">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""/>
                                        </div>
                                    </th>
                                    <th>Tipe Form</th>
                                    <th>Nama Form</th>
                                    <th>Tanggal Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($forms as $item)
                                    <tr data-template-form>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $item->id }}"/>
                                            </div>
                                        </td>
                                        <td>{{ ucwords(str_replace("_", " ", $item->form_type)) }}</td>
                                        <td>{{ $item->form_name }}</td>
                                        <td>{{ $item->due_date }} hari</td>
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
                        <label class="col-form-label">Due Date (hari)</label>
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
                        <label class="col-form-label">Due Date (hari)</label>
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
                            <label class="col-form-label">Due Date (hari)</label>
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
            <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
            <button type="button" name="submit" value="store" class="btn btn-primary" data-target="">Tambah</button>
        @endslot
    @endcomponent
</form>

<form action="{{ route("personel.onboarding.detail_update") }}" method="post">
    @component('layouts._crm_modal', [
        "modalId" => "modal_add_detail_form",
        "modalSize" => "modal-lg"
    ])
        @slot('modalTitle')
            <div class="d-flex align-items-center mb-5">
                <div class="symbol symbol-50px me-3">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-sr-add text-primary fs-1"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <span class="fs-3 fw-bold">Add Form</span>
                    <span class="text-muted">&nbsp;&nbsp;</span>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <div class="fv-row">
                <label class="col-form-label required">Add Form</label>
                <table class="table table-display-2 bg-white" id="form-sel-detail" data-ordering="false" data-paging="false">
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
            <input type="hidden" name="id">
            <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
            <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
        @endslot
    @endcomponent
</form>

@endsection

@section('view_script')
    <script>

        var tb_loc

        var form_sel = []

        function addFormDetail(id){
            $("#modal_add_detail_form input[name=id]").val(id)
        }

        function onboardAction(type, id, me){
            if(type == "upload_file"){
                var form = $(me).parents("form")
                var fileuploaddata = new FormData($(form)[0])
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();

                        xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);

                            if (percentComplete === 100) {

                            }

                        }
                        }, false);

                        return xhr;
                    },
                    url: "{{ route("personel.onboarding.update") }}",
                    type: "POST",
                    data: fileuploaddata,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(result) {
                        if(result.success){
                            var btn = $(me).parents("label.btn").eq(0)
                            console.log($.parseHTML(me))
                            var el = ` ${me.files[0].name} <i class="fi fi-rr-clip"></i>`
                            $(btn).html($(me))
                            $(btn).append(el)
                            $(btn).removeClass("btn-secondary")
                            $(btn).addClass("btn-primary")
                            var lbl = $(btn).next()
                            $(lbl).html(`<i class="fi fi-sr-check-circle text-success"></i> Uploaded on ${result.data}`)
                            $(lbl).removeClass("text-danger")
                            $(lbl).addClass("text-success   ")
                        }
                    }
                });
            } else if(type == "download_file"){
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
            } else if(type == "task"){
                var form = $(me).parents("form")
                var fileuploaddata = new FormData($(form)[0])
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();

                        xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);

                            if (percentComplete === 100) {

                            }

                        }
                        }, false);

                        return xhr;
                    },
                    url: "{{ route("personel.onboarding.update") }}",
                    type: "POST",
                    data: fileuploaddata,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(result) {
                        if(result.success){
                            var btn = $(me).parents("label.btn")
                            console.log()
                            $(btn).html("")
                            var el = ` ${me.files[0].name} <i class="fi fi-rr-clip"></i>`
                            $(btn).append($(me))
                            $(btn).append(el)
                            $(btn).removeClass("btn-secondary")
                            $(btn).addClass("btn-primary")
                            var lbl = $(btn).parents("div.flex-column").find("li.text-danger")
                            $(lbl).html(`<i class="fi fi-sr-check-circle text-success"></i> Uploaded on ${result.data}`)
                            $(lbl).removeClass("text-danger")
                            $(lbl).addClass("text-success")
                        }
                    }
                });
            } else if(type == "task_appr"){
                $.ajax({
                    url: "{{ route("personel.onboarding.update") }}",
                    type: "POST",
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : id,
                        type : type,
                        checked : $(me).prop("checked")
                    },
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
            } else if(type == "notif"){
                $.ajax({
                    url: "{{ route("personel.onboarding.update") }}",
                    type: "POST",
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : id,
                        type : type,
                    },
                    dataType: "json",
                    success: function(result) {
                        if(result.success){
                            $(me).attr("onclick", "")
                            $(me).addClass("text-secondary")
                            $(me).next().removeClass("d-none")
                        }
                    }
                })
            }
        }

        function selectedForm(data){
            $("#table-form [data-template-form]").removeClass("d-none")
            $("#table-form [data-template-form]").each(function(){
                var ck = parseInt($(this).find("input[type=checkbox]").val())
                if(data.includes(ck)){
                    $(this).addClass("d-none")
                }
            })
        }

        function editDetail(id){
            $("#form-detail").html("")
            $.ajax({
                url : "{{ route("personel.onboarding.index") }}?a=detail",
                type : "get",
                dataType : "json",
                data : {
                    id : id
                }
            }).then(function(resp){
                $("#form-detail").html(resp.view)

                var br = resp.br
                var fsel = []
                for (let i = 0; i < br.length; i++) {
                    const element = br[i];
                    fsel.push(element.form_id)
                }

                selectedForm(fsel)

                $("#modal_detail").modal("show")
                $("#modal_detail [data-bs-stacked-modal]").click(function(){
                    var t = $(this).data("bs-stacked-modal")
                    $(t).modal("show")
                })

                modalForm("#form-sel-detail")

                $("#modal_add_detail_form [data-bs-stacked-modal]").click(function(){
                    var t = $(this).data("bs-stacked-modal")
                    $(t).css("z-index", 10000000)
                })
            })
        }

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

        function loadTemplate(id){
            return $.ajax({
                url : "{{ route("personel.onboarding.index") }}?a=template",
                type : "get",
                dataType : "json",
                data : {
                    id : id
                }
            }).then(function(resp){
                form_sel = resp.data
            })
        }

        function addForm(){
            modalForm("#form-sel")
            selectedForm([])
        }

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

            var rows = tb_loc.rows().data()
            for (let i = 0; i < rows.length; i++) {
                const element = rows[i];
                var dom_nodes = $($.parseHTML(element[0]));
                var ck = $(dom_nodes).find("input[type=checkbox]").val()
                var ind = form_sel.findIndex((obj) => obj.id == ck)
                if(ind != -1){
                    tb_loc.row(i).select()
                }
            }

            tableFn("#table-form", tb_loc)
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
                            col['due_date'] = element.due_date + " hari"
                            form_sel.push(col)

                            renderForms(target)

                            $("#modal_form").modal("hide")
                        }
                    })
                }
            })
        }


        $(document).ready(function(){

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

            $("#modal_add input[name=template]").click(function(){
                console.log($(this).val())
                $("#modal_add select[name=obtemplate]").parents("div.fv-row").removeClass("d-none")
                if($(this).val() == "custom"){
                    $("#modal_add select[name=obtemplate]").parents("div.fv-row").addClass("d-none")
                }
            })

            $("#modal_add select[name=obtemplate]").change(function(){
                if($(this).val() != ""){
                    loadTemplate($(this).val()).then(function(resp){
                        renderForms("#form-sel")
                    })
                }
            })
        })

    </script>
@endsection
