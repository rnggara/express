@extends('_ess.layout', [
    'noMenu' => true
])

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
                <span class="fs-3 fw-bold">Orientasi</span>
                <span class="text-muted">Daftar orientasi untuk Anda</span>
            </div>
        </div>
        <div>
            <a href="https://ess.kerjaku.cloud" class="btn btn-sm btn-primary">
                <i class="fi fi-rr-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>
    <div class="d-flex flex-column">
        <div class="card card-p-0">
            <div class="card-body py-5">
                <div class="d-flex">
                    <ul class="nav border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
                        <li class="nav-item w-md-200px me-0">
                            <a class="nav-link text-active-primary text-dark active" data-bs-toggle="tab" href="#onboarding_upload">
                                <i class="fi fi-rr-caret-right"></i>
                                Unggah Dokumen
                            </a>
                        </li>
                        <li class="nav-item w-md-200px me-0">
                            <a class="nav-link text-active-primary text-dark" data-bs-toggle="tab" href="#onboarding_download">
                                <i class="fi fi-rr-caret-right"></i>
                                Unduh Dokumen
                            </a>
                        </li>
                        <li class="nav-item w-md-200px">
                            <a class="nav-link text-active-primary text-dark" data-bs-toggle="tab" href="#onboarding_task">
                                <i class="fi fi-rr-caret-right"></i>
                                Tugas
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content bg-secondary-crm rounded p-5 flex-fill" id="myTabContent">
                        <div class="tab-pane fade show active" id="onboarding_upload" role="tabpanel">
                            <div class="d-flex flex-column">
                                @foreach ($brDetail->where("type", "upload_file") as $item)
                                    @php
                                        $form = $item['detail'];
                                    @endphp
                                    <form action="{{ route("personel.onboarding.update") }}" class="mb-3" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item['id'] }}">
                                        <input type="hidden" name="type" value="upload_file">
                                        <div class="fv-row">
                                            <label class="col-form-label w-100">{{ $form->form_name }}</label>
                                            <div class="d-flex align-items-center mb-3">
                                                <label class="btn btn-{{ empty($item['file_name']) ? 'secondary' : "primary" }}">
                                                    <input type="file" name="attachment" onchange="onboardAction('upload_file', '{{ $item['id'] }}', this)" accept="{{ $form->file_format }}" data-attachment class="d-none">
                                                    {{ $item['file_name'] ?? "Attachment" }}
                                                    <i class="fi fi-rr-clip"></i>
                                                </label>
                                                <div class="ms-5 text-{{ empty($item['action_at']) ? "danger" : "success" }}">
                                                    @if (empty($item['action_at']))
                                                        Deadline on {{ date("d-m-Y", strtotime($item['due_date'])) }}
                                                    @else
                                                        <i class="fi fi-sr-check-circle text-success"></i>
                                                        Uploaded on {{ date("d-m-Y", strtotime($item['action_at'])) }}
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="text-muted">Max 2mb {{ strtoupper(str_replace(".", "", $form->file_format)) }}</span>
                                        </div>
                                    </form>
                                @endforeach
                            </div>
                        </div>
        
                        <div class="tab-pane fade" id="onboarding_download" role="tabpanel">
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
                            @endforeach
                        </div>
        
                        <div class="tab-pane fade" id="onboarding_task" role="tabpanel">
                            @foreach ($brDetail->where("type", "task") as $item)
                                @php
                                    $form = $item['detail'];
                                @endphp
                                <form action="" class="mb-3" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                                    <input type="hidden" name="type" value="task">
                                    <div class="d-flex align-items-start rounded border bg-white p-5">
                                        <div class="d-flex flex-column">
                                            <span class="fs-3">{{ $form->form_name }}</span>
                                            <span class="text-muted my-3">{{ $form->descriptions }}</span>
                                            <div class="fv-row">
                                                <div class="d-flex align-items-center mb-3">
                                                    <label class="btn btn-{{ empty($item['file_name']) ? 'secondary' : "primary" }}">
                                                        <input type="file" name="attachment" onchange="onboardAction('task', '{{ $item['id'] }}', this)" accept=".pdf, .jpg, .png" data-attachment class="d-none">
                                                        {{ $item['file_name'] ?? "Attachment" }}
                                                        <i class="fi fi-rr-clip"></i>
                                                    </label>
                                                </div>
                                                <span class="text-muted">5MB PDF, JPG, PNG</span>
                                            </div>
                                            <ol class="breadcrumb breadcrumb-dot text-muted fs-6 fw-semibold my-3">
                                                <li class="breadcrumb-item"><span class="fi fi-rr-user"></span></li>
                                                <li class="breadcrumb-item"><span class="">{{ $pic_name[$form->pic] ?? "" }}</span></li>
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
                                    </div>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="form-detail" method="post">
</div>

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
                            var btn = $(me).parents("label.btn").eq(0)
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

            @if(!empty($_GET['t']))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ $_GET['t'] }}']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif
        })

    </script>
@endsection
